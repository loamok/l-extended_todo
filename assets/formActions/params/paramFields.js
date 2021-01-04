const paramsFieldsIdsPrefix = 'wt_parameters_';
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
    'shortedTotalDayBreaksDuration'
];
const paramsHoursFieldsIds = [
    'noWorkBefore', 
    'noWorkAfter'
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
    { name: 'agenda', identifier: "/api/agendas/" }
];

const jsonRepresentation = { };

for (const f of paramsFieldsIds) {
    jsonRepresentation[f] = null;
}

for (const f of paramsHoursFieldsIds) {
    jsonRepresentation[f] = null;
}

for (const f of paramsSimpleFieldsIds) {
    jsonRepresentation[f] = null;
}

for (const f of paramsUuidFieldsIds) {
    jsonRepresentation[f.name] = null;
}

for (const f of paramsCbFieldsIds) {
    jsonRepresentation[f] = null;
}

export { 
    paramsFieldsIdsSuffix, paramsFieldsIdsPrefix, 
    paramsFieldsIds, paramsHoursFieldsIds, paramsSimpleFieldsIds, 
    paramsUuidFieldsIds, paramsCbFieldsIds, paramsIntFieldsIds, jsonRepresentation
};
