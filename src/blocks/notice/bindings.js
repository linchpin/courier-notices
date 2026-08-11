/**
 * The courier/notice block bindings source, editor side.
 *
 * Mirrors includes/Controller/Block_Bindings.php so the canvas resolves the
 * same values the front end does. Registered under the same source name.
 *
 * `message` deliberately resolves to undefined: core leaves a bound attribute
 * alone when the source has no value, so the authored paragraph content is
 * what shows. The server-side courier_notices_binding_value filter is where a
 * dynamic message comes from, which keeps the notice authored by default.
 */
import { registerBlockBindingsSource } from '@wordpress/blocks';
import { store as coreDataStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const POST_TYPE = 'courier_notice';

export const SOURCE_NAME = 'courier/notice';

// Read-only keys are derived from the notice, not authored through the block.
const EDITABLE_KEYS = ['title'];

/**
 * Resolve the notice record for the block's context.
 *
 * @param {Function} select  Data registry select.
 * @param {Object}   context Block context.
 * @return {Object|undefined} The edited notice record.
 */
function getNotice(select, context) {
	if (!context?.postId) {
		return undefined;
	}

	return select(coreDataStore).getEditedEntityRecord(
		'postType',
		POST_TYPE,
		context.postId
	);
}

registerBlockBindingsSource({
	name: SOURCE_NAME,
	label: __('Courier Notice', 'courier-notices'),
	usesContext: ['postId'],

	getValues({ select, context, bindings }) {
		const notice = getNotice(select, context);
		const values = {};

		// Only `title` resolves in the canvas. `message` stays undefined so
		// the authored paragraph content shows, and `type` / `expiration` are
		// resolved server side — surfacing a raw key in the canvas would read
		// worse than leaving the placeholder.
		Object.entries(bindings || {}).forEach(([attribute, binding]) => {
			if ('title' === binding?.args?.key) {
				values[attribute] = notice?.title;
			}
		});

		return values;
	},

	setValues({ dispatch, context, bindings }) {
		if (!context?.postId) {
			return;
		}

		Object.values(bindings || {}).forEach(({ args, newValue } = {}) => {
			if ('title' !== args?.key) {
				return;
			}

			dispatch(coreDataStore).editEntityRecord(
				'postType',
				POST_TYPE,
				context.postId,
				{ title: newValue }
			);
		});
	},

	canUserEditValue({ select, context, args }) {
		if (!context?.postId || !EDITABLE_KEYS.includes(args?.key)) {
			return false;
		}

		return !!getNotice(select, context);
	},
});
