import { call, additionnalHeaders } from '../../manipulateToken';

const debug = false;
function baseCallback(successCallback, errorCallback) {
    return {
        url: null,
        data: null,
        dataType: 'json',
        contentType: "application/json",
        method: null,
        success: function(result,status,xhr){
            if(debug)
                console.log('ajax : ', result);
            if(successCallback)
                successCallback(result);
        },
        error: function (xhr,status,result) {
            if(debug)
                console.log("erreur : ", result);
            if(errorCallback)
                errorCallback(result, xhr, status);
        }  
    };
}

export function postOneWtParameter(values, successCallback, errorCallback) {
    var callFunction = baseCallback(successCallback, errorCallback);
    callFunction.url = Routing.generate('api_wt_parameters_post_collection');
    callFunction.data = JSON.stringify(values);
    callFunction.method = 'POST';
    
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    
    call(callFunction);
}
export function putOneWtParameter(id, values, successCallback, errorCallback) {
    var callFunction = baseCallback(successCallback, errorCallback);
    callFunction.url = Routing.generate('api_wt_parameters_put_item', {'id': id});
    callFunction.data = JSON.stringify(values);
    callFunction.method = 'PUT';
    
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    
    call(callFunction);
}
export function getOneWtParameter(id, successCallback, errorCallback) {
    var baseCallFunction = baseCallback(successCallback, errorCallback);
    var callFunction = {
            url: Routing.generate('api_wt_parameters_get_item', {'id': id}),
            method: 'GET',
            success: baseCallFunction.success,
            error: baseCallFunction.error
    };
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    call(callFunction);
};

