<?php
/**
 * Base Block Controller
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
 * Base Block Class
 *
 * @package CourierNotices\Controller
 */
class Block extends Controller {

	protected $slug = 'courier-notices-block';


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 0.5.0
	 */
	public function register_actions(): void {

	}


	/**
	 * Enqueue our block assets
	 *
	 * @return void
	 */
	public function enqueue_block_assets():void {
		if ( file_exists( COURIER_NOTICES_BLOCK_PATH . "/build/blocks/{$this->slug}/style.css" ) ) {
			wp_enqueue_style(
				"{$this->slug}-block",
				COURIER_NOTICES_PLUGIN_URL . "blocks/build/blocks/{$this->slug}/style.css",
				[],
				COURIER_NOTICES_VERSION
			);
		}

	}


	/**
	 * Add our block to a list of available blocks
	 *
	 * @since 0.5.0
	 *
	 * @param $blocks
	 *
	 * @return array
	 */
	public function add_block( $blocks ): array {
		$blocks[ $this->slug ] = [
			'render_callback' => [ __CLASS__, 'dynamic_render_callback' ],
			'editor_script'   => sanitize_title( "{$this->slug}-block" ),
		];

		return $blocks;

	}


}
