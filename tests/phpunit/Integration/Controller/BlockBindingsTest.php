<?php
/**
 * Integration tests for the courier/notice block bindings source.
 *
 * The informational layout binds its message paragraph to this source so a
 * notice can be adjusted dynamically. The contract that matters is the
 * authored-by-default one: the source returns null for `message`, core skips
 * a null binding, and the paragraph the author wrote survives into
 * post_content and onto the legacy render path.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Controller;

use CourierNotices\Controller\Block_Bindings;
use WP_UnitTestCase;

/**
 * Class BlockBindingsTest
 */
final class BlockBindingsTest extends WP_UnitTestCase {

	/**
	 * Re-register the post type and the bindings source; the suite tears both
	 * down between tests while a real site re-registers them every request.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		( new \CourierNotices\Controller\Courier_Notices() )->register_custom_post_type();
		( new Block_Bindings() )->register_source();

		wp_cache_flush();
	}

	/**
	 * Create a published notice.
	 *
	 * @param array $args Post field overrides.
	 *
	 * @return int
	 */
	private function create_notice( array $args = array() ): int {
		return self::factory()->post->create(
			wp_parse_args(
				$args,
				array(
					'post_type'    => 'courier_notice',
					'post_status'  => 'publish',
					'post_title'   => 'Scheduled maintenance tonight',
					'post_content' => 'Notice body',
				)
			)
		);
	}

	/**
	 * Render a bound paragraph in the notice's context, the way the notice
	 * block's inner blocks are rendered.
	 *
	 * @param int    $notice_id Notice providing context.
	 * @param string $key       Binding key.
	 * @param string $authored  The authored paragraph text.
	 *
	 * @return string
	 */
	private function render_bound_paragraph( int $notice_id, string $key, string $authored = 'Authored message' ): string {
		$markup = sprintf(
			'<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"courier/notice","args":{"key":"%s"}}}}} --><p>%s</p><!-- /wp:paragraph -->',
			$key,
			$authored
		);

		$parsed = parse_blocks( $markup );

		$block = new \WP_Block(
			$parsed[0],
			array(
				'postId'   => $notice_id,
				'postType' => 'courier_notice',
			)
		);

		return $block->render( array( 'dynamic' => false ) );
	}

	/**
	 * The source is registered under the name the editor also registers.
	 *
	 * @return void
	 */
	public function test_the_source_is_registered(): void {
		$sources = get_all_registered_block_bindings_sources();

		$this->assertArrayHasKey( 'courier/notice', $sources );
		$this->assertSame( 'courier/notice', Block_Bindings::SOURCE_NAME );
		$this->assertContains( 'postId', $sources['courier/notice']->uses_context );
	}

	/**
	 * Registering twice must not fatal — init runs more than once in the
	 * suite, and Pro or a site could register the name first.
	 *
	 * @return void
	 */
	public function test_registering_the_source_twice_is_safe(): void {
		( new Block_Bindings() )->register_source();
		( new Block_Bindings() )->register_source();

		$this->assertArrayHasKey( 'courier/notice', get_all_registered_block_bindings_sources() );
	}

	/**
	 * The contract that protects the legacy render path: `message` resolves to
	 * null, core skips the binding, and the authored paragraph renders.
	 *
	 * @return void
	 */
	public function test_the_message_key_leaves_the_authored_content_alone(): void {
		$notice_id = $this->create_notice();

		$rendered = $this->render_bound_paragraph( $notice_id, 'message', 'What the author actually wrote' );

		$this->assertStringContainsString( 'What the author actually wrote', $rendered );
	}

	/**
	 * And the seam that makes it dynamic: a filter supplies a value, which
	 * then wins over the authored content.
	 *
	 * @return void
	 */
	public function test_the_filter_can_substitute_a_dynamic_message(): void {
		$notice_id = $this->create_notice();

		add_filter(
			'courier_notices_binding_value',
			static function ( $value, $key ) {
				return 'message' === $key ? 'Substituted at render time' : $value;
			},
			10,
			2
		);

		$rendered = $this->render_bound_paragraph( $notice_id, 'message', 'What the author actually wrote' );

		remove_all_filters( 'courier_notices_binding_value' );

		$this->assertStringContainsString( 'Substituted at render time', $rendered );
		$this->assertStringNotContainsString( 'What the author actually wrote', $rendered );
	}

	/**
	 * The notice's own values resolve from the postId in context.
	 *
	 * @return void
	 */
	public function test_the_title_key_resolves_the_notice_title(): void {
		$notice_id = $this->create_notice( array( 'post_title' => 'Payment failed' ) );

		$this->assertStringContainsString(
			'Payment failed',
			$this->render_bound_paragraph( $notice_id, 'title' )
		);
	}

	/**
	 * The type key resolves the courier_type term name.
	 *
	 * @return void
	 */
	public function test_the_type_key_resolves_the_notice_type_label(): void {
		$notice_id = $this->create_notice();

		wp_set_object_terms( $notice_id, 'Alert', 'courier_type', false );

		$this->assertStringContainsString(
			'Alert',
			$this->render_bound_paragraph( $notice_id, 'type' )
		);
	}

	/**
	 * Expiration renders in the site timezone, and an unset expiration is
	 * empty rather than an epoch date.
	 *
	 * @return void
	 */
	public function test_the_expiration_key_renders_in_the_site_timezone(): void {
		update_option( 'timezone_string', 'America/New_York' );
		update_option( 'date_format', 'Y-m-d' );
		update_option( 'time_format', 'H:i' );

		$notice_id = $this->create_notice();

		// Noon in New York on an August day is 16:00 UTC.
		update_post_meta( $notice_id, '_courier_expiration', strtotime( '2026-08-11 16:00:00 UTC' ) );

		$this->assertStringContainsString(
			'2026-08-11 12:00',
			$this->render_bound_paragraph( $notice_id, 'expiration' )
		);
	}

	/**
	 * A notice with no expiration binds to an empty string, not a fallback
	 * date and not the authored text.
	 *
	 * @return void
	 */
	public function test_an_unset_expiration_binds_to_nothing(): void {
		$notice_id = $this->create_notice();

		$rendered = $this->render_bound_paragraph( $notice_id, 'expiration', 'Authored fallback' );

		$this->assertStringNotContainsString( 'Authored fallback', $rendered );
		$this->assertStringNotContainsString( '1970', $rendered );
	}

	/**
	 * The source must not resolve against posts that are not notices, or when
	 * there is no post in context at all.
	 *
	 * @return void
	 */
	public function test_the_source_ignores_posts_that_are_not_notices(): void {
		$page_id = self::factory()->post->create(
			array(
				'post_type'  => 'page',
				'post_title' => 'An ordinary page',
			)
		);

		$rendered = $this->render_bound_paragraph( $page_id, 'title', 'Authored message' );

		$this->assertStringContainsString( 'Authored message', $rendered );
		$this->assertStringNotContainsString( 'An ordinary page', $rendered );
	}

	/**
	 * An unknown key leaves the authored attribute alone rather than emitting
	 * an empty paragraph.
	 *
	 * @return void
	 */
	public function test_an_unknown_key_leaves_the_authored_content_alone(): void {
		$notice_id = $this->create_notice();

		$this->assertStringContainsString(
			'Authored message',
			$this->render_bound_paragraph( $notice_id, 'no-such-key', 'Authored message' )
		);
	}
}
