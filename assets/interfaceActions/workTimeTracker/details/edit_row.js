/* global global */
const smartEventDefine = global.smartEventDefine;

import { putOneTodo } from '../../../api/todos/todos';
import { getAllCategories } from '../../../api/categories/categories';

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

function deleteTodo(obj, event) {
    console.log('obj : ', obj);
    console.log('event elemId : ', event.elemId);
    
}

function saveStatusTodo(obj, event) {
    console.log('obj : ', obj);
    console.log('event elemId : ', event.elemId);
    
    var idParts = $(obj).attr('id').split('_');
    var $row = $('tr#rowid_' + idParts[idParts.length - 1]);
    console.log('idParts : ', idParts);
    console.log('row : ', $row);
    console.log('row obj id : ', $row.data('rowid'));
    
    putOneTodo($row.data('rowid'), {categories: ["/api/categories/" +$('select#stat_'+idParts[idParts.length - 1]+'_statuses').val()]}, function(data) {
        // @todo vider la cellule et purger l'évènement click du bouton save
        // @todo mettre à jour la ligne
        // 'ibtn_'+$(elem).attr('id')
        $(obj).smartEventDeRegister({owner: obj, event: 'click'});
        console.log('putOneTodo objId : ', $(obj).attr('id'));
        console.log('putOneTodo returned : ', data);
    });
}

const inlinedBtnGreen = '<button class="btn btn-outline-success" ></button>';
const inlinedBtnRed = '<button class="btn btn-outline-danger" ></button>';
//const icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16"> \
//  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path> \
//  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"></path> \
//</svg>';
const iconSave = '<span class="glyphicon glyphicon-floppy-open"></span>';
const iconTrash = '<span class="glyphicon glyphicon-trash"></span>';

function makeBtnsForElem(elem) {
    var btns = {
        ordering: [
            'iBtnG', 'iBtnR' 
        ], iBtnR: null, iBtnG: null};
    var $iBtnG = $(inlinedBtnGreen).attr('id', 'ibtn_'+$(elem).attr('id')).html(iconSave);
    var $iBtnR = $(inlinedBtnRed).attr('id', 'ibtndel_'+$(elem).attr('id')).html(iconTrash);
    
    if($(elem).parent().data('type') === "todo") {
        var delTodoEvent = { ...smartEventDefine };
        delTodoEvent.event = 'click';
        delTodoEvent.handler = function(obj, event) {
            event.elemId = $(elem).attr('id');
            // @todo différencier todos de events
            deleteTodo(obj, event);
        };
        $iBtnR.smartEvent(delTodoEvent);
        btns.iBtnR = $iBtnR;
        
        var saveStatusEvent = { ...smartEventDefine };
        saveStatusEvent.event = 'click';
        saveStatusEvent.handler = function(obj, event) {
            event.elemId = $(elem).attr('id');
            // @todo différencier todos de events
            saveStatusTodo(obj, event);
        };
        $iBtnG.smartEvent(saveStatusEvent);
        btns.iBtnG = $iBtnG;
    }
    
    return btns;
}

function makeStatusesSelectIn(elem) {
    const cats = JSON.parse($('#tableView_categories').text());
    const btns = makeBtnsForElem(elem);
    
    var $divGroup = $('<div class="input-group w-75"></div>').html('');
    var $select = $('<select id="'+ $(elem).attr('id') +'_statuses" class="form-control" ></select>').html("");
    
    for(const [key, cat] of Object.entries(cats)) {
        $select.append(getStatusesSelectOption(cat));
    }
    
    $divGroup.append($select);
    var $oldHidden = $('<input type="hidden" name="old_statuses[]" data-statusid="'+$(elem).data('statusid')+'" />').val($(elem).html());
    $(elem).html('');
    $(elem).append($oldHidden);
//    $(elem).append($select);
    $(elem).append($divGroup);
    $('#'+ $(elem).attr('id') +' option[value="'+ $(elem).data('statusid') +'"]').prop('selected', true);
    
    for (const Btn of btns.ordering)
        $divGroup.append(btns[Btn]);
    
}

const TableEventStatusLoad = 'categories:postLoad';

$(document).ready(function(){
    if($('#tableView').length > 0) {
        $('.rowstatus').each(function(i, e){
            $('#'+$(e).attr('id')).smartEvent(setStatusEvent);
        });

        getAllCategories(function(data) {
            var event = $.Event(TableEventStatusLoad);
            event.cbData = data;
            $('#btn-calculator-action').trigger(event);
        });
    }
});
