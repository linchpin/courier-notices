<?php
/**
 * Courier Scope Taxonomy Model
 *
 * @package CourierNotices\Model\Taxonomy
 */

namespace CourierNotices\Model\Taxonomy;

use CourierNotices\Model\Config;

/**
 * Courier_Type Class
 */
class Scope {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Courier scope name
	 *
	 * @var string
	 */
	public $name = 'courier_scope';

	/**
	 * Labels
	 *
	 * @var array
	 */
	private $labels = array();

	/**
	 * Arguments
	 *
	 * @var array
	 */
	private $args = array();


	/**
	 * _Scope constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Notice Scopes', 'courier-notices' ),
			'singular_name'              => esc_html_x( 'Notice Scope', 'taxonomy general name', 'courier-notices' ),
			'search_items'               => esc_html__( 'Search notice scopes', 'courier-notices' ),
			'popular_items'              => esc_html__( 'Popular notice scopes', 'courier-notices' ),
			'all_items'                  => esc_html__( 'All notice scopes', 'courier-notices' ),
			'parent_item'                => esc_html__( 'Parent notice scope', 'courier-notices' ),
			'parent_item_colon'          => esc_html__( 'Parent notice scope:', 'courier-notices' ),
			'edit_item'                  => esc_html__( 'Edit notice scope', 'courier-notices' ),
			'update_item'                => esc_html__( 'Update notice scope', 'courier-notices' ),
			'add_new_item'               => esc_html__( 'New notice scope', 'courier-notices' ),
			'new_item_name'              => esc_html__( 'New notice scope', 'courier-notices' ),
			'separate_items_with_commas' => esc_html__( 'Notice Scopes separated by comma', 'courier-notices' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice scopes', 'courier-notices' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice scopes', 'courier-notices' ),
			'menu_name'                  => esc_html__( 'Notice Scopes', 'courier-notices' ),
			'view_item'                  => esc_html__( 'View Notice Scopes', 'courier-notices' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier-notices' ),
			'no_terms'                   => esc_html__( 'No types', 'courier-notices' ),
			'items_list'                 => esc_html__( 'Notice Scopes list', 'courier-notices' ),
			'items_list_navigation'      => esc_html__( 'Notice Scopes list navigation', 'courier-notices' ),
		);

		$this->labels = apply_filters( 'courier_notices_courier_scope_labels', $default_labels );

		$default_args = array(
			'labels'                => $this->labels,
			'hierarchical'          => false,
			'public'                => false,
			'show_in_nav_menus'     => false,
			'show_ui'               => false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => false,
			'capabilities'          => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
			'show_tagcloud'         => false,
			'update_count_callback' => '_update_generic_term_count',
		);

		$this->args = apply_filters( 'courier_notices_courier_scope_args', $default_args );

	}


	/**
	 * Returns the arguments
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_args() {
		return $this->args;

	}


}
