/**
 * 2013-2019 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * Use, copy, modification or distribution of this source file without written
 * license agreement from Frédéric BENOIST is strictly forbidden.
 *
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence Academic Free License (AFL 3.0)
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de Frédéric BENOIST est
 * expressement interdite.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2019 Frédéric BENOIST <https://www.fbenoist.com>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


const webpack = require('webpack');
const dev = process.argv.indexOf('--env=dev') !== -1; // In production mode ?
const path = require('path');

let plugins = [];

let js_config = {
  entry: {
    admindocumenttypecontroller: [
      './js/admindocumenttypecontroller.js'
    ]
  },
  output: {
    path: path.resolve(__dirname, '../../assets/js'),
    filename: '[name].js'
  },
  /* suppress node shims */
	node: {
		process: false,
		Buffer: false
	},
  module: {
    rules: [
      {
         use: {
            loader:'babel-loader',
            options: { presets: ['es2015'] }
         },
         test: /\.js$/,
         exclude: /node_modules/
      }
    ]
  },
  externals: {
    prestashop: 'prestashop'
  },
  plugins: plugins,
  resolve: {
    extensions: ['.js']
  }
};

js_config.plugins = js_config.plugins||[];
if (dev) {
  js_config.devtool = 'source-map';
  js_config.cache = true;
} else {
  js_config.cache = false;
  js_config.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: false,
      compress: {
        sequences: true,
        conditionals: true,
        booleans: true,
        if_return: true,
        join_vars: true,
        drop_console: true
      },
      output: {
        comments: false
      },
      minimize: true
    })
  );
}

module.exports = js_config;

