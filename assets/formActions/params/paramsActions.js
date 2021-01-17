/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
import { getOneWtParameter, postOneWtParameter, putOneWtParameter } from '../../api/wt_parameters/wt_parameters';

const debug = false;

var callbackEnded = true;
var globalParam = null;

import { 
    addDayFieldToSomething,
    paramsFieldsIds, paramsFieldsIdsSuffix, paramsFieldsDayParametersIdsPrefix,
    paramsAutoDurationFieldsIds, paramsFieldsIdsPrefix, paramsHoursFieldsIds, 
    paramsSimpleFieldsIds, paramsUuidFieldsIds, paramsCbFieldsIds, paramsIntFieldsIds, jsonRepresentation
} from './paramFields';

function translateValuesFromSelector(input) {
    var h = $('#'+paramsFieldsIdsPrefix+input+paramsFieldsIdsSuffix).timesetter().getHoursValue();
    var m = $('#'+paramsFieldsIdsPrefix+input+paramsFieldsIdsSuffix).timesetter().getMinutesValue();

    h = (h < 10) ? '0'+h : h;
    m = (m < 10) ? '0'+m : m;

    $('#'+paramsFieldsIdsPrefix+input).val(""+ h + ":"+ m);
    if(debug)
        console.log(input + ':' + $('#'+paramsFieldsIdsPrefix+input).val());
}

function translateValuesForAjax(input) {
    var hoursMinVals = $('#'+paramsFieldsIdsPrefix+input).val().split(':');
    if(debug)
        console.log('hoursMinVals :', hoursMinVals);
    
    $('#'+paramsFieldsIdsPrefix+input).val("P0Y0M0DT"+parseInt(hoursMinVals[0])+"H"+parseInt(hoursMinVals[1])+"M0S");
    
    if(debug)
        console.log(input + ':' + $('#'+paramsFieldsIdsPrefix+input).val());
    
}

function translateValuesFromAjax(input) {
    var intervalS = $('#'+paramsFieldsIdsPrefix+input).val();
    var dateTimeSpec = intervalS.split('T');
    if(dateTimeSpec[1] === undefined) {
        return;
    }
    var hoursMinSpecs = dateTimeSpec[1].split('H');
    var minSecSpecs = hoursMinSpecs[1].split('M');
    var h = parseInt(hoursMinSpecs[0]);
    var m = parseInt(minSecSpecs[0]);
    
    h = (h < 10) ? '0'+h : h;
    m = (m < 10) ? '0'+m : m;
    
    $('#'+paramsFieldsIdsPrefix+input+paramsFieldsIdsSuffix).timesetter().setHour(h);
    $('#'+paramsFieldsIdsPrefix+input+paramsFieldsIdsSuffix).timesetter().setMinute(m);
    
    if(debug)
        console.log(input + ':' + $('#'+paramsFieldsIdsPrefix+input).val());
    
    translateValuesFromSelector(input);
}

function getUuidValues(name) {
    var uuidVal = $('#'+paramsFieldsIdsPrefix+ name).val();
    
    if(uuidVal.length < 1) {
        var uuid = JSON.parse($('script#'+name).text());
        if(uuid.length > 0) {
            uuidVal = uuid.id;
        }
    }
    
    if(uuidVal.length < 1) {
        uuidVal = null;
    }
    
    return uuidVal;
}

function translateUpUuid(uuid) {
    var uuidVal = $('#'+paramsFieldsIdsPrefix+ uuid.name).val();
    
    if(uuidVal === undefined || uuidVal.length < 1) {
        if (debug) 
            console.log('missing Uuid element : ', uuid);
        return;
    }
    
    uuidVal = (uuidVal.indexOf(uuid.identifier) !== -1)?uuidVal:uuid.identifier+uuidVal;
    
    $('#'+paramsFieldsIdsPrefix+ uuid.name).val(uuidVal);
}

