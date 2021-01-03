import { call, additionnalHeaders } from '../../manipulateToken';

const debug = false;

export function postOneWtParameter(values, successCallback, errorCallback) {
    var callFunction = {
            url: Routing.generate('api_wt_parameters_post_collection'),
            data: JSON.stringify(values),
            dataType: 'json',
            contentType: "application/json",
            method: 'POST',
            success: function(result,status,xhr){
                if(debug)
                    console.log('ajax : ', result);
                successCallback(result);
            },
            error: function (xhr,status,result) {
                if(debug)
                    console.log("erreur : ", result);
                if(errorCallback)
                    errorCallback(result, xhr, status);
            }
    };
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    call(callFunction);
}
export function putOneWtParameter(id, values, successCallback, errorCallback) {
    var callFunction = {
            url: Routing.generate('api_wt_parameters_post_item', {'id': id}),
            data: JSON.stringify(values),
            dataType: 'json',
            method: 'PUT',
            success: function(result,status,xhr){
                if(debug)
                    console.log('ajax : ', result);
                successCallback(result);
            },
            error: function (xhr,status,result) {
                if(debug)
                    console.log("erreur : ", result);
                errorCallback(result, xhr, status);
            }
    };
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    call(callFunction);
}
export function getOneWtParameter(id, successCallback, errorCallback) {
    var callFunction = {
            url: Routing.generate('api_wt_parameters_get_item', {'id': id}),
            method: 'GET',
            success: function(result,status,xhr){
                if(debug)
                    console.log('ajax : ', result);
                successCallback(result);
            },
            error: function (xhr,status,result) {
                if(debug)
                    console.log("erreur : ", result);
                errorCallback(result, xhr, status);
            }
    };
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    call(callFunction);
};

