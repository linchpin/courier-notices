<?php
/**
 * PHPUnit bootstrap for the WordPress-free unit lane.
 *
 * WordPress is never loaded in this lane. Classes under test shadow the
 * handful of WordPress functions they need within the test file's own
 * namespace (mantle's approach).
 *
 * Some class files open with the conventional `if ( ! defined( 'ABSPATH' ) )
 * { exit; }` direct-access guard. Without WordPress that guard fires the
 * moment such a class is autoloaded, and the test run ends mid-suite with
 * exit code 0 and no failure reported — a silent pass that hides every test
 * after it. Defining ABSPATH here lets guarded classes be unit tested at all.
 *
 * @package CourierNotices\Tests
 */

if ( ! defined( 'ABSPATH' ) ) {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- Standing in for the WordPress core constant, which cannot be prefixed.
	define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );
}

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';
