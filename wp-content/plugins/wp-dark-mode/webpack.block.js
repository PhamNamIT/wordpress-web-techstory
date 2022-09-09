const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");

const path = require("path")

module.exports = {
  ...defaultConfig,
  entry: "./src/block/index.js",
  output: {
    path: path.resolve(__dirname, "includes/block"),
  },
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.svg$/,
        use: ["@svgr/webpack", "url-loader"],
      },
      {
        test: /\.(jpe?g|png|gif)$/,
        loader: "url-loader",
        options: {
          name: "images/[name].[ext]",
        },
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          "style-loader",
          // Translates CSS into CommonJS
          "css-loader",
          // Compiles Sass to CSS
          "sass-loader",
        ],
      },
      {
        test: /\.(js|jsx)$/,
        use: {
          loader: "babel-loader",
          options: {
            babelrc: false,
            presets: [
              [
                "@babel/preset-env",
                {
                  modules: false,
                  targets: {
                    browsers: [
                      "last 2 Chrome versions",
                      "last 2 Firefox versions",
                      "last 2 Safari versions",
                      "last 2 iOS versions",
                      "last 1 Android version",
                      "last 1 ChromeAndroid version",
                      "ie 11",
                    ],
                  },
                },
              ],
            ],
            plugins: [
              [
                "@babel/plugin-transform-react-jsx",
                {
                  pragma: "wp.element.createElement",
                },
              ],
              "@babel/plugin-proposal-class-properties",
            ],
          },
        },
        exclude: /node_modules/,
      },
    ],
  },
};
