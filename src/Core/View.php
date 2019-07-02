<?php

namespace Courier\Core;

/**
 * Class View
 * @package Courier\Core
 */
class View {
	/**
	 * Variables for substitution in templates.
	 *
	 * @var array
	 */
	protected $variables = array();

	/**
	 * View constructor.
	 * * @param null $config
	 */
	public function __construct( $config = null ) {
	}

	/**
	 * Load all assets on boot-up.
	 *
	 * @return void
	 */
	public function load_assets() {
	}

	/**
	 * Render HTML file.
	 *
	 * @param string $file     File to get HTML string
	 * @param string $view_dir View directory
	 *
	 * @return void
	 */
	public function render( $file, $view_dir = null ) {
		foreach ( $this->variables as $key => $value ) {
			${$key} = $value;
		}

		$view_dir  = isset( $view_dir ) ? $view_dir : COURIER_PATH . 'templates/';
		$view_file = $view_dir . $file . '.php';

		if ( ! file_exists( $view_file ) ) {
			return;
		}

		include_once $view_file;
	}


	/**
	 * Assign variable for substitution in templates.
	 *
	 * @param string $variable Name variable to assign
	 * @param mixed  $value    Value variable for assign
	 *
	 * @return void
	 */
	public function assign( $variable, $value ) {
		$this->variables[ $variable ] = $value;
	}


	/**
	 * Get HTML from file.
	 *
	 * @paramg string $file File to get HTML string
	 * @param string $view_dir View directory
	 *
	 * @return string $html HTML output as string
	 */
	public function get_text_view( $file, $view_dir = null ) {
		foreach ( $this->variables as $key => $value ) {
			${$key} = $value;
		}

		$view_dir  = isset( $view_dir ) ? $view_dir : COURIER_PATH . 'templates/';
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
	 */
	protected function init_assignments() {
		$this->variables = array();
	}
}
