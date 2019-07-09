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
			'cb'                => '<input type="checkbox" />', // to display the checkbox.
			'title'             => esc_html__( 'Type', 'courier' ),
			'notice_icon'       => esc_html__( 'Icon', 'courier' ),
			'notice_color'      => esc_html__( 'Notice Color', 'courier' ),
			'notice_text_color' => esc_html__( 'Notice Text Color', 'courier' ),
		);
		return $table_columns;
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item        Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'cb':
			case 'title':
			case 'notice_icon':
			case 'notice_text_color':
			case 'notice_color':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ;
		}
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
		// $this->_column_headers = $this->get_column_info();
		$term_table_data = $this->fetch_table_data();

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data     = $this->fetch_table_data();

		usort( $data, array( $this, 'sort_data' ) );

		$items_per_page = 100;
		$current_page   = $this->get_pagenum();
		$total_items    = count( $data );
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $items_per_page,
			)
		);
		$data                  = array_slice( $data, ( ( $current_page - 1 ) * $items_per_page ), $items_per_page );
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}


	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}
	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns() {
		return array( 'title' => array( 'title', false ) );
	}


	/**
	 * Get value for checkbox column.
	 *
	 * @param object $item A row's data.
	 *
	 * @return string Text to be placed inside the column <td>.
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="courier_type_' . $item[ 'ID' ] . '">' . sprintf( esc_html__( 'Select %s' ), $item[ 'title' ] ) . '</label>'
			. "<input type='checkbox' name='courier_types[]' id='courier_type_{$item['ID']}' value='{$item['ID']}' />"
		);
	}

	/**
	 * Get the data for our table
	 * @return array
	 */
	public function fetch_table_data() {

		$types = get_terms(
			array(
				'hide_empty' => false,
				'taxonomy'   => 'courier_type',
			)
		);

		$data = [];

		if ( ! empty( $types ) ) {
			foreach ( $types as $type ) {

				$color = get_term_meta( $type->term_id, '_courier_type_color', true );

				if ( empty( $color ) ) {
					$color = '';
				}

				$icon = get_term_meta( $type->term_id, '_courier_type_icon', true );

				if ( ! empty( $icon ) ) {
					$icon = sprintf( '<img src="%1$s" alt="%2$s" class="courier-type-icon" />', esc_url( $icon ), esc_attr( $type->slug ) );
				} else {
					$icon = '';
				}

				$data[] = array(
					'cb'           => '<input type="checkbox" />',
					'ID'           => $type->term_id,
					'notice_icon'  => $icon,
					'notice_color' => sprintf( '<input type="text" value="%1$s" class="courier-type-color">', esc_attr( $color ) ),
					'title'        => $type->name,
				);
			}
		}

		// return result array to prepare_items.
		return $data;
	}
}
