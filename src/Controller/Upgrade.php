<?php
/**
 * Upgrade Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Model\Config;

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

		if ( empty( $plugins_options ) ) {
			$plugin_options = get_option( 'courier_notices_options', array( 'plugin_version' => '0.0.0' ) );
		}

		$stored_version = ( $plugin_options['plugin_version'] ) ? $plugin_options['plugin_version'] : '0.0.0';

		// Keep the plugin version up to date.
		if ( version_compare( '1.0.0', $stored_version, '>' ) ) {
			flush_rewrite_rules();
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'courier_expire' );

			update_option( 'courier_version', '1.0.0' );
		}

		if ( version_compare( '1.0.5', $stored_version, '>' ) ) {
			$plugin_options['plugin_version'] = '1.0.5';
			update_option( 'courier_options', $plugin_options );
		}

		if ( version_compare( '1.1.0', $stored_version, '>' ) ) {

			// Create our default style of courier notices
			if ( ! term_exists( 'Informational', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Informational', 'courier-notices' ), 'courier_style' );
			}

			if ( ! term_exists( 'popup-modal', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Pop Over / Modal', 'courier-notices' ), 'courier_style', array( 'slug' => 'popup-modal' ) );
			}

			// Just in case we don't have a modal in our placement taxonomy, create one
			if ( ! term_exists( 'popup-modal', 'courier_placement' ) ) {
				wp_insert_term( esc_html__( 'Pop Over / Modal', 'courier-notices' ), 'courier_placement', array( 'slug' => 'popup-modal' ) );
			}

			// Remove the version from it's own variable
			delete_option( 'courier_version' );

			$plugin_options['plugin_version'] = '1.1.0';
			update_option( 'courier_options', $plugin_options );
		}

		if ( version_compare( '1.2.0', $stored_version, '>' ) ) {
			$plugin_options['plugin_version'] = '1.2.0';
			update_option( 'courier_notices_options', $plugin_options ); // save options in the new namespace
			delete_option( 'courier_options' ); // remove older options that exist

			// Rename the design options to the new name space
			$courier_design_settings = get_option( 'courier_design', array() );

			update_option( 'courier_notices_design', $courier_design_settings );
			delete_option( 'courier_design' );
		}

		// Do one last check to make sure our stored version is the latest.
		if ( version_compare( COURIER_NOTICES_VERSION, $stored_version, '>' ) ) {

			delete_transient( 'courier_notices_notice_css' );
			delete_transient( 'courier_notice_css' );
			courier_get_css();

			$plugin_options['plugin_version'] = COURIER_NOTICES_VERSION;
			update_option( 'courier_notice_options', $plugin_options );
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
		$options       = get_option( 'courier_notices_options' );
		$notifications = get_user_option( 'courier_notices_notifications' );

		// If we don't have a date die early.
		if ( ! isset( $options['first_activated_on'] ) || '' === $options['first_activated_on'] ) {
			return '';
		}

		$now          = new \DateTime();
		$install_date = new \DateTime();
		$install_date->setTimestamp( $options['first_activated_on'] );

		if ( $install_date->diff( $now )->days < 30 ) {
			return '';
		}

		if ( false !== $options && ( ! empty( $notifications['update-notice'] ) && empty( $notifications['review-notice'] ) ) ) {
			include COURIER_NOTICES_PATH . 'templates/admin/review-notice.php';
		}

		return '';
	}
}

