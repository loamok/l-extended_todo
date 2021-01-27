/* global global */

const smartEventDefine = global.smartEventDefine;
const debug = false;

import { getOneWtParameter, postOneWtParameter, putOneWtParameter } from '../../../api/wt_parameters/wt_parameters';
import { getOneDayParameters, postOneDayParameters, putOneDayParameters } from '../../../api/day_parameters/day_parameters';

import { getTimeVal, getTimeValTs, addTime, subsTime, parseIntTime, checkTimeVals, setTimeVal, zeroVal } from '../../../js/let/let_utils';

var paramsFormSave = { ...smartEventDefine };

paramsFormSave.event = 'click';
paramsFormSave.handler = function (obj, event) {
    var values = prepareValuesForAjax();
            
    if(debug)
        console.log('prepared', values);

    var id = JSON.parse($('script#globalParam').text()).id;
    if(id === null) {
        if(debug)
            console.log('id is null :', id);

        postOneWtParameter(values, setParamValues);
    } else {
        if(debug)
            console.log('id is not null :', id);
        putOneWtParameter(id, values, setParamValues);
    }
          
};

var callbackEnded = true;
var globalParam = null;
var globalDayParams = null;

import {  
    fields as wtFields, suffix as wtSuffix, 
    prefix as wtPrefix, hoursFields as wtHoursFields, 
    simpleFields as wtSimpleFields, uuidFields as wtUuidFields, 
    cbFields as wtCbFields, intFields as wtIntFields, jsonRepresentation as wtJsonRepresentation
} from './wtFields';

function translateValuesFromSelector(input) {
    var h = $('#' + wtPrefix + input + wtSuffix).timesetter().getHoursValue();
    var m = $('#' + wtPrefix + input + wtSuffix).timesetter().getMinutesValue();

    h = (h < 10) ? '0' + h : h;
    m = (m < 10) ? '0' + m : m;

    $('#' + wtPrefix + input).val('' + h + ':' + m);
    if(debug)
        console.log(input + ':' + $('#' + wtPrefix + input).val());
}

function translateValuesForAjax(input) {
    var hoursMinVals = $('#' + wtPrefix + input).val().split(':');
    if(debug) 
        console.log('hoursMinVals :', hoursMinVals);
    
    $('#'+wtPrefix+input).val('P0Y0M0DT' + parseInt(hoursMinVals[0]) + 'H' + parseInt(hoursMinVals[1]) + 'M0S');
    
    if(debug)
        console.log(input + ':' + $('#' + wtPrefix + input).val());
    
}

function translateValuesFromAjax(input) {
    var intervalS = $('#' + wtPrefix + input).val();
    var dateTimeSpec = intervalS.split('T');
    if(dateTimeSpec[1] === undefined) {
        return;
    }
    var hoursMinSpecs = dateTimeSpec[1].split('H');
    var minSecSpecs = hoursMinSpecs[1].split('M');
    var h = parseInt(hoursMinSpecs[0]);
    var m = parseInt(minSecSpecs[0]);
    
    h = (h < 10) ? '0' + h : h;
    m = (m < 10) ? '0' + m : m;
    
    $('#' + wtPrefix + input + wtSuffix).timesetter().setHour(h);
    $('#' + wtPrefix + input + wtSuffix).timesetter().setMinute(m);
    
    if(debug)
        console.log(input + ':' + $('#' + wtPrefix + input).val());
    
    translateValuesFromSelector(input);
}

function getUuidValues(name) {
    var uuidVal = $('#' + wtPrefix + name).val();
    
    if(uuidVal !== undefined && uuidVal.length > 0) {
        var uuid = JSON.parse($('script#' + name).text());
        if(uuid.length > 0) {
            uuidVal = uuid.id;
        }
    }
    
    if(uuidVal === undefined || uuidVal.length < 1) {
        uuidVal = null;
    }
    
    return uuidVal;
}

function translateUpUuid(uuid) {
    var uuidVal = $('#' + wtPrefix + uuid.name).val();
    
    if(uuidVal === undefined || uuidVal.length < 1) {
        if (debug) 
            console.log('missing Uuid element : ', uuid);
        return;
    }
    
    uuidVal = (uuidVal.indexOf(uuid.identifier) !== -1) ? uuidVal : uuid.identifier + uuidVal;
    $('#' + wtPrefix + uuid.name).val(uuidVal);
}

