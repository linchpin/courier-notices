import {__} from "@wordpress/i18n";
import {ReactComponent as InformationalNotice} from "../icons/courier-informational.svg";
import {ReactComponent as FlyoutNotice} from "../icons/courier-flyout.svg";
import {ReactComponent as ModalNotice} from "../icons/courier-modal.svg";

import { useDispatch, useSelect } from '@wordpress/data';

import {
	ToolbarDropdownMenu,
} from '@wordpress/components';

const TypeControls = ( props ) => {

	const { styles } = props;
	const { editPost, editEntityRecord } = useDispatch( 'core/editor' );

	const courier_style = useSelect((select) => {
		return select('core').getEntityRecords( 'taxonomy', 'courier_style' );
	});

	/**
	 * Change the style of the notice
	 * @param style_term_id
	 */
	const setStyle = ( style_term_id ) => {
		editEntityRecord( 'taxonomy', 'courier_style', [parseInt( style_term_id, 10 ) ]  );
		editPost( { 'courier_style' : [parseInt( style_term_id, 10 ) ] } );
	}

	/**
	 * Icons related to the type of notice
	 *
	 * @param name
	 * @returns {*}
	 */
	const getIcon = ( name ) => {
		switch( name ) {
			case __( 'Informational', 'courier-notices' ):
				return InformationalNotice;
			case __( 'Slide-In', 'courier-notices' ):
			case __( 'Flyout', 'courier-notices' ):
				return FlyoutNotice;
			case __( 'Pop Over / Modal', 'courier-notices' ):
			case __( 'Modal', 'courier-notices' ):
				return ModalNotice;
		}
	}

	if ( ! styles ) {
		return null;
	}

	let courierStyles = [];

	_.forOwn( styles,( item, key ) => {
		courierStyles.push({
			title: item.name,
			value: item.id,
			icon: getIcon(item.name),
			onClick: () => {
				setStyle(item.id)
			}
		});
	});

	let activeIcon;

	if ( ! courier_style ) {
		activeIcon = _.find( courierStyles, (obj) => obj.value === courierStyles[0].value );
	} else {
		activeIcon = _.find( courierStyles, (obj) => obj.value === parseInt( courier_style, 10 ) );
	}

	if ( ! activeIcon || ! courier_style ) {
		activeIcon = {}
		activeIcon.icon = InformationalNotice;
	}

	return (
		<ToolbarDropdownMenu
			label={__('Select the style of the notice you want to display', 'courier-notices')}
			controls={courierStyles}
			icon={activeIcon.icon}
		/>
	);
}

import _ from "lodash";

export default TypeControls;
