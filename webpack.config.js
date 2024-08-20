const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const DependencyExtractionWebpackPlugin = require("@wordpress/dependency-extraction-webpack-plugin");
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const path = require("path");
const isDevelopment = process.env.NODE_ENV === 'development';

module.exports = {
	...defaultConfig,
	mode: "production",
	entry: {
		frontend: path.resolve( __dirname, './src/js/courier-notices.js' ),
		admin: path.resolve( __dirname, './src/js/courier-notices-admin.js' ),
	},
	devtool: "eval-source-map",
	module: {
		...defaultConfig.module,
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env', '@babel/preset-react']
					}
				}
			},
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
			}
		]
	},
	resolve: {
		extensions: [ '.js', '.jsx', '.scss', '.css', '.svg' ]
	},
	output: {
		path: path.resolve( __dirname, './build' ),
		filename: '[name].js',
	},
	devServer: {
		contentBase: path.resolve( __dirname, './build' ),
		hot: true
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: isDevelopment ? '[name].css' : '[name].css',
			chunkFilename: isDevelopment ? '[id].css' : '[id].css'
		} )
	]
};
