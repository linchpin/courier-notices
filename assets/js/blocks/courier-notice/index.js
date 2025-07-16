/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { 
	RichText, 
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';
import { 
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getNoticeTypes, getNoticeStyles, getNoticeScopes, getNoticeClasses } from '../shared/utils';

/**
 * Block metadata
 */
import metadata from './block.json';

/**
 * Edit component
 */
function Edit( { attributes, setAttributes } ) {
	const {
		content,
		noticeType,
		noticeStyle,
		scope,
		dismissible,
		showTitle,
		title,
		icon,
	} = attributes;

	const blockProps = useBlockProps( {
		className: getNoticeClasses( attributes ),
		'data-wp-interactive': 'courier-notices',
		'data-wp-context': JSON.stringify( {
			noticeId: `notice-${Math.random().toString(36).substr(2, 9)}`,
			dismissible,
		} ),
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Notice Settings', 'courier-notices' ) }>
					<SelectControl
						label={ __( 'Notice Type', 'courier-notices' ) }
						value={ noticeType }
						options={ getNoticeTypes() }
						onChange={ ( value ) => setAttributes( { noticeType: value } ) }
					/>
					<SelectControl
						label={ __( 'Notice Style', 'courier-notices' ) }
						value={ noticeStyle }
						options={ getNoticeStyles() }
						onChange={ ( value ) => setAttributes( { noticeStyle: value } ) }
					/>
					<SelectControl
						label={ __( 'Scope', 'courier-notices' ) }
						value={ scope }
						options={ getNoticeScopes() }
						onChange={ ( value ) => setAttributes( { scope: value } ) }
					/>
					<ToggleControl
						label={ __( 'Dismissible', 'courier-notices' ) }
						checked={ dismissible }
						onChange={ ( value ) => setAttributes( { dismissible: value } ) }
						help={ __( 'Allow users to dismiss this notice', 'courier-notices' ) }
					/>
					<ToggleControl
						label={ __( 'Show Title', 'courier-notices' ) }
						checked={ showTitle }
						onChange={ ( value ) => setAttributes( { showTitle: value } ) }
						help={ __( 'Display the title of this notice', 'courier-notices' ) }
					/>
					{ showTitle && (
						<TextControl
							label={ __( 'Title', 'courier-notices' ) }
							value={ title }
							onChange={ ( value ) => setAttributes( { title: value } ) }
						/>
					) }
					<TextControl
						label={ __( 'Icon Class', 'courier-notices' ) }
						value={ icon }
						onChange={ ( value ) => setAttributes( { icon: value } ) }
						help={ __( 'CSS class for the icon (e.g., "dashicons-warning")', 'courier-notices' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div className="courier-content-wrapper">
					{ showTitle && title && (
						<RichText
							tagName="h4"
							className="courier-title"
							value={ title }
							onChange={ ( value ) => setAttributes( { title: value } ) }
							placeholder={ __( 'Notice title...', 'courier-notices' ) }
						/>
					) }
					{ icon && (
						<span className={ `courier-icon ${icon}` }></span>
					) }
					<RichText
						tagName="div"
						className="courier-content"
						value={ content }
						onChange={ ( value ) => setAttributes( { content: value } ) }
						placeholder={ __( 'Enter your notice content...', 'courier-notices' ) }
						allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
					/>
					{ dismissible && (
						<button
							className="courier-close close"
							data-wp-on--click="actions.dismissNotice"
						>
							×
						</button>
					) }
				</div>
			</div>
		</>
	);
}

/**
 * Save component
 */
function Save( { attributes } ) {
	const {
		content,
		noticeType,
		dismissible,
		showTitle,
		title,
		icon,
	} = attributes;

	const blockProps = useBlockProps.save( {
		className: getNoticeClasses( attributes ),
		'data-wp-interactive': 'courier-notices',
		'data-wp-context': JSON.stringify( {
			noticeId: `notice-${Math.random().toString(36).substr(2, 9)}`,
			dismissible,
		} ),
		'data-alert': '',
		'data-closable': dismissible ? '' : undefined,
	} );

	return (
		<div { ...blockProps }>
			<div className="courier-content-wrapper">
				{ showTitle && title && (
					<RichText.Content
						tagName="h4"
						className="courier-title"
						value={ title }
					/>
				) }
				{ icon && (
					<span className={ `courier-icon ${icon}` }></span>
				) }
				<RichText.Content
					tagName="div"
					className="courier-content"
					value={ content }
				/>
				{ dismissible && (
					<button
						className="courier-close close"
						data-wp-on--click="actions.dismissNotice"
					>
						×
					</button>
				) }
			</div>
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