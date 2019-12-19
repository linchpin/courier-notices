<?php
/**
 * Courier Style Taxonomy Model
 *
 * @package Courier\Model\Taxonomy
 */

namespace Courier\Model\Taxonomy;

use \Courier\Model\Config;

/**
 * Courier_Style Class
 */
class Courier_Style {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Placement name
	 *
	 * @var string
	 */
	public $name = 'courier_style';

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
	 * Courier_Placement constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Style', 'courier' ),
			'singular_name'              => esc_html_x( 'Style', 'taxonomy general name', 'courier' ),
			'search_items'               => esc_html__( 'Search notice styles', 'courier' ),
			'popular_items'              => esc_html__( 'Popular notice styles', 'courier' ),
			'all_items'                  => esc_html__( 'All notice styles', 'courier' ),
			'parent_item'                => esc_html__( 'Parent notice style', 'courier' ),
			'parent_item_colon'          => esc_html__( 'Parent notice style:', 'courier' ),
			'edit_item'                  => esc_html__( 'Edit notice style', 'courier' ),
			'update_item'                => esc_html__( 'Update notice style', 'courier' ),
			'add_new_item'               => esc_html__( 'New notice style', 'courier' ),
			'new_item_name'              => esc_html__( 'New notice style', 'courier' ),
			'separate_items_with_commas' => esc_html__( 'Notice Styles separated by comma', 'courier' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice styles', 'courier' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice styles', 'courier' ),
			'menu_name'                  => esc_html__( 'Notice Styles', 'courier' ),
			'view_item'                  => esc_html__( 'View Notice Styles', 'courier' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier' ),
			'no_terms'                   => esc_html__( 'No types', 'courier' ),
			'items_list'                 => esc_html__( 'Notice Styles list', 'courier' ),
			'items_list_navigation'      => esc_html__( 'Notice Styles list navigation', 'courier' ),
		);

		$this->labels = apply_filters( 'courier_courier_style_labels', $default_labels );

		$default_args = array(
			'labels'            => $this->labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => false,
			'capabilities'      => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
			'show_tagcloud'     => false,
		);

		$this->args = apply_filters( 'courier_courier_placement_args', $default_args );
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
