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
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	/**
	 * Check and schedule plugin upgrading if necessary.
	 *
	 * @since 1.0
	 */
	public function plugins_loaded() {

		wp_die( get_option( 'courier_version', '0.0.0' ) );

		if ( version_compare( COURIER_VERSION, get_option( 'courier_version', '0.0.0' ), '>' ) ) {
			add_action( 'admin_init', array( $this, 'upgrade' ), 999 );
		}
	}

	/**
	 * Handles upgrade tasks
	 *
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
}

