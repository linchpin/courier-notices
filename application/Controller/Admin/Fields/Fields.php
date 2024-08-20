<?php
/**
 * Fields Class
 *
 * @package CourierNotices\Controller\Admin\Fields
 */

namespace CourierNotices\Controller\Admin\Fields;

use CourierNotices\Core\View;
use CourierNotices\Helper\Utils;
use CourierNotices\Helper\Type_List_Table as Type_List_Table;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Control all of our plugin Settings
 *
 * @since      1.0
 * @package    CourierNotices
 * @subpackage Fields
 */

/**
 * Fields Class
 */
class Fields {

	/**
	 * Instance of Type_List_Table
	 *
	 * @var Type_List_Table
	 */
	private static $type_list_table;

	/**
	 * FIELD CONTROLS
	 *
	 * Below you will find all field controls.
	 */


	/**
	 * Build out our settings fields as needed
	 *
	 * Echos our field html
	 *
	 * @since 1.0
	 *
	 * @param object $args An Object of our field customizations.
	 */
	public static function add_textfield( $args ) {
		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'type'        => 'text',
			'class'       => '',
			'description' => '',
			'label'       => '',
			'name'        => $args['section'] . '[' . $args['field'] . ']',
			'id'          => $args['section'] . '_' . $args['field'],
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args        = wp_parse_args( $args, $defaults );
		$options     = get_option( $args['section'] );
		$field_value = ! empty( $options[ $args['field'] ] ) ? $options[ $args['field'] ] : '';
		?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>" class="screen-reader-text"><?php echo esc_html( $args['label'] ); ?></label>
		<input type="<?php echo esc_attr( $args['type'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo esc_attr( $field_value ); ?>">
		<?php

	}


	/**
	 * Used any time we need to add in a select field
	 *
	 * @since 1.0
	 *
	 * @param array $args Args for Customization.
	 */
	public static function add_select( $args ) {
		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'   => '',
			'options' => array(),
			'name'    => $args['section'] . '[' . $args['field'] . ']',
			'id'      => $args['section'] . '_' . $args['field'],
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		$options = get_option( $args['section'] );

		// @since 1.0
		// @todo this needs to be cleaned up to meet wpcs
		$select_options = ( ! empty( $args['options_cb'] ) && is_callable( $args['options_cb'] ) )
			? call_user_func_array( $args['options_cb'], array_values( $args ) )
			: $args['options'];

		?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
		<select id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>">
			<?php foreach ( $select_options as $option ) : ?>
				<?php

				$selected = '';

				if ( ! empty( $options[ $args['field'] ] ) ) {
					$selected = selected( $options[ $args['field'] ], $option['value'], false );
				}

				if ( empty( $options[ $args['field'] ] ) && ( isset( $args['default'] ) && '' !== $args['default'] ) ) {
					$selected = selected( $option['value'], $args['default'], false );
				}
				?>
				<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php echo $selected; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $option['label'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php

	}


	/**
	 * Create a checkbox field
	 *
	 * @since 1.0
	 *
	 * @param array $args Customizations.
	 */
	public static function add_checkbox( $args ) {
		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'       => '',
			'description' => '',
			'label'       => '',
			'options'     => '',
			'name'        => $args['section'] . '[' . $args['field'] . ']',
			'id'          => $args['section'] . '_' . $args['field'],
			'value'       => '1',
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		// @since 1.3.0
		// @todo this needs to be cleaned up to meet wpcs
		$select_options = ( ! empty( $args['options_cb'] ) && is_callable( $args['options_cb'] ) )
			? call_user_func_array( $args['options_cb'], array_values( $args ) )
			: $args['options'];

		$options = get_option( $args['options'] );

		if ( is_string( $select_options ) ) {

			$checked = false;

			if ( ! empty( $options[ $args['field'] ] ) && 'false' !== $options[ $args['field'] ] ) {
				$checked = true;
			}

			$checkbox = new View();
			$checkbox->assign( 'checked', $checked );
			$checkbox->assign( 'args', $args );

			$checkbox->render( 'admin/fields/field-checkbox' );
		} else {
			?>
			<?php if ( ! empty( $args['description'] ) ) : ?>
				<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
			<?php endif; ?>
			<?php foreach ( $select_options as $option ) : ?>
				<div class="courier-notices-settings-option">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $option['label'] ); ?></label>
					<?php
					$checked = '';

					if ( ! is_array( $options[ $args['field'] ] ) ) {
						$options[ $args['field'] ] = explode( ',', $options[ $args['field'] ] );
					}

					if ( ! empty( $options[ $args['field'] ] ) ) {
						$checked = checked( in_array( $option['value'], $options[ $args['field'] ], true ), true, false );
					}

					if ( empty( $options[ $args['field'] ] ) && ( isset( $args['default'] ) && '' !== $args['default'] ) ) {
						$checked = checked( in_array( $args['default'], $option['value'], true ), true, false );
					}
					?>
					<input type="checkbox"
						   id="<?php echo esc_attr( $args['id'] ); ?>"
						   name="<?php echo esc_attr( $args['name'] . '[]' ); ?>"
						   value="<?php echo esc_attr( $option['value'] ); ?>"
						<?php echo $checked; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					/>
				</div>
			<?php endforeach; ?>
			<?php
		}

	}


	/**
	 * Add a custom table to display options
	 *
	 * @since 1.0
	 *
	 * @param array $args Array of arguments.
	 */
	public static function add_table( $args ) {
		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'   => '',
			'options' => array(),
			'name'    => $args['section'] . '[' . $args['field'] . ']',
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		self::$type_list_table = new Type_List_Table( array( 'screen' => 'courier_notice_page_courier' ) );
		self::$type_list_table->prepare_items();

		$page = ( isset( $_REQUEST['page'] ) && '' !== $_REQUEST['page'] ) ? $_REQUEST['page'] : ''; // phpcs:ignore WordPress.Security.NonceVerification

		// Create Table View
		$table = new View();
		$table->assign( 'description', esc_html( $args['description'] ) );
		$table->assign( 'type_list_table', self::$type_list_table );
		$table->assign( 'page', $page );
		$table->render( 'admin/fields/field-table' );

	}


}
