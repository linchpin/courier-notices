/**
 * The courier/notice block: the notice being authored, rendered with the
 * same markup in the editor and on the front end.
 *
 * Three layouts: informational locks composition to a message (plus the
 * optional title), robust frees the inner blocks entirely, and popup-modal
 * previews as the modal it will display in.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	useBlockProps,
	useInnerBlocksProps,
	InnerBlocks,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { useNoticeTypeSlug } from './use-notice-type';
// Imported for its side effect too: registers the bindings source.
import { SOURCE_NAME } from './bindings';

import '../notice-icon';

const LAYOUTS = [
	{
		value: 'informational',
		label: __('Informational', 'courier-notices'),
	},
	{ value: 'robust', label: __('Robust', 'courier-notices') },
	{ value: 'popup-modal', label: __('Popup / Modal', 'courier-notices') },
];

// Informational notices are an icon, a title and a message. The icon follows
// the notice type unless the author overrides it.
//
// Both text blocks bind their content to the courier/notice source, which is
// what makes an informational notice dynamically adjustable. core/heading and
// core/paragraph are the two blocks this layout defaults to, and `content` on
// both is on core's hardcoded bindable allowlist
// (get_block_bindings_supported_attributes), so this works on the WP 6.8 floor
// without needing the 6.9-only filter that would open up custom attributes.
//
// The two keys behave differently on purpose:
//
// - `title` resolves the notice's post title and is writable, so typing in the
//   heading edits the real post title. This replaces a hand-rolled RichText
//   wired straight to useEntityProp.
// - `message` resolves to nothing by default. Core leaves a bound attribute
//   alone when its source has no value, so the authored paragraph is what
//   renders - staying in post_content, on the legacy path as much as the block
//   path - until courier_notices_binding_value substitutes something.
const INFORMATIONAL_TEMPLATE = [
	['courier/notice-icon'],
	[
		'core/heading',
		{
			level: 6,
			className: 'courier-notice-title',
			placeholder: __('Notice title…', 'courier-notices'),
			metadata: {
				bindings: {
					content: {
						source: SOURCE_NAME,
						args: { key: 'title' },
					},
				},
			},
		},
	],
	[
		'core/paragraph',
		{
			placeholder: __('Write the notice message…', 'courier-notices'),
			metadata: {
				bindings: {
					content: {
						source: SOURCE_NAME,
						args: { key: 'message' },
					},
				},
			},
		},
	],
];

/**
 * Edit component.
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 * @return {Object} Element.
 */
function Edit({ attributes, setAttributes }) {
	const { layout, showTitle } = attributes;

	const locked = 'informational' === layout;
	const typeSlug = useNoticeTypeSlug();

	const blockProps = useBlockProps({
		className:
			`courier-notice courier_notice courier-layout-${layout}` +
			(typeSlug ? ` courier_type-${typeSlug}` : '') +
			// The bound title heading is a locked part of the template, so it
			// is always present; showTitle governs whether it shows. render.php
			// drops it outright, and the canvas hides it, so both sides agree.
			(showTitle ? '' : ' courier-title-hidden'),
	});

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'courier-content' },
		{
			template: INFORMATIONAL_TEMPLATE,
			templateLock: locked ? 'all' : false,
		}
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Notice layout', 'courier-notices')}>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={__('Layout', 'courier-notices')}
						help={__(
							'Informational is a simple message. Robust allows any blocks. Popup / Modal displays in a modal.',
							'courier-notices'
						)}
						value={layout}
						options={LAYOUTS}
						onChange={(value) => setAttributes({ layout: value })}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__('Show title', 'courier-notices')}
						help={__(
							'Notices hide their title by default.',
							'courier-notices'
						)}
						checked={showTitle}
						onChange={(value) =>
							setAttributes({ showTitle: value })
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="courier-content-wrapper">
					<div {...innerBlocksProps} />
					<span className="courier-close close" aria-hidden="true">
						&times;
					</span>
				</div>
			</div>
		</>
	);
}

registerBlockType(metadata.name, {
	edit: Edit,
	// Dynamic block: render.php owns every wrapper on the front end, so
	// only the inner blocks serialize into post_content.
	save: () => <InnerBlocks.Content />,
	variations: [
		{
			name: 'informational',
			title: __('Informational notice', 'courier-notices'),
			description: __(
				'A simple message with an optional title.',
				'courier-notices'
			),
			icon: 'info',
			attributes: { layout: 'informational' },
			scope: ['transform'],
			isActive: ['layout'],
		},
		{
			name: 'robust',
			title: __('Robust notice', 'courier-notices'),
			description: __(
				'Compose the notice from any blocks.',
				'courier-notices'
			),
			icon: 'layout',
			attributes: { layout: 'robust' },
			scope: ['transform'],
			isActive: ['layout'],
		},
		{
			name: 'popup-modal',
			title: __('Popup / Modal notice', 'courier-notices'),
			description: __(
				'Displays in a modal over the page.',
				'courier-notices'
			),
			icon: 'external',
			attributes: { layout: 'popup-modal' },
			scope: ['transform'],
			isActive: ['layout'],
		},
	],
});
