<?php

namespace Courier\Controller\Admin\Settings;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use \Courier\Controller\Admin\Fields\Fields as Fields;
use \Courier\Helper\Type_List_Table as Type_List_Table;

/**
 * Control all of our plugin Settings
 *
 * @since      1.0
 * @package    Courier
 * @subpackage Settings
 */

/**
 * Class Settings
 * @package LinchpinHelpdesk\Controller\Admin
 */
class General {

	/**
	 * Define our settings page
	 *
	 * @var string
	 */
	public static $settings_page = 'courier';

	/**
	 * Give our plugin a name
	 *
	 * @var string
	 */
	public static $plugin_name = COURIER_PLUGIN_NAME;

	/**
	 * @var
	 */
	private static $type_list_table;

	/**
	 * Initialize our plugin settings.
	 *
	 * @since 1.0.0
	 */
	public static function register_actions() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'settings_init' ) );

		add_filter( 'plugin_action_links', array( __CLASS__, 'add_settings_link' ), 10, 5 );
	}

	/**
	 * Add the options page to our settings menu
	 */
	public static function add_admin_menu() {
		add_options_page( COURIER_PLUGIN_NAME, COURIER_PLUGIN_NAME, 'manage_options', self::$settings_page, array( __CLASS__, 'add_settings_page' ) );
		add_submenu_page( 'edit.php?post_type=courier_notice', COURIER_PLUGIN_NAME, esc_html__( 'Settings', 'courier' ), 'manage_options', self::$settings_page, array( __CLASS__, 'add_settings_page' ) );
	}

	/**
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return array
	 */
	public static function add_settings_link( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = 'courier/courier.php';
		}

		if ( $plugin === $plugin_file ) {

			$settings  = array( 'settings' => '<a href="options-general.php?page=' . esc_attr( self::$settings_page ) . '">' . esc_html__( 'Settings', 'courier' ) . '</a>' );
			$site_link = array( 'faq' => '<a href="https://linchpin.com/plugins/courier/" target="_blank">' . esc_html__( 'FAQ', 'courier' ) . '</a>' );

			$actions = array_merge( $settings, $actions );
			$actions = array_merge( $site_link, $actions );

		}

		return $actions;
	}

	/**
	 * Create our settings section
	 *
	 * @param $args
	 * @since 1.0.0
	 */
	public static function create_section( $args ) {
		?>
		<div class="gray-bg negative-bg">
			<div class="wrapper">
				<h2 class="color-darkpurple light-weight">
					<?php echo esc_html( $args['title'] ); ?>
				</h2>
			</div>
		</div>
		<?php
	}

	/**
	 * Add all of our settings from the API
	 *
	 * @since 1.1.0
	 *
	 */
	public static function settings_init() {

		// If we have save our settings flush the rewrite rules for our new structure
		if ( delete_transient( 'courier_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
		}

		// Setup General Settings
		self::setup_general_settings();

		// Setup Design Settings
		self::setup_design_settings();
	}

	/**
	 * Get our general settings registered
	 *
	 * @since 1.1.0
	 */
	private static function setup_general_settings() {
		$tab_section = 'courier_settings';

		register_setting( $tab_section, $tab_section );

		// Default Settings Section.
		add_settings_section(
			'courier_general_settings_section',
			'',
			array( __CLASS__, 'create_section' ),
			$tab_section
		);

		/**
		 * @todo this doesn't do anything yet.
		 */
		add_settings_field(
			'uninstall',
			esc_html__( 'Remove All Data on Uninstall?', 'courier' ),
			array( '\Courier\Controller\Admin\Fields\Fields', 'add_checkbox' ),
			$tab_section,
			'courier_general_settings_section',
			array(
				'field'   => 'uninstall',
				'section' => $tab_section,
				'options' => 'courier_settings',
				'label'   => esc_html__( 'Yes clear data', 'courier' ),
			)
		);
	}

	/**
	 * Design Panel
	 */
	private static function setup_design_settings() {
		$tab_section = 'courier_design';

		register_setting( $tab_section, $tab_section );

		// Default Settings Section.
		add_settings_section(
			'courier_design_settings_section',
			'',
			array( __CLASS__, 'create_section' ),
			$tab_section
		);

		/**
		 * Disable output of frontend css
		 */
		add_settings_field(
			'disable_css',
			esc_html__( 'Disable CSS on front end', 'courier' ),
			array( '\Courier\Controller\Admin\Fields\Fields', 'add_checkbox' ),
			$tab_section,
			'courier_design_settings_section',
			array(
				'field'       => 'disable_css',
				'section'     => $tab_section,
				'options'     => 'courier_design',
				'label'       => esc_html__( 'Yes disable CSS', 'courier' ),
				'description' => esc_html__( 'This is useful if you are using your own styles as part of your theme or overriding the css using the CSS Customizer', 'courier' ),
			)
		);

		add_settings_field(
			'notice_type_designs',
			esc_html__( 'Design Panel', 'courier' ),
			array( '\Courier\Controller\Admin\Fields\Fields', 'add_table' ),
			$tab_section,
			'courier_design_settings_section',
			array(
				'field'       => 'notice_type_designs',
				'section'     => $tab_section,
				'options'     => 'courier_design',
				'label'       => esc_html__( 'Yes disable CSS', 'courier' ),
				'description' => esc_html__( 'This is useful if you are using your own styles as part of your theme or overriding the css using the CSS Customizer', 'courier' ),
			)
		);
	}

	/**
	 * Add our options page wrapper Form
	 *
	 * @since 1.0
	 */
	public static function add_settings_page() {

		$tabs        = self::get_tabs();
		$default_tab = self::get_default_tab_slug();
		$active_tab  = isset( $_GET['tab'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_GET['tab'] ) ), $tabs ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab; // WPCS: input var okay, CSRF ok.

		require_once COURIER_PATH . 'templates/admin/settings.php';
	}

	/**
	 * Allow filtering of the settings tabs.
	 *
	 * @param array $default_settings Default Settings Array.
	 *
	 * @return array
	 */
	private static function apply_tab_slug_filters( $default_settings ) {

		$extended_settings[] = array();
		$extended_tabs       = self::get_tabs();

		foreach ( $extended_tabs as $tab_slug => $tab_desc ) {

			$options = isset( $default_settings[ $tab_slug ] ) ? $default_settings[ $tab_slug ] : array();

			$extended_settings[ $tab_slug ] = apply_filters( 'courier_' . $tab_slug, $options );
		}

		return $extended_settings;
	}

	/**
	 * Get the default tab slug
	 *
	 * @return mixed
	 */
	public static function get_default_tab_slug() {
		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since    1.0.0
	 * @return   array $tabs Settings tabs
	 */
	public static function get_tabs() {
		$tabs = array(
			'settings'  => array(
				'label'    => esc_html__( 'General Settings', 'courier' ),
				'sub_tabs' => array(),
			),
			'design'    => array(
				'label'    => esc_html__( 'Design', 'courier' ),
				'sub_tabs' => array(),
			),
			'addons'    => array(
				'label'    => esc_html__( 'Add Ons', 'courier' ),
				'sub_tabs' => array(),
			),
			'about'     => array(
				'label'    => esc_html__( 'About Courier', 'courier' ),
				'sub_tabs' => array(),
			),
			'new'       => array(
				'label'    => esc_html__( "What's New", 'courier' ),
				'sub_tabs' => array(),
			),
			'changelog' => array(
				'label'    => esc_html__( 'Change Log', 'courier' ),
				'sub_tabs' => array(),
			),
			'linchpin'  => array(
				'label'    => esc_html__( 'About Linchpin', 'courier' ),
				'sub_tabs' => array(),
			),
		);

		return apply_filters( 'courier_settings_tabs', $tabs );
	}

	/**
	 * Build out our submenu if we have one.
	 * Allow for this to be extended by addons.
	 *
	 * @since 1.2.0
	 *
	 * @param $parent_tab
	 *
	 * @return mixed
	 */
	public static function get_sub_tabs( $parent_tab ) {

		$sub_tabs = self::get_tabs();

		return $sub_tabs[ $parent_tab ]['sub_tabs'];
	}

	/**
	 * Utility Method to get a request parameter within the admin
	 * Strip it of malicious things.
	 *
	 * @since        1.2.0
	 * @param string $key
	 * @param string $default
	 *
	 * @return string
	 */
	public static function get_request_param( $key, $default = '' ) {
		// If not request set
		if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
			return $default;
		}

		// Set so process it
		return strip_tags( (string) wp_unslash( $_REQUEST[ $key ] ) );
	}
}
