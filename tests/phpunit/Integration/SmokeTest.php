<?php
/**
 * Smoke test proving the WordPress integration lane boots.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration;

use WP_UnitTestCase;

/**
 * Proves the integration lane loads real WordPress with the plugin active.
 */
final class SmokeTest extends WP_UnitTestCase {

	/**
	 * Real WordPress must be loaded in this lane.
	 */
	public function test_wordpress_is_loaded(): void {
		$this->assertTrue( function_exists( 'do_action' ) );
		$this->assertNotEmpty( get_bloginfo( 'version' ) );
	}

	/**
	 * The plugin must have been loaded and booted by the bootstrap.
	 */
	public function test_plugin_is_loaded_and_booted(): void {
		$this->assertTrue( defined( 'COURIER_NOTICES_VERSION' ) );
		$this->assertTrue( class_exists( \CourierNotices\Core\Bootstrap::class ) );
	}

	/**
	 * The plugin's boot sequence must have registered its post type — this
	 * proves plugins_loaded -> Bootstrap::run() -> init actually ran, not
	 * just that the main file was required.
	 */
	public function test_courier_notice_post_type_is_registered(): void {
		$this->assertTrue( post_type_exists( 'courier_notice' ) );
	}
}
