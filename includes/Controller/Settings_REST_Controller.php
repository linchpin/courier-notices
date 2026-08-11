<?php
/**
 * Settings Endpoint
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Controller\REST\REST_Base;
use CourierNotices\Model\Settings;

/**
 * Class Settings_REST_Controller
 */
class Settings_REST_Controller extends REST_Base {

	/**
	 * Option keys the settings screens may write to. The key used to be
	 * caller-controlled, which let any manage_options request write an
	 * arbitrary option name.
	 *
	 * @var string[]
	 */
	private const ALLOWED_OPTION_KEYS = [ 'courier_settings', 'courier_design' ];

	/**
	 * Add routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->get_api_namespace(),
			'/settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_settings' ),
				'args'                => array(),
				'permission_callback' => array( $this, 'get_manage_settings_permissions' ),
			)
		);

		register_rest_route(
			$this->get_api_namespace(),
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'args'                => array(),
				'permission_callback' => array( $this, 'get_manage_settings_permissions' ),
			)
		);
	}

	/**
	 * Update settings
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function update_settings( \WP_REST_Request $request ) {
		$option_key = $request->get_param( 'settings_key' );

		// The admin screens post their Settings API option_page as the key
		// (general -> courier_settings, design -> courier_design). Anything
		// else falls back to the primary settings option.
		if ( ! in_array( $option_key, self::ALLOWED_OPTION_KEYS, true ) ) {
			$option_key = 'courier_settings';
		}

		$settings_model = new Settings( $option_key );

		$settings_model->save_settings_array( $request->get_params() );

		return new \WP_REST_Response( $settings_model->get_settings() );
	}

	/**
	 * Get settings via API
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_settings( \WP_REST_Request $request ) {
		$settings_model = new Settings();

		return rest_ensure_response( $settings_model->get_settings() );
	}
}
