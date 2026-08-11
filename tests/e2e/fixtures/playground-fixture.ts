/**
 * Playwright fixture that boots WordPress Playground with the plugin mounted
 * from the working tree. Ported from mantle's tests/playground fixture.
 *
 * The plugin requires vendor/autoload.php at runtime, so `composer install`
 * must have run before this lane - the whole repo root is mounted, vendor
 * included.
 */
import { test as base, expect } from '@playwright/test';
import { runCLI } from '@wp-playground/cli';
import type { RunCLIServer } from '@wp-playground/cli';
import { mkdirSync, readFileSync } from 'fs';
import { resolve } from 'path';

type PlaygroundOptions = {
	wpVersion: string;
};

type PlaygroundFixtures = {
	playgroundServer: RunCLIServer;
	wpBaseUrl: string;
	screenshotDir: string;
};

export const test = base.extend< PlaygroundFixtures & PlaygroundOptions >( {
	wpVersion: [ 'latest', { option: true } ],

	playgroundServer: [
		async ( { wpVersion }, use ) => {
			const blueprintPath = resolve( __dirname, '../blueprint.json' );
			const blueprint = JSON.parse( readFileSync( blueprintPath, 'utf8' ) );
			const pluginRoot = resolve( __dirname, '../../../' );

			const server: RunCLIServer = await runCLI( {
				command: 'server',
				wp: wpVersion,
				php: '8.2',
				mount: [
					{
						hostPath: pluginRoot,
						vfsPath: '/wordpress/wp-content/plugins/courier-notices',
					},
				],
				blueprint,
				quiet: true,
			} );

			await use( server );

			await server.server.close();
		},
		{ timeout: 120_000 },
	],

	wpBaseUrl: async ( { playgroundServer }, use ) => {
		await use( playgroundServer.serverUrl );
	},

	screenshotDir: async ( {}, use, testInfo ) => {
		const dir = resolve(
			__dirname,
			`../../../test-results/screenshots/${ testInfo.project.name }`
		);
		mkdirSync( dir, { recursive: true } );
		await use( dir );
	},
} );

export { expect };
