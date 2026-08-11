<?php
/**
 * Render Registry
 *
 * @package CourierNotices\Helper
 * @since 2.0.0
 */

namespace CourierNotices\Helper;

/**
 * Render_Registry Class
 *
 * Tracks which notice regions have already been emitted on this request, so a
 * region is rendered once no matter how many code paths ask for it.
 *
 * Two paths converge here and will keep converging for several phases:
 *
 * 1. The legacy hook placements — `Placement` on `wp_body_open`, `get_footer`
 *    and now `wp_footer`.
 * 2. The `courier/notices` outlet block, wherever a template puts it.
 *
 * Without this, a page carrying both renders the same placement twice and the
 * visitor sees every notice duplicated. Claims are first-come, which matches
 * execution order: `get_footer` fires before `wp_footer`, and `wp_body_open`
 * before anything a template renders.
 *
 * Phase 6 widens the key to display type + screen position once fixed regions
 * exist — two blocks both asking for "bottom-right toasts" must not stack.
 *
 * @since 2.0.0
 */
class Render_Registry {

	/**
	 * Regions already emitted on this request.
	 *
	 * @since 2.0.0
	 *
	 * @var array<string, true>
	 */
	private static $claimed = array();

	/**
	 * Build the key for a notice region.
	 *
	 * @since 2.0.0
	 *
	 * @param string $placement Placement slug.
	 *
	 * @return string
	 */
	public static function region_key( $placement ) {
		return 'placement:' . sanitize_key( (string) $placement );
	}

	/**
	 * Claim a region for this request.
	 *
	 * @since 2.0.0
	 *
	 * @param string $placement Placement slug.
	 *
	 * @return bool True when the caller may render; false when something already has.
	 */
	public static function claim( $placement ) {
		$key = self::region_key( $placement );

		/**
		 * Filter whether notice regions render only once per request.
		 *
		 * Returning false restores the pre-2.0 behavior, where every caller
		 * emitted its own region. Only useful on a site that deliberately
		 * renders the same placement more than once.
		 *
		 * @since 2.0.0
		 *
		 * @param bool   $render_once Whether to enforce render-once.
		 * @param string $placement   The placement being claimed.
		 */
		if ( ! apply_filters( 'courier_notices_render_once', true, $placement ) ) {
			return true;
		}

		if ( isset( self::$claimed[ $key ] ) ) {
			return false;
		}

		self::$claimed[ $key ] = true;

		return true;
	}

	/**
	 * Whether a region has already been emitted.
	 *
	 * @since 2.0.0
	 *
	 * @param string $placement Placement slug.
	 *
	 * @return bool
	 */
	public static function is_claimed( $placement ) {
		return isset( self::$claimed[ self::region_key( $placement ) ] );
	}

	/**
	 * Every region claimed so far, for debugging and tests.
	 *
	 * @since 2.0.0
	 *
	 * @return array<int, string>
	 */
	public static function claimed() {
		return array_keys( self::$claimed );
	}

	/**
	 * Forget every claim.
	 *
	 * Statics live for one request on a real site; the test suite runs many
	 * "requests" in one process, so it needs a way back to a clean slate.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function reset() {
		self::$claimed = array();
	}
}
