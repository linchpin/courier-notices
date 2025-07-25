<?php
/**
 * Database functionality
 *
 * @package CourierNotices\Core
 */

namespace CourierNotices\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Database base class
 *
 * @since 1.0
 */
abstract class Database {

	/**
	 * The name of the database table
	 *
	 * @var string
	 */
	public $table_name;

	/**
	 * The version of the database table
	 *
	 * @var string
	 */
	public $version;

	/**
	 * The name of the primary column
	 *
	 * @var string
	 */
	public $primary_key;


	/**
	 * Get things started
	 *
	 * @since 1.0
	 */
	public function __construct() {
	}


	/**
	 * Returns whitelist of columns
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_columns() {
		return array();
	}


	/**
	 * Returns the default column values
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array();
	}


	/**
	 * Retrieves a row by the primary key
	 *
	 * @since 1.0
	 *
	 * @param string $row_id The row ID.
	 *
	 * @return object
	 */
	public function get( $row_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}


	/**
	 * Retrieve a row by a specific column / value
	 *
	 * @since   1.0
	 *
	 * @param string $column The column.
	 * @param int    $row_id The ID of the row.
	 *
	 * @return  object
	 */
	public function get_by( $column, $row_id ) {
		global $wpdb;

		$column = esc_sql( $column );

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id ) );
	}


	/**
	 * Retrieves a specific column's value by the primary key
	 *
	 * @since 1.0
	 *
	 * @param string $column The column.
	 * @param int    $row_id The ID of the row.
	 *
	 * @return  string
	 */
	public function get_column( $column, $row_id ) {
		global $wpdb;

		$column = esc_sql( $column );

		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}


	/**
	 * Retrieves a specific column's value by the the specified column / value
	 *
	 * @since 1.0
	 *
	 * @param string $column       The column.
	 * @param string $column_where The WHERE part of the query.
	 * @param string $column_value The column value.
	 *
	 * @return  string
	 */
	public function get_column_by( $column, $column_where, $column_value ) {
		global $wpdb;

		$column_where = esc_sql( $column_where );
		$column       = esc_sql( $column );

		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
	}


	/**
	 * Inserts a new row.
	 *
	 * @since 1.0
	 *
	 * @param array  $data  Array of data.
	 * @param string $type Type of data to insert.
	 *
	 * @return int
	 */
	public function insert( $data, $type = '' ) {
		global $wpdb;

		// Set default values.
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		do_action( 'courier_notices_db_before_insert_' . $type, $data );

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->table_name, $data, $column_formats ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		do_action( 'courier_notices_db_after_insert_' . $type, $wpdb->insert_id, $data );

		return $wpdb->insert_id;
	}


	/**
	 * Updates a row in the database.
	 *
	 * @since 1.0
	 *
	 * @param int    $row_id The ID of the row.
	 * @param array  $data   Array of data.
	 * @param string $where  The WHERE part of the query.
	 *
	 * @return bool
	 */
	public function update( $row_id, $data = array(), $where = '' ) {
		global $wpdb;

		// Row ID must be positive integer,
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			$where = $this->primary_key;
		}

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		if ( false === $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Deletes a row identified by the primary key
	 *
	 * @since 1.0
	 *
	 * @param int $row_id The ID of the row to delete.
	 *
	 * @return  bool
	 */
	public function delete( $row_id = 0 ) {
		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Check if the given table exists
	 *
	 * @since  1.0
	 *
	 * @param string $table The table name.
	 *
	 * @return bool If the table name exists
	 */
	public function table_exists( $table = '' ) {
		global $wpdb;

		$table = ! empty( $table ) ? sanitize_text_field( $table ) : $this->table_name;

		return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;
	}
}
