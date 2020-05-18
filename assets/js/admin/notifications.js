/**
 * Controls Notification Administration
 *
 * @since 1.2
 */

/**
 * Controls Welcome area display
 *
 * @package    Courier
 * @subpackage Welcome
 * @since      1.0
 */

import jQuery from 'jquery';

let $ = jQuery;

export default function notifications() {
	let $body = $('body');

	init();

	/**
	 * Initialize Notifications
	 */
	function init() {

		$body
			.on( 'click', '.courier-admin-notice .notice-dismiss', dismissNotification )
			.on( 'click', '.courier-review-notice .review-dismiss, .courier-review-notice .notice-dismiss', dismissNotification );
	}

	/**
	 * Ajax call to dismiss notifications.
	 *
	 * @since 1.2
	 */
	function dismissNotification ( event ) {

		event.preventDefault();

		var $notice = $(this).parents('.courier-admin-notice');

		$.post( ajaxurl, {
			action                : 'courier_dismiss_notification',
			courier_notification_type : $notice.attr('data-type'),
			_ajax_nonce              : courier_notices_admin_data.dismiss_nonce
		}, function( response ) {
			$notice.fadeTo(100, 0,function() {
				$notice.slideUp( 100, function(){
					$notice.remove();
				});
			});
		});
	}
}
