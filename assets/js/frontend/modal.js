/**
 * Modal dialog accessibility.
 *
 * The markup for the modal overlay is rendered by templates/notices-ajax-modal.php
 * as role="dialog" aria-modal="true". core.js and dismiss.js show and hide that
 * overlay directly, so this module watches it for visibility and content changes
 * instead of hooking into either of them, and layers on the behaviour a dialog
 * needs: focus moves in on open, focus is trapped while open, Escape closes, and
 * focus returns to wherever it came from on close.
 *
 * @since 1.10.0
 */

const FOCUSABLE_SELECTOR = [
	'a[href]',
	'area[href]',
	'button:not([disabled])',
	'input:not([disabled])',
	'select:not([disabled])',
	'textarea:not([disabled])',
	'iframe',
	'object',
	'embed',
	'[contenteditable]',
	'[tabindex]:not([tabindex="-1"])',
].join(', ');

export default function modal() {
	let previouslyFocused = null;

	init();

	/**
	 * Wire up every modal dialog on the page.
	 */
	function init() {
		const dialogs = document.querySelectorAll(
			'.courier-modal-overlay[role="dialog"]'
		);

		if (dialogs.length === 0) {
			return;
		}

		dialogs.forEach(function (dialog) {
			watch(dialog);

			dialog.addEventListener('keydown', function (event) {
				onKeyDown(event, dialog);
			});
		});
	}

	/**
	 * Watch a dialog for the show/hide and append that core.js and dismiss.js perform.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 */
	function watch(dialog) {
		let wasVisible = isVisible(dialog);

		const observer = new MutationObserver(function (mutations) {
			const visible = isVisible(dialog);

			// The dialog was just opened or closed.
			if (visible !== wasVisible) {
				wasVisible = visible;

				if (visible) {
					onOpen(dialog);
				} else {
					onClose(dialog);
				}

				return;
			}

			// The dialog was already open and the next queued notice was appended to it.
			if (visible && mutations.some(hasAddedNodes)) {
				focusInto(dialog);
			}
		});

		observer.observe(dialog, {
			attributes: true,
			attributeFilter: ['class', 'style'],
			childList: true,
		});
	}

	/**
	 * Remember where focus came from, then move it into the dialog.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 */
	function onOpen(dialog) {
		previouslyFocused = activeElement(dialog);
		focusInto(dialog);
	}

	/**
	 * Hand focus back to whatever was focused before the dialog opened.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 */
	function onClose(dialog) {
		if (
			previouslyFocused &&
			typeof previouslyFocused.focus === 'function' &&
			dialog.ownerDocument.contains(previouslyFocused)
		) {
			previouslyFocused.focus();
		}

		previouslyFocused = null;
	}

	/**
	 * Handle Escape to close and Tab to cycle focus within the dialog.
	 *
	 * @param {KeyboardEvent} event  The keydown event.
	 * @param {HTMLElement}   dialog The dialog element.
	 */
	function onKeyDown(event, dialog) {
		if (event.key === 'Escape' || event.key === 'Esc') {
			event.preventDefault();
			close(dialog);
			return;
		}

		if (event.key === 'Tab') {
			trapTab(event, dialog);
		}
	}

	/**
	 * Close the dialog.
	 *
	 * A dismissible notice already has a full dismissal flow behind its close
	 * control, so reuse it. A notice that is not dismissible has no close control
	 * at all, so hide the overlay directly rather than trapping the keyboard in it.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 */
	function close(dialog) {
		const notice = visibleNotice(dialog);
		const closeControl = notice
			? notice.querySelector('.courier-close')
			: null;

		if (closeControl) {
			closeControl.click();
			return;
		}

		dialog.classList.add('hide');
		dialog.style.display = 'none';
	}

	/**
	 * Keep Tab and Shift + Tab inside the dialog.
	 *
	 * @param {KeyboardEvent} event  The keydown event.
	 * @param {HTMLElement}   dialog The dialog element.
	 */
	function trapTab(event, dialog) {
		const focusable = Array.prototype.filter.call(
			dialog.querySelectorAll(FOCUSABLE_SELECTOR),
			isRendered
		);

		// Nothing to move to, so keep focus where it is.
		if (focusable.length === 0) {
			event.preventDefault();
			return;
		}

		const index = focusable.indexOf(activeElement(dialog));

		if (event.shiftKey) {
			// On the first focusable, or on the dialog/notice wrapper itself.
			if (index <= 0) {
				event.preventDefault();
				focusable[focusable.length - 1].focus();
			}
		} else if (index === focusable.length - 1) {
			event.preventDefault();
			focusable[0].focus();
		}
	}

	/**
	 * Move focus to the notice currently on screen, falling back to the dialog itself.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 */
	function focusInto(dialog) {
		const target = visibleNotice(dialog) || dialog;

		if (!target.hasAttribute('tabindex')) {
			target.setAttribute('tabindex', '-1');
		}

		target.focus();
	}

	/**
	 * Get the notice currently on screen inside a dialog.
	 *
	 * Dismissed notices are left in the dialog with display:none, and the newest
	 * notice is always appended last, so walk backwards.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 *
	 * @return {HTMLElement|null} The visible notice, or null when there isn't one.
	 */
	function visibleNotice(dialog) {
		const notices = dialog.querySelectorAll('.courier-notice');

		for (let i = notices.length - 1; i >= 0; i--) {
			if (isRendered(notices[i])) {
				return notices[i];
			}
		}

		return null;
	}

	/**
	 * Whether the dialog is currently shown.
	 *
	 * core.js and dismiss.js toggle both the hide class and an inline display,
	 * and dismiss.js has a path that only sets the inline display, so check both.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 *
	 * @return {boolean} True when the dialog is shown.
	 */
	function isVisible(dialog) {
		return (
			!dialog.classList.contains('hide') &&
			dialog.style.display !== 'none'
		);
	}

	/**
	 * Whether an element takes up space on screen.
	 *
	 * @param {HTMLElement} element The element to test.
	 *
	 * @return {boolean} True when the element is rendered.
	 */
	function isRendered(element) {
		return element.getClientRects().length > 0;
	}

	/**
	 * Get the focused element from the document the dialog belongs to.
	 *
	 * @param {HTMLElement} dialog The dialog element.
	 *
	 * @return {Element|null} The focused element.
	 */
	function activeElement(dialog) {
		return dialog.ownerDocument.activeElement;
	}

	/**
	 * Whether a mutation record added any nodes.
	 *
	 * @param {MutationRecord} mutation The mutation record.
	 *
	 * @return {boolean} True when nodes were added.
	 */
	function hasAddedNodes(mutation) {
		return mutation.addedNodes.length > 0;
	}
}
