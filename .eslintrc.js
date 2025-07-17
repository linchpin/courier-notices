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
		'/tests',
		'/**/*.min.js',
	],
};

module.exports = config;
