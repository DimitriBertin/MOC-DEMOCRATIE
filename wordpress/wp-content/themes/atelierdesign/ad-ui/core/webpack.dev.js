// Imports
const { merge } = require('webpack-merge')
const common = require('./webpack.common')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')

/**
 * Webpack configuration
 */
module.exports = merge(common, {
  mode: 'development',
  watch: true,
  optimization: {
    minimize: false,
  },
  plugins: [new CleanWebpackPlugin()],
})
