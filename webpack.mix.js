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

 mix.styles([
 
    'public/assets/lib/css/bootstrap.min.css',
    'public/assets/lib/css/slick.css',
    'public/assets/lib/css/slick-theme.css',
    'public/assets/lib/css/icofont.min.css',
    'public/assets/lib/css/jquery-ui.css',
    'public/assets/lib/css/jquery-ui.theme.css',
    'public/assets/lib/css/pretty-checkbox.min.css',
    'public/assets/lib/css/materialdesignicons.min.css',
   /*  'public/assets/lib/css/intlTelInput.css', */
   /*  'public/assets/lib/css/select2.min.css', 
    'public/assets/lib/css/lib.css',
    'public/assets/lib/css/select2-bootstrap.min.css', */
], 'public/assets/css/vendors.css');

mix.scripts([ 
    'public/assets/lib/js/jquery-3.5.1.min.js',
    'public/assets/lib/js/popper.js',
    'public/assets/lib/js/bootstrap.min.js',
    'public/assets/lib/js/jquery-ui.min.js',
    'public/assets/lib/js/slick.min.js',
    'public/assets/lib/js/zoomsl.js',
/*     'public/assets/lib/js/intlTelInput.min.js', */
   /*  'public/assets/lib/js/select2.min.js',   */
], 'public/assets/js/vendors.js');

mix.js('resources/js/app.js', 'public/assets/js');
		
if (mix.inProduction()) {
    mix.sourceMaps();
    mix.version();
}