/* 
 * Manipulate the user token for api's calls
 */
const debug = false;

//import $ from 'jquery';

//var myStorage = window.localStorage;
var tokenKey = 'let_jwt';
var pemKey = 'let_pem';
var pubkey;
var useAuth = true;

//const routes = require('./js/fos_js_routes.json');
//import Routing from '../public/bundles/fosjsrouting/js/router.min.js';

//Routing.setRoutingData(routes);

const jwt = require('jsonwebtoken');

//import { showAlert, dissmissAlert } from './js/alerts';

function purgeToken() {
    if(debug)
        console.log('onPurgeToken : start');
    localStorage.removeItem(tokenKey);
    localStorage.removeItem(pemKey);
}

function haveIaValidToken() {
    if(debug)
        console.log('onHIAVT : start');
    var token = localStorage.getItem(tokenKey);
    var key = localStorage.getItem(pemKey);
    if(token === null || key === null) {
        if(debug)
            console.log('onHIAVT : (Token or key) is null');
        purgeToken();
        getMyToken();
//        showAlert('warning', "No token found.", 'token-warning');
        return false;
    } else {
        if(debug)
            console.log('onHIAVT : Verify token start');
        pubkey = key.replace(/\n$/, '');
        try {
            var decoded = jwt.verify(token, pubkey);
            if(debug) {
                console.log("decoded : ");
                console.log(decoded);
                const d = new Date(0);
                d.setUTCSeconds(decoded.exp);
                console.log(d);
                console.log('onHIAVT : Verify token OK');
            }
            setMyBearer();
//            showAlert('success', "Token Found and correct.", 'token-success');
            return true;
        } catch (e) {
            if(debug) {
                console.log('onHIAVT : Verify token KO');
                console.log('!verify');
                console.log("error : ");
                console.log(e);
            }
            getMyToken();
//            showAlert('warning', "Token Found but not verified.", 'token-success');
            return false
        }
    }
}

function getMyToken () {
    if(debug)
        console.log('onGetMyToken : start');
    localStorage.removeItem(tokenKey);
    localStorage.removeItem(pemKey);
    useAuth = false;
    var res = false;
    $.ajaxSetup({
        beforeSend: function(xhr) {
            if(debug)
                console.log('onBeforeSend, clear bearer');
            xhr.setRequestHeader('Authorization', '');
        }
    });
    $.ajax({
        url: Routing.generate('app_front_token'),
        method: 'GET',
        dataType: 'json'
    }).done(function (result) {
        if(debug)
            console.log('onGetMyToken : success');
        localStorage.setItem(tokenKey, result.token);
        localStorage.setItem(pemKey, result.pem);
        haveIaValidToken();
        useAuth = true;
    }).fail(function () {
        if(debug) {
            console.log('onGetMyToken : error');
            console.log("Utilisateur non reconnu ! ou non identifié.");
        }
        useAuth = true;
    })
    ;
};

var additionnalHeaders;
global.additionnalHeaders = additionnalHeaders;

export function setMyBearer() {
    if(debug) {
        console.log("globHeaders :");
        console.log(global.additionnalHeaders);
    }
    $.ajaxSetup({
        beforeSend: function(xhr) {
            if(debug)
                console.log('onBeforeSend, launch');
            if(useAuth) {
                if(debug)
                    console.log('onBeforeSend, set Bearer '+ localStorage.getItem(tokenKey));
                xhr.setRequestHeader('Authorization', 'Bearer '+ localStorage.getItem(tokenKey));
                if(global.additionnalHeaders) {
                    for(header in additionnalHeaders) {
                        xhr.setRequestHeader(header.name, header.value);
                    }
                }
            } 
        }
    });
};

export function call(query, run) {
    if(debug) {
        console.log('fromCall, run : '+run);
    }
    
//    if($("#"+'call-info_'+query.url.replace(/\//g, '_')).length < 1) {
//        showAlert('info', "Call to '"+query.url+"' pending !", 'call-info_'+query.url.replace(/\//g, '_'));
//    }
    
    if(run === null || run === undefined) {
        run = 0;
    }
    if(run > 3) {
        showAlert('danger', "Call to "+query.url+" failed almost "+ run +" time(s) !", 'call-error'+query.url.replace(/\//g, '_'));
        return false;
    }
    
    var hiavt = haveIaValidToken();
    if(!hiavt) {
        var start = new Date().getTime();
        while (new Date().getTime() < start + 2000);
    }
    $.ajax(query).done(function(data,jqXHR) {
        if(debug)
            console.log("res ok run : " + run);
        dissmissAlert("alerts-warning-call-warning_"+query.url.replace(/\//g, '_'));
        dissmissAlert("alerts-info-call-info_"+query.url.replace(/\//g, '_'));
    }).fail(function(jqXHR) {
        if(debug) {
            console.log("run ko run : " + run);
            console.log("status : "+ jqXHR.status);
        }
//        showAlert('warning', "Call to '"+query.url+"' failed "+(run + 1)+" time(s) !", 'call-warning_'+query.url.replace(/\//g, '_'));
        
        var status = parseInt(jqXHR.status);
        if(status >= 400 || Number.isNaN(jqXHR.status)) {
            run += 1;
            call(query, run);
        }
    });
};
//export default call;

$(document).ready(function () {
    $('a[href="'+Routing.generate('app_logout')+'"]').click(function(e) {
        purgeToken();
    });
    haveIaValidToken();
    
});