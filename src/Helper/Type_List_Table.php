<?php
/**
 * Type List Table
 *
 * @package Courier\Helper
 */

namespace Courier\Helper;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use \Courier\Helper\WP_List_Table as WP_List_Table;

/**
 * Type_List_Table Class
 */
class Type_List_Table extends WP_List_Table {

	/**
	 * Returns columns.
	 *
	 * @since 1.0
	 *
	 * @return array|void
	 */
	public function get_columns() {
		$table_columns = array(
			'cb'                => '<input type="checkbox" />', // to display the checkbox.
			'notice_default'    => esc_html__( 'Default', 'courier' ),
			'title'             => esc_html__( 'Type', 'courier' ),
			'notice_icon'       => esc_html__( 'Icon', 'courier' ),
			'notice_color'      => esc_html__( 'Notice Color', 'courier' ),
			'notice_text_color' => esc_html__( 'Notice Text Color', 'courier' ),
			'notice_delete'     => '',
		);
		return $table_columns;
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @since 1.0
	 *
	 * @param array  $item         Data.
	 * @param string $column_name Current column name.
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'cb':
			case 'title':
			case 'notice_default':
			case 'notice_icon':
			case 'notice_color':
			case 'notice_delete':
			case 'notice_text_color':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; // phpcs:ignore
		}
	}

	/**
	 * Text for when no items are available.
	 *
	 * @since 1.0
	 */
	public function no_items() {
		esc_html_e( 'No types available.', 'courier' );
	}

	/**
	 * Prepare Items in the list.
	 *
	 * Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering.
	 *
	 * @since 1.0
	 */
	public function prepare_items() {
		// check if a search was performed.
		$term_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// check and process any actions such as bulk actions.
		$this->handle_table_actions();

		// Used by WordPress to build and fetch the _column_headers property.
		$term_table_data = $this->fetch_table_data();

		if ( $term_search_key ) {
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
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Define the sortable columns
	 *
	 * @since 1.0
	 *
	 * @return array
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
			// translators: %1$s Title of the term.
			sprintf( esc_html__( 'Select %1$s', 'courier' ), $item['title'] )
		);
	}

	/**
	 * Get the data for our table
	 *
	 * @since 1.0
	 *
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

		$default_types = array(
			'alert',
			'feedback',
			'info',
			'secondary',
			'success',
			'warning',
		);

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
					// translators: %1$s Title of the term.
					sprintf( esc_html__( '%1$s Color', 'courier' ), $type->name )
				);

				$icon = get_term_meta( $type->term_id, '_courier_type_icon', true );

				if ( ! empty( $icon ) ) {
					$icon = sprintf( '<label class="screen-reader-text" for="courier_type_%2$s_icon">%1$s</label><span alt="%1$s" class="courier-type-icon icon-%2$s"></span><input type="text" name="courier_type_%2$s_icon" id="courier_type_%2$s_icon" class="courier-type-icon" value="%1$s" />',
						esc_attr( $icon ),
						esc_attr( $type->slug )
					);
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
					// translators: %1$s Title of the term.
					sprintf( esc_html__( '%1$s Text Color', 'courier' ), $type->name )
				);

				if ( in_array( $type->slug, $default_types ) ) {
					$is_default_item = '<span class="dashicons dashicons-shield-alt"></span>';
				}

				$data[] = array(
					'cb'                => '<input type="checkbox" />',
					'notice_default'    => $is_default_item,
					'slug'              => $type->slug,
					'ID'                => $type->term_id,
					'notice_icon'       => $icon,
					'notice_color'      => $color_input,
					'notice_text_color' => $text_input,
					'title'             => $type->name, // Custom Callback.
					'notice_delete'     => '' // Custom Callback. $this->notice_delete( $type->term_id ),
				);
			}
		}

		// Return result array to prepare_items.
		return $data;
	}

	/**
	 * Filter the terms based on searching the table
	 *
	 * @since 1.0
	 *
	 * @param array  $table_data The table data.
	 * @param string $search_key The search key.
	 *
	 * @return array
	 */
	public function filter_table_data( $table_data, $search_key ) {
		$filtered_table_data = array_values(
			array_filter(
				$table_data,
				function ( $row ) use ( $search_key ) {
					foreach ( $row as $row_val ) {
						if ( stripos( $row_val, $search_key ) !== false ) {
							return true;
						}
					}
				}
			)
		);

		return $filtered_table_data;
	}

	/**
	 * Handles bulk actions.
	 *
	 * @since 1.0
	 */
	public function handle_table_actions() {
		/**
		 * Add any bulk actions. Maybe bulk delete?
		 */
	}

	/**
	 * Method for rendering the user_login column.
	 *
	 * Adds row action links to the user_login column.
	 * e.g. url/users.php?page=nds-wp-list-table-demo&action=view_usermeta&user=18&_wpnonce=1984253e5e
	 *
	 * @since 1.0
	 *
	 * @param array $item The current item.
	 *
	 * @return string
	 */
	protected function column_title( $item ) {
		$edit_link = sprintf(
			'<span class="dashicons dashicons-edit"></span><a href="%1$s">%2$s</a></strong>',
			esc_attr( get_edit_term_link( $item['ID'], 'courier_type' ) ),
			esc_html( $item['title'] )
		);

		$actions = [];

		return $edit_link . $this->row_actions( $actions );
	}

	protected function column_notice_delete( $item ) {
		$edit_link = sprintf(
			'<a class="courier-notices-type-delete" href="#" data-term-id="%1$s"><span class="dashicons dashicons-trash"></span></a></strong>',
			esc_attr( $item['ID'] )
		);

		return $edit_link;
	}

	/**
	 * Show is a column is a default title.
	 *
	 * @param $item
	 *
	 * @return string
	 */
	protected function column_default_item( $item ) {

		$is_default_item = '';
		$default_types = array(
			'alert',
			'feedback',
			'info',
			'secondary',
			'success',
			'warning',
		);

		if ( in_array( $item->slug, $default_types ) ) {
			$is_default_item = '<span class="dashicons dashicons-shield-alt"></span>';
		}

		return $is_default_item;
	}

}
