/**
 * Smoke test proving the Playground e2e lane boots: the plugin activates in a
 * real WordPress without a fatal error.
 */
import { test, expect } from '../fixtures/playground-fixture';
import path from 'path';

test.describe( 'Plugin Activation', () => {
	test( 'Courier Notices activates without errors', async ( {
		page,
		wpBaseUrl,
		screenshotDir,
	} ) => {
		await page.goto( `${ wpBaseUrl }/wp-admin/plugins.php` );

		// Verify the plugin is listed and active (has a Deactivate link).
		const pluginRow = page.locator(
			'tr[data-plugin="courier-notices/courier-notices.php"]'
		);
		await expect( pluginRow ).toBeVisible();
		await expect( pluginRow.locator( '.deactivate' ) ).toBeVisible();

		// Ensure activation does not trigger a fatal runtime failure.
		const bodyText = await page.textContent( 'body' );
		expect( bodyText ).not.toContain( 'Fatal error' );
		expect( bodyText ).not.toContain(
			'There has been a critical error on this website.'
		);

		await page.screenshot( {
			path: path.join( screenshotDir, 'plugins-page.png' ),
			fullPage: true,
		} );
	} );
} );
