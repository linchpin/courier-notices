import jQuery from 'jquery';

let $ = jQuery;

export default function fixed_notices() {
	let $body = $('body');

	init();

	/**
	 * Initialize fixed notices functions
	 */
	function init() {
		var $fixed_header_notices = $('.courier-notices-fixed.courier-location-header'),
			$fixed_footer_notices = $('.courier-notices-fixed.courier-location-footer');

		if ( $fixed_header_notices.length ) {
			courier_display_fixed_notices( $fixed_header_notices, 'header' );
		}

		if ( $fixed_footer_notices.length ) {
			courier_display_fixed_notices( $fixed_footer_notices, 'footer' );
		}
	}

	/**
	 * Get height of fixed notices
	 *
	 * @param location
	 * @returns {string|*}
	 */
	function courier_get_notices_height( location ) {
		if ( 'undefined' === typeof location ) {
			console.error( 'No notice location passed' );

			return '';
		}

		var $notices = $('.courier-notices-fixed.courier-location-' + location);

		if ( ! $notices.length ) {
			console.error( 'No notices exist in ' + location + ' location' );

			return '';
		}

		var notices_height = $notices.height();

		return notices_height;
	}

	/**
	 * Position and display fixed notices
	 *
	 * @param el
	 * @param location
	 * @returns {string}
	 */
	function courier_display_fixed_notices( el, location ) {
		if ( 'undefined' === typeof el || 'undefined' === typeof location ) {
			console.error( 'No element or location set for display fixed notices' );

			return '';
		}

		var $fixed_notices       = $( el ),
			fixed_notices_height = courier_get_notices_height( location );

		if ( 'header' === location ) {
			$fixed_notices.animate({
				top : 0
			}, 'fast').addClass('courier-notices-displayed');

			$body.animate({
				'padding-top' : fixed_notices_height
			}, 'fast');
		}

		if ( 'footer' === location ) {
			$fixed_notices.animate({
				bottom : 0
			}, 'fast').addClass('courier-notices-displayed');

			$body.animate({
				'padding-bottom' : fixed_notices_height
			}, 'fast');
		}
	}

	/**
	 * Callback on notice dismissal to refactor height/spacing of fixed notices
	 *
	 * @param location
	 * @returns {string}
	 */
	function courier_refactor_fixed_padding( location ) {
		if ( 'undefined' === typeof location ) {
			console.error( 'No notice location passed' );

			return '';
		}

		var fixed_notice_height = courier_get_notices_height( location );

		if ( 'header' === location ) {
			$body.animate({
				'padding-top' : fixed_notice_height
			}, 'fast');
		}

		if ( 'footer' === location ) {
			$body.animate({
				'padding-bottom' : fixed_notice_height
			}, 'fast');
		}
	}
}