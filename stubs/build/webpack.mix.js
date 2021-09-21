const mix = require('laravel-mix');

mix
    // App scripts...
    .js('resources/js/app.js', 'js')

    // App css...
    .postCss('resources/css/app.css', 'css')

    .sourceMaps(false)
    .version()
    .disableSuccessNotifications()
    .options({
        postCss: [
            require('postcss-import'),
            require('tailwindcss'),
        ],
    });
