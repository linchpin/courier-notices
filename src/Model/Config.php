<?php

namespace Courier\Model;

/**
 * Class Config
 * @package Courier\Model
 */
class Config {

	/**
	 * @var array
	 */
	private $properties = array();

	/**
	 * Config constructor.
	 */
	public function __construct() {
		$this->setup_plugin_config();
	}

	/**
	 * Define our configuration settings
	 * @return bool|mixed
	 */
	private function setup_plugin_config() {
		$config = wp_cache_get( 'config', 'courier' );

		if ( false !== $config ) {
			return $config;
		}

		$this->set( 'plugin_base_name', plugin_basename( COURIER_FILE ) );

		$plugin_headers = get_file_data(
			COURIER_FILE,
			array(
				'plugin_name'      => 'Plugin Name',
				'plugin_uri'       => 'Plugin URI',
				'description'      => 'Description',
				'author'           => 'Author',
				'version'          => 'Version',
				'author_uri'       => 'Author URI',
				'textdomain'       => 'Text Domain',
				'text_domain_path' => 'Domain Path',
			)
		);

		$this->import( $plugin_headers );

		$this->set( 'prefix', '_courier_' );
		$this->set( 'plugin_path', COURIER_PATH );
		$this->set( 'plugin_file', COURIER_FILE );
		$this->set( 'plugin_url', COURIER_PLUGIN_URL );
		$this->set( 'namespace', 'Courier' );

		wp_cache_set( 'config', $config, 'courier' );

		$this->config = $config;

		return $config;
	}

	/**
	 * Config Getter
	 *
	 * @param $name
	 *
	 * @return bool|mixed
	 */
	public function get( $name ) {
		if ( isset( $this->properties[ $name ] ) ) {
			return $this->properties[ $name ];
		}

		return false;
	}

	/**
	 * Config Getter
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return $this
	 */
	public function set( $name, $value ) {
		$this->properties[ $name ] = $value;

		return $this;
	}

	/**
	 * Config Importer
	 *
	 * @param $var
	 *
	 * @return $this|bool
	 */
	public function import( $var ) {
		if ( ! is_array( $var ) && ! is_object( $var ) ) {
			return false;
		}

		foreach ( $var as $name => $value ) {
			$this->properties[ $name ] = $value;
		}

		return $this;
	}

}
