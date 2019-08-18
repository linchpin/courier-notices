<?php
/**
 * GravityForms Controller.
 *
 * @package Courier\Controller
 */

namespace Courier\Controller;

/**
 * Class GravityForms
 */
class GravityForms {

	/**
	 * These variables are used to convenient "wrap" child form settings in the appropriate HTML.
	 */

	/**
	 * Sub-setting open
	 *
	 * @var string
	 */
	private $subsetting_open = '<td colspan="2" class="gf_sub_settings_cell"><div class="gf_animate_sub_settings"><table style="width:100%"><tr>';

	/**
	 * Sub-setting close
	 *
	 * @var string
	 */
	private $subsetting_close = '</tr></table></div></td>';

	/**
	 * GravityForms constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ), 999 );

		add_filter( 'gform_confirmation_ui_settings', array( $this, 'gform_confirmation_ui_settings' ), 10, 2 );
		add_filter( 'gform_pre_confirmation_save', array( $this, 'gform_pre_confirmation_save' ) );
		add_filter( 'gform_pre_submission_filter', array( $this, 'gform_pre_submission_filter' ) );
		add_filter( 'gform_confirmation', array( $this, 'gform_confirmation' ), 10, 4 );
	}

	/**
	 * Add a Courier option to the confirmation area.
	 *
	 * @since 1.0
	 *
	 * @param array $ui_settings  The Settings page markup.
	 * @param array $confirmation Contains the confirmation details.
	 *
	 * @return mixed
	 */
	public function gform_confirmation_ui_settings( $ui_settings, $confirmation ) {

		$confirmation_type = rgar( $confirmation, 'type' ) ? rgar( $confirmation, 'type' ) : 'message';

		// Add a radio button option.
		ob_start(); ?>
		&nbsp;&nbsp;
		<input type="radio" id="form_confirmation_courier" name="form_confirmation" <?php checked( 'courier', $confirmation_type ); ?> value="courier" onclick="ToggleConfirmation();" />
		<label for="form_confirmation_courier" class="inline">
			<?php esc_html_e( 'Courier Notice', 'courier' ); ?>
		</label>
		<a href="#" onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_form_courier_notice" title="<h6>Courier Notice</h6>Enter the text you would like the user to see after they have submitted the form."><i class="fa fa-question-circle"></i></a>
		</td>
		<?php
		$courier_radio = ob_get_clean();

		$ui_settings['confirmation_type'] = str_replace( '</td>', $courier_radio, $ui_settings['confirmation_type'] );

		// Add Courier notice setting container.
		ob_start();
		?>

		<tr id="form_confirmation_courier_container" <?php echo 'courier' !== $confirmation_type ? 'style="display:none;"' : ''; ?> >
			<?php echo $this->subsetting_open; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<th><?php esc_html_e( 'Courier Notice', 'courier' ); ?></th>
			<td>
				<span class="mt-form_confirmation_courier"></span>
				<?php
					wp_editor(
						rgar( $confirmation, 'courier_notice' ),
						'form_confirmation_courier_editor',
						array(
							'autop'        => false,
							'editor_class' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right',
						)
					);
				?>
			</td>
			<?php echo $this->subsetting_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</tr>

		<tr id="form_confirmation_courier_page_container" <?php echo 'courier' !== $confirmation_type ? 'style="display:none;"' : ''; ?>>
			<?php echo $this->subsetting_open; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<th><?php esc_html_e( 'Redirect Page', 'gravityforms' ); ?></th>
			<td>
				<?php
				wp_dropdown_pages(
					array(
						'name'             => 'form_courier_page',
						'selected'         => (int) rgar( $confirmation, 'courier_page_id' ),
						'show_option_none' => esc_html__( 'Select a page', 'courier' ),
					)
				);
				?>
			</td>
			<?php echo $this->subsetting_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</tr>

		<?php
		$ui_settings['confirmation_courier'] = ob_get_clean();

		foreach ( $ui_settings as $key => $setting ) {
			if ( false !== strpos( $setting, 'ToggleConfirmation()' ) ) {
				$ui_settings[ $key ] = str_replace( 'ToggleConfirmation()', 'courier.gforms.toggleConfirmation()', $setting );
			}
		}

		return $ui_settings;

	}

	/**
	 * Save a Courier Confirmation data.
	 *
	 * @since 1.0
	 *
	 * @param array $confirmation The confirmation details.
	 *
	 * @return mixed
	 */
	public function gform_pre_confirmation_save( $confirmation ) {
		$type = rgpost( 'form_confirmation' );
		$page = rgpost( 'form_courier_page' );

		if ( ! in_array( $type, array( 'message', 'page', 'redirect', 'courier' ), true ) ) {
			$type = 'message';
		}

		$confirmation['type'] = $type;

		if ( 'courier' === $confirmation['type'] ) {
			$confirmation['courier_notice'] = rgpost( 'form_confirmation_courier_editor' );

			if ( empty( $page ) ) {
				$confirmation['courier_page_id'] = '';
			} else {
				$confirmation['courier_page_id'] = intval( $page );
			}
		}

		return $confirmation;
	}

