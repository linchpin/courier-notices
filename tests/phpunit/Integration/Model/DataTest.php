<?php
/**
 * Integration tests for the notice query model.
 *
 * Model\Courier_Notice\Data is the highest-risk code in the plugin and the
 * thing Phase 3's block depends on. These tests pin scope selection, the
 * caching layers, dismissal reads, and expiration — including an
 * inverse-pinned test for the COURIER-1028 cache-bypasses-filter bug.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Integration\Model;

use CourierNotices\Model\Courier_Notice\Data;
use WP_UnitTestCase;

/**
 * Class DataTest
 */
final class DataTest extends WP_UnitTestCase {

	/**
	 * Clear the plugin's caches between tests; the suite only rolls back the
	 * database, and Data caches into the object cache and transients.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		wp_cache_flush();
	}

	/**
	 * Create a published courier notice with the given terms.
	 *
	 * @param string $placement   Placement term.
	 * @param string $scope       Scope term.
	 * @param bool   $dismissible Whether to add the dismissible meta.
	 * @param array  $overrides   Post field overrides.
	 *
	 * @return int
	 */
	private function create_notice( string $placement = 'Header', string $scope = 'Global', bool $dismissible = false, array $overrides = array() ): int {
		$post_id = self::factory()->post->create(
			wp_parse_args(
				$overrides,
				array(
					'post_type'    => 'courier_notice',
					'post_status'  => 'publish',
					'post_content' => 'Notice body',
				)
			)
		);

		wp_set_object_terms( $post_id, $scope, 'courier_scope', false );
		wp_set_object_terms( $post_id, $placement, 'courier_placement', false );

		if ( $dismissible ) {
			update_post_meta( $post_id, '_courier_dismissible', 1 );
		}

		return $post_id;
	}

	/**
	 * Global notices for the requested placement are selected; other
	 * placements and non-global scopes are not.
	 *
	 * @return void
	 */
	public function test_get_notices_selects_global_notices_for_the_placement(): void {
		$persistent  = $this->create_notice( 'Header', 'Global', false );
		$dismissible = $this->create_notice( 'Header', 'Global', true );
		$footer      = $this->create_notice( 'Footer', 'Global', false );
		$user_scoped = $this->create_notice( 'Header', 'User', false );

		$ids = ( new Data() )->get_notices( array( 'number' => 10 ) );

		$this->assertContains( $persistent, $ids );
		$this->assertContains( $dismissible, $ids );
		$this->assertNotContains( $footer, $ids, 'A footer notice must not appear in the header placement.' );
		$this->assertNotContains( $user_scoped, $ids, 'A user-scoped notice must not ride in with the globals.' );
	}

	/**
	 * Expired notices no longer hold publish status and must drop out of the
	 * query once the Action Scheduler expiry has run.
	 *
	 * @return void
	 */
	public function test_expired_notices_are_excluded(): void {
		$live    = $this->create_notice( 'Header', 'Global', false );
		$expired = $this->create_notice( 'Header', 'Global', false, array( 'post_status' => 'courier_expired' ) );

		$ids = ( new Data() )->get_notices( array( 'number' => 11 ) );

		$this->assertContains( $live, $ids );
		$this->assertNotContains( $expired, $ids );
	}

	/**
	 * The warm path: identical arguments are served from cache without the
	 * query pipeline running again. This is the mechanism behind the
	 * COURIER-1028 bug — the cache key is only the two argument arrays, so
	 * anything a filter varies on beyond them is frozen at write time.
	 *
	 * @return void
	 */
	public function test_a_warm_cache_serves_identical_arguments_without_requerying(): void {
		$this->create_notice( 'Header', 'Global', false );

		$filter_runs = 0;

		add_filter(
			'courier_notices_display_notices_query',
			static function ( $query_args ) use ( &$filter_runs ) {
				++$filter_runs;

				return $query_args;
			},
			10,
			2
		);

		$data  = new Data();
		$cold  = $data->get_notices( array( 'number' => 12 ) );
		$warm  = $data->get_notices( array( 'number' => 12 ) );

		$this->assertSame( 1, $filter_runs, 'The second identical call must be answered from cache.' );
		$this->assertSame( $cold, $warm );
	}

	/**
	 * The COURIER-1028 cache-context contract: a filter that varies on
	 * context outside the two argument arrays declares that context through
	 * courier_notices_query_cache_context, and declared context is never
	 * served stale. (This test was the inverse pin for the bug; the
	 * contract landed and armed it.)
	 *
	 * @return void
	 */
	public function test_declared_filter_context_is_not_served_stale(): void {
		$visible = $this->create_notice( 'Header', 'Global', false );

		add_filter(
			'courier_notices_display_notices_query',
			static function ( $query_args ) {
				if ( ! empty( $GLOBALS['courier_notices_test_hide_all'] ) ) {
					$query_args['post__in'] = array( 0 );
				}

				return $query_args;
			},
			10,
			2
		);

		// The Pro-shaped half of the contract: whatever the query filter
		// varies on gets declared here so it reaches the cache key.
		add_filter(
			'courier_notices_query_cache_context',
			static function ( $context ) {
				$context['hide_all'] = ! empty( $GLOBALS['courier_notices_test_hide_all'] );

				return $context;
			}
		);

		$data = new Data();

		$cold = $data->get_notices( array( 'number' => 13 ) );
		$this->assertContains( $visible, $cold, 'Cold cache must show the notice.' );

		// Flip the context the filter varies on — think "user gained a role"
		// or "the time window closed" in Courier Pro's visibility engine.
		$GLOBALS['courier_notices_test_hide_all'] = true;

		$warm = $data->get_notices( array( 'number' => 13 ) );

		$this->assertNotContains( $visible, $warm, 'A declared context change must not be served stale.' );

		// Flipping back reuses the original cache entry — the context is
		// part of the key, not a cache buster.
		unset( $GLOBALS['courier_notices_test_hide_all'] );

		$this->assertSame( $cold, $data->get_notices( array( 'number' => 13 ) ) );
	}

