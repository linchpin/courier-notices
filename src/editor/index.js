/**
 * The block editor experience for courier notices.
 *
 * The Notice document panel: delivery controls (Type, Placement) driven by
 * the REST surface. Presentation lives on the courier/notice block itself -
 * its layout attribute re-frames the canvas and syncs the legacy delivery
 * terms on save.
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

import { buildTermOptions } from './utils';

const POST_TYPE = 'courier_notice';

/**
 * Fetch every term of a taxonomy, unresolved-safe.
 *
 * @param {string} taxonomy Taxonomy name.
 * @return {Array|null} Term records, or null while resolving.
 */
function useAllTerms(taxonomy) {
	return useSelect(
		(select) =>
			select('core').getEntityRecords('taxonomy', taxonomy, {
				per_page: 100,
				hide_empty: false,
				context: 'view',
			}),
		[taxonomy]
	);
}

/**
 * A single-choice taxonomy selector bound to the edited notice.
 *
 * @param {Object} props          Component props.
 * @param {string} props.taxonomy Taxonomy name.
 * @param {string} props.restBase The taxonomy's rest_base on the post object.
 * @param {string} props.label    Control label.
 * @param {string} props.help     Control help text.
 * @return {Object} Element.
 */
function TermSelect({ taxonomy, restBase, label, help }) {
	const terms = useAllTerms(taxonomy);
	const [termIds, setTermIds] = useEntityProp(
		'postType',
		POST_TYPE,
		restBase
	);

	return (
		<SelectControl
			__nextHasNoMarginBottom
			__next40pxDefaultSize
			label={label}
			help={help}
			value={termIds && termIds.length ? termIds[0] : 0}
			options={buildTermOptions(terms, __('Default', 'courier-notices'))}
			onChange={(value) => {
				const termId = parseInt(value, 10);
				setTermIds(termId ? [termId] : []);
			}}
		/>
	);
}

/**
 * The Notice panel in the document sidebar.
 *
 * @return {Object} Element.
 */
function NoticePanel() {
	return (
		<PluginDocumentSettingPanel
			name="courier-notice-settings"
			title={__('Notice', 'courier-notices')}
		>
			<TermSelect
				taxonomy="courier_type"
				restBase="courier-types"
				label={__('Type', 'courier-notices')}
				help={__(
					'What kind of message this is - drives color and icon.',
					'courier-notices'
				)}
			/>
			<TermSelect
				taxonomy="courier_placement"
				restBase="courier-placements"
				label={__('Placement', 'courier-notices')}
				help={__(
					'Where on the page the notice shows.',
					'courier-notices'
				)}
			/>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin('courier-notices-editor', {
	render: NoticePanel,
});
