/* 
 * Manipulate the user token for api's calls
 */
import $ from 'jquery';

//var myStorage = window.localStorage;
var tokenKey = 'let_jwt';
var pemKey = 'let_pem';
var pubkey;
var useAuth = true;

const routes = require('./js/fos_js_routes.json');
import Routing from '../public/bundles/fosjsrouting/js/router.min.js';

Routing.setRoutingData(routes);

const jwt = require('jsonwebtoken');

function purgeToken() {
    console.log('onPurgeToken : start');
    localStorage.removeItem(tokenKey);
    localStorage.removeItem(pemKey);
}

function haveIaValidToken() {
    console.log('onHIAVT : start');
    var token = localStorage.getItem(tokenKey);
    var key = localStorage.getItem(pemKey);
    if(token === null || key === null) {
        console.log('onHIAVT : (Token or key) is null');
        purgeToken();
        getMyToken();
        return false;
    } else {
        console.log('onHIAVT : Verify token start');
        pubkey = key.replace(/\n$/, '');
        try {
            var decoded = jwt.verify(token, pubkey);
            console.log("decoded : ");
            console.log(decoded);
            const d = new Date(0);
            d.setUTCSeconds(decoded.exp);
            console.log(d);
            setMyBearer();
            console.log('onHIAVT : Verify token OK');
            return true;
        } catch (e) {
            console.log('onHIAVT : Verify token KO');
            console.log('!verify');
            console.log("error : ");
            console.log(e);
            getMyToken();
            return false
        }
    }
}

function getMyToken () {
    console.log('onGetMyToken : start');
    localStorage.removeItem(tokenKey);
    localStorage.removeItem(pemKey);
    useAuth = false;
    var res = false;
    $.ajaxSetup({
        beforeSend: function(xhr) {
            console.log('onBeforeSend, clear bearer');
            xhr.setRequestHeader('Authorization', '');
        }
    });
    $.ajax({
        url: Routing.generate('app_front_token'),
        method: 'GET',
        dataType: 'json'
    }).done(function (result) {
        console.log('onGetMyToken : success');
        localStorage.setItem(tokenKey, result.token);
        localStorage.setItem(pemKey, result.pem);
        haveIaValidToken();
        useAuth = true;
    }).fail(function () {
        console.log('onGetMyToken : error');
        console.log("Utilisateur non reconnu ! ou non identifié.");
        useAuth = true;
    })
    ;
};

function setMyBearer() {
    $.ajaxSetup({
        beforeSend: function(xhr) {
            console.log('onBeforeSend, launch');
            if(useAuth) {
                console.log('onBeforeSend, set Bearer '+ localStorage.getItem(tokenKey));
                xhr.setRequestHeader('Authorization', 'Bearer '+ localStorage.getItem(tokenKey));
            } 
        }
    });
};

export function call(query, run) {
    console.log('fromCall, run : '+run);
    
    if(run === null) {
        run = 0;
    }
    if(run > 3) {
        return false;
    }
    
    var hiavt = haveIaValidToken();
    if(!hiavt) {
        var start = new Date().getTime();
        while (new Date().getTime() < start + 2000);
    }
    $.ajax(query).done(function(data,jqXHR) {
        console.log("res ok run : " + run);
    }).fail(function(jqXHR) {
        console.log("run ko run : " + run);
        console.log("status : "+ jqXHR.status);
        
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