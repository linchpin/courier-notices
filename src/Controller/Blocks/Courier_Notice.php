<?php
/**
 * Courier Notice Block Controller
 *
 * Show a single notice
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
 * Individual Courier Notice Block
 *
 * @package CourierNotices\Controller
 */
class Courier_Notice extends Controller {


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 1.6.0
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
	public function add_block( array $blocks = [] ): array {
		$blocks['courier-notice'] = [
			'editor_script' => 'courier-notices-blocks',
		];

		return $blocks;

	}


}
