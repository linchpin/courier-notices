<?php
/**
 * Upgrade Controller
 *
 * @package Courier\Controller
 */

namespace Courier\Controller;

use \Courier\Model\Config;

/**
 * Class Upgrade
 */
class Upgrade {

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
		add_action( 'admin_init', array( $this, 'upgrade' ), 999 );
		add_action( 'admin_notices', array( $this, 'show_review_nag' ), 11 );
	}

	/**
	 * Check and schedule plugin upgrading if necessary.
	 *
	 * @since 1.0
	 */
	public function upgrade() {
		$plugin_options = get_option( 'courier_options', array() );
		$stored_version = ( $plugin_options['plugin_version'] ) ? $plugin_options['plugin_version'] : '0.0.0';

		// Keep the plugin version up to date.

		if ( version_compare( '1.0.0', $stored_version, '>' ) ) {
			flush_rewrite_rules();
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'courier_expire' );

			$plugin_options['plugin_version'] = '1.0.0';
			update_option( 'courier_options', $plugin_options );
		}

		if ( version_compare( '1.1.0', $stored_version, '>' ) ) {

			// Create our default style of courier notices
			if ( ! term_exists( 'Informational', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Informational', 'courier' ), 'courier_style' );
			}

			if ( ! term_exists( 'Modal', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Modal', 'courier' ), 'courier_style' );
			}

			// Delete modal from our placement type as it's now a "style" of notice.
			if ( $term = term_exists( 'popup-modal', 'courier_placement' ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found
				wp_delete_term( $term['term_id'], 'courier_placement' );
			}

			// Remove the version from it's own variable
			delete_option( 'courier_version' );

			$plugin_options['plugin_version'] = '1.1.0';
			update_option( 'courier_options', $plugin_options );
		}

		// Do one last check to make sure our stored version is the latest.
		if ( version_compare( COURIER_VERSION, $stored_version, '>' ) ) {

			$plugin_options['plugin_version'] = COURIER_VERSION;
			update_option( 'courier_options', $plugin_options );
		}
	}

	/**
	 * Show a nag to the user to review Courier Notices.
	 * Because it's awesome! -Patrick Swayze
	 *
	 * @since 1.0
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function show_review_nag() {
		$courier_settings = get_option( 'courier_options' );
		$notifications    = get_user_option( 'courier_notifications' );

		// If we don't have a date die early.
		if ( ! isset( $courier_settings['first_activated_on'] ) || '' === $courier_settings['first_activated_on'] ) {
			return '';
		}

		$now          = new \DateTime();
		$install_date = new \DateTime();
		$install_date->setTimestamp( $courier_settings['first_activated_on'] );

		if ( $install_date->diff( $now )->days < 30 ) {
			return '';
		}

		if ( false !== $courier_settings && ( ! empty( $notifications['update-notice'] ) && empty( $notifications['review-notice'] ) ) ) {
			include COURIER_PATH . 'templates/admin/review-notice.php';
		}

		return '';
	}
}

