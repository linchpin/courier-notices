<?php
/**
 * WordPress test-suite configuration for the integration lane.
 *
 * Everything is environment-driven so CI can point the suite at its service
 * container without editing this file. The defaults match the MySQL service
 * in .github/workflows/tests.yml.
 *
 * WARNING: the test suite EMPTIES the configured database between runs.
 * Never point it at a database you care about.
 *
 * @package CourierNotices\Tests
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals -- This file defines the constants and globals the WordPress test suite itself requires; none of them can be prefixed.

// Path to the WordPress codebase to test against, with a trailing slash.
$courier_notices_wp_core_dir = getenv( 'WP_CORE_DIR' );

if ( false === $courier_notices_wp_core_dir ) {
	$courier_notices_wp_core_dir = '/tmp/wordpress';
}

define( 'ABSPATH', rtrim( $courier_notices_wp_core_dir, '/' ) . '/' );

define( 'DB_NAME', getenv( 'WP_DB_NAME' ) ? getenv( 'WP_DB_NAME' ) : 'wordpress_test' );
define( 'DB_USER', getenv( 'WP_DB_USER' ) ? getenv( 'WP_DB_USER' ) : 'root' );
define( 'DB_PASSWORD', false !== getenv( 'WP_DB_PASSWORD' ) ? getenv( 'WP_DB_PASSWORD' ) : 'root' );
define( 'DB_HOST', getenv( 'WP_DB_HOST' ) ? getenv( 'WP_DB_HOST' ) : '127.0.0.1' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

$table_prefix = 'wptests_';

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WP_DEBUG', true );

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals
