const debug = false;
export const zeroVal = { H:0, M:0 };

export function trottleTimeVal (val) {
    if(debug)
        console.log('trottleTimeVal val : ', val);
    if(val === undefined) 
        return null;
    
    return (val > 9) ? val : (val > 0) ? '0' + val : '00';
}

export function getTimeValRaw(H, M) {
    var res = { H: H, M: M, dateVal: null };
    
    if(debug)
        console.log('getTimeValRaw H, M : ', res);
    
    if(H === undefined || M === undefined) 
        return res;
    
    res.H = trottleTimeVal(res.H);
    res.M = trottleTimeVal(res.M);
    
    res.dateVal = new Date('1970-01-01T' + res.H + ':' + res.M + ':00');
    
    return res;
}

export function getTimeValTs(identifier) {
    if(debug)
        console.log('getTimeValTs identifier : ', identifier);
    if(identifier === undefined) 
        return null;
    
    const H = $('#'+ identifier).timesetter().getHoursValue();
    const M = $('#' + identifier).timesetter().getMinutesValue();
    
    return getTimeValRaw(H, M);
}

export function getTimeVal(identifier) {   
    if(debug)
        console.log('getTimeVal identifier : ', identifier);
    if(identifier === undefined) 
        return null;
    
    const H = $('#'+ identifier +'_hour').val();
    const M = $('#' + identifier + '_minute').val();
    
    return getTimeValRaw(H, M);
}

export function subsTime(from, to) {
    if(debug) 
        console.log('from, to', {f: from, t: to});
    
    var res = { H: from.H, M: from.M };
    
    const resArray = new Date(
            Math.abs(
                ((res.H * 60 * 60) + (res.M * 60)) - ((to.H * 60 * 60) + (to.M * 60))) * 1000
            ).toISOString().substr(11, 8).split(':');
    
    if(debug) 
        console.log('resArray: ', resArray);
    
    res.H = resArray[0];
    res.M = resArray[1];
        
    if(debug) 
        console.log('res', res);
    
    return res;
}

export function addTime(from, to) {
    if(debug) 
        console.log('from, to', {f: from, t: to});
    
    var res = { H: from.H, M: from.M };
    
    const resArray = new Date(
            Math.abs(
                ((res.H * 60 * 60) + (res.M * 60)) + ((to.H * 60 * 60) + (to.M * 60))) * 1000
            ).toISOString().substr(11, 8).split(':');
    
    if(debug) 
        console.log('resArray: ', resArray);
    
    res.H = resArray[0];
    res.M = resArray[1];
        
    if(debug) 
        console.log('res', res);
    
    return checkTimeVals(res);
}

export function parseIntTime(Val) {
    Val.H = parseInt(Val.H);
    Val.M = parseInt(Val.M);
    
    return Val;
}

export function checkTimeVals(Val) {
    if(debug)
        console.log('checkTimeVals Val in: ', Val);
    
    if(Val === undefined) 
        return null;
    if(Val.H >= 24) 
        Val.H -= 24;
    if(Val.H < 0) 
        Val.H += 24;
    if(Val.M >= 60) { 
        Val.M -= 60;
    }
    if(Val.M < 0) {
        Val.M += 60;
    }
    if(debug) 
        console.log('checkTimeVals Val out: ', Val);
    
    return Val;
}

export function setTimeVal(identifier, Val) {
    if(debug) 
        console.log('setTimeVal: ', { identifier: identifier, Val: Val });
    
    $('#' + identifier + '_hour').val(parseInt(Val.H));
    if(Val.H < 1)
        $('#' + identifier + '_hour').val($('#' + identifier + '_hour option[value="0"]').attr('value'));
    $('#' + identifier + '_minute').val(parseInt(Val.M));
    if(Val.M < 1)
        $('#' + identifier + '_minute').val($('#' + identifier + '_minute option[value="0"]').attr('value'));
}
