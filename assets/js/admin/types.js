/**
 * Controls Notice Types admin area within settings
 *
 * @package    CourierNotices
 * @subpackage Types
 * @since      1.0
 */

import jQuery from 'jquery';

let $ = jQuery;

export default function types() {

	// Private Variables
	let $body = $('body'),
		$types = $('.courier_notice_page_courier'),
		$new_container = $('#courier-notice-type-new'),
		$courierTypeColor = $( '.courier-type-color' ),
		courierNoticeTypeTemplate = $('#courier-notice-type-template').text().split(/\{(.+?)\}/g),
		courierNoticeTypeEditTemplate = $('#courier-notice-type-edit-template').text().split(/\{(.+?)\}/g),
		inputTemplate = {
			indicator: $('#courier-notice-loader').html(),
			cancel: courier_notices_admin_data.strings.cancel,
			cancelcssclass: 'btn btn-danger',
			submitcssclass: 'btn btn-success',
			maxlength: 50,
			showfn: function (elem) {
				elem.fadeIn('fast')
			},
			submit: courier_notices_admin_data.strings.save,
			tooltip: courier_notices_admin_data.strings.editurl,
			width: '100%'
		},
		courierNoticeTypeCurrent = '';

	/**
	 * Initialize the module
	 *
	 * @since 1.0.5
	 */
	function init() {

		// Setup type edit screen js within settings.
		setupControls();
	}

	/**
	 * Create all the event listeners for the module
	 *
	 * @since 1.0
	 */
	function setupControls() {
		$body
			.on( 'click', '.courier-notices-type-delete', confirmDeleteCourierNoticeType )
			.on( 'click', '.courier-notices-type-cancel-delete', cancelDeleteCourierNoticeType )
			.on( 'click', '#add-courier-notice-type', addNewCourierNoticeTypeRow )
			.on( 'click', '#courier-notice-type-new .save-button', addCourierNoticeType )
			.on( 'click', '#courier-notice-type-new .close-button', cancelAddCourierNoticeType )
			.on( 'click', '.courier-notice-editing .close-button', cancelEditCourierNoticeType )
			.on( 'click', '.courier-notice-editing .save-button', updateCourierNoticeType )
			.on( 'click', '.courier-notice-type-edit', editCourierNoticeType )
			.on( 'click', '#courier-settings .settings-form #submit', disableTypeControls );
	}

	/**
	 * Due to the complexity of this input type, we need to disable all the hidden fields associated with it
	 * so they are not saved to the options table.
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function disableTypeControls( event ) {

		event.stopPropagation();

		$('table.courier_notice_page_courier').find('input,button').attr( 'disabled', 'disabled' );
		$('#nds-post-body').find('input[type="hidden"]').attr( 'disabled', 'disabled' );
		$('.settings-form').submit();
	}

	/**
	 * Edit a courier notice type
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function editCourierNoticeType( event ) {
		event.preventDefault();

		var $parentRow = $(this).closest('tr');

		$( 'table.courier_notice_page_courier .button-editing.close-button:visible' ).trigger('click');

		$parentRow.addClass('courier-notice-editing').find('.notice-options').show();
		$parentRow.find('.courier-notice-type-title').hide();

		setupTypeEditing( $parentRow );
	}

	/**
	 * Cancel editing an existing Courier Notice Type.
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function cancelEditCourierNoticeType(event) {
		event.preventDefault();

		var $target = $('#courier-notice-type-edit')
			.replaceWith( courierNoticeTypeCurrent );

		var $parentRow = $(this).closest('tr');

		$('.courier-icon', $parentRow).removeAttr('style');
		$('.courier-content-wrapper', $parentRow).removeAttr('style');

		$('.notice-options', $parentRow).hide();
		$('.courier-notice-type-title', $parentRow).show();
		$('.courier-notice-editing').removeClass('courier-notice-editing');

		$parentRow.find('.courier-type-color').each(function () {
			$(this).val( $(this).data('default-color') );
		} );

		$parentRow.find('.courier-type-color').trigger('change');
	}

	/**
	 * Display the edit template.
	 *
	 * @since 1.0
	 *
	 * @param template
	 * @param options
	 */
	function displayEditTemplate(template, options) {

		// If no template, die early
		if ( typeof ( template ) === 'undefined' ) {
			return;
		}

		options = (options) ? options : {};

		let input = $.extend(true, options, template);
		let $noticeRow = $(courierNoticeTypeEditTemplate.map(render(input)).join(''));
		$('.courier-notice-editing').replaceWith($($noticeRow));

		setupTypeEditing( $noticeRow );
	}

	/**
	 * Cancel adding a new Courier Notice Type.
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function cancelAddCourierNoticeType(event) {
		event.preventDefault();

		var $target = $('#courier-notice-type-new');

		$target.fadeOut('fast').promise().done(function () {
			$('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');

			$(this).remove();
		});
	}

	/**
	 * Add a new courier type.
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function addNewCourierNoticeTypeRow(event) {

		event.preventDefault();

		$(this).addClass('disabled').attr('disabled', 'disabled');

		// Only show the new row if it's not visible.
		// if ( ! $new_container.is(':visible') ) {

		var options = {};
		var input = $.extend(true, options, inputTemplate);

		displayNewCourierNoticeTypeTemplate(input);
				// }
	}

	/**
	 * Confirm delete of Courier Notice type term
	 *
	 * @todo there should be some sort of busy/loading indicator
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function confirmDeleteCourierNoticeType(event) {

		event.preventDefault();

		var $this = $(this),
			$cancel = $('<button />', {
				'class' : 'courier-notices-type-cancel-delete button button-secondary button-cancel button-editing close-button',
				'href'  : '#'
			}),
			$cancel_icon = $('<span />', {
				'class' : 'dashicons dashicons-no'
			});

		if (true !== $this.data('confirm')) {
			// $this.find('dashicons-trash').hide();
			$cancel.append( $cancel_icon );

			$this
				.addClass('button button-secondary button-editing')
				.attr('aria-label', courier_notices_admin_data.strings.confirm_delete)
				.data('confirm', true);

			$this.after( $cancel ).after('<span class="spacer">&nbsp;</span>');
		} else {
			$this.addClass('disabled').text(courier_notices_admin_data.strings.deleting);

			deleteCourierNoticeType($this);
		}

	}

	/**
	 * Delete the Courier Notice Type
	 *
	 * @since 1.0
	 */
	function deleteCourierNoticeType( $target ) {
		$.post(ajaxurl, {
			action: 'courier_notices_delete_type',
			courier_notices_delete_type: courier_notices_admin_data.delete_nonce,
			courier_notices_type: parseInt($target.data('term-id'))
		}).success(function () {
			$target.closest('tr').fadeOut('fast').promise().done(function () {
				$(this).remove(); // Remove the row from the table after it fades out.
			});
		});
	}

	/**
	 * Cancel deleting of the Courier Notice Type
	 *
	 * @since 1.0
	 */
	function cancelDeleteCourierNoticeType( event ) {
		event.preventDefault();

		var $this   = $(this),
			$spacer = $this.siblings('.spacer'),
			$trash  = $this.siblings('.courier-notices-type-delete');

		$this.remove();
		$spacer.remove();

		$trash
			.removeClass('button button-secondary button-editing')
			.removeAttr('aria-label')
			.data('confirm', false);
	}

	/**
	 * Setup Courier Type Editing Screen
	 *
	 * @since 1.0
	 */
	function setupTypeEditing( $target ) {

		// If we don't have a $target element target all color pickers.
		if ( ! $target ) {
			$courierTypeColor = $( '.courier-type-color' );
		} else {
			$courierTypeColor = $target.find( '.courier-type-color' );
		}

		if ( ! $courierTypeColor.length ) {
			return;
		}

		$courierTypeColor.wpColorPicker({
			change: function ( event, ui ) {
				let $target =  $(this).closest('.notice_preview'),
					notice_ui = $(this).closest('.notice-option').data('notice-option-color');

				setTimeout(function() {
					var color_value = event.target.value;

					switch ( notice_ui ) {
						case 'accent':
							$target.find( '.courier-icon' ).css( 'background', color_value );
							break;
						case 'icon':
							$target.find( '.courier-icon' ).css( 'color', color_value );
							break;
						case 'text':
							$target.find( '.courier-content-wrapper' ).css( 'color', color_value );
							break;
						case 'bg':
							$target.find( '.courier-content-wrapper' ).css( 'background', color_value );
							break;
					}
				},1 );
			}
		});
	}

	/**
	 * Render our template including our properties.
	 *
	 * This is a temp solution prior to move to react.
	 *
	 * @param props
	 * @returns {Function}
	 */
	function render(props) {
		return function (token, i) {
			return (i % 2) ? props[token] : token;
		};
	}

	/**
	 * Display the template for adding a new Notice Type.
	 * @param item
	 */
	function displayNewCourierNoticeTypeTemplate(item) {

		let $noticeRow = $( courierNoticeTypeTemplate.map( render(item)).join('') );
			$noticeRow = $( $noticeRow );

		$('table.courier_notice_page_courier tbody').append( $noticeRow );

		setupTypeEditing( $noticeRow );
	}

	/**
	 * Add / Save our new courier notice type
	 */
	function addCourierNoticeType(event) {

		event.preventDefault();

		$(this).addClass('disabled').attr('disabled', 'disabled');

		var $target = $('#courier-notice-type-new');

		$.post(ajaxurl, {
			action: 'courier_notices_add_type',
			'page': 'courier',
			'courier_notices_add_type': courier_notices_admin_data.add_nonce,
			'courier_notice_type_new_title': $('#courier-notice-type-new-title').val(),
			'courier_notice_type_new_css_class': $('#courier-notice-type-new-css-class').val(),
			'courier_notice_type_new_color': $('#courier-notice-type-new-color').val(),
			'courier_notice_type_new_text_color': $('#courier-notice-type-new-text-color').val(),
			'courier_notice_type_new_icon_color': $('#courier-notice-type-new-icon-color').val(),
			'courier_notice_type_new_bg_color': $('#courier-notice-type-new-bg-color').val(),
			contentType: "application/json"
		}).success(function (response) {

			response = JSON.parse(response);

			if (response && response.fragments) {
				$target.fadeOut('fast').promise().done(function () {
					$('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');

					for (var fragment in response.fragments) {
						$(fragment).html(response.fragments[fragment]);
					}
				});
			}
		});
	}

	/**
	 * Update courier notice type
	 *
	 * @since 1.0
	 */
	function updateCourierNoticeType( event ) {

		event.preventDefault();

		let $this   = $(this),
			$target = $this.closest('tr');

		$this.addClass( 'disabled' ).attr( 'disabled', 'disabled' );

		$.post(ajaxurl, {
			action: 'courier_notices_update_type',
			'page': 'courier',
			'courier_notices_update_type':         courier_notices_admin_data.update_nonce,
			'courier_notice_type_edit_title':      $target.find( '.courier-notice-type-edit-title' ).val(),
			'courier_notice_type_edit_css_class':  $target.find( '.courier-notice-type-edit-css-class' ).val(),
			'courier_notice_type_edit_color':      $target.find( '.courier-notice-type-edit-color' ).val(),
			'courier_notice_type_edit_text_color': $target.find( '.courier-notice-type-edit-text-color' ).val(),
			'courier_notice_type_edit_icon_color': $target.find( '.courier-notice-type-edit-icon-color' ).val(),
			'courier_notice_type_edit_bg_color':   $target.find( '.courier-notice-type-edit-bg-color' ).val(),
			'courier_notice_type_id':              parseInt( $target.find( '[data-courier-notice-id]' ).data( 'courier-notice-id' ) ),
			'contentType': "application/json"
		} ).success( function ( response ) {
			response = JSON.parse( response );

			if ( response && response.fragments ) {
				$target.fadeOut( 'fast' ).promise().done(
					function() {
						for ( let fragment in response.fragments ) {
							$( fragment ).html( response.fragments[ fragment ] );
						}
					}
				);
			}
		} );
	}

	init(); // kick everything off controlling types.
}
