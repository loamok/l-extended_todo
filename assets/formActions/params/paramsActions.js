/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
import { getOneWtParameter, postOneWtParameter, putOneWtParameter } from '../../api/wt_parameters/wt_parameters';

const debug = false;

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
    
    if(uuidVal.length < 1) {
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
    if(debug) {
        console.log("globalParam : ", globalParam);
    }
    if(globalParam.id !== null) {
        getOneWtParameter(globalParam.id, setParamValues);
    }
}

$(document).ready(function(){
    if($('#params-form').length > 0) {
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
