<?php
namespace Courier\Controller;

use \Courier\Model\Config;

/**
 * Class Install
 * @package Courier\Controller
 */
class Install {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Install constructor.
	 *
	 */
	public function __construct() {
		$this->config = new Config();
	}

	/**
	 * Register Actions
	 */
	public function register_actions() {
		add_action( 'admin_notices', array( $this, 'check_for_updates' ) );
	}

	/**
	 * Check to see if we have any updates
	 */
	public function check_for_updates() {
		$plugin_options  = get_option( 'courier_options', array() );
		$current_version = isset( $plugin_options['plugin_version'] ) ? $plugin_options['plugin_version'] : '0';

		// If your version is different than this version, run install
		if ( version_compare( $current_version, $this->config->get( 'version' ), '!=' ) ) {
			$this->install( $current_version );
		}
	}

	/**
	 * Handle upgrade tasks
	 * @since 1.0
	 */
	public function upgrade() {
		$current_version = get_option( 'courier_version', '0.0.0' );

		if ( version_compare( '1.0.0', $current_version, '>' ) ) {
			flush_rewrite_rules();
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'courier_expire' );

			$current_version = '1.0.0';
			update_option( 'courier_version', $current_version );
		}
	}

	private function insert_term_meta( $term, $class_name, $hex_color ) {
		add_term_meta( $term['term_id'], '_courier_type_color', $hex_color, true );
		add_term_meta( $term['term_id'], '_courier_type_icon', $class_name, true );
	}

	/**
	 * Install our default options and version number
	 * @param $current_version
	 */
	public function install( $current_version ) {
		$plugin_options = get_option( 'courier_options', array() );

		// This is the type of notification that is being displayed to the user.

		// Success
		$success = wp_insert_term( esc_html__( 'Success', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $success, 'success', '#69DF44' );

		// Warnings
		$warning = wp_insert_term( esc_html__( 'Warning', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $warning, 'warning', '#EBBE61' );

		// Info
		$info = wp_insert_term( esc_html__( 'Info', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $info, 'info', '#A4E2FF' );

		// Alert / Error
		$alert = wp_insert_term( esc_html__( 'Alert', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $alert, 'alert', '#A4E2FF' );

		// Secondary
		$secondary = wp_insert_term( esc_html__( 'Secondary', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $secondary, 'secondary', '#cecece' );

		// Feedback is similar to success for form feedback
		$feedback = wp_insert_term( esc_html__( 'Feedback', 'courier' ), 'courier_type' );
		$this->insert_term_meta( $feedback, 'feedback', '#cecece' );

		// Is this notification for all viewers to see.
		// This is checked by default.
		wp_insert_term( esc_html__( 'Global', 'courier' ), 'courier_scope' );

		// Has the notification been viewed and/or dismissed
		wp_insert_term( esc_html__( 'Viewed', 'courier' ), 'courier_status' );
		wp_insert_term( esc_html__( 'Dismissed', 'courier' ), 'courier_status' );

		// Select where the notification is placed.
		wp_insert_term( esc_html__( 'Header', 'courier' ), 'courier_placement' );
		wp_insert_term( esc_html__( 'Footer', 'courier' ), 'courier_placement' );
		wp_insert_term( esc_html__( 'Popup/Modal', 'courier' ), 'courier_placement' );

		// Keep the plugin version up to date
		$plugin_options['plugin_version'] = $this->config->get( 'version' );

		update_option( 'courier_options', $plugin_options );
	}
}

