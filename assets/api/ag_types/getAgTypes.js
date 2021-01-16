const debug = false;

import { getCollection } from '../commons/functions';

$(document).ready(function () {
    
    $('#testMe').click(function(e) {
        e.preventDefault();
        var success = function(result,status,xhr){
            console.log('ajax : ');
            console.log(result);
        };
        var error = function (xhr,status,result) {
            console.log("erreur : ");
            console.log(result);
        };
        
        getCollection(success, error, Routing.generate('api_ag_types_get_collection'));
    });
});