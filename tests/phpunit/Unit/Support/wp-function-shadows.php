<?php
/**
 * WordPress function shadows for the unit lane.
 *
 * WordPress is never loaded in this lane. PHP resolves unqualified function
 * calls to the calling code's own namespace first, so defining these inside
 * CourierNotices\Model makes the classes under test call the shadows instead
 * of (absent) WordPress. Each shadow is backed by $GLOBALS state that
 * WP_Shadow_State::reset() clears between tests — mantle's approach.
 *
 * A namespace's shadows can only be declared once per process, so every
 * shadow lives here rather than in individual test files; tests require_once
 * this file. It must NEVER be loaded in the integration lane, where the
 * shadows would mask real WordPress.
 *
 * @package CourierNotices\Tests
 */

namespace {

	if ( ! defined( 'COURIER_NOTICES_FILE' ) ) {
		define( 'COURIER_NOTICES_FILE', dirname( __DIR__, 4 ) . '/courier-notices.php' );
	}

	if ( ! defined( 'COURIER_NOTICES_PATH' ) ) {
		define( 'COURIER_NOTICES_PATH', dirname( __DIR__, 4 ) . '/' );
	}

	if ( ! defined( 'COURIER_NOTICES_PLUGIN_URL' ) ) {
		define( 'COURIER_NOTICES_PLUGIN_URL', 'https://example.org/wp-content/plugins/courier-notices/' );
	}
}

namespace CourierNotices\Model {

	/**
	 * Run callbacks registered through WP_Shadow_State::add_filter().
	 *
	 * @param string $hook  Hook name.
	 * @param mixed  $value Value being filtered.
	 * @param mixed  ...$args Additional filter arguments.
	 *
	 * @return mixed
	 */
	function apply_filters( $hook, $value, ...$args ) {
		foreach ( $GLOBALS['courier_notices_test_filters'][ $hook ] ?? array() as $callback ) {
			$value = $callback( $value, ...$args );
		}

		return $value;
	}

	/**
	 * Options read, backed by the test option store.
	 *
	 * @param string $key           Option key.
	 * @param mixed  $default_value Default when unset.
	 *
	 * @return mixed
	 */
	function get_option( $key, $default_value = false ) {
		return $GLOBALS['courier_notices_test_options'][ $key ] ?? $default_value;
	}

	/**
	 * Options write. Mirrors core's contract: writing an unchanged value
	 * returns false.
	 *
	 * @param string $key      Option key.
	 * @param mixed  $value    Value to store.
	 * @param mixed  $autoload Ignored.
	 *
	 * @return bool
	 */
	function update_option( $key, $value, $autoload = null ) {
		unset( $autoload );

		if ( isset( $GLOBALS['courier_notices_test_options'][ $key ] )
			&& $GLOBALS['courier_notices_test_options'][ $key ] === $value ) {
			return false;
		}

		$GLOBALS['courier_notices_test_options'][ $key ] = $value;

		return true;
	}

	/**
	 * Core's array-input semantics: defaults overlaid by args.
	 *
	 * @param array|object|string $args     Arguments to merge.
	 * @param array               $defaults Default values.
	 *
	 * @return array
	 */
	function wp_parse_args( $args, $defaults = array() ) {
		return array_merge( $defaults, (array) $args );
	}

	/**
	 * Object-cache read, backed by the test cache store.
	 *
	 * @param string $key   Cache key.
	 * @param string $group Cache group.
	 *
	 * @return mixed False when missing, matching core.
	 */
	function wp_cache_get( $key, $group = '' ) {
		return $GLOBALS['courier_notices_test_cache'][ $group ][ $key ] ?? false;
	}

	/**
	 * Object-cache write.
	 *
	 * @param string $key    Cache key.
	 * @param mixed  $data   Value to store.
	 * @param string $group  Cache group.
	 * @param int    $expire Ignored.
	 *
	 * @return bool
	 */
	function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
		unset( $expire );

		$GLOBALS['courier_notices_test_cache'][ $group ][ $key ] = $data;

