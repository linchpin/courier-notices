<?php
/**
 * Tests for the REST base class.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Controller\REST;

require_once dirname( __DIR__, 2 ) . '/Support/wp-function-shadows.php';

use CourierNotices\Controller\REST\REST_Base;
use CourierNotices\Tests\Unit\Support\WP_Shadow_State;
use PHPUnit\Framework\TestCase;

/**
 * Class RESTBaseTest
 */
final class RESTBaseTest extends TestCase {

	/**
	 * Reset shadow-backed state.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		WP_Shadow_State::reset();
	}

	/**
	 * A minimal concrete controller for exercising the base.
	 *
	 * @return REST_Base
	 */
	private function make_controller(): REST_Base {
		return new class() extends REST_Base {
			/**
			 * No routes needed for the base tests.
			 *
			 * @return void
			 */
			public function register_routes(): void {
			}
		};
	}

	/**
	 * The namespace is composed in one place: base path plus version.
	 *
	 * @return void
	 */
	public function test_api_namespace_composes_base_and_version(): void {
		$this->assertSame( 'courier-notices/v1', $this->make_controller()->get_api_namespace() );
	}

	/**
	 * register_actions() hooks rest_api_init exactly once — calling it
	 * again must not double-register the routes.
	 *
	 * @return void
	 */
	public function test_register_actions_is_idempotent(): void {
		$controller = $this->make_controller();

		$this->assertFalse( $controller->are_actions_registered() );

		$controller->register_actions();
		$controller->register_actions();

		$hooks = array_column( $GLOBALS['courier_notices_test_added_actions'], 0 );

		$this->assertSame( array( 'rest_api_init' ), $hooks, 'A second register_actions() call must be a no-op.' );
		$this->assertTrue( $controller->are_actions_registered() );
	}

	/**
	 * The named permission callbacks express their intent.
	 *
	 * @return void
	 */
	public function test_permission_callbacks(): void {
		$controller = $this->make_controller();

		$this->assertTrue( $controller->get_public_permissions(), 'Notices display to anonymous visitors by design.' );

		$this->assertFalse( $controller->get_logged_in_permissions() );
		$GLOBALS['courier_notices_test_logged_in'] = true;
		$this->assertTrue( $controller->get_logged_in_permissions() );

		$this->assertFalse( $controller->get_manage_settings_permissions() );
		$GLOBALS['courier_notices_test_caps'] = array( 'manage_options' );
		$this->assertTrue( $controller->get_manage_settings_permissions() );
	}
}
