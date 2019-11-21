<?php
/**
 * Courier Notices Controller
 *
 * @package Courier\Controller
 */

namespace Courier\Controller;

use \Courier\Model\Config;
use \Courier\Model\Post_Type\Courier_Notice as Courier_Notice_Post_Type;
use \Courier\Model\Taxonomy\Courier_Placement;
use \Courier\Model\Taxonomy\Courier_Scope;
use \Courier\Model\Taxonomy\Courier_Status;
use \Courier\Model\Taxonomy\Courier_Type;

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
	protected static $js_variable = 'courier_data';

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
		add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'exclude_courier_notice_from_search' ), 10, 2 );
		add_filter( 'wpseo_sitemap_exclude_taxonomy', array( $this, 'exclude_courier_notice_taxonomies' ), 10, 2 );
		add_filter( 'add_query_vars', array( $this, 'add_query_vars' ) );
	}

	/**
	 * Exclude One Taxonomy From Yoast SEO Sitemap
	 *
	 * @since 1.0
	 *
	 * @param $value
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public function exclude_courier_notice_taxonomies( $value, $taxonomy ) {
		$taxonomy_to_exclude = array( 'courier_type', 'courier_scope', 'courier_placement', 'courier_status' );

		if ( in_array( $taxonomy, $taxonomy_to_exclude, true ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Exclude Courier Notices from WordPress SEO Sitemaps
	 *
	 * @since 1.0
	 *
	 * @param bool   $value     Default false.
	 * @param string $post_type Post type name.
	 *
	 * @return bool
	 */
	public function exclude_courier_notice_from_search( $value, $post_type ) {
		if ( 'courier_notice' === $post_type ) {
			return true;
		}

		return false;
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

		$js_dependencies = array( 'jquery' );

		wp_register_style( 'courier-css', $config->get( 'plugin_url' ) . 'assets/css/courier.css', array(), $config->get( 'version' ) );

		global $post;

		$localized_data = array(
			'endpoint'      => site_url( '/wp-json/courier/v1/notice/' ),
			'notices_nonce' => wp_create_nonce( 'courier_notice_get_notices' ),
			'post_info'     => array(
				'ID'            => ( ! empty( $post ) ) ? $post->ID : -1,
				'is_single'     => is_single(),
				'is_archive'    => is_archive(),
				'is_front_page' => is_front_page(),
				'is_home'       => is_home(),
				'is_author'     => is_author(),
				'is_category'   => is_category(),
				'post_type'     => ( ! empty( $post ) ) ? get_post_type( $post->ID ) : '',
				'is_tax'        => is_tax(),
				'is_page'       => is_page(),
				'is_404'        => is_404(),
			),
			'strings'       => array(
				'close'   => esc_html__( 'Close', 'courier' ),
				'dismiss' => esc_html__( 'Dismiss', 'courier' ),
			),
		);

		wp_register_script( 'courier', $config->get( 'plugin_url' ) . 'assets/js/courier.js', $js_dependencies, $config->get( 'version' ), true );
		wp_enqueue_script( 'courier' );

		$localized_data = apply_filters( 'courier_localized_data', $localized_data );

		wp_localize_script(
			'courier',
			'courier_data',
			$localized_data
		);
	}

	public function wp_enqueue_styles() {

		if ( is_admin() ) {
			return;
		}

		$config = new Config();
		wp_register_style( 'courier', $config->get( 'plugin_url' ) . 'css/courier-notices.css', '', $config->get( 'version' ) );
		wp_enqueue_style( 'courier' );

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
		$courier_type_taxonomy_model      = new Courier_Type();
		$courier_scope_taxonomy_model     = new Courier_Scope();
		$courier_status_taxonomy_model    = new Courier_Status();
		$courier_placement_taxonomy_model = new Courier_Placement();

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
