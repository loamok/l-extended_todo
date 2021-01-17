/* 
 * display alerts
 */

const debug = false;

export function dissmissAlert(id, level) {
    if(id !== null && id !== undefined) {
        $('#' + id).addClass('let-hidden').removeClass('show');
        $('#' + id + ' div.col').addClass('let-hidden').removeClass('show');
        $('#' + id + ' div.col div.alert-dismissible').addClass('let-hidden').removeClass('show');
    } else {
        if((id === null || id === undefined) && level !== null && level !== undefined) {
            $('#alerts .alerts-' + level + '-zone').addClass('let-hidden').removeClass('show');
            $('#alerts .alerts-' + level + '-zone div.col').addClass('let-hidden').removeClass('show');
            $('#alerts .alerts-' + level + '-zone div.col div.alert-' + level + '.alert-dismissible').addClass('let-hidden').removeClass('show');
        } else {
            const levels = ['danger', 'warning', 'success', 'info'];
            for (const level of levels) {
                $('#alerts .alerts-' + level + '-zone').addClass('let-hidden').removeClass('show');
                $('#alerts .alerts-' + level + '-zone div.col').addClass('let-hidden').removeClass('show');
                $('#alerts .alerts-' + level + '-zone div.col div.alert-' + level + '.alert-dismissible').addClass('let-hidden').removeClass('show');
            }
        }
    }
}

export function showAlert(level, message, id) {
    if(debug) {
        console.log('level : '+ level);
        console.log('message : '+ message);
        console.log('id : '+ id);
    }
    const alertsPrefix = 'alerts-';
    const finalId = alertsPrefix + level + '-' + id;
    const modelId = alertsPrefix + level + '-model';
    if(debug) {
        console.log('finalId : ' + finalId);
        console.log('modelId : ' + modelId);
    }
    
    $('#' + finalId).remove();
    $('#' + modelId).clone().attr('id', finalId).appendTo('#alerts');
    $('#' + finalId + ' span.message').html(message);
    $('#' + finalId).removeClass('let-hidden').addClass('show');
    $('#' + finalId + ' div.col').removeClass('let-hidden').addClass('show');
    $('#' + finalId + ' div.col div.alert-dismissible').removeClass('let-hidden').addClass('show');
    
    return finalId;
}
