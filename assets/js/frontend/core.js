import jQuery from 'jquery';
import { getItem } from './cookie';

let $ = jQuery;

export default function core() {

	/**
	 * Check to make sure the dom is ready before we display any notices
	 * @since 1.0.5
	 *
	 * @param callback
	 */
	function domReady( callback ) {
		document.readyState === "interactive" || document.readyState === "complete" ? callback() : document.addEventListener( "DOMContentLoaded", callback );
	}

	/**
	 * Initialize the core display of notices
	 *
	 * @since 1.0.0
	 */
	function init() {

		let $notice_container = $( '.courier-notices[data-courier-ajax="true"]' );
			$notice_container.attr( 'data-loaded', false );

		// If no notice containers expecting ajax, die early.
		if ( $notice_container.length === 0 ) {
			return;
		}

		let courierContainers = document.querySelectorAll('.courier-notices:not(.courier-location-popup-modal)[data-courier-ajax="true"]' );
		let observer          = new IntersectionObserver(function( entries, observer ) {

			entries.forEach( function( entry ) {

				if ( entry.intersectionRatio === 1 && 'false' === entry.target.getAttribute( 'data-loaded' ) ) {

					let settings = {
						contentType: "application/json",
						placement: entry.target.getAttribute( 'data-courier-placement' ),
						format: 'html',
						post_info:{},
					};

					if ( typeof( courier_notices_data.post_info ) !== 'undefined' ) {
						settings.post_info = courier_notices_data.post_info;
					}

					let dismissed_notice_ids = getItem( 'dismissed_notices' );
						dismissed_notice_ids = JSON.parse( dismissed_notice_ids );
						dismissed_notice_ids = dismissed_notice_ids || [];

					$.ajax( {
						method: 'GET',
						beforeSend: function (xhr) {
							// only send nonce if the user is logged in.
							if ( courier_notices_data.user_id !== '0' ) {
								xhr.setRequestHeader( 'X-WP-Nonce', courier_notices_data.wp_rest_nonce );
							}
						},
						'url': courier_notices_data.notices_endpoint,
						'data': settings,
					} ).success( function ( response ) {
						if ( response.notices ) {

							$.each( response.notices, function ( index ) {

								// If the notice is dismissed don't show it.
								if ( dismissed_notice_ids.indexOf( parseInt( index )  ) !== -1 ) {
									return;
								}

								let $notice = $( response.notices[ index ] ).hide();

								$( '.courier-notices[data-courier-placement="' + settings.placement + '"]' )
									.append( $notice );

								$notice.slideDown( 'fast', 'swing', function() {
									const event = new CustomEvent( 'courierNoticeDisplayed', { detail: index });

									document.dispatchEvent(event);
								});
							} );


						}
					} );

					entry.target.setAttribute( 'data-loaded', true );
				}
			} );
		}, { threshold: 1 } );

		Array.prototype.forEach.call( courierContainers, function ( element ) {
			observer.observe( element );
		});
	}

	domReady( init );
}
