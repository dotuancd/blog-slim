const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.config.publicPath = 'public';

mix.js('resources/assets/js/app.js', 'public/js');


mix.sass('resources/assets/sass/app.scss', 'public/css/app.css')
    .styles([
        'node_modules/simplemde/dist/simplemde.min.css',
        'public/css/app.css',
    ], 'public/css/all.css');

//    .sass('resources/assets/sass/app.scss', 'public/css/app.css')
//     .styles([
//         'node_modules/simplemde/dist/simplemde.min.css',
//         'public/css/app.css',
//     ], 'public/css/all.css');
//
// mix.copy('node_modules/codemirror/theme', 'public/vendor/codemirror/theme');


