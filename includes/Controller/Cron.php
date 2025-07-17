<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Cron Controller.
 *
 * @package CourierNotices\Controller
 * @deprecated 1.8.0 Use Action_Scheduler instead
 */

namespace CourierNotices\Controller;

/**
 * Cron Class
 *
 * @deprecated 1.8.0 This class is deprecated. Use Action_Scheduler instead.
 */
class Cron {


	/**
	 * Courier_Cron constructor.
	 *
	 * @since 1.0
	 * @deprecated 1.8.0 Use Action_Scheduler instead.
	 */
	public function register_actions() {
		// Skip cron registration if Action Scheduler is being used.
		if ( $this->is_action_scheduler_active() ) {
			return;
		}

		// Add deprecation notice for developers.
		if ( \defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			_deprecated_function( __METHOD__, '1.8.0', 'CourierNotices\Controller\Action_Scheduler::register_actions' );
		}

		add_action( 'courier_purge', [ $this, 'courier_purge' ] );
		add_action( 'courier_expire', [ $this, 'courier_expire' ] );

		add_action( 'init', [ $this, 'init' ] );
	}


	/**
	 * Add our events for expiring notices
	 *
	 * @since 1.0.5
	 * @deprecated 1.8.0 Use Action_Scheduler instead
	 */
	public function init() {
		// Skip cron initialization if Action Scheduler is being used.
		if ( $this->is_action_scheduler_active() ) {
			return;
		}

		if ( ! wp_next_scheduled( 'courier_purge' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'courier_purge_cron_interval', 'courier_purge' );
		}

		if ( ! wp_next_scheduled( 'courier_expire' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'courier_expire_cron_interval', 'courier_expire' );
		}
	}


	/**
	 * Delete Courier notices that are older than 6 months.
	 *
	 * @since 1.0
	 * @deprecated 1.8.0 Use Action_Scheduler instead.
	 */
	public function courier_purge() {
		$args = [
			'post_type'      => 'courier_notice',
			'offset'         => 0,
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'date_query'     => [
				[
					'column' => 'post_date',
					'before' => '6 months ago',
				],
			],
		];

		$notices_query = new \WP_Query( $args );

		while ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post ) {
				wp_trash_post( $post );
			}

			$args['offset'] = $args['offset'] + $args['posts_per_page'];
			$notices_query  = new \WP_Query( $args );
		}

		// Clear cache to ensure changes are immediately reflected.
		courier_notices_clear_cache();
	}


	/**
	 * Expire notices if their expiration date has passed.
	 *
	 * @since 1.0
	 * @deprecated 1.8.0 Use Action_Scheduler instead
	 */
	public function courier_expire() {
		$args = [
			'post_type'      => 'courier_notice',
			'offset'         => 0,
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'     => '_courier_expiration',
					'value'   => time(),
					'compare' => '<',
					'type'    => 'NUMERIC',
				],
			],
		];

		$notices_query = new \WP_Query( $args );

		while ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post ) {
				wp_update_post(
					[
						'ID'          => $post,
						'post_status' => 'courier_expired',
					]
				);
			}

			$args['offset'] = $args['offset'] + $args['posts_per_page'];
			$notices_query  = new \WP_Query( $args );
		}

		// Clear cache to ensure changes are immediately reflected.
		courier_notices_clear_cache();
	}


	/**
	 * Check if Action Scheduler is active and should be used instead of cron
	 *
	 * @since 1.8.0
	 *
	 * @return bool True if Action Scheduler should be used
	 */
	private function is_action_scheduler_active() {
		$plugin_options = get_option( 'courier_notices_options', [] );
		return ! empty( $plugin_options['use_action_scheduler'] );
	}
}
