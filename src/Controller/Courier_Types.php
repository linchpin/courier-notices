<?php

namespace Courier\Controller;

use Courier\Controller\Admin\Fields\Fields;
use Courier\Core\View;
use Courier\Helper\Utils;

/**
 * Class Courier_Types
 * @package Courier\Controller
 */
class Courier_Types {

	private $kses_template = array(
		'p'      => array(
			'class' => array(),
		),
		'button' => array(
			'class' => array(),
			'value' => array(),
			'type'  => array(),
			'id'    => array(),
		),
		'label'  => array(
			'for'   => array(),
			'class' => array(),
		),
		'input'  => array(
			'type'  => array(),
			'value' => array(),
			'class' => array(),
			'name'  => array(),
			'id'    => array(),
		),
		'span'   => array(
			'aria-hidden' => array(),
			'class'       => array(),
		),
		'pre'    => array(
			'class'          => array(),
			'id'             => array(),
			'data-css-class' => array(),
		),
		'div'    => array(
			'id'    => array(),
			'class' => array(),
		),
		'table'  => array(
			'class' => array(),
		),
		'thead'  => array(),
		'tbody'  => array(
			'id'    => array(),
			'class' => array(),
		),
		'tr'     => array(),
		'td'     => array(
			'data-colname' => array(),
			'class'        => array(),
			'scope'        => array(),
			'id'           => array(),
		),
		'th'     => array(
			'class' => array(),
			'scope' => array(),
			'id'    => array(),
		),
		'a'      => array(
			'class'        => array(),
			'href'         => array(),
			'data-term-id' => array(),
		),
		'strong' => array(
			'class'      => array(),
			'data-title' => array(),
		),
		'tfoot'  => array(),
		'br'     => array(
			'class' => array(),
		),
	);

	/**
	 * Register our actions for courier type administration.
	 */
	public function register_actions() {
		add_action( 'wp_ajax_courier_notices_add_type', array( $this, 'add_type' ) );
		add_action( 'wp_ajax_courier_notices_update_type', array( $this, 'update_type' ) );
		add_action( 'wp_ajax_courier_notices_delete_type', array( $this, 'delete_type' ) );

		add_action( 'admin_footer', array( $this, 'add_templates' ) );
	}

	/**
	 * Get the row template
	 *
	 * @since 1.0
	 */
	public function add_templates() {

		$page = filter_input( INPUT_GET, 'page' );
		$tab  = filter_input( INPUT_GET, 'tab' );

		if ( 'courier' === $page && 'design' === $tab ) {
			// Create New Row to be rendered using JavaScript
			$new_courier_ype = new View();
			$new_courier_ype->assign( 'text_color', '#ffffff' );
			$new_courier_ype->assign( 'notice_color', Utils::get_random_color() );
			$new_courier_ype->render( 'admin/js/courier-notice-type-row' );
		}

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

		$notice_type_class      = sanitize_title_with_dashes( $notice_type_class );
		$notice_type_color      = sanitize_hex_color( $_POST['courier_notice_type_new_color'] );
		$notice_type_text_color = sanitize_hex_color( $_POST['courier_notice_type_new_text_color'] );

		$type = wp_insert_term( $notice_type_title, 'courier_type' );

		if ( ! is_wp_error( $type ) ) {
			$this->insert_term_meta( $type, $notice_type_class, $notice_type_color, $notice_type_text_color );
		} else {
			wp_die( esc_html( $type ) );
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
				'success'   => $type,
				'fragments' => array(
					'table.form-table tbody tr td:first' => wp_kses(
						$table,
						$this->kses_template
					),
				),
			)
		);
		exit;
	}

	/**
	 * Update a courier notice type
	 */
	public function update_type() {

		check_ajax_referer( 'courier_notices_update_type_nonce', 'courier_notices_update_type' );

		if ( empty( $_POST['courier_notice_type_id'] ) ) {
			return wp_json_encode( -1 );
		}

		if ( ! current_user_can( 'edit_courier_notices' ) ) {
			return wp_json_encode( -1 );
		}

		$notice_type_title = sanitize_text_field( $_POST['courier_notice_type_edit_title'] );

		// If not css class is passed, fall back to the title
		if ( ! isset( $_POST['courier_notice_type_edit_css_class'] ) || '' === $_POST['courier_notice_type_edit_css_class'] ) {
			$notice_type_class = $_POST['courier_notice_type_edit_title'];
		} else {
			$notice_type_class = $_POST['courier_notice_type_edit_css_class'];
		}

		$notice_type_class      = sanitize_title_with_dashes( $notice_type_class );
		$notice_type_color      = sanitize_hex_color( $_POST['courier_notice_type_edit_color'] );
		$notice_type_text_color = sanitize_hex_color( $_POST['courier_notice_type_edit_text_color'] );

		$type = wp_update_term( (int) $_POST['courier_notice_type_id'], 'courier_type', array( 'name' => $notice_type_title ) );

		if ( ! is_wp_error( $type ) ) {
			$this->insert_term_meta( $type, $notice_type_class, $notice_type_color, $notice_type_text_color );
		} else {
			wp_die( esc_html( $type ) );
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
				'success'   => $type,
				'fragments' => array(
					'table.form-table tbody tr td:first' => wp_kses(
						$table,
						$this->kses_template
					),
				),
			)
		);
		exit;
	}

	/**
	 * Delete a courier notice type.
	 */
	public function delete_type() {

		check_ajax_referer( 'courier_notices_delete_type_nonce', 'courier_notices_delete_type' );

		if ( empty( $_POST['courier_notices_type'] ) ) {
			echo wp_json_encode( -1 );
			exit;
		}

		if ( ! current_user_can( 'delete_courier_notices' ) ) {
			echo wp_json_encode( -1 );
			exit;
		}

		$term_id = (int) $_POST['courier_notices_type'];

		// Confirm that the term does indeed exist.
		if ( term_exists( $term_id, 'courier_type' ) ) {
			$deleted = wp_delete_term( $term_id, 'courier_type', array( 'force_default' => true ) );

			if ( is_wp_error( $deleted ) ) {
				echo wp_json_encode( -1 );
				exit;
			}
		}

		echo wp_json_encode( 1 );
		exit;
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
	private function insert_term_meta( $term, $class_name = '', $hex_color = '', $label_color = '' ) {

		if ( empty( $term ) ) {
			return;
		}

		$term_id = (int) $term['term_id'];

		if ( ! empty( $term_id ) && ! empty( $hex_color ) ) {
			update_term_meta( $term_id, '_courier_type_color', $hex_color );
		}

		if ( ! empty( $term_id ) && ! empty( $label_color ) ) {
			update_term_meta( $term_id, '_courier_type_text_color', $label_color );
		}

		if ( ! empty( $term_id ) && ! empty( $class_name ) ) {
			update_term_meta( $term_id, '_courier_type_icon', $class_name );
		}
	}
}
