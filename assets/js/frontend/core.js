import { getItem } from './cookie';

export default function core() {
	/**
	 * Check to make sure the dom is ready before we display any notices
	 * @since 1.0.5
	 *
	 * @param callback
	 */
	function domReady(callback) {
		document.readyState === 'interactive' ||
		document.readyState === 'complete'
			? callback()
			: document.addEventListener('DOMContentLoaded', callback);
	}

	/**
	 * Initialize the core display of notices
	 *
	 * @since 1.7.2
	 */
	function init() {
		let noticeContainers = document.querySelectorAll(
			'.courier-notices[data-courier-ajax="true"]'
		);

		// If no notice containers expecting ajax, die early.
		if (noticeContainers.length === 0) {
			return;
		}

		// Check if any notices exist at all
		if (!courier_notices_data.has_notices) {
			// No notices exist, so don't set up observers or make AJAX calls
			return;
		}

		// Mark all containers as not loaded
		noticeContainers.forEach(function (container) {
			container.setAttribute('data-loaded', 'false');
		});

		// Collect all placements that have containers
		let placements = [];
		noticeContainers.forEach(function (container) {
			let placement = container.getAttribute('data-courier-placement');
			if (placement && placements.indexOf(placement) === -1) {
				placements.push(placement);
			}
		});

		// If no placements found, die early
		if (placements.length === 0) {
			return;
		}

		// Use IntersectionObserver to detect when any notice container becomes visible
		let observer = new IntersectionObserver(
			function (entries) {
				// Check if any container is fully visible and not loaded
				let shouldLoad = entries.some(function (entry) {
					return (
						entry.intersectionRatio === 1 &&
						'false' === entry.target.getAttribute('data-loaded')
					);
				});

				if (shouldLoad) {
					loadAllNotices(placements);
				}
			},
			{ threshold: 1 }
		);

		// Observe all notice containers
		noticeContainers.forEach(function (container) {
			observer.observe(container);
		});
	}

	/**
	 * Load all notices in a single AJAX call
	 *
	 * @since 1.7.2
	 * @param {Array} placements Array of placement strings
	 */
	function loadAllNotices(placements) {
		// Check if we've already loaded notices
		let loadedContainers = document.querySelectorAll(
			'.courier-notices[data-loaded="true"]'
		);
		if (loadedContainers.length > 0) {
			return;
		}

		let settings = {
			placements: placements,
			format: 'html',
			post_info: {},
			user_id:
				courier_notices_data.user_id !== '0'
					? courier_notices_data.user_id
					: '',
		};

		if (typeof courier_notices_data.post_info !== 'undefined') {
			settings.post_info = courier_notices_data.post_info;
		}

		let dismissed_notice_ids = getItem('dismissed_notices');
		dismissed_notice_ids = JSON.parse(dismissed_notice_ids);
		dismissed_notice_ids = dismissed_notice_ids || [];

		// Build query string from settings
		let queryParams = new URLSearchParams();
		for (let key in settings) {
			if (key === 'post_info') {
				for (let subKey in settings[key]) {
					queryParams.append(
						`post_info[${subKey}]`,
						settings[key][subKey]
					);
				}
			} else if (key === 'placements') {
				settings[key].forEach(function (placement) {
					queryParams.append('placements[]', placement);
				});
			} else {
				queryParams.append(key, settings[key]);
			}
		}

		// Make fetch call to get all notices
		let headers = {};
		// only send nonce if the user is logged in.
		if (courier_notices_data.user_id !== '0') {
			headers['X-WP-Nonce'] = courier_notices_data.wp_rest_nonce;
		}

		fetch(
			courier_notices_data.notices_all_endpoint +
				'?' +
				queryParams.toString(),
			{
				method: 'GET',
				headers: headers,
			}
		)
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(function (response) {
				// Distribute notices to their appropriate containers
				if (response && typeof response === 'object') {
					distributeNotices(response, dismissed_notice_ids);
				}
			})
			.catch(function (error) {
				console.error('Courier Notices: Failed to load notices', error);
			});
	}

	/**
	 * Distribute notices to appropriate containers
	 *
	 * @since 1.7.2
	 * @param {Object} response Response object with notices grouped by placement
	 * @param {Array} dismissed_notice_ids Array of dismissed notice IDs
	 */
	function distributeNotices(response, dismissed_notice_ids) {
		// Process each placement
		Object.keys(response).forEach(function (placement) {
			let notices = response[placement];
			let container = document.querySelector(
				'.courier-notices[data-courier-placement="' + placement + '"]'
			);

			// Skip if no container for this placement
			if (!container) {
				return;
			}

			// Handle modal placement differently
			if (placement === 'popup-modal') {
				handleModalNotices(notices, dismissed_notice_ids);
			} else {
				// Handle standard placements (header, footer)
				Object.keys(notices).forEach(function (notice_id) {
					// Skip if notice is dismissed
					if (
						dismissed_notice_ids.indexOf(parseInt(notice_id)) !== -1
					) {
						return;
					}

					// Create temporary div to parse HTML
					let tempDiv = document.createElement('div');
					tempDiv.innerHTML = notices[notice_id];
					let noticeElement = tempDiv.firstElementChild;

					// Hide initially
					noticeElement.style.display = 'none';
					container.appendChild(noticeElement);

					// Animate the notice in using CSS transition
					slideDown(noticeElement, function () {
						const event = new CustomEvent(
							'courierNoticeDisplayed',
							{
								detail: {
									notice_id: notice_id,
									placement: placement,
								},
							}
						);
						document.dispatchEvent(event);
					});
				});
			}

			// Mark container as loaded
			container.setAttribute('data-loaded', 'true');
		});
	}

	/**
	 * Handle modal notices with special timing and display logic
	 *
	 * @since 1.7.2
	 * @param {Object} notices Object of modal notices keyed by ID
	 * @param {Array} dismissed_notice_ids Array of dismissed notice IDs
	 */
	function handleModalNotices(notices, dismissed_notice_ids) {
		let modalNotices = [];

		Object.keys(notices).forEach(function (notice_id) {
			if (dismissed_notice_ids.indexOf(parseInt(notice_id)) !== -1) {
				return;
			}
			modalNotices.push(notices[notice_id]);
		});

		if (modalNotices.length > 0) {
			window.courier_notices_modal_notices = modalNotices;
			// Show first modal after a delay
			setTimeout(function () {
				displayModal(0);
			}, 500);
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
	function displayModal(index) {
		if (
			!window.courier_notices_modal_notices ||
			!window.courier_notices_modal_notices[index]
		) {
			return;
		}

		// Create temporary div to parse HTML
		let tempDiv = document.createElement('div');
		tempDiv.innerHTML = window.courier_notices_modal_notices[index];
		let noticeElement = tempDiv.firstElementChild;

		if (!noticeElement) {
			return;
		}

		noticeElement.style.display = 'none';

		let modalOverlay = document.querySelector(
			'.courier-notices[data-courier-placement="popup-modal"] .courier-modal-overlay'
		);
		if (modalOverlay) {
			modalOverlay.appendChild(noticeElement);
			modalOverlay.classList.remove('hide');
			modalOverlay.style.display = 'block';
			slideDown(noticeElement);
		}

		window.courier_notices_modal_notices.splice(index, 1);
	}

	/**
	 * Vanilla JavaScript slideDown animation
	 *
	 * @param {HTMLElement} element Element to slide down
	 * @param {Function} callback Optional callback when animation completes
	 */
	function slideDown(element, callback) {
		element.style.display = 'block';
		element.style.overflow = 'hidden';

		let height = element.scrollHeight;
		element.style.height = '0px';
		element.style.transition = 'height 0.3s ease';

		// Force browser reflow
		element.offsetHeight;

		element.style.height = height + 'px';

		setTimeout(function () {
			element.style.height = '';
			element.style.overflow = '';
			element.style.transition = '';
			if (callback) {
				callback();
			}
		}, 300);
	}

	// Listen for next modal event
	document.addEventListener('courierModalNext', function () {
		if (
			window.courier_notices_modal_notices &&
			window.courier_notices_modal_notices.length > 0
		) {
			displayModal(0);
		}
	});

	domReady(init);
}
