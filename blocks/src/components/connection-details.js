import { useRef, useState } from '@wordpress/element';
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __, sprintf } from '@wordpress/i18n';

import {
	PanelBody,
	TextControl,
	TextareaControl,
	SelectControl,
	ToggleControl,
	__experimentalGrid as Grid,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption, Tip
} from '@wordpress/components';

const ConnectionDetails = ( { connectionType, direction, meta, updateMeta } ) => {

	if ( ! connectionType ) {
		return null;
	}

	let directionLabel = '';

	if ( meta._has_outbound_connection ) {
		if ( direction === 'outbound' ) {
		 directionLabel = __( 'Outbound ', 'elite-shopify' );
		} else {
		 directionLabel = __( 'Inbound ', 'elite-shopify' );
		}
	}

	return (
		<div>
			<fieldset>
				<TextControl
					label={ sprintf( __('%1$s%2$s Host', 'elite-shopify'),
						directionLabel,
						connectionType.toUpperCase()
					)}
					value={meta['_' + connectionType + '_host']}
					onChange={( value ) => updateMeta( '_' + connection_type + '_host', value ) }
				/>
			</fieldset>
			<Grid columns={2}>
				<fieldset>
					<TextControl
						label={sprintf(__('%1$s%2$s Username', 'elite-shopify'),
							directionLabel,
							connectionType.toUpperCase()
						)}
						value={meta['_' + connectionType + '_user']}
						onChange={( value ) => updateMeta( '_' + connection_type + '_user', value ) }
					/>
				</fieldset>
				<fieldset>
					<TextControl
						label={sprintf(__('%1$s%2$s Password', 'elite-shopify'),
							directionLabel,
							connectionType.toUpperCase()
						)}
						value={meta['_' + connectionType + '_pass']}
						onChange={( value ) => updateMeta( '_' + connection_type + '_pass', value ) }
					/>
				</fieldset>
			</Grid>
			{ connectionType !== 'soap' &&
				<>
					<Grid>
						<fieldset>
							<TextControl
								label={sprintf(__('%1$s%2$s Folder Path', 'elite-shopify'),
									directionLabel,
									connectionType.toUpperCase())}
								value={meta['_' + connectionType + '_folder_path']}
								onChange={( value ) => updateMeta( '_' + connectionType + '_folder_path', value ) }
							/>
						</fieldset>
						<fieldset>
							<TextControl
								labelPosition="side"
								label={sprintf(__('%1$s%2$s Port', 'elite-shopify'),
									directionLabel,
									connectionType.toUpperCase())}
								value={meta['_' + connectionType + '_port']}
								onChange={( value ) => updateMeta( '_' + connection_type + '_port', value ) }
							/>
						</fieldset>
					</Grid>
				{ connectionType === 'sftp' && <fieldset>
					<TextareaControl
						label={sprintf( __('%1$s%2$s Public Key', 'elite-shopify'),
							directionLabel,
							connectionType.toUpperCase() )}
						value={meta['_' + connectionType + '_public_key']}
						onChange={( value ) => updateMeta( '_' + connection_type +'_public_key', value ) }
					/>
				</fieldset> }
				{ ! direction &&
				<ToggleControl
					label={__('Use a separate connection to send invoices?', 'elite-shopify')}
					help={__('If you need to connect to a different server (or different credentials) enable this option. Only available for FTP and SFTP', 'elite-shopify')}
					onChange={ ( value ) => updateMeta( '_has_outbound_connection', value ) }
					checked={ meta._has_outbound_connection }
				/> }
			</>
			}
		</div>
	);
}

export default ConnectionDetails;
