/**
 * Attempt at making a utility method to setting colors and custom colors without
 * duplicate code all over the place.
 *
 * @since 1.6.0
 *
 * @todo may need some review
 *
 * @param color
 * @param type
 * @return {string|{}|null}
 */
export const setStyleOrClass = ( color, type ) => {

	let cssStyles = {};
	let cssClass  = '';

	if ( color !== undefined ) {

		if ( color.class !== undefined ) {
			cssClass = color.class;
		} else {
			cssStyles.color = color.color;
		}

		switch( type ) {
			case 'class' :
				return cssClass;
			case 'style' :
			default :
				return cssStyles;
		}
	}

	return null;
}


/**
 * Dynamically set the meta value based on the field we are passed and the new value
 *
 * @since 1.6.0
 *
 * @param field
 * @param newValue
 */
export const updateMeta = ( field, newValue ) => {
	setMeta( { ...meta, [field]: newValue } );
};
