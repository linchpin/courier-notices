import jQuery from 'jquery';

let $ = jQuery;

export default function settings() {

	// Extend Serialize Array to also include checkboxes that are not selected
	(function ($) {
		let _base_serializeArray = $.fn.serializeArray;

		/**
		 * extract the field name based on key
		 *
		 * @param fieldName
		 * @return {*}
		 */
		function cleanupFieldName( fieldName ) {

			let matches = fieldName.match(/\[([a-zA-Z_]*)\]/);

			if ( matches ) {
				return matches[1];
			}

			return fieldName;
		}

		$.fn.serializeArray = function () {

			let originalArray = _base_serializeArray.apply(this);
			let dataCleanup   = {};

			$.each( this.find("input"), function (i, element) {

				if ( "checkbox" === element.type || "radio" === element.type ) {
					element.checked
						? originalArray[i].value = true
						: originalArray.splice( i, 0, { name: element.name, value: false })
				}

				let fieldName = cleanupFieldName( element.name );

				if ( dataCleanup[ fieldName ] !== undefined ) { // The field exists, if it's not an array create one
					if ( ! Array.isArray( dataCleanup[ fieldName ] ) ) {
						if ( false !== dataCleanup[ fieldName ] ) {
							dataCleanup[ fieldName ] = [ dataCleanup[ fieldName ] ];
						} else {
							dataCleanup[ fieldName ] = [];
						}
					}

					if ( false !== element.value && element.checked ) {
						dataCleanup[ fieldName ].push( element.value );
					}
				} else {

					if ( "checkbox" === element.type || "radio" === element.type ) {
						if ( element.checked ) {
							if ( false !== element.value ) {
								dataCleanup[ fieldName ] = element.value;
							} else {
								dataCleanup[ fieldName ] = false;
							}
						} else {
							dataCleanup[ fieldName ] = false;
						}
					} else {
						dataCleanup[ fieldName ] = element.value;
					}
				}
			});

			for( const field in dataCleanup ) {
				if ( typeof dataCleanup[field] !== 'object' ) {
					continue;
				}

				if ( 0 === dataCleanup[field].length ) {
					dataCleanup[field] = false;
				}
			}

			return dataCleanup;
		};
	})(jQuery);

    setup_forms();

	/**
	 * Convert our form data to a json object
	 *
	 * @param form
	 * @return {{}}
	 */
	function formDataToJSON( form ) {
		let formData = $(form).serializeArray();

		return formData;
	}

    /**
     * Initialize our dismiss
     * Add our events
	 *
	 * @since 1.0
     */
    function setup_forms() {

    	let $settings_form = $( '.courier-notices-settings-form' );

		$settings_form.find( '#submit' ).on('click', function(event ) {
			event.preventDefault();

			$(this).attr({
				'disabled':'disabled',
				'value' : 'Saving',
			})
			.parents('form').submit();
		});

		$settings_form.on( 'submit', function(event) {
			event.preventDefault();
			event.stopImmediatePropagation();

			let $form = $(this);
			let formData = formDataToJSON(this);
				formData['settings_key'] = formData['option_page'];
				formData['method'] = 'POST';
				formData._wpnonce = courier_notices_admin_data.settings_nonce;
			$.ajax( {
				'url' :	courier_notices_admin_data.settings_endpoint,
				'beforeSend' : function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', formData._wpnonce );
				},
				'method' : 'POST',
				'data' : formData,
			} ).done(function ( response ) {
				$settings_form.find( '#submit' ).attr({
					'value' : 'Save Changes',
				}).removeAttr('disabled');
			});
		} );
    }
}
