<?php
namespace Courier\Model\Taxonomy;

use \Courier\Model\Config;

/**
 * Class Courier_Status
 * @package Courier\Model\Taxonomy
 */
class Courier_Status {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var string
	 */
	public $name = 'courier_status';

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
			'name'                       => esc_html__( 'Statuses', 'courier' ),
			'singular_name'              => esc_html_x( 'Status', 'taxonomy general name', 'courier' ),
			'search_items'               => esc_html__( 'Search statuses', 'courier' ),
			'popular_items'              => esc_html__( 'Popular statuses', 'courier' ),
			'all_items'                  => esc_html__( 'All statuses', 'courier' ),
			'parent_item'                => esc_html__( 'Parent status', 'courier' ),
			'parent_item_colon'          => esc_html__( 'Parent statuses:', 'courier' ),
			'edit_item'                  => esc_html__( 'Edit statuses', 'courier' ),
			'update_item'                => esc_html__( 'Update statuses', 'courier' ),
			'add_new_item'               => esc_html__( 'New statuses', 'courier' ),
			'new_item_name'              => esc_html__( 'New statuses', 'courier' ),
			'separate_items_with_commas' => esc_html__( 'Statuses separated by comma', 'courier' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove statuses', 'courier' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used statuses', 'courier' ),
			'menu_name'                  => esc_html__( 'Statuses', 'courier' ),
			'view_item'                  => esc_html__( 'View Notice Scopes', 'courier' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier' ),
			'no_terms'                   => esc_html__( 'No types', 'courier' ),
			'items_list'                 => esc_html__( 'Notice Scopes list', 'courier' ),
			'items_list_navigation'      => esc_html__( 'Notice Scopes list navigation', 'courier' ),
		);

		$this->labels = apply_filters( 'courier_courier_status_labels', $default_labels );

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

		$this->args = apply_filters( 'courier_courier_status_args', $default_args );
	}

	/**
	 * @return array|mixed|void
	 */
	public function get_args() {
		return $this->args;
	}
}
