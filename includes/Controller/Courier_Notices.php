<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Courier Notices Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Model\Config;
use CourierNotices\Model\Post_Type\Courier_Notice as Courier_Notice_Post_Type;
use CourierNotices\Model\Taxonomy\Placement;
use CourierNotices\Model\Taxonomy\Scope;
use CourierNotices\Model\Taxonomy\Status;
use CourierNotices\Model\Taxonomy\Type;
use CourierNotices\Model\Taxonomy\Style;

/**
 * Courier_Notices Class
 */
class Courier_Notices implements Controller_Interface {

	/**
	 * JS handle
	 *
	 * @var string
	 */
	protected static $handle = 'courier-admin';

	/**
	 * JS variable name
	 *
	 * @var string
	 */
	protected static $js_variable = 'courier_notices_data';

	/**
	 * Dependencies
	 *
	 * @var array
	 */
	protected static $dependencies = array( 'jquery' => 'jquery' );


	/**
	 * Register our hooks
	 *
	 * @since 1.0
	 */
	public function register_actions(): void {
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
		add_action( 'save_post_courier_notice', array( $this, 'sync_notice_layout_terms' ), 20, 2 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );

		// Exclude requests from the sitemap regardless of options.
		add_filter( 'add_query_vars', array( $this, 'add_query_vars' ) );

		// Hook into notice save/delete to clear cache.
		add_action( 'save_post_courier_notice', [ $this, 'clear_cache' ] );
		add_action( 'deleted_post', [ $this, 'clear_cache' ] );

		// REST writes meta after save_post, so the EXISTS-sensitive keys are
		// normalized here rather than in the save_post handler.
		add_action( 'rest_after_insert_courier_notice', [ $this, 'normalize_notice_meta' ] );
	}


	/**
	 * Clear the cache when a notice is saved or deleted.
	 *
	 * @param int $post_id The ID of the post that was saved or deleted.
	 */
	public function clear_cache( $post_id ) {
		if ( 'courier_notice' === get_post_type( $post_id ) ) {
			courier_notices_clear_cache();
		}
	}

	/**
	 * Enqueue all of our needed scripts
	 *
	 * @since 1.0
	 */
	public function wp_enqueue_scripts() {
		if ( is_admin() ) {
			return;
		}

		$config = new Config();

		$js_dependencies = array( 'wp-url' );

		global $post;

		$localized_data = array(
			// rest_url() honors non-pretty permalinks and custom REST
			// prefixes; the hardcoded wp-json paths broke on both.
			'notice_endpoint'      => rest_url( 'courier-notices/v1/notice/' ),
			'notices_endpoint'     => rest_url( 'courier-notices/v1/notices/display/' ),
			'notices_all_endpoint' => rest_url( 'courier-notices/v1/notices/display/all/' ),
			'notices_nonce'        => wp_create_nonce( 'courier_notices_get_notices' ),
			'wp_rest_nonce'        => wp_create_nonce( 'wp_rest' ),
			'dismiss_nonce'        => wp_create_nonce( 'courier_notices_dismiss_' . get_current_user_id() . '_notice_nonce' ),
			'post_info'            => array(
				'ID' => ( ! empty( $post ) ) ? $post->ID : -1,
			),
			'strings'              => array(
				'close'   => esc_html__( 'Close', 'courier-notices' ),
				'dismiss' => esc_html__( 'Dismiss', 'courier-notices' ),
			),
			'user_id'              => get_current_user_id(),
			'has_notices'          => courier_notices_has_any_notices( get_current_user_id() ),
		);

		// Expose prevent_ajax_cache option (default: false)
		// The setting was moved from the design settings to the general settings; check both places for compatibility.
		$courier_design_settings  = get_option( 'courier_design', array() );
		$courier_general_settings = get_option( 'courier_settings', array() );
		$courier_settings         = array_merge( (array) $courier_design_settings, (array) $courier_general_settings );
		$localized_data['prevent_ajax_cache'] = ( isset( $courier_settings['prevent_ajax_cache'] ) && 1 === (int) $courier_settings['prevent_ajax_cache'] ) ? true : false;

		wp_register_script( 'courier-notices', $config->get( 'plugin_url' ) . 'js/courier-notices.js', $js_dependencies, $config->get( 'version' ), true );
		wp_enqueue_script( 'courier-notices' );

		$localized_data = apply_filters( 'courier_notices_localized_data', $localized_data );

		wp_localize_script(
			'courier-notices',
			'courier_notices_data',
			$localized_data
		);
	}


