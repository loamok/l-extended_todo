const debug = false;

const prefix = 'wt_parameters_';
const suffix = '_ph';

export const wtDayParametersId = 'dayParameters';

const fields = [
    'baseLunchBreakDuration', 
    'extendedLunchBreakDuration', 
    'shortedLunchBreakDuration',
    'baseWorkDayHoursDuration', 
    'extendedWorkDayHoursDuration', 
    'shortedWorkDayHoursDuration',
    'baseTotalDayBreaksDuration', 
    'extendedTotalDayBreaksDuration', 
    'shortedTotalDayBreaksDuration'
];


const hoursFields = [
    'noWorkBefore', 
    'noWorkAfter'
];
const simpleFields = [
    'name'
];
const intFields = [
    'annualToilDaysNumber',
    'annualHolidayDaysNumber'
];
const cbFields = [
    'defaultConfig', 
    'active',
    'global'
];
const uuidFields = [
    { name: 'user', identifier: '/api/users/' },
    { name: 'agenda', identifier: '/api/agendas/' },
    { name: 'dayParameters', identifier: '/api/day_parameters/' }
];

const jsonRepresentation = { };

for (const f of fields) {
    jsonRepresentation[f] = null;
}

for (const f of hoursFields) {
    jsonRepresentation[f] = null;
}

for (const f of simpleFields) {
    jsonRepresentation[f] = null;
}

for (const f of uuidFields) {
    jsonRepresentation[f.name] = null;
}

for (const f of cbFields) {
    jsonRepresentation[f] = null;
}

if(debug) 
    console.log('representation', jsonRepresentation);

export { 
    suffix, prefix, fields, hoursFields, simpleFields, 
    uuidFields, cbFields, intFields, jsonRepresentation
};