		return true;
	}

	/**
	 * Enough of core's implementation for a plugin main file:
	 * "plugin-dir/plugin-file.php".
	 *
	 * @param string $file Absolute path to a plugin file.
	 *
	 * @return string
	 */
	function plugin_basename( $file ) {
		return basename( dirname( $file ) ) . '/' . basename( $file );
	}

	/**
	 * Records every read so tests can assert the caching contract, then
	 * answers from WP_Shadow_State's canned headers.
	 *
	 * @param string $file            File being "read".
	 * @param array  $default_headers Header map (internal key => header name).
	 * @param string $context         Ignored.
	 *
	 * @return array
	 */
	function get_file_data( $file, $default_headers, $context = '' ) {
		unset( $context );

		$GLOBALS['courier_notices_test_file_reads'][] = $file;

		$headers = array();

		foreach ( $default_headers as $key => $header ) {
			$headers[ $key ] = $GLOBALS['courier_notices_test_file_headers'][ $header ] ?? '';
		}

		return $headers;
	}
}

namespace CourierNotices\Helper {

	/**
	 * Run callbacks registered through WP_Shadow_State::add_filter().
	 *
	 * @param string $hook  Hook name.
	 * @param mixed  $value Value being filtered.
	 * @param mixed  ...$args Additional filter arguments.
	 *
	 * @return mixed
	 */
	function apply_filters( $hook, $value, ...$args ) {
		foreach ( $GLOBALS['courier_notices_test_filters'][ $hook ] ?? array() as $callback ) {
			$value = $callback( $value, ...$args );
		}

		return $value;
	}

	/**
	 * Deterministic stand-in for core's RNG; tests seed the value.
	 *
	 * @param int $min Lower bound.
	 * @param int $max Upper bound.
	 *
	 * @return int
	 */
	function wp_rand( $min = 0, $max = 0 ) {
		unset( $min, $max );

		return (int) ( $GLOBALS['courier_notices_test_wp_rand'] ?? 0 );
	}
}

namespace CourierNotices\Controller\REST {

	/**
	 * Record hook registrations for assertions.
	 *
	 * @param string   $hook     Hook name.
	 * @param callable $callback Callback.
	 * @param mixed    ...$args  Priority and accepted args.
	 *
	 * @return void
	 */
	function add_action( $hook, $callback, ...$args ) {
		unset( $args );

		$GLOBALS['courier_notices_test_added_actions'][] = array( $hook, $callback );
	}

	/**
	 * Login state, driven by the test.
	 *
	 * @return bool
	 */
	function is_user_logged_in() {
		return (bool) ( $GLOBALS['courier_notices_test_logged_in'] ?? false );
	}

	/**
	 * Capability checks, driven by the test's capability list.
	 *
	 * @param string $capability Capability name.
	 *
	 * @return bool
	 */
	function current_user_can( $capability ) {
		return in_array( $capability, $GLOBALS['courier_notices_test_caps'] ?? array(), true );
	}
}

namespace CourierNotices\Tests\Unit\Support {

	/**
	 * Test-state registry behind the function shadows above.
	 */
	final class WP_Shadow_State {

		/**
		 * Clear all shadow-backed state. Call from setUp().
		 *
		 * @return void
		 */
		public static function reset(): void {
			$GLOBALS['courier_notices_test_filters']        = array();
			$GLOBALS['courier_notices_test_options']        = array();
			$GLOBALS['courier_notices_test_cache']          = array();
			$GLOBALS['courier_notices_test_file_reads']     = array();
			$GLOBALS['courier_notices_test_file_headers']   = array();
			$GLOBALS['courier_notices_test_wp_rand']        = 0;
			$GLOBALS['courier_notices_test_transients']     = array();
			$GLOBALS['courier_notices_test_actions']        = array();
			$GLOBALS['courier_notices_test_cache_deletes']  = array();
			$GLOBALS['courier_notices_test_cache_flushes']  = array();
			$GLOBALS['courier_notices_test_cache_supports'] = true;
			$GLOBALS['courier_notices_test_posts']          = array();
			$GLOBALS['courier_notices_test_added_actions']  = array();
			$GLOBALS['courier_notices_test_logged_in']      = false;
			$GLOBALS['courier_notices_test_caps']           = array();
		}

		/**
		 * Register a callback for the apply_filters() shadow.
		 *
		 * @param string   $hook     Hook name.
		 * @param callable $callback Filter callback.
		 *
		 * @return void
		 */
		public static function add_filter( string $hook, callable $callback ): void {
			$GLOBALS['courier_notices_test_filters'][ $hook ][] = $callback;
		}
	}
}
