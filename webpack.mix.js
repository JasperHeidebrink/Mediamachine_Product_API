const mix = require('laravel-mix');

mix.setPublicPath('assets')
  .js('assets/src/admin.js', 'admin.min.js')
  .sourceMaps(true)
  .options({
    processCssUrls: false
  });

