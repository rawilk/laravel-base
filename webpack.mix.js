const mix = require('laravel-mix');

mix.options({
    terser: {
        extractComments: () => false,
        terserOptions: {
            compress: {
                drop_console: true,
            },
            // Prevent LICENSE.txt files from being generated.
            format: {
                comments: false,
            },
        },
    },
})
    .setPublicPath('dist')
    .js('resources/js/index.js', 'dist/assets/laravel-base.js')
    .version();
