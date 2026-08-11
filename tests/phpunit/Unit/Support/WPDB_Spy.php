<?php
/**
 * Recording stand-in for $wpdb.
 *
 * @package CourierNotices\Tests
 */

namespace CourierNotices\Tests\Unit\Support;

/**
 * Records every prepare()/query()/esc_like() call so tests can assert that
 * SQL went through preparation and which patterns were targeted.
 */
final class WPDB_Spy {

	/**
	 * Options table name, matching the real property.
	 *
	 * @var string
	 */
	public $options = 'wp_options';

	/**
	 * Raw SQL strings received by query().
	 *
	 * @var string[]
	 */
	public $queries = array();

	/**
	 * [query, args] pairs received by prepare().
	 *
	 * @var array[]
	 */
	public $prepared = array();

	/**
	 * Strings passed to esc_like().
	 *
	 * @var string[]
	 */
	public $esc_liked = array();

	/**
	 * Interpolate placeholders the way tests need — one %s at a time.
	 *
	 * @param string $query SQL with placeholders.
	 * @param mixed  ...$args Placeholder values.
	 *
	 * @return string
	 */
	public function prepare( $query, ...$args ) {
		$this->prepared[] = array( $query, $args );

		foreach ( $args as $arg ) {
			$position = strpos( $query, '%s' );

			if ( false !== $position ) {
				$query = substr_replace( $query, "'" . $arg . "'", $position, 2 );
			}
		}

		return $query;
	}

	/**
	 * Record the executed SQL.
	 *
	 * @param string $sql SQL statement.
	 *
	 * @return int
	 */
	public function query( $sql ) {
		$this->queries[] = $sql;

		return 0;
	}

	/**
	 * Mirror core's escaping closely enough for pattern assertions.
	 *
	 * @param string $text String to escape for LIKE.
	 *
	 * @return string
	 */
	public function esc_like( $text ) {
		$this->esc_liked[] = $text;

		return addcslashes( $text, '_%\\' );
	}
}
