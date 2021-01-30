/* global global */

const smartEventDefine = global.smartEventDefine;
const debug = false;

import { getOneWtParameter } from '../../../api/wt_parameters/wt_parameters';
import { getOneDayParameters, postOneDayParameters, putOneDayParameters } from '../../../api/day_parameters/day_parameters';

import { getTimeVal, getTimeValTs, addTime, subsTime, parseIntTime, checkTimeVals, setTimeVal, zeroVal } from '../../../js/let/let_utils';

var callbackEnded = true;
var globalParam = null;
var globalDayParams = null;

import {  
    fields as wtFields, suffix as wtSuffix, wtDayParametersId,
    prefix as wtPrefix, hoursFields as wtHoursFields, 
    simpleFields as wtSimpleFields, uuidFields as wtUuidFields, 
    cbFields as wtCbFields, intFields as wtIntFields, jsonRepresentation as wtJsonRepresentation
} from '../wt/fields';
import {  
    fields as dayFields, 
    prefix as dayBasePrefix, hoursFields as dayHoursFields, 
    uuidFields as dayUuidFields, jsonRepresentation as dayJsonRepresentation
} from './fields';

const dayPrefix = wtPrefix + dayBasePrefix;

var dayFormSave = { ...smartEventDefine };
var dayFormLoad = { ...smartEventDefine };
dayFormSave.event = 'wtParam:postRecord';
dayFormLoad.event = 'wtParam:postLoad';
dayFormLoad.handler = function (obj, event) {
    loadGlobalDayParams();
};
dayFormSave.handler = function (obj, event) {
    if(debug)
        console.log("event :", event);
    
    var start = new Date().getTime();
    while (global.allCBEnded === false && new Date().getTime() < start + 1000);
    
//    addCbToPending('dayFormSave');
    var id = JSON.parse($('script#dayParameters').text()).id;
    var paramId = event.wtParam.id;
    
    setSimpleValues('wtParameter', paramId);
    var values = prepareValuesForAjax();
            
    if(debug)
        console.log('preparedDay', values);

    if(id === null && paramId !== null) {
        if(debug) {
            console.log('id is null :', id);
            console.log('id is null, paramId :', paramId);
        }
        
        postOneDayParameters(values, setParamValues);
    } else if(paramId !== null) {
        if(debug) {
            console.log('id is not null :', id);
            console.log('id is not null, paramId :', paramId);
        }
        
        putOneDayParameters(id, values, setParamValues);
    }
    
};

function translateValuesFromSelector(input) {
    const inputId = '' + dayPrefix + input + wtSuffix;
    if(debug)
        console.log('inputId', inputId);
    var h = $('#' + inputId).timesetter().getHoursValue();
    var m = $('#' + inputId).timesetter().getMinutesValue();

    h = (h < 10) ? '0' + h : h;
    m = (m < 10) ? '0' + m : m;

    $('#' + dayPrefix + input).val('' + h + ':' + m);
    if(debug)
        console.log(input + ':' + $('#' + dayPrefix + input).val());
}

function translateValuesForAjax(input) {
    var hoursMinVals = $('#' + dayPrefix + input).val().split(':');
    if(debug) 
        console.log('hoursMinVals :', hoursMinVals);
    
    $('#' + dayPrefix + input).val('P0Y0M0DT' + parseInt(hoursMinVals[0]) + 'H' + parseInt(hoursMinVals[1]) + 'M0S');
    
    if(debug)
        console.log(input + ':' + $('#' + dayPrefix + input).val());
    
}

function translateValuesFromAjax(input) {
    var intervalS = $('#' + dayPrefix + input).val();
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
    
    $('#' + dayPrefix + input + wtSuffix).timesetter().setHour(h);
    $('#' + dayPrefix + input + wtSuffix).timesetter().setMinute(m);
    
    if(debug)
        console.log(input + ':' + $('#' + dayPrefix + input).val());
    
    translateValuesFromSelector(input);
}

