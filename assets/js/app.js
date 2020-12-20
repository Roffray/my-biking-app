/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/globals.scss';
import '../css/app.css';

import '@fortawesome/fontawesome-free/js/all';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

global.$ = global.jQuery = $;

require('bootstrap');

window.axios = require('axios');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
