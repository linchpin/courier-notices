import { useRef, useState } from '@wordpress/element';
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import {
	PanelBody,
	TextControl,
	TextareaControl,
	SelectControl,
} from '@wordpress/components';

const Sync = ( { tab, meta, updateMeta } ) => {
	return (
		<>
			<p>Sync a bidness</p>
		</>
	);
}

export default Sync;
