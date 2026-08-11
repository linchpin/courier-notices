<?php
/**
 * Integration tests for notice placement and the render-once registry.
 *
 * Two Phase 3 items live here:
 *
 * 1. The wp_footer fix. `get_footer` does not fire in a block theme, so every
 *    footer notice was silently missing on modern sites.
 * 2. The render-once registry, which is what makes double-hooking safe and
 *    what stops the courier/notices block and the legacy hooks from both
 *    emitting the same region.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Controller\Placement;
use CourierNotices\Helper\Render_Registry;
use WP_UnitTestCase;

/**
 * Class PlacementTest
 */
final class PlacementTest extends WP_UnitTestCase {

	/**
	 * Statics survive between tests in one PHP process; a real request starts
	 * with an empty registry, so mirror that.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		Render_Registry::reset();
		wp_cache_flush();
	}

	/**
	 * Leave no claims behind for the next test.
	 *
	 * @return void
	 */
	public function tear_down(): void {
		Render_Registry::reset();
		remove_all_filters( 'courier_notices_render_once' );

		parent::tear_down();
	}

	/**
	 * Capture whatever a placement callback echoes.
	 *
	 * @param callable $callback Callback to run.
	 *
	 * @return string
	 */
	private function capture( callable $callback ): string {
		ob_start();
		$callback();

		return (string) ob_get_clean();
	}

	/**
	 * Footer notices must hook wp_footer as well as get_footer — the block
	 * theme fix. Both hooks carry the same callback.
	 *
	 * @return void
	 */
	public function test_footer_notices_hook_wp_footer_as_well_as_get_footer(): void {
		( new Placement() )->register_actions();

		$callback = array( Placement::class, 'place_footer_notices' );

		$this->assertNotFalse(
			has_action( 'get_footer', $callback ),
			'Classic themes must keep rendering footer notices where they always did.'
		);
		$this->assertNotFalse(
			has_action( 'wp_footer', $callback ),
			'get_footer never fires in a block theme, so wp_footer is the actual fix.'
		);
	}

	/**
	 * The region renders on the first call and is silent on the second, so a
	 * theme firing both get_footer and wp_footer gets one region, not two.
	 *
	 * @return void
	 */
	public function test_a_region_renders_once_across_both_footer_hooks(): void {
		$first  = $this->capture( array( Placement::class, 'place_footer_notices' ) );
		$second = $this->capture( array( Placement::class, 'place_footer_notices' ) );

		$this->assertStringContainsString( 'data-courier-placement="footer"', $first );
		$this->assertSame( '', $second, 'The second call must emit nothing.' );
	}

	/**
	 * Different placements are independent claims.
	 *
	 * @return void
	 */
	public function test_claiming_one_placement_does_not_claim_another(): void {
		$header = $this->capture( array( Placement::class, 'place_header_notices' ) );
		$footer = $this->capture( array( Placement::class, 'place_footer_notices' ) );

		$this->assertStringContainsString( 'data-courier-placement="header"', $header );
		$this->assertStringContainsString( 'data-courier-placement="footer"', $footer );

		$this->assertTrue( Render_Registry::is_claimed( 'header' ) );
		$this->assertTrue( Render_Registry::is_claimed( 'footer' ) );
		$this->assertFalse( Render_Registry::is_claimed( 'popup-modal' ) );
	}

	/**
	 * The emitted region is the lazy shell the existing frontend JS services —
	 * core.js selects `.courier-notices[data-courier-ajax="true"]` and reads
	 * data-courier-placement. The courier/notices block reuses this contract,
	 * so it must not drift.
	 *
	 * @return void
	 */
	public function test_the_region_shell_matches_the_frontend_js_contract(): void {
		$output = $this->capture( array( Placement::class, 'place_header_notices' ) );

		$this->assertStringContainsString( 'courier-notices', $output );
		$this->assertStringContainsString( 'data-courier-ajax="true"', $output );
		$this->assertStringContainsString( 'data-courier-placement="header"', $output );
	}

	/**
	 * A site can opt back out of render-once.
	 *
	 * @return void
	 */
	public function test_the_render_once_filter_restores_duplicate_regions(): void {
		add_filter( 'courier_notices_render_once', '__return_false' );

		$first  = $this->capture( array( Placement::class, 'place_footer_notices' ) );
		$second = $this->capture( array( Placement::class, 'place_footer_notices' ) );

		$this->assertStringContainsString( 'data-courier-placement="footer"', $first );
		$this->assertStringContainsString(
			'data-courier-placement="footer"',
			$second,
			'With render-once filtered off, every caller emits its own region again.'
		);
	}

	/**
	 * The registry keys on the placement, not on object identity, so an
	 * unrelated caller asking for the same placement is also deduped.
	 *
	 * @return void
	 */
	public function test_the_registry_dedupes_across_callers(): void {
		$this->assertTrue( Render_Registry::claim( 'header' ), 'The first caller wins.' );
		$this->assertFalse( Render_Registry::claim( 'header' ), 'A later caller must be told to stand down.' );

		// This is what stops the block and the legacy hook doubling up.
		$hook_output = $this->capture( array( Placement::class, 'place_header_notices' ) );

		$this->assertSame( '', $hook_output );
	}
}
