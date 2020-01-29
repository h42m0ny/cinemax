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
require('material-design-icons');

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
// Routing.generate('movie_index');
// Routing.generate('add_favourite_movie');
$(function() {
    
    $('.js-movie-fav').click(function(){
        let imDBID = $(this).data('imdbid');
        let $that = $(this); //convention qd on récupère un elt du DOM on préfixe le nom de var avec un $ ici on stocke l'élément courant (bouton) pr l'utiliser dans le contexte ajax
        $.ajax({
            method: "POST",
            url: Routing.generate("add_favourite_movie",{'imDBID':imDBID})
            })
            .done(function( jsonResponse ) {
                console.log(jsonResponse);
              alert( jsonResponse.message + ' ' + jsonResponse.data);
              if (jsonResponse.message == 'added' ){
                  $that.addClass('favourite');
              } else if (jsonResponse.message == 'removed'){
                  $that.removeClass('favourite');
              }
            });
    });
});