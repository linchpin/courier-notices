const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const DependencyExtractionWebpackPlugin = require("@wordpress/dependency-extraction-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");
const fs = require("fs");

// Copy fonts function
class CopyFontsPlugin {
	apply(compiler) {
		compiler.hooks.afterEmit.tap("CopyFontsPlugin", () => {
			const sourceDir = path.resolve(__dirname, "assets/fonts");
			const destDir = path.resolve(__dirname, "css/fonts");

			if (fs.existsSync(sourceDir)) {
				this.copyRecursiveSync(sourceDir, destDir);
			}
		});
	}

	copyRecursiveSync(src, dest) {
		const exists = fs.existsSync(src);
		const stats = exists && fs.statSync(src);
		const isDirectory = exists && stats.isDirectory();

		if (isDirectory) {
			if (!fs.existsSync(dest)) {
				fs.mkdirSync(dest, { recursive: true });
			}
			fs.readdirSync(src).forEach((childItemName) => {
				this.copyRecursiveSync(
					path.join(src, childItemName),
					path.join(dest, childItemName),
				);
			});
		} else {
			fs.copyFileSync(src, dest);
		}
	}
}

module.exports = {
	...defaultConfig,
	entry: {
		"courier-notices": [
			path.resolve(__dirname, "./assets/js/courier-notices.js"),
			path.resolve(__dirname, "./assets/scss/courier-notices.scss"),
		],
		"courier-notices-admin": [
			path.resolve(__dirname, "./assets/js/courier-notices-admin.js"),
			path.resolve(__dirname, "./assets/scss/courier-notices-admin.scss"),
		],
		"courier-notices-admin-global": [
			path.resolve(
				__dirname,
				"./assets/scss/courier-notices-admin-global.scss",
			),
		],
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(woff|woff2|eot|ttf|svg)$/,
				type: "asset/resource",
				generator: {
					filename: "fonts/[name][ext]",
				},
			},
		],
	},
	resolve: {
		extensions: [".js", ".jsx"],
	},
	output: {
		path: path.resolve(__dirname, "./"),
		filename: "js/[name].js",
		clean: {
			keep: /^(?!js\/|css\/).*$/,
		},
	},
	externals: {
		...defaultConfig.externals,
		jquery: "jQuery",
		lodash: "_",
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: "css/[name].css",
		}),
		new DependencyExtractionWebpackPlugin(),
		new CopyFontsPlugin(),
	],
};
