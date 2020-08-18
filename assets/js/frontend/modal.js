import jQuery from 'jquery';
import {getItem} from "./cookie";

let $ = jQuery;

export default function modal() {

	let $doc    = $(document),
		$body   = $('body'),
		$window = $(window);

	const init = () => {
		$window.on('load', display_modal);
	};

	/**
	 * Shows the modal (if there is one) after the page is fully loaded
	 */
	const display_modal = () => {

		let settings = {
			contentType: "application/json",
			placement: 'popup-modal',
			format: 'html',
			post_info:{},
		};

		let modalContainer = document.querySelector('.courier-notices.courier-location-popup-modal[data-courier-ajax="true"]' );

		// If no modal container die early.
		if ( ! modalContainer ) {
			return;
		}

		if ( typeof( courier_notices_data.post_info ) !== 'undefined' ) {
			settings.post_info = courier_notices_data.post_info;
		}

		let dismissed_notice_ids = getItem( 'dismissed_notices' );
			dismissed_notice_ids = JSON.parse( dismissed_notice_ids );
			dismissed_notice_ids = dismissed_notice_ids || [];

		$.ajax( {
			method: 'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader( 'X-WP-Nonce', courier_notices_data.wp_rest_nonce );
			},
			'url': courier_notices_data.notices_endpoint,
			'data': settings,
		} ).success( function ( response ) {

			if ( response.notices ) {

				$.each( response.notices, function ( index ) {

					// If the notice is dismissed don't show it.
					if ( dismissed_notice_ids.indexOf( parseInt( index ) ) !== -1 ) {
						return;
					}

					let $notice = $( response.notices[ index ] ).hide();

					$( '.courier-notices[data-courier-placement="' + settings.placement + '"]' )
						.append( $notice );

					$notice.slideDown( 'fast' );
				} );
			}
		} );

		// .target.setAttribute( 'data-loaded', true );

		$('.modal_overlay').show();
	};

	init();
}
