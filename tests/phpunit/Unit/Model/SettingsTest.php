<?php
/**
 * Tests for the Settings model.
 *
 * These pin the COURIER-1075 whitelist bug: save_setting() shipped with
 * inverted logic — `if ( array_key_exists( … ) ) { return false; }` — so it
 * rejected every valid key and saved every invalid one. Nothing in the free
 * plugin called it, which is how the inversion survived; the tests below
 * assert the documented contract ("array keys must be whitelisted").
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Model;

require_once dirname( __DIR__ ) . '/Support/wp-function-shadows.php';

use CourierNotices\Model\Settings;
use CourierNotices\Tests\Unit\Support\WP_Shadow_State;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingsTest
 */
final class SettingsTest extends TestCase {

	/**
	 * Reset the shadow-backed option store and filter registry.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		WP_Shadow_State::reset();
	}

	/**
	 * The documented defaults, with their default values.
	 *
	 * @return void
	 */
	public function test_defaults_contain_the_documented_settings(): void {
		$settings = ( new Settings() )->get_settings();

		$this->assertTrue( $settings['ajax_notices'] );
		$this->assertFalse( $settings['clear_data_on_uninstall'] );
		$this->assertFalse( $settings['disable_css'] );
		$this->assertSame( '', $settings['enable_title'] );
		$this->assertFalse( $settings['prevent_ajax_cache'] );
	}

	/**
	 * Saved values overlay the defaults without erasing unsaved ones.
	 *
	 * @return void
	 */
	public function test_get_settings_merges_saved_values_over_defaults(): void {
		$GLOBALS['courier_notices_test_options']['courier_settings'] = array( 'disable_css' => true );

		$settings = ( new Settings() )->get_settings();

		$this->assertTrue( $settings['disable_css'], 'The saved value must win.' );
		$this->assertTrue( $settings['ajax_notices'], 'Unsaved keys must keep their defaults.' );
	}

	/**
	 * A whitelisted key must save and be readable back. This is the
	 * COURIER-1075 inversion, direction one: valid keys were rejected.
	 *
	 * @return void
	 */
	public function test_save_setting_accepts_a_whitelisted_key(): void {
		$result = ( new Settings() )->save_setting( 'disable_css', true );

		$this->assertIsArray( $result, 'Saving a whitelisted key must return the settings array, not false.' );
		$this->assertTrue( $result['disable_css'] );
		$this->assertTrue( ( new Settings() )->get_setting( 'disable_css' ) );
	}

	/**
	 * An unknown key must be rejected and never written. The inversion,
	 * direction two: invalid keys sailed straight into the option.
	 *
	 * @return void
	 */
	public function test_save_setting_rejects_an_unknown_key(): void {
		$result = ( new Settings() )->save_setting( 'not_a_setting', 'payload' );

		$this->assertFalse( $result, 'Saving a non-whitelisted key must return false.' );

		$stored = $GLOBALS['courier_notices_test_options']['courier_settings'] ?? array();

		$this->assertArrayNotHasKey( 'not_a_setting', $stored, 'A rejected key must not reach the database.' );
	}

	/**
	 * Pro contributes keys (licenseKey, licenseActive, productID) through the
	 * courier_notices_allowed_settings filter; a filtered-in key must be
	 * accepted exactly like a built-in one.
	 *
	 * @return void
	 */
	public function test_save_setting_accepts_a_filtered_in_key(): void {
		WP_Shadow_State::add_filter(
			'courier_notices_allowed_settings',
			static function ( $defaults ) {
				$defaults['licenseKey'] = '';

				return $defaults;
			}
		);

		$result = ( new Settings() )->save_setting( 'licenseKey', 'abc-123' );

		$this->assertIsArray( $result );
		$this->assertSame( 'abc-123', $result['licenseKey'] );
	}

	/**
	 * The array save path already had the whitelist the right way around;
	 * keep it locked.
	 *
	 * @return void
	 */
	public function test_save_settings_array_strips_unknown_keys(): void {
		$result = ( new Settings() )->save_settings_array(
			array(
				'disable_css'   => true,
				'not_a_setting' => 'payload',
			)
		);

		$this->assertIsArray( $result );
		$this->assertTrue( $result['disable_css'] );
		$this->assertArrayNotHasKey( 'not_a_setting', $result );
	}

	/**
	 * The docblock promises mixed|null; an unknown key must return null
	 * rather than raise an undefined-index warning.
	 *
	 * @return void
	 */
	public function test_get_setting_returns_null_for_an_unknown_key(): void {
		$this->assertNull( ( new Settings() )->get_setting( 'not_a_setting' ) );
	}

	/**
	 * An empty key returns null.
	 *
	 * @return void
	 */
	public function test_get_setting_returns_null_for_an_empty_key(): void {
		$this->assertNull( ( new Settings() )->get_setting() );
	}

	/**
	 * The constructor's option key routes every read and write.
	 *
	 * @return void
	 */
	public function test_a_custom_option_key_is_used_for_storage(): void {
		$result = ( new Settings( 'courier_settings_alt' ) )->save_setting( 'enable_title', 'yes' );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'courier_settings_alt', $GLOBALS['courier_notices_test_options'] );
		$this->assertArrayNotHasKey( 'courier_settings', $GLOBALS['courier_notices_test_options'] );
	}
}
