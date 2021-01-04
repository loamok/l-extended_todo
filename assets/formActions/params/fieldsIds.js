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
    'name', 
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

export { 
    paramsFieldsIds, paramsFieldsIdsSuffix, paramsFieldsIdsPrefix, paramsHoursFieldsIds, 
    paramsSimpleFieldsIds, paramsUuidFieldsIds, paramsCbFieldsIds
};
