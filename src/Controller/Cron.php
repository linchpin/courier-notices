<?php

namespace Courier\Controller;

/**
 * Class Cron
 * @package Courier\Controller
 */
class Cron {
	/**
	 * Courier_Cron constructor.
	 */
	public function register_actions() {

		// Load cron functionality.
		if ( ! defined( 'DOING_CRON' ) || ! DOING_CRON ) {
			return;
		}

		add_action( 'courier_purge', array( $this, 'courier_purge' ) );
		add_action( 'courier_expire', array( $this, 'courier_expire' ) );
	}

	/**
	 * Delete Courier notices that are older than 6 months.
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

		wp_cache_delete( 'courier-global-notices', 'courier' );
	}

	/**
	 * Expire notices if their expiration date has passed.
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

		wp_cache_delete( 'courier-global-notices', 'courier' );
	}
}
