import { registerStore } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

const DEFAULT_STATE = {
	availablePromos: [],
	selectedPromoId: null,
	postMeta: {},
};

const actions = {
	setAvailablePromos(promos) {
		return {
			type: 'SET_AVAILABLE_PROMOS',
			promos,
		};
	},
	setSelectedPromoId(postId) {
		return {
			type: 'SET_SELECTED_PROMO_ID',
			postId,
		};
	},
	setPostMeta(postId, meta) {
		return {
			type: 'SET_POST_META',
			postId,
			meta,
		};
	},
	fetchAvailablePromos() {
		return {
			type: 'FETCH_AVAILABLE_PROMOS',
		};
	},
	fetchPostMeta(postId) {
		return {
			type: 'FETCH_POST_META',
			postId,
		};
	},
};

const store = registerStore('adspiration/store', {
	reducer(state = DEFAULT_STATE, action) {
		switch (action.type) {
			case 'SET_AVAILABLE_PROMOS':
				return {
					...state,
					availablePromos: action.promos,
				};
			case 'SET_SELECTED_PROMO_ID':
				return {
					...state,
					selectedPromoId: action.promoId,
				};
			case 'SET_POST_META':
				return {
					...state,
					postMeta: {
						...state.postMeta,
						[action.postId]: action.meta,
					},
				};
			default:
				return state;
		}
	},
	actions,
	selectors: {
		getAvailablePromos(state) {
			return state.availablePromos;
		},
		getSelectedPromoId(state) {
			return state.selectedPromoId;
		},
		getPostMeta(state, postId) {
			return state.postMeta[postId] || {};
		},
	},
	controls: {
		FETCH_AVAILABLE_PROMOS() {
			let query = {
				per_page: 100,
				orderby: 'title',
				order: 'asc',
				status: 'publish',
			};

			console.log(addQueryArgs( `/wp/v2/adspiration_promo`, query ) )

			return apiFetch({ path: addQueryArgs( '/wp/v2/adspiration_promo', query ) })
				.then((promos) => {
					actions.setAvailablePromos(promos)
					return promos;
				})
				.catch((error) => {
					console.error('Error fetching promos:', error);
					return actions.setAvailablePromos([]); // Handle error by setting empty promos
				});
		},
		FETCH_POST_META({ postId }) {
			return apiFetch({ path: `/wp/v2/posts/${postId}/meta` }).then((meta) => actions.setSelectedPromoId(meta._adspiration_promo_id));
		},
	},
	resolvers: {
		*getAvailablePromos() {
			const promos = yield actions.fetchAvailablePromos();
			return actions.setAvailablePromos(promos);
		},
		*getPostMeta(postId) {
			const meta = yield actions.fetchPostMeta(postId);
			return actions.setPostMeta(postId, meta);
		},
	},
});
