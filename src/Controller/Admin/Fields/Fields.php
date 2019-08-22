<?php
/**
 * Fields Class
 *
 * @package Courier\Controller\Admin\Fields
 */

namespace Courier\Controller\Admin\Fields;

// Make sure we don't expose any info if called directly.
use Courier\Core\View;
use Courier\Helper\Type_List_Table as Type_List_Table;

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Control all of our plugin Settings
 *
 * @since      1.0
 * @package    Courier
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
		$args    = wp_parse_args( $args, $defaults );
		$options = get_option( $args['section'] );
		?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>" class="screen-reader-text"><?php echo esc_html( $args['label'] ); ?></label>
		<input type="<?php echo esc_attr( $args['type'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo esc_attr( $options[ $args['field'] ] ); ?>">
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
			? call_user_func_array( $args['options_cb'], $args )
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
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['options'] ) ) { // If we don't have any option, die early.
			return;
		}

		$options = get_option( $args['options'] );
		$checked = false;

		if ( ! empty( $options[ $args['field'] ] ) ) {
			$checked = true;
		}
		?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
		<input type="checkbox" class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="1" <?php checked( $checked ); ?>>
		<?php
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
			'id'      => $args['section'] . '_' . $args['field'],
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		self::$type_list_table = new Type_List_Table();
		self::$type_list_table->prepare_items();

		?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>

		<div class="wrap">
			<div id="nds-post-body">
				<form id="nds-user-list-form" method="get">
					<?php if ( isset( $_REQUEST['page'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
						<input type="hidden" name="page" value="<?php echo esc_attr( (int) $_REQUEST['page'] ); // phpcs:ignore WordPress.Security.NonceVerification ?>" />
					<?php endif; ?>
					<div class="courier-row">
						<div class="courier-columns courier-columns-7">
							Add a new Courier Type
						</div>
						<div class="courier-columns courier-columns-5">
							<?php self::$type_list_table->search_box( esc_html__( 'Find', 'courier' ), 'courier-find-type' ); ?>
						</div>
					</div>
					<?php self::$type_list_table->display(); ?>
				</form>
			</div>
		</div>
		<?php

		$view = new View();
		$view->render( 'admin/js/courier-notice-type-row' );
	}
}
