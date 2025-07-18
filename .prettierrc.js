module.exports = {
	...require('@wordpress/prettier-config'),
	overrides: [
		{
			files: ['*.yml', '*.yaml'],
			options: { tabWidth: 2, useTabs: false },
		},
	],
};
