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
class Courier_Notices {

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
	public function register_actions() {
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );

		// Exclude requests from the sitemap regardless of options.
		add_filter( 'add_query_vars', array( $this, 'add_query_vars' ) );

		// Hook into notice save/delete to clear cache.
		add_action( 'save_post_courier_notice', [ $this, 'clear_cache' ] );
		add_action( 'deleted_post', [ $this, 'clear_cache' ] );
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

		$js_dependencies = array();

		global $post;

		$localized_data = array(
			'notice_endpoint'      => site_url( '/wp-json/courier-notices/v1/notice/' ),
			'notices_endpoint'     => site_url( '/wp-json/courier-notices/v1/notices/display/' ),
			'notices_all_endpoint' => site_url( '/wp-json/courier-notices/v1/notices/display/all/' ),
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
		);

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

		wp_add_inline_style( 'courier-notices', courier_get_css() );
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
