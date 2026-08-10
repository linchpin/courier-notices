<?php
/**
 * PHPUnit bootstrap for the WordPress integration lane.
 *
 * This lane runs in CI only — it needs a real WordPress checkout and a MySQL
 * database (see .github/workflows/tests.yml). The test library comes from the
 * wp-phpunit/wp-phpunit composer package, which mirrors WordPress core's
 * tests/phpunit/includes and exposes its own path as WP_PHPUNIT__DIR via a
 * composer-autoloaded file.
 *
 * Studio is PHP-WASM and is not a PHPUnit host; do not try to run this lane
 * against the local Studio site.
 *
 * @package CourierNotices\Tests
 */

$courier_notices_root = dirname( __DIR__, 2 );

require_once $courier_notices_root . '/vendor/autoload.php';

// Default the tests configuration so a bare `composer phpunit:integration`
// works anywhere the environment provides WP_CORE_DIR and a database.
if ( false === getenv( 'WP_PHPUNIT__TESTS_CONFIG' ) ) {
	putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . $courier_notices_root . '/tests/phpunit/wp-tests-config.php' );
}

$courier_notices_wp_phpunit = getenv( 'WP_PHPUNIT__DIR' );

if ( false === $courier_notices_wp_phpunit ) {
	$courier_notices_wp_phpunit = $courier_notices_root . '/vendor/wp-phpunit/wp-phpunit';
}

require_once $courier_notices_wp_phpunit . '/includes/functions.php';

// Load the plugin the same way WordPress would, before the test install runs.
tests_add_filter(
	'muplugins_loaded',
	static function () use ( $courier_notices_root ) {
		// Action Scheduler is a composer-autoloaded file, but PHPUnit's own
		// launcher pulls in vendor/autoload.php before WordPress exists, so
		// its `function_exists( 'add_action' )` guard skipped everything and
		// composer will not re-run the file. Requiring it again here — with
		// WordPress loaded — is safe by design: every symbol inside is
		// function_exists-guarded so bundled copies can coexist.
		require $courier_notices_root . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
		require $courier_notices_root . '/courier-notices.php';
	}
);

require $courier_notices_wp_phpunit . '/includes/bootstrap.php';