	/**
	 * Provide a custom function for changing confirmation type.
	 *
	 * This is copied from Gravity Forms. We need this because the options are hard coded, not allowing dynamic addition
	 * of confirmation types.
	 *
	 * @since 1.0
	 */
	public function admin_print_scripts() {
		$current_screen = get_current_screen();

		if ( 'toplevel_page_gf_edit_forms' !== $current_screen->base ) {
			return;
		}
		?>
		<script type="text/javascript">

			var courier = courier || {};
				courier.gforms = {};

			jQuery( function( $ ) {

				courier.gforms.toggleConfirmation = function() {
					var showElement = '',
						hideElement = '',
						isRedirect  = $("#form_confirmation_redirect").is(":checked"),
						isPage      = $("#form_confirmation_show_page").is(":checked"),
						isCourier   = $("#form_confirmation_courier").is(":checked");

					if (isRedirect) {
						showElement = ".form_confirmation_redirect_container";
						hideElement = "#form_confirmation_message_container, .form_confirmation_page_container, #form_confirmation_courier_container, #form_confirmation_courier_page_container";
						ClearConfirmationSettings(['text', 'page']);
					} else if (isPage) {
						showElement = ".form_confirmation_page_container";
						hideElement = "#form_confirmation_message_container, .form_confirmation_redirect_container, #form_confirmation_courier_container, #form_confirmation_courier_page_container";
						ClearConfirmationSettings(['text', 'redirect']);
					} else if (isCourier) {
						showElement = "#form_confirmation_courier_container, #form_confirmation_courier_page_container";
						hideElement = "#form_confirmation_message_container, .form_confirmation_redirect_container, .form_confirmation_page_container";
						ClearConfirmationSettings(['text', 'courier']);
					} else {
						showElement = "#form_confirmation_message_container";
						hideElement = ".form_confirmation_page_container, .form_confirmation_redirect_container, #form_confirmation_courier_container, #form_confirmation_courier_page_container";
						ClearConfirmationSettings(['page', 'redirect']);
					}

					ToggleQueryString();
					TogglePageQueryString();

					$(hideElement).hide();
					$(showElement).show();
				};

				setTimeout(function () {
					$('#form_confirmation_courier_container').detatch().insertAfter('#form_confirmation_redirect_container');

					courier.gforms.toggleConfirmation();
				}, 300);
			});
		</script>
		<?php
	}

	/**
	 * If a user is not logged in, change the confirmation type to a default message if the form uses Courier Notice.
	 *
	 * @since 1.0
	 *
	 * @param array $form The form being submitted.
	 *
	 * @return mixed
	 */
	public function gform_pre_submission_filter( $form ) {
		if ( 'courier' !== $form['confirmation']['type'] ) {
			return $form;
		}

		// If a user is not logged in, we can't store a notice to their account. Display the notice as a regular message type.
		if ( ! is_user_logged_in() || ! function_exists( 'courier_add_notice' ) ) {
			$form['confirmation']['message'] = $form['confirmation']['courier_notice'];
			$form['confirmation']['type']    = 'message';

			return $form;
		}

		// If a redirect is supplied make sure the page is valid.
		if ( ! empty( $form['confirmation']['courier_page_id'] ) ) {
			$page = get_post( (int) $form['confirmation']['courier_page_id'] );

			if ( empty( $page ) || is_wp_error( $page ) ) {
				$form['confirmation']['message'] = $form['confirmation']['courier_notice'];
				$form['confirmation']['type']    = 'message';

				return $form;
			}
		}

		return $form;
	}

	/**
	 * Filter form confirmations to work appropriately for Courier notices.
	 *
	 * @since 1.0
	 *
	 * @param string $confirmation Confirmation URL.
	 * @param array  $form         Form object.
	 * @param array  $entry        The Entry Object.
	 * @param bool   $is_ajax      If AJAX is being used.
	 *
	 * @return mixed
	 */
	public function gform_confirmation( $confirmation, $form, $entry, $is_ajax ) {

		if ( 'courier' !== $form['confirmation']['type'] ) {
			return $confirmation;
		}

		// Courier notice data.
		$notice = array(
			// translators: %s Form Title.
			'post_title'   => sprintf( __( '%s Submission' ), esc_html( $form['title'] ) ),
			'post_content' => $form['confirmation']['courier_notice'],
		);

		courier_add_notice( $notice, array( 'Feedback', 'Success' ) );

		if ( ! empty( $form['confirmation']['courier_page_id'] ) ) {
			$page = get_post( (int) $form['confirmation']['courier_page_id'] );

			if ( empty( $page ) || is_wp_error( $page ) ) {
				return $form['confirmation']['courier_notice'];
			}

			// Ajax submissions require a front end redirect. Server side can just redirect.
			if ( $is_ajax ) {
				return $this->get_js_redirect_confirmation( get_permalink( $form['confirmation']['courier_page_id'] ), $is_ajax );
			} else {
				return array(
					'redirect' => get_permalink( $form['confirmation']['courier_page_id'] ),
				);
			}
		}

		return '';
	}

	/**
	 * Perform a front end redirect on successful submission.
	 *
	 * @since 1.0
	 *
	 * @param string $url  The redirect URL.
	 * @param bool   $ajax If AJAX is being used.
	 *
	 * @return string
	 */
	public function get_js_redirect_confirmation( $url, $ajax ) {
		$confirmation = '<script type="text/javascript">' . apply_filters( 'gform_cdata_open', '' ) . ' function gformRedirect(){document.location.href="$url";}';

		if ( ! $ajax ) {
			$confirmation .= 'gformRedirect();';
		}

		$confirmation .= apply_filters( 'gform_cdata_close', '' ) . '</script>';

		return $confirmation;
	}
}
