let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public')
    .sass('resources/scss/app.scss', 'public');
