const mix = require('laravel-mix');

mix.setPublicPath('dist');

mix.js('resources/js/index.js', 'laravel-base.js')
    .sourceMaps(false)
    .version()
    .disableSuccessNotifications();
