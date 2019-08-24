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
		};

    /**
     * Add some event listeners
     */
    function init() {

		// Setup type edit screen js within settings.
		setupTypeEditing();

        $types.find('.courier-notices-type-delete').on('click', confirmDeleteCourierNoticeType);

        $body
			.on( 'click', '#add-courier-notice-type', addNewCourierNoticeTypeRow )
			.on( 'click', '$new_container .save-button', addCourierNoticeType );
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

		$(this).addClass('disabled').attr( 'disabled', 'disabled' );

        // Only show the new row if it's visible.
        if ( ! $new_container.is(':visible') ) {

        	var options = {};
			var input   = $.extend( true, options, inputTemplate );

			displayNewCourierNoticeTypeTemplate( input );
        }
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

        if (true !== $this.data('confirm')) {
            $this.find('dashicons-trash').hide();
            $this.addClass('button button-primary').text(courier_admin_data.strings.confirm_delete).data('confirm', true);
        } else {
            $this.addClass('disabled').text(courier_admin_data.strings.deleting);

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
            $target.closest('tr').fadeOut('fast');
        });
    }

	/**
	 * Setup Courier Type Editing Screen
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
	 * display the template.
	 * @param item
	 */
	function displayNewCourierNoticeTypeTemplate ( item ) {

		var $noticeRow = $( courierNoticeTypeTemplate.map( render( item ) ).join('') );
		$('table.courier_notice_page_courier tbody').append( $( $noticeRow ) );

		setupTypeEditing();
	}

	/**
	 * Add / Save our new courier notice type
	 */
	function addCourierNoticeType() {
		$.post( ajaxurl, {
			action: 'courier_notices_add_type',
			'courier-notice-type-add-nonce': courier_admin_data.delete_nonce,
			'courier-notice-type-new-title' : $('#ccourier-notice-type-new-title').val(),
			'courier-notice-type-new-css-class' : $('#courier-notice-type-new-css-class').val(),
			'courier-notice-type-new-color' : $('#courier-notice-type-new-color').val(),
			'courier-notice-type-new-text-color' : $('#courier-notice-type-new-text-color').val()
		}).success(function () {
			
			$target.closest('tr').fadeOut('fast');
		});
	}

	init(); // kick everything off controlling types.
}
