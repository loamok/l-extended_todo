/* global global */

const smartEventDefine = global.smartEventDefine;
const debug = false;

import { postPrepareQuery } from '../../../api/asyncWttActions/prepareAction';

var prepareTableEvent = { ...smartEventDefine };
prepareTableEvent.event = 'click';

prepareTableEvent.handler = function (obj, event) {
    sendPostPrepareQuery();
};

const prepareParams = {
    agenda: null,
    dayParameters: null,
    paginateParams: null,
    wtParameters: null
};

function sendPostPrepareQuery() {
    var toSend = {
        ...prepareParams, 
        ...{
            agenda: JSON.parse($('script#agenda').text()).id, 
            dayParameters: JSON.parse($('script#dayParameters').text()).id,
            paginateParams: JSON.parse($('script#paginateParams').text()),
            wtParameters: JSON.parse($('script#globalParam').text()).id
        }};
    
    postPrepareQuery(toSend, function(data) {
        console.log('prepareMe : ', data);
    });
}

$(document).ready(function(){
    if($('#btn-calculator-action').length > 0) {
        
        $('#btn-calculator-action').smartEvent(prepareTableEvent);
        
    }
});
