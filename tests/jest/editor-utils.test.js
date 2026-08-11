/**
 * Tests for the notice editor panel helpers.
 */
import { buildTermOptions } from '../../src/editor/utils';

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
