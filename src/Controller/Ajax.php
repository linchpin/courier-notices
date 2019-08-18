<?php
/**
 * Handles everything specific to AJAX calls/responses
 *
 * @package Courier\Controller
 */

namespace Courier\Controller;

/**
 * Ajax Class
 */
class Ajax {

	/**
	 * Register the hooks and filters
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect_admin' ) );
	}

	/**
	 * Register our endpoint for Courier AJAX requests
	 *
	 * @since 1.0
	 */
	public function init() {
		add_rewrite_tag( '%courier_notice_id%', '([0-9]+)' );
		add_rewrite_rule( 'courier/notice/([0-9\,]+)/?', 'index.php?courier_notice_id=$matches[1]', 'top' );

		add_rewrite_tag( '%courier_reactivate_notice_id%', '([0-9]+)' );
		add_rewrite_rule( 'courier/reactivate/(.*)/?', 'index.php?courier_reactivate_notice_id=$matches[1]', 'top' );

		add_rewrite_tag( '%courier_user_search%', '([0-9]+)' );
		add_rewrite_rule( 'courier/user-search/(.*)/?', 'index.php?courier_user_search=$matches[1]', 'top' );
	}

	/**
	 * Detect and handle Courier AJAX requests
	 *
	 * @since 1.0
	 */
	public function template_redirect() {
		global $wp_query;

		if ( ! empty( $wp_query->get( 'courier_notice_id' ) ) ) {
			$notice_ids = array_filter( explode( ',', $wp_query->get( 'courier_notice_id' ) ) );

			if ( ! empty( $notice_ids ) ) {
				define( 'DOING_AJAX', true );

				// Only logged in users can dismiss notices.
				if ( ! is_user_logged_in() ) {
					wp_send_json( -1 );
				}

				$result = courier_dismiss_notices( $notice_ids );

				if ( is_wp_error( $result ) ) {
					wp_send_json( $result );
				}

				wp_send_json( 1 );
			}
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( ! empty( $wp_query->get( 'courier_reactivate_notice_id' ) ) ) {
			define( 'DOING_AJAX', true );

			$post_id = (int) $wp_query->get( 'courier_reactivate_notice_id' );

			wp_remove_object_terms( $post_id, array( 'dismissed' ), 'courier_status' );

			wp_send_json( 1 );
		}
	}

	/**
	 * Perform a user search for assigning a notice to a user.
	 *
	 * @since 1.0
	 */
	public function template_redirect_admin() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		global $wp_query;

		if ( empty( $wp_query->get( 'courier_user_search' ) ) ) {
			return;
		}

		$search_term = '*' . sanitize_text_field( $wp_query->get( 'courier_user_search' ) ) . '*';
		$results     = array();

		$user_search = new \WP_User_Query(
			array(
				'search'         => $search_term,
				'search_columns' => array(
					'user_login',
					'user_email',
					'user_nicename',
				),
				'number'         => 30,
				'orderby'        => 'login',
				'order'          => 'ASC',
				'fields'         => array(
					'ID',
					'display_name',
					'user_email',
				),
			)
		);

		if ( ! empty( $user_search->results ) ) {
			$results = $user_search->results;
		}

		wp_send_json( $results );
	}
}