function setUuidValues(name, value) {
    $('#'+paramsFieldsIdsPrefix+ name).val(value);
}

function setHourValues(name, value) {
    if(value === undefined) {
        return;
    }
    var hoursValue = value.split('T')[1].split(':');
    hoursValue = { h: parseInt(hoursValue[0]), m: parseInt(hoursValue[1])};
    
    $('#'+paramsFieldsIdsPrefix+ name + "_hour").val(hoursValue.h);
    $('#'+paramsFieldsIdsPrefix+ name + "_minute").val(hoursValue.m);
}

function getHoursStringForJson(name) {
    var hoursValue = {
        h: parseInt($('#'+paramsFieldsIdsPrefix+ name + "_hour").val()), 
        m: parseInt($('#'+paramsFieldsIdsPrefix+ name + "_minute").val())
    };
    
    hoursValue.h = (hoursValue.h < 10) ? '0' + hoursValue.h : hoursValue.h;
    hoursValue.m = (hoursValue.m < 10) ? '0' + hoursValue.m : hoursValue.m;
    
    return "1970-01-01T" + hoursValue.h + ":" + hoursValue.m + ":00.000+01:00";
}

function getSimpleValues(name, setnull, integer) {
    var val = $('#'+paramsFieldsIdsPrefix+ name).val()
    if((setnull && val.length < 1) || integer) {
        if(integer && (!setnull)) {
            val = parseInt(val);
        } else if (setnull && val.length < 1) {
            val = null;
        } else if(!setnull && val.length < 1) {
            val = 0;
        }
    }
    
    return val;
}

function setSimpleValues(name, value) {
    $('#'+paramsFieldsIdsPrefix+ name).val(value);
}

function setCbValue(name, value) {
    if (value) 
        $('#'+paramsFieldsIdsPrefix+ name).prop("checked", true); 
    else 
        $('#'+paramsFieldsIdsPrefix+ name).prop("checked", false);
}

function getCbValue(name) {
    return $('#'+paramsFieldsIdsPrefix+ name).is(':checked');
}

function setParamValues(param) {
    
    for(const field of paramsSimpleFieldsIds) {
        setSimpleValues(field, param[field]);
    }
    
    for(const uuid of paramsUuidFieldsIds) {
        setUuidValues(uuid.name, param[uuid.name]);
        translateUpUuid(uuid);
    }
    
    for(const cb of paramsCbFieldsIds) {
        setCbValue(cb, param[cb]);
    }
    
    for(const durField of paramsFieldsIds) {
        setSimpleValues(durField, param[durField]);
        translateValuesFromAjax(durField);
    }
    
    for(const hour of paramsHoursFieldsIds) {
        setHourValues(hour, param[hour]);
    }
    
    
    for (const f of paramsIntFieldsIds) {
        setSimpleValues(f, param[f]);
    }
    
}

function prepareValuesForAjax() {
    var res = jsonRepresentation;
    
    for(const input of paramsFieldsIds) {
        translateValuesFromSelector(input);
        translateValuesForAjax(input);
        if(input.includes(paramsFieldsDayParametersIdsPrefix)) {
            res = addDayFieldToSomething(input, res, getSimpleValues(input));
        } else {
            res[input] = getSimpleValues(input);
        }
        translateValuesFromAjax(input);
    }
    
    for(const input of paramsSimpleFieldsIds) {
        res[input] = getSimpleValues(input);
    }
    
    for(const input of paramsHoursFieldsIds) {
        if(input.includes(paramsFieldsDayParametersIdsPrefix)) {
            res = addDayFieldToSomething(input, res, getHoursStringForJson(input));
        } else {
            res[input] = getHoursStringForJson(input);
        }
    }
    
    for (const f of paramsUuidFieldsIds) {
        if(f.name.includes(paramsFieldsDayParametersIdsPrefix)) {
            setSimpleValues(f.name, getUuidValues(f.name));
//            @todo res = addDayFieldToSomething(input, res, getSimpleValues(input));
        } else {
            setSimpleValues(f.name, getUuidValues(f.name));
            translateUpUuid(f);
            res[f.name] = getSimpleValues(f.name, true);
        }
    }
    
    for (const f of paramsCbFieldsIds) {
        res[f] = getCbValue(f);
    }
    
    for (const f of paramsIntFieldsIds) {
        res[f] = getSimpleValues(f, false, true);
    }

    if(debug)
        console.log('res :' , res);
    
    return res;
}

