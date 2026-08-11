/**
 * The block editor experience for courier notices.
 *
 * Replaces the classic Notice Information metabox with a document settings
 * panel driven by the REST surface (COURIER-1035), and keeps the editor
 * canvas notice-shaped: the selected style re-frames the preview live —
 * informational renders as the notice bar, popup-modal as a centered card.
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { buildTermOptions, termSlug } from './utils';

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
 * Mirror the selected style onto the editor canvas so the preview re-frames
 * live. The canvas may or may not be iframed depending on what else renders
 * on the screen, so both documents are handled; this is editor-only sugar
 * and fails silently if the canvas is not there yet.
 *
 * @return {null} Renders nothing.
 */
function StylePreviewSync() {
	const styles = useAllTerms('courier_style');
	const [styleIds] = useEntityProp('postType', POST_TYPE, 'courier-styles');
	const slug =
		termSlug(styles, styleIds && styleIds.length ? styleIds[0] : 0) ||
		'informational';

	useEffect(() => {
		const iframe = document.querySelector('iframe[name="editor-canvas"]');
		const canvasDocument =
			iframe && iframe.contentDocument
				? iframe.contentDocument
				: document;
		const wrapper = canvasDocument.querySelector('.editor-styles-wrapper');

		if (wrapper) {
			wrapper.setAttribute('data-courier-style', slug);
		}
	}, [slug]);

	return null;
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
				taxonomy="courier_style"
				restBase="courier-styles"
				label={__('Style', 'courier-notices')}
				help={__(
					'How the notice presents - the editor preview follows.',
					'courier-notices'
				)}
			/>
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
			<StylePreviewSync />
		</PluginDocumentSettingPanel>
	);
}

registerPlugin('courier-notices-editor', {
	render: NoticePanel,
});
