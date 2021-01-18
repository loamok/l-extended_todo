const debug = false;

const prefix = 'dayParameters_';

const fields = [
    'amPauseDuration',
    'amPmPauseDuration',
    'pmPauseDuration'
];

const autoDurationFields = [
    {
        key: prefix + 'amPauseDuration',
        val: {
            start: prefix + 'amPauseStart',
            end: prefix + 'amPauseEnd'
        }
    }, { 
        key: prefix + 'amPmPauseDuration',
        val: {
            start: prefix + 'amEnd',
            end: prefix + 'pmStart'
        }
    }, { 
        key: prefix + 'pmPauseDuration',
        val: {
            start: prefix + 'pmPauseStart',
            end: prefix + 'pmPauseEnd'
        }
    }
];

const hoursFields = [
    'amPauseStart', 'amPauseEnd', 'pmPauseStart',
    'pmPauseEnd', 'amStart', 'amEnd', 'pmStart', 'pmEnd'
];
const uuidFields = [
    { name: 'wtParameter', identifier: '/api/wt_parameters/' }
];

const jsonRepresentation = { };

function addDayFieldToRepresentation(f, representation) {
    const plop = addDayFieldToSomething(f, representation, null);
    representation['dayParameters'] = plop.dayParameters;
    
    return representation
}
export function addDayFieldToSomething(f, something, value) {
    var dayParameters = something.dayParameters;
    const dp = {};
    const rg = new RegExp(prefix, 'g');
    const name = f.replace(rg, '');
    const val = (value)?value:null;
    
    if(!dayParameters) {
        dayParameters = dp;
    }
    
    dayParameters[name] = val;
    
    something['dayParameters'] = dayParameters;
    
    return something;
}

for (const f of fields) {
    jsonRepresentation[f] = null;
}

for (const f of hoursFields) {
    jsonRepresentation[f] = null;
}

for (const f of uuidFields) {
    jsonRepresentation[f.name] = null;
}

if(debug) 
    console.log('representation', jsonRepresentation);

export { 
    prefix, autoDurationFields, fields, hoursFields, uuidFields, jsonRepresentation
};
