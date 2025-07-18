<?php
/**
 * This file is used to mock constants that are not defined in the plugin.
 *
 * @package Courier_Notices
 */

define( 'COURIER_NOTICES_VERSION', '1.0.0' );

// Plugin path constants.
define( 'COURIER_NOTICES_PATH', dirname( dirname( __DIR__ ) ) );
define( 'COURIER_NOTICES_PLUGIN_URL', 'https://example.com' );

// WordPress constants that might be missing.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
}

if ( ! defined( 'WPINC' ) ) {
	define( 'WPINC', 'wp-includes' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
}

if ( ! class_exists( 'WP_CLI' ) ) {
	class WP_CLI {
		public static function add_command( $name, $callable, $args = [] ) {}
		public static function error( $message ) {}
		public static function success( $message ) {}
		public static function warning( $message ) {}
		public static function log( $message ) {}
	}
}

// Mock Action Scheduler functions.
if ( ! function_exists( 'as_schedule_single_action' ) ) {
	function as_schedule_single_action( $timestamp, $hook, $args = [], $group = '' ) {}
}

if ( ! function_exists( 'as_next_scheduled_action' ) ) {
	function as_next_scheduled_action( $hook, $args = [], $group = '' ) {}
}

if ( ! function_exists( 'as_schedule_recurring_action' ) ) {
	function as_schedule_recurring_action( $timestamp, $interval_in_seconds, $hook, $args = [], $group = '' ) {}
}

// Mock WP_CLI\Utils class.
if ( ! class_exists( 'WP_CLI\Utils' ) ) {
	class WP_CLI_Utils {
		public static function format_items( $format, $items, $fields ) {}
	}
	// Alias for the namespaced version.
	class_alias( 'WP_CLI_Utils', 'WP_CLI\Utils' );
}

