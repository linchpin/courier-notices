/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './courier-notices-container';
import './courier-notice';

// Register block category for Courier Notices
function addCourierNoticesCategory( categories ) {
	return [
		...categories,
		{
			slug: 'courier-notices',
			title: __( 'Courier Notices', 'courier-notices' ),
			icon: 'warning',
		},
	];
}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'courier-notices/add-category',
	addCourierNoticesCategory
);