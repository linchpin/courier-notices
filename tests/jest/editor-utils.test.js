/**
 * Tests for the notice editor panel helpers.
 */
import {
	buildTermOptions,
	isTwelveHourFormat,
	pickerValueToTimestamp,
	timestampToPickerValue,
} from '../../src/editor/utils';

describe('buildTermOptions', () => {
	test('prepends the placeholder and maps terms to options', () => {
		const options = buildTermOptions(
			[
				{ id: 5, name: 'Informational', slug: 'informational' },
				{ id: 9, name: 'Pop Over / Modal', slug: 'popup-modal' },
			],
			'Default'
		);

		expect(options).toEqual([
			{ label: 'Default', value: 0 },
			{ label: 'Informational', value: 5 },
			{ label: 'Pop Over / Modal', value: 9 },
		]);
	});

	test('handles unresolved (null) term lists', () => {
		expect(buildTermOptions(null, 'Default')).toEqual([
			{ label: 'Default', value: 0 },
		]);
	});
});

describe('timestampToPickerValue', () => {
	// Stands in for @wordpress/date's site-timezone formatter.
	const formatter = (date) => date.toISOString().replace(/\.\d+Z$/, '');

	test('converts stored UTC seconds to a picker value', () => {
		expect(timestampToPickerValue(1786449600, formatter)).toBe(
			'2026-08-11T12:00:00'
		);
	});

	test.each([
		['zero', 0],
		['an absent value', undefined],
		['an empty string', ''],
		['a negative timestamp', -1],
	])('treats %s as never expiring', (_label, stored) => {
		expect(timestampToPickerValue(stored, formatter)).toBeNull();
	});

	test('accepts the string integers REST returns', () => {
		expect(timestampToPickerValue('1786449600', formatter)).toBe(
			'2026-08-11T12:00:00'
		);
	});
});

describe('pickerValueToTimestamp', () => {
	// Stands in for @wordpress/date's getDate: parses wall-clock in site time.
	const parser = (value) => new Date(`${value}Z`);

	test('converts a picker value to UTC seconds', () => {
		expect(pickerValueToTimestamp('2026-08-11T12:00:00', parser)).toBe(
			1786449600
		);
	});

	test.each([
		['null', null],
		['an empty string', ''],
	])('maps %s to 0 so the server deletes the meta row', (_label, value) => {
		expect(pickerValueToTimestamp(value, parser)).toBe(0);
	});

	test('maps an unparseable value to 0 rather than NaN', () => {
		expect(pickerValueToTimestamp('not a date', parser)).toBe(0);
	});

	test('floors sub-second precision', () => {
		const withMilliseconds = () => new Date(1786449600999);

		expect(
			pickerValueToTimestamp('2026-08-11T12:00:00', withMilliseconds)
		).toBe(1786449600);
	});
});

describe('isTwelveHourFormat', () => {
	test.each([
		['g:i a', true],
		['h:i A', true],
		['H:i', false],
		['G:i', false],
	])('reads %s as 12-hour: %s', (format, expected) => {
		expect(isTwelveHourFormat(format)).toBe(expected);
	});

	test('ignores an escaped meridiem character', () => {
		expect(isTwelveHourFormat('H:i \\a\\t')).toBe(false);
	});

	test('defaults to 12-hour when the format is missing', () => {
		expect(isTwelveHourFormat('')).toBe(true);
	});
});