function loadGlobalParam() {
    globalParam = JSON.parse($('#globalParam').text());
    if(debug) 
        console.log("globalParam : ", globalParam);
    
    if(globalParam.id !== null) 
        getOneWtParameter(globalParam.id, setParamValues);
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
    var startValH = $('#'+startId+"_hour").val();
    startValH = (startValH > 9)?startValH:(startValH>0)?'0'+startValH:"00";
    var startValM = $('#'+startId+"_minute").val();
    startValM = (startValM > 9)?startValM:(startValM>0)?'0'+startValM:"00";
    var startVal = new Date("1970-01-01T"+startValH+":"+startValM+":00");
    /* end */
    const endId = end.attr('id');
    var endValH = $('#'+endId+"_hour").val();
    endValH = (endValH > 9)?endValH:(endValH>0)?'0'+endValH:"00";
    var endValM = $('#'+endId+"_minute").val();
    endValM = (endValM > 9)?endValM:(endValM>0)?'0'+endValM:"00";
    var endVal = new Date("1970-01-01T"+endValH+":"+endValM+":00");
    /* duration */
    const durId = duration.attr('id');
    var durValH = $('#'+durId).timesetter().getHoursValue();
    durValH = (durValH > 9)?durValH:(durValH>0)?'0'+durValH:"00";
    var durValM = $('#'+durId).timesetter().getMinutesValue();
    durValM = (durValM > 9)?durValM:(durValM>0)?'0'+durValM:"00";
    var durVal = new Date("1970-01-01T"+durValH+":"+durValM+":00");
    
    if(debug) {
        console.log('startVal : ', startVal);
        console.log('endVal : ', endVal);
        console.log('durVal : ', durVal);
    }
    
    startValH = parseInt(startValH);
    startValM = parseInt(startValM);
    durValH = parseInt(durValH);
    durValM = parseInt(durValM);
    endValH = parseInt(endValH);
    endValM = parseInt(endValM);
    var finalStartH;
    var finalStartM;
    var finalDurH;
    var finalDurM;
    var finalEndH;
    var finalEndM;
    
    switch (trigger) {
        case 'start':
            finalStartH = startValH;
            finalStartM = startValM;
            
            if(durValH > 0 || durValM > 0) {
                finalDurH = durValH;
                finalDurM = durValM;
                finalEndH = startValH + durValH;
                finalEndM = startValM + durValM;
                
                if(finalEndM >= 60) {
                    finalEndM -= 60;
                    finalEndH += 1;
                }
                
            } else if(endValH > 0 || endValM > 0) {
                finalEndH = endValH;
                finalEndM = endValM;
                if(endValH < startValH)
                    endValH += 24;
                finalDurH = endValH - startValH;
                if(endValM < startValM)
                    endValM += 60;
                finalDurM = endValM - startValM;
                
                if(finalDurM >= 60) { 
                    finalDurM -= 60;
                    finalDurH += 1;
                }
                
            } else if (durValH < 1 && durValM < 1) {
                finalEndH = finalStartH;
                finalEndM = finalStartM;                
            }
            break;
        case 'end':
            finalEndH = endValH;
            finalEndM = endValM;
            
            if(durValH > 0 || durValM > 0) {
                finalDurH = durValH;
                finalDurM = durValM;
                finalStartH = endValH - durValH;
                finalStartM = endValM - durValM;
                
                if(finalStartM < 0) {
                    finalStartM += 60;
                    finalStartH -= 1;
                }
            } else if(startValH > 0 || startValM > 0) {
                finalStartH = startValH;
                finalStartM = startValM;
                
                if(endValH < startValH)
                    endValH += 24;
                finalDurH = endValH - startValH;
                if(endValM < startValM)
                    endValM += 60;
                finalDurM = endValM - startValM;
                
                if(finalDurM >= 60) { 
                    finalDurM -= 60;
                    finalDurH += 1;
                }
                
            } else if (durValH < 1 && durValM < 1) {
                finalStartH = finalEndH;
                finalStartM = finalEndM;                
            }
            break;
        case 'duration':
            finalDurH = durValH;
            finalDurM = durValM;
            
            if(startValH > 0 || startValM > 0) {
                finalStartH = startValH;
                finalStartM = startValM;
                finalEndH = startValH + durValH;
                finalEndM = startValM + durValM;
                
                if(finalEndM >= 60) {
                    finalEndM -= 60;
                    finalEndH += 1;
                }
            } else if(endValH > 0 || endValM > 0) {
                finalEndH = endValH;
                finalEndM = endValM;
                
                finalStartH = endValH - durValH;
                finalStartM = endValM - durValM;
                
                if(finalStartM < 0) { 
                    finalStartM += 60;
                    finalStartH -= 1;
                }
            }
            
            break;
            
        default:
            
            break;
    }
    
    if(finalDurH <= 0) 
        finalDurH += 24;
    if(finalDurH >= 24) 
        finalDurH -= 24;
    if(finalStartH < 0) 
        finalStartH += 24;
    if(finalStartH >= 24) 
        finalStartH -= 24;
    if(finalEndH < 0) 
        finalEndH += 24;
    if(finalEndH >= 24) 
        finalEndH -= 24;
    
    if(debug) {
        console.log('finalStart: ', {H: finalStartH, M: finalStartM});
        console.log('finalEndVal : ', {H: finalEndH, M: finalEndM});
        console.log('finalDurVal : ', {H: finalDurH, M: finalDurM});
    }
        
    $('#'+startId+"_hour").val(parseInt(finalStartH));
    if(finalStartH < 1)
        $('#'+startId+'_hour').val($('#'+startId+'_hour option[value="0"]').attr('value'));
    $('#'+startId+"_minute").val(parseInt(finalStartM));
    if(finalStartM < 1)
        $('#'+startId+'_minute').val($('#'+startId+'_minute option[value="0"]').attr('value'));
    $('#'+durId).timesetter().setHour(parseInt(finalDurH));
    $('#'+durId).timesetter().setMinute(parseInt(finalDurM));
    $('#'+endId+"_hour").val(parseInt(finalEndH));
    if(finalEndH < 1)
        $('#'+endId+'_hour').val($('#'+endId+'_hour option[value="0"]').attr('value'));
    $('#'+endId+"_minute").val(parseInt(finalEndM));
    if(finalEndM < 1)
        $('#'+endId+'_minute').val($('#'+startId+'_minute option[value="0"]').attr('value'));
    
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
        start = $('#'+$(element).data('start'));
        hasStart = true;
    } else {
        start = $(element);
    }
    if(typeof $(element).data('end') !== 'undefined') {
        end = $('#'+$(element).data('end'));
        hasEnd = true;
    } else {
        end = $(element);
    }
    if(typeof $(element).data('duration') !== 'undefined') {
        duration = $('#'+$(element).data('duration'));
        hasDuration = true;
    } else {
        duration = $(element);
        trigger = 'duration';
    }
    
    if(hasDuration && !hasStart) {
        trigger = 'start';
    } else if (hasDuration && !hasEnd) {
        trigger = 'end';
    }

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
            return; 
            /*
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
             * 
             */
            if(debug)
                console.log('values :', values);
    
        });

        loadGlobalParam();
        
    }
});
