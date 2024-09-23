const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss');
require('dotenv').config();

// Check for required environment variable
if (!process.env.WORDPRESS_URL) {
    console.error('WORDPRESS_URL is not set in the .env file. Please set it and try again.');
    process.exit(1);
}

// Set the public path
mix.setPublicPath('dist');

// Process JavaScript
mix.js('src/js/app.js', 'js');

// Process SCSS and PostCSS (including Tailwind)
mix.sass('src/scss/app.scss', 'css')
   .options({
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Add source maps for development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Configure BrowserSync
mix.browserSync({
    proxy: process.env.WORDPRESS_URL,
    port: 4321,
    files: [
        'src/**/*.js',
        'src/**/*.scss',
        'src/**/*.css',
        'src/routes/**/*.php',
        'src/views/**/*.twig',
        '**/*.php'
    ],
    ignore: [
        'node_modules/**/*',
        'vendor/**/*',
        'wp-admin/**/*',
        'wp-includes/**/*',
        'dist/**/*'
    ],
    watchOptions: {
        ignoreInitial: true,
        ignored: ['dist/**/*']
    },
    notify: false,
    open: false,
    reloadDebounce: 500
});

// Webpack-specific configuration
mix.webpackConfig({
    output: {
        publicPath: `/wp-content/themes/${path.basename(__dirname)}/dist/`,
    },
    externals: {
        jquery: 'jQuery'
    },
    watchOptions: {
        ignored: /node_modules|dist/
    }
});

// Disable notifications
mix.disableSuccessNotifications();