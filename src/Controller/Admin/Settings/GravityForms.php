<?php
/**
 * Control all of our plugin Settings
 *
 * @since   1.0
 * @package Courier\Controller\Admin\Settings
 */

namespace Courier\Controller\Admin\Settings;

// Make sure we don't expose any info if called directly.
use \Courier\Controller\Admin\Fields\Fields as Fields;
use \Courier\Controller\Admin\Settings\General as General;

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * GravityForms Class
 */
class GravityForms {

	/**
	 * Define our settings page
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public static $settings_page = 'courier';

	/**
	 * Give our plugin a name
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public static $plugin_name = COURIER_PLUGIN_NAME;

	/**
	 * Initialize our plugin settings.
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_filter( 'courier_settings_tabs', array( $this, 'add_gravityforms_tab' ), 10, 1 );

		add_action( 'admin_init', array( __CLASS__, 'setup_gravityforms_settings' ) );
	}

	/**
	 * Add Settings Tab for Gravity Forms Integration
	 *
	 * @since 1.0
	 *
	 * @param array $tabs The tabs to add.
	 *
	 * @return array $tabs Settings tabs
	 */
	public function add_gravityforms_tab( $tabs ) {
		$tabs['addons']['sub_tabs']['gravityforms'] = array(
			'label' => esc_html__( 'Gravity Forms Settings', 'courier' ),
		);

		return $tabs;
	}

	/**
	 * Setup all the fields associated with Forgot Password Shortcodes
	 * Emails and other notifications
	 *
	 * @since 1.0
	 */
	public static function setup_gravityforms_settings() {

		$tab_section = 'courier_gravityforms';

		register_setting( $tab_section, $tab_section );

		// Default Settings Section.
		add_settings_section(
			'courier_gravityforms_section',
			'',
			array( '\Courier\Controller\Admin\Settings\General', 'create_section' ),
			$tab_section
		);
	}

	/**
	 * Get forms
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public static function get_forms() {

		$forms = \GFAPI::get_forms();

		$_forms = wp_list_pluck( $forms, 'title', 'id' );

		$gforms = array(
			array(
				'value' => '',
				'label' => esc_html__( 'Select a Form', 'courier' ),
			),
		);

		foreach ( $_forms as $key => $form ) {
			$gforms[ $key ] = array(
				'label' => $form,
				'value' => $key,
			);
		}

		return $gforms;
	}
}
