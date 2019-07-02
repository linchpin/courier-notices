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
	 *
	 * @param $current_version
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

	/**
	 * Install our default options and version number
	 * @param $current_version
	 */
	public function install( $current_version ) {
		$plugin_options = get_option( 'courier_options', array() );

		wp_insert_term( esc_html__( 'Success', 'courier' ), 'courier_type' );
		wp_insert_term( esc_html__( 'Warning', 'courier' ), 'courier_type' );
		wp_insert_term( esc_html__( 'Info', 'courier' ), 'courier_type' );
		wp_insert_term( esc_html__( 'Alert', 'courier' ), 'courier_type' );
		wp_insert_term( esc_html__( 'Secondary', 'courier' ), 'courier_type' );
		wp_insert_term( esc_html__( 'Feedback', 'courier' ), 'courier_type' );

		wp_insert_term( esc_html__( 'Global', 'courier' ), 'courier_scope' );

		wp_insert_term( esc_html__( 'Viewed', 'courier' ), 'courier_status' );
		wp_insert_term( esc_html__( 'Dismissed', 'courier' ), 'courier_status' );

		// Keep the plugin version up to date
		$plugin_options['plugin_version'] = $this->config->get( 'version' );

		update_option( 'courier_options', $plugin_options );
	}
}

