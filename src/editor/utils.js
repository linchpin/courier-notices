/**
 * Pure helpers for the notice editor panel.
 *
 * The date helpers take their formatter/parser as arguments rather than
 * importing @wordpress/date, so the site-timezone conversion stays testable
 * in the WordPress-free Jest lane.
 */

/**
 * Detect whether a PHP date format renders time in 12-hour form.
 *
 * Escaped characters (`\a`) are literal text, not the meridiem token.
 *
 * @param {string} timeFormat The site's PHP time format.
 * @return {boolean} True when the format carries a meridiem token.
 */
export function isTwelveHourFormat(timeFormat) {
	if (!timeFormat) {
		return true;
	}

	return /a/i.test(timeFormat.replace(/\\./g, ''));
}

/**
 * Convert a stored expiration timestamp into a DateTimePicker value.
 *
 * `_courier_expiration` holds UTC seconds; the picker wants a wall-clock
 * string in the site timezone. Zero and absent both mean "never expires",
 * which the picker represents as null.
 *
 * @param {number|string|undefined} timestamp UTC seconds, or falsy for never.
 * @param {Function}                formatter Formats a Date in the site timezone.
 * @return {string|null} Picker value, or null when the notice never expires.
 */
export function timestampToPickerValue(timestamp, formatter) {
	const seconds = parseInt(timestamp, 10);

	if (!seconds || seconds <= 0) {
		return null;
	}

	return formatter(new Date(seconds * 1000));
}

/**
 * Convert a DateTimePicker value back into a stored expiration timestamp.
 *
 * Returns 0 for "never expires". The server deletes the meta row on 0 rather
 * than storing it, because the display query treats an existing row as an
 * expiry date that has already passed — see Courier_Notices::normalize_notice_meta().
 *
 * @param {string|null} value  Picker value, a wall-clock string in site time.
 * @param {Function}    parser Parses a wall-clock string in the site timezone.
 * @return {number} UTC seconds, or 0 when the notice never expires.
 */
export function pickerValueToTimestamp(value, parser) {
	if (!value) {
		return 0;
	}

	const parsed = parser(value);
	const milliseconds = parsed instanceof Date ? parsed.getTime() : NaN;

	if (isNaN(milliseconds)) {
		return 0;
	}

	return Math.floor(milliseconds / 1000);
}

/**
 * Build SelectControl options from taxonomy term records.
 *
 * @param {Array|null} terms       Term records from core-data, or null while resolving.
 * @param {string}     placeholder Label for the empty option.
 * @return {Array} SelectControl options.
 */
export function buildTermOptions(terms, placeholder) {
	const options = [{ label: placeholder, value: 0 }];

	if (Array.isArray(terms)) {
		terms.forEach((term) => {
			options.push({ label: term.name, value: term.id });
		});
	}

	return options;
}
