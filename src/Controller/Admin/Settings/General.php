<?php
/**
 * Control all of our plugin Settings
 *
 * @since   1.0
 * @package CourierNotices\Controller\Admin\Settings
 */

namespace CourierNotices\Controller\Admin\Settings;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use CourierNotices\Controller\Admin\Fields\Fields as Fields;
use CourierNotices\Core\View;
use CourierNotices\Helper\Type_List_Table as Type_List_Table;

/**
 * Settings Class.
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
	public static $plugin_name = COURIER_NOTICES_PLUGIN_NAME;

	/**
	 * Instance of Type_List_Table
	 *
	 * @var Type_List_Table
	 */
	private static $type_list_table;

	/**
	 * Initialize our plugin settings
	 *
	 * @since 1.0
	 */
	public static function register_actions() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'settings_init' ) );

		add_filter( 'plugin_action_links', array( __CLASS__, 'add_settings_link' ), 10, 5 );
	}

	/**
	 * Add the options page to our settings menu
	 *
	 * @since 1.0
	 */
	public static function add_admin_menu() {
		global $submenu;

		$design = admin_url( 'edit.php?post_type=courier_notice&page=courier&tab=design' );

		$submenu['edit.php?post_type=courier_notice'][] = array( esc_html__( 'Types/Design', 'courier-notices' ), 'manage_options', esc_url( $design ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		add_submenu_page( 'edit.php?post_type=courier_notice', COURIER_NOTICES_PLUGIN_NAME, esc_html__( 'Settings', 'courier-notices' ), 'manage_options', self::$settings_page, array( __CLASS__, 'add_settings_page' ) );
	}

	/**
	 * Add settings link
	 *
	 * @since 1.0
	 *
	 * @param array  $actions     Actions.
	 * @param string $plugin_file Plugin file.
	 *
	 * @return array
	 */
	public static function add_settings_link( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = 'courier-notices/courier-notices.php';
		}

		if ( $plugin === $plugin_file ) {

			$plugin_url = 'edit.php?post_type=courier_notice&page=courier';

			$settings = array(
				'settings' => '<a href="' . esc_url( admin_url( $plugin_url ) ) . '">' . esc_html__( 'Settings', 'courier-notices' ) . '</a>',
			);

			$site_link = array(
				'faq'    => '<a href="https://linchpin.com/plugins/courier/" target="_blank">' . esc_html__( 'FAQ', 'courier-notices' ) . '</a>',
				'go_pro' => '<a href="https://shop.linchpin.com/plugins/courier-pro/" target="_blank">' . esc_html__( 'Go Pro', 'courier-notices' ) . '</a>',
			);

			$actions = array_merge( $settings, $actions );
			$actions = array_merge( $site_link, $actions );
		}

		/**
		 * Allow for settings links to be filtered.
		 *
		 * @since 1.0
		 */
		$actions = apply_filters( 'courier_notices_settings_links', $actions );

		return $actions;
	}

	/**
	 * Create our settings section
	 *
	 * @since 1.0
	 *
	 * @param array $args Array of arguments.
	 */
	public static function create_section( $args ) {
		if ( ! empty( $args['title'] ) ) {
			?>
			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php echo esc_html( $args['title'] ); ?>
					</h2>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Add all of our settings from the API
	 *
	 * @since 1.0
	 */
	public static function settings_init() {

		// If we have save our settings flush the rewrite rules for our new structure.
		if ( delete_transient( 'courier_notices_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
		}

		// Setup General Settings.
		self::setup_general_settings();

		// Setup Design Settings.
		self::setup_design_settings();
	}

	/**
	 * Get our general settings registered
	 *
	 * @since 1.0
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
		 * Add settings field
		 *
		 * @todo this doesn't do anything yet.
		 */
		add_settings_field(
			'ajax_notices',
			esc_html__( 'Use Ajax to display courier notices?', 'courier-notices' ),
			array( '\CourierNotices\Controller\Admin\Fields\Fields', 'add_checkbox' ),
			$tab_section,
			'courier_general_settings_section',
			array(
				'field'       => 'ajax_notices',
				'section'     => $tab_section,
				'options'     => 'courier_settings',
				'label'       => esc_html__( 'Yes use Ajax', 'courier-notices' ),
				'description' => esc_html__( 'Using ajax allows for Courier Notices to potentially load quicker. It also helps with issues that may come up with more advanced caching (Varnish, or other full page caching)', 'courier-notices' ),
			)
		);

		/**
		 * Add settings field
		 *
		 * @todo this doesn't do anything yet.
		 */
		add_settings_field(
			'uninstall',
			esc_html__( 'Remove All Data on Uninstall?', 'courier-notices' ),
			array( '\CourierNotices\Controller\Admin\Fields\Fields', 'add_checkbox' ),
			$tab_section,
			'courier_general_settings_section',
			array(
				'field'   => 'uninstall',
				'section' => $tab_section,
				'options' => 'courier_settings',
				'label'   => esc_html__( 'Yes clear data', 'courier-notices' ),
			)
		);
	}

	/**
	 * Design Panel
	 *
	 * @since 1.0
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

		add_settings_field(
			'notice_type_designs',
			esc_html__( 'Types', 'courier-notices' ),
			array( '\CourierNotices\Controller\Admin\Fields\Fields', 'add_table' ),
			$tab_section,
			'courier_design_settings_section',
			array(
				'field'       => 'notice_type_designs',
				'section'     => $tab_section,
				'options'     => 'courier_design',
				'class'       => 'type_table',
				'label'       => esc_html__( 'Courier Types', 'courier-notices' ),
				'description' => esc_html__( 'From this panel you can create and edit different types of Courier notices.', 'courier-notices' ),
			)
		);

		/**
		 * Disable output of frontend css
		 */
		add_settings_field(
			'disable_css',
			esc_html__( 'Disable CSS on front end', 'courier-notices' ),
			array( '\CourierNotices\Controller\Admin\Fields\Fields', 'add_checkbox' ),
			$tab_section,
			'courier_design_settings_section',
			array(
				'field'       => 'disable_css',
				'section'     => $tab_section,
				'options'     => 'courier_design',
				'label'       => esc_html__( 'Yes disable CSS', 'courier-notices' ),
				'description' => esc_html__( 'This is useful if you are using your own styles as part of your theme or overriding the css using the Appearance -> Customizer', 'courier-notices' ),
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
		$active_tab  = isset( $_GET['tab'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_GET['tab'] ) ), $tabs ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab; // phpcs:ignore WordPress.Security.NonceVerification

		$settings = new View();
		$settings->assign( 'tabs', $tabs );
		$settings->assign( 'sub_tabs', self::get_sub_tabs( $active_tab ) );
		$settings->assign( 'default_tab', $default_tab );
		$settings->assign( 'active_tab', $active_tab );
		$settings->assign( 'request_sub_tab', self::get_request_param( 'subtab', '' ) );
		$settings->assign( 'plugin_name', self::$plugin_name );
		$settings->render( 'admin/settings' );
	}

	/**
	 * Allow filtering of the settings tabs
	 *
	 * @since 1.0
	 *
	 * @param array $default_settings Default settings array.
	 *
	 * @return array
	 */
	private static function apply_tab_slug_filters( $default_settings ) {

		$extended_settings[] = array();
		$extended_tabs       = self::get_tabs();

		foreach ( $extended_tabs as $tab_slug => $tab_desc ) {

			$options = isset( $default_settings[ $tab_slug ] ) ? $default_settings[ $tab_slug ] : array();

			$extended_settings[ $tab_slug ] = apply_filters( 'courier_notices_' . $tab_slug, $options );
		}

		return $extended_settings;
	}

	/**
	 * Get the default tab slug
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public static function get_default_tab_slug() {
		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since 1.0
	 *
	 * @return array $tabs Settings tabs
	 */
	public static function get_tabs() {
		$tabs = array(
			'settings'  => array(
				'label'    => esc_html__( 'General Settings', 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'design'    => array(
				'label'    => esc_html__( 'Notice Types / Design', 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'gopro'     => array(
				'label'    => esc_html__( 'Go Pro', 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'about'     => array(
				'label'    => esc_html__( 'About Courier', 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'new'       => array(
				'label'    => esc_html__( "What's New", 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'changelog' => array(
				'label'    => esc_html__( 'Change Log', 'courier-notices' ),
				'sub_tabs' => array(),
			),
			'linchpin'  => array(
				'label'    => esc_html__( 'About Linchpin', 'courier-notices' ),
				'sub_tabs' => array(),
			),
		);

		return apply_filters( 'courier_notices_settings_tabs', $tabs );
	}

	/**
	 * Build out our submenu if we have one.
	 * Allow for this to be extended by addons.
	 *
	 * @since 1.0
	 *
	 * @param string $parent_tab The parent of the sub tab to retrieve.
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
	 * @since 1.0
	 *
	 * @param string $key     The parameter key.
	 * @param string $default The default value.
	 *
	 * @return string
	 */
	public static function get_request_param( $key, $default = '' ) {
		// If request not set.
		if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return $default;
		}

		// It's set, so process it.
		return wp_strip_all_tags( (string) wp_unslash( $_REQUEST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
}
