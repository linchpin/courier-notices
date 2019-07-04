<?php
namespace Courier\Model\Taxonomy;

use \Courier\Model\Config;

/**
 * Class Courier_Type
 * @package Courier\Model\Taxonomy
 */
class Courier_Type {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var string
	 */
	public $name = 'courier_type';

	/**
	 * @var array|mixed|void
	 */
	private $labels = array();

	/**
	 * @var array|mixed|void
	 */
	private $args = array();

	/**
	 * Courier_Status constructor.
	 *
	 */
	function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Types', 'courier' ),
			'singular_name'              => esc_html_x( 'Notice Type', 'taxonomy general name', 'courier' ),
			'search_items'               => esc_html__( 'Search notice types', 'courier' ),
			'popular_items'              => esc_html__( 'Popular notice types', 'courier' ),
			'all_items'                  => esc_html__( 'All notice types', 'courier' ),
			'parent_item'                => esc_html__( 'Parent notice type', 'courier' ),
			'parent_item_colon'          => esc_html__( 'Parent notice type:', 'courier' ),
			'edit_item'                  => esc_html__( 'Edit notice type', 'courier' ),
			'update_item'                => esc_html__( 'Update notice type', 'courier' ),
			'add_new_item'               => esc_html__( 'New notice type', 'courier' ),
			'new_item_name'              => esc_html__( 'New notice type', 'courier' ),
			'separate_items_with_commas' => esc_html__( 'Notice Types separated by comma', 'courier' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice types', 'courier' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice types', 'courier' ),
			'menu_name'                  => esc_html__( 'Types', 'courier' ),
			'view_item'                  => esc_html__( 'View Type', 'courier' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier' ),
			'no_terms'                   => esc_html__( 'No types', 'courier' ),
			'items_list'                 => esc_html__( 'Types list', 'courier' ),
			'items_list_navigation'      => esc_html__( 'Types list navigation', 'courier' ),
		);

		$this->labels = apply_filters( 'courier_courier_type_labels', $default_labels );

		$default_args = array(
			'labels'            => $this->labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'meta_box_cb'       => false,
			'show_admin_column' => true,
			'query_var'         => false,
			'rewrite'           => false,
			'show_tagcloud'     => true,
			'capabilities'      => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
		);

		$this->args = apply_filters( 'courier_courier_type_args', $default_args );

	}

	/**
	 * @return array|mixed|void
	 */
	public function get_args() {
		return $this->args;
	}
}
