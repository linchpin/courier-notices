import { getItem, setItem } from './cookie';

export default function dismiss() {
	let notices;

	init();

	/**
	 * Initialize our dismiss
	 * Add our events
	 */
	function init() {
		// Use event delegation on document body for dynamic content
		document.body.addEventListener('click', function (event) {
			// Check if clicked element or its parent has the dismiss trigger classes
			let target = event.target;
			if (
				target.classList.contains('courier-close') ||
				target.classList.contains('trigger-close') ||
				target.closest('.courier-close') ||
				target.closest('.trigger-close')
			) {
				closeClick(event);
			}
		});
	}

	/**
	 * Utility wrapper to block clicks.
	 *
	 * Grab the data-courier-notice-id from current notice.
	 *
	 * @param event
	 */
	function closeClick(event) {
		let target =
			event.target.classList.contains('courier-close') ||
			event.target.classList.contains('trigger-close')
				? event.target
				: event.target.closest('.courier-close') ||
					event.target.closest('.trigger-close');

		if (!target) {
			return;
		}

		let href = target.getAttribute('href');

		if (target.dataset.dismiss !== 'true') {
			event.preventDefault();
			event.stopPropagation();

			// Store that our notice should be dismissed
			// This will stop an infinite loop.
			target.dataset.dismiss = 'true';

			let notice = target.closest('.courier-notice');
			let notice_id = notice
				? parseInt(notice.dataset.courierNoticeId, 10)
				: NaN;

			if (!notice || isNaN(notice_id)) {
				return;
			}

			// Only pass an ajax call if the user has an ID
			if (
				courier_notices_data.user_id &&
				courier_notices_data.user_id > 0
			) {
				fetch(
					courier_notices_data.notice_endpoint +
						notice_id +
						'/dismiss',
					{
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
							'X-WP-Nonce': courier_notices_data.wp_rest_nonce,
						},
						body: new URLSearchParams({
							dismiss_nonce: courier_notices_data.dismiss_nonce,
							user_id: courier_notices_data.user_id,
						}),
					}
				)
					.then(function (response) {
						if (response.ok) {
							hideNotice(notice_id);

							if (href && href !== '#') {
								window.location = href;
							}
						}
					})
					.catch(function (error) {
						console.error('Error dismissing notice:', error);
					});
			} else {
				hideNotice(notice_id);

				if (href && href !== '#') {
					window.location = href;
				}
			}
		}
	}

	/**
	 * Hide the notice so it is not longer visible.
	 *
	 * @param notice_id
	 */
	function hideNotice(notice_id) {
		let notice = document.querySelector(
			".courier-notice[data-courier-notice-id='" + notice_id + "']"
		);

		if (notice) {
			// Fade out animation
			notice.style.transition = 'opacity 0.5s ease';
			notice.style.opacity = '0';

			setTimeout(function () {
				notice.style.display = 'none';

				// Check if we need to hide modal overlay
				if (
					!window.courier_notices_modal_notices ||
					window.courier_notices_modal_notices.length === 0
				) {
					let modalOverlay = document.querySelector(
						'.courier-modal-overlay'
					);
					if (modalOverlay) {
						modalOverlay.classList.add('hide');
						modalOverlay.style.display = 'none';
					}
				} else {
					// Trigger next modal if available
					const event = new CustomEvent('courierModalNext', {
						detail: { notice_id: notice_id },
					});
					document.dispatchEvent(event);
				}
			}, 500);
		}

		setCookie(notice_id);
	}

	/**
	 * Send all the notices in a comma delimited list.
	 *
	 * @param event
	 */
	function close_all_click(event) {
		let target = event.target;

		if (target.dataset.dismiss !== 'true') {
			event.preventDefault();
			event.stopPropagation();

			// Store that our notice should be dismissed
			// This will stop an infinite loop.
			target.dataset.dismiss = 'true';

			notices = document.querySelectorAll(
				'.courier-notices .courier-notice'
			);

			let notice_ids = [];
			notices.forEach(function (notice) {
				let id = notice.dataset.courierNoticeId;
				if (id) {
					notice_ids.push(id);
				}
			});

			if (notice_ids.length > 0) {
				ajax(notice_ids.join(','));
			}
		}
	}

	/**
	 * Call out to our ajax endpoint pass either a single id or a comma
	 * delimited list of id
	 *
	 * @param notice_ids example 1 or 1,2,3
	 */
	function ajax(notice_ids) {
		fetch(courier_notices_data.endpoint + notice_ids + '/')
			.then(function (response) {
				if (response.ok) {
					// Trigger click on all close buttons
					let closeButtons = document.querySelectorAll(
						'.courier-notices .courier-close'
					);
					closeButtons.forEach(function (button) {
						button.click();
					});

					let ids = String(notice_ids).split(',');
					ids.forEach(function (value) {
						let notice = document.querySelector(
							".courier-notice[data-courier-notice-id='" +
								value +
								"']"
						);
						if (notice) {
							notice.style.transition = 'opacity 0.5s ease';
							notice.style.opacity = '0';
							setTimeout(function () {
								notice.style.display = 'none';
							}, 500);
						}

						let modalOverlay = document.querySelector(
							'.courier-modal-overlay'
						);
						if (modalOverlay) {
							modalOverlay.style.display = 'none';
						}

						setCookie(parseInt(value));
					});
				}
			})
			.catch(function (error) {
				console.error('Error dismissing notices:', error);
			});
	}

	function setCookie(notice_id) {
		let notice_ids = getItem('dismissed_notices');
		notice_ids = JSON.parse(notice_ids);
		notice_ids = notice_ids || [];

		// Add to array if not already in there
		if (notice_ids.indexOf(notice_id) === -1) {
			notice_ids.push(notice_id);
		}

		setItem('dismissed_notices', JSON.stringify(notice_ids));
	}
}
