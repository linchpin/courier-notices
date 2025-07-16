/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { 
	PanelBody,
	SelectControl,
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import { getNoticePlacements } from '../shared/utils';

/**
 * Block metadata
 */
import metadata from './block.json';

/**
 * Edit component
 */
function Edit( { attributes, setAttributes } ) {
	const { placement } = attributes;
	const blockProps = useBlockProps( {
		className: `courier-notices courier-location-${placement}`,
	} );

	const ALLOWED_BLOCKS = [ 'courier-notices/courier-notice' ];
	const TEMPLATE = [
		[ 'courier-notices/courier-notice', {} ],
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Notice Container Settings', 'courier-notices' ) }>
					<SelectControl
						label={ __( 'Placement', 'courier-notices' ) }
						value={ placement }
						options={ getNoticePlacements() }
						onChange={ ( value ) => setAttributes( { placement: value } ) }
						help={ __( 'Where should these notices be displayed on the frontend?', 'courier-notices' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div className="courier-notices-editor-header">
					<h3>{ __( 'Courier Notices Container', 'courier-notices' ) }</h3>
					<p>{ __( `Placement: ${placement}`, 'courier-notices' ) }</p>
				</div>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
					templateLock={ false }
				/>
			</div>
		</>
	);
}

/**
 * Save component
 */
function Save( { attributes } ) {
	const { placement } = attributes;
	const blockProps = useBlockProps.save( {
		className: `courier-notices courier-location-${placement}`,
		'data-courier-placement': placement,
		'data-courier-ajax': 'false',
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}

/**
 * Register block
 */
registerBlockType( metadata.name, {
	edit: Edit,
	save: Save,
} );