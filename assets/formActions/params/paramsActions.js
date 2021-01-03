/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
import { getOneWtParameter, postOneWtParameter } from '../../api/wt_parameters/wt_parameters';

const debug = false;

var globalParam = null;

import { paramsFieldsIdsPrefix, paramsFieldsIdsSuffix , paramsFieldsIds } from './times_selectors';

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

function setParamValues(param) {
    $('#'+paramsFieldsIdsPrefix+"name").val(param.name);
    $('#'+paramsFieldsIdsPrefix+"user").val(param.user);
    $('#'+paramsFieldsIdsPrefix+"agenda").val(param.agenda);
    if (param.defaultConfig) 
        $('#'+paramsFieldsIdsPrefix+"defaultConfig").prop("checked", true); 
    else 
        $('#'+paramsFieldsIdsPrefix+"defaultConfig").prop("checked", false);
    if (param.active) 
        $('#'+paramsFieldsIdsPrefix+"active").prop("checked", true); 
    else 
        $('#'+paramsFieldsIdsPrefix+"active").prop("checked", false);
    if (param.global) 
        $('#'+paramsFieldsIdsPrefix+"global").prop("checked", true); 
    else 
        $('#'+paramsFieldsIdsPrefix+"global").prop("checked", false);
    
    $('#'+paramsFieldsIdsPrefix+"baseLunchBreakDuration").val(param.baseLunchBreakDuration);
    $('#'+paramsFieldsIdsPrefix+"extendedLunchBreakDuration").val(param.extendedLunchBreakDuration);
    $('#'+paramsFieldsIdsPrefix+"shortedLunchBreakDuration").val(param.shortedLunchBreakDuration);
    $('#'+paramsFieldsIdsPrefix+"baseWorkDayHoursDuration").val(param.baseWorkDayHoursDuration);
    $('#'+paramsFieldsIdsPrefix+"extendedWorkDayHoursDuration").val(param.extendedWorkDayHoursDuration);
    $('#'+paramsFieldsIdsPrefix+"shortedWorkDayHoursDuration").val(param.shortedWorkDayHoursDuration);
    $('#'+paramsFieldsIdsPrefix+"baseTotalDayBreaksDuration").val(param.baseTotalDayBreaksDuration);
    $('#'+paramsFieldsIdsPrefix+"extendedTotalDayBreaksDuration").val(param.extendedTotalDayBreaksDuration);
    $('#'+paramsFieldsIdsPrefix+"shortedTotalDayBreaksDuration").val(param.shortedTotalDayBreaksDuration);
    $('#'+paramsFieldsIdsPrefix+"annualToilDaysNumber").val(param.annualToilDaysNumber);
    $('#'+paramsFieldsIdsPrefix+"annualHolidayDaysNumber").val(param.annualHolidayDaysNumber);
    
    var noWorkBefore = param.noWorkBefore.split('T')[1].split(':');
    noWorkBefore = {'h': parseInt(noWorkBefore[0]), 'm': parseInt(noWorkBefore[1])};
    
    var noWorkAfter = param.noWorkAfter.split('T')[1].split(':');
    noWorkAfter = {'h': parseInt(noWorkAfter[0]), 'm': parseInt(noWorkAfter[1])};
    
    $('#'+paramsFieldsIdsPrefix+"noWorkBefore_hour").val(noWorkBefore.h);
    $('#'+paramsFieldsIdsPrefix+"noWorkBefore_minute").val(noWorkBefore.m);
    $('#'+paramsFieldsIdsPrefix+"noWorkAfter_hour").val(noWorkAfter.h);
    $('#'+paramsFieldsIdsPrefix+"noWorkAfter_minute").val(noWorkAfter.m);
    
    for(const input of paramsFieldsIds) {
        translateValuesFromAjax(input);
    }
}

