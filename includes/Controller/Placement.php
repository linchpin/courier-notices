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
class Placement implements Controller_Interface {


	/**
	 * Registers our actions for where notifications will be placed.
	 *
	 * @since 1.0
	 */
	public function register_actions(): void {
		add_action( 'wp_body_open', array( __CLASS__, 'place_header_notices' ), 100 );
		add_action( 'get_footer', array( __CLASS__, 'place_footer_notices' ), 100 );
		add_action( 'wp_body_open', array( __CLASS__, 'place_modal_notices' ), 100 );

		/*
		 * get_footer() does not fire at all in a block theme, which silently
		 * broke every footer notice on modern sites. wp_footer does fire in
		 * both, so footer notices now hook it as well.
		 *
		 * This is safe to double-hook: Render_Registry claims the region on
		 * first render, and get_footer runs before wp_footer, so a classic
		 * theme still renders exactly where it always did while a block theme
		 * gets its footer notices back. Priority 5 keeps the region ahead of
		 * the scripts other plugins print at the default priority.
		 */
		add_action( 'wp_footer', array( __CLASS__, 'place_footer_notices' ), 5 );
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
