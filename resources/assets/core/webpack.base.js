const path = require('path')
const webpack = require('webpack')
const {cssLoaders, htmlPage} = require('./tools')
const CopyWebpackPlugin = require('copy-webpack-plugin')

let resolve = dir => path.join(__dirname, '..', '', dir) ; 

module.exports = {
  entry: {
    index: resolve('./page/app'),
    login: resolve('./page/login'),
    signup: resolve('./page/signup'),
    forgot: resolve('./page/forgot'),
    resetpass: resolve('./page/resetpass'),
  },
  output: {
    path: path.join(__dirname, '../../../', 'public'),
    publicPath: '/',
    filename: 'js/[name].js',
    chunkFilename: 'js/[id].[name].js?[hash]',
    library: '[name]'
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': resolve('src')
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          extractCSS: true,
          loaders: {
            ...cssLoaders(),
            js: { loader: 'babel-loader' }
          },
          transformToRequire: {
            video: 'src',
            source: 'src',
            img: 'src',
            image: 'xlink:href'
          }
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        include:  [path.join(__dirname, '..', 'page'), path.join(__dirname, '..', 'test')],
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: 'img/[name].[hash:7].[ext]'
        }
      },
      {
        test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: 'media/[name].[hash:7].[ext]'
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: 'fonts/[name].[hash:7].[ext]'
        }
      }
      
    ]
  },
  plugins: [
    htmlPage('home', 'index', ['index']),
    htmlPage('login', 'login', ['login']),
    htmlPage('signup', 'signup', ['signup']),
    htmlPage('forgot', 'forgot', ['forgot']),
    htmlPage('resetpass', 'resetpass', ['resetpass']),
    new CopyWebpackPlugin([{ from: path.join(__dirname, '..', 'static') }]),
    new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery"
    }),
  ],
  performance: { hints: false },
}