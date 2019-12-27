import jQuery from 'jquery';

let $ = jQuery;

export default function modal() {

	let $doc  = $(document),
		$body = $('body'),
		$window = $(window);

	init();

	function init() {
		/*
	$window
		.on( 'load', self.display_modal );
		*/
	}

	/**
	 * Shows the modal (if there is one) after the page is fully loaded
	 */
	function display_modal() {

		var $modal_overlay = $('.courier-modal-overlay'),
			notice_ids = courier.cookie.getItem('dismissed_notices');
		notice_ids = JSON.parse(notice_ids);
		notice_ids = notice_ids || [];

		$.each( notice_ids, function (i, val) {
			$('div[data-courier-notice-id="' + val + '"]').remove();
		});

		if ($modal_overlay.length < 1 || $modal_overlay.find('div.courier-notices').length < 1) {
			return;
		}

		// $modal_overlay.show();
	}
}