function getUuidValues(name) {
    var uuidVal = $('#' + dayPrefix + name).val();
    if(debug)
        console.log('uuidVal ' + name, uuidVal);
    
    if((uuidVal === undefined || uuidVal.length <= 0) && $('script#' + name).length > 0 ) {
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
    var uuidVal = $('#' + dayPrefix + uuid.name).val();
    
    if(uuidVal === undefined || uuidVal.length < 1) {
        if (debug) 
            console.log('missing Uuid element : ', uuid);
        return;
    }
    
    uuidVal = (uuidVal.indexOf(uuid.identifier) !== -1) ? uuidVal : uuid.identifier + uuidVal;
    $('#' + dayPrefix + uuid.name).val(uuidVal);
}

function setUuidValues(name, value) {
    $('#' + dayPrefix + name).val(value);
}

function setHourValues(name, value) {
    if(value === undefined) 
        return;
    var hoursValue = value.split('T')[1].split(':');
    hoursValue = { h: parseInt(hoursValue[0]), m: parseInt(hoursValue[1])};
    
    $('#' + dayPrefix + name + '_hour').val(hoursValue.h);
    $('#' + dayPrefix + name + '_minute').val(hoursValue.m);
}

function getHoursStringForJson(name) {
    var hoursValue = {
        h: parseInt($('#' + dayPrefix + name + '_hour').val()), 
        m: parseInt($('#' + dayPrefix + name + '_minute').val())
    };
    
    hoursValue.h = (hoursValue.h < 10) ? '0' + hoursValue.h : hoursValue.h;
    hoursValue.m = (hoursValue.m < 10) ? '0' + hoursValue.m : hoursValue.m;
    
    return '1970-01-01T' + hoursValue.h + ':' + hoursValue.m + ':00.000+01:00';
}

function getSimpleValues(name, setnull, integer) {
    var val = $('#' + dayPrefix + name).val()
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
    $('#' + dayPrefix + name).val(value);
}

function setParamValues(param) {
//    removeCbFromPending('dayFormSave')
    $('script#dayParameters').text(JSON.stringify({id: param.id}));
    for(const uuid of dayUuidFields) {
        setUuidValues(uuid.name, param[uuid.name]);
        translateUpUuid(uuid);
    }
    
    for(const durField of dayFields) {
        setSimpleValues(durField, param[durField]);
        translateValuesFromAjax(durField);
    }
    
    for(const hour of dayHoursFields) 
        setHourValues(hour, param[hour]);
    
}

function prepareValuesForAjax() {
    var res = dayJsonRepresentation;
    
    for(const input of dayFields) {
        translateValuesFromSelector(input);
        translateValuesForAjax(input);
        res[input] = getSimpleValues(input);
        
        translateValuesFromAjax(input);
    }
    
    for(const input of dayHoursFields) {
        res[input] = getHoursStringForJson(input);
    }
    
    for (const f of dayUuidFields) {
        setSimpleValues(f.name, getUuidValues(f.name));
        translateUpUuid(f);
        res[f.name] = getSimpleValues(f.name, true);
        
    }
    
    if(debug)
        console.log('res :' , res);
    
    return res;
}

function loadGlobalDayParams() {
    globalDayParams = JSON.parse($('#' + wtDayParametersId).text());
    if(debug) 
        console.log('globalDayParams: ', globalDayParams);
    
    if(globalDayParams.id !== null) 
        getOneDayParameters(globalDayParams.id, setParamValues);
}

function dayParamsCalculateDurationsAndBounds(start, end, duration, trigger) {
    if(!callbackEnded)
        return false;
    
    callbackEnded = false;
    
    if(debug) {
        console.log('start : ', start);
        console.log('end : ', end);
        console.log('duration : ', duration);
        console.log('trigger : ', trigger);
    }
    
    /* récupération des valeurs */
    /* start */
    const startId = start.attr('id');
    var startVal = getTimeVal(startId) ;
    /* end */
    const endId = end.attr('id');
    var endVal = getTimeVal(endId) ;
    /* duration */
    const durId = duration.attr('id');
    var durVal = getTimeValTs(durId) ;
    
    startVal = parseIntTime(startVal);
    durVal = parseIntTime(durVal);
    endVal = parseIntTime(endVal);
    
    if(debug) {
        console.log('startVal : ', startVal);
        console.log('endVal : ', endVal);
        console.log('durVal : ', durVal);
    }
    
    var finalStart;
    var finalDur;
    var finalEnd;
    
    if(debug) console.log('trigger: ', trigger);
    finalStart = startVal;
    finalDur = durVal;
    finalEnd = endVal;
    
    switch (trigger) {
        case 'start':            
            if(finalDur.H > 0 || finalDur.M > 0) 
                finalEnd = addTime(finalStart, finalDur);
            else if(finalEnd.H > 0 || endVal.M > 0) 
                finalDur = subsTime(finalEnd, finalStart);
            else if (finalDur.H < 1 && finalDur.M < 1) 
                finalEnd = finalStart;
            
            break;
        case 'end':
            if(finalDur.H > 0 || finalDur.M > 0) 
                finalStart = subsTime(finalEnd, finalDur);
            else if(finalStart.H > 0 || finalStart.M > 0) 
                finalDur = subsTime(finalEnd, finalStart);
            else if (finalDur.H < 1 && finalDur.M < 1) 
                finalStart = finalEnd;
            
            break;
        case 'duration':
            if(finalStart.H > 0 || finalStart.M > 0) 
                finalEnd = addTime(finalStart, finalDur);
            else if(finalEnd.H > 0 || finalEnd.M > 0) 
                finalStart = subsTime(finalEnd, finalDur);
            
            break;
    }
    
    finalDur = checkTimeVals(finalDur);
    if(finalDur === null) finalDur = zeroVal;
    finalStart = checkTimeVals(finalStart);
    if(finalStart === null) finalStart = zeroVal;
    finalEnd = checkTimeVals(finalEnd);
    if(finalEnd === null) finalEnd = zeroVal;
    
    if(debug) 
        console.log('final Vars: ', {finalStart: finalStart, finalDur: finalDur, finalEnd: finalEnd});
        
    setTimeVal(startId, finalStart);
    $('#' + durId).timesetter().setHour(parseInt(finalDur.H));
    $('#' + durId).timesetter().setMinute(parseInt(finalDur.M));
    setTimeVal(endId, finalEnd);
    
    callbackEnded = true;
}
function dayParamsExecPauseCallbackDuration(event, element) {
    var hasStart = false;
    var hasEnd = false;
    var hasDuration = false;
    var duration;
    var start;
    var end;
    var trigger;
    
    if(typeof $(element).data('start') !== 'undefined') {
        start = $('#' + $(element).data('start'));
        hasStart = true;
    } else 
        start = $(element);
    
    if(typeof $(element).data('end') !== 'undefined') {
        end = $('#' + $(element).data('end'));
        hasEnd = true;
    } else 
        end = $(element);
    
    if(typeof $(element).data('duration') !== 'undefined') {
        duration = $('#' + $(element).data('duration'));
        hasDuration = true;
    } else {
        duration = $(element);
        trigger = 'duration';
    }
    
    if(hasDuration && !hasStart) {
        trigger = 'start';
    } else if (hasDuration && !hasEnd) 
        trigger = 'end';

    dayParamsCalculateDurationsAndBounds(start, end, duration, trigger);
}
function dayParamsSetPauseCallbackDuration(element) {
    $(element).timesetter().change(function(e) {
        dayParamsExecPauseCallbackDuration(e, this);
    });
}
function dayParamsSetPauseCallbackFields(element) {
    $(element).change(function(e) {
        dayParamsExecPauseCallbackDuration(e, this);
    });
}

$(document).ready(function(){
    if($('#params-form').length > 0) {
        $('.pauseCallbackDuration').each(function () {
            dayParamsSetPauseCallbackDuration(this);
        });
        $('.pauseCallbackFields').each(function () {
            dayParamsSetPauseCallbackFields(this);
        });
//        $('#params-form-save').click(function(){
//              return; 
//            /* @todo trouver une méthode pour 'empiler' les callbacks */
        dayFormSave.owner = $('#params-form-save');
        recordSmartEvent(dayFormSave, 5);
        dayFormLoad.owner = $('#params-form-save');
        recordSmartEvent(dayFormLoad);
//        });

//        loadGlobalDayParams();
        
    }
});
