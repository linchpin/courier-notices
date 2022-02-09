<?php
/**
 * Placement Controller.
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

/**
 * Placement Class
 */
class Placement {


	/**
	 * Registers our actions for where notifications will be placed.
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_action( 'wp_body_open', array( __CLASS__, 'place_header_notices' ), 100 );
		add_action( 'get_footer', array( __CLASS__, 'place_footer_notices' ), 100 );
		add_action( 'get_footer', array( __CLASS__, 'place_modal_notices' ), 100 );

	}


	/**
	 * Places all of our header notifications
	 *
	 * @since 1.0
	 */
	public static function place_header_notices() {
		courier_notices_display_notices(
			array(
				'placement' => 'header',
			)
		);

	}


	/**
	 * Places all of our footer notifications
	 *
	 * @since 1.0
	 */
	public static function place_footer_notices() {
		courier_notices_display_notices(
			array(
				'placement' => 'footer',
			)
		);

	}


	/**
	 * Places all of our modal notices
	 *
	 * @since 1.0
	 */
	public static function place_modal_notices() {
		courier_notices_display_modals();

	}


}
