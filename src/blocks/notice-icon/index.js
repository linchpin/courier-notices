/**
 * The courier/notice-icon block: the notice's icon, following the notice
 * type by default (alert, info, success…) with a per-notice override.
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { useNoticeTypeSlug } from '../notice/use-notice-type';

const ICONS = [
	{ value: '', label: __('Follow the notice type', 'courier-notices') },
	{ value: 'info', label: __('Info', 'courier-notices') },
	{ value: 'primary', label: __('Primary', 'courier-notices') },
	{ value: 'success', label: __('Success', 'courier-notices') },
	{ value: 'alert', label: __('Alert', 'courier-notices') },
	{ value: 'warning', label: __('Warning', 'courier-notices') },
	{ value: 'feedback', label: __('Feedback', 'courier-notices') },
];

/**
 * Resolve the icon the front end will use, for the canvas preview.
 *
 * The seeded types name their icon after their own slug, so the slug is
 * the honest preview when following the type; the real render reads the
 * type's term meta server-side.
 *
 * @param {string} override Explicit icon attribute.
 * @return {string} Icon name.
 */
function useResolvedIcon(override) {
	const typeSlug = useNoticeTypeSlug();

	return override || typeSlug || 'info';
}

/**
 * Edit component.
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 * @return {Object} Element.
 */
function Edit({ attributes, setAttributes }) {
	const { icon } = attributes;
	const resolved = useResolvedIcon(icon);

	const blockProps = useBlockProps({
		className: `courier-icon icon-${resolved}`,
		'aria-hidden': 'true',
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Notice icon', 'courier-notices')}>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={__('Icon', 'courier-notices')}
						help={__(
							'By default the icon matches the notice type.',
							'courier-notices'
						)}
						value={icon}
						options={ICONS}
						onChange={(value) => setAttributes({ icon: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<span {...blockProps} />
		</>
	);
}

registerBlockType(metadata.name, {
	edit: Edit,
	save: () => null,
});
