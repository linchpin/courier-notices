/**
 * Smoke test proving the Jest lane boots.
 *
 * Exercises the hand-written @wordpress/interactivity mock (resolved via
 * moduleNameMapper in jest.config.js) so a regression in the mock wiring
 * fails here rather than in the first real view.js test.
 */
import { store, getStore, clearMocks } from '@wordpress/interactivity';

describe('jest lane', () => {
	afterEach(() => clearMocks());

	test('the @wordpress/interactivity mock registers stores', () => {
		const result = store('courier-notices/smoke', {
			state: { visible: 0 },
			actions: {
				dismiss() {
					result.state.visible -= 1;
				},
			},
		});

		expect(store).toHaveBeenCalledWith(
			'courier-notices/smoke',
			expect.any(Object)
		);
		expect(getStore('courier-notices/smoke')).toBe(result);

		result.state.visible = 2;
		result.actions.dismiss();
		expect(result.state.visible).toBe(1);
	});

	test('the DOM test environment is available', () => {
		document.body.innerHTML = '<div class="courier-notices"></div>';
		expect(document.querySelector('.courier-notices')).not.toBeNull();
	});
});
