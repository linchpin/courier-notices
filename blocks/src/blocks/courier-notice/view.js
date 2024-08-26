import { store, getContext } from '@wordpress/interactivity';
import { __ } from '@wordpress/i18n';

const { state } = store( 'adspiration', {
	state: {
		sizes: [
			{
				'label':'Default Size',
				'displayOptions': [

				]
			}
		],
		get hasSizes() {
			return state.sizes.length > 0;
		},
		get allSizes() {
			return state.sizes;
		}
	},
	actions: {
		click: ( event ) => {

		}
	}
} );
