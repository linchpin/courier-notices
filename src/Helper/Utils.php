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

	/**
	 * Get an array of safe markup and classes to be used
	 * on settings pages.
	 *
	 * @since 1.0
	 *
	 * @return mixed|void
	 */
	public static function get_safe_markup() {
		$safe_content = array(
			'a'    => array(
				'href'  => array(),
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
		);

		return apply_filters( 'courier_notices_safe_markup', $safe_content );
	}
}
