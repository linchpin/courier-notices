<?php
/**
 * Bootstrap the plugin
 *
 * @package CourierNotices\Core
 */

namespace CourierNotices\Core;

use CourierNotices\Model\Config;
use CourierNotices\Helper\Files;

/**
 * Bootstrap Class
 */
class Bootstrap {

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
	 * Namespace
	 *
	 * @var string
	 */
	private $namespace = 'CourierNotices';


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
		foreach ( Files::glob_recursive( COURIER_NOTICES_PATH . 'includes/Controller/*.php' ) as $file ) {
			preg_match( '/\/Controller\/(.+(?<!_Interface|SOAP|Generic))\.php/', $file, $matches, PREG_OFFSET_CAPTURE );

			if ( empty( $matches ) ) {
				continue;
			}

			$name  = str_replace( '/', '\\', $matches[1][0] );
			$class = "\\{$this->namespace}\\Controller\\{$name}";

			$this->controllers[ $name ] = new $class();
		}
	}


	/**
	 * Initialize and Register any of our actions.
	 *
	 * @since 1.0
	 */
	private function register_actions() {
		foreach ( $this->controllers as $name => $class ) {
			if ( method_exists( $class, 'register_actions' ) ) {
				$class->register_actions();
			}
		}
	}
}
