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
    let $window       = $(window),
        $doc          = $(document),
        $body         = $('body'),
        $types        = $('.courier_notice_page_courier');

    /**
     * Add some event listeners
     */
    function init() {
        $types.find('.courier-notices-type-delete').on( 'click', confirmDeleteCourierNoticeType );
    }

    /**
     * Confirm delete of Courier Notice type term
     *
     * @since 1.0
     *
     * @param event
     */
    function confirmDeleteCourierNoticeType( event ) {

        event.preventDefault();

        var $this = $(this);

        if ( true !== $this.data('confirm' ) ) {
            $this.find('dashicons-trash').hide();
            $this.addClass('button button-primary').text( courier_admin_data.strings.confirm_delete ).data('confirm', true );
        } else {
            $this.addClass('disabled').text( courier_admin_data.strings.deleting );

            deleteCourierNoticeType( $this );
        }

    }

    /**
     * Delete the Courier Notice Type
     *
     * @since 1.0
     */
    function deleteCourierNoticeType( $target ) {
        $.post( ajaxurl, {
            action: 'courier_notices_delete_type',
            courier_notices_delete_type: courier_admin_data.delete_nonce,
            courier_notices_type: parseInt( $target.data('term-id') )
        }).success( function() {
            $target.closest('tr').fadeOut('fast');
        } );
    }

    init(); // kick everything off controlling types
}
