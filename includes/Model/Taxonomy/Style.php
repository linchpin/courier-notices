<?php
/**
 * Courier Style Taxonomy Model
 *
 * @package CourierNotices\Model\Taxonomy
 */

namespace CourierNotices\Model\Taxonomy;

use CourierNotices\Model\Config;

/**
 * Courier_Style Class
 */
class Style {

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
	 * _Placement constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                       => esc_html__( 'Style', 'courier-notices' ),
			'singular_name'              => esc_html_x( 'Style', 'taxonomy general name', 'courier-notices' ),
			'search_items'               => esc_html__( 'Search notice styles', 'courier-notices' ),
			'popular_items'              => esc_html__( 'Popular notice styles', 'courier-notices' ),
			'all_items'                  => esc_html__( 'All notice styles', 'courier-notices' ),
			'parent_item'                => esc_html__( 'Parent notice style', 'courier-notices' ),
			'parent_item_colon'          => esc_html__( 'Parent notice style:', 'courier-notices' ),
			'edit_item'                  => esc_html__( 'Edit notice style', 'courier-notices' ),
			'update_item'                => esc_html__( 'Update notice style', 'courier-notices' ),
			'add_new_item'               => esc_html__( 'New notice style', 'courier-notices' ),
			'new_item_name'              => esc_html__( 'New notice style', 'courier-notices' ),
			'separate_items_with_commas' => esc_html__( 'Notice Styles separated by comma', 'courier-notices' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove notice styles', 'courier-notices' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used notice styles', 'courier-notices' ),
			'menu_name'                  => esc_html__( 'Notice Styles', 'courier-notices' ),
			'view_item'                  => esc_html__( 'View Notice Styles', 'courier-notices' ),
			'not_found'                  => esc_html__( 'Not Found', 'courier-notices' ),
			'no_terms'                   => esc_html__( 'No types', 'courier-notices' ),
			'items_list'                 => esc_html__( 'Notice Styles list', 'courier-notices' ),
			'items_list_navigation'      => esc_html__( 'Notice Styles list navigation', 'courier-notices' ),
		);

		$this->labels = apply_filters( 'courier_notices_courier_style_labels', $default_labels );

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


	/**
	 * Get all the styles associated with courier notices
	 *
	 * @since 1.2.8
	 *
	 * @param string|mixed $fields Allows for whatever WP_Term_Query allows
	 *
	 * @return array|mixed|void
	 */
	public function get_styles( $fields = 'all' ) {
		$courier_notice_styles = get_terms(
			array(
				'taxonomy'   => 'courier_style',
				'hide_empty' => false,
				'fields'     => $fields,
			)
		);

		return apply_filters( 'courier_notices_courier_styles', $courier_notice_styles );
	}


	public function get_styles_options() {
		$courier_notice_styles = $this->get_styles( 'all' );
		$styles                = array();

		if ( ! empty( $courier_notice_styles ) ) {
			foreach ( $courier_notice_styles as $courier_notice_style ) {
				$styles[] = array(
					'label' => $courier_notice_style->name,
					'value' => $courier_notice_style->slug,
				);
			}
		}

		return apply_filters( 'courier_notices_courier_style_options', $styles );
	}
}
