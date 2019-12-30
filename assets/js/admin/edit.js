import jQuery from 'jquery';

let $ = jQuery;

export default function edit() {

	let $doc = $(document),
		$body = $('body');

	init();

	/**
	 * Initialize our dismiss
	 * Add our events
	 *
	 * @since 1.0
	 */
	function init() {

		$('#courier_expire_date').datetimepicker({
			minDate: 0,
			controlType: 'select',
			timeFormat: 'hh:mm tt',
			dateFormat: 'MM dd, yy',
			oneLine: true,
			// firstDay: 0,
			afterInject: function () {
				$('button.ui-datepicker-current').addClass('button button-secondary');
				$('button.ui-datepicker-close').addClass('right button button-primary');
			}
		});

		if ('courier_notice' === courier_admin_data.post_type) {
			$doc
				.on('ready', populate_status);
		}

		$body
			.on('click', '.courier-reactivate-notice', reactivate_notice )
			.on('click', '.copy-text', copy_text )
			.on('change', '#courier_style', show_hide_type )
			.on('change', '#courier_style', show_hide_placement )
			.on('focus', '#courier-shortcode', function () {
				$('#courier-shortcode').select();
			});
	}

	/**
	 * Show or hide the "type" dropdown depending on the style of notice
	 * Typically show the type if it's informational
	 *
	 * @since 1.1
	 */
	function show_hide_type( event ) {

		let $this = $(this);

		if ( $this.find('option:selected').val() !== 'informational' ) {
			$('#courier-notice_type_container').hide();
		} else {
			$('#courier-notice_type_container').show();
		}
	}

	/**
	 * Show or hide the "placement" dropdown depending on the style of notice
	 * Typically we wouldn't show this for popover/modal notices
	 *
	 * @since 1.1
	 */
	function show_hide_placement( event ) {
		let $this = $(this);

		if ( $this.find('option:selected').val() !== 'informational' ) {
			$( '#courier-notice_placement_container' ).hide();
		} else {
			$( '#courier-notice_placement_container' ).show();
		}
	}

	/**
	 * When the page loads, push our custom post status into the post status select.
	 * If that is the current status of the post, select it and push the text to the on screen label.
	 *
	 * @since 1.0
	 */
	function populate_status() {
		var $option = $('<option />').val('courier_expired').text(courier_admin_data.strings.label);

		if (courier_admin_data.post_status === 'courier_expired') {
			$('#post-status-display').text(courier_admin_data.strings.expired);
			$option.attr('selected', 'selected');
		}

		$('#post_status').append($option);
	}

	/**
	 * Reactivate a notice.
	 * @param event
	 */
	function reactivate_notice( event ) {
		event.preventDefault();
		event.stopPropagation();

		var $this = $(this),
			notice_id = $this.attr('data-courier-notice-id'),
			$notice = $this.parents('.notice');

		$.post(courier_admin_data.reactivate_endpoint + notice_id + '/', {
			success: function (data) {
				$notice.fadeOut();
			}
		});
	}

	/**
	 * Copy the shortcode
	 *
	 * @param event
	 */
	function copy_text(event) {
		event.preventDefault();

		let $this = $(this),
			$copyID = $this.data('copy'),
			$copy = $('#' + $copyID),
			$indicator = $('.copy-link-indicator'),
			UA = navigator.userAgent,
			isIE = (!(window.ActiveXObject) && "ActiveXObject" in window) || (UA.indexOf('MSIE') != -1);

		let copyURL = '';

		if ( ! isIE ) {
			$copy.select();

			try {
				var success = document.execCommand('copy');

				if (success) {
					copyURL = true;
				}

				if ( ! success ) {
					copyURL = prompt( courier_admin_data.strings.copy, $copy.text() );
				}
			} catch ( err ) {
				copyURL = prompt( courier_admin_data.strings.copy, $copy.text() );
			}
		} else {
			copyURL = prompt( courier_admin_data.strings.copy, $copy.text() );
		}

		if ( copyURL ) {
			$indicator.text(courier_admin_data.strings.copied).fadeIn();

			setTimeout(function () {
				$indicator.fadeOut( function () {
					$indicator.text('');
				} );
			}, 3000 );
		}
	}
}
