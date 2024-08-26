/**
 * Component to Select a Promo, used in the block settings
 * as well as pages that are using the promo block
 */
import {useSelect, useDispatch} from "@wordpress/data";
import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import InserterNoResults from "../blocks/promo/components/no-results";
import {SelectControl} from "@wordpress/components";

const PromoSelect = ( props ) => {

	const { availablePromos, selectedPromoId } = useSelect( ( select ) => {
		const promos = select('adspiration/store').getAvailablePromos();
		const availableChoices = [];
		const selectedPromoId = select('adspiration/store').getSelectedPromoId();

		if ( promos && promos.length > 0 ) {
			availableChoices.push( { value: 0, label: __('Select a Promo', 'adspiration') });
			promos.forEach( ( post ) => {
				availableChoices.push( { value: post.id, label: post.title.rendered } );
			});
		}

		return { availablePromos: availableChoices, selectedPromoId };

	}, [] );

	const { setSelectedPromoId } = useDispatch('adspiration/store');

	const onChange = (promoId) => {
		setSelectedPromoId(promoId);
		props.updatePromoContent(promoId);
	};

	if ( ! availablePromos ) {
		return <InserterNoResults />
	}

	return (
		<>
			<SelectControl
				label={ __('Select a Promo', 'adspiration') }
				options={availablePromos}
				value={props.promoId}
				onChange={onChange}
			/>
		</>

	)

}

export default PromoSelect;
