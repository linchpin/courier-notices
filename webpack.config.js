const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const path = require( 'path' );
const isDevelopment = process.env.NODE_ENV === 'development';

module.exports = {
	...defaultConfig,
	mode: 'production',
	entry: {
		frontend: path.resolve( __dirname, './src/module-polyfill.js' ),
		index: path.resolve( __dirname, './src/index.js' ),
		guides: path.resolve( __dirname, './src/block-guides.js' ),
	},
	devtool: 'eval-source-map',
	module: {
		...defaultConfig.module,
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
					},
				},
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'resolve-url-loader',
					'postcss-loader',
					{
						loader: 'sass-loader',
						options: {
							implementation: require.resolve( 'sass' ),
							sourceMap: true,
						},
					},
				],
			},
		],
	},
	resolve: {
		extensions: [ '.js', '.jsx' ],
	},
	output: {
		path: path.resolve( __dirname, './build' ),
		filename: '[name].js',
	},
	devServer: {
		contentBase: path.resolve( __dirname, './build' ),
		hot: true,
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: isDevelopment ? '[name].css' : '[name].css',
			chunkFilename: isDevelopment ? '[id].css' : '[id].css',
		} ),
		new DependencyExtractionWebpackPlugin(),
	],
};
