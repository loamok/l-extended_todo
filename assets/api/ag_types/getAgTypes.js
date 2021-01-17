const debug = true;

import { getCollection } from '../commons/functions';

$(document).ready(function () {
    
    $('#testMe').click(function(e) {
        e.preventDefault();
        var success = function(result,status,xhr){
            if(debug) console.log('ajax : ', result);
        };
        var error = function (xhr,status,result) {
            if(debug) console.log("erreur : ", result);
        };
        
        getCollection(success, error, Routing.generate('api_ag_types_get_collection'));
    });
});