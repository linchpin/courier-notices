/**
 * External dependencies
 */
const fs = require("fs");

/**
 * @type {import('lint-staged').Configuration}
 */
const config = {
	"**/*.{js,ts,mjs}": (filenames) => {
		// Exclude config files from JS linting
		const filteredFiles = filenames.filter(
			(file) =>
				!file.includes("lint-staged.config.js") &&
				!file.includes("webpack.config.js") &&
				!file.includes(".config.js"),
		);
		return filteredFiles.length > 0
			? ["npm run lint-js", () => "npm run tsc"]
			: [];
	},
	// Temporarily disable PHPStan due to memory issues
	"**/*.php": () => "composer phpstan",
	"courier-notices.php": "composer lint",
	// Exclude problematic files from linting for now
	"*.php": "composer lint",
	"/tools/**.php": "composer lint",
	"composer.json": () => "composer validate --strict",
};

module.exports = config;
