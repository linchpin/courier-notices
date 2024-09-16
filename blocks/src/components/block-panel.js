import _ from 'lodash';

import {__} from "@wordpress/i18n";

import {
	ToggleControl,
	Panel,
	PanelBody,
	PanelRow,
	CheckboxControl,
	ColorPalette,
	SelectControl,
	DateTimePicker,
	__experimentalText as Text
} from '@wordpress/components';

import { useDispatch } from '@wordpress/data';
import {
	ColorPaletteControl,
	InspectorControls,
	PanelColorSettings
} from "@wordpress/block-editor";

const CourierNoticesBlockPanel = (props) => {

	const courier_style = "";
	const {
		attributes,
		setAttributes,
		className,
		styles,
		types,
		placements,
		setMeta,
		meta,
		postType,
		postId,
	} = props;

	const { editPost } = useDispatch( 'core/editor' );

	if ( ! styles || ! types || ! placements ) { // If we don't have our terms, don't render
		return null;
	}

	let courierStyles = [];
	let courierTypes  = [];
	let courierPlacements = [];

	// Reformat our options
	_.forOwn(styles,function(item, key) {
		courierStyles.push({ label : item.name, value : item.id });
	});

	// Reformat our options
	_.forOwn(types,function(item, key) {
		courierTypes.push({ label : item.name, value : item.id });
	});

	_.forOwn(placements,function(item, key) {
		courierPlacements.push({ label : item.name, value : item.id });
	});

	const setShowHideTitle = ( newval ) => {
		setAttributes({ showHideTitle: newval });
		setMeta( { ...meta, '_courier_show_title': newval } );
	}

	/**
	 * Set the style
	 * @param newval
	 */
	const setStyle = ( set ) => {
		setAttributes( { style: parseInt(set, 10) } );
		editPost( { 'courier_style' : [parseInt( set, 10 ) ] } );
	}

	/**
	 * Place the notice
	 *
	 * @param set
	 */
	const setPlacement = ( set ) => {
		setAttributes( { placement: set } );
		editPost( { 'courier_placement' : [parseInt( set, 10 ) ] } );
	}

	/**
	 * Set the type of notice, if the notice is informational
	 *
	 * @param set
	 */
	const setType = ( set ) => {
		setAttributes( { type: set } );
		editPost( { 'courier_type' : [parseInt( set, 10 ) ] } );
	}

	let courierStyleInformational = _.find( styles, (obj) => obj.slug === 'informational' );
	let courierStyleModal         = _.find( styles, (obj) => obj.slug === 'popup-modal' );

	return (
		<Panel title={__('Display Settings', 'courier-notices')}>
			<PanelBody>
				<PanelRow>
					<ToggleControl
						label={__('Show Notice Title', 'courier-notices')}
						checked={ meta._showHideTitle ?? false }
						onChange={ ( value ) => {
							setShowHideTitle( ! attributes.showHideTitle );
						} }
					/>
				</PanelRow>
				<PanelRow>
					<SelectControl
						label={__('Display Styles', 'courier-notices')}
						onChange={ setStyle }
						value={ attributes.style }
						options={ courierStyles }
					></SelectControl>
				</PanelRow>
				{
					/* Don't show placement for modal notices
					attributes.style !== courierStyleModal.id &&
					courierTypes &&
					<PanelRow>
						<SelectControl
							label={__('Type', 'courier-notices')}
							onChange={ set => setAttributes( { type: set } ) }
							value={ attributes.type }
							options={ courierTypes }
						></SelectControl>
					</PanelRow> */
				}
				<PanelRow>
					{
						/*
						attributes.style === courierStyleInformational.id &&
						courierPlacements &&
						<SelectControl
							label={__('Placement', 'courier-notices')}
							onChange={ setPlacement }
							value={ attributes.placement }
							options={ courierPlacements }
						></SelectControl> */
					}
				</PanelRow>
			</PanelBody>
			<PanelBody>
				<InspectorControls>
					{ /*}
					<PanelColorSettings
						title={ __('Color Settings', 'courier-notices' ) }
						colorSettings={[
							{
								value: textColor.color,
								onChange: setTextColor,
								label: __( 'Text Color', 'courier-notices' )
							},
							{
								value: backgroundColor.color,
								onChange: setBackgroundColor,
								label: __( 'Background Color', 'courier-notices' )
							},
							{
								value: accentColor.color,
								onChange: setAccentColor,
								label: __( 'Accent Color', 'courier-notices' )
							},
							{
								value: iconColor.color,
								onChange: setIconColor,
								label: __( 'Icon Color', 'courier-notices' )
							},
						]}>
					</PanelColorSettings>*/ }
				</InspectorControls>
			</PanelBody>
			<PanelBody>
				<PanelRow>
					<Text>{__( 'Notice Expiration', 'courier-notices' )}</Text>
				</PanelRow>
				<PanelRow>
					<DateTimePicker
						label={__( 'Notice Expiration', 'courier-notices' )}
						is12Hour={ true }
					/>
				</PanelRow>
			</PanelBody>
		</Panel>
	);
}

export default CourierNoticesBlockPanel;
