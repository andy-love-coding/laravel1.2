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

/*
    在public/app.css中改成: <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    version() 配合 mix() 使用，编译后会变成:<link rel="stylesheet" href="/css/app.css?id=a18bcbec68adfba91268">
    以此来解决：静态文件浏览器缓存问题
*/
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').version();
