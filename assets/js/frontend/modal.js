import jQuery from 'jquery';
import {getItem} from './cookie';

let $ = jQuery;
let $window = $(window);
let notices = []; // Notices data store.
let settings = {
	contentType: "application/json",
	placement: 'popup-modal',
	format: 'html',
	post_info:{},
};

const modal = () => {
	$window.on( 'load', loadModals );
}

/**
 * Shows the modal (if there is one) after the page is fully loaded
 */
const loadModals = () => {

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

				if ( dismissed_notice_ids.indexOf(parseInt(index)) !== -1 ) {
					return;
				}

				notices.push( response.notices[index] );
			} );

			if ( notices.length > 0 ) {

				window.courier_notices_modal_notices = notices;

				displayModal( 0 );
			}
		}
	} );
};

/**
 * Display a modal notice by index based on the window.courier_notices_modal_notices array
 * also remove the element from the dataset.
 *
 * @since 1.3.0
 *
 * @param index
 */
export function displayModal ( index ) {
	let $notice = $( window.courier_notices_modal_notices[ index ] ).hide();

	window.courier_notices_modal_notices.splice( index, 1 );

	$( '.courier-notices[data-courier-placement="' + settings.placement + '"] .courier-modal-overlay' )
		.append( $notice );

	$('.modal_overlay').show();

	$notice.slideDown( 'fast' );
}

export default modal;
