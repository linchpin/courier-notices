<?php
/**
 * Courier Notice Model
 *
 * @package CourierNotices\Model\Post_Type
 */
namespace CourierNotices\Model\Post_Type;

use CourierNotices\Model\Config;

/**
 * Courier_Notice Class
 */
class Courier_Notice {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Notice Name
	 *
	 * @var string
	 */
	public $name = 'courier_notice';

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
	 * Courier_Notice constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();

		$default_labels = array(
			'name'                  => esc_html__( 'Courier Notices', 'courier-notices' ),
			'singular_name'         => esc_html__( 'Notice', 'courier-notices' ),
			'all_items'             => esc_html__( 'All Notices', 'courier-notices' ),
			'new_item'              => esc_html__( 'New notice', 'courier-notices' ),
			'add_new'               => esc_html__( 'Add New', 'courier-notices' ),
			'add_new_item'          => esc_html__( 'Add New notice', 'courier-notices' ),
			'edit_item'             => esc_html__( 'Edit notice', 'courier-notices' ),
			'view_item'             => esc_html__( 'View notice', 'courier-notices' ),
			'search_items'          => esc_html__( 'Search notices', 'courier-notices' ),
			'not_found'             => esc_html__( 'No notices found', 'courier-notices' ),
			'not_found_in_trash'    => esc_html__( 'No notices found in trash', 'courier-notices' ),
			'parent_item_colon'     => esc_html__( 'Parent notice', 'courier-notices' ),
			'menu_name'             => esc_html__( 'Courier Notices', 'courier-notices' ),
			'name_admin_bar'        => esc_html__( 'Notice', 'courier-notices' ),
			'archives'              => esc_html__( 'Notice Archives', 'courier-notices' ),
			'attributes'            => esc_html__( 'Notice Attributes', 'courier-notices' ),
			'update_item'           => esc_html__( 'Update Notice', 'courier-notices' ),
			'view_items'            => esc_html__( 'View Notice', 'courier-notices' ),
			'featured_image'        => esc_html__( 'Featured Image', 'courier-notices' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'courier-notices' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'courier-notices' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'courier-notices' ),
			'insert_into_item'      => esc_html__( 'Insert into Notice', 'courier-notices' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Notice', 'courier-notices' ),
			'items_list'            => esc_html__( 'Notice list', 'courier-notices' ),
			'items_list_navigation' => esc_html__( 'Notice list navigation', 'courier-notices' ),
			'filter_items_list'     => esc_html__( 'Filter Notice list', 'courier-notices' ),
		);

		$this->labels = apply_filters( 'courier_notice_labels', $default_labels ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Public back-compat filter since 1.0; renaming it breaks existing consumers. See the back-compat landmines section of docs/2.0-MIGRATION-PLAN.md.

		$default_args = array(
			'label'               => esc_html__( 'Notice', 'courier-notices' ),
			'description'         => esc_html__( 'Notices', 'courier-notices' ),
			'labels'              => $this->labels,
			// custom-fields is load-bearing: without it, registered post meta
			// is not exposed over REST and the block editor cannot save it.
			'supports'            => array( 'title', 'editor', 'custom-fields' ),
			// Authors compose inside the courier/notice block rather than a
			// blank post canvas - the same block markup renders in the
			// editor and on the front end. The block cannot be removed or
			// moved; its layouts govern what goes inside (informational is
			// a locked message, robust is free-form, popup-modal previews
			// as the modal it displays in).
			'template'            => array(
				array(
					'courier/notice',
					array(
						'lock' => array(
							'move'   => true,
							'remove' => true,
						),
					),
				),
			),
			// A notice IS the courier/notice block, so the root accepts
			// nothing else. Without this lock the per-block move/remove lock
			// above still left the root inserter open, and a sibling
			// paragraph or heading dropped at the root would serialize into
			// post_content outside the notice - invisible to render.php and
			// silently dropped by the legacy wp_kses_post render path.
			// The lock applies to the ROOT list only: courier/notice passes
			// its own templateLock explicitly per layout, and an explicit
			// value overrides inheritance, so robust stays free-form.
			'template_lock'       => 'all',
			'taxonomies'          => array( 'courier_type', 'courier_status', 'courier_scope', 'courier_style', 'courier_placement' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'show_in_rest'        => true,
			'rest_base'           => 'courier-notices',
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'rewrite'             => false,
		);

		$this->args = apply_filters( 'courier_notices_notice_args', $default_args );
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
