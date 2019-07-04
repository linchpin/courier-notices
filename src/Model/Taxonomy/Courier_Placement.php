<?php
namespace Courier\Model\Taxonomy;

use \Courier\Model\Config;

/**
 * Class Courier_Type
 * @package Courier\Model\Taxonomy
 */
class Courier_Placement {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var string
	 */
	public $name = 'courier_placement';

	/**
	 * @var array|mixed|void
	 */
	private $labels = array();

	/**
	 * @var array|mixed|void
	 */
	private $args = array();

	/**
	 * Courier_Placement constructor.
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Placement', 'courier' ),
			'singular_name'              => esc_html_x( 'Placement', 'taxonomy general name', 'courier' ),
			'search_items'               => esc_html__( 'Search notice placements', 'courier' ),
			'popular_items'              => esc_html__( 'Popular notice placements', 'courier' ),
			'all_items'                  => esc_html__( 'All notice placements', 'courier' ),
			'parent_item'                => esc_html__( 'Parent notice placement', 'courier' ),
			'parent_item_colon'          => esc_html__( 'Parent notice placement:', 'courier' ),
			'edit_item'                  => esc_html__( 'Edit notice placement', 'courier' ),
			'update_item'                => esc_html__( 'Update notice placement', 'courier' ),
			'add_new_item'               => esc_html__( 'New notice placement', 'courier' ),
			'new_item_name'              => esc_html__( 'New notice placement', 'courier' ),
			'separate_items_with_commas' => esc_html__( 'Notice Placements separated by comma', 'courier' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice placements', 'courier' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice placements', 'courier' ),
			'menu_name'                  => esc_html__( 'Notice Placements', 'courier' ),
			'view_item'                  => esc_html__( 'View Notice Placements', 'courier' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier' ),
			'no_terms'                   => esc_html__( 'No types', 'courier' ),
			'items_list'                 => esc_html__( 'Notice Placements list', 'courier' ),
			'items_list_navigation'      => esc_html__( 'Notice Placements list navigation', 'courier' ),
		);

		$this->labels = apply_filters( 'courier_courier_placement_labels', $default_labels );

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
	 * @return array|mixed|void
	 */
	public function get_args() {
		return $this->args;
	}
}
