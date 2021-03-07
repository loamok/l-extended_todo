/* global global */

const smartEventDefine = global.smartEventDefine;
const debug = false;

require('./edit_row');

import { postPrepareQuery } from '../../../api/asyncWttActions/prepareAction';

import { getAllCategories } from '../../../api/categories/categories';

var prepareTableEvent = { ...smartEventDefine };
prepareTableEvent.event = 'click';
prepareTableEvent.handler = function (obj, event) {
    sendPostPrepareQuery();
};

const TableEventStatusLoad = 'categories:postLoad';
var setStatusTableEvent = { ...smartEventDefine };
setStatusTableEvent.event = TableEventStatusLoad;
setStatusTableEvent.handler = function (obj, event) {
    setTableRowsStatuses(event);
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
        populateTable(data.params.elemsInRange);
    });
    var catTableScripts = $('#tableView_categories');
    if(catTableScripts.text().length == 0)  {
        getAllCategories(function(data) {
            var event = $.Event(TableEventStatusLoad);
            event.cbData = data;
            $('#btn-calculator-action').trigger(event);
        });
    }
}

function setTableRowsStatuses(event) {
    console.log('datas :', event.cbData);
    var cats = {};
    for(const cat of event.cbData) {
        cats[cat.id] = cat;
    }
    var catTableScripts = $('#tableView_categories');
    if(catTableScripts.length > 0) 
        $(catTableScripts).remove();
    
    $('#tableView').parent().append('<script type="application/json" id="tableView_categories">'+JSON.stringify(cats)+'</script>')
}

function dateTimeObjFromDateTimeStr(str) {
    var res = {
        originalStr: str,
        datePart: null,
        day: null,
        month: null,
        year: null,
        timeAndZone: null,
        timePart: null,
        hours: null,
        minutes: null,
        seconds: null,
        timeZone: null
    };
    var dateTimeParts = str.split('T');
    res.datePart = dateTimeParts[0];
    var dateParts = res.datePart.split('-');
    res.year = dateParts[0];
    res.month = dateParts[1];
    res.day = dateParts[2];
    res.timeAndZone = dateTimeParts[1];
    
    var timeParts = null;
    var timeAndZone = res.timeAndZone.split('-');
    if(timeAndZone.length > 1) {
        res.timeZone = '-' + timeAndZone[1];
    }
    if(timeAndZone.length === 1) {
        timeAndZone = res.timeAndZone.split('+');
        if(timeAndZone.length > 1) {
            res.timeZone = '+' + timeAndZone[1];
        }
    }
    
    res.timePart = timeAndZone[0];
    timeParts = timeAndZone[0].split(':');
    res.hours = timeParts[0];
    res.minutes = timeParts[1];
    res.seconds = timeParts[2].split('.')[0].substring(0,2);
    
    return res;
}

export function populateTable(elemsInRange) {
    
    for (const [key, elem] of Object.entries(elemsInRange)) {
        setDateTableElement(key, elem);
    }
}

function setDateHtmlTimeElem($elem, dateDesc) {
    $elem.attr('datetime', dateDesc.year + '-' + dateDesc.month + '-' + dateDesc.day + 'T00:00:00' + dateDesc.timeZone);
    $elem.html(dateDesc.year + '-' + dateDesc.month + '-' + dateDesc.day);
}

function setTimeHtmlTimeElem($elem, dateDesc) {
    $elem.attr('datetime', dateDesc.originalStr);
    $elem.html(dateDesc.hours + ':' + dateDesc.minutes + ':' + dateDesc.seconds);
}

export function setDateTableElement(key, elem) {
    var $trRow = $('#dr_' + key).parent();
    $trRow.attr('data-rowid', elem.object.id);
    $trRow.attr('id', 'rowid_' + key);
    // @todo indiquer le type de ligne (todo, event)
    $trRow.attr('data-type', elem.type);
    
    var $drTime = $('#dr_' + key + ' time');
    var $dsTime = $('#ds_' + key + ' time');
    var $dayAmEndTime = $('#dame_' + key + ' time');
    var $dayPmStartTime = $('#dpms_' + key + ' time');
    var $deTime = $('#de_' + key + ' time');
    const elemStartAt = dateTimeObjFromDateTimeStr(elem.object.startAt);
    const elemMeridBreakStart = dateTimeObjFromDateTimeStr(elem.relateds['meridian-break'].startAt);
    const elemMeridBreakEnd = dateTimeObjFromDateTimeStr(elem.relateds['meridian-break'].endAt);
    const elemEndAt = dateTimeObjFromDateTimeStr(elem.object.endAt);
    
    setDateHtmlTimeElem($drTime, elemStartAt);
    setTimeHtmlTimeElem($dsTime, elemStartAt);
    setTimeHtmlTimeElem($deTime, elemEndAt);
    
    setTimeHtmlTimeElem($dayAmEndTime, elemMeridBreakStart);
    setTimeHtmlTimeElem($dayPmStartTime, elemMeridBreakEnd);
    
    var categories = elem.object.categories.slice(0);
    const cat = categories.shift().split('/').pop();
    if($('#tableView_categories').length > 0) {
        const cats = JSON.parse($('#tableView_categories').text());
        if(cats[cat] !== undefined) {
            $('#stat_' + key).parent().addClass('category_' + cats[cat].code.replace(/-/g, '_'));
            $('#stat_' + key).html(cats[cat].label);
        }
    }
    $('#stat_' + key).attr('data-statusid', cat);
}

$(document).ready(function(){
    if($('#btn-calculator-action').length > 0) {
        $('#btn-calculator-action').smartEvent(prepareTableEvent);
        $('#btn-calculator-action').smartEvent(setStatusTableEvent);
        
    }
});
