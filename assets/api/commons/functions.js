import { call, additionnalHeaders } from '../../manipulateToken';

const debug = false;
function fCall(callFunction) {
    global.additionnalHeaders = [{'name': 'Accept', 'value': "application/ld+json"}];
    
    call(callFunction);
}

export function baseCallback(successCallback, errorCallback) {
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

export function postOne(values, successCallback, errorCallback, route) {
    var callFunction = baseCallback(successCallback, errorCallback);
    callFunction.url = route;
    callFunction.data = JSON.stringify(values);
    callFunction.method = 'POST';
    
    fCall(callFunction);
}

export function putOne(values, successCallback, errorCallback, route) {
    var callFunction = baseCallback(successCallback, errorCallback);
    callFunction.url = route;
    callFunction.data = JSON.stringify(values);
    callFunction.method = 'PUT';
    
    fCall(callFunction);
}

export function getOne(successCallback, errorCallback, route) {
    var baseCallFunction = baseCallback(successCallback, errorCallback);
    var callFunction = {
            url: route,
            method: 'GET',
            success: baseCallFunction.success,
            error: baseCallFunction.error,
            dataType: 'json'
    };
    
    fCall(callFunction);
}

export function getCollection(successCallback, errorCallback, route) {
    var baseCallFunction = baseCallback(successCallback, errorCallback);
    var callFunction = {
            url: route,
            method: 'GET',
            success: baseCallFunction.success,
            error: baseCallFunction.error,
            dataType: 'json'
    };
    
    fCall(callFunction);
}