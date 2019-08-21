<?php

namespace Courier\Controller;

/**
 * Class Courier_Types
 * @package Courier\Controller
 */
class Courier_Types {

	/**
	 * Register our actions for courier type administration.
	 */
	public function register_actions() {
		add_action( 'wp_ajax_courier_notices_add_type', array( $this, 'add_type' ) );
		add_action( 'wp_ajax_courier_notices_update_type', array( $this, 'update_type' ) );
		add_action( 'wp_ajax_courier_notices_delete_type', array( $this, 'delete_type' ) );
	}

	/**
	 * Add a new Courier Type by Ajax.
	 *
	 * @since 1.0
	 */
	public function add_type() {

		check_ajax_referer( 'courier_notices_add_type_nonce', 'courier_notices_add_type' );

		if ( ! current_user_can( 'edit_courier_notices' ) ) {
			wp_die( - 1 );
		}

		wp_die( 1 );
	}

	/**
	 * Update a courier notice type
	 */
	public function update_type() {

		check_ajax_referer( 'courier_notices_update_type_nonce', 'courier_notices_update_type' );

		if ( empty( $_POST['courier_notice_id'] ) ) {
			return wp_json_encode( -1 );
		}

		if ( ! current_user_can( 'edit_courier_notices' ) ) {
			return wp_json_encode( -1 );
		}

		return wp_json_encode( 1 );
	}

	/**
	 * Delete a courier notice type.
	 */
	public function delete_type() {

		check_ajax_referer( 'courier_notices_delete_type_nonce', 'courier_notices_delete_type' );

		if ( empty( $_POST['courier_notice_id'] ) ) {
			return wp_json_encode( -1 );
		}

		if ( ! current_user_can( 'delete_courier_notices' ) ) {
			return wp_json_encode( -1 );
		}

		return wp_json_encode( 1 );
	}
}
