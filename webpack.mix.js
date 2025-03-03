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

mix
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/worker.js', 'public/js')
    .js('resources/frontend/js/main.js', 'public/frontend/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);

// mix.copy('node_modules/blowup/lib/blowup.min.js', 'public/js/blowup.min.js');
// mix.copy('node_modules/formdata-polyfill/formdata-to-blob.js', 'public/js/formdata-to-blob.js');
// mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/frontend/js/jquery.min.js');
// mix.copy('node_modules/js-cookie/dist/js.cookie.min.js', 'public/frontend/js/js.cookie.min.js');
// mix.copy('node_modules/tus-js-client/dist/tus.js', 'public/frontend/js/tus.js');

if (mix.inProduction()) {
    mix.version();
}
