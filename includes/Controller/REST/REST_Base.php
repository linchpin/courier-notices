<?php
/**
 * Base class for the plugin's REST controllers.
 *
 * @since 2.0.0
 *
 * @package CourierNotices\Controller\REST
 */

namespace CourierNotices\Controller\REST;

use CourierNotices\Controller\Controller_Interface;

/**
 * Class REST_Base
 *
 * Modeled on mantle's REST_Base: the API namespace is composed in one
 * place, hook registration is idempotent, and permission callbacks are
 * named for the intent they express rather than the capability they
 * happen to check today.
 *
 * @since 2.0.0
 */
abstract class REST_Base implements Controller_Interface {

	/**
	 * Base path for every endpoint.
	 *
	 * @var string
	 */
	private $api_base_path = 'courier-notices';

	/**
	 * API version.
	 *
	 * @var string
	 */
	private $api_version = 'v1';

	/**
	 * Composed namespace within the base path.
	 *
	 * @var string
	 */
	private $api_namespace;

	/**
	 * Whether actions have been registered, to prevent duplicates.
	 *
	 * @var bool
	 */
	protected $actions_registered = false;

	/**
	 * REST_Base constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->api_namespace = $this->api_base_path . '/' . $this->api_version;
	}

	/**
	 * Get the API namespace for the REST class.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_api_namespace(): string {
		return $this->api_namespace;
	}

	/**
	 * Register the base actions for the REST endpoints.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_actions(): void {
		// Bail if the actions were already registered, to prevent duplicates.
		if ( $this->are_actions_registered() ) {
			return;
		}

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );

		$this->flag_actions_registered();
	}

	/**
	 * Register the controller's routes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	abstract public function register_routes(): void;

	/**
	 * Check whether actions have been registered.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function are_actions_registered(): bool {
		return $this->actions_registered;
	}

	/**
	 * Flag that actions have been registered.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function flag_actions_registered(): void {
		$this->actions_registered = true;
	}

	/**
	 * Public endpoints: notices display to anonymous visitors by design —
	 * the frontend lazy-fetches them over REST on every page view.
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	public function get_public_permissions(): bool {
		return true;
	}

	/**
	 * Endpoints that act on behalf of a user account, such as persisting a
	 * dismissal to user options. Anonymous visitors persist theirs in the
	 * dismissed_notices cookie and never hit these routes.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function get_logged_in_permissions(): bool {
		return is_user_logged_in();
	}

	/**
	 * Endpoints that read or write the plugin's settings.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function get_manage_settings_permissions(): bool {
		return current_user_can( 'manage_options' );
	}
}
