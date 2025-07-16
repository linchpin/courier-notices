<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Blocks Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Model\Config;
use CourierNotices\Model\Settings;

/**
 * Blocks Class
 * 
 * Handles registration and management of Courier Notice blocks
 */
class Blocks {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Settings
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Blocks constructor.
	 */
	public function __construct() {
		$this->config = new Config();
		$this->settings = new Settings();
	}

	/**
	 * Register our hooks
	 *
	 * @since 1.8.0
	 */
	public function register_actions() {
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		add_filter( 'block_categories_all', [ $this, 'add_blocks_category' ] );
	}

	/**
	 * Check if blocks are enabled
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	private function blocks_enabled() {
		$settings = $this->settings->get_settings();
		return ! empty( $settings['enable_block_editor'] );
	}

	/**
	 * Register Courier Notice blocks
	 *
	 * @since 1.8.0
	 */
	public function register_blocks() {
		if ( ! $this->blocks_enabled() ) {
			return;
		}

		// Register the courier-notices-container block
		register_block_type( COURIER_NOTICES_PATH . 'assets/js/blocks/courier-notices-container' );

		// Register the courier-notice block
		register_block_type( COURIER_NOTICES_PATH . 'assets/js/blocks/courier-notice' );
	}

	/**
	 * Enqueue block assets for both frontend and editor
	 *
	 * @since 1.8.0
	 */
	public function enqueue_block_assets() {
		if ( ! $this->blocks_enabled() ) {
			return;
		}

		// Enqueue the interactivity API script
		wp_enqueue_script( 'wp-interactivity' );

		// Enqueue existing Courier Notices frontend styles
		// (blocks will inherit the existing styling)
		$courier_settings = get_option( 'courier_design', [] );
		if ( ! isset( $courier_settings['disable_css'] ) || ! $courier_settings['disable_css'] ) {
			wp_enqueue_style( 'courier-notices' );
		}
	}

	/**
	 * Enqueue block editor assets
	 *
	 * @since 1.8.0
	 */
	public function enqueue_block_editor_assets() {
		if ( ! $this->blocks_enabled() ) {
			return;
		}

		$asset_file = include COURIER_NOTICES_PATH . 'js/courier-notices-blocks.asset.php';

		wp_enqueue_script(
			'courier-notices-blocks',
			COURIER_NOTICES_PLUGIN_URL . 'js/courier-notices-blocks.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		// Localize script with necessary data
		wp_localize_script(
			'courier-notices-blocks',
			'courierNoticesBlocksData',
			[
				'restUrl' => rest_url(),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'noticeTypes' => $this->get_notice_types(),
				'noticeStyles' => $this->get_notice_styles(),
				'pluginUrl' => COURIER_NOTICES_PLUGIN_URL,
			]
		);
	}

	/**
	 * Add Courier Notices block category
	 *
	 * @since 1.8.0
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public function add_blocks_category( $categories ) {
		if ( ! $this->blocks_enabled() ) {
			return $categories;
		}

		return array_merge(
			$categories,
			[
				[
					'slug'  => 'courier-notices',
					'title' => __( 'Courier Notices', 'courier-notices' ),
					'icon'  => 'warning',
				],
			]
		);
	}

	/**
	 * Get notice types for blocks
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	private function get_notice_types() {
		$terms = get_terms( [
			'taxonomy' => 'courier_type',
			'hide_empty' => false,
		] );

		$types = [];
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$types[] = [
					'value' => $term->slug,
					'label' => $term->name,
				];
			}
		}

		// Fallback to default types
		if ( empty( $types ) ) {
			$types = [
				[ 'value' => 'informational', 'label' => __( 'Informational', 'courier-notices' ) ],
				[ 'value' => 'success', 'label' => __( 'Success', 'courier-notices' ) ],
				[ 'value' => 'warning', 'label' => __( 'Warning', 'courier-notices' ) ],
				[ 'value' => 'error', 'label' => __( 'Error', 'courier-notices' ) ],
			];
		}

		return $types;
	}

	/**
	 * Get notice styles for blocks
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	private function get_notice_styles() {
		$terms = get_terms( [
			'taxonomy' => 'courier_style',
			'hide_empty' => false,
		] );

		$styles = [];
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$styles[] = [
					'value' => $term->slug,
					'label' => $term->name,
				];
			}
		}

		// Fallback to default styles
		if ( empty( $styles ) ) {
			$styles = [
				[ 'value' => 'informational', 'label' => __( 'Informational', 'courier-notices' ) ],
				[ 'value' => 'modal', 'label' => __( 'Modal', 'courier-notices' ) ],
				[ 'value' => 'banner', 'label' => __( 'Banner', 'courier-notices' ) ],
			];
		}

		return $styles;
	}
}