var webpack = require('webpack');
var path = require('path');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var ip = require('ip');
var conPackageJson = require('./package.json');
var host = ip.address();
var port = conPackageJson.config.port;
var config = {
  devServer: {
    host: host,
    port: port
  },
  entry: {
    index: [
      './assets/js/index.js',
      './assets/css/index.less'
    ],
    // test: [
    //   './assets/js/index.js',
    //   './assets/css/index.less'
    // ],
    vendor: [
      './node_modules/bootstrap/dist/css/bootstrap.css',
      './node_modules/bootstrap/dist/js/bootstrap.js',
      './assets/js/require-image.js'
    ]
  },
  output: {
    publicPath: `http://${host}:${port}/public/`,
    path: path.resolve(__dirname, './public/'),
    filename: '[name].js',
    chunkFilename: '[name].js'
  },
  module: {
    loaders: [
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract("style-loader", "css-loader")
      },
      {
        test: /\.less$/,
        loader: ExtractTextPlugin.extract("style", "css!less")
      },
      {
        test: /\.(jpg|jpeg|gif|png|ico)$/i,
        loader: 'file?name=[path][name].[ext]'
      },
      {
        test: /\.(ttf|eot|svg|woff|woff2)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: "file"
      },
      {
        test: /\.jsx?$/,
        exclude: /(node_modules|bower_components)/,
        loader: 'babel-loader', // 'babel-loader' is also a legal name to reference
        query: {
          presets: ['react', 'es2015']
        }
      }
    ]
  },
  plugins: [
    new webpack.optimize.CommonsChunkPlugin({"name": "vendor"}),
    new ExtractTextPlugin('[name].css'),
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      "window.jQuery": "jquery"
    })
  ]
}



// generate manifest.json
//if (env == 'production' || env === 'testing') {
  config.plugins.push(function() {
    this.plugin("done", function(stats) {
      var statsJson = stats.toJson();
      var manifest = Object.assign({}, statsJson.assetsByChunkName);
      statsJson.modules.forEach(function(el, index) {
        if (el.assets[0]) {
          manifest[el.name] = el.assets[0];
        }
      });

      require("fs").writeFileSync(
        path.join(__dirname, "manifest.json"),
        JSON.stringify(manifest, null, 2)
      );
    });
  });
//}

module.exports = config;
