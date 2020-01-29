/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';
const $ = require('jquery');
require('popper.js');
require('bootstrap');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
Routing.generate('movie_index');