<?php
/**
 * Smoke test proving the WordPress-free unit lane boots.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Proves the unit lane's bootstrap contract holds.
 */
final class SmokeTest extends TestCase {

	/**
	 * The bootstrap must define ABSPATH before the autoloader runs, or any
	 * class file carrying the `if ( ! defined( 'ABSPATH' ) ) exit;` guard
	 * silently ends the whole suite with exit code 0 when autoloaded.
	 */
	public function test_bootstrap_defines_abspath_before_the_autoloader(): void {
		$this->assertTrue( defined( 'ABSPATH' ) );
	}

	/**
	 * Plugin classes must be autoloadable without WordPress present.
	 */
	public function test_plugin_classes_autoload_without_wordpress(): void {
		$this->assertTrue( class_exists( \CourierNotices\Helper\Utils::class ) );
	}

	/**
	 * WordPress itself must never leak into this lane — if it does, tests
	 * written against function shadows would silently exercise the real thing.
	 */
	public function test_wordpress_is_not_loaded(): void {
		$this->assertFalse( function_exists( 'add_action' ) );
	}
}
