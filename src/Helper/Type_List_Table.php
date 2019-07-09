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
			case 'notice_color':
			case 'notice_text_color':
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

		// check if a search was performed.
		$term_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		// check and process any actions such as bulk actions.
		$this->handle_table_actions();

		//used by WordPress to build and fetch the _column_headers property
		// $this->_column_headers = $this->get_column_info();
		$term_table_data = $this->fetch_table_data();

		if( $term_search_key ) {
			$term_table_data = $this->filter_table_data( $term_table_data, $term_search_key );
		}

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
			'<label class="screen-reader-text" for="courier_type_%2$s">%3$s</label><input type="checkbox" name="courier_types[]" id="courier_type_%2$s" value="%1$s" />',
			esc_attr( (int) $item['ID'] ),
			esc_attr( $item['slug'] ),
			// translators: %1$s Title of the term
			sprintf( esc_html__( 'Select %1$s', 'courier' ), $item['title'] )
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
					$color = '#cccccc';
				}

				$color_input = sprintf(
					'<label class="screen-reader-text" for="courier_type_%2$s_color">%3$s</label><input type="text" name="courier_type_%2$s_color" id="courier_type_%2$s_color" class="courier-type-color" value="%1$s" />',
					esc_attr( $color ),
					esc_attr( $type->slug ),
					// translators: %1$s Title of the term
					sprintf( esc_html__( '%1$s Color', 'courier' ), $type->name )
				);

				$icon = get_term_meta( $type->term_id, '_courier_type_icon', true );

				if ( ! empty( $icon ) ) {
					$icon = sprintf( '<img src="%1$s" alt="%2$s" class="courier-type-icon" />', esc_url( $icon ), esc_attr( $type->slug ) );
				} else {
					$icon = '';
				}

				$text_color = get_term_meta( $type->term_id, '_courier_type_text_color', true );

				if ( empty( $text_color ) ) {
					$text_color = '#ffffff';
				}

				$text_input = sprintf(
					'<label class="screen-reader-text" for="courier_type_%2$s_text_color">%3$s</label><input type="text" name="courier_type_%2$s_text_color" id="courier_type_%2$s_text_color" class="courier-type-color" value="%1$s" />',
					esc_attr( $text_color ),
					esc_attr( $type->slug ),
					// translators: %1$s Title of the term
					sprintf( esc_html__( '%1$s Text Color', 'courier' ), $type->name )
				);

				$edit_link = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_attr( get_edit_term_link( $type->term_id, 'courier_type' ) ),
					esc_html( $type->name )
				);

				$data[] = array(
					'cb'                => '<input type="checkbox" />',
					'ID'                => $type->term_id,
					'notice_icon'       => $icon,
					'notice_color'      => $color_input,
					'notice_text_color' => $text_input,
					'title'             => $edit_link,
				);
			}
		}

		// return result array to prepare_items.
		return $data;
	}

	/**
	 * Filter the terms based on searching the table
	 * @param $table_data
	 * @param $search_key
	 *
	 * @return array
	 */
	public function filter_table_data( $table_data, $search_key ) {
		$filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
			foreach( $row as $row_val ) {
				if( stripos( $row_val, $search_key ) !== false ) {
					return true;
				}
			}
		} ) );
		return $filtered_table_data;
	}

	public function handle_table_actions() {
		/**
		 * Add any bulk actions. Maybe bulk delete?
		 */
	}
}
