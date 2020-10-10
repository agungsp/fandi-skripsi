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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/bootstrap/dist', 'public/bootstrap')
   .copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
   .copy('node_modules/@fortawesome/fontawesome-free', 'public/fontawesome')
   .copy('node_modules/bs-custom-file-input/dist/bs-custom-file-input.js', 'public/js/bs-custom-file-input.js');
