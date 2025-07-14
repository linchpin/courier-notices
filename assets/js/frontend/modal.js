
import {getItem} from './cookie';

let $ = jQuery;
let $window = $(window);
let settings = {
	placements: ['popup-modal'],
	format: 'html',
	post_info: {},
};

const modal = () => {
	document.readyState === 'complete' ? setupModals() : window.addEventListener( 'load', setupModals );
}

const setupModals = () => {
	setTimeout(() => { 
		loadModals();
	}, '500' );
}

/**
 * Shows the modal (if there is one) after the page is fully loaded
 * Updated to use the optimized endpoint
 *
 * @since 1.7.2
 */
const loadModals = () => {

	let modalContainer = document.querySelector('.courier-notices.courier-location-popup-modal[data-courier-ajax="true"]' );
	let notices        = [];

	// If no modal container die early.
	if ( ! modalContainer ) {
		return;
	}

	// Check if we should include user ID
	if ( courier_notices_data.user_id !== '0' ) {
		settings.user_id = courier_notices_data.user_id;
	}

	if ( typeof( courier_notices_data.post_info ) !== 'undefined' ) {
		settings.post_info = courier_notices_data.post_info;
	}

	let dismissed_notice_ids = getItem( 'dismissed_notices' );
		dismissed_notice_ids = JSON.parse( dismissed_notice_ids );
		dismissed_notice_ids = dismissed_notice_ids || [];

	// Use the optimized endpoint
	$.ajax( {
		method: 'GET',
		beforeSend: function (xhr) {
			// only send nonce if the user is logged in.
			if ( courier_notices_data.user_id !== '0' ) {
				xhr.setRequestHeader( 'X-WP-Nonce', courier_notices_data.wp_rest_nonce );
			}
		},
		url: courier_notices_data.notices_all_endpoint,
		data: settings,
	} ).done( function ( response ) {

		// Handle the new response format
		if ( response && response['popup-modal'] ) {
			let modalNotices = response['popup-modal'];

			for ( let notice_id in modalNotices ) {
				if ( dismissed_notice_ids.indexOf( parseInt( notice_id ) ) !== -1 ) {
					continue;
				}

				notices.push( modalNotices[ notice_id ] );
			}

			if ( notices.length > 0 ) {
				window.courier_notices_modal_notices = notices;
				displayModal( 0 );
			}
		}
	} ).fail( function( jqXHR, textStatus, errorThrown ) {
		console.error( 'Courier Notices: Failed to load modal notices', textStatus, errorThrown );
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

	let $notice = $( window.courier_notices_modal_notices[ index ] );
		$notice.hide();

	if ( $notice.length < 1 ) {
		return;
	}

	$( '.courier-notices[data-courier-placement="popup-modal"] .courier-modal-overlay' )
		.append( $notice );

	$('.courier-modal-overlay').removeClass('hide').show();
	$notice.slideDown( 'fast' );

	window.courier_notices_modal_notices.splice( index, 1 );
}

export default modal;
