/**
 * Resolve the edited notice's type term slug, for editor previews.
 *
 * The front end reads the term server-side; in the canvas the slug drives
 * the same courier_type-* class and the default icon.
 */
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

/**
 * The selected courier_type slug of the edited notice.
 *
 * @return {string} Term slug, or '' while unresolved / unset.
 */
export function useNoticeTypeSlug() {
	const [typeIds] = useEntityProp(
		'postType',
		'courier_notice',
		'courier-types'
	);

	return useSelect(
		(select) => {
			if (!typeIds || !typeIds.length) {
				return '';
			}

			const term = select('core').getEntityRecord(
				'taxonomy',
				'courier_type',
				typeIds[0]
			);

			return term ? term.slug : '';
		},
		[typeIds]
	);
}
