<?php
/**
 * Tests for the procedural helper API in Helper/Functions.php.
 *
 * These lock the public output contracts (placeholder containers, title
 * markup, the courier_notices / courier_notices_modal filters) and pin the
 * COURIER-1077 cache-clearing fix: courier_notices_clear_cache() used to
 * call wp_cache_flush_group() unconditionally — a fatal on cache drop-ins
 * that predate group flushing — and ran three raw DELETEs of which one was
 * entirely unprepared.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Helper;

require_once dirname( __DIR__ ) . '/Support/wp-function-shadows.php';
require_once dirname( __DIR__ ) . '/Support/wp-function-shadows-global.php';
require_once dirname( __DIR__, 4 ) . '/includes/Helper/Functions.php';

use CourierNotices\Tests\Unit\Support\WPDB_Spy;
use CourierNotices\Tests\Unit\Support\WP_Shadow_State;
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionsTest
 */
final class FunctionsTest extends TestCase {

	/**
	 * The $wpdb spy behind courier_notices_clear_cache().
	 *
	 * @var WPDB_Spy
	 */
	private $wpdb;

	/**
	 * Reset shadow state and install a fresh $wpdb spy.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		WP_Shadow_State::reset();

		// courier_notices_display_notices() claims its region through the
		// render-once registry, whose statics outlive a single test. Without
		// this, two tests rendering the same placement would silently get an
		// empty string from the second one.
		\CourierNotices\Helper\Render_Registry::reset();

		$this->wpdb      = new WPDB_Spy();
		$GLOBALS['wpdb'] = $this->wpdb;
	}

	/**
	 * A warm transient is returned with its markup stripped, without
	 * touching the CSS compiler.
	 *
	 * @return void
	 */
	public function test_get_css_returns_the_stripped_transient_when_warm(): void {
		$GLOBALS['courier_notices_test_transients']['courier_notice_css'] = '<style id="courier">.courier-notices{color:red}</style>';

		$this->assertSame( '.courier-notices{color:red}', courier_notices_get_css() );
	}

	/**
	 * An empty title short-circuits to an empty string even in echo mode.
	 *
	 * @return void
	 */
	public function test_the_notice_title_short_circuits_on_empty(): void {
		ob_start();
		$result = courier_notices_the_notice_title( '' );
		$echoed = ob_get_clean();

		$this->assertSame( '', $result );
		$this->assertSame( '', $echoed );
	}

	/**
	 * Return mode wraps the title and applies the public title filter.
	 *
	 * @return void
	 */
	public function test_the_notice_title_wraps_and_filters(): void {
		WP_Shadow_State::add_filter(
			'courier_notices_the_notice_title',
			static function ( $title ) {
				return $title . '<!--filtered-->';
			}
		);

		$this->assertSame(
			'<h3 class="courier-notice-title">Hello</h3><!--filtered-->',
			courier_notices_the_notice_title( 'Hello', '<h3 class="courier-notice-title">', '</h3>', false )
		);
	}

	/**
	 * Echo mode prints rather than returns.
	 *
	 * @return void
	 */
	public function test_the_notice_title_echoes_by_default(): void {
		ob_start();
		courier_notices_the_notice_title( 'Hello', '<h3>', '</h3>' );
		$echoed = ob_get_clean();

		$this->assertSame( '<h3>Hello</h3>', $echoed );
	}

	/**
	 * Without a placement there is nothing to render.
	 *
	 * @return void
	 */
	public function test_display_notices_requires_a_placement(): void {
		ob_start();
		$result = courier_notices_display_notices();
		$echoed = ob_get_clean();

		$this->assertFalse( $result );
		$this->assertSame( '', $echoed );
	}

	/**
	 * The placeholder container carries the placement classes and data
	 * attributes the frontend JS binds to.
	 *
	 * @return void
	 */
	public function test_display_notices_emits_the_placeholder_container(): void {
		ob_start();
		courier_notices_display_notices( array( 'placement' => 'header' ) );
		$echoed = ob_get_clean();

		$this->assertStringContainsString( 'courier-location-header', $echoed );
		$this->assertStringContainsString( 'data-courier-placement="header"', $echoed );
		$this->assertStringContainsString( 'data-courier-ajax="true"', $echoed );
	}

