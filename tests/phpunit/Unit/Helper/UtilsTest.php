<?php
/**
 * Tests for the Utils helper.
 *
 * get_safe_markup() is the kses allowlist everything user-facing passes
 * through, and the courier_notices_safe_markup filter is public API that
 * Courier Pro extends — these tests lock both before the 2.0 refactors.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Helper;

require_once dirname( __DIR__ ) . '/Support/wp-function-shadows.php';

use CourierNotices\Helper\Utils;
use CourierNotices\Tests\Unit\Support\WP_Shadow_State;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilsTest
 */
final class UtilsTest extends TestCase {

	/**
	 * Reset shadow-backed state.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		WP_Shadow_State::reset();
	}

	/**
	 * Cron is reported enabled while the DISABLE_CRON constant is absent.
	 *
	 * @return void
	 */
	public function test_wp_cron_reported_enabled_without_the_constant(): void {
		$this->assertFalse( Utils::is_wp_cron_disabled() );
	}

	/**
	 * Cron is reported disabled when DISABLE_CRON is true. Constants cannot
	 * be undefined, so this runs in its own process.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 *
	 * @return void
	 */
	public function test_wp_cron_reported_disabled_with_the_constant(): void {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- Standing in for the site-configurable WordPress constant, which cannot be prefixed.
		define( 'DISABLE_CRON', true );

		$this->assertTrue( Utils::is_wp_cron_disabled() );
	}

	/**
	 * The allowlist shape everything user-facing depends on: the notice
	 * container's data attributes, and the small set of allowed elements.
	 *
	 * @return void
	 */
	public function test_safe_markup_allows_the_notice_container_shape(): void {
		$markup = Utils::get_safe_markup();

		foreach ( array( 'data-courier', 'data-courier-notice-id', 'data-courier-ajax', 'data-courier-placement', 'data-alert', 'data-closable', 'class' ) as $attribute ) {
			$this->assertArrayHasKey( $attribute, $markup['div'], "div must allow {$attribute}" );
		}

		$this->assertArrayHasKey( 'class', $markup['span'] );
		$this->assertArrayHasKey( 'p', $markup );
		$this->assertArrayHasKey( 'id', $markup['style'] );
		$this->assertArrayHasKey( 'href', $markup['a'] );
		$this->assertArrayHasKey( 'class', $markup['a'] );
	}

	/**
	 * Pro widens the allowlist through courier_notices_safe_markup; a
	 * filtered-in element must survive.
	 *
	 * @return void
	 */
	public function test_safe_markup_filter_can_widen_the_allowlist(): void {
		WP_Shadow_State::add_filter(
			'courier_notices_safe_markup',
			static function ( $allowed ) {
				$allowed['strong'] = array();

				return $allowed;
			}
		);

		$this->assertArrayHasKey( 'strong', Utils::get_safe_markup() );
	}

	/**
	 * Colors are six uppercase hex digits with a leading hash, across the
	 * full RNG range.
	 *
	 * @return void
	 */
	public function test_random_color_formats_the_full_range(): void {
		$cases = array(
			0        => '#000000',
			0x04A84E => '#04A84E',
			0xFFFFFF => '#FFFFFF',
		);

		foreach ( $cases as $seed => $expected ) {
			$GLOBALS['courier_notices_test_wp_rand'] = $seed;

			$this->assertSame( $expected, Utils::get_random_color() );
		}
	}
}
