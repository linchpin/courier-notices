<?php
/**
 * The Courier Pro hook contract.
 *
 * Pro integrates with the free plugin ONLY through its public hooks, so
 * these tests assert not just that each hook fires but the shape of what it
 * receives — the argument contract Pro's visibility engine and settings
 * screens are written against. Phase 1's synthesized request array widens
 * the second argument of the query filter; these tests are the floor it
 * must keep.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration;

use CourierNotices\Model\Courier_Notice\Data;
use CourierNotices\Model\Settings;
use WP_UnitTestCase;

/**
 * Class HookContractTest
 */
final class HookContractTest extends WP_UnitTestCase {

	/**
	 * Clear plugin caches so every test drives the cold query path where
	 * the filters actually fire.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		wp_cache_flush();
	}

	/**
	 * courier_notices_display_notices_query fires with two arguments: the
	 * WP_Query arguments, and a request-shaped array carrying the keys the
	 * live REST path posts today. Pro's visibility engine reads the second
	 * argument to decide who sees what.
	 *
	 * @return void
	 */
	public function test_display_notices_query_filter_receives_the_request_shape(): void {
		$captured_query_args = null;
		$captured_request    = null;

		add_filter(
			'courier_notices_display_notices_query',
			static function ( $query_args, $ajax_post_data ) use ( &$captured_query_args, &$captured_request ) {
				$captured_query_args = $query_args;
				$captured_request    = $ajax_post_data;

				return $query_args;
			},
			10,
			2
		);

		( new Data() )->get_notices( array( 'number' => 21 ) );

		$this->assertIsArray( $captured_query_args, 'The filter must fire on the cold path.' );
		$this->assertSame( 'courier_notice', $captured_query_args['post_type'] );
		$this->assertArrayHasKey( 'post__in', $captured_query_args );

		$this->assertIsArray( $captured_request );

		foreach ( array( 'user_id', 'include_global', 'include_dismissed', 'prioritize_persistent_global', 'ids_only', 'number', 'placement', 'style' ) as $key ) {
			$this->assertArrayHasKey( $key, $captured_request, "The second argument must carry '{$key}' — Pro reads this shape." );
		}

		$this->assertSame( 'header', $captured_request['placement'] );
	}

	/**
	 * Filter output must reach the query. The final query always carries
	 * post__in (the merged global list), and WP_Query ignores post__not_in
	 * whenever post__in is present — so the only way a filter can exclude a
	 * notice is to edit post__in itself. That constraint is part of the
	 * contract Pro codes against.
	 *
	 * @return void
	 */
	public function test_query_filter_output_reaches_the_query(): void {
		$kept    = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'publish',
			)
		);
		$blocked = self::factory()->post->create(
			array(
				'post_type'   => 'courier_notice',
				'post_status' => 'publish',
			)
		);

		foreach ( array( $kept, $blocked ) as $post_id ) {
			wp_set_object_terms( $post_id, 'Global', 'courier_scope', false );
			wp_set_object_terms( $post_id, 'Header', 'courier_placement', false );
		}

		add_filter(
			'courier_notices_display_notices_query',
			static function ( $query_args ) use ( $blocked ) {
				$query_args['post__in'] = array_values(
					array_diff( (array) $query_args['post__in'], array( $blocked ) )
				);

				if ( array() === $query_args['post__in'] ) {
					$query_args['post__in'] = array( 0 );
				}

				return $query_args;
			},
			10,
			2
		);

		$ids = ( new Data() )->get_notices( array( 'number' => 22 ) );

		$this->assertContains( $kept, $ids );
		$this->assertNotContains( $blocked, $ids, 'A visibility filter must be able to exclude a notice.' );
	}

	/**
	 * The post-filter wp_parse_args at Data.php:373 leaks the request
	 * arguments into WP_Query — user_id, include_global and friends become
	 * query vars, and any colliding key silently overrides filter output.
	 * This pins today's semantics so Phase 1's decision to keep or remove
	 * the line is a deliberate, visible change.
	 *
	 * @return void
	 */
	public function test_request_args_currently_leak_into_the_final_query(): void {
		$leaked = array();

		add_action(
			'pre_get_posts',
			static function ( $query ) use ( &$leaked ) {
				if ( 'courier_notice' === $query->get( 'post_type' ) && '' !== $query->get( 'user_id' ) ) {
					$leaked[] = $query->get( 'user_id' );
				}
			}
		);

		( new Data() )->get_notices(
			array(
				'number'  => 23,
				'user_id' => 424242,
			)
		);

		$this->assertSame(
			array( 424242 ),
			$leaked,
			'Removing the post-filter wp_parse_args changes filter semantics; if this fails because the leak is gone, update the Pro contract notes for COURIER-1028 and delete this test deliberately.'
		);
	}

	/**
	 * courier_notices_allowed_settings admits Pro's settings keys — a
	 * filtered-in key must be accepted by the save path against real
	 * WordPress options.
	 *
	 * @return void
	 */
	public function test_allowed_settings_filter_admits_new_keys(): void {
		add_filter(
			'courier_notices_allowed_settings',
			static function ( $defaults ) {
				$defaults['licenseKey'] = '';

				return $defaults;
			}
		);

		$result = ( new Settings() )->save_setting( 'licenseKey', 'pro-key' );

		$this->assertIsArray( $result );
		$this->assertSame( 'pro-key', $result['licenseKey'] );
		$this->assertSame( 'pro-key', ( new Settings() )->get_setting( 'licenseKey' ) );
	}

	/**
	 * courier_notices_safe_markup can widen the kses allowlist — Pro adds
	 * form elements through it.
	 *
	 * @return void
	 */
	public function test_safe_markup_filter_widens_the_allowlist(): void {
		add_filter(
			'courier_notices_safe_markup',
			static function ( $allowed ) {
				$allowed['button'] = array( 'class' => array() );

				return $allowed;
			}
		);

		$allowed = \CourierNotices\Helper\Utils::get_safe_markup();

		$this->assertArrayHasKey( 'button', $allowed );
		$this->assertSame(
			'<button class="x">Go</button>',
			wp_kses( '<button class="x" onclick="evil()">Go</button>', $allowed ),
			'The widened allowlist must pass the element while still stripping disallowed attributes.'
		);
	}
}
