<?php
/**
 * Block Controller
 *
 * Actions and filters for the admin area of WordPress
 *
 * @package CourierNotices\Controller\Blocks
 */

namespace CourierNotices\Controller\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use CourierNotices\Controller\Controller;

/**
 * Block Class
 *
 * @package CourierNotices\Controller
 */
class Block extends Controller {


	/**
	 * Admin constructor.
	 */
	public function __construct() {
		parent::__construct();

	}


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 1.6.0
	 */
	public function register_actions() {
		add_action( 'init', [ $this, 'block_init' ] );
		add_action( 'init', [ $this, 'block_scripts' ] );

	}



	/**
	 * Register our plugin scripts
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function block_scripts() {

		$script_asset_path = COURIER_NOTICES_BLOCK_PATH . '/build/index.asset.php';

		if ( ! file_exists( $script_asset_path ) ) {
			throw new \Error(
				'You need to run `npm start` or `npm run build` for the "fluval/blocks" script first.'
			);
		}

		$index_js     = COURIER_NOTICES_PLUGIN_URL . 'blocks/build/index.js';
		$script_asset = require $script_asset_path;

		$dependencies = array_merge(
			$script_asset['dependencies'],
			[
				'wp-edit-post',
			]
		);

		wp_register_script(
			'courier-notices-blocks',
			$index_js,
			$dependencies,
			$script_asset['version']
		);

	}


	/**
	 * Registers the block using the metadata loaded from the `block-{type}.json` file.
	 * Behind the scenes, it registers also all assets, so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function block_init() {

		$blocks = [];

		$blocks = apply_filters( 'courier_notices_blocks', $blocks );

		if ( empty( $blocks ) ) {
			return;
		}

		foreach ( $blocks as $block_key => $block_args ) {

			$block_path = trailingslashit( COURIER_NOTICES_BLOCK_PATH ) . "src/blocks/{$block_key}/block.json";

			if ( file_exists( $block_path ) ) {
				register_block_type( $block_path, $block_args );
			} else {
				wp_die( esc_html( 'could not find file: ' . $block_path ) );
			}
		}

	}


}
