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
 * @since      1.0.5
 * @package    CourierNotices
 * @subpackage Settings
 */

/**
 * Sections Class
 */
class Sections {

	public function __construct() {
	}

	/**
	 * Prints out all settings sections added to a particular settings page
	 *
	 * Part of the Settings API. Use this in a settings page callback function
	 * to output all the sections and fields that were added to that $page with
	 * add_settings_section() and add_settings_field()
	 *
	 * @global $wp_settings_sections Storage array of all settings sections added to admin pages.
	 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections.
	 * @since 2.7.0
	 *
	 * @param string $page The slug name of the page whose settings sections you want to output.
	 */
	public function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
			if ( $section['title'] ) {
				echo "<h2>{$section['title']}</h2>\n";
			}

			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}

			if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				continue;
			}

			echo '<div class="form-table" role="presentation">';
			$this->do_settings_fields( $page, $section['id'] );
			echo '</ddiv>';
		}
	}

	/**
	 * Print out the settings fields for a particular settings section.
	 *
	 * Part of the Settings API. Use this in a settings page to output
	 * a specific section. Should normally be called by do_settings_sections()
	 * rather than directly.
	 *
	 * @global $wp_settings_fields Storage array of settings fields and their pages/sections.
	 *
	 * @since 2.7.0
	 *
	 * @param string $page Slug title of the admin page whose settings fields you want to show.
	 * @param string $section Slug title of the settings section whose fields you want to show.
	 */
	public function do_settings_fields( $page, $section ) {
		global $wp_settings_fields;

		if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}

			echo "<div{$class}>";

			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<div scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></div>';
			} else {
				echo '<div scope="row">' . $field['title'] . '</div>';
			}

			echo '<div>';
			call_user_func( $field['callback'], $field['args'] );
			echo '</div>';
			echo '</div>';
		}
	}
}
