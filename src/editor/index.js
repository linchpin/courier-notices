/**
 * The block editor experience for courier notices.
 *
 * The Notice document panel: delivery controls (Type, Placement) driven by
 * the REST surface, plus the behavior meta (Dismissible, Expires).
 * Presentation lives on the courier/notice block itself - its layout
 * attribute re-frames the canvas and syncs the legacy delivery terms on save.
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import {
	BaseControl,
	Button,
	DateTimePicker,
	Dropdown,
	Flex,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { dateI18n, getDate, getSettings } from '@wordpress/date';
import { __ } from '@wordpress/i18n';

import {
	buildTermOptions,
	isTwelveHourFormat,
	pickerValueToTimestamp,
	timestampToPickerValue,
} from './utils';

const POST_TYPE = 'courier_notice';

// PHP-style format the DateTimePicker round-trips on: a wall-clock string with
// no offset, which @wordpress/date reads back in the site timezone.
const PICKER_FORMAT = 'Y-m-d\\TH:i:s';

/**
 * Format a Date in the site timezone as a picker value.
 *
 * @param {Date} date The instant to format.
 * @return {string} Wall-clock string in the site timezone.
 */
const formatForPicker = (date) => dateI18n(PICKER_FORMAT, date);

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
 * Read and write the edited notice's meta.
 *
 * @return {Array} The meta object and a single-key setter.
 */
function useNoticeMeta() {
	const [meta, setMeta] = useEntityProp('postType', POST_TYPE, 'meta');

	const setMetaValue = (key, value) => setMeta({ ...meta, [key]: value });

	return [meta || {}, setMetaValue];
}

/**
 * Whether visitors may close the notice.
 *
 * Sends false when cleared; the server deletes the row rather than storing it,
 * because the dismissible/persistent queries split on EXISTS.
 *
 * @return {Object} Element.
 */
function DismissibleToggle() {
	const [meta, setMetaValue] = useNoticeMeta();

	return (
		<ToggleControl
			__nextHasNoMarginBottom
			label={__('Dismissible', 'courier-notices')}
			help={
				meta._courier_dismissible
					? __(
							'Visitors can close this notice, and it stays closed for them.',
							'courier-notices'
						)
					: __(
							'The notice stays on screen until it expires.',
							'courier-notices'
						)
			}
			checked={!!meta._courier_dismissible}
			onChange={(value) => setMetaValue('_courier_dismissible', value)}
		/>
	);
}

/**
 * When the notice stops displaying.
 *
 * Replaces the vendored 2016 jQuery Timepicker the classic metabox uses. The
 * stored value is UTC seconds; the picker works in the site timezone, which is
 * also how the metabox now reads it.
 *
 * @return {Object} Element.
 */
function ExpirationControl() {
	const [meta, setMetaValue] = useNoticeMeta();
	const settings = getSettings();

	const timestamp = meta._courier_expiration;
	const pickerValue = timestampToPickerValue(timestamp, formatForPicker);

	const setExpiration = (value) =>
		setMetaValue(
			'_courier_expiration',
			pickerValueToTimestamp(value, getDate)
		);

	return (
		<BaseControl
			__nextHasNoMarginBottom
			id="courier-notice-expiration"
			label={__('Expires', 'courier-notices')}
			help={__(
				'The notice stops displaying at this time, whether or not it has been dismissed.',
				'courier-notices'
			)}
		>
			<Dropdown
				contentClassName="courier-notice-expiration__popover"
				popoverProps={{ placement: 'left-start', offset: 36 }}
				renderToggle={({ isOpen, onToggle }) => (
					<Button
						__next40pxDefaultSize
						id="courier-notice-expiration"
						variant="tertiary"
						onClick={onToggle}
						aria-expanded={isOpen}
					>
						{pickerValue
							? dateI18n(
									settings.formats.datetime,
									new Date(parseInt(timestamp, 10) * 1000)
								)
							: __('Never expires', 'courier-notices')}
					</Button>
				)}
				renderContent={() => (
					<Flex direction="column" gap={3} align="stretch">
						<DateTimePicker
							currentDate={pickerValue}
							onChange={setExpiration}
							is12Hour={isTwelveHourFormat(settings.formats.time)}
						/>
						<Button
							variant="tertiary"
							disabled={!pickerValue}
							accessibleWhenDisabled
							onClick={() => setExpiration(null)}
						>
							{__('Clear expiration', 'courier-notices')}
						</Button>
					</Flex>
				)}
			/>
		</BaseControl>
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
			<DismissibleToggle />
			<ExpirationControl />
		</PluginDocumentSettingPanel>
	);
}

registerPlugin('courier-notices-editor', {
	render: NoticePanel,
});
