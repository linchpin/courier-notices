import jQuery from 'jquery';
import {getItem} from "./cookie";

let $ = jQuery;

export default function modal() {

	let $doc        = $(document),
		$body       = $('body'),
		$window     = $(window),
		initialized = false;

	const init = () => {
		if ( ! initialized ) {
			window.onload = display_modal;
			initialized   = true;
		}
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

		// There should only ever be one modal placement display container, forcing :first selector
		let $container = $( '.courier-notices.courier-location-popup-modal[data-courier-placement="' + settings.placement + '"]:first' );

		// If for some reason there is a theme conflict double check if modal has already loaded. If so die early.
		if ( 'true' === $container.prop( 'data-loaded' ) ) {
			return;
		}

		$.ajax( {
			method: 'GET',
			beforeSend: function (xhr) {
				if ( courier_notices_data.user_id !== '0' ) {
					xhr.setRequestHeader('X-WP-Nonce', courier_notices_data.wp_rest_nonce);
				}
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

					// There should only ever be one modal placement display container, forcing :first selector
					$container.append( $notice );

					$notice.slideDown( 'fast' );
				} );

				$container.prop( 'data-loaded', 'true' );
			}
		} );

		$('.modal_overlay').show();
	};

	init();
}
