const mix = require('laravel-mix');

// Set the path to your Adyen SDK node_modules directory
const adyenSdkPath = 'node_modules/@adyen/adyen-web';

mix.js('resources/js/app.js', 'public/js') // Example: Your main JS file
   .sass('resources/sass/app.scss', 'public/css') // Example: Your main Sass file
   .copy(`${adyenSdkPath}/adyen.js`, 'public/js/adyen.js') // Copy Adyen JS to public directory
   .copy(`${adyenSdkPath}/adyen.css`, 'public/css/adyen.css'); // Copy Adyen CSS to public directory

// Add other Mix configurations as needed