	/**
	 * The courier_notices filter is public API and may replace the output.
	 *
	 * @return void
	 */
	public function test_display_notices_output_passes_the_public_filter(): void {
		WP_Shadow_State::add_filter(
			'courier_notices',
			static function () {
				return '<div class="filtered-container"></div>';
			}
		);

		ob_start();
		courier_notices_display_notices( array( 'placement' => 'footer' ) );
		$echoed = ob_get_clean();

		$this->assertSame( '<div class="filtered-container"></div>', $echoed );
	}

	/**
	 * Modals default to the popup-modal placement and emit the overlay
	 * container, filtered through courier_notices_modal.
	 *
	 * @return void
	 */
	public function test_display_modals_emits_the_modal_container(): void {
		ob_start();
		courier_notices_display_modals();
		$echoed = ob_get_clean();

		$this->assertStringContainsString( 'courier-location-popup-modal', $echoed );
		$this->assertStringContainsString( 'courier-modal-overlay', $echoed );
	}

	/**
	 * With group flushing supported, the plugin's cache group is flushed
	 * and every transient DELETE goes through prepare() with an escaped
	 * LIKE pattern — including the has-notices family that used to run raw.
	 *
	 * @return void
	 */
	public function test_clear_cache_flushes_the_group_and_prepares_every_delete(): void {
		$GLOBALS['courier_notices_test_cache_supports'] = true;

		courier_notices_clear_cache();

		$this->assertSame( array( 'courier-notices' ), $GLOBALS['courier_notices_test_cache_flushes'] );

		$expected_prefixes = array(
			'_transient_courier_notices_',
			'_transient_timeout_courier_notices_',
			'_transient_courier_has_notices_',
			'_transient_timeout_courier_has_notices_',
		);

		$this->assertSame( $expected_prefixes, $this->wpdb->esc_liked, 'Every LIKE pattern must be escaped.' );
		$this->assertCount( 4, $this->wpdb->prepared, 'Every DELETE must be prepared.' );
		$this->assertCount( 4, $this->wpdb->queries );

		foreach ( $this->wpdb->queries as $sql ) {
			$this->assertStringContainsString( 'DELETE FROM wp_options', $sql );
			$this->assertStringNotContainsString( '%s', $sql, 'No placeholder may survive into executed SQL.' );
		}

		$this->assertContains( 'courier_notices_cache_cleared', $GLOBALS['courier_notices_test_actions'] );
	}

	/**
	 * Without group-flush support the known group keys are deleted
	 * individually instead — never a fatal, never a whole-cache flush.
	 *
	 * @return void
	 */
	public function test_clear_cache_falls_back_to_known_keys_without_group_flush(): void {
		$GLOBALS['courier_notices_test_cache_supports'] = false;

		courier_notices_clear_cache();

		$this->assertSame( array(), $GLOBALS['courier_notices_test_cache_flushes'] );

		foreach ( array( 'global-notices', 'global-dismissible-notices', 'global-persistent-notices' ) as $key ) {
			$this->assertContains( array( $key, 'courier-notices' ), $GLOBALS['courier_notices_test_cache_deletes'] );
		}
	}

	/**
	 * Every notice's post_meta cache entry is dropped.
	 *
	 * @return void
	 */
	public function test_clear_cache_drops_post_meta_cache_for_notices(): void {
		$GLOBALS['courier_notices_test_posts'] = array( 11, 12 );

		courier_notices_clear_cache();

		$this->assertContains( array( 11, 'post_meta' ), $GLOBALS['courier_notices_test_cache_deletes'] );
		$this->assertContains( array( 12, 'post_meta' ), $GLOBALS['courier_notices_test_cache_deletes'] );
	}
}
