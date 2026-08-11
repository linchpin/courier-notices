<?php
/**
 * Integration tests for the EXISTS-sensitive notice meta (COURIER-1037).
 *
 * The Notice panel's Dismissible toggle and Expires picker write meta the
 * display queries branch on by row existence, not by value. The block editor
 * posts its whole meta object on every save, so clearing a control arrives as
 * `false` / `0` — and a stored falsy row inverts both queries. These tests pin
 * the deletion contract and its user-visible consequence, plus the Action
 * Scheduler rescheduling that only works because meta is normalized after the
 * REST write rather than during save_post.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Controller\Action_Scheduler;
use CourierNotices\Model\Courier_Notice\Data;
use WP_REST_Request;
use WP_UnitTestCase;

/**
 * Class NoticeMetaTest
 */
final class NoticeMetaTest extends WP_UnitTestCase {

	/**
	 * Boot a REST server with the plugin's routes, re-register the meta the
	 * suite wipes between tests, and clear the plugin's caches.
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

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		wp_cache_flush();
	}

	/**
	 * Create a published global header notice.
	 *
	 * @param array $meta Meta to seed, as the classic path would write it.
	 *
	 * @return int
	 */
	private function create_notice( array $meta = array() ): int {
		$post_id = self::factory()->post->create(
			array(
				'post_type'    => 'courier_notice',
				'post_status'  => 'publish',
				'post_content' => 'Notice body',
			)
		);

		wp_set_object_terms( $post_id, 'Global', 'courier_scope', false );
		wp_set_object_terms( $post_id, 'Header', 'courier_placement', false );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		return $post_id;
	}

	/**
	 * Save meta over REST the way the block editor does — the whole object,
	 * including the keys the author just cleared.
	 *
	 * @param int   $notice_id Notice to update.
	 * @param array $meta      Meta payload.
	 *
	 * @return \WP_REST_Response
	 */
	private function rest_save_meta( int $notice_id, array $meta ) {
		$request = new WP_REST_Request( 'POST', '/wp/v2/courier-notices/' . $notice_id );
		$request->set_body_params( array( 'meta' => $meta ) );

		return rest_get_server()->dispatch( $request );
	}

	/**
	 * A cleared Dismissible toggle deletes the row instead of storing '', so
	 * the EXISTS query at Data.php:167 stops matching it.
	 *
	 * @return void
	 */
	public function test_a_cleared_dismissible_toggle_deletes_the_meta_row(): void {
		$notice_id = $this->create_notice( array( '_courier_dismissible' => 1 ) );

		$response = $this->rest_save_meta( $notice_id, array( '_courier_dismissible' => false ) );

		$this->assertSame( 200, $response->get_status() );
		$this->assertFalse(
			metadata_exists( 'post', $notice_id, '_courier_dismissible' ),
			'A cleared toggle must delete the row; a row holding "" reads as dismissible.'
		);
	}

	/**
	 * Enabling the toggle still stores a truthy row.
	 *
	 * @return void
	 */
	public function test_an_enabled_dismissible_toggle_stores_the_meta_row(): void {
		$notice_id = $this->create_notice();

		$this->rest_save_meta( $notice_id, array( '_courier_dismissible' => true ) );

		$this->assertTrue( metadata_exists( 'post', $notice_id, '_courier_dismissible' ) );
		$this->assertTrue( (bool) get_post_meta( $notice_id, '_courier_dismissible', true ) );
	}

	/**
	 * The consequence that matters: after clearing the toggle the notice is
	 * selected as persistent and no longer as dismissible.
	 *
	 * @return void
	 */
	public function test_a_cleared_dismissible_notice_reads_as_persistent(): void {
		$notice_id = $this->create_notice( array( '_courier_dismissible' => 1 ) );

		$this->rest_save_meta( $notice_id, array( '_courier_dismissible' => false ) );

		wp_cache_flush();
		$data = new Data();

		$this->assertContains(
			$notice_id,
			$data->get_persistent_global_notices( array( 'placement' => 'header' ) ),
			'A non-dismissible notice must be selected by the NOT EXISTS query.'
		);
		$this->assertNotContains(
			$notice_id,
			$data->get_dismissible_global_notices( array( 'placement' => 'header' ) ),
			'A non-dismissible notice must not be selected by the EXISTS query.'
		);
	}

