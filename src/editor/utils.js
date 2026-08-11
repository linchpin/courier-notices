/**
 * Pure helpers for the notice editor panel.
 */

/**
 * Build SelectControl options from taxonomy term records.
 *
 * @param {Array|null} terms       Term records from core-data, or null while resolving.
 * @param {string}     placeholder Label for the empty option.
 * @return {Array} SelectControl options.
 */
export function buildTermOptions(terms, placeholder) {
	const options = [{ label: placeholder, value: 0 }];

	if (Array.isArray(terms)) {
		terms.forEach((term) => {
			options.push({ label: term.name, value: term.id });
		});
	}

	return options;
}