	/**
	 * Enqueue all the styles needed for the design of our courier notices within the admin
	 *
	 * @since 1.0.0
	 */
	public function wp_enqueue_styles() {
		if ( is_admin() ) {
			return;
		}

		$config = new Config();

		$courier_settings = get_option( 'courier_design', array() );

		if ( isset( $courier_settings['disable_css'] ) && 1 === (int) $courier_settings['disable_css'] ) {
			return;
		}

		wp_register_style( 'courier-notices', $config->get( 'plugin_url' ) . 'css/courier-notices.css', '', $config->get( 'version' ) );
		wp_enqueue_style( 'courier-notices' );

		/*
		 * The block stylesheets. @wordpress/scripts routes any entry file named
		 * style.scss into a `style-` prefixed bundle rather than the entry's own
		 * CSS, so this file exists separately from courier-notices.css - and was
		 * never enqueued, meaning the notice block's shared skeleton has not
		 * been reaching the front end at all. It went unnoticed because the
		 * legacy stylesheet already covers most of what it declares.
		 *
		 * Depends on courier-notices so it prints after, which is what lets the
		 * outlet block's rules override the legacy placement positioning.
		 */
		$courier_block_styles = COURIER_NOTICES_PATH . 'css/style-courier-notices.css';

		if ( file_exists( $courier_block_styles ) ) {
			wp_enqueue_style(
				'courier-notices-blocks',
				$config->get( 'plugin_url' ) . 'css/style-courier-notices.css',
				array( 'courier-notices' ),
				$config->get( 'version' )
			);
		}

		wp_add_inline_style( 'courier-notices', courier_notices_get_css() );
	}


	/**
	 * Add admin Query Vars
	 *
	 * @since 1.0
	 *
	 * @param array $vars Array of query vars.
	 *
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		// Admin Vars.
		$vars[] = 'tab';
		$vars[] = 'subtab';

		return $vars;
	}


	/**
	 * Register the 'courier_notice' post type
	 *
	 * @wp-hook init
	 * @since   1.0.0
	 */
	public function register_custom_post_type() {
		$courier_post_type_model = new Courier_Notice_Post_Type();
		register_post_type( $courier_post_type_model->name, $courier_post_type_model->get_args() );

		$this->register_notice_meta();
		$this->register_notice_block();
	}


