<?php
/**
 * Integration tests for the block-editor enablement (Phase 2).
 *
 * Covers COURIER-1035 — REST exposure of the CPT, all five taxonomies and
 * the five meta keys, gated behind the per-site opt-in — and the
 * COURIER-1034 blocker: a notice created without an explicit scope now
 * defaults to global instead of becoming invisible.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Model\Courier_Notice\Data;
use WP_REST_Request;
use WP_UnitTestCase;

/**
 * Class BlockEditorTest
 */
final class BlockEditorTest extends WP_UnitTestCase {

	/**
	 * Boot a REST server with the plugin's routes and clear plugin caches.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		global $wp_rest_server;

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Core's own REST server global; this is how the WP test suite boots REST.
		$wp_rest_server = new \WP_REST_Server();
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
		do_action( 'rest_api_init', $wp_rest_server );

		// The WP test suite wipes registered meta between tests; on a real
		// site init re-registers it every request. Mirror that here.
		( new \CourierNotices\Controller\Courier_Notices() )->register_custom_post_type();

		$this->restore_default_terms();

		wp_cache_flush();
	}

	/**
	 * Recreate the registered default terms this class depends on.
	 *
	 * register_taxonomy() creates a taxonomy's default_term and records its ID
	 * in the `default_term_{$taxonomy}` option — but only once, at init. The
	 * suite's tear_down_after_class() then runs _delete_all_data(), which
	 * DELETEs every term and commits, so those bootstrap-created terms are
	 * gone for every test class after the first. Courier_Notices::
	 * register_taxonomies() cannot restore them either: it is guarded by
	 * taxonomy_exists(), which is still true.
	 *
	 * Without this, the two default-term assertions below passed only because
	 * this class happened to sort first among the integration tests — adding
	 * any test file that sorts earlier broke them, which is exactly what
	 * BlockBindingsTest did.
	 *
	 * @return void
	 */
	private function restore_default_terms(): void {
		$defaults = array(
			'courier_scope'     => array( 'Global', 'global' ),
			'courier_placement' => array( 'Header', 'header' ),
		);

		foreach ( $defaults as $taxonomy => $term ) {
			list( $name, $slug ) = $term;

			$existing = term_exists( $slug, $taxonomy );

			if ( null === $existing ) {
				$existing = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}

			if ( is_wp_error( $existing ) || ! isset( $existing['term_id'] ) ) {
				continue;
			}

			update_option( 'default_term_' . $taxonomy, (int) $existing['term_id'] );
		}
	}

	/**
	 * The block editor stays off until the per-site opt-in flips it, and
	 * the courier_notices_use_block_editor filter has the final word.
	 *
	 * @return void
	 */
	public function test_block_editor_is_gated_by_the_setting(): void {
		$this->assertFalse(
			apply_filters( 'use_block_editor_for_post_type', true, 'courier_notice' ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
			'The block editor must be opt-in while Phase 2 lands.'
		);

		update_option( 'courier_settings', array( 'enable_block_editor' => true ) );

		$this->assertTrue(
			apply_filters( 'use_block_editor_for_post_type', false, 'courier_notice' ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
			'The setting must turn the block editor on.'
		);

		$this->assertTrue(
			apply_filters( 'use_block_editor_for_post_type', true, 'post' ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
			'Other post types must be left alone.'
		);
	}

	/**
	 * The CPT, all five taxonomies, and all five meta keys are exposed to
	 * REST — the surface the block editor reads and writes.
	 *
	 * @return void
	 */
	public function test_notice_surface_is_exposed_to_rest(): void {
		$post_type = get_post_type_object( 'courier_notice' );

		$this->assertTrue( $post_type->show_in_rest );
		$this->assertSame( 'courier-notices', $post_type->rest_base );
		$this->assertContains( 'custom-fields', get_all_post_type_supports( 'courier_notice' ) ? array_keys( get_all_post_type_supports( 'courier_notice' ) ) : array(), 'custom-fields is load-bearing for REST meta.' );

		foreach ( array( 'courier_scope', 'courier_type', 'courier_style', 'courier_placement', 'courier_status' ) as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );

			$this->assertTrue( $taxonomy_object->show_in_rest, "{$taxonomy} must be visible to REST." );
			$this->assertNotEmpty( $taxonomy_object->rest_base );
			$this->assertContains( 'courier_notice', $taxonomy_object->object_type );
		}

		$registered_meta = get_registered_meta_keys( 'post', 'courier_notice' );

		foreach ( array( '_courier_dismissible', '_courier_show_title', '_courier_hide_title', '_courier_expiration', '_courier_sender' ) as $meta_key ) {
			$this->assertArrayHasKey( $meta_key, $registered_meta, "{$meta_key} must be registered." );
			$this->assertNotEmpty( $registered_meta[ $meta_key ]['show_in_rest'], "{$meta_key} must be exposed to REST." );
		}
	}

	/**
	 * New notices open inside a locked wrapper the author cannot remove or
	 * move — the notice-shaped canvas from COURIER-1037 — and the root itself
	 * takes nothing else.
	 *
	 * The root lock is a reversal, per Aaron (2026-08-11): the per-block
	 * move/remove lock still left the root inserter open, so an author could
	 * drop a sibling paragraph at the root, where it serializes into
	 * post_content outside the notice and is silently dropped by render.php
	 * and the legacy wp_kses_post path. A notice IS the block, so the root
	 * accepts only the block. Composition INSIDE it is still governed by the
	 * layout, which passes its own templateLock explicitly — see
	 * test_the_root_lock_does_not_reach_inside_the_notice_block().
	 *
	 * @return void
	 */
	public function test_the_cpt_template_is_a_locked_notice_block(): void {
		$post_type = get_post_type_object( 'courier_notice' );

		$this->assertIsArray( $post_type->template );

		list( $block_name, $attributes ) = $post_type->template[0];

		$this->assertSame( 'courier/notice', $block_name );
		$this->assertTrue( $attributes['lock']['remove'], 'The notice block must not be removable.' );
		$this->assertTrue( $attributes['lock']['move'], 'The notice block must not be movable.' );
		$this->assertSame( 'all', $post_type->template_lock, 'The root block list must not accept anything but the notice block.' );
		$this->assertTrue( \WP_Block_Type_Registry::get_instance()->is_registered( 'courier/notice' ), 'The courier/notice block must be registered.' );
	}

	/**
	 * The root lock must reach the root block list only.
	 *
	 * Core hands template_lock to the editor as `templateLock`
	 * (wp-admin/edit-form-blocks.php), and inner block lists inherit it only
	 * when they do not set their own. courier/notice always passes an
	 * explicit value per layout — 'all' for informational, false for robust
	 * and popup-modal — so inheritance never applies and a robust notice
	 * stays free-form. This pins the block.json side of that contract; the
	 * explicit pass lives in src/blocks/notice/index.js.
	 *
	 * @return void
	 */
	public function test_the_root_lock_does_not_reach_inside_the_notice_block(): void {
		$block_type = \WP_Block_Type_Registry::get_instance()->get_registered( 'courier/notice' );

		$this->assertNotNull( $block_type );

		// The layout attribute is what the edit component switches the inner
		// templateLock on, so it has to survive as a real attribute.
		$this->assertArrayHasKey( 'layout', $block_type->attributes );
		$this->assertSame( 'informational', $block_type->attributes['layout']['default'] );

		// A dynamic block: render.php owns the front-end wrapper, so the
		// block must not also declare a templateLock that would freeze the
		// inner list regardless of layout.
		$this->assertArrayNotHasKey(
			'templateLock',
			$block_type->attributes,
			'templateLock must stay a runtime decision per layout, not a fixed attribute.'
		);
	}

	/**
	 * COURIER-1034, the Phase 2 blocker: a notice created without an
	 * explicit scope — exactly what a block-editor REST save does — gets
	 * the global scope by default and is visible to the queries.
	 *
	 * @return void
	 */
	public function test_a_notice_created_without_scope_defaults_to_global(): void {
		// Core only applies default terms when the acting user can assign
		// them — which every block-editor save does. A user-0 programmatic
		// insert still skips the default; courier_notices_add_notice() sets
		// scope explicitly, so that path is unaffected.
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'editor' ) ) );

