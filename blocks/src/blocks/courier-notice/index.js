/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

import apiFetch from '@wordpress/api-fetch';

import api from '@wordpress/api';

/**
 * Use the core data object to load in our promo posts (or other data) into
 * our block.
 *
 * @since 0.1.0
 *
 */
import {dispatch, withSelect} from "@wordpress/data";


import { useEntityProp, store as coreStore } from '@wordpress/core-data';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';

import {InnerBlocks, useBlockProps} from "@wordpress/block-editor";

import {__} from "@wordpress/i18n";

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( 'courier-notices/courier-notice', {

	/**
	 * Use Higher order component to load all of our available promo posts
	 *
	 * @see ./edit.js
	 */
	edit: Edit,
	/**
	 * @note we do not use save because this is a dynamic block
	 */
	save: (props) => {
		useBlockProps.save()
		return null;
	},
	icon: <svg version="1.1" x="0px" y="0px" viewBox="0 0 279.58 340.482">
		<path fill="#EFA500" d="M172.731,131.694l22.402,18.959l-8.295,8.295c-9.638,9.637-13.953,5.323-19.275,0
	c-5.323-5.322-5.428-9.775,0-19.275L172.731,131.694z M67.982,129.617c7.821,7.821,14.161,14.161,28.322,0l34.384-34.384
	c-3.88-3.748-7.662-7.538-11.293-11.35c-8.836-9.276-15.697-17.079-20.825-23.693l-30.587,41.106
	C56.182,117.816,60.162,121.796,67.982,129.617z M144.969,99.335l54.964,46.518l23.442-23.441c9.637-9.637,5.323-13.952,0-19.275
	l0,0c-5.323-5.323-8.894-6.562-19.275,0l-13.341,8.64l-25.393-29.968c-3.36-3.717-6.793-7.371-10.292-10.962l35.298-35.298
	c14.161-14.161,7.822-20.501,0-28.322c-7.82-7.82-11.295-11.295-28.321,0l-43.062,31.103l-0.003,0.004
	c-2.823-2.089-5.325-3.763-7.549-5.078l15.844-15.844c1.451-1.451,1.451-3.803,0-5.254c-1.451-1.451-3.803-1.451-5.254,0
	l-17.74,17.74c-4.259-1.325-6.902-0.569-8.717,1.246c-1.815,1.815-2.571,4.459-1.246,8.717L76.66,57.524
	c-1.451,1.451-1.451,3.804,0,5.254c1.451,1.451,3.803,1.451,5.254,0l15.77-15.77c1.15,1.942,2.57,4.093,4.3,6.483
	c5.095,6.878,12.585,15.517,22.31,25.725C130.789,86.034,137.745,92.803,144.969,99.335z"/>
		<path fill="#43C0F7" d="M257.326,211.659l-53.384-53.384v14.149c0,13.186-10.689,23.875-23.875,23.875v0H0l60.551,72.091L0,340.481
	h121.101v-0.001l58.966,0.001v0h23.875C271.183,340.503,304.873,259.206,257.326,211.659z"/>
		<g>
	<path fill="#FFFFFF" d="M224.579,289.565c-5.843,5.843-13.915,9.457-22.831,9.457c-17.832,0-32.287-14.456-32.287-32.287
		s14.456-32.287,32.287-32.287c8.915,0,16.986,3.613,22.828,9.455l16.957-16.957c-10.182-10.181-24.249-16.479-39.786-16.479
		c-31.076,0-56.268,25.192-56.268,56.268s25.192,56.268,56.268,56.268c15.538,0,29.605-6.298,39.788-16.481L224.579,289.565z"/>
</g>
</svg>

} );
