<?php

namespace Courier\Controller;

use Courier\Controller\Admin\Fields\Fields;

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
			return wp_json_encode( -1 );
		}

		$notice_type_title = sanitize_text_field( $_POST['courier_notice_type_new_title'] );

		// If not css class is passed, fall back to the title
		if ( ! isset( $_POST['courier_notice_type_new_css_class'] ) || '' === $_POST['courier_notice_type_new_css_class'] ) {
			$notice_type_class = $_POST['courier_notice_type_new_title'];
		} else {
			$notice_type_class = $_POST['courier_notice_type_new_css_class'];
		}

		$notice_type_class      = sanitize_html_class( $notice_type_class );
		$notice_type_color      = sanitize_hex_color( $_POST['courier_notice_type_new_color'] );
		$notice_type_text_color = sanitize_hex_color( $_POST['courier_notice_type_new_text_color'] );

		$type = wp_insert_term( $notice_type_title, 'courier_type' );

		if ( ! is_wp_error( $type ) ) {
			$this->insert_term_meta( $type, $notice_type_class, $notice_type_color, $notice_type_text_color );
		} else {
			wp_die( $type );
		}

		$data = array(
			'field'       => 'notice_type_designs',
			'section'     => 'courier_design',
			'options'     => 'courier_design',
			'label'       => esc_html__( 'Courier Types', 'courier' ),
			'description' => esc_html__( 'From this panel you can create and edit different types of Courier notices.', 'courier' ),
		);

		ob_start();
		Fields::add_table( $data );
		$table = ob_get_contents();
		ob_end_clean();

		echo wp_json_encode(
			array(
				'success' => $type,
				'table'   => $table,
			)
		);
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

		if ( empty( $_POST['courier_notices_type'] ) ) {
			return wp_json_encode( -1 );
		}

		if ( ! current_user_can( 'delete_courier_notices' ) ) {
			return wp_json_encode( -1 );
		}

		$term_id = (int) $_POST['courier_notices_type'];

		// Confirm that the term does indeed exist.
		if ( term_exists( $term_id, 'courier_type' ) ) {
			$deleted = wp_delete_term( $term_id, 'courier_type', array( 'force_default' => true ) );

			if ( is_wp_error( $deleted ) ) {
				return wp_json_encode( -1 );
			}
		}

		return wp_json_encode( 1 );
	}


	/**
	 * Adds in our term meta for our courier types
	 *
	 * @since 1.0
	 *
	 * @param array  $term        The term to add meta to.
	 * @param string $class_name  The class name.
	 * @param string $hex_color   The hex color.
	 * @param string $label_color The hex color label.
	 */
	private function insert_term_meta( $term, $class_name, $hex_color, $label_color ) {
		add_term_meta( $term['term_id'], '_courier_type_color', $hex_color, true );
		add_term_meta( $term['term_id'], '_courier_type_label', $label_color, true );
		add_term_meta( $term['term_id'], '_courier_type_icon', $class_name, true );
	}
}
