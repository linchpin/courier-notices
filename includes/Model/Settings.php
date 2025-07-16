<?php
/**
 * Settings Model
 *
 * @package CourierNotices\Model
 */

namespace CourierNotices\Model;

/**
 * Class Settings
 *
 * @package CourierNotices\Model
 */
class Settings {

	/**
	 * Option key to save settings
	 *
	 * @var string
	 */
	protected $option_key = 'courier_settings';

	/**
	 * Default settings
	 *
	 * @var array
	 */
	private $defaults = array(
		// Overarching Plugin Settings
		'ajax_notices'            => true,
		'clear_data_on_uninstall' => false,
		'enable_block_editor'     => false,

		// Design Related Settings
		'disable_css'             => false,
		'enable_title'            => '', // Display the title of the notice on the front end
	);


	/**
	 * Initialize our class and setup our settings key
	 *
	 * @since 1.3.0
	 *
	 * @param string $option_key
	 */
	public function __construct( $option_key = 'courier_settings' ) {
		$defaults         = $this->defaults;
		$this->defaults   = apply_filters( 'courier_notices_allowed_settings', $defaults );
		$this->option_key = $option_key;
	}


	/**
	 * Get saved settings
	 *
	 * @since 0.2.0
	 *
	 * @return array
	 */
	public function get_settings() {
		$saved = get_option( $this->option_key, array() );

		if ( ! is_array( $saved ) || empty( $saved ) ) {
			return $this->defaults;
		}

		return wp_parse_args( $saved, $this->defaults );
	}


	/**
	 * Get an individual option from our options array
	 *
	 * @since 0.6.0
	 *
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_setting( $key = '' ) {
		if ( empty( $key ) ) {
			return null;
		}

		$settings = $this->get_settings();

		return $settings[ $key ];
	}


	/**
	 * Saves a single setting
	 *
	 * Array keys must be whitelisted (see $this->defaults)
	 *
	 * @since 0.2.0
	 *
	 * @param string $key   The setting name.
	 * @param mixed  $value The value of the setting.
	 *
	 * @return array|false
	 */
	public function save_setting( $key, $value ) {
		if ( array_key_exists( $key, $this->defaults ) ) {
			return false;
		}

		$settings         = $this->get_settings();
		$settings[ $key ] = $value;

		if ( update_option( $this->option_key, $settings ) ) {
			return $settings;
		}

		return false;
	}


	/**
	 * Similar to the save_setting method but allows for passing of array data as well
	 */
	public function save_settings_array( $settings = array() ) {
		$current_settings = $this->get_settings();

		// Remove any settings that don't belong.
		foreach ( $settings as $key => $setting ) {
			if ( ! array_key_exists( $key, $this->defaults ) ) {
				unset( $settings[ $key ] );
			}
		}

		$merged_settings = wp_parse_args( $settings, $current_settings );

		if ( update_option( $this->option_key, $merged_settings ) ) {
			return $merged_settings;
		}

		return false;
	}


	/**
	 * Saves an array of settings
	 *
	 * Array keys must be whitelisted (see $this->defaults)
	 *
	 * @param array $settings Array of settings to save.
	 */
	public function save_settings( \WP_REST_Request $request ) {
		$settings = $request->get_params();

		// @todo this could be moved to a utility, this is used elsewhere
		foreach ( $settings as $key => $setting ) {
			// Remove any settings that don't belong.
			if ( ! array_key_exists( $key, $this->defaults ) ) {
				unset( $settings[ $key ] );
			}
		}

		return $this->save_settings_array( $settings );
	}
}
