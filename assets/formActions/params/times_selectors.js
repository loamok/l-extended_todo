
import { prefix as wtFieldsIdsPrefix, suffix as wtFieldsIdsSuffix, fields as wtFieldsIds } from './wt/fields';
import { prefix as dayFieldsIdsPrefix, fields as dayFieldsIds } from './day/fields';
    
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
    if($('#wt-form').length > 0) {
        for(const input of wtFieldsIds) {
            $('#' + wtFieldsIdsPrefix + input + wtFieldsIdsSuffix).timesetter(commonParams);
        }
        for(const input of dayFieldsIds) {
            $('#' + wtFieldsIdsPrefix + dayFieldsIdsPrefix + input + wtFieldsIdsSuffix).timesetter(commonParams);
        }
    }
});
