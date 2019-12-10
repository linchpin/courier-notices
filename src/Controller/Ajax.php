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
		add_action( 'template_redirect', array( $this, 'template_redirect_user_search' ) );

		add_action( 'wp_ajax_courier_dismiss_notification', array( $this, 'dismiss_notification' ) );
	}

	/**
	 * Register our endpoint for Courier AJAX requests
	 *
	 * @since 1.0
	 */
	public function init() {
		add_rewrite_tag( '%courier_reactivate_notice_id%', '([0-9]+)' );
		add_rewrite_rule( 'courier/reactivate/(.*)/?', 'index.php?courier_reactivate_notice_id=$matches[1]', 'top' );

		add_rewrite_tag( '%courier_user_search%', '([0-9]+)' );
		add_rewrite_rule( 'courier/user-search/(.*)/?', 'index.php?courier_user_search=$matches[1]', 'top' );
	}

	/**
	 * Add the ability to store when notifications are dismissed
	 *
	 * @since 1.0
	 */
	public function dismiss_notification() {
		check_ajax_referer( 'courier_dismiss_notification_nonce', 'courier_dismiss_notification_nonce' );

		$user_id = get_current_user_id();

		if ( ! isset( $_POST['courier_notification_type'] ) || empty( $_POST['courier_notification_type'] ) ) { // Input var okay.
			return -1;
		}

		$notification_type = sanitize_title( wp_unslash( $_POST['courier_notification_type'] ) ); // Input var okay.
		$notifications     = maybe_unserialize( get_user_option( 'courier_notifications', $user_id ) );

		if ( empty( $notifications ) ) {
			$notifications = array();
		}

		$notifications[ $notification_type ] = '1';

		if ( current_user_can( 'edit_posts' ) ) {
			update_user_option( $user_id, 'courier_notifications', $notifications );
			wp_die( 1 );
		}
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
	public function template_redirect_user_search() {
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
