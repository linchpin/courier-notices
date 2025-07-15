const $ = jQuery;

export default function list() {

    var $doc  = $(document),
        $body = $('body');

    init();

    /**
     * Initialize our dismiss
     * Add our events
	 *
	 * @since 1.0
     */
    function init() {

        if ( 'courier_notice' === courier_notices_admin_data.post_type ) {
            $doc
                .on( 'ready', populate_status );
        }

        $body
            .on('click', '.editinline', quick_edit_populate_status )
            .on('click', '.courier-reactivate-notice', reactivate_notice )
            .on('click', '.courier-reactivate-notice-link', reactivate_notice_link );
    }


    /**
     * When the page loads, push our custom post status into the post status select.
     * If that is the current status of the post, select it and push the text to the on screen label.
	 *
	 * @since 1.0
     */
    function populate_status() {
        var $option = $('<option />').val('courier_expired').text( courier_notices_admin_data.strings.label );

        if ( courier_notices_admin_data.post_status === 'courier_expired' ) {
            $('#post-status-display').text(courier_notices_admin_data.strings.expired);
            $option.attr('selected', 'selected');
        }

        $('#post_status').append($option);
    }

    /**
     * Puts an Expired option in the quick edit dropdown menu.
	 *
	 * @since 1.0
     */
    function quick_edit_populate_status() {
        var $this = $(this),
            $row = $this.parents('tr.iedit'),
            post_id = $row.attr('id').replace('post-', ''),
            post_status = $('#inline_' + post_id + ' ._status').text(),
            $edit_row = '',
            $select = '',
            $expired_option = $('<option />').text(courier_notices_admin_data.strings.label).attr('value', 'courier_expired');

        // Delay things to ensure the quick edit row has been added to the page.
        setTimeout(function () {
            $edit_row = $('#edit-' + post_id);
            $select = $('#edit-' + post_id + ' select[name="_status"]');

            $select.append($expired_option);
            $select.val(post_status);

        }, 300);
    }

    /**
     * Reactivate a notice.
     * @param event
     */
    function reactivate_notice(event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this),
            notice_id = $this.attr('data-courier-notice-id'),
            $notice = $this.parents('.notice');

        $.post(courier_notices_admin_data.reactivate_endpoint + notice_id + '/', {
            success: function (data) {
                $notice.fadeOut();
            }
        });
    }

    /**
     * Reactivate a notice from row actions.
     * @param event
     */
    function reactivate_notice_link(event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this),
            notice_id = $this.attr('data-courier-notice-id'),
            $row = $this.closest('tr'),
            $link = $this;

        // Show loading state.
        $link.text('Reactivating...').addClass('updating-message');

        $.post(courier_notices_admin_data.reactivate_endpoint + notice_id + '/', {
            _wpnonce: courier_notices_admin_data.settings_nonce
        })
        .done(function(response) {
            if (response.success) {
                // Remove expired styling and update the row.
                $row.removeClass('courier-notice-expired');
                
                // Update the expiration date column to show active status.
                var $dateColumn = $row.find('.column-courier-date');
                var expirationDate = new Date();
                expirationDate.setDate(expirationDate.getDate() + 30); // Set to 30 days from now
                
                $dateColumn.html('<span style="color: #0073aa;">' + expirationDate.toLocaleDateString() + '</span>');
                
                // Remove the reactivate link.
                $link.remove();
                
                // Show success message.
                $row.addClass('updated');
                setTimeout(function() {
                    $row.removeClass('updated');
                }, 2000);
            } else {
                // Show error message.
                $link.text('Error').addClass('error');
                setTimeout(function() {
                    $link.text('Reactivate').removeClass('error updating-message');
                }, 2000);
            }
        })
        .fail(function() {
            // Show error message.
            $link.text('Error').addClass('error');
            setTimeout(function() {
                $link.text('Reactivate').removeClass('error updating-message');
            }, 2000);
        });
    }
}
