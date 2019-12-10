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
	var $body = $('body');

	init();

	/**
	 * Initialize Notifications
	 */
	function init() {

		$body
			.on( 'click', '.courier-update-notice .notice-dismiss', dismissNotification )
			.on( 'click', '.courier-review-notice .review-dismiss, .courier-review-notice .notice-dismiss', dismissNotification );
	}

	/**
	 * Ajax call to dismiss notifications.
	 *
	 * @since 1.2
	 */
	function dismissNotification ( event ) {

		event.preventDefault();

		var $notice = $(this).parents('.mesh-notice');

		$.post( ajaxurl, {
			action                : 'courier_dismiss_notification',
			mesh_notification_type : $notice.attr('data-type'),
			_wpnonce              : courier_data.dismiss_nonce
		}, function( response ) {
			$notice.fadeTo(100, 0,function() {
				$notice.slideUp( 100, function(){
					$notice.remove();
				});
			});
		});
	}
}
