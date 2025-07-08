/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { createSlotFill, PanelBody } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import CourierNoticesSidebar from './sidebar';

export const settings = {
	render: () => <CourierNoticesPanel />,
};

export const CourierNoticesPanel = function CourierNoticesPanel( { showUpgradeNudge } ) {

	return (
		<>
			<CourierNoticesSidebar>
				<PanelBody title={ __( 'Social Previews', 'jetpack' ) }>
					<SettingsPanel
						openModal={ () => setIsOpened( true ) }
						showUpgradeNudge={ showUpgradeNudge }
					/>
				</PanelBody>
			</CourierNoticesSidebar>
			<PluginPrePublishPanel title={ __( 'Social Previews', 'jetpack' ) }>
				<SettingsPanel
					openModal={ () => setIsOpened( true ) }
					showUpgradeNudge={ showUpgradeNudge }
				/>
			</PluginPrePublishPanel>
		</>
	);
};
