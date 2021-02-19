/* global global */

const smartEventDefine = global.smartEventDefine;
const debug = false;

import { postPrepareQuery } from '../../../api/asyncWttActions/prepareAction';

var prepareTableEvent = { ...smartEventDefine };
prepareTableEvent.event = 'click';

prepareTableEvent.handler = function (obj, event) {
    sendPostPrepareQuery();
};

var prepareParams = {
    agenda: null,
    wtParameters: null,
    dayParameters: null,
    mode: 'month',    
};

function sendPostPrepareQuery() {
    prepareParams.agenda = JSON.parse($('script#agenda').text()).id;
    prepareParams.wtParameters = JSON.parse($('script#globalParam').text()).id;
    prepareParams.dayParameters = JSON.parse($('script#dayParameters').text()).id;
    
    postPrepareQuery(prepareParams, function(data) {
        console.log('prepareMe : ', data);
    });
}

$(document).ready(function(){
    if($('#btn-calculator-action').length > 0) {
        
        $('#btn-calculator-action').smartEvent(prepareTableEvent);
        
    }
});