function setUuidValues(name, value) {
    $('#' + wtPrefix + name).val(value);
}

function setHourValues(name, value) {
    if(value === undefined) 
        return;
    var hoursValue = value.split('T')[1].split(':');
    hoursValue = { h: parseInt(hoursValue[0]), m: parseInt(hoursValue[1])};
    
    $('#' + wtPrefix + name + '_hour').val(hoursValue.h);
    $('#' + wtPrefix + name + '_minute').val(hoursValue.m);
}

function getHoursStringForJson(name) {
    var hoursValue = {
        h: parseInt($('#' + wtPrefix + name + '_hour').val()), 
        m: parseInt($('#' + wtPrefix + name + '_minute').val())
    };
    
    hoursValue.h = (hoursValue.h < 10) ? '0' + hoursValue.h : hoursValue.h;
    hoursValue.m = (hoursValue.m < 10) ? '0' + hoursValue.m : hoursValue.m;
    
    return '1970-01-01T' + hoursValue.h + ':' + hoursValue.m + ':00.000+01:00';
}

function getSimpleValues(name, setnull, integer) {
    var val = $('#' + wtPrefix + name).val()
    if((setnull && val.length < 1) || integer) {
        if(integer && (!setnull)) 
            val = parseInt(val);
        else if (setnull && val.length < 1) 
            val = null;
        else if(!setnull && val.length < 1) 
            val = 0;
    }
    
    return val;
}

function setSimpleValues(name, value) {
    $('#' + wtPrefix + name).val(value);
}

function setCbValue(name, value) {
    if (value) 
        $('#' + wtPrefix + name).prop('checked', true); 
    else 
        $('#' + wtPrefix + name).prop('checked', false);
}

function getCbValue(name) {
    return $('#' + wtPrefix + name).is(':checked');
}

function setParamValues(param) {
    for(const field of wtSimpleFields) 
        setSimpleValues(field, param[field]);
    
    for(const uuid of wtUuidFields) {
        setUuidValues(uuid.name, param[uuid.name]);
        translateUpUuid(uuid);
    }
    
    for(const cb of wtCbFields) 
        setCbValue(cb, param[cb]);
    
    for(const durField of wtFields) {
        setSimpleValues(durField, param[durField]);
        translateValuesFromAjax(durField);
    }
    
    for(const hour of wtHoursFields) 
        setHourValues(hour, param[hour]);
    
    for (const f of wtIntFields) 
        setSimpleValues(f, param[f]);
    
}

function prepareValuesForAjax() {
    var res = wtJsonRepresentation;
    
    for(const input of wtFields) {
        translateValuesFromSelector(input);
        translateValuesForAjax(input);
        res[input] = getSimpleValues(input);
        
        translateValuesFromAjax(input);
    }
    
    for(const input of wtSimpleFields) {
        res[input] = getSimpleValues(input);
    }
    
    for(const input of wtHoursFields) {
        res[input] = getHoursStringForJson(input);
    }
    
    for (const f of wtUuidFields) {
        setSimpleValues(f.name, getUuidValues(f.name));
        translateUpUuid(f);
        res[f.name] = getSimpleValues(f.name, true);
    }
    
    for (const f of wtCbFields) 
        res[f] = getCbValue(f);
    
    for (const f of wtIntFields) 
        res[f] = getSimpleValues(f, false, true);

    if(debug)
        console.log('res :' , res);
    
    return res;
}

function loadGlobalParam() {
    globalParam = JSON.parse($('#globalParam').text());
    if(debug) 
        console.log('globalParam : ', globalParam);
    
    if(globalParam.id !== null) 
        getOneWtParameter(globalParam.id, setParamValues);
}

$(document).ready(function(){
    if($('#params-form').length > 0) {
        /*
        $('#params-form-save').click(function(e){
            var values = prepareValuesForAjax();
            
            if(debug)
                console.log('prepared', values);
            
            var id = JSON.parse($('script#globalParam').text()).id;
            if(id === null) {
                if(debug)
                    console.log('id is null :', id);
            
                postOneWtParameter(values, setParamValues);
            } else {
                if(debug)
                    console.log('id is not null :', id);
                putOneWtParameter(id, values, setParamValues);
            }
          
            if(debug)
                console.log('values :', values);
    
        });
       */
        paramsFormSave.owner = $('#params-form-save');
        recordSmartEvent(paramsFormSave);
        
        loadGlobalParam();
        
    }
    
});
