const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).minify('public/assets/js/soft-ui-dashboard.js')
    .copy('public/assets/js/soft-ui-dashboard.js', 'public/js')
    .copy('resources/js/pages/*', 'public/js/pages')
    .copy('resources/js/plugins/*', 'public/js/plugins')
    .copy('node_modules/moment/min/moment.min.js', 'public/js/moment.min.js') // Agrega esta línea para copiar Moment.js al directorio public/js
    .copy('node_modules/toastr/build/toastr.min.js', 'public/js/plugins/toastr.min.js') // Agrega esta línea para copiar Toastr al directorio public/js
mix.sass('public/assets/scss/soft-ui-dashboard.scss', 'public/assets/css')
    .copy('node_modules/toastr/build/toastr.min.css', 'public/css/plugins/toastr.min.css') // Agrega esta línea para copiar Toastr al directorio public/css
    .copy('resources/css/plugins/*', 'public/css/plugins')
