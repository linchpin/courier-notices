import { registerBlockType } from "@wordpress/blocks";
import {
	Panel,
	PanelBody,
	PanelRow,
	Tip
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';
import { InnerBlocks, useBlockProps} from '@wordpress/block-editor';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { useEffect, useCallback } from '@wordpress/element';

import PromoSelect from '../../components/promo-select';

import './editor.scss';

registerPlugin( 'promo-injection', {
	icon: 'megaphone',
	render: () => {

		const postType = useSelect((select) => select('core/editor').getCurrentPostType());

		if ( 'adspiration_promo' === postType ) {
			return null;
		}

		const postId = useSelect((select) => select('core/editor').getCurrentPostId());
		const { editPost } = useDispatch('core/editor');
		const { setPostMeta } = useDispatch('adspiration/store');

		const selectedPromoId = useSelect((select) => {
			const meta = select('core/editor').getEditedPostAttribute('meta');
			return meta?._adspiration_promo_id;
		}, [postId]);

		const updatePromoContent = (promoId) => {
			setPostMeta(postId, { _adspiration_promo_id: promoId });
			editPost({ meta: { _adspiration_promo_id: promoId } });
		};



		return (
			<PluginDocumentSettingPanel
				name="promo-override"
				title={__( 'Promo Overide', 'adspiration' )}
			>
				<Tip>{__('This option allows you to override a the default selected promo within a page or template', 'adspiration')}</Tip>
				<PromoSelect promoId={selectedPromoId}
							 updatePromoContent={updatePromoContent} />
			</PluginDocumentSettingPanel>
		);
	},
} );
