import { useRef, useState } from '@wordpress/element';
import { useSelect } from "@wordpress/data";

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import {
	TextControl,
	TextareaControl,
	SelectControl,
	SearchControl,
	Spinner,

	__experimentalNumberControl as NumberControl
} from '@wordpress/components';

const GeneralSettings = ( { tab, meta, updateMeta } ) => {

	return (
		<>
			<fieldset>
				<TextareaControl
					label={__( 'Error Notifications', 'elite-shopify')}
					value={meta._email_notifications} />
			</fieldset>
			<fieldset>
				<TextControl
					label={__('Inventory Sync Cron Schedule', 'elite-shopify')}
					value={meta._inventory_cron_schedule} />
			</fieldset>
			<fieldset>
				<TextControl
					label={__('Invoice Sync Cron Schedule', 'elite-shopify')}
					value={meta._invoice_cron_schedule} />
			</fieldset>
			<fieldset>
				<NumberControl
					isShiftStepEnabled
					label={__('Retry Schedule', 'elite-shopify')}
					max={60}
					min={5}
					placeholder="5"
					shiftStep={10}
					step="5"
					value={meta._failure_pause_timer}
				/>
			</fieldset>
			<fieldset>
				<SelectControl
					label={__('Log Cleanup Schedule', 'elite-shopify')}
					options={[
						{ label: 'Daily', value: 1 },
						{ label: 'Weekly', value: 7 },
						{ label: 'Monthly', value: 30 },
						{ label: 'Quarterly', value: 90 },
						{ label: 'Bi-Yearly', value: 183 },
						{ label: 'Yearly', value: 365 },
					]}
					value={meta._log_cleanup_schedule}
					/>
			</fieldset>
		</>
	);
}

export default GeneralSettings;
