import { useRef, useState } from '@wordpress/element';
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import {
	TextControl,
	ToggleControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption
} from '@wordpress/components';
import ConnectionDetails from "./connection-details";

const Connection = ( { tab, meta, updateMeta } ) => {

	return (
		<>
			<fieldset>
				<ToggleControl
					label={__('Pause Shopify Invoice Sync to elite', 'elite-shopify')}
					help={__('No order will push to Elite. All orders processing the during pause will be tagged "pending" in the WooCommerce order list. One syncing is enabled again, all "pending" orders will transfer automatically.', 'elite-shopify')}
					onChange={ ( value ) => updateMeta( '_pause_sync', value ) }
					checked={ meta._pause_sync }
				/>
			</fieldset>
			<fieldset>
				<ToggleControl
					label={__('Pause Inventory and Price Sync from Elite', 'elite-shopify')}
					help={__('No pricing or inventory updates will push to Elite. After re-enabling inventory and price syncing you can force a syncing on the Sync with Elite page', 'elite-shopify')}
					onChange={ ( value ) => updateMeta( '_pause_inventory_price_sync', value ) }
					checked={meta._pause_inventory_price_sync}
				/>
			</fieldset>
			<fieldset>
				<ToggleGroupControl label={__('Connection Type', 'elite-shopify')}
														value={meta._connection_type}
														onChange={ ( value ) => updateMeta( '_connection_type', value ) }
														isBlock>
					<ToggleGroupControlOption value="soap" label={__('Web Services', 'elite-shopify')} />
					<ToggleGroupControlOption value="sftp" label={__('SFTP', 'elite-shopify')} />
					<ToggleGroupControlOption value="ftp" label={__('FTP', 'elite-shopify')} />
				</ToggleGroupControl>
			</fieldset>
			{ meta._connection_type === 'soap' && <ConnectionDetails connectionType="soap" meta={meta} updateMeta={updateMeta} /> }
			{ meta._connection_type === 'sftp' &&
				<>
				<ConnectionDetails connectionType="sftp" meta={meta} updateMeta={updateMeta} />
					{ meta._has_outbound_connection && <ConnectionDetails direction="outbound" connectionType="sftp" meta={meta} updateMeta={updateMeta} /> }
 				</>
			}
			{ meta._connection_type === 'ftp' && <>
				<ConnectionDetails connectionType="ftp" meta={meta} updateMeta={updateMeta} />
				{ meta._has_outbound_connection && <ConnectionDetails direction="outbound" connectionType="ftp" meta={meta} updateMeta={updateMeta} /> }
				</>
			}
		</>
	);
}

export default Connection;