	/**
	 * Register the courier/notice authoring block.
	 *
	 * The editor script rides the plugin's existing build pipeline as a
	 * registered handle block.json points at; block.json and render.php
	 * are committed source under src/blocks/notice.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function register_notice_block() {
		if ( \WP_Block_Type_Registry::get_instance()->is_registered( 'courier/notice' ) ) {
			return;
		}

		foreach ( array( 'courier-notices-notice-block', 'courier-notices-notices-block' ) as $handle ) {
			$asset_file = COURIER_NOTICES_PATH . 'js/' . $handle . '.asset.php';

			if ( ! file_exists( $asset_file ) ) {
				continue;
			}

			$assets = require $asset_file;

			wp_register_script(
				$handle,
				COURIER_NOTICES_PLUGIN_URL . 'js/' . $handle . '.js',
				$assets['dependencies'],
				$assets['version'],
				true
			);

			wp_set_script_translations( $handle, 'courier-notices', COURIER_NOTICES_PATH . 'languages' );
		}

		register_block_type( COURIER_NOTICES_PATH . 'src/blocks/notice' );
		register_block_type( COURIER_NOTICES_PATH . 'src/blocks/notice-icon' );

		// The outlet block: a region notices render into. Unlike the two
		// above it is inserted by an author into a template or post, so it
		// stays available regardless of the notice-editor opt-in.
		register_block_type( COURIER_NOTICES_PATH . 'src/blocks/notices' );
	}


	/**
	 * Keep the legacy delivery terms in step with the block's layout.
	 *
	 * The 1.x pipeline routes modals by courier_style and courier_placement
	 * terms; authors should not have to set them separately from the
	 * block's layout. Explicit placements are preserved except where they
	 * contradict the layout.
	 *
	 * @since 2.0.0
	 *
	 * @param int      $post_id Notice ID.
	 * @param \WP_Post $post    Notice post object.
	 *
	 * @return void
	 */
	public function sync_notice_layout_terms( $post_id, $post ) {
		if ( false !== wp_is_post_revision( $post_id ) || false !== wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( ! has_block( 'courier/notice', $post ) ) {
			return;
		}

		$layout = 'informational';

		foreach ( parse_blocks( $post->post_content ) as $parsed_block ) {
			if ( 'courier/notice' === $parsed_block['blockName'] ) {
				if ( isset( $parsed_block['attrs']['layout'] ) ) {
					$layout = $parsed_block['attrs']['layout'];
				}
				break;
			}
		}

		if ( 'popup-modal' === $layout ) {
			wp_set_object_terms( $post_id, 'popup-modal', 'courier_style', false );
			wp_set_object_terms( $post_id, 'popup-modal', 'courier_placement', false );

			return;
		}

		wp_set_object_terms( $post_id, 'informational', 'courier_style', false );

		// Leaving the modal layout must also leave the modal placement, or
		// the notice keeps delivering as a modal.
		if ( has_term( 'popup-modal', 'courier_placement', $post_id ) ) {
			wp_set_object_terms( $post_id, 'header', 'courier_placement', false );
		}
	}


	/**
	 * Register the notice meta keys for REST and the block editor.
	 *
	 * The plugin wrote these five keys for years with zero register_post_meta
	 * calls, so nothing could read or save them through REST. Protected
	 * (underscore) keys require the auth_callback to be writable at all.
	 *
	 * Note the dismissible/persistent queries split on EXISTS / NOT EXISTS —
	 * editor UI saving _courier_dismissible must delete the meta rather than
	 * write false, or a "false" row reads as dismissible (see COURIER-1037).
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function register_notice_meta() {
		$auth_callback = static function ( $allowed, $meta_key, $object_id ) {
			return current_user_can( 'edit_post', $object_id );
		};

		$meta_keys = array(
			'_courier_dismissible' => 'boolean',
			'_courier_show_title'  => 'boolean',
			'_courier_hide_title'  => 'boolean',
			'_courier_expiration'  => 'integer',
			'_courier_sender'      => 'integer',
		);

		foreach ( $meta_keys as $meta_key => $type ) {
			register_post_meta(
				'courier_notice',
				$meta_key,
				array(
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => $type,
					'auth_callback'     => $auth_callback,
					'sanitize_callback' => 'boolean' === $type ? 'rest_sanitize_boolean' : 'absint',
				)
			);
		}
	}


	/**
	 * Delete, rather than store, the falsy values of EXISTS-sensitive meta.
	 *
	 * Two display queries branch on whether a meta row exists at all, not on
	 * what it holds:
	 *
	 * - `_courier_dismissible` splits dismissible (EXISTS, `Data.php:167`)
	 *   from persistent (NOT EXISTS, `Data.php:244`) notices.
	 * - `_courier_expiration` gates display on `NOT EXISTS OR value >= now`
	 *   (`Data.php:492`).
	 *
	 * The block editor posts the whole meta object on every save, so clearing
	 * a toggle arrives as `false` and clearing a date as `0`. Core stores
	 * those as rows holding `''` and `'0'`, and a row that merely exists
	 * inverts both queries: a non-dismissible notice starts reading as
	 * dismissible, and a notice with no expiry date stops rendering entirely
	 * — `'0' >= now` is false, and the row itself defeats NOT EXISTS.
	 *
	 * Deleting keeps REST saves matching what the classic metabox has always
	 * done (`Courier::save_post_courier_notice()`), so both editors agree.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Post $post The notice that was just written.
	 *
	 * @return void
	 */
	public function normalize_notice_meta( $post ) {
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		foreach ( array( '_courier_dismissible', '_courier_expiration' ) as $meta_key ) {
			// An absent key is not the same as a cleared one: only normalize
			// what this save actually wrote, so an untouched notice is left
			// alone.
			if ( ! metadata_exists( 'post', $post->ID, $meta_key ) ) {
				continue;
			}

			if ( 0 === (int) get_post_meta( $post->ID, $meta_key, true ) ) {
				delete_post_meta( $post->ID, $meta_key );
			}
		}
	}


	/**
	 * Register the taxonomies for the courier_notice post type
	 *
	 * @since   1.0
	 */
	public function register_taxonomies() {
		$courier_style_taxonomy_model     = new Style();
		$courier_type_taxonomy_model      = new Type();
		$courier_scope_taxonomy_model     = new Scope();
		$courier_status_taxonomy_model    = new Status();
		$courier_placement_taxonomy_model = new Placement();

		if ( ! taxonomy_exists( $courier_style_taxonomy_model->name ) ) {
			register_taxonomy( $courier_style_taxonomy_model->name, array( 'courier_notice' ), $courier_style_taxonomy_model->get_args() );
		}

		if ( ! taxonomy_exists( $courier_type_taxonomy_model->name ) ) {
			register_taxonomy( $courier_type_taxonomy_model->name, array( 'courier_notice' ), $courier_type_taxonomy_model->get_args() );
		}

		if ( ! taxonomy_exists( $courier_scope_taxonomy_model->name ) ) {
			register_taxonomy( $courier_scope_taxonomy_model->name, array( 'courier_notice' ), $courier_scope_taxonomy_model->get_args() );
		}

		if ( ! taxonomy_exists( $courier_status_taxonomy_model->name ) ) {
			register_taxonomy( $courier_status_taxonomy_model->name, array( 'courier_notice' ), $courier_status_taxonomy_model->get_args() );
		}

		if ( ! taxonomy_exists( $courier_placement_taxonomy_model->name ) ) {
			register_taxonomy( $courier_placement_taxonomy_model->name, array( 'courier_notice' ), $courier_placement_taxonomy_model->get_args() );
		}
	}
}