		$notice_id = wp_insert_post(
			array(
				'post_type'    => 'courier_notice',
				'post_status'  => 'publish',
				'post_title'   => 'Editor-created notice',
				'post_content' => 'Body',
			)
		);

		$this->assertTrue( has_term( 'global', 'courier_scope', $notice_id ), 'The default scope must be assigned on create.' );
		$this->assertTrue( has_term( 'header', 'courier_placement', $notice_id ), 'A notice with no explicit placement must deliver to the header - the placement queries never see a term-less notice.' );

		$global_ids = ( new Data() )->get_global_notices(
			array(
				'ids_only'  => true,
				'placement' => '',
			)
		);

		$this->assertContains( $notice_id, $global_ids, 'A scopeless notice used to be invisible to get_global_notices().' );
	}

	/**
	 * An explicitly user-scoped notice keeps its scope — the default must
	 * never stomp a deliberate choice, which is why the old force-global-
	 * on-every-save behavior was not replicated.
	 *
	 * @return void
	 */
	public function test_an_explicit_scope_is_not_stomped(): void {
		$notice_id = self::factory()->post->create( array( 'post_type' => 'courier_notice' ) );

		wp_set_object_terms( $notice_id, 'User', 'courier_scope', false );
		wp_update_post(
			array(
				'ID'         => $notice_id,
				'post_title' => 'Edited title',
			)
		);

		$this->assertTrue( has_term( 'user', 'courier_scope', $notice_id ) );
		$this->assertFalse( has_term( 'global', 'courier_scope', $notice_id ), 'Editing must not force the notice back to global.' );
	}

	/**
	 * The full block-editor round trip over REST: create a notice with meta
	 * and read it back.
	 *
	 * @return void
	 */
	public function test_rest_create_round_trip_with_meta(): void {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$request = new WP_REST_Request( 'POST', '/wp/v2/courier-notices' );
		$request->set_body_params(
			array(
				'title'   => 'REST notice',
				'content' => 'REST body',
				'status'  => 'publish',
				'meta'    => array(
					'_courier_dismissible' => true,
					'_courier_expiration'  => time() + DAY_IN_SECONDS,
				),
			)
		);

		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 201, $response->get_status() );

		$notice_id = $response->get_data()['id'];

		$this->assertTrue( (bool) get_post_meta( $notice_id, '_courier_dismissible', true ) );
		$this->assertGreaterThan( time(), (int) get_post_meta( $notice_id, '_courier_expiration', true ) );
		$this->assertTrue( has_term( 'global', 'courier_scope', $notice_id ), 'A REST-created notice must default to global scope.' );
	}
}
