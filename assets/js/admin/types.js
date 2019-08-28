/**
 * Controls Notice Types admin area within settings
 *
 * @package    Courier
 * @subpackage Types
 * @since      1.0
 */

import jQuery from 'jquery';

let $ = jQuery;

export default function types() {

    // Private Variables
    let $window = $(window),
        $doc = $(document),
        $body = $('body'),
        $types = $('.courier_notice_page_courier'),
        $new_container = $('#courier-notice-type-new'),
		courierNoticeTypeTemplate = $('#courier-notice-type-template').text().split(/\{(.+?)\}/g),
		courierNoticeTypeEditTemplate = $('#courier-notice-type-edit-template').text().split(/\{(.+?)\}/g),
		inputTemplate = {
			indicator : $('#courier-notice-loader').html(),
			cancel : courier_admin_data.strings.cancel,
			cancelcssclass : 'btn btn-danger',
			submitcssclass : 'btn btn-success',
			maxlength : 50,
			showfn : function(elem) {
				elem.fadeIn('fast')
			},
			submit : courier_admin_data.strings.save,
			tooltip : courier_admin_data.strings.editurl,
			width : '100%'
		},
		courierNoticeTypeCurrent = '';

    /**
     * Add some event listeners
     */
    function init() {

		// Setup type edit screen js within settings.
		setupTypeEditing();
		setupControls();
	}

	function setupControls() {
		$body
			.on( 'click', '.courier-notices-type-delete', confirmDeleteCourierNoticeType )
			.on( 'click', '#add-courier-notice-type', addNewCourierNoticeTypeRow )
			.on( 'click', '#courier-notice-type-new .save-button', addCourierNoticeType )
			.on( 'click', '#courier-notice-type-new .close-button', cancelAddCourierNoticeType )
			.on( 'click', '.courier-notice-type-edit', editCourierNoticeType );
	}

	/**
	 * Edit a courier notice
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function editCourierNoticeType( event ) {
    	event.preventDefault();

    	var $parentRow = $(this).closest('tr');


		$parentRow.addClass('editing');

		courierNoticeTypeCurrent = $parentRow.detach(); // Store our row for usage later, if some on decides not to edit.
	}

	/**
	 * display the template.
	 * @param item
	 */
	function displayEditCourierNoticeTypeTemplate ( item ) {

		var $noticeRow = $( courierNoticeTypeEditTemplate.map( render( item ) ).join( '' ) );
		$( 'table.courier_notice_page_courier tbody' ).append( $( $noticeRow ) );

		setupTypeEditing();
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

		$target.fadeOut('fast').promise().done( function () {
			$('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');

			$(this).remove();
		} );
	}

	/**
	 * Add a new courier type.
	 *
	 * @since 1.0
	 *
	 * @param event
	 */
	function addNewCourierNoticeTypeRow( event ) {

		event.preventDefault();

		$(this).addClass( 'disabled' ).attr( 'disabled', 'disabled' );

		// Only show the new row if it's not visible.
		// if ( ! $new_container.is(':visible') ) {

			var options = {};
			var input   = $.extend( true, options, inputTemplate );

			displayNewCourierNoticeTypeTemplate( input );
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

		var $this = $(this);

		if ( true !== $this.data('confirm') ) {
			$this.find('dashicons-trash').hide();
			$this.addClass('button button-primary').text(courier_admin_data.strings.confirm_delete).data('confirm', true);
		} else {
			$this.addClass('disabled').text( courier_admin_data.strings.deleting );

			deleteCourierNoticeType($this);
		}

	}

	/**
	 * Delete the Courier Notice Type
	 *
	 * @since 1.0
	 */
	function deleteCourierNoticeType($target) {
		$.post(ajaxurl, {
			action: 'courier_notices_delete_type',
			courier_notices_delete_type: courier_admin_data.delete_nonce,
			courier_notices_type: parseInt($target.data('term-id'))
		}).success(function () {
			$target.closest('tr').fadeOut('fast').promise().done( function() {
				$(this).remove(); // Remove the row from the table after it fades out.
			});
		});
	}

	/**
	 * Setup Courier Type Editing Screen
	 *
	 * @since 1.0
	 */
	function setupTypeEditing() {
		$( '.courier-type-color' ).wpColorPicker();
	}

	/**
	 * Render our template including our properties.
	 *
	 * This is a temp solution prior to move to react.
	 *
	 * @param props
	 * @returns {Function}
	 */
	function render( props ) {
		return function (token, i) {
			return ( i % 2 ) ? props[ token ] : token;
		};
	}

	/**
	 * Display the template for adding a new Notice Type.
	 * @param item
	 */
	function displayNewCourierNoticeTypeTemplate ( item ) {

		var $noticeRow = $( courierNoticeTypeTemplate.map( render( item ) ).join( '' ) );
		$( 'table.courier_notice_page_courier tbody' ).append( $( $noticeRow ) );

		setupTypeEditing();
	}

	/**
	 * Add / Save our new courier notice type
	 */
	function addCourierNoticeType( event ) {

		event.preventDefault();

		$(this).addClass('disabled').attr('disabled', 'disabled');

		var $target = $('#courier-notice-type-new');

		$.post( ajaxurl, {
			action: 'courier_notices_add_type',
			'page': 'courier',
			'courier_notices_add_type': courier_admin_data.add_nonce,
			'courier_notice_type_new_title' : $('#courier-notice-type-new-title').val(),
			'courier_notice_type_new_css_class' : $('#courier-notice-type-new-css-class').val(),
			'courier_notice_type_new_color' : $('#courier-notice-type-new-color').val(),
			'courier_notice_type_new_text_color' : $('#courier-notice-type-new-text-color').val(),
			contentType: "application/json"
		}).success( function ( response ) {

			response = JSON.parse( response );

			if ( response && response.fragments ) {
				$target.fadeOut('fast').promise().done( function () {
					$('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');

					for ( var fragment in  response.fragments ) {
						$( fragment ).html( response.fragments[ fragment ] );
					}

					setupTypeEditing();
				} );
			}
		} );
	}

	init(); // kick everything off controlling types.
}
