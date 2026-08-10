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

		wp_cache_flush();
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
