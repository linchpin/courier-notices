<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Control the metabox displayed on the Courier_Notice post type edit screen.
 *
 * @since 1.1.0
 *
 * @package CourierNotices\Controller\Admin
 */

namespace CourierNotices\Controller\Admin;

use CourierNotices\Core\View;

/**
 * Class Courier_Notice_Metabox
 * @package CourierNotices\Controller\Admin
 */
class Courier_Notice_Metabox {

	/**
	 * Register the hooks and filters
	 *
	 * @since 1.1.0
	 */
	public function register_actions() {
		add_action( 'add_meta_boxes_courier_notice', array( $this, 'add_meta_boxes' ), 99 );
	}

	/**
	 * Add an option for selecting notice type
	 *
	 * @since 1.0
	 */
	public function add_meta_boxes() {
		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );

		add_meta_box( 'courier_meta_box', esc_html__( 'Notice Information', 'courier-notices' ), array( $this, 'meta_box' ), 'courier_notice', 'side', 'default' );
	}

	/**
	 * Allow for notices to be customized
	 *
	 * Set expiration on a notice
	 * Assign a notice to a specific user in within WordPress
	 *
	 * @since 1.0
	 *
	 * @param object $post The post object.
	 */
	public function meta_box( $post ) {
		wp_nonce_field( 'courier_notices_expiration_nonce', 'courier_notices_expiration_noncename' );

		global $wp_local;

		?>
		<div id="courier-notice_style_container">
			<h4><?php esc_html_e( 'Style', 'courier-notices' ); ?></h4>
			<?php

			if ( has_term( '', 'courier_style' ) ) {
				$selected_courier_style = get_the_terms( $post->ID, 'courier_style' );
			}

			if ( ! empty( $selected_courier_style ) ) {
				$selected_courier_style = $selected_courier_style[0]->slug;
			} else {
				$selected_courier_style = 'informational';
			}

			// Create and display the dropdown menu.
			wp_dropdown_categories(
				array(
					'orderby'           => 'name',
					'taxonomy'          => 'courier_style',
					'value_field'       => 'slug',
					'name'              => 'courier_style',
					'class'             => 'widefat',
					'hide_empty'        => false,
					'required'          => true,
					'option_none_value' => apply_filters( 'courier_default_notice_style', 'informational' ),
					'selected'          => $selected_courier_style,
				)
			);
			?>
		</div>

		<div id="courier-notice_type_container">
			<h4><?php esc_html_e( 'Type', 'courier-notices' ); ?></h4>
			<?php

			if ( has_term( '', 'courier_type' ) ) {
				$selected_courier_type = get_the_terms( $post->ID, 'courier_type' );
			}

			if ( ! empty( $selected_courier_type ) ) {
				$selected_courier_type = $selected_courier_type[0]->slug;
			} else {
				$selected_courier_type = 'info';
			}

			// Create and display the dropdown menu.
			wp_dropdown_categories(
				array(
					'orderby'           => 'name',
					'taxonomy'          => 'courier_type',
					'value_field'       => 'slug',
					'name'              => 'courier_type',
					'class'             => 'widefat',
					'hide_empty'        => false,
					'required'          => true,
					'option_none_value' => apply_filters( 'courier_notices_default_notice_type', 'info' ),
					'selected'          => $selected_courier_type,
				)
			);
			?>
		</div>

		<div id="courier-notice_placement_container">
			<h4><?php esc_html_e( 'Placement', 'courier-notices' ); ?></h4>
			<?php

			if ( has_term( '', 'courier_placement' ) ) {
				$selected_courier_placement = wp_get_post_terms( $post->ID, 'courier_placement' );
			}

			if ( ! empty( $selected_courier_placement ) ) {
				$selected_courier_placement = $selected_courier_placement[0]->slug;
			} else {
				$selected_courier_placement = 'header';
			}

			$exclude_popup_modal = '';

			// Exclude modal from our placement type as it's now a "style" of notice. We still need it for ease of use
			if ( $term = term_exists( 'popup-modal', 'courier_placement' ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.Found
				$exclude_popup_modal = array( $term['term_id'] );
			}

			// Create and display the dropdown menu.
			wp_dropdown_categories(
				array(
					'orderby'           => 'name',
					'taxonomy'          => 'courier_placement',
					'value_field'       => 'slug',
					'name'              => 'courier_placement_display',
					'class'             => 'widefat',
					'hide_empty'        => false,
					'required'          => true,
					'option_none_value' => apply_filters( 'courier_notices_default_notice_placement', 'header' ),
					'selected'          => $selected_courier_placement,
					'exclude'           => $exclude_popup_modal,
				)
			);
			?>
		</div>
		<?php // Control the display through a hidden field and javascript ?>
		<input type="hidden" name="courier_placement" id="courier_placement" value="<?php echo esc_attr( $selected_courier_placement ); ?>" />
		<?php

		// Date Display.
		$current_date = (int) get_post_meta( $post->ID, '_courier_expiration', true );

		if ( ! empty( $current_date ) ) {
			$current_date = date( get_option( 'date_format' ) . ' h:i A', $current_date );
		} else {
			$current_date = '';
		}
		?>
		<div id="courier-notice_expiration_container">
			<h4><?php esc_html_e( 'Notice Expiration', 'courier-notices' ); ?></h4>
			<p class="description"><?php esc_html_e( 'The date and time this notice should expire.', 'courier-notices' ); ?></p>

			<fieldset id="courier-timestampdiv">
				<legend class="screen-reader-text"><?php esc_html_e( 'Expiration date and time', 'courier-notices' ); ?></legend>
				<div class="timestamp-wrap">
					<label for="courier_expire_date">
						<input type="text" class="widefat" autocomplete="off" id="courier_expire_date" name="courier_expire_date" value="<?php echo esc_attr( $current_date ); ?>">
					</label>
				</div>
			</fieldset>
		</div>
		<?php
	}

	/**
	 * Show select for selecting notice type
	 *
	 * @since 1.0
	 */
	public function post_submitbox_misc_actions() {
		global $post;

		wp_nonce_field( 'courier_notice_info_nonce', 'courier_notice_info_noncename' );
		?>
		<div class="misc-pub-section courier-dismissable">
			<span class="dashicons dashicons-no-alt wp-media-buttons-icon"></span>&nbsp;
			<label for="courier_dismissible"><?php esc_html_e( 'Dismissible Notice:', 'courier-notices' ); ?></label>&nbsp;
			<input type="checkbox" name="courier_dismissible" id="courier_dismissible" value="1" <?php checked( get_post_meta( $post->ID, '_courier_dismissible', true ) ); ?> />
			<a href="#" class="courier-info-icon courier-help" title="<?php esc_html_e( 'Allow this notice to be dismissed by users. If your notice is a Pop Over/Modal. We force the notice to be dismissible', 'courier-notices' ); ?>">?</a>
		</div>
		<?php

		$copy_shortcode_info = new View();
		$copy_shortcode_info->assign( 'type', 'shortcode-help' );
		$copy_shortcode_info->assign( 'courier_notifications', get_user_option( 'courier_notifications' ) );
		$copy_shortcode_info->assign( 'message', __( 'Copy this notice <strong>shortcode</strong> to display in your content or in a widget!', 'courier-notices' ) );
		$copy_shortcode_info->render( 'admin/notifications' );

		$copy_shortcode = new View();
		$copy_shortcode->assign( 'post_id', $post->ID );
		$copy_shortcode->render( 'admin/copy-shortcode' );
	}
}
