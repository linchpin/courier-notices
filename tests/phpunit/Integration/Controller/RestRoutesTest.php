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

		foreach ( array( '/notice/(?P<notice_id>\d+)', '/notice/(?P<notice_id>\d+)/dismiss', '/notices/display', '/notices/display/all', '/settings' ) as $route ) {
			$this->assertStringContainsString( $route, $keys, "Route {$route} must stay registered under courier-notices/v1." );
		}
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
