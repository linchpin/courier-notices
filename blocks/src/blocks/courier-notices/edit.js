import { useRef, useState } from '@wordpress/element';

import { useEntityProp } from '@wordpress/core-data';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {
	useBlockProps,
	InspectorControls
} from '@wordpress/block-editor';


import {
	Panel,
	PanelBody,
	TabPanel
} from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

const Edit = ( {
								 isSelected,
								 className,
								 attributes,
								 setAttributes,
								 context: { postType, postId },
							 } ) => {

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	/**
	 * Dynamically set the meta value based on the field we are passed and the new value
	 * @param field
	 * @param newValue
	 */
	const updateMeta = ( field, newValue ) => {
		setMeta( { ...meta, [field]: newValue } );
	};

	const ref                         = useRef();
	const blockProps                  = useBlockProps( { ref } );
	const [ error, setError ]         = useState( null );
	const [ isLoaded, setIsLoaded ]   = useState( false );
	const [ isLoading, setIsLoading ] = useState( false );

	// Only allow this block to be used on integrations.
	if ( postType !== 'courier_notice' ) {
		return null;
	}

	return (
		<div { ...blockProps }>
			This is my notice template
		</div>
	);
}

export default Edit;
