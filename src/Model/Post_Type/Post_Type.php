<?php
/**
 * Post Type Model
 *
 * @package CourierNotices\Model
 */

namespace CourierNotices\Model\Post_Type;

use \CourierNotices\Model\Config;

/**
 * Class Post_Type_Abstract
 *
 * @package CourierNotices\Model\Post_Type
 */
class Post_Type extends Post_Type_Abstract implements Post_Type_Model_Interface {

	/**
	 * Our post type slug
	 *
	 * @var string
	 */
	private string $post_type = 'post_type';

	/**
	 * The Labels of our Post Type
	 *
	 * @var array
	 */
	protected array $labels;

	/**
	 * The Post ID
	 *
	 * @var int
	 */
	protected int $ID;

	/**
	 * The Post Title
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * Model Arguments used when Creating the Post Type
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * @var array
	 */
	protected array $pending = [];

	/**
	 * Default Labels
	 *
	 * @var array
	 */
	protected array $defaults_labels = [];


	/**
	 * Post_Type constructor.
	 *
	 * @param int $post_id The post ID.
	 */
	public function __construct( int $post_id = 0 ) {
		$this->config = new Config();

		$this->default_labels = [
			'name'                  => esc_html_x( 'Custom Post Type', 'General Name', 'elite-shopify' ),
			'singular_name'         => esc_html_x( 'Custom Post Type', 'Singular Name', 'elite-shopify' ),
			'menu_name'             => esc_html__( 'Custom Post Types', 'elite-shopify' ),
			'name_admin_bar'        => esc_html__( 'Custom Post Types', 'elite-shopify' ),
			'archives'              => esc_html__( 'Custom Post Type Archives', 'elite-shopify' ),
			'attributes'            => esc_html__( 'Custom Post Type Attributes', 'elite-shopify' ),
			'parent_item_colon'     => esc_html__( 'Parent Custom Post Type:', 'elite-shopify' ),
			'all_items'             => esc_html__( 'All Custom Post Types', 'elite-shopify' ),
			'add_new_item'          => esc_html__( 'Add New Custom Post Type', 'elite-shopify' ),
			'add_new'               => esc_html__( 'Edit Custom Post Type', 'elite-shopify' ),
			'update_item'           => esc_html__( 'Update Custom Post Type', 'elite-shopify' ),
			'view_item'             => esc_html__( 'View Custom Post Type', 'elite-shopify' ),
			'view_items'            => esc_html__( 'View Custom Post Types', 'elite-shopify' ),
			'search_items'          => esc_html__( 'Search Custom Post Types', 'elite-shopify' ),
			'not_found'             => esc_html__( 'Not found', 'elite-shopify' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'elite-shopify' ),
			'featured_image'        => esc_html__( 'Featured Image', 'elite-shopify' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'elite-shopify' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'elite-shopify' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'elite-shopify' ),
			'insert_into_item'      => esc_html__( 'Insert into Custom Post Type', 'elite-shopify' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Custom Post Type', 'elite-shopify' ),
			'items_list'            => esc_html__( 'Custom Post Type list', 'elite-shopify' ),
			'items_list_navigation' => esc_html__( 'Custom Post Type list navigation', 'elite-shopify' ),
			'filter_items_list'     => esc_html__( 'Filter Custom Post Types list', 'elite-shopify' ),
		];

		$this->default_args = [
			'label'               => esc_html__( 'Custom Post Type', 'elite-shopify' ),
			'description'         => esc_html__( 'Custom Post Type', 'elite-shopify' ),
			'taxonomies'          => [],
			'hierarchical'        => true,
			'public'              => true,
			'show_in_rest'        => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
				'page-attributes',
			],
			'has_archive'         => false,
			'show_in_nav_menus'   => false,
			'menu_icon'           => 'dashicons-universal-access',
			'rewrite'             => [
				'slug'       => 'clients',
				'with_front' => false,
			],
		];

		$this->set_labels();
		$this->set_args();

		if ( ! empty( $id ) ) {
			$post = get_post( absint( $id ) );

			if ( ! empty( $post ) ) {
				$this->setup_model( $post );
			}
		}

	}


	/**
	 * Get the slug for our post type
	 *
	 * @return string
	 */
	public function get_post_type_slug(): string {
		return $this->post_type;

	}


	/**
	 * Get our Post ID.
	 *
	 * @return int
	 */
	public function get_ID(): int { // phpcs:ignore
		return $this->ID;

	}


