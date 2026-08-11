/**
 * Jest configuration for Courier Notices.
 *
 * The @wordpress/interactivity module is mapped to a hand-written mock
 * (copied from linchpin-blocks) — it is what makes view.js Interactivity
 * stores unit-testable at all. See tests/jest/__mocks__.
 *
 * @see https://jestjs.io/docs/configuration
 */
module.exports = {
	preset: '@wordpress/jest-preset-default',
	testEnvironment: 'jsdom',
	setupFilesAfterEnv: [ '<rootDir>/tests/jest/setup.js' ],
	testMatch: [ '**/tests/jest/**/*.test.js' ],
	moduleNameMapper: {
		'^@wordpress/interactivity$':
			'<rootDir>/tests/jest/__mocks__/@wordpress/interactivity.js',
	},
	collectCoverageFrom: [ 'src/**/view.js', 'src/**/edit.js', 'src/**/index.js' ],
	coverageDirectory: 'coverage-js',
	coverageReporters: [ 'html', 'text', 'lcov' ],
	transformIgnorePatterns: [ '/node_modules/(?!(@wordpress|swiper)/)' ],
};
