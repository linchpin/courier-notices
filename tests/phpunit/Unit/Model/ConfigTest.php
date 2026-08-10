<?php
/**
 * Tests for the Config model.
 *
 * These pin the COURIER-1075 caching bug: the wp_cache_get() short-circuit
 * was commented out while wp_cache_set() stored a variable that was always
 * null — so every instantiation re-read the plugin file through
 * get_file_data(), many times per request, and the cache never held anything.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Model;

require_once dirname( __DIR__ ) . '/Support/wp-function-shadows.php';

use CourierNotices\Model\Config;
use CourierNotices\Tests\Unit\Support\WP_Shadow_State;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 */
final class ConfigTest extends TestCase {

	/**
	 * Reset shadow state and can the plugin-file headers get_file_data()
	 * "reads".
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		WP_Shadow_State::reset();

		$GLOBALS['courier_notices_test_file_headers'] = array(
			'Plugin Name' => 'Courier Notices',
			'Version'     => '1.9.18',
			'Text Domain' => 'courier-notices',
		);
	}

	/**
	 * Construction exposes the plugin headers and the derived paths.
	 *
	 * @return void
	 */
	public function test_config_exposes_plugin_headers_and_paths(): void {
		$config = new Config();

		$this->assertSame( 'Courier Notices', $config->get( 'plugin_name' ) );
		$this->assertSame( '1.9.18', $config->get( 'version' ) );
		$this->assertSame( 'courier-notices', $config->get( 'textdomain' ) );
		$this->assertSame( COURIER_NOTICES_PATH, $config->get( 'plugin_path' ) );
		$this->assertSame( 'courier-notices/courier-notices.php', $config->get( 'plugin_base_name' ) );
		$this->assertSame( '_courier_', $config->get( 'prefix' ) );
		$this->assertSame( 'CourierNotices', $config->get( 'namespace' ) );
	}

	/**
	 * Unknown properties read as false, matching the getter's contract.
	 *
	 * @return void
	 */
	public function test_get_returns_false_for_an_unknown_property(): void {
		$this->assertFalse( ( new Config() )->get( 'not_a_property' ) );
	}

	/**
	 * set() stores and chains; import() accepts arrays and objects and
	 * rejects scalars.
	 *
	 * @return void
	 */
	public function test_set_and_import_behaviour(): void {
		$config = new Config();

		$this->assertSame( $config, $config->set( 'custom', 'value' ) );
		$this->assertSame( 'value', $config->get( 'custom' ) );

		$this->assertSame( $config, $config->import( array( 'a' => 1 ) ) );
		$this->assertSame( 1, $config->get( 'a' ) );

		$this->assertSame( $config, $config->import( (object) array( 'b' => 2 ) ) );
		$this->assertSame( 2, $config->get( 'b' ) );

		$this->assertFalse( $config->import( 'scalar' ) );
	}

	/**
	 * The cache must hold the built configuration after first construction —
	 * not null, which is what the shipped code stored.
	 *
	 * @return void
	 */
	public function test_the_cache_holds_the_built_config(): void {
		new Config();

		$cached = $GLOBALS['courier_notices_test_cache']['courier-notices']['config'] ?? null;

		$this->assertIsArray( $cached, 'wp_cache_set() must store the built config, not null.' );
		$this->assertSame( 'Courier Notices', $cached['plugin_name'] );
	}

	/**
	 * The caching contract itself: the plugin file is read once, and every
	 * later instantiation answers from cache with the same properties.
	 *
	 * @return void
	 */
	public function test_a_second_instantiation_reads_from_cache_not_the_file(): void {
		new Config();

		$this->assertCount(
			1,
			$GLOBALS['courier_notices_test_file_reads'],
			'First construction reads the plugin file exactly once.'
		);

		$second = new Config();

		$this->assertCount(
			1,
			$GLOBALS['courier_notices_test_file_reads'],
			'A warm cache must satisfy later constructions without re-reading the plugin file.'
		);
		$this->assertSame( 'Courier Notices', $second->get( 'plugin_name' ), 'Cached values must populate the new instance.' );
		$this->assertSame( 'CourierNotices', $second->get( 'namespace' ) );
	}
}
