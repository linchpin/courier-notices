<?php
/**
 * Tests for the deprecated back-compat wrappers in Helper/Deprecated.php.
 *
 * These are the functions client sites call; a regression here is silent
 * breakage on someone's production site. Each test proves the wrapper
 * passes its arguments through to the modern function, returns its result,
 * and reports itself via _deprecated_function() — pinning the COURIER-1077
 * change that made the wrappers deprecate loudly.
 *
 * Every test runs in its own process: the modern functions are replaced by
 * recording stubs, which could never coexist with the real
 * Helper/Functions.php loaded elsewhere in the suite.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Helper;

use CourierNotices\Tests\Unit\Support\Stub_Recorder;
use PHPUnit\Framework\TestCase;

/**
 * Class DeprecatedTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class DeprecatedTest extends TestCase {

	/**
	 * Wrappers that display rather than return.
	 *
	 * @var string[]
	 */
	private const VOID_WRAPPERS = array( 'courier_display_notices', 'courier_display_modals' );

	/**
	 * Load the recording stubs, then the wrappers under test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		require_once dirname( __DIR__ ) . '/Support/modern-function-stubs.php';

		$GLOBALS['courier_notices_test_deprecations'] = array();

		require_once dirname( __DIR__, 4 ) . '/includes/Helper/Deprecated.php';
	}

	/**
	 * One case per wrapper: wrapper name, modern target, arguments, and the
	 * sentinel the stub returns.
	 *
	 * @return array<string, array>
	 */
	public static function wrapper_provider(): array {
		return array(
			'courier_add_notice'                     => array( 'courier_add_notice', 'courier_notices_add_notice', array( 'Hello notice', array( 'Alert' ), true, false, 42 ), 123 ),
			'courier_get_user_notices'               => array( 'courier_get_user_notices', 'courier_notices_get_user_notices', array( array( 'number' => 5 ) ), array( 'user-notice' ) ),
			'courier_get_global_notices'             => array( 'courier_get_global_notices', 'courier_notices_get_global_notices', array( array( 'placement' => 'header' ) ), array( 'global-notice' ) ),
			'courier_get_dismissible_global_notices' => array( 'courier_get_dismissible_global_notices', 'courier_notices_get_dismissible_global_notices', array( array( 'number' => 2 ), true ), array( 4, 5 ) ),
			'courier_get_persistent_global_notices'  => array( 'courier_get_persistent_global_notices', 'courier_notices_get_persistent_global_notices', array( array( 'number' => 3 ) ), array( 6 ) ),
			'courier_get_notices'                    => array( 'courier_get_notices', 'courier_notices_get_notices', array( array( 'ids_only' => true ) ), array( 7, 8 ) ),
			'courier_display_notices'                => array( 'courier_display_notices', 'courier_notices_display_notices', array( array( 'placement' => 'header' ) ), null ),
			'courier_display_modals'                 => array( 'courier_display_modals', 'courier_notices_display_modals', array( array( 'style' => 'informational' ) ), null ),
			'courier_get_dismissed_notices'          => array( 'courier_get_dismissed_notices', 'courier_notices_get_dismissed_notices', array( 42 ), array( 9 ) ),
			'courier_get_global_dismissed_notices'   => array( 'courier_get_global_dismissed_notices', 'courier_notices_get_global_dismissed_notices', array( 42 ), array( 10 ) ),
			'courier_get_all_dismissed_notices'      => array( 'courier_get_all_dismissed_notices', 'courier_notices_get_all_dismissed_notices', array( 42 ), array( 11 ) ),
			'courier_dismiss_notices'                => array( 'courier_dismiss_notices', 'courier_notices_dismiss_notices', array( array( 5, 6 ), 42, true, false ), true ),
			'courier_get_css'                        => array( 'courier_get_css', 'courier_notices_get_css', array(), '.courier{}' ),
		);
	}

	/**
	 * Delegation, return passthrough, and the deprecation report.
	 *
	 * @dataProvider wrapper_provider
	 *
	 * @param string $wrapper  Deprecated function under test.
	 * @param string $modern   Its replacement.
	 * @param array  $args     Arguments to pass through.
	 * @param mixed  $sentinel Return value the stub is primed with.
	 *
	 * @return void
	 */
	public function test_wrapper_delegates_and_deprecates( string $wrapper, string $modern, array $args, $sentinel ): void {
		Stub_Recorder::$returns[ $modern ] = $sentinel;

		$result = $wrapper( ...$args );

		$this->assertSame(
			$args,
			Stub_Recorder::$calls[ $modern ] ?? null,
			"{$wrapper} must pass its arguments through to {$modern} unchanged."
		);

		if ( ! in_array( $wrapper, self::VOID_WRAPPERS, true ) ) {
			$this->assertSame( $sentinel, $result, "{$wrapper} must return {$modern}'s result." );
		}

		$deprecations = $GLOBALS['courier_notices_test_deprecations'];

		$this->assertCount( 1, $deprecations, "{$wrapper} must report exactly one deprecation." );
		$this->assertSame( $wrapper, $deprecations[0][0] );
		$this->assertSame( '1.2.0', $deprecations[0][1] );
		$this->assertSame( $modern . '()', $deprecations[0][2] );
	}
}
