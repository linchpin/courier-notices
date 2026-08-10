/**
 * Playwright configuration for the Playground e2e lane.
 *
 * Each project boots WordPress Playground (WASM PHP, no MySQL needed) with
 * the plugin mounted from the working tree - see fixtures/playground-fixture.ts.
 * Ported from mantle's tests/playground setup.
 */
import { defineConfig, devices } from '@playwright/test';
import path from 'path';

const wpVersions = ( process.env.WP_VERSIONS || 'latest' ).split( ',' );

export default defineConfig( {
	testDir: './specs',
	outputDir: path.resolve( __dirname, '../../test-results/e2e' ),
	fullyParallel: false,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 1 : 0,
	workers: 1,
	reporter: process.env.CI
		? [
				[
					'html',
					{
						outputFolder: path.resolve(
							__dirname,
							'../../test-results/playwright-report'
						),
					},
				],
				[ 'github' ],
		  ]
		: 'line',
	use: {
		trace: 'on-first-retry',
		screenshot: 'off',
		viewport: { width: 1280, height: 900 },
	},
	projects: wpVersions.map( ( version ) => ( {
		name: `wp-${ version.trim() }`,
		use: {
			...devices[ 'Desktop Chrome' ],
			wpVersion: version.trim(),
		},
	} ) ),
} );
