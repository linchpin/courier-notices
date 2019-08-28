<?php
/**
 * Install Controller
 *
 * @package Courier\Controller
 */

namespace Courier\Controller;

use \Courier\Model\Config;

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
		add_action( 'admin_notices', array( $this, 'check_for_updates' ) );
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
		$plugin_options  = get_option( 'courier_options', array() );
		$current_version = isset( $plugin_options['plugin_version'] ) ? $plugin_options['plugin_version'] : '0';

		// If your version is different than this version, run install.
		if ( version_compare( $current_version, $this->config->get( 'version' ), '!=' ) ) {
			$this->install( $current_version );
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
		add_term_meta( $term['term_id'], '_courier_type_color', $hex_color, true );
		add_term_meta( $term['term_id'], '_courier_type_icon', $class_name, true );
	}

	/**
	 * Install our default options and version number
	 *
	 * @since 1.0
	 *
	 * @param int $current_version The current version.
	 */
	public function install( $current_version ) {
		$plugin_options = get_option( 'courier_options', array() );

		// This is the type of notification that is being displayed to the user.

		// Success.
		$success = wp_insert_term( esc_html__( 'Success', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $success, 'success', '#A5FF82' );

		// Warnings.
		$warning = wp_insert_term( esc_html__( 'Warning', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $warning, 'warning', '#FFB64B' );

		// Info.
		$info = wp_insert_term( esc_html__( 'Info', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $info, 'info', '#6BCCE8' );

		// Alert / Error.
		$alert = wp_insert_term( esc_html__( 'Alert', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $alert, 'alert', '#FF4053' );

		// Secondary.
		$secondary = wp_insert_term( esc_html__( 'Secondary', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $secondary, 'secondary', '#cecece' );

		// Feedback is similar to success for form feedback.
		$feedback = wp_insert_term( esc_html__( 'Feedback', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $feedback, 'feedback', '#7D86E8' );

		// Is this notification for all viewers to see. This is checked by default.
		wp_insert_term( esc_html__( 'Global', 'courier' ), 'courier_scope' );

		// Has the notification been viewed and/or dismissed?
		wp_insert_term( esc_html__( 'Viewed', 'courier' ), 'courier_status' );
		wp_insert_term( esc_html__( 'Dismissed', 'courier' ), 'courier_status' );

		// Select where the notification is placed.
		wp_insert_term( esc_html__( 'Header', 'courier' ), 'courier_placement' );
		wp_insert_term( esc_html__( 'Footer', 'courier' ), 'courier_placement' );
		wp_insert_term( esc_html__( 'Popup/Modal', 'courier' ), 'courier_placement' );

		// Keep the plugin version up to date.
		$plugin_options['plugin_version'] = $this->config->get( 'version' );

		update_option( 'courier_options', $plugin_options );
	}
}

