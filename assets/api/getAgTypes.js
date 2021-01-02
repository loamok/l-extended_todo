const debug = true;

//import $ from 'jquery';

//const routes = require('../js/fos_js_routes.json');
//import Routing from '../../public/bundles/fosjsrouting/js/router.min.js';
//
//Routing.setRoutingData(routes);

import { call } from '../manipulateToken';

$(document).ready(function () {
    
    $('#testMe').click(function(e) {
        e.preventDefault();

        call({
            url: Routing.generate('api_ag_types_get_collection'),
            method: 'GET',
            success: function(result,status,xhr){
                console.log('ajax : ');
                console.log(result);
            },
            error: function (xhr,status,result) {
                console.log("erreur : ");
                console.log(result);
            },
            dataType: 'json'
        });
        
    });
});