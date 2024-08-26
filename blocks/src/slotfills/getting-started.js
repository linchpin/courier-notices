import { addFilter } from '@wordpress/hooks';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

import { registerPlugin } from '@wordpress/plugins';

import { useSelect } from "@wordpress/data";
import { useEntityProp } from '@wordpress/core-data';

import WelcomeGuide from '../components/WelcomeGuide';

const PromoSettingPanel = () => {

	const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );

	const [meta, setMeta] = useEntityProp( 'postType', postType, 'meta' );

	/**
	 * Make sure our banners go 100% width regardless of the container.
	 *
	 * @param settings
	 * @param name
	 * @return {(*&{attributes: (*&{align: {default: string, type: string}})})|*}
	 */
	const modifyDefaultAlignment = ( settings, name ) => {
		if ( name !== 'adspiration/promo' && name !== 'adspiration/promo-banner' && name !== 'adspiration/promo-settings' && name !== 'adspiration/promo-content' ) {
			return settings;
		}

		const newSettings = {
			...settings,
			attributes: {
				...settings.attributes,
				align: { type: 'string', default: 'full' },
			},
		};

		return newSettings;
	};

	addFilter( 'blocks.registerBlockType', 'adspiration/change-default-alignment', modifyDefaultAlignment );

	if ( 'adspiration_promo' !== postType ) {
		return null;
	}

	return (
		<PluginDocumentSettingPanel
			name="promo-getting-started-panel"
			title="Getting Started"
			className="custom-panel"
		>
			<WelcomeGuide />
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'promo-getting-started-panel', { render: PromoSettingPanel, icon: 'megaphone' } );
