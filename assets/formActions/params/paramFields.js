const debug = true;

const paramsFieldsIdsPrefix = 'wt_parameters_';
const paramsFieldsDayParametersIdsPrefix = 'dayParameters_';
const paramsFieldsIdsSuffix = '_ph';
const paramsFieldsIds = [
    'baseLunchBreakDuration', 
    'extendedLunchBreakDuration', 
    'shortedLunchBreakDuration',
    'baseWorkDayHoursDuration', 
    'extendedWorkDayHoursDuration', 
    'shortedWorkDayHoursDuration',
    'baseTotalDayBreaksDuration', 
    'extendedTotalDayBreaksDuration', 
    'shortedTotalDayBreaksDuration',
    'dayParameters_amPauseDuration',
    'dayParameters_amPmPauseDuration',
    'dayParameters_pmPauseDuration'
];

const paramsAutoDurationFieldsIds = [
    {
        dayParameters_amPauseDuration: {
            start: 'dayParameters_amPauseStart',
            end: 'dayParameters_amPauseEnd'
        }
    }, {
        dayParameters_amPmPauseDuration: {
            start: 'dayParameters_amEnd',
            end: 'dayParameters_pmStart'
        }
    }, { 
        dayParameters_pmPauseDuration: {
            start: 'dayParameters_pmPauseStart',
            end: 'dayParameters_pmPauseEnd'
        }
    }
];
const paramsHoursFieldsIds = [
    'noWorkBefore', 
    'noWorkAfter',
    'dayParameters_amPauseStart',
    'dayParameters_amPauseEnd',
    'dayParameters_pmPauseStart',
    'dayParameters_pmPauseEnd',
    'dayParameters_amStart',
    'dayParameters_amEnd',
    'dayParameters_pmStart',
    'dayParameters_pmEnd'
];
const paramsSimpleFieldsIds = [
    'name'
];
const paramsIntFieldsIds = [
    'annualToilDaysNumber',
    'annualHolidayDaysNumber'
];
const paramsCbFieldsIds = [
    'defaultConfig', 
    'active',
    'global'
];
const paramsUuidFieldsIds = [
    { name: 'user', identifier: "/api/users/" },
    { name: 'agenda', identifier: "/api/agendas/" },
    { name: 'dayParameters_id', identifier: "/api/day_parameters/" },
    { name: 'dayParameters_wtParameter', identifier: "/api/wt_parameters/" }
];

const jsonRepresentation = { };
function addDayFieldToRepresentation(f) {
    const plop = addDayFieldToSomething(f, jsonRepresentation, null);
    jsonRepresentation['dayParameters'] = plop.dayParameters;
}
export function addDayFieldToSomething(f, something, value) {
    var dayParameters = something.dayParameters;
    const dp = {};
    const rg = new RegExp(paramsFieldsDayParametersIdsPrefix, "g");
    const name = f.replace(rg, '');
    const val = (value)?value:null;
    
    if(!dayParameters) {
        dayParameters = dp;
    }
    
    dayParameters[name] = val;
    
    something['dayParameters'] = dayParameters;
    
    return something;
}

for (const f of paramsFieldsIds) {
    if(f.includes(paramsFieldsDayParametersIdsPrefix)) {
        addDayFieldToRepresentation(f);
    } else {
        jsonRepresentation[f] = null;
    }
}

for (const f of paramsHoursFieldsIds) {
    if(f.includes(paramsFieldsDayParametersIdsPrefix)) {
        addDayFieldToRepresentation(f);
    } else {
        jsonRepresentation[f] = null;
    }
}

for (const f of paramsSimpleFieldsIds) {
    jsonRepresentation[f] = null;
}

for (const f of paramsUuidFieldsIds) {
    if(f.name.includes(paramsFieldsDayParametersIdsPrefix)) {
        addDayFieldToRepresentation(f.name);
    } else {
        jsonRepresentation[f.name] = null;
    }
}

for (const f of paramsCbFieldsIds) {
    jsonRepresentation[f] = null;
}

if(debug) console.log('representation', jsonRepresentation);

export { 
    paramsFieldsIdsSuffix, paramsFieldsIdsPrefix, paramsFieldsDayParametersIdsPrefix,
    paramsAutoDurationFieldsIds, paramsFieldsIds, paramsHoursFieldsIds, paramsSimpleFieldsIds, 
    paramsUuidFieldsIds, paramsCbFieldsIds, paramsIntFieldsIds, jsonRepresentation
};
