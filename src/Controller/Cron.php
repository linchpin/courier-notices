<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Cron Controller.
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

/**
 * Cron Class
 */
class Cron {


	/**
	 * Courier_Cron constructor.
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_filter( 'cron_schedules', array( $this, 'add_courier_cron_interval' ) );

		add_action( 'courier_purge', array( $this, 'courier_purge' ) );
		add_action( 'courier_expire', array( $this, 'courier_expire' ) );

		add_action( 'init', array( $this, 'init' ) );

	}


	/**
	 * Add our events for expiring notices
	 *
	 * @since 1.0.5
	 */
	public function init() {
		if ( ! wp_next_scheduled( 'courier_purge' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'courier_purge_cron_interval', 'courier_purge' );
		}

		if ( ! wp_next_scheduled( 'courier_expire' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'courier_expire_cron_interval', 'courier_expire' );
		}

	}


	/**
	 * Add new schedules for the cron to run every 5 minutes
	 *
	 * @since 1.0.5
	 *
	 * @param array $schedules Cron Schedules
	 */
	public function add_courier_cron_interval( $schedules ) {
		$schedules['courier_purge_cron_interval'] = array(
			'interval' => 300,
			'display'  => esc_html__( 'Every 5 Minutes', 'courier-notices' ),
		);

		$schedules['courier_expire_cron_interval'] = array(
			'interval' => 300,
			'display'  => esc_html__( 'Every 5 Minutes', 'courier-notices' ),
		);

		return $schedules;

	}


	/**
	 * Delete Courier notices that are older than 6 months.
	 *
	 * @since 1.0
	 */
	public function courier_purge() {
		$args = array(
			'post_type'      => 'courier_notice',
			'offset'         => 0,
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'date_query'     => array(
				array(
					'column' => 'post_date',
					'before' => '6 months ago',
				),
			),
		);

		$notices_query = new \WP_Query( $args );

		while ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post ) {
				wp_trash_post( $post );
			}

			$args['offset'] = $args['offset'] + $args['posts_per_page'];
			$notices_query  = new \WP_Query( $args );
		}

		wp_cache_delete( 'courier-global-header-notices', 'courier-notices' );
		wp_cache_delete( 'courier-global-footer-notices', 'courier-notices' );

	}


	/**
	 * Expire notices if their expiration date has passed.
	 *
	 * @since 1.0
	 */
	public function courier_expire() {
		$args = array(
			'post_type'      => 'courier_notice',
			'offset'         => 0,
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_courier_expiration',
					'value'   => current_time( 'timestamp' ),
					'compare' => '<',
					'type'    => 'NUMERIC',
				),
			),
		);

		$notices_query = new \WP_Query( $args );

		while ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post ) {
				wp_update_post(
					array(
						'ID'          => $post,
						'post_status' => 'courier_expired',
					)
				);
			}

			$args['offset'] = $args['offset'] + $args['posts_per_page'];
			$notices_query  = new \WP_Query( $args );
		}

		wp_cache_delete( 'courier-global-header-notices', 'courier-notices' );
		wp_cache_delete( 'courier-global-footer-notices', 'courier-notices' );

	}


}
