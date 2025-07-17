const $ = jQuery;

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
			stepMinute: 5,
			oneLine: true,
			// firstDay: 0,
			afterInject: function () {
				$('button.ui-datepicker-current').addClass(
					'button button-secondary'
				);
				$('button.ui-datepicker-close').addClass(
					'right button button-primary'
				);
			},
		});

		if ('courier_notice' === courier_notices_admin_data.post_type) {
			$doc.on('ready', populate_status);
		}

		show_hide_type();
		modal_option_rules();

		$body
			.on('click', '.courier-reactivate-notice', reactivate_notice)
			.on('click', '.copy-text', copy_text)
			.on('change', '#courier_style', show_hide_type)
			.on('change', '#courier_placement_display', change_placement)
			.on('change', '#courier_style', toggle_notice_style_options)
			.on('focus', '#courier-shortcode', function () {
				$('#courier-shortcode').select();
			});
	}

	/**
	 * Show or hide different options based on notice style
	 *
	 * @since 1.3.0
	 */
	function toggle_notice_style_options(event) {
		modal_option_rules(event);
		toggle_show_hide_title_options(event);
	}

	/**
	 * Show/Hde title controls.
	 * Display is dependant on global settings per notice and individual Courier Notice meta
	 *
	 * @since 1.3.0
	 *
	 * @param event
	 */
	function toggle_show_hide_title_options(event) {
		let $this = $('#courier_style'),
			$selected_option = $this.find('option:selected'),
			val = $selected_option.val(),
			label = $selected_option.text(),
			$show_hide_info = $('#show-hide-info'),
			$selected_style = $('#selected-courier-notice-type'),
			enabled_style_titles = $selected_style.attr('data-enable-title');

		$selected_style.text(label);

		$('.show-hide-toggle').removeClass('hide').hide();

		if (enabled_style_titles.indexOf(val) === -1) {
			$show_hide_info.hide();
			$('#show-title-toggle').show();
		} else {
			$show_hide_info.show();
			$('#hide-title-toggle').show();
		}
	}

	/**
	 * Show / Hide rules for Modals
	 *
	 * @since 1.1
	 */
	function modal_option_rules(event) {
		show_hide_placement();
		force_dismissible();
	}

	/**
	 * When showing a modal notice, force the notice to be dismissible
	 *
	 * @since 1.1
	 */
	function force_dismissible() {
		let $this = $('#courier_style');

		if ('popup-modal' === $this.find('option:selected').val()) {
			$('#courier_dismissible')
				.prop('checked', 'checked')
				.addClass('disabled')
				.on('click', function (event) {
					event.stopImmediatePropagation();
					event.preventDefault();
				});
		} else {
			$('#courier_dismissible').removeClass('disabled').off('click');
		}
	}

	/**
	 * Show or hide the "type" dropdown depending on the style of notice
	 * Typically show the type if it's informational
	 *
	 * @since 1.1
	 */
	function show_hide_type(event) {
		let $this = $('#courier_style');

		if ($this.find('option:selected').val() !== 'informational') {
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
	function show_hide_placement(event) {
		let $this = $('#courier_style');

		if ($this.find('option:selected').val() === 'popup-modal') {
			$('#courier_placement').val('popup-modal');
			$('#courier-notice_placement_container').hide();
		} else {
			$('#courier-notice_placement_container').show();
		}
	}

	/**
	 * Show or hide the "placement" dropdown depending on the style of notice
	 * Typically we wouldn't show this for popover/modal notices
	 *
	 * @since 1.1
	 */
	function change_placement(event) {
		let $this = $('#courier_placement_display');
		$('#courier_placement').val($this.val());
	}

	/**
	 * When the page loads, push our custom post status into the post status select.
	 * If that is the current status of the post, select it and push the text to the on screen label.
	 *
	 * @since 1.0
	 */
	function populate_status() {
		var $option = $('<option />')
			.val('courier_expired')
			.text(courier_notices_admin_data.strings.label);

		if (courier_notices_admin_data.post_status === 'courier_expired') {
			$('#post-status-display').text(
				courier_notices_admin_data.strings.expired
			);
			$option.attr('selected', 'selected');
		}

		$('#post_status').append($option);
	}

	/**
	 * Reactivate a notice.
	 * @param event
	 */
	function reactivate_notice(event) {
		event.preventDefault();
		event.stopPropagation();

		let $this = $(this),
			notice_id = $this.attr('data-courier-notice-id'),
			$notice = $this.parents('.notice');

		$.post(
			courier_notices_admin_data.reactivate_endpoint + notice_id + '/',
			{
				success: function (data) {
					$notice.fadeOut();
				},
			}
		);
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
			isIE =
				(!window.ActiveXObject && 'ActiveXObject' in window) ||
				UA.indexOf('MSIE') != -1;

		let copyURL = '';

		if (!isIE) {
			$copy.select();

			try {
				var success = document.execCommand('copy');

				if (success) {
					copyURL = true;
				}

				if (!success) {
					copyURL = prompt(
						courier_notices_admin_data.strings.copy,
						$copy.text()
					);
				}
			} catch (err) {
				copyURL = prompt(
					courier_notices_admin_data.strings.copy,
					$copy.text()
				);
			}
		} else {
			copyURL = prompt(
				courier_notices_admin_data.strings.copy,
				$copy.text()
			);
		}

		if (copyURL) {
			$indicator.text(courier_notices_admin_data.strings.copied).fadeIn();

			setTimeout(function () {
				$indicator.fadeOut(function () {
					$indicator.text('');
				});
			}, 3000);
		}
	}
}