	/**
	 * Set our Post ID.
	 *
	 * @param int $ID Post ID.
	 *
	 * @return void
	 */
	public function set_ID( int $ID ) { // phpcs:ignore
		$this->ID = $ID; // phpcs:ignore;

	}


	/**
	 * Get the name of our model
	 *
	 * @return string
	 */
	public function get_name():string {
		return $this->name;

	}


	/**
	 * Set the name/title
	 *
	 * @param string $name The name/type of our post
	 *
	 * @return void
	 */
	public function set_name( string $name = '' ) {
		$this->name = $name;

	}


	/**
	 * Sets up the model.
	 *
	 * @param \WP_Post $post This plan's post object.
	 */
	public function setup_model( $post ) {
		$this->ID   = $post->ID;
		$this->name = get_the_title( $post->ID );

	}


	/**
	 * Set the labels of our Post Type
	 */
	public function set_labels() {
		$this->labels = $this->default_labels;

	}


	/**
	 * Get the labels of our post type, with the ability to filter
	 *
	 * @return mixed|void
	 */
	public function get_labels() {
		return apply_filters( "{$this->config->get('prefix')}_{$this->post_type}_labels", $this->labels );

	}


	/**
	 * Sets the custom post type arguments.
	 *
	 * @since 0.1.0
	 */
	public function set_args() {
		$this->args = $this->default_args;

	}


	/**
	 * Get the custom post type arguments
	 *
	 * @return mixed|void
	 */
	public function get_args() {
		return apply_filters( "{$this->config->get('prefix')}_{$this->post_type}_args", $this->args );

	}


	/**
	 * Returns data from inaccessible properties.
	 *
	 * @param string $key The property.
	 *
	 * @return mixed The value
	 * @since 0.1.0
	 */
	public function __get( string $key ) {
		if ( method_exists( $this, 'get_' . $key ) ) {
			$value = call_user_func( [ $this, 'get_' . $key ] );
		} else {
			$value = $this->$key;
		}

		return $value;

	}


	/**
	 * Sets data when attempting to write to inaccessible properties.
	 *
	 * @param string $key   The property name.
	 * @param mixed  $value The value of the property.
	 *
	 * @since 0.1.0
	 */
	public function __set( string $key, $value ) {
		$ignore = [ '_ID', 'old_status' ];

		if ( ! in_array( $key, $ignore, true ) ) {
			$this->pending[ $key ] = $value;
		}

		if ( '_ID' !== $key ) {
			$this->$key = $value;
		}

	}


	/**
	 * Updates the post meta.
	 *
	 * @since 0.1.0
	 *
	 * @param string $meta_key   The key of the custom field you will edit.
	 * @param mixed  $meta_value The new value of the custom field.
	 * @param mixed  $prev_value The old value of the custom field you wish to change.
	 *
	 * @return bool|int Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
	 */
	public function update_meta( string $meta_key = '', $meta_value = '', $prev_value = '' ) {
		do_action( "{$this->config->get('prefix')}_update_{$this->post_type}_meta", $this, $meta_key, $meta_value );

		// Add prefix to meta key if it doesn't already have it.
		if ( substr( $meta_key, 0, strlen( $this->config->get( 'prefix' ) ) ) !== $this->config->get( 'prefix' ) ) {
			$meta_key = $this->config->get( 'prefix' ) . $meta_key;
		}

		if ( $results = update_post_meta( $this->ID, $meta_key, $meta_value, $prev_value) ) { // phpcs:ignore
			do_action( "{$this->config->get('prefix')}_updated_{$this->post_type}_meta", $this, $meta_key, $meta_value );
		}

		return $results;

	}


	/**
	 * Retrieves meta data for a post.
	 *
	 * @param string $meta_key The meta key to retrieve.
	 * @param bool   $single   Whether to return a single value.
	 *
	 * @return mixed
	 *
	 * @since 0.1.0
	 */
	public function get_meta( string $meta_key = '', $single = false ) {
		// Add prefix to meta key if it doesn't already have it.
		if ( substr( $meta_key, 0, strlen( $this->config->get( 'prefix' ) ) ) !== $this->config->get( 'prefix' ) ) {
			$meta_key = $this->config->get( 'prefix' ) . $meta_key;
		}

		return get_post_meta( $this->ID, $meta_key, $single );

	}


	/**
	 * This method should be overridden.
	 *
	 * @return array
	 */
	public function get_meta_boxes(): array {
		return [];

	}


	/**
	 * This method should be overridden.
	 *
	 * @return array
	 */
	public function get_all_meta( $post_id ) {
		return [];
	}


}
