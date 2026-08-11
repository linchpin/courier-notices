/**
 * The notice block's layouts, shared between the block and the Notice panel.
 *
 * A notice post holds exactly one courier/notice block (multiple: false, and
 * the CPT template locks the root), so layout is really a property of the
 * notice rather than of a block among many. The control therefore lives in the
 * Notice document panel and writes through to the block's attribute - this
 * module is what keeps the two from drifting.
 */
import { __ } from '@wordpress/i18n';

export const NOTICE_BLOCK_NAME = 'courier/notice';

export const LAYOUT_OPTIONS = [
	{
		value: 'informational',
		label: __('Informational', 'courier-notices'),
	},
	{ value: 'robust', label: __('Robust', 'courier-notices') },
	{ value: 'popup-modal', label: __('Popup / Modal', 'courier-notices') },
];
