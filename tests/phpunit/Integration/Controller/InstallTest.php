<?php
/**
 * Integration tests for installation and activation.
 *
 * Pins three COURIER-1076 lifecycle bugs: install() never wrote
 * courier_notices_options (so check_for_updates() re-ran it until the
 * upgrade ladder happened to write the option), never seeded the
 * courier_style taxonomy (a fresh install had no styles at all), and
 * activation never recorded first_activated_on (so the review nag could
 * never fire).
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Controller\Install;
use WP_UnitTestCase;

/**
 * Class InstallTest
 */
final class InstallTest extends WP_UnitTestCase {

	/**
	 * Run install() against a virgin options table.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		delete_option( 'courier_notices_options' );
	}

	/**
	 * The style taxonomy gets its two working terms — informational is the
	 * query default, popup-modal is how modals are selected.
	 *
	 * @return void
	 */
	public function test_install_seeds_the_style_terms(): void {
		( new Install() )->install();

		$this->assertNotNull( term_exists( 'informational', 'courier_style' ) );
		$this->assertNotNull( term_exists( 'popup-modal', 'courier_style' ) );
	}

	/**
	 * The type terms carry their color and icon term meta.
	 *
	 * @return void
	 */
	public function test_install_seeds_the_type_terms_with_design_meta(): void {
		( new Install() )->install();

		$success = term_exists( 'success', 'courier_type' );

		$this->assertIsArray( $success );
		$this->assertSame( '#04a84e', get_term_meta( (int) $success['term_id'], '_courier_type_color', true ) );
		$this->assertSame( 'success', get_term_meta( (int) $success['term_id'], '_courier_type_icon', true ) );

		$this->assertNotNull( term_exists( 'global', 'courier_scope' ) );
		$this->assertNotNull( term_exists( 'header', 'courier_placement' ) );
		$this->assertNotNull( term_exists( 'dismissed', 'courier_status' ) );
	}

	/**
	 * install() marks itself done, closing the check_for_updates() gate
	 * that used to re-run it on every admin request.
	 *
	 * @return void
	 */
	public function test_install_marks_itself_done(): void {
		( new Install() )->install();

		$options = get_option( 'courier_notices_options' );

		$this->assertIsArray( $options );
		$this->assertSame( COURIER_NOTICES_VERSION, $options['plugin_version'] );
	}

	/**
	 * A version the upgrade ladder owns is never clobbered — an upgrading
	 * site's pending migrations stay pending.
	 *
	 * @return void
	 */
	public function test_install_does_not_clobber_an_existing_version(): void {
		update_option( 'courier_notices_options', array( 'plugin_version' => '1.5.0' ) );

		( new Install() )->install();

		$options = get_option( 'courier_notices_options' );

		$this->assertSame( '1.5.0', $options['plugin_version'] );
	}

	/**
	 * Activation records first_activated_on once — the timestamp the
	 * 30-day review nag counts from — and never overwrites it.
	 *
	 * @return void
	 */
	public function test_activation_records_first_activated_on_once(): void {
		courier_notices_activation();

		$options = get_option( 'courier_notices_options' );

		$this->assertArrayHasKey( 'first_activated_on', $options );
		$this->assertEqualsWithDelta( time(), $options['first_activated_on'], 5 );

		$original = $options['first_activated_on'];

		update_option(
			'courier_notices_options',
			array_merge( $options, array( 'first_activated_on' => $original - 100 ) )
		);

		courier_notices_activation();

		$options = get_option( 'courier_notices_options' );

		$this->assertSame( $original - 100, $options['first_activated_on'], 'Re-activation must not reset the first-activation date.' );
	}
}
