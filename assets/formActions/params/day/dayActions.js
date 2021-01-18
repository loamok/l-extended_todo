const debug = false;

import { getOneWtParameter, postOneWtParameter, putOneWtParameter } from '../../../api/wt_parameters/wt_parameters';
import { getOneDayParameters, postOneDayParameters, putOneDayParameters } from '../../../api/day_parameters/day_parameters';

import { getTimeVal, getTimeValTs, addTime, subsTime, parseIntTime, checkTimeVals, setTimeVal, zeroVal } from '../../../js/let/let_utils';

var callbackEnded = true;
var globalParam = null;
var globalDayParams = null;

import {  
    fields as wtFields, suffix as wtSuffix, 
    prefix as wtPrefix, hoursFields as wtHoursFields, 
    simpleFields as wtSimpleFields, uuidFields as wtUuidFields, 
    cbFields as wtCbFields, intFields as wtIntFields, jsonRepresentation as wtJsonRepresentation
} from './wtFields';
//import {  
//    fields as dayFields, 
//    prefix as dayPrefix, hoursFields as dayHoursFields, 
//    uuidFields as dayUuidFields, jsonRepresentation as dayJsonRepresentation
//} from './day/dayParamFields';

function translateValuesFromSelector(input) {
    var h = $('#' + wtPrefix + input + wtSuffix).timesetter().getHoursValue();
    var m = $('#' + wtPrefix + input + wtSuffix).timesetter().getMinutesValue();

    h = (h < 10) ? '0' + h : h;
    m = (m < 10) ? '0' + m : m;

    $('#'+wtPrefix+input).val('' + h + ':' + m);
    if(debug)
        console.log(input + ':' + $('#'+wtPrefix+input).val());
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
    
    $('#' + wtPrefix + input+wtSuffix).timesetter().setHour(h);
    $('#' + wtPrefix + input+wtSuffix).timesetter().setMinute(m);
    
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
    $('#'+wtPrefix+ uuid.name).val(uuidVal);
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
function setDayParamValues(param) {
    for(const field of dayParamsFields) 
        setSimpleValues(wtPrefix + wtDayParametersIdsPrefix + field, param[field]);
    
    for(const uuid of wtUuidFields) {
        setUuidValues(uuid.name, param[uuid.name]);
        translateUpUuid(uuid);
    }
    
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
    var res = jsonRepresentation;
    
    for(const input of wtFields) {
        translateValuesFromSelector(input);
        translateValuesForAjax(input);
        if(input.includes(wtDayParametersIdsPrefix)) 
            res = addDayFieldToSomething(input, res, getSimpleValues(input));
        else 
            res[input] = getSimpleValues(input);
        
        translateValuesFromAjax(input);
    }
    
    for(const input of wtSimpleFields) {
        res[input] = getSimpleValues(input);
    }
    
    for(const input of wtHoursFields) {
        if(input.includes(wtDayParametersIdsPrefix)) 
            res = addDayFieldToSomething(input, res, getHoursStringForJson(input));
        else 
            res[input] = getHoursStringForJson(input);
    }
    
    for (const f of wtUuidFields) {
        if(f.name.includes(wtDayParametersIdsPrefix)) {
            setSimpleValues(f.name, getUuidValues(f.name));
//            @todo res = addDayFieldToSomething(input, res, getSimpleValues(input));
        } else {
            setSimpleValues(f.name, getUuidValues(f.name));
            translateUpUuid(f);
            res[f.name] = getSimpleValues(f.name, true);
        }
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
function loadGlobalDayParams() {
    globalDayParams = JSON.parse($('#dayParameters_id').text());
    if(debug) 
        console.log('globalDayParams: ', globalDayParams);
    
    if(globalDayParams.id !== null) 
        getOneDayParameters(globalDayParams.id, setDayParamValues);
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
        $('.pauseCallbackDuration').each(function (i,e) {
            dayParamsSetPauseCallbackDuration(this);
        });
        $('.pauseCallbackFields').each(function (i,e) {
            dayParamsSetPauseCallbackFields(this);
        });
        $('#params-form-save').click(function(e){
            var values = prepareValuesForAjax();
            
            console.log('prepared', values);
//            return; 
//            /* @todo */
            var id = JSON.parse($('script#globalParam').text()).id;
            if(id === null) {
//                if(debug)
                    console.log('id is null :', id);
                globalDayParams = values.dayParameters;
                values.dayParameters = null;
                postOneWtParameter(values, setParamValues);
                
            } else {
//                if(debug)
                    console.log('id is not null :', id);
                putOneWtParameter(id, values, setParamValues);
            }
             /**/
//            if(debug)
                console.log('values :', values);
    
        });

        loadGlobalParam();
        
    }
});
