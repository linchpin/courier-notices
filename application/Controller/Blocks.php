<?php
/**
 * Block Controller
 *
 * @package CourierNotices\Controller\Blocks
 */

namespace CourierNotices\Controller;

use CourierNotices\Controller\Controller;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Blocks Class
 *
 * @package CourierNotices\Controller
 */
class Blocks extends Controller {


	/**
	 * Admin constructor.
	 */
	public function __construct() {
		parent::__construct();

	}


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 0.1.0
	 */
	public function register_actions():void {
		add_action( 'init', [ $this, 'block_init' ] );
		add_action( 'admin_init', [ $this, 'block_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
		add_filter( 'block_editor_settings_all', function( $editor_settings ) {

			global $post;

			if ( get_post_type( $post ) !== 'courier_notice' ) {
				return $editor_settings;
			}

			$css = '.edit-post-visual-editor__post-title-wrapper {display: none;}';

			$editor_settings['styles'][] = array( 'css' => $css );
			return $editor_settings;
		} );
	}


	/**
	 * Enqueue our block assets
	 *
	 * @return void
	 */
	public function block_editor_assets():void {

		$slotfills_js                 = COURIER_NOTICES_PLUGIN_URL . 'blocks/build/slotfills.js';
		$slotfills_asset_path = COURIER_NOTICES_BLOCK_PATH . '/build/slotfills.asset.php';

		if ( ! file_exists( $slotfills_asset_path ) ) {
			add_action(
				'admin_notices',
				function () {
					?>
					<div class="notice notice-warning is-dismissible">
						<p><?php echo wp_kses_post( __( 'It looks like you are using a development copy of <strong>Courier Notices</strong>. Please run <code>npm i; npm run build</code> to the slotfill assets.', 'elite-shopify' ) ); ?></p>
					</div>
					<?php
				}
			);

			return;
		}

		$slotfills_asset = require $slotfills_asset_path;

		wp_enqueue_script(
			'courier-notices-block-admin',
			$slotfills_js,
			$slotfills_asset['dependencies'],
			$slotfills_asset['version'],
			true
		);

	}

	/**
	 * Register our plugin scripts
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function block_scripts():void {
		$blocks = [];
		$blocks = apply_filters( 'courier_notices_blocks', $blocks );

		// temp skip blocks
		return;

		if ( empty( $blocks ) ) {
			return;
		}

		foreach ( $blocks as $block_key => $block_args ) {

			if ( empty( $block_key ) ) {
				continue;
			}

			$script_asset_path = COURIER_NOTICES_BLOCK_PATH . "/build/blocks/{$block_key}/index.asset.php";

			if ( ! file_exists( $script_asset_path ) ) {
				add_action(
					'admin_notices',
					function () {
						?>
						<div class="notice notice-warning is-dismissible">
							<p><?php echo wp_kses_post( __( 'It looks like you are using a development copy of <strong>Adspriation</strong>. Please run <code>npm i; npm run build</code> to create assets.', 'my-linchpin' ) ); ?></p>
						</div>
						<?php
					}
				);

				continue;
			}

			$index_js     = COURIER_NOTICES_PLUGIN_URL . "blocks/build/blocks/{$block_key}/index.js";
			$script_asset = require $script_asset_path;

			$dependencies = array_merge(
				$script_asset['dependencies'],
				[
					'wp-edit-post',
				]
			);

			wp_register_script(
				"{$block_key}-block",
				$index_js,
				$dependencies,
				$script_asset['version'],
				true
			);
		}

	}


	/**
	 * Registers the block using the metadata loaded from the `block-{type}.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see each block registered within the \Blocks namespace for examples
	 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
	 *
	 * @since 0.2.1
	 */
	public function block_init():void {
		$blocks = [];

		// temp
		return;

		$blocks = apply_filters( 'courier_notices_blocks', $blocks );

		if ( empty( $blocks ) ) {
			return;
		}

		foreach ( $blocks as $block_key => $block_args ) {

			$block_path = trailingslashit( COURIER_NOTICES_BLOCK_PATH ) . "build/blocks/{$block_key}/block.json";

			if ( file_exists( $block_path ) ) {
				register_block_type( $block_path, $block_args );
			} else {
				if ( wp_get_environment_type() === 'local' ) {
					wp_die( sprintf( esc_html__( 'Could not find file: %1$s did you do a build?','adspiration' ), esc_html( $block_path ) ) );
				}
			}
		}

	}


}
