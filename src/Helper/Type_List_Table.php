<?php

namespace Courier\Helper;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use \Courier\Helper\WP_List_Table as WP_List_Table;

/**
 * Class Type_List_Table
 * @package Courier\Controller\Admin\Fields
 */
class Type_List_Table extends WP_List_Table {
	/**
	 * @return array|void
	 */
	public function get_columns() {
		$table_columns = array(
			'cb'           => '<input type="checkbox" />', // to display the checkbox.
			'notice_icon'  => __( 'Icon', 'courier' ),
			'notice_color' => __( 'Color', 'courier' ),
		);
		return $table_columns;
	}

	/**
	 *
	 */
	public function no_items() {
		esc_html_e( 'No types available.', 'courier' );
	}

	/**
	 * Prepare Items in the list.
	 */

	//Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
	public function prepare_items() {

		// code to handle bulk actions

		//used by WordPress to build and fetch the _column_headers property
		$this->_column_headers = $this->get_column_info();
		$table_data            = $this->fetch_table_data();

		// code to handle data operations like sorting and filtering

		// start by assigning your data to the items variable
		$this->items = $table_data;

		// code to handle pagination
	}

	public function fetch_table_data() {

		$types  = get_terms();



		// return result array to prepare_items.
		return $types;
	}
}
