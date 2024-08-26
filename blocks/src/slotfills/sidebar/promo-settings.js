import { registerBlockType } from "@wordpress/blocks";
import { TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { registerPlugin } from '@wordpress/plugins';
import { InnerBlocks, useBlockProps} from '@wordpress/block-editor';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { useCallback } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';

import './editor.scss';

registerPlugin( 'additional-settings', {
	icon: 'megaphone',
	render: () => {
		const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );

		console.log(postType);

		if ( 'adspiration_promo' !== postType ) {
			return null;
		}

		const { editPost } = useDispatch( 'core/editor' );

		return (
			<PluginDocumentSettingPanel
				name="additional-settings"
				title={__( 'Additional Settings', 'adspiration' )}
			>
				<TextControl
					label={ __( 'Size', 'adspiration' ) }
					value=''
				/>
			</PluginDocumentSettingPanel>
		);
	},
} );
