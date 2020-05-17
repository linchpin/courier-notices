<?php
/**
 * Install Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Model\Config;

/**
 * Install Class
 */
class Install {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Install constructor
	 */
	public function __construct() {
		$this->config = new Config();
	}

	/**
	 * Registers hooks and filters
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_action( 'admin_init', array( $this, 'check_for_updates' ) );
		add_action( 'init', array( $this, 'add_capabilities' ), 11 );
	}

	/**
	 * Add new capabilities to administrators.
	 *
	 * @since 1.0
	 */
	public function add_capabilities() {

		// Get the administrator role.
		$role = get_role( 'administrator' );

		if ( $role ) {
			if ( ! $role->has_cap( 'edit_courier_notices' ) ) {
				// Add new capabilities.
				$role->add_cap( 'delete_courier_notices', true );
				$role->add_cap( 'delete_others_courier_notices', true );
				$role->add_cap( 'edit_courier_notices', true );
				$role->add_cap( 'edit_published_courier_notices', true );
				$role->add_cap( 'edit_others_courier_notices', true );
			}
		}
	}

	/**
	 * Checks to see if we have any updates.
	 *
	 * @since 1.0
	 */
	public function check_for_updates() {
		$plugin_options = get_option( 'courier_notices_options', array() );

		// If your version is different than this version, run install.
		if ( empty( $plugin_options ) ) {
			$this->install();
		}
	}

	/**
	 * Adds in our term meta for our courier types
	 *
	 * @since 1.0
	 *
	 * @param array  $term       The term to add meta to.
	 * @param string $class_name The class name.
	 * @param string $hex_color  The hex color.
	 */
	private function insert_term_meta( $term, $class_name, $hex_color ) {
		if ( ! is_wp_error( $term ) ) {
			add_term_meta( $term['term_id'], '_courier_type_color', $hex_color, true );
			add_term_meta( $term['term_id'], '_courier_type_icon', $class_name, true );
		}
	}

	/**
	 * Install our default options.
	 *
	 * @since 1.0
	 */
	public function install() {

		// This is the type of notification that is being displayed to the user.

		// Secondary.
		$primary = wp_insert_term( esc_html__( 'Primary', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $primary, 'primary', '#039ad6' );

		// Success.
		$success = wp_insert_term( esc_html__( 'Success', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $success, 'success', '#04a84e' );

		// Alert / Error.
		$alert = wp_insert_term( esc_html__( 'Alert', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $alert, 'alert', '#f97600' );

		// Warnings.
		$warning = wp_insert_term( esc_html__( 'Warning', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $warning, 'warning', '#ea3118' );

		// Feedback is similar to success for form feedback.
		$feedback = wp_insert_term( esc_html__( 'Feedback', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $feedback, 'feedback', '#8839d3' );

		// Info.
		$info = wp_insert_term( esc_html__( 'Info', 'courier-notices' ), 'courier_type' );
		$this->insert_term_meta( $info, 'info', '#878787' );

		// Is this notification for all viewers to see. This is checked by default.
		wp_insert_term( esc_html__( 'Global', 'courier-notices' ), 'courier_scope' );

		// Has the notification been viewed and/or dismissed?
		wp_insert_term( esc_html__( 'Viewed', 'courier-notices' ), 'courier_status' );
		wp_insert_term( esc_html__( 'Dismissed', 'courier-notices' ), 'courier_status' );

		// Select where the notification is placed.
		wp_insert_term( esc_html__( 'Header', 'courier-notices' ), 'courier_placement' );
		wp_insert_term( esc_html__( 'Footer', 'courier-notices' ), 'courier_placement' );

		courier_get_css();
	}
}

