/**
 * @type {import('lint-staged').Configuration}
 */
const config = {
	// Config files are excluded from JS linting - they are CommonJS and predate
	// the shared eslint config.
	'**/*.{js,mjs}': ( filenames ) => {
		const linted = filenames.filter(
			( file ) => ! /\.config\.(js|mjs)$/.test( file )
		);

		return linted.length > 0
			? [ `wp-scripts lint-js ${ linted.join( ' ' ) }` ]
			: [];
	},

	'**/*.{css,scss}': [ 'wp-scripts lint-style' ],

	// Staged files only, NOT the whole project. The project currently has a
	// large PHPCS backlog (~120 errors outside the vendored WP_List_Table fork),
	// so a project-wide pass would block every commit. Linting only what you
	// touch means the debt gets paid down file by file as we migrate.
	//
	// Note: passing explicit paths OVERRIDES the <file> list in phpcs.xml.dist.
	// That is intentional here - it means a staged templates/*.php gets linted
	// even though templates/ is not yet in the ruleset's own scope.
	//
	// PHPStan is deliberately not run here: it needs the whole project loaded
	// and carries a large suppression baseline. It runs in CI instead.
	'**/*.php': ( filenames ) => [
		`composer phpcs -- ${ filenames.map( ( f ) => `'${ f }'` ).join( ' ' ) }`,
	],

	'composer.json': () => 'composer validate --strict',
};

module.exports = config;
