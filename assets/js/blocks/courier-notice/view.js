/**
 * WordPress dependencies
 */
import { store } from '@wordpress/interactivity';

/**
 * Courier Notices Interactivity Store
 */
store( 'courier-notices', {
	state: {
		dismissedNotices: [],
	},
	actions: {
		/**
		 * Dismiss a notice
		 */
		dismissNotice: () => {
			const { ref } = store( 'courier-notices' );
			const context = store( 'courier-notices' ).getContext();
			const { noticeId, dismissible } = context;

			if ( ! dismissible ) {
				return;
			}

			// Add visual dismiss animation
			ref.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
			ref.style.opacity = '0';
			ref.style.transform = 'translateY(-10px)';

			// Remove from DOM after animation
			setTimeout( () => {
				if ( ref && ref.parentNode ) {
					ref.parentNode.removeChild( ref );
				}
			}, 300 );

			// Store dismissed state (in real implementation, this could sync with server)
			store( 'courier-notices' ).state.dismissedNotices.push( noticeId );

			// Dispatch custom event for other scripts to listen to
			const event = new CustomEvent( 'courierNoticeDismissed', {
				detail: { noticeId },
			} );
			document.dispatchEvent( event );

			// If this is a persistent notice, make API call to dismiss server-side
			if ( window.courierNoticesData?.restUrl && window.courierNoticesData?.nonce ) {
				fetch( `${window.courierNoticesData.restUrl}courier-notices/v1/dismiss`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': window.courierNoticesData.nonce,
					},
					body: JSON.stringify( {
						notice_id: noticeId,
					} ),
				} ).catch( ( error ) => {
					console.warn( 'Failed to dismiss notice server-side:', error );
				} );
			}
		},

		/**
		 * Show notice with animation
		 */
		showNotice: () => {
			const { ref } = store( 'courier-notices' );
			
			// Initial hidden state
			ref.style.opacity = '0';
			ref.style.transform = 'translateY(-10px)';
			ref.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

			// Animate in
			setTimeout( () => {
				ref.style.opacity = '1';
				ref.style.transform = 'translateY(0)';
			}, 50 );
		},
	},
	callbacks: {
		/**
		 * Initialize notice on mount
		 */
		initNotice: () => {
			const context = store( 'courier-notices' ).getContext();
			const { noticeId } = context;
			const { dismissedNotices } = store( 'courier-notices' ).state;

			// Don't show if already dismissed
			if ( dismissedNotices.includes( noticeId ) ) {
				const { ref } = store( 'courier-notices' );
				if ( ref && ref.parentNode ) {
					ref.parentNode.removeChild( ref );
				}
				return;
			}

			// Show notice with animation
			store( 'courier-notices' ).actions.showNotice();
		},
	},
} );