<?php
/**
 * Bootstrap the plugin
 *
 * @package CourierNotices\Core
 */

namespace CourierNotices\Core;

use CourierNotices\Controller;
use CourierNotices\Model\Config;

/**
 * Bootstrap Class
 */
class Bootstrap {

	/**
	 * Every controller the plugin boots. Explicit on purpose: the old
	 * glob/reflection loader was regex-coupled to directory names, and a
	 * controller that failed the pattern silently disappeared. Add new
	 * controllers here.
	 *
	 * @var array<int, class-string>
	 */
	private const CONTROLLERS = [
		Controller\Action_Scheduler::class,
		Controller\Admin\Admin::class,
		Controller\Admin\Courier_Notice_Metabox::class,
		Controller\Admin\Settings\General::class,
		Controller\Block_Bindings::class,
		Controller\Courier::class,
		Controller\Courier_Notices::class,
		Controller\Courier_REST_Controller::class,
		Controller\Courier_Types::class,
		Controller\Install::class,
		Controller\Integrations\Stream::class,
		Controller\Integrations\WP_CLI::class,
		Controller\Integrations\WP_SEO::class,
		Controller\Placement::class,
		Controller\Settings_REST_Controller::class,
		Controller\Shortcodes::class,
		Controller\Upgrade::class,
		Controller\Welcome::class,
	];

	/**
	 * Config
	 *
	 * @var array|Config
	 */
	private $config = [];

	/**
	 * Controllers
	 *
	 * @var array
	 */
	private $controllers = [];


	/**
	 * Bootstrap constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->config = new Config();
	}


	/**
	 * Run the bootstrap
	 *
	 * @since 1.0
	 */
	public function run() {
		// Load the controllers in the Controller directory.
		$this->load_controllers();

		// Register actions for each controller.
		$this->register_actions();

		// Include helper functions.
		require_once COURIER_NOTICES_PATH . 'includes/Helper/Functions.php';
		require_once COURIER_NOTICES_PATH . 'includes/Helper/Deprecated.php';
		require_once COURIER_NOTICES_PATH . 'includes/Helper/WP_List_Table.php';
		require_once COURIER_NOTICES_PATH . 'includes/Helper/Type_List_Table.php';

		add_action( 'init', [ $this, 'load_textdomain' ] );

		// The plugin is ready.
		do_action( 'courier_notices_ready', $this );
	}


	/**
	 * Loads the plugin's textdomain for translation.
	 *
	 * We need to load the text domain a little later based on WordPress 6.6 changes.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		\load_plugin_textdomain( 'courier-notices', false, COURIER_NOTICES_PATH . 'languages/' );
	}


	/**
	 * Instantiates all the plugin's Controller classes
	 *
	 * @since 1.0.0
	 */
	private function load_controllers() {
		foreach ( self::CONTROLLERS as $class ) {
			$this->controllers[ $class ] = new $class();
		}
	}


	/**
	 * Initialize and Register any of our actions.
	 *
	 * The method_exists guard stays until every controller implements
	 * Controller_Interface — the stragglers carry PHPCS debt owed by
	 * later phases (Courier by the Phase 2 metabox rewrite, Courier_Types
	 * and the settings controllers by the Phase 5 React admin).
	 *
	 * @since 1.0
	 */
	private function register_actions() {
		foreach ( $this->controllers as $class ) {
			if ( method_exists( $class, 'register_actions' ) ) {
				$class->register_actions();
			}
		}
	}
}
