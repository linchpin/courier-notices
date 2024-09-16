/**
 * External dependencies
 */
import { createSlotFill } from '@wordpress/components';
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
// import './courier-notices-sidebar.scss';
import { CourierNoticesLogo } from './icons';
import CourierNoticesBlockPanel from './block-panel';

const { Fill, Slot } = createSlotFill( 'CourierNoticesSidebar' );

export default function Sidebar(props) {
	return (
		<Fragment>
			<PluginSidebarMoreMenuItem target="couriernotices" icon={ <CourierNoticesLogo /> }>
				Courier Notices
			</PluginSidebarMoreMenuItem>
			<PluginSidebar name="couriernotices" title="Courier Notices" icon={ <CourierNoticesLogo /> }>
				<CourierNoticesBlockPanel />
			</PluginSidebar>
		</Fragment>
	);
}
