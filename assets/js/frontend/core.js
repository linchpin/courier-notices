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
			$notice_container.data( 'loaded', false );

		// If no notice containers expecting ajax, die early.
		if ( $notice_container.length === 0 ) {
			return;
		}

		let observer = new IntersectionObserver(function( entries, observer ) {

			entries.forEach( function( entry ) {

				if ( entry.intersectionRatio === 1 && false === $notice_container.data('loaded') ) {

					let settings = {
						contentType: "application/json",
						placement: $notice_container.data( 'courier-placement' ),
						format: 'html',
					};

					let data = $.extend( {}, courier_data.post_info, settings );

					let dismissed_notice_ids = getItem( 'dismissed_notices' );
						dismissed_notice_ids = JSON.parse( dismissed_notice_ids );
						dismissed_notice_ids = dismissed_notice_ids || [];

					$.ajax( {
						method: 'GET',
						beforeSend: function (xhr) {
							xhr.setRequestHeader( 'X-WP-Nonce', courier_data.wp_rest_nonce );
						},
						'url': courier_data.notices_endpoint,
						'data': data,
					} ).success( function ( response ) {
						if ( response.notices ) {

							$.each( response.notices, function ( index ) {
								let $notice = $( response.notices[ index ] ).hide();

								// If the notice is dismissed don't show it.
								if ( dismissed_notice_ids.indexOf( parseInt( index )  ) !== -1 ) {
									return;
								}

								$( '.courier-notices[data-courier-placement="' + data.placement + '"]' )
									.append( $notice );

								$notice.slideDown( 'fast' );
							} );
						}
					} );

					$notice_container.data( 'loaded', true );
				}
			} );
		}, { threshold: 1 } );

		observer.observe( document.querySelector('.courier-notices[data-courier-ajax="true"]' ) );
	}

	domReady( init );
}
