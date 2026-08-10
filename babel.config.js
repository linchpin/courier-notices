/**
 * Babel configuration for Courier Notices.
 *
 * This is the same preset @wordpress/scripts uses internally when no project
 * config exists. It has to be declared explicitly here because the presence
 * of jest.config.js makes wp-scripts hand Jest transforms to plain babel-jest,
 * which only reads project-level Babel configuration.
 */
module.exports = {
	presets: [ '@wordpress/babel-preset-default' ],
};
