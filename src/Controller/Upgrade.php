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
		$current_version = get_option( 'courier_version', '0.0.0' );

		if ( version_compare( '1.0.0', $current_version, '>' ) ) {
			flush_rewrite_rules();
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'courier_expire' );

			$current_version = COURIER_VERSION;
			update_option( 'courier_version', $current_version );
		}

		if ( version_compare( '1.0.3', $current_version, '>' ) ) {
			$current_version = COURIER_VERSION;
			update_option( 'courier_version', $current_version );
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

