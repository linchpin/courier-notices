/**
 * External dependencies
 */
const wpConfig = require('@wordpress/scripts/config/.eslintrc.js');

const config = {
	...wpConfig,
	rules: {
		...(wpConfig?.rules || {}),
		'jsdoc/valid-types': 'off',
		'import/no-unresolved': ['error'],
	},
	env: {
		browser: true,
	},
	ignorePatterns: [
		'/vendor',
		'/node_modules',
		'/build',
		'/dist',
		'/tools',
		// Playwright e2e files are TypeScript, which Playwright transpiles
		// itself; this setup has no TS parser, so eslint cannot read them.
		'/tests/e2e',
		'/**/*.min.js',
	],
	overrides: [
		...(wpConfig?.overrides || []),
		{
			files: ['tests/jest/**/*.js'],
			env: {
				jest: true,
			},
		},
	],
};

module.exports = config;
