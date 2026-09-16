const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = {
  entry: {
    app: path.resolve(__dirname, './src/build.js'),
    'editor-style': path.resolve(__dirname, './src/editor-style.js'),
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].js',
    chunkFilename: 'app.chunk.[contenthash].js',
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader'],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
      chunkFilename: 'app.chunk.[contenthash].css',
    }),
  ],
  stats: {
    assets: true, // Display assets
    modules: false, // Hide modules
    reasons: false, // Hide reasons
    children: false, // Hide children information
    source: false, // Hide source information
    errorDetails: false, // Hide error details (they are shown by default when 'errors' is true)
    publicPath: false, // Hide publicPath
    builtAt: false, // Hide built at information
    entrypoints: false, // Hide entry points information
    hash: false, // Hide hash information
  },
}
