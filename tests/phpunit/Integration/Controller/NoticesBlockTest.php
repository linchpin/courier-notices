<?php
/**
 * Integration tests for the courier/notices outlet block (Phase 3, Section B).
 *
 * The block is a region, not a notice. Its two loading modes are the whole
 * point: `lazy` emits a shell the frontend fills per visitor, which is the only
 * mode safe behind a full-page cache, and `server` renders at request time.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Helper\Render_Registry;
use WP_UnitTestCase;

/**
 * Class NoticesBlockTest
 */
final class NoticesBlockTest extends WP_UnitTestCase {

	/**
	 * Re-register the post type and block, and start from an unclaimed
	 * registry the way a real request does.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		( new \CourierNotices\Controller\Courier_Notices() )->register_custom_post_type();

		Render_Registry::reset();
		wp_cache_flush();
	}

	/**
	 * Leave nothing behind for the next test.
	 *
	 * @return void
	 */
	public function tear_down(): void {
		Render_Registry::reset();

		parent::tear_down();
	}

	/**
	 * Create a published global notice for the given placement.
	 *
	 * @param string $placement Placement term.
	 * @param string $content   Post content.
	 *
	 * @return int
	 */
	private function create_notice( string $placement = 'Header', string $content = 'Outlet notice body' ): int {
		$notice_id = self::factory()->post->create(
			array(
				'post_type'    => 'courier_notice',
				'post_status'  => 'publish',
				'post_content' => $content,
			)
		);

		wp_set_object_terms( $notice_id, 'Global', 'courier_scope', false );
		wp_set_object_terms( $notice_id, $placement, 'courier_placement', false );
		wp_set_object_terms( $notice_id, 'Info', 'courier_type', false );

		return $notice_id;
	}

	/**
	 * Render the outlet block with the given attributes.
	 *
	 * @param array $attributes Block attributes as JSON-encodable values.
	 *
	 * @return string
	 */
	private function render_block( array $attributes = array() ): string {
		$json = array() === $attributes ? '' : ' ' . wp_json_encode( $attributes );

		return do_blocks( '<!-- wp:courier/notices' . $json . ' /-->' );
	}

	/**
	 * The block registers alongside the notice blocks.
	 *
	 * @return void
	 */
	public function test_the_outlet_block_is_registered(): void {
		$block = \WP_Block_Type_Registry::get_instance()->get_registered( 'courier/notices' );

		$this->assertNotNull( $block, 'courier/notices must be registered.' );
		$this->assertSame( 'lazy', $block->attributes['loading']['default'], 'Lazy is the cache-safe default.' );
		$this->assertSame( 'header', $block->attributes['placement']['default'] );
	}

	/**
	 * Lazy mode emits the region shell the existing frontend JS services and
	 * deliberately no notice content — that is what makes it cache-safe.
	 *
	 * @return void
	 */
	public function test_lazy_mode_emits_an_empty_region_shell(): void {
		$notice_id = $this->create_notice( 'Header', 'Should NOT be inlined' );

		$output = $this->render_block();

		$this->assertStringContainsString( 'courier-notices', $output );
		$this->assertStringContainsString( 'data-courier-ajax="true"', $output );
		$this->assertStringContainsString( 'data-courier-placement="header"', $output );
		$this->assertStringNotContainsString(
			'Should NOT be inlined',
			$output,
			'Lazy mode must not bake notice content into the page.'
		);
		$this->assertStringNotContainsString( (string) $notice_id, $output );
	}

	/**
	 * Server mode renders the notices inline.
	 *
	 * @return void
	 */
	public function test_server_mode_renders_notices_inline(): void {
		$notice_id = $this->create_notice( 'Header', 'Inlined at request time' );

		$output = $this->render_block( array( 'loading' => 'server' ) );

		$this->assertStringContainsString( 'Inlined at request time', $output );
		$this->assertStringContainsString( 'data-courier-notice-id="' . $notice_id . '"', $output );
		$this->assertStringNotContainsString(
			'data-courier-ajax="true"',
			$output,
			'Server mode has nothing left to fetch.'
		);
	}

	/**
	 * The placement attribute selects which notices the region shows.
	 *
	 * @return void
	 */
	public function test_the_placement_attribute_filters_the_region(): void {
		$this->create_notice( 'Header', 'A header notice' );
		$this->create_notice( 'Footer', 'A footer notice' );

		$output = $this->render_block(
			array(
				'loading'   => 'server',
				'placement' => 'footer',
			)
		);

		$this->assertStringContainsString( 'A footer notice', $output );
		$this->assertStringNotContainsString( 'A header notice', $output );
	}

	/**
	 * A courier/notice block notice renders as itself inside the region, not
	 * double-wrapped by the legacy template.
	 *
	 * @return void
	 */
	public function test_a_block_authored_notice_renders_as_itself_in_the_region(): void {
		$this->create_notice(
			'Header',
			'<!-- wp:courier/notice {"layout":"informational"} -->' .
			"<!-- wp:paragraph -->\n<p>Block-authored in a region</p>\n<!-- /wp:paragraph -->" .
			'<!-- /wp:courier/notice -->'
		);

		$output = $this->render_block( array( 'loading' => 'server' ) );

		$this->assertStringContainsString( 'Block-authored in a region', $output );
		$this->assertStringContainsString( 'wp-block-courier-notice', $output );
		$this->assertSame(
			1,
			substr_count( $output, 'courier-content-wrapper' ),
			'The legacy template must not double-wrap a block notice inside the region.'
		);
	}

	/**
	 * The block and the legacy hook placements share the render-once registry,
	 * so a page carrying both shows one region rather than duplicating every
	 * notice. The legacy hooks fire first on a real request, so the block is
	 * the one that stands down.
	 *
	 * @return void
	 */
	public function test_the_block_stands_down_when_a_region_is_already_claimed(): void {
		$this->create_notice( 'Header', 'Only once please' );

		// Whatever claimed it first — on a real request, wp_body_open.
		$this->assertTrue( Render_Registry::claim( 'header' ) );

		$this->assertSame( '', trim( $this->render_block( array( 'loading' => 'server' ) ) ) );
	}

	/**
	 * Two outlet blocks asking for the same placement render once.
	 *
	 * @return void
	 */
	public function test_two_regions_for_one_placement_render_once(): void {
		$this->create_notice( 'Header', 'Duplicated region check' );

		$first  = $this->render_block();
		$second = $this->render_block();

		$this->assertStringContainsString( 'data-courier-placement="header"', $first );
		$this->assertSame( '', trim( $second ) );
	}

	/**
	 * Different placements are independent regions.
	 *
	 * @return void
	 */
	public function test_two_regions_for_different_placements_both_render(): void {
		$header = $this->render_block( array( 'placement' => 'header' ) );
		$footer = $this->render_block( array( 'placement' => 'footer' ) );

		$this->assertStringContainsString( 'data-courier-placement="header"', $header );
		$this->assertStringContainsString( 'data-courier-placement="footer"', $footer );
	}
}
