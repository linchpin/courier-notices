<?php
/**
 * Integration tests for block-aware notice rendering (COURIER-1036).
 *
 * The REST fragment path used to hand raw post_content to the templates,
 * where wp_kses_post() mangled block comment delimiters and dynamic blocks
 * never rendered. Classic content must keep rendering byte-identically.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use WP_REST_Request;
use WP_UnitTestCase;

/**
 * Class BlockRenderTest
 */
final class BlockRenderTest extends WP_UnitTestCase {

	/**
	 * Boot a REST server with the plugin's routes registered.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		global $wp_rest_server;

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Core's own REST server global; this is how the WP test suite boots REST.
		$wp_rest_server = new \WP_REST_Server();
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Firing core's hook, not declaring one.
		do_action( 'rest_api_init', $wp_rest_server );

		wp_cache_flush();
	}

	/**
	 * Create a published global header notice with the given content.
	 *
	 * @param string $content   Post content.
	 * @param bool   $with_type Whether to assign a courier_type term.
	 *
	 * @return int
	 */
	private function create_notice( string $content, bool $with_type = true ): int {
		$notice_id = self::factory()->post->create(
			array(
				'post_type'    => 'courier_notice',
				'post_status'  => 'publish',
				'post_content' => $content,
			)
		);

		wp_set_object_terms( $notice_id, 'Global', 'courier_scope', false );
		wp_set_object_terms( $notice_id, 'Header', 'courier_placement', false );

		if ( $with_type ) {
			wp_set_object_terms( $notice_id, 'Info', 'courier_type', false );
		}

		return $notice_id;
	}

	/**
	 * Fetch the rendered HTML fragment for a notice via the display endpoint.
	 *
	 * @param int $notice_id Notice to pull from the response.
	 *
	 * @return string
	 */
	private function fetch_fragment( int $notice_id ): string {
		$request = new WP_REST_Request( 'GET', '/courier-notices/v1/notices/display' );
		$request->set_param( 'placement', 'header' );
		$request->set_param( 'format', 'html' );

		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$data = $response->get_data();

		$this->assertArrayHasKey( $notice_id, $data['notices'], 'The notice must be in the response.' );

		return $data['notices'][ $notice_id ];
	}

	/**
	 * Static block content renders as HTML — no block comment delimiters,
	 * real markup in the fragment.
	 *
	 * @return void
	 */
	public function test_static_block_content_renders_in_the_fragment(): void {
		$notice_id = $this->create_notice(
			"<!-- wp:paragraph -->\n<p>Hello from the block editor</p>\n<!-- /wp:paragraph -->"
		);

		$fragment = $this->fetch_fragment( $notice_id );

		$this->assertStringContainsString( '<p>Hello from the block editor</p>', $fragment );
		$this->assertStringNotContainsString( 'wp:paragraph', $fragment, 'Block delimiters must not leak into rendered output.' );
	}

	/**
	 * Dynamic blocks render server-side — they used to output nothing at all.
	 *
	 * @return void
	 */
	public function test_dynamic_block_content_renders_in_the_fragment(): void {
		self::factory()->post->create( array( 'post_title' => 'A published post for latest-posts' ) );

		$notice_id = $this->create_notice( '<!-- wp:latest-posts /-->' );

		$fragment = $this->fetch_fragment( $notice_id );

		$this->assertStringContainsString( 'wp-block-latest-posts', $fragment, 'A dynamic block must render its output.' );
		$this->assertStringContainsString( 'A published post for latest-posts', $fragment );
	}

	/**
	 * Classic content passes through byte-identically — no wpautop, no
	 * rendering surprises for the notices existing sites already have.
	 *
	 * @return void
	 */
	public function test_classic_content_is_untouched(): void {
		$notice_id = $this->create_notice( 'Plain classic <strong>content</strong> stays as-is' );

		$fragment = $this->fetch_fragment( $notice_id );

		$this->assertStringContainsString( 'Plain classic <strong>content</strong> stays as-is', $fragment );
		$this->assertStringNotContainsString( '<p>Plain classic', $fragment, 'Classic content must not gain wpautop paragraphs.' );
	}

