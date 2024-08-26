const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// Import the helper to find and generate the entry points in the src directory
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		slotfills: path.resolve(__dirname, 'src/index.js'),
	},
	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, 'build'),
		filename: '[name].js'  // This will generate default WordPress files plus slotfills.js in the build folder
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: '../fonts/',
							publicPath: '../fonts/',
						},
					},
				],
			}
		]
	},
	plugins: [
		...defaultConfig.plugins,
	],
	resolve: {
		...defaultConfig.resolve,
		extensions: ['.js', '.jsx', '.scss']
	}
};
