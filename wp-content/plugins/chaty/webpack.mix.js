const mix = require('laravel-mix');
mix.sass('src/app.scss', 'admin/assets/css');
mix.js('src/app.js', 'admin/assets/js');