	/**
	 * The other side of the contract boundary: context the filter varies on
	 * but does NOT declare cannot reach the cache key, so identical
	 * arguments are served from cache. Vary on it — declare it.
	 *
	 * @return void
	 */
	public function test_undeclared_filter_context_is_served_from_cache(): void {
		$visible = $this->create_notice( 'Header', 'Global', false );

		add_filter(
			'courier_notices_display_notices_query',
			static function ( $query_args ) {
				if ( ! empty( $GLOBALS['courier_notices_test_hide_all'] ) ) {
					$query_args['post__in'] = array( 0 );
				}

				return $query_args;
			},
			10,
			2
		);

		$data = new Data();
		$cold = $data->get_notices( array( 'number' => 14 ) );

		$GLOBALS['courier_notices_test_hide_all'] = true;

		$warm = $data->get_notices( array( 'number' => 14 ) );

		unset( $GLOBALS['courier_notices_test_hide_all'] );

		$this->assertSame( $cold, $warm );
		$this->assertContains( $visible, $warm );
	}

	/**
	 * Anonymous dismissals arrive in a cookie the query used to read inside
	 * the cached path while keying without it — one visitor's dismissal
	 * state served to everyone. The cookie is now part of the default cache
	 * context, so two anonymous visitors with different cookies cannot
	 * share a cache entry.
	 *
	 * @return void
	 */
	public function test_anonymous_dismissal_cookies_do_not_share_a_cache_entry(): void {
		$notice = $this->create_notice( 'Header', 'Global', false );

		wp_set_current_user( 0 );

		// prioritize_persistent_global re-adds persistent notices after the
		// dismissal filter, which would mask the cookie's effect entirely -
		// dismissal only observably filters this arg combination.
		$args = array(
			'number'                       => 15,
			'prioritize_persistent_global' => false,
		);

		// Visitor A has dismissed the notice.
		$_COOKIE['dismissed_notices'] = wp_json_encode( array( $notice ) );

		$visitor_a = ( new Data() )->get_notices( $args );

		// Visitor B has no dismissals; without cookie-aware keying B would
		// be served visitor A's cached, already-filtered result.
		unset( $_COOKIE['dismissed_notices'] );

		$visitor_b = ( new Data() )->get_notices( $args );

		$this->assertNotContains( $notice, $visitor_a, 'The dismissing visitor must not see the notice.' );
		$this->assertContains( $notice, $visitor_b, 'A visitor without dismissals must see the notice.' );
	}

	/**
	 * Logged-in dismissals come from the per-site user option, normalized
	 * to integers.
	 *
	 * @return void
	 */
	public function test_global_dismissed_notices_for_a_logged_in_user(): void {
		$user_id = self::factory()->user->create();

		wp_set_current_user( $user_id );
		update_user_option( $user_id, 'courier_dismissals', array( 3, '5' ) );

		$this->assertSame( array( 3, 5 ), ( new Data() )->get_global_dismissed_notices() );
	}

	/**
	 * Anonymous dismissals come from the dismissed_notices cookie.
	 *
	 * @return void
	 */
	public function test_global_dismissed_notices_for_an_anonymous_visitor(): void {
		wp_set_current_user( 0 );

		$_COOKIE['dismissed_notices'] = '[7,9]';

		$dismissed = ( new Data() )->get_global_dismissed_notices();

		unset( $_COOKIE['dismissed_notices'] );

		$this->assertSame( array( 7, 9 ), $dismissed );
	}

	/**
	 * An anonymous visitor without the cookie has no dismissals.
	 *
	 * @return void
	 */
	public function test_global_dismissed_notices_default_to_empty(): void {
		wp_set_current_user( 0 );

		$this->assertSame( array(), ( new Data() )->get_global_dismissed_notices() );
	}

	/**
	 * A malformed cookie is treated as no dismissals — json_decode returns
	 * null for garbage, which used to fatal in an array_map.
	 *
	 * @return void
	 */
	public function test_a_malformed_dismissal_cookie_is_ignored(): void {
		wp_set_current_user( 0 );

		$_COOKIE['dismissed_notices'] = 'not-json{{{';

		$dismissed = ( new Data() )->get_global_dismissed_notices();

		unset( $_COOKIE['dismissed_notices'] );

		$this->assertSame( array(), $dismissed );
	}
}