	/**
	 * A courier/notice block IS the notice: the fragment is the block's own
	 * chrome — id hook, type class, layout class, dismiss affordance, the
	 * opted-in title — with no legacy template double-wrap around it.
	 *
	 * @return void
	 */
	public function test_a_courier_notice_block_renders_as_itself(): void {
		$notice_id = $this->create_notice(
			'<!-- wp:courier/notice {"layout":"informational","showTitle":true} -->' .
			"<!-- wp:paragraph -->\n<p>Block notice body</p>\n<!-- /wp:paragraph -->" .
			'<!-- /wp:courier/notice -->'
		);
		update_post_meta( $notice_id, '_courier_dismissible', 1 );

		$fragment = $this->fetch_fragment( $notice_id );

		$this->assertStringContainsString( 'wp-block-courier-notice', $fragment );
		$this->assertStringContainsString( 'courier-layout-informational', $fragment );
		$this->assertStringContainsString( 'courier_type-info', $fragment, 'The type class must ride on the block for the color CSS.' );
		$this->assertStringContainsString( 'data-courier-notice-id="' . $notice_id . '"', $fragment, 'The dismissal JS hook must be present.' );
		$this->assertStringContainsString( 'data-closable', $fragment );
		$this->assertStringContainsString( 'courier-close', $fragment );
		$this->assertStringContainsString( 'courier-notice-title', $fragment, 'The opted-in title must render.' );
		$this->assertStringContainsString( '<p>Block notice body</p>', $fragment );
		$this->assertSame( 1, substr_count( $fragment, 'courier-content-wrapper' ), 'The legacy template must not double-wrap a block notice.' );
	}

	/**
	 * The icon block follows the notice type's _courier_type_icon term
	 * meta — the same source the legacy templates read — and an explicit
	 * attribute overrides it.
	 *
	 * @return void
	 */
	public function test_the_notice_icon_follows_the_type_and_honors_overrides(): void {
		$term = wp_insert_term( 'Breaking', 'courier_type' );
		add_term_meta( $term['term_id'], '_courier_type_icon', 'warning', true );

		$following = $this->create_notice(
			'<!-- wp:courier/notice --><!-- wp:courier/notice-icon /-->' .
			"<!-- wp:paragraph -->\n<p>Following</p>\n<!-- /wp:paragraph --><!-- /wp:courier/notice -->",
			false
		);
		wp_set_object_terms( $following, 'Breaking', 'courier_type', false );

		$overridden = $this->create_notice(
			'<!-- wp:courier/notice --><!-- wp:courier/notice-icon {"icon":"success"} /-->' .
			"<!-- wp:paragraph -->\n<p>Overridden</p>\n<!-- /wp:paragraph --><!-- /wp:courier/notice -->"
		);

		$this->assertStringContainsString( 'courier-icon icon-warning', $this->fetch_fragment( $following ), 'The icon must follow the type term meta.' );
		$this->assertStringContainsString( 'courier-icon icon-success', $this->fetch_fragment( $overridden ), 'An explicit icon must win over the type.' );
	}

	/**
	 * The block's layout drives the legacy delivery terms: popup-modal
	 * routes as a modal, and leaving the modal layout leaves the modal
	 * placement too.
	 *
	 * @return void
	 */
	public function test_notice_layout_syncs_the_delivery_terms(): void {
		$modal_content = '<!-- wp:courier/notice {"layout":"popup-modal"} -->' .
			"<!-- wp:paragraph -->\n<p>Modal body</p>\n<!-- /wp:paragraph -->" .
			'<!-- /wp:courier/notice -->';

		$notice_id = self::factory()->post->create(
			array(
				'post_type'    => 'courier_notice',
				'post_status'  => 'publish',
				'post_content' => $modal_content,
			)
		);

		$this->assertTrue( has_term( 'popup-modal', 'courier_style', $notice_id ), 'A modal layout must set the modal style term.' );
		$this->assertTrue( has_term( 'popup-modal', 'courier_placement', $notice_id ), 'A modal layout must route to the modal placement.' );

		wp_update_post(
			array(
				'ID'           => $notice_id,
				'post_content' => str_replace( 'popup-modal', 'informational', $modal_content ),
			)
		);

		$this->assertTrue( has_term( 'informational', 'courier_style', $notice_id ) );
		$this->assertTrue( has_term( 'header', 'courier_placement', $notice_id ), 'Leaving the modal layout must leave the modal placement.' );
	}

	/**
	 * Block-support styles generated during the fragment render ride along
	 * in the response's styles key, ready for the consumer to inject —
	 * they can never reach the already-loaded page on their own.
	 *
	 * @return void
	 */
	public function test_block_support_styles_ride_in_the_response(): void {
		// Explicit spacing values serialize into the saved markup and travel
		// inline; it is RENDER-time styles - layout rules like flex - that
		// only exist in the style engine store and would otherwise be lost.
		$this->create_notice(
			'<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->' .
			'<div class="wp-block-group">' .
			"<!-- wp:paragraph -->\n<p>Flexed</p>\n<!-- /wp:paragraph -->" .
			'</div><!-- /wp:group -->'
		);

		$request = new WP_REST_Request( 'GET', '/courier-notices/v1/notices/display' );
		$request->set_param( 'placement', 'header' );
		$request->set_param( 'format', 'html' );

		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertArrayHasKey( 'styles', $data );
		$this->assertStringContainsString( 'flex-wrap:nowrap', $data['styles'], 'The render-time layout CSS must be in the payload.' );
	}
}
