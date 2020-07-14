<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Courier Notices Placement Taxonomy Model
 *
 * @package CourierNotices\Model\Taxonomy
 */

namespace CourierNotices\Model\Taxonomy;

use CourierNotices\Model\Config;

/**
 * Placement Class
 */
class Placement {

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
	public $name = 'courier_placement';

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
	 * _Placement constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Placement', 'courier-notices' ),
			'singular_name'              => esc_html_x( 'Placement', 'taxonomy general name', 'courier-notices' ),
			'search_items'               => esc_html__( 'Search notice placements', 'courier-notices' ),
			'popular_items'              => esc_html__( 'Popular notice placements', 'courier-notices' ),
			'all_items'                  => esc_html__( 'All notice placements', 'courier-notices' ),
			'parent_item'                => esc_html__( 'Parent notice placement', 'courier-notices' ),
			'parent_item_colon'          => esc_html__( 'Parent notice placement:', 'courier-notices' ),
			'edit_item'                  => esc_html__( 'Edit notice placement', 'courier-notices' ),
			'update_item'                => esc_html__( 'Update notice placement', 'courier-notices' ),
			'add_new_item'               => esc_html__( 'New notice placement', 'courier-notices' ),
			'new_item_name'              => esc_html__( 'New notice placement', 'courier-notices' ),
			'separate_items_with_commas' => esc_html__( 'Notice Placements separated by comma', 'courier-notices' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice placements', 'courier-notices' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice placements', 'courier-notices' ),
			'menu_name'                  => esc_html__( 'Notice Placements', 'courier-notices' ),
			'view_item'                  => esc_html__( 'View Notice Placements', 'courier-notices' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier-notices' ),
			'no_terms'                   => esc_html__( 'No types', 'courier-notices' ),
			'items_list'                 => esc_html__( 'Notice Placements list', 'courier-notices' ),
			'items_list_navigation'      => esc_html__( 'Notice Placements list navigation', 'courier-notices' ),
		);

		$this->labels = apply_filters( 'courier_notices_courier_placement_labels', $default_labels );

		$default_args = array(
			'labels'            => $this->labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => false,
			'show_admin_column' => false,
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

		$this->args = apply_filters( 'courier_notices_courier_placement_args', $default_args );
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
