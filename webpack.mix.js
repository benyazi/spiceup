const mix = require('laravel-mix');

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

mix.js('resources/js/screen-view/app.js', 'public/js/screenView')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/screenView.scss', 'public/css/screenView')
    .js('resources/js/screen-dashboard/app.js', 'public/js/screenDashboard')
    .sass('resources/sass/screenDashboard.scss', 'public/css/screenDashboard');
