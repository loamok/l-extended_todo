/* global global */
const smartEventDefine = global.smartEventDefine;

var setStatusEvent = { ...smartEventDefine };
setStatusEvent.event = 'click';
setStatusEvent.handler = function (obj, event) {
    if(onedit[$(obj).attr('id')] === undefined) {
        makeStatusesSelectIn(obj);
        onedit[$(obj).attr('id')] = true;
    }
};

var onedit = [];

function getStatusesSelectOption(status) {
    return '<option value="' + status.id + '" data-code="' + status.code + '">' + status.label + '</option>';
}

function makeStatusesSelectIn(elem) {
    const cats = JSON.parse($('#tableView_categories').text());
    
    var $select = $('<select id="'+ $(elem).attr('id') +'_statuses"></select>').html("");
    
    for(const [key, cat] of Object.entries(cats)) {
        $select.append(getStatusesSelectOption(cat));
    }
    
    $(elem).append($select);
    $('#'+ $(elem).attr('id') +' option[value="'+ $(elem).data('statusid') +'"]').prop('selected', true);
}

$(document).ready(function(){
    if($('#tableView').length > 0) {
        $('.rowstatus').each(function(i, e){
            $('#'+$(e).attr('id')).smartEvent(setStatusEvent);
        });
        
    }
});
