<?php
/**
 * View functionality
 *
 * @package CourierNotices\Core
 */

namespace CourierNotices\Core;

/**
 * View Class
 */
class View {
	/**
	 * Variables for substitution in templates
	 *
	 * @var array
	 */
	protected $variables = array();

	/**
	 * View constructor
	 *
	 * @since 1.0
	 *
	 * @param null $config The configuration.
	 */
	public function __construct( $config = null ) {
	}

	/**
	 * Load all assets on boot-up
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function load_assets() {
	}

	/**
	 * Render HTML file
	 *
	 * @since 1.0
	 *
	 * @param string $file     File to get HTML string.
	 * @param string $view_dir View directory.
	 *
	 * @return void
	 */
	public function render( $file, $view_dir = null ) {
		foreach ( $this->variables as $key => $value ) {
			${$key} = $value;
		}

		$view_dir  = isset( $view_dir ) ? trailingslashit( $view_dir ) : COURIER_NOTICES_PATH . 'templates/';
		$view_file = $view_dir . $file . '.php';

		if ( ! file_exists( $view_file ) ) {
			wp_die( $view_file );
		}

		require $view_file;
	}


	/**
	 * Assign variable for substitution in templates.
	 *
	 * @since 1.0
	 *
	 * @param string $variable Name variable to assign.
	 * @param mixed  $value    Value variable for assign.
	 *
	 * @return void
	 */
	public function assign( $variable, $value ) {
		$this->variables[ $variable ] = $value;
	}


	/**
	 * Get HTML from file
	 *
	 * @since 1.0
	 *
	 * @param string $file File to get HTML string.
	 * @param string $view_dir View directory.
	 *
	 * @return string $html HTML output as string
	 */
	public function get_text_view( $file, $view_dir = null ) {
		foreach ( $this->variables as $key => $value ) {
			${$key} = $value;
		}

		$view_dir  = isset( $view_dir ) ? $view_dir : COURIER_NOTICES_PATH . 'templates/';
		$view_file = $view_dir . $file . '.php';

		if ( ! file_exists( $view_file ) ) {
			return '';
		}

		ob_start();
		include $view_file;
		$thread = ob_get_contents();
		ob_end_clean();
		$html = $thread;

		$this->init_assignments();

		return $html;
	}

	/**
	 * Reset the variables
	 *
	 * @since 1.0
	 */
	protected function init_assignments() {
		$this->variables = array();
	}
}
