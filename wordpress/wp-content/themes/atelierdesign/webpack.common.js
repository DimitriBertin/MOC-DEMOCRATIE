const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  entry: {
    app: "./src/build.js",
  },
  output: {
    path: path.resolve(__dirname, "dist"),
    filename: "app.js",
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"],
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              importLoaders: 2,
              modules: {
                // localIdentName: '[name]__[local]--[hash:base64:5]',
                localIdentName: "[local]",
              },
            },
          },
          "postcss-loader",
          {
            loader: "sass-loader",
            options: {
              api: "modern-compiler",
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "app.chunk.[contenthash].css",
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
};