	/**
	 * A cleared expiration deletes the row. A row holding '0' would defeat
	 * both halves of the expiry clause at Data.php:492 — NOT EXISTS fails
	 * because the row is there, and '0' >= now is false.
	 *
	 * @return void
	 */
	public function test_a_cleared_expiration_deletes_the_meta_row(): void {
		$notice_id = $this->create_notice( array( '_courier_expiration' => time() + DAY_IN_SECONDS ) );

		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => 0 ) );

		$this->assertFalse(
			metadata_exists( 'post', $notice_id, '_courier_expiration' ),
			'A cleared expiration must delete the row, not store 0.'
		);
	}

	/**
	 * The consequence that matters: clearing the expiration must not silently
	 * stop the notice from displaying.
	 *
	 * @return void
	 */
	public function test_a_notice_with_a_cleared_expiration_still_displays(): void {
		$notice_id = $this->create_notice( array( '_courier_expiration' => time() + DAY_IN_SECONDS ) );

		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => 0 ) );

		wp_cache_flush();

		$this->assertContains(
			$notice_id,
			( new Data() )->get_notices( array( 'number' => 10 ) ),
			'Clearing the expiration means "never expires", not "expired at the epoch".'
		);
	}

	/**
	 * A future expiration written over REST is stored and keeps displaying.
	 *
	 * @return void
	 */
	public function test_a_future_expiration_is_stored_and_still_displays(): void {
		$notice_id  = $this->create_notice();
		$expiration = time() + DAY_IN_SECONDS;

		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => $expiration ) );

		$this->assertSame( $expiration, (int) get_post_meta( $notice_id, '_courier_expiration', true ) );

		wp_cache_flush();

		$this->assertContains( $notice_id, ( new Data() )->get_notices( array( 'number' => 10 ) ) );
	}

	/**
	 * Action Scheduler reads _courier_expiration to schedule the expiry, but
	 * REST writes meta *after* wp_update_post fires save_post — so without the
	 * rest_after_insert pass the scheduler reads the previous value.
	 *
	 * @return void
	 */
	public function test_expiration_is_scheduled_from_the_meta_rest_just_wrote(): void {
		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			$this->markTestSkipped( 'Action Scheduler is not loaded.' );
		}

		$notice_id  = $this->create_notice();
		$expiration = time() + DAY_IN_SECONDS;

		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => $expiration ) );

		$this->assertSame(
			$expiration,
			as_next_scheduled_action(
				Action_Scheduler::EXPIRE_NOTICE_ACTION,
				array( $notice_id ),
				Action_Scheduler::SCHEDULER_GROUP
			),
			'The expiry must be scheduled from the timestamp this save wrote, not the previous one.'
		);
	}

	/**
	 * Clearing the expiration must also drop the scheduled action.
	 *
	 * @return void
	 */
	public function test_clearing_the_expiration_unschedules_the_expiry(): void {
		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			$this->markTestSkipped( 'Action Scheduler is not loaded.' );
		}

		$notice_id = $this->create_notice();

		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => time() + DAY_IN_SECONDS ) );
		$this->rest_save_meta( $notice_id, array( '_courier_expiration' => 0 ) );

		$this->assertFalse(
			as_next_scheduled_action(
				Action_Scheduler::EXPIRE_NOTICE_ACTION,
				array( $notice_id ),
				Action_Scheduler::SCHEDULER_GROUP
			),
			'A notice that never expires must not carry a scheduled expiry.'
		);
	}

	/**
	 * The classic metabox field holds a wall-clock string with no offset.
	 * WordPress pins PHP's default timezone to UTC, so the old bare
	 * strtotime() read it as UTC and a notice on a UTC-4 site expired four
	 * hours early. It is now read in the site timezone, matching both the
	 * block editor's picker and what the field renders back.
	 *
	 * @return void
	 */
	public function test_the_classic_expiration_field_is_read_in_the_site_timezone(): void {
		update_option( 'timezone_string', 'America/New_York' );

		$notice_id = $this->create_notice();

		$_POST['courier_notices_expiration_noncename'] = wp_create_nonce( 'courier_notices_expiration_nonce' );
		$_POST['courier_expire_date']                  = '2026-08-11 12:00:00';

		( new \CourierNotices\Controller\Courier() )->save_post_courier_notice( $notice_id, get_post( $notice_id ) );

		unset( $_POST['courier_notices_expiration_noncename'], $_POST['courier_expire_date'] );

		$stored = (int) get_post_meta( $notice_id, '_courier_expiration', true );

		// August 11 is EDT, UTC-4: noon in New York is 16:00 UTC.
		$this->assertSame(
			strtotime( '2026-08-11 16:00:00 UTC' ),
			$stored,
			'The field must be interpreted in the site timezone, not as UTC.'
		);

		// And it round-trips: the metabox renders the stored instant back as
		// the same wall-clock string the author typed.
		$this->assertSame( '2026-08-11 12:00:00', wp_date( 'Y-m-d H:i:s', $stored ) );
	}

	/**
	 * The classic metabox assigns the three delivery terms from submitted
	 * slugs, and ignores a slug that is not a real term. Pins the behavior of
	 * the loop that replaced three near-identical branches.
	 *
	 * @return void
	 */
	public function test_the_classic_save_assigns_only_real_delivery_terms(): void {
		$notice_id = $this->create_notice();

		wp_insert_term( 'Footer', 'courier_placement', array( 'slug' => 'footer' ) );
		wp_insert_term( 'Informational', 'courier_style', array( 'slug' => 'informational' ) );

		// Seeded by Install::install() on a real site. Without it the
		// wp_remove_object_terms( 'dismissed' ) call in the same branch warns
		// on WP 6.9 — noted for the dead-code/install triage, not this change.
		wp_insert_term( 'Dismissed', 'courier_status', array( 'slug' => 'dismissed' ) );

		$_POST['courier_notice_info_noncename'] = wp_create_nonce( 'courier_notice_info_nonce' );
		$_POST['courier_placement']             = 'footer';
		$_POST['courier_style']                 = 'informational';
		$_POST['courier_type']                  = 'no-such-type';

		( new \CourierNotices\Controller\Courier() )->save_post_courier_notice( $notice_id, get_post( $notice_id ) );

		unset(
			$_POST['courier_notice_info_noncename'],
			$_POST['courier_placement'],
			$_POST['courier_style'],
			$_POST['courier_type']
		);

		$this->assertTrue( has_term( 'footer', 'courier_placement', $notice_id ) );
		$this->assertTrue( has_term( 'informational', 'courier_style', $notice_id ) );
		$this->assertFalse(
			has_term( 'no-such-type', 'courier_type', $notice_id ),
			'A submitted slug with no matching term must not be assigned.'
		);
		$this->assertTrue( has_term( 'global', 'courier_scope', $notice_id ), 'The classic save still forces global scope.' );
	}

	/**
	 * An unparseable expiration string clears the date rather than storing a
	 * falsy row that would stop the notice displaying.
	 *
	 * @return void
	 */
	public function test_an_unparseable_classic_expiration_clears_the_date(): void {
		$notice_id = $this->create_notice( array( '_courier_expiration' => time() + DAY_IN_SECONDS ) );

		$_POST['courier_notices_expiration_noncename'] = wp_create_nonce( 'courier_notices_expiration_nonce' );
		$_POST['courier_expire_date']                  = 'not a date';

		( new \CourierNotices\Controller\Courier() )->save_post_courier_notice( $notice_id, get_post( $notice_id ) );

		unset( $_POST['courier_notices_expiration_noncename'], $_POST['courier_expire_date'] );

		$this->assertFalse( metadata_exists( 'post', $notice_id, '_courier_expiration' ) );
	}

	/**
	 * Normalization only touches what a save actually wrote — a REST request
	 * carrying no meta must leave the existing rows alone.
	 *
	 * @return void
	 */
	public function test_a_rest_save_without_meta_leaves_existing_meta_alone(): void {
		$expiration = time() + DAY_IN_SECONDS;
		$notice_id  = $this->create_notice(
			array(
				'_courier_dismissible' => 1,
				'_courier_expiration'  => $expiration,
			)
		);

		$request = new WP_REST_Request( 'POST', '/wp/v2/courier-notices/' . $notice_id );
		$request->set_body_params( array( 'title' => 'Retitled, nothing else' ) );
		rest_get_server()->dispatch( $request );

		$this->assertTrue( (bool) get_post_meta( $notice_id, '_courier_dismissible', true ) );
		$this->assertSame( $expiration, (int) get_post_meta( $notice_id, '_courier_expiration', true ) );
	}
}
