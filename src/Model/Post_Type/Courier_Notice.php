<?php
namespace Courier\Model\Post_Type;

use \Courier\Model\Config;

/**
 * Class Courier_Notice
 * @package Courier\Model\Post_Type
 */
class Courier_Notice {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var string
	 */
	public $name = 'courier_notice';

	/**
	 * @var array|mixed|void
	 */
	private $labels = array();

	/**
	 * @var array|mixed|void
	 */
	private $args = array();

	/**
	 * Welcome constructor.
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                  => esc_html__( 'Notices', 'courier' ),
			'singular_name'         => esc_html__( 'Notice', 'courier' ),
			'all_items'             => esc_html__( 'Notices', 'courier' ),
			'new_item'              => esc_html__( 'New notice', 'courier' ),
			'add_new'               => esc_html__( 'Add New', 'courier' ),
			'add_new_item'          => esc_html__( 'Add New notice', 'courier' ),
			'edit_item'             => esc_html__( 'Edit notice', 'courier' ),
			'view_item'             => esc_html__( 'View notice', 'courier' ),
			'search_items'          => esc_html__( 'Search notices', 'courier' ),
			'not_found'             => esc_html__( 'No notices found', 'courier' ),
			'not_found_in_trash'    => esc_html__( 'No notices found in trash', 'courier' ),
			'parent_item_colon'     => esc_html__( 'Parent notice', 'courier' ),
			'menu_name'             => esc_html__( 'Notices', 'courier' ),
			'name_admin_bar'        => esc_html__( 'Notice', 'courier' ),
			'archives'              => esc_html__( 'Notice Archives', 'courier' ),
			'attributes'            => esc_html__( 'Notice Attributes', 'courier' ),
			'update_item'           => esc_html__( 'Update Notice', 'courier' ),
			'view_items'            => esc_html__( 'View Notice', 'courier' ),
			'featured_image'        => esc_html__( 'Featured Image', 'courier' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'courier' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'courier' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'courier' ),
			'insert_into_item'      => esc_html__( 'Insert into Notice', 'courier' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Notice', 'courier' ),
			'items_list'            => esc_html__( 'Notice list', 'courier' ),
			'items_list_navigation' => esc_html__( 'Notice list navigation', 'courier' ),
			'filter_items_list'     => esc_html__( 'Filter Notice list', 'courier' ),
		);

		$this->labels = apply_filters( 'courier_notice_labels', $default_labels );

		$default_args = array(
			'label'               => esc_html__( 'Notice', 'courier' ),
			'description'         => esc_html__( 'Notices', 'courier' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'editor', 'page-attributes' ),
			'taxonomies'          => array( 'courier_type', 'courier_status', 'courier_scope' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-feedback',
			'rewrite'             => false,
		);

		$this->args = apply_filters( 'courier_notice_args', $default_args );
	}

	/**
	 * @return array|mixed|void
	 */
	public function get_args() {
		return $this->args;
	}
}
