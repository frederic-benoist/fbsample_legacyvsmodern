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

if (dev) {
  var css_options = {sourceMap: true, minimize: false};
} else {
  var css_options = {sourceMap: false, minimize: true};
}

const ExtractTextPlugin = require('extract-text-webpack-plugin');
let plugins = [];
plugins.push(
  new ExtractTextPlugin('../css/[name].css')
);

let css_config = {
  // CSS Processing
  cache: true,
  entry: {
    admindocumenttypecontroller: [
      './scss/admindocumenttypecontroller.scss'
    ]  },
  output: {
    path: path.resolve(__dirname, '../../assets/css'),
    filename: '[name].css',
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
              // CSS Loader resolves import at-rules and url functions in the CSS.
              loader: 'css-loader', options: css_options
            },
            {
              // PostCSS Loader processes CSS
              loader: 'postcss-loader', options: css_options
            },
            {
              // SASS Loader compiles Sass to CSS
              loader: 'sass-loader', options: css_options
            }
            // Note that the loaders are ordered from bottom to top or right to left.
            // Loaders act like functions, that’s why it’s from right to left.
            // For example, css-loader(postcss-loader(sass-loader(resource)))
          ]
        })
    },
    {
      test: /\.css$/,
      use: ExtractTextPlugin.extract({
        fallback: 'style-loader',
        use: [
          {
            loader: 'css-loader', options: css_options
          },
          {
            loader: 'postcss-loader', options: css_options
          },
        ]
      })
    },
    {
      test: /.(png|woff(2)?|eot|ttf|svg)(\?[a-z0-9=\.]+)?$/,
      use: [
        {
          loader: 'file-loader',
          options: {
            name: '../css/[hash].[ext]'
          }
        }
      ]
    },
  ]},
  plugins: plugins,
  resolve: {
    extensions: ['.js', '.css', '.scss']
  }
};

/*
This code was seperated from the config for multiple reasons.
Other conditional things can be added very simply.
Also, the check for config.plugins is so it is not dependent on the structure above.
*/
css_config.plugins = css_config.plugins||[];
if (dev) {
  css_config.devtool = 'source-map';
  css_config.plugins.push(new webpack.DefinePlugin({
      'process.env': {
          'NODE_ENV': `""`
      }
  }));
} else {
  css_config.plugins.push(new webpack.DefinePlugin({
      'process.env': {
          'NODE_ENV': `"production"`
      }
  }));
}

module.exports = css_config;
