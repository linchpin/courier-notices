<?php
/**
 * Main Controller.
 *
 * Other controllers should extend this one.
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Core\View;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Controller
 *
 * @package CourierNotices\Controller
 */
class Controller extends View {

	/**
	 * Registers the default hooks and filters.
	 */
	public function register_actions():void {
		add_action( 'init', [ $this, 'handle_courier_notices_blocks_actions' ] );

	}


	/**
	 * Handles this plugin's form submissions.
	 *
	 * For example, a form could have a hidden field like this:
	 *     <input type="hidden" name="linchpin_blocks_action" value="login_submit">
	 * This would cause the following hook to fire: linchpin_action_login_submit
	 */
	public function handle_courier_notices_blocks_actions() {
		if ( isset( $_REQUEST['courier_notices_blocks_action'] ) ) { // phpcs:ignore
			do_action( 'courier_notices_blocks_action_' . $_REQUEST['courier_notices_blocks_action'], $_REQUEST ); // phpcs:ignore
		}

	}


}
