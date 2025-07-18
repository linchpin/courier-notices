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
	protected $variables = [];


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

		$view_dir  = isset( $view_dir ) ? $view_dir : COURIER_NOTICES_PATH . 'templates/';
		$view_file = $this->validate_and_build_path( $file, $view_dir );

		if ( false === $view_file ) {
			wp_die( esc_html__( 'Invalid file path', 'courier-notices' ) . ': ' . esc_html( $file ) );
		}

		if ( ! file_exists( $view_file ) ) {
			wp_die( esc_html( $view_file ) );
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
	 * @param string $file     File to get HTML string.
	 * @param string $view_dir View directory.
	 *
	 * @return string $html HTML output as string
	 */
	public function get_text_view( $file, $view_dir = null ) {
		foreach ( $this->variables as $key => $value ) {
			${$key} = $value;
		}

		$view_dir  = isset( $view_dir ) ? $view_dir : COURIER_NOTICES_PATH . 'templates/';
		$view_file = $this->validate_and_build_path( $file, $view_dir );

		if ( false === $view_file ) {
			return '';
		}

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
		$this->variables = [];
	}

	/**
	 * Validate and build secure file path
	 *
	 * @since 1.8.0
	 *
	 * @param string $file     The file name/path.
	 * @param string $view_dir The view directory.
	 *
	 * @return string|false The validated file path or false if invalid.
	 */
	protected function validate_and_build_path( string $file, string $view_dir ) {
		// Validate input parameters.
		if ( ! is_string( $file ) || '' === $file ) {
			return false;
		}

		if ( ! is_string( $view_dir ) || '' === $view_dir ) {
			return false;
		}

		// Normalize the view directory to absolute path.
		$view_dir = realpath( $view_dir );
		if ( false === $view_dir ) {
			return false;
		}

		// Ensure view directory is within the plugin directory.
		$plugin_path = realpath( COURIER_NOTICES_PATH );
		if ( false === $plugin_path || 0 !== strpos( $view_dir, $plugin_path ) ) {
			return false;
		}

		// Remove any directory traversal attempts.
		$file = str_replace( [ '../', '..\\', './', '.\\' ], '', $file );

		// Remove any null bytes or other dangerous characters.
		$file = str_replace( [ "\0", "\r", "\n" ], '', $file );

		// Ensure file doesn't start with a slash or backslash.
		$file = ltrim( $file, '/\\' );

		// Validate file name - allow alphanumeric, hyphens, underscores, forward slashes, and dots
		// but prevent directory traversal and other dangerous patterns.
		if ( ! preg_match( '/^[a-zA-Z0-9\/_-]+$/', $file ) ) {
			return false;
		}

		// Build the full file path with .php extension, ensuring proper path separators.
		$view_file = rtrim( $view_dir, '/\\' ) . DIRECTORY_SEPARATOR . $file . '.php';

		// Normalize the final path.
		$real_file_path = realpath( $view_file );

		// Ensure the final path is within the allowed directory.
		if ( false === $real_file_path || 0 !== strpos( $real_file_path, $view_dir ) ) {
			return false;
		}

		return $real_file_path;
	}
}
