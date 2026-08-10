<?php
/**
 * Integration tests for the REST surface after the REST_Base port.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use WP_REST_Request;
use WP_UnitTestCase;

/**
 * Class RestRoutesTest
 */
final class RestRoutesTest extends WP_UnitTestCase {

	/**
	 * Spin up a REST server with the plugin's routes registered.
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

		wp_cache_flush();
	}

	/**
	 * Every public route survives the port intact — external consumers are
	 * frozen on courier-notices/v1.
	 *
	 * @return void
	 */
	public function test_all_v1_routes_are_registered(): void {
		$routes = rest_get_server()->get_routes( 'courier-notices/v1' );
		$keys   = implode( "\n", array_keys( $routes ) );

		foreach ( array( '/notice/(?P<notice_id>\d+)', '/notice/(?P<notice_id>\d+)/dismiss', '/notices/display', '/notices/display/all', '/settings', '/reactivate/(?P<notice_id>\d+)' ) as $route ) {
			$this->assertStringContainsString( $route, $keys, "Route {$route} must stay registered under courier-notices/v1." );
		}
	}

	/**
	 * Reactivating an expired notice republishes it and pushes the lapsed
	 * expiration 30 days out — the feature the admin UI has linked to for
	 * years while the route did not exist.
	 *
	 * @return void
	 */
	public function test_reactivate_republishes_an_expired_notice(): void {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$notice_id = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'courier_expired',
			)
		);
		update_post_meta( $notice_id, '_courier_expiration', time() - HOUR_IN_SECONDS );
		wp_set_object_terms( $notice_id, 'Dismissed', 'courier_status', false );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'POST', "/courier-notices/v1/reactivate/{$notice_id}" ) );

		$this->assertSame( 200, $response->get_status() );
		$this->assertTrue( $response->get_data()['success'] );

		$this->assertSame( 'publish', get_post_status( $notice_id ) );
		$this->assertGreaterThan( time(), (int) get_post_meta( $notice_id, '_courier_expiration', true ), 'A lapsed expiration must be pushed into the future.' );
		$this->assertFalse( has_term( 'dismissed', 'courier_status', $notice_id ), 'The dismissed status must be cleared.' );
	}

	/**
	 * Reactivation edits the notice, so it needs edit rights — subscribers
	 * and anonymous visitors are refused.
	 *
	 * @return void
	 */
	public function test_reactivate_requires_edit_rights(): void {
		$notice_id = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'courier_expired',
			)
		);

		wp_set_current_user( 0 );
		$response = rest_get_server()->dispatch( new WP_REST_Request( 'POST', "/courier-notices/v1/reactivate/{$notice_id}" ) );
		$this->assertSame( 401, $response->get_status() );

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );
		$response = rest_get_server()->dispatch( new WP_REST_Request( 'POST', "/courier-notices/v1/reactivate/{$notice_id}" ) );
		$this->assertSame( 403, $response->get_status() );

		$this->assertSame( 'courier_expired', get_post_status( $notice_id ), 'A refused request must not change the notice.' );
	}

	/**
	 * The display endpoint stays public — the frontend lazy-fetches notices
	 * anonymously on every page view.
	 *
	 * @return void
	 */
	public function test_display_endpoint_is_public_and_returns_notices(): void {
		wp_set_current_user( 0 );

		$notice_id = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'publish',
			)
		);
		wp_set_object_terms( $notice_id, 'Global', 'courier_scope', false );
		wp_set_object_terms( $notice_id, 'Header', 'courier_placement', false );

		$request = new WP_REST_Request( 'GET', '/courier-notices/v1/notices/display' );
		$request->set_param( 'placement', 'header' );

		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( 'notices', $data );
		$this->assertContains( $notice_id, wp_list_pluck( $data['notices'], 'ID' ) );
	}

	/**
	 * The dismiss route acts on a user account and rejects anonymous
	 * visitors, whose dismissals live in the cookie instead.
	 *
	 * @return void
	 */
	public function test_dismiss_route_requires_a_logged_in_user(): void {
		wp_set_current_user( 0 );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'POST', '/courier-notices/v1/notice/123/dismiss' ) );

		$this->assertSame( 401, $response->get_status() );
	}

	/**
	 * Settings routes require manage_options in both directions.
	 *
	 * @return void
	 */
	public function test_settings_routes_require_manage_options(): void {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'GET', '/courier-notices/v1/settings' ) );
		$this->assertSame( 403, $response->get_status() );

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'GET', '/courier-notices/v1/settings' ) );
		$this->assertSame( 200, $response->get_status() );
		$this->assertArrayHasKey( 'ajax_notices', $response->get_data() );
	}

	/**
	 * Only registered placement terms survive sanitization, popup-modal is
	 * force-appended for the legacy frontend, and the response is grouped
	 * by placement.
	 *
	 * @return void
	 */
	public function test_display_all_sanitizes_placements_and_groups_by_placement(): void {
		wp_set_current_user( 0 );

		$notice_id = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'publish',
			)
		);
		wp_set_object_terms( $notice_id, 'Global', 'courier_scope', false );
		wp_set_object_terms( $notice_id, 'Header', 'courier_placement', false );

		$request = new WP_REST_Request( 'GET', '/courier-notices/v1/notices/display/all' );
		$request->set_param( 'placements', array( 'header', '<script>bogus</script>' ) );

		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertSame( array( 'header', 'popup-modal' ), array_keys( $data ), 'Unregistered placements must be stripped; popup-modal is always appended for the legacy frontend.' );
		$this->assertContains( $notice_id, wp_list_pluck( $data['header'], 'ID' ) );
	}

	/**
	 * user_id declares a real JSON Schema type — a non-integer value is
	 * rejected by validation instead of flowing into the query.
	 *
	 * @return void
	 */
	public function test_display_rejects_a_non_integer_user_id(): void {
		$request = new WP_REST_Request( 'GET', '/courier-notices/v1/notices/display' );
		$request->set_param( 'user_id', 'not-a-number' );

		$this->assertSame( 400, rest_get_server()->dispatch( $request )->get_status() );
	}

	/**
	 * The localized endpoints are built with rest_url(), which honors
	 * non-pretty permalinks and custom REST prefixes.
	 *
	 * @return void
	 */
	public function test_localized_endpoints_use_rest_url(): void {
		$captured = null;

		add_filter(
			'courier_notices_localized_data',
			static function ( $data ) use ( &$captured ) {
				$captured = $data;

				return $data;
			}
		);

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
		do_action( 'wp_enqueue_scripts' );

		$this->assertIsArray( $captured );
		$this->assertSame( rest_url( 'courier-notices/v1/notices/display/all/' ), $captured['notices_all_endpoint'] );
		$this->assertSame( rest_url( 'courier-notices/v1/notices/display/' ), $captured['notices_endpoint'] );
		$this->assertSame( rest_url( 'courier-notices/v1/notice/' ), $captured['notice_endpoint'] );
	}

	/**
	 * The settings write path only accepts the two known option keys — the
	 * key used to be caller-controlled, letting any manage_options request
	 * write an arbitrary option name.
	 *
	 * @return void
	 */
	public function test_settings_key_cannot_name_an_arbitrary_option(): void {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$request = new WP_REST_Request( 'POST', '/courier-notices/v1/settings' );
		$request->set_param( 'settings_key', 'hijacked_option' );
		$request->set_param( 'disable_css', true );

		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertFalse( get_option( 'hijacked_option' ), 'A caller-chosen option name must never be written.' );

		$stored = get_option( 'courier_settings' );

		$this->assertTrue( $stored['disable_css'], 'The write must land in the primary settings option instead.' );
	}
}