function prepareValuesForAjax() {
    for(const input of paramsFieldsIds) {
        translateValuesForAjax(input);
    }
    
    var agenda = $('#'+paramsFieldsIdsPrefix+"agenda").val();
    var user = $('#'+paramsFieldsIdsPrefix+"user").val();
    var userVal = JSON.parse($("#user").text()).id;
    if(debug)
        console.log("userVal : ", userVal);
    userVal = (userVal.indexOf("/api/users/") !== -1)?userVal:"/api/users/"+userVal;
    if(debug)
        console.log("userVal : ", userVal);
    
    var defaultConfig = $('#'+paramsFieldsIdsPrefix+"defaultConfig").val();
    var active = $('#'+paramsFieldsIdsPrefix+"active").val();
    var global = $('#'+paramsFieldsIdsPrefix+"global").val();
    
    var noWorkBeforeH = $('#'+paramsFieldsIdsPrefix+"noWorkBefore_hour").val();
    noWorkBeforeH = (parseInt(noWorkBeforeH) < 10)?'0'+noWorkBeforeH:noWorkBeforeH;
    var noWorkBeforeM = $('#'+paramsFieldsIdsPrefix+"noWorkBefore_minute").val();
    noWorkBeforeM = (parseInt(noWorkBeforeM) < 10)?'0'+noWorkBeforeM:noWorkBeforeM;
    var noWorkBefore = "1970-01-01T"+noWorkBeforeH+":"+noWorkBeforeM+":00.000+01:00";
    
    var noWorkAfterH = $('#'+paramsFieldsIdsPrefix+"noWorkAfter_hour").val();
    noWorkAfterH = (parseInt(noWorkAfterH) < 10)?'0'+noWorkAfterH:noWorkAfterH;
    var noWorkAfterM = $('#'+paramsFieldsIdsPrefix+"noWorkAfter_minute").val();
    noWorkAfterM = (parseInt(noWorkAfterM) < 10)?'0'+noWorkAfterM:noWorkAfterM;
    var noWorkAfter = "1970-01-01T"+noWorkAfterH+":"+noWorkAfterM+":00.000+01:00";
    
    var res = {
        'name' : $('#'+paramsFieldsIdsPrefix+"name").val(),
        'user' : (user.length > 0)?user:userVal,
        'agenda' : (agenda.length > 0)?agenda:null,
        'defaultConfig' : (parseInt(defaultConfig) === 1)?true:false,
        'active' : (parseInt(active) === 1)?true:false,
        'global' : (parseInt(global) === 1)?true:false,
        'baseLunchBreakDuration' : $('#'+paramsFieldsIdsPrefix+"baseLunchBreakDuration").val(),
        'extendedLunchBreakDuration' : $('#'+paramsFieldsIdsPrefix+"extendedLunchBreakDuration").val(),
        'shortedLunchBreakDuration' : $('#'+paramsFieldsIdsPrefix+"shortedLunchBreakDuration").val(),
        'baseWorkDayHoursDuration' : $('#'+paramsFieldsIdsPrefix+"baseWorkDayHoursDuration").val(),
        'extendedWorkDayHoursDuration' : $('#'+paramsFieldsIdsPrefix+"extendedWorkDayHoursDuration").val(),
        'shortedWorkDayHoursDuration' : $('#'+paramsFieldsIdsPrefix+"shortedWorkDayHoursDuration").val(),
        'baseTotalDayBreaksDuration' : $('#'+paramsFieldsIdsPrefix+"baseTotalDayBreaksDuration").val(),
        'extendedTotalDayBreaksDuration' : $('#'+paramsFieldsIdsPrefix+"extendedTotalDayBreaksDuration").val(),
        'shortedTotalDayBreaksDuration' : $('#'+paramsFieldsIdsPrefix+"shortedTotalDayBreaksDuration").val(),
        'annualToilDaysNumber' : parseInt($('#'+paramsFieldsIdsPrefix+"annualToilDaysNumber").val()),
        'annualHolidayDaysNumber' : parseInt($('#'+paramsFieldsIdsPrefix+"annualHolidayDaysNumber").val()),
        'noWorkBefore' : noWorkBefore,
        'noWorkAfter' : noWorkAfter,
    };
    
    
    if(debug)
        console.log('res :' , res);
    
    for(const input of paramsFieldsIds) {
        translateValuesFromAjax(input);
    }
    
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
            for(const input of paramsFieldsIds) {
                translateValuesFromSelector(input);
            }
            var values = prepareValuesForAjax();
            postOneWtParameter(values, setParamValues);
//            if(debug)
                console.log('values :', values);
    
        });

        loadGlobalParam();
    }
});
