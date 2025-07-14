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
	 * @since 1.7.2
	 */
	function init() {

		let $notice_containers = $( '.courier-notices[data-courier-ajax="true"]' );
		
		// If no notice containers expecting ajax, die early.
		if ( $notice_containers.length === 0 ) {
			return;
		}

		// Mark all containers as not loaded
		$notice_containers.attr( 'data-loaded', false );

		// Collect all placements that have containers
		let placements = [];
		$notice_containers.each( function() {
			let placement = $( this ).data( 'courier-placement' );
			if ( placement && placements.indexOf( placement ) === -1 ) {
				placements.push( placement );
			}
		});

		// If no placements found, die early
		if ( placements.length === 0 ) {
			return;
		}

		// Use IntersectionObserver to detect when any notice container becomes visible
		let observer = new IntersectionObserver( function( entries ) {
			// Check if any container is fully visible and not loaded
			let shouldLoad = entries.some( function( entry ) {
				return entry.intersectionRatio === 1 && 
					   'false' === entry.target.getAttribute( 'data-loaded' );
			});

			if ( shouldLoad ) {
				loadAllNotices();
			}
		}, { threshold: 1 });

		// Observe all notice containers
		$notice_containers.each( function() {
			observer.observe( this );
		});
	}

	/**
	 * Load all notices in a single AJAX call
	 *
	 * @since 1.7.2
	 * @param {Array} placements Array of placement strings
	 */
	function loadAllNotices( placements ) {
		// Check if we've already loaded notices
		let $loadedContainers = $( '.courier-notices[data-loaded="true"]' );
		if ( $loadedContainers.length > 0 ) {
			return;
		}

		let settings = {
			placements: placements,
			format: 'html',
			post_info: {},
			user_id: ( courier_notices_data.user_id !== '0' ) ? courier_notices_data.user_id : '',
		};

		if ( typeof( courier_notices_data.post_info ) !== 'undefined' ) {
			settings.post_info = courier_notices_data.post_info;
		}

		let dismissed_notice_ids = getItem( 'dismissed_notices' );
		dismissed_notice_ids = JSON.parse( dismissed_notice_ids );
		dismissed_notice_ids = dismissed_notice_ids || [];

		// Make single AJAX call to fetch all notices
		$.ajax({
			method: 'GET',
			beforeSend: function (xhr) {
				// only send nonce if the user is logged in.
				if ( courier_notices_data.user_id !== '0' ) {
					xhr.setRequestHeader( 'X-WP-Nonce', courier_notices_data.wp_rest_nonce );
				}
			},
			url: courier_notices_data.notices_all_endpoint,
			data: settings,
		}).done( function( response ) {
			// Distribute notices to their appropriate containers
			if ( response && typeof response === 'object' ) {
				distributeNotices( response, dismissed_notice_ids );
			}
		}).fail( function( jqXHR, textStatus, errorThrown ) {
			console.error( 'Courier Notices: Failed to load notices', textStatus, errorThrown );
		});
	}

	/**
	 * Distribute notices to appropriate containers
	 *
	 * @since 1.7.2
	 * @param {Object} response Response object with notices grouped by placement
	 * @param {Array} dismissed_notice_ids Array of dismissed notice IDs
	 */
	function distributeNotices( response, dismissed_notice_ids ) {
		// Process each placement
		Object.keys( response ).forEach( function( placement ) {
			let notices = response[ placement ];
			let $container = $( '.courier-notices[data-courier-placement="' + placement + '"]' );
			
			// Skip if no container for this placement
			if ( $container.length === 0 ) {
				return;
			}

			// Handle modal placement differently
			if ( placement === 'popup-modal' ) {
				handleModalNotices( notices, dismissed_notice_ids );
			} else {
				// Handle standard placements (header, footer)
				Object.keys( notices ).forEach( function( notice_id ) {
					// Skip if notice is dismissed
					if ( dismissed_notice_ids.indexOf( parseInt( notice_id ) ) !== -1 ) {
						return;
					}

					let $notice = $( notices[ notice_id ] ).hide();
					$container.append( $notice );

					// Animate the notice in
					$notice.slideDown( 'fast', 'swing', function() {
						const event = new CustomEvent( 'courierNoticeDisplayed', {
							detail: {
								notice_id: notice_id,
								placement: placement
							}
						});
						document.dispatchEvent( event );
					});
				});
			}

			// Mark container as loaded
			$container.attr( 'data-loaded', 'true' );
		});
	}

	/**
	 * Handle modal notices with special timing and display logic
	 *
	 * @since 1.7.2
	 * @param {Object} notices Object of modal notices keyed by ID
	 * @param {Array} dismissed_notice_ids Array of dismissed notice IDs
	 */
	function handleModalNotices( notices, dismissed_notice_ids ) {
		let modalNotices = [];
		
		Object.keys( notices ).forEach( function( notice_id ) {
			if ( dismissed_notice_ids.indexOf( parseInt( notice_id ) ) !== -1 ) {
				return;
			}
			modalNotices.push( notices[ notice_id ] );
		});

		if ( modalNotices.length > 0 ) {
			window.courier_notices_modal_notices = modalNotices;
			// Show first modal after a delay
			setTimeout( function() {
				displayModal( 0 );
			}, 500 );
		}
	}

	/**
	 * Display a modal notice by index based on the window.courier_notices_modal_notices array
	 * also remove the element from the dataset.
	 *
	 * @since 1.3.0
	 *
	 * @param index
	 */
	function displayModal ( index ) {
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

	domReady( init );
}
