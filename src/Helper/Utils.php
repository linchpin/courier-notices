<?php
/**
 * Helper Utilities
 *
 * @package Courier\Helper
 */

namespace Courier\Helper;

/**
 * Utils Class
 */
class Utils {

	/**
	 * Return whether or not the default WP Cron process is being used.
	 * Typically with alternate/true crons the default WordPress cron will
	 * be disabled with the DISABLE_CRON constant
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function is_wp_cron_disabled() {
		return ( defined( 'DISABLE_CRON' ) && true === DISABLE_CRON );
	}
}
