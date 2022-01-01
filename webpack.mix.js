const { join } = require('path')
const mix = require('laravel-mix')

mix
  .js('resources/js/app.js', 'public/dist/js').vue({
    extractStyles: true
  })
  .sass('resources/sass/bootstrap.scss', 'public/dist/css')
  .sass('resources/sass/app.scss', 'public/dist/css')
  .disableNotifications()

if (mix.inProduction()) {
  mix
    .extract()
    .version()
} else {
  mix.sourceMaps()
}

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': join(__dirname, './resources/js')
    }
  }
})
