/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Get available notice types from the REST API
 * 
 * @return {Array} Array of notice types
 */
export const getNoticeTypes = () => {
	// This would ideally fetch from REST API, but for now we'll use hardcoded values
	// matching the current plugin taxonomy
	return [
		{ value: 'informational', label: __( 'Informational', 'courier-notices' ) },
		{ value: 'success', label: __( 'Success', 'courier-notices' ) },
		{ value: 'warning', label: __( 'Warning', 'courier-notices' ) },
		{ value: 'error', label: __( 'Error', 'courier-notices' ) },
	];
};

/**
 * Get available notice styles from the REST API
 * 
 * @return {Array} Array of notice styles
 */
export const getNoticeStyles = () => {
	return [
		{ value: 'informational', label: __( 'Informational', 'courier-notices' ) },
		{ value: 'modal', label: __( 'Modal', 'courier-notices' ) },
		{ value: 'banner', label: __( 'Banner', 'courier-notices' ) },
	];
};

/**
 * Get available notice placements
 * 
 * @return {Array} Array of notice placements
 */
export const getNoticePlacements = () => {
	return [
		{ value: 'header', label: __( 'Header', 'courier-notices' ) },
		{ value: 'footer', label: __( 'Footer', 'courier-notices' ) },
		{ value: 'popup-modal', label: __( 'Modal/Popup', 'courier-notices' ) },
		{ value: 'inline', label: __( 'Inline', 'courier-notices' ) },
	];
};

/**
 * Get available notice scopes
 * 
 * @return {Array} Array of notice scopes
 */
export const getNoticeScopes = () => {
	return [
		{ value: 'global', label: __( 'Global', 'courier-notices' ) },
		{ value: 'user', label: __( 'User Specific', 'courier-notices' ) },
		{ value: 'role', label: __( 'Role Specific', 'courier-notices' ) },
	];
};

/**
 * Generate class names for notice wrapper
 * 
 * @param {Object} attributes Block attributes
 * @return {string} Generated class names
 */
export const getNoticeClasses = ( attributes ) => {
	const {
		noticeType = 'informational',
		noticeStyle = 'informational',
		dismissible = true,
		className = '',
	} = attributes;

	const classes = [
		'courier-notice',
		'courier_notice',
		'alert',
		'alert-box',
		`courier_type-${noticeType}`,
		`courier_style-${noticeStyle}`,
		className,
	];

	if ( dismissible ) {
		classes.push( 'courier-notice-dismissible' );
	}

	return classes.filter( Boolean ).join( ' ' );
};