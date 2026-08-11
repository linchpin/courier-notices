/**
 * The courier/notices outlet block: a region where notices appear.
 *
 * Distinct from courier/notice, which IS a notice. This block is only a mount
 * point, so it has no inner blocks and renders a preview in the editor rather
 * than live notices - the notices a visitor sees depend on their page and
 * user, which the editor cannot know.
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	Placeholder,
	RangeControl,
	SelectControl,
} from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

import metadata from './block.json';

const LOADING_OPTIONS = [
	{
		value: 'lazy',
		label: __('Lazy (recommended)', 'courier-notices'),
	},
	{ value: 'server', label: __('Server-rendered', 'courier-notices') },
];

const PLACEMENT_OPTIONS = [
	{ value: 'header', label: __('Header', 'courier-notices') },
	{ value: 'footer', label: __('Footer', 'courier-notices') },
	{ value: 'popup-modal', label: __('Pop Over / Modal', 'courier-notices') },
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
	const { loading, placement, number } = attributes;
	const blockProps = useBlockProps();

	const placementLabel =
		PLACEMENT_OPTIONS.find((option) => option.value === placement)?.label ||
		placement;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Notices region', 'courier-notices')}>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={__('Placement', 'courier-notices')}
						help={__(
							'Which notices this region shows. Matches the Placement set on each notice.',
							'courier-notices'
						)}
						value={placement}
						options={PLACEMENT_OPTIONS}
						onChange={(value) =>
							setAttributes({ placement: value })
						}
					/>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={__('Loading', 'courier-notices')}
						help={
							'server' === loading
								? __(
										'Rendered with the page. Only for sites without full-page caching - a cached page would serve one visitor’s notices to everyone.',
										'courier-notices'
									)
								: __(
										'The region is fetched after the page loads, so it stays correct behind a full-page cache. Set a minimum height if it sits above the fold.',
										'courier-notices'
									)
						}
						value={loading}
						options={LOADING_OPTIONS}
						onChange={(value) => setAttributes({ loading: value })}
					/>
					<RangeControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={__('Maximum notices', 'courier-notices')}
						help={__(
							'How many notices this region shows at once.',
							'courier-notices'
						)}
						min={1}
						max={20}
						value={number}
						onChange={(value) =>
							setAttributes({ number: parseInt(value, 10) || 1 })
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<Placeholder
					icon="megaphone"
					label={__('Courier Notices', 'courier-notices')}
					instructions={sprintf(
						/* translators: %s: placement label, e.g. Header. */
						__(
							'%s notices appear here on the front end. What a visitor sees depends on their page and account, so there is nothing to preview.',
							'courier-notices'
						),
						placementLabel
					)}
				/>
			</div>
		</>
	);
}

registerBlockType(metadata.name, {
	edit: Edit,
	// Dynamic block: render.php owns the front end entirely.
	save: () => null,
});
