import _ from "lodash";

import { InnerBlockSlider } from '@humanmade/block-editor-components';
import { useMemo, useState, useEffect } from '@wordpress/element';
import { useSelect, useDispatch, dispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { __, sprintf } from '@wordpress/i18n';
import {
	RichText,
	useBlockProps,
	InspectorControls,
	store as blockEditorDataStore
} from '@wordpress/block-editor';

import { createBlock } from "@wordpress/blocks";
import { Path, SVG } from "@wordpress/primitives";

import {
	TextControl,
	PanelBody,
	CardHeader,
	Card,
	Flex,
	FlexItem,
	FlexBlock,
	Toolbar,
	ToolbarGroup,
	ToolbarButton,
	ToolbarDropdownMenu,
	Icon,
	TabPanel,
	Tooltip,
	SelectControl
} from '@wordpress/components';

import {
	addCard as AddCardIcon,
	seen as SeenIcon
} from "@wordpress/icons";

import { store as preferencesStore } from '@wordpress/preferences';

// Internal
import WarningTooltip from './components/warning-tooltip';

const TAB_LIMIT = 5;
const ALLOWED_BLOCK = 'courier/courier-notice';

/**
 * Provide an interface for editing the block.
 *
 * @param {Object} props Props
 * @return {Element} Formatted blocks.
 */
const Edit = ( props ) => {
	const postType = useSelect((select) => select('core/editor').getCurrentPostType());

	const { toggle, set } = useDispatch( preferencesStore );

	const { clientId, attributes, setAttributes } = props;
	const notices = useSelect( ( select ) =>
		select( noticesStore ).getNotices()
	);

	const {
		allowPromoOverride,
	} = attributes;

	const { createWarningNotice, removeNotice } = useDispatch( noticesStore )

	const innerBlocks = useSelect(
		( select ) => {
			return (
				select( 'core/block-editor' ).getBlock( clientId )
					?.innerBlocks || []
			);
		},
		[ clientId ]
	);

	const blockProps = useBlockProps( {
		className: 'courier-notice-container',
	} );

	const { updateBlockAttributes } = useDispatch( blockEditorDataStore.name );

	const [ currentItemIndex, setCurrentItemIndex ] = useState( 0 );
	const [ overlaps, setOverlaps ] = useState( 0 );

	return (
		<div { ...blockProps }>
			{ postType === 'courier_notice' && <CardHeader as={'div'} size={'small'} style={{padding: '0 16px'}}>
				<Flex>
					<FlexItem>
						<Toolbar
							id="adspiration-banner-toolbar"
							variant="unstyled"
							label={"Options"}
						>
							<ToolbarGroup>
								<ToolbarButton
									icon={<Icon icon={AddCardIcon} />}
									text={__('Add Alternative Size', 'courier-notices')}
									onClick={() => {
										onAddBanner(clientId);
									}}
								/>
								<ToolbarButton
									icon={<SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><Path d="m9.99609 14v-.2251l.00391.0001v6.225h1.5v-14.5h2.5v14.5h1.5v-14.5h3v-1.5h-8.50391c-2.76142 0-5 2.23858-5 5 0 2.7614 2.23858 5 5 5z" /></SVG>}
									text={__('Help', 'adspriation')}
									onClick={( event ) => {

									}}
								/>
							</ToolbarGroup>
							<ToolbarDropdownMenu
								controls={[]}
								icon={<Icon icon={SeenIcon} />}
								label={__("Select Banner Visiblity", 'courier-notices')}
								title={__("Banner Visiblity", 'courier-notices')}
							/>
						</Toolbar>
					</FlexItem>
				</Flex>
			</CardHeader> }
			<InspectorControls>
				<PanelBody
					title={ __( 'Courier Notice Information', 'courier-notices' ) }
				>
					<SelectControl>

					</SelectControl>
					<TextControl
						label="Banner Area Container ID"
						help={ __(
							'Provide a custom ID on the container if needed',
							'courier-notices'
						) }
						value={ attributes.tablistContainerId }
						onChange={ ( tablistContainerId ) =>
							setAttributes( { tablistContainerId } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			{
				<InnerBlockSlider.Controlled
					className={ 'hm-tabs__content' }
					slideLimit={ TAB_LIMIT }
					parentBlockId={ clientId }
					currentItemIndex={ currentItemIndex }
					setCurrentItemIndex={ setCurrentItemIndex }
					showNavigation={ false }
				/>
			}
		</div>
	);
}

export default Edit;
