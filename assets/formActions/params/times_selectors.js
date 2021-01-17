
import { paramsFieldsIdsPrefix, paramsFieldsIdsSuffix , paramsFieldsIds } from './paramFields';
    
const commonParams = {
    hour: {
        value: 0,
        min: 0,
        max: 24,
        step: 1,
        symbol: 'h'
    },
    minute: {
        value: 0,
        min: 0,
        max: 60,
        step: 5,
        symbol: 'm'
    },
    direction: 'increment',
    postfixText: '',
    numberPaddingChar: '0'
};

$(document).ready(function(){
    if($('#params-form').length > 0) {
        for(const input of paramsFieldsIds) {
            $('#' + paramsFieldsIdsPrefix + input + paramsFieldsIdsSuffix).timesetter(commonParams);
        }
    }
});
