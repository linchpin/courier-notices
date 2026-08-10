<?php
/**
 * Recording stubs for the modern courier_notices_* functions.
 *
 * DeprecatedTest runs each test in its own process and loads THESE instead
 * of the real Helper/Functions.php, so a deprecated wrapper's delegation can
 * be asserted without executing the real implementation (which needs
 * WordPress). Each stub records its arguments and returns whatever sentinel
 * the test primed.
 *
 * Never load this in the same process as Helper/Functions.php.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Support {

	/**
	 * Registry the stubs below record into.
	 */
	final class Stub_Recorder {

		/**
		 * Arguments per function name.
		 *
		 * @var array<string, array>
		 */
		public static $calls = array();

		/**
		 * Primed return values per function name.
		 *
		 * @var array<string, mixed>
		 */
		public static $returns = array();

		/**
		 * Record a call and answer with the primed return.
		 *
		 * @param string $name Function name.
		 * @param array  $args Call arguments.
		 *
		 * @return mixed
		 */
		public static function record( string $name, array $args ) {
			self::$calls[ $name ] = $args;

			return self::$returns[ $name ] ?? null;
		}
	}
}

namespace {

	use CourierNotices\Tests\Unit\Support\Stub_Recorder;

	/**
	 * Stand-in for core's deprecation reporter; records instead of logging.
	 *
	 * @param string $function_name Deprecated function.
	 * @param string $version       Version deprecated in.
	 * @param string $replacement   Replacement function.
	 *
	 * @return void
	 */
	function _deprecated_function( $function_name, $version, $replacement = '' ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Standing in for the WordPress core function, which cannot be prefixed.
		$GLOBALS['courier_notices_test_deprecations'][] = array( $function_name, $version, $replacement );
	}

	// phpcs:disable Squiz.Commenting.FunctionComment.Missing -- Thirteen identical one-line recorders; the file docblock covers them.

	function courier_notices_add_notice( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_user_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_global_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_dismissible_global_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_persistent_global_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_display_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_display_modals( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_dismissed_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_global_dismissed_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_all_dismissed_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_dismiss_notices( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	function courier_notices_get_css( ...$args ) {
		return Stub_Recorder::record( __FUNCTION__, $args );
	}

	// phpcs:enable Squiz.Commenting.FunctionComment.Missing
}
