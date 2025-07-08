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
	private $config = array();

	/**
	 * Controllers
	 *
	 * @var array
	 */
	private $controllers = array();


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
		include_once $this->config->get( 'plugin_path' ) . 'src/Helper/Functions.php';
		include_once $this->config->get( 'plugin_path' ) . 'src/Helper/Deprecated.php';
		include_once $this->config->get( 'plugin_path' ) . 'src/Helper/WP_List_Table.php';
		include_once $this->config->get( 'plugin_path' ) . 'src/Helper/Type_List_Table.php';

		// The plugin is ready.
		do_action( 'courier_notices_ready', $this );

	}


	/**
	 * Loop over all php files in the Controllers directory and add them to
	 * the $controllers array
	 *
	 * @since 1.0
	 */
	private function load_controllers() {
		$namespace = $this->config->get( 'namespace' );

		foreach ( Files::glob_recursive( $this->config->get( 'plugin_path' ) . 'src/Controller/*.php' ) as $file ) {
			preg_match( '/\/Controller\/(.+)\.php/', $file, $matches, PREG_OFFSET_CAPTURE );
			$name  = str_replace( '/', '\\', $matches[1][0] );
			$class = '\\' . $namespace . '\\Controller\\' . $name;

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
