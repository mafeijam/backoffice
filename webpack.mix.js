const mix = require('laravel-mix')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const path = require('path')

mix.js('resources/js/app.js', 'public/js').vue()
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
  ])
  .options({
    processCssUrls: false
  })
  .extract(['vue', 'vue-meta', 'quasar', '@inertiajs/inertia', '@inertiajs/inertia-vue'])
  .version()
  .sourceMaps()
  // .browserSync('192.168.50.68:9002')

mix.webpackConfig({
  stats: 'errors-only',
  output: {
    chunkFilename: 'js/[name].js?id=[chunkhash]',
  },
  resolve: {
    alias: {
      '@': path.resolve('resources/js'),
    },
  },
  module: {
    rules: [{
      test: /\.pug$/,
      oneOf: [{
        resourceQuery: /^\?vue/,
        use: ['pug-plain-loader']
      }, {
        use: ['raw-loader', 'pug-plain-loader']
      }]
    }]
  },
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['js/*', 'css/*'],
      verbose: true
    })
  ]
})

