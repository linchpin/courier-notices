<?php
/**
 * Global-namespace WordPress function shadows for the unit lane.
 *
 * Helper/Functions.php is procedural global-namespace code, so its WordPress
 * calls cannot be intercepted with namespaced shadows — but with WordPress
 * absent the real functions do not exist, and defining them globally here
 * works the same way. They share the WP_Shadow_State registry with the
 * namespaced shadows so one reset clears everything.
 *
 * Like the namespaced shadows, this file must NEVER be loaded in the
 * integration lane; there it would fatal on redeclaring core functions.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals -- Every symbol here deliberately stands in for a WordPress core function, whose names cannot be prefixed.
 *
 * @package CourierNotices\Tests
 */

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
 * Record fired actions for assertions.
 *
 * @param string $hook Hook name.
 * @param mixed  ...$args Action arguments.
 *
 * @return void
 */
function do_action( $hook, ...$args ) {
	unset( $args );

	$GLOBALS['courier_notices_test_actions'][] = $hook;
}

/**
 * Options read, backed by the shared test option store.
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
 * Transient read, backed by the test transient store.
 *
 * @param string $key Transient key.
 *
 * @return mixed False when missing, matching core.
 */
function get_transient( $key ) {
	return $GLOBALS['courier_notices_test_transients'][ $key ] ?? false;
}

/**
 * Transient write.
 *
 * @param string $key        Transient key.
 * @param mixed  $value      Value to store.
 * @param int    $expiration Ignored.
 *
 * @return bool
 */
function set_transient( $key, $value, $expiration = 0 ) {
	unset( $expiration );

	$GLOBALS['courier_notices_test_transients'][ $key ] = $value;

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
 * Passthrough — kses filtering itself is core behaviour, not under test.
 *
 * @param string $content      Markup.
 * @param array  $allowed_html Ignored.
 *
 * @return string
 */
function wp_kses( $content, $allowed_html = array() ) {
	unset( $allowed_html );

	return $content;
}

/**
 * Passthrough, as above.
 *
 * @param string $content Markup.
 *
 * @return string
 */
function wp_kses_post( $content ) {
	return $content;
}

/**
 * Simplified tag stripping — enough to prove get_css strips the style tag.
 *
 * @param string $text Markup.
 *
 * @return string
 */
function wp_strip_all_tags( $text ) {
	return trim( wp_strip_all_tags_preserve( $text ) );
}

/**
 * Internal: strip tags without trimming (keeps the shadow honest for
 * whitespace assertions).
 *
 * @param string $text Markup.
 *
 * @return string
 */
function wp_strip_all_tags_preserve( $text ) {
	return strip_tags( (string) $text ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- The alternative IS the function being shadowed.
}

/**
 * Close-enough attribute escaping for markup assertions.
 *
 * @param string $text Attribute value.
 *
 * @return string
 */
function esc_attr( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES );
}

/**
 * Object-cache delete, recorded for assertions.
 *
 * @param string $key   Cache key.
 * @param string $group Cache group.
 *
 * @return bool
 */
function wp_cache_delete( $key, $group = '' ) {
	$GLOBALS['courier_notices_test_cache_deletes'][] = array( $key, $group );
	unset( $GLOBALS['courier_notices_test_cache'][ $group ][ $key ] );

	return true;
}

/**
 * Group flush, recorded for assertions.
 *
 * @param string $group Cache group.
 *
 * @return bool
 */
function wp_cache_flush_group( $group ) {
	$GLOBALS['courier_notices_test_cache_flushes'][] = $group;
	unset( $GLOBALS['courier_notices_test_cache'][ $group ] );

	return true;
}

/**
 * Capability probe for the cache backend; tests toggle the flag.
 *
 * @param string $feature Feature name.
 *
 * @return bool
 */
function wp_cache_supports( $feature ) {
	unset( $feature );

	return (bool) ( $GLOBALS['courier_notices_test_cache_supports'] ?? true );
}

/**
 * Returns the canned post list.
 *
 * @param array $args Ignored.
 *
 * @return array
 */
function get_posts( $args = array() ) {
	unset( $args );

	return $GLOBALS['courier_notices_test_posts'] ?? array();
}
