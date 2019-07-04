<?php

namespace Courier\Controller;

/**
 * Class Placement
 * @package Courier\Controller
 */
class Placement {

	/**
	 * Register our actions for where notifications will be placed.
	 */
	public function register_actions() {
		add_filter( 'get_header', array( $this, 'place_header_notices' ), 100 );
		add_filter( 'get_footer', array( $this, 'place_footer_notices' ), 100 );
	}

	/**
	 * Place all of our header notifications
	 */
	public function place_header_notices( $header ) {
		courier_display_notices(
			array(
				'placement' => 'header',
			)
		);
	}

	/**
	 * Place all of our footer notifications
	 */
	public function place_footer_notices( $footer ) {
		courier_display_notices(
			array(
				'placement' => 'footer',
			)
		);
	}
}
