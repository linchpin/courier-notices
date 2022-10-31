<?php
/**
 * Courier Notices Block Controller
 *
 * This block controls showing a list of notice blocks
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use CourierNotices\Controller\Controller;

/**
 * Courier Notices Block Class
 *
 * This block load all child notices into this block.
 *
 * @package CourierNotices\Controller
 */
class Courier_Notices extends Controller {

	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 0.3.0
	 */
	public function register_actions() {
		add_filter( 'courier_notices_blocks', [ $this, 'add_block' ] );

	}


	/**
	 * Add our Integration Meta block to our list of blocks.
	 *
	 * @param array $blocks
	 *
	 * @return array
	 */
	public function add_block( array $blocks = [] ) {
		$blocks['courier-notices'] = [
			'editor_script' => 'courier-nontices-blocks',
		];

		return $blocks;

	}


}
