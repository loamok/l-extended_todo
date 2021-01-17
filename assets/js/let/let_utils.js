const debug = false;

export function trottleTimeVal (val) {
    return (val > 9) ? val : (val > 0) ? '0' + val : '00';
}

export function getTimeValRaw(H, M) {
    var res = { H: 0, M: 0, dateVal: null };
    
    res.H = H;
    res.H = trottleTimeVal(res.H);
    
    res.M = M;
    res.M = trottleTimeVal(res.M);
    
    res.dateVal = new Date('1970-01-01T' + res.H + ':' + res.M + ':00');
    
    return res;
}

export function getTimeValTs(identifier) {
    const H = $('#'+ identifier).timesetter().getHoursValue();
    const M = $('#' + identifier).timesetter().getMinutesValue();
    
    return getTimeValRaw(H, M);
}

export function getTimeVal(identifier) {   
    const H = $('#'+ identifier +'_hour').val();
    const M = $('#' + identifier + '_minute').val();
    
    return getTimeValRaw(H, M);
}

export function subsTime(from, to) {
    var res = { H: 0, M: 0 };
    
    if(from.H < to.H)
        from.H += 24;
    
    res.H = from.H - to.H;
    
    if(from.M < to.M)
        from.M += 60;
    
    res.M = from.M - to.M;

    if(res.M < 0) { 
        res.M += 60;
        res.H -= 1;
    }
    
    if(res.H < 0) 
        res.H += 24;
    
    return res;
}

export function addTime(from, to) {
    var res = { H: 0, M: 0 };
    
    res.H = from.H + to.H;
    res.M = from.M + to.M;
    
    if(res.M >= 60){
        res.M -= 60;
        res.H += 1;
    }
    
    
    
    return res;
}

export function parseIntTime(Val) {
    Val.H = parseInt(Val.H);
    Val.M = parseInt(Val.M);
    
    return Val;
}

export function checkTimeVals(Val) {
    if(Val.H >= 24) 
        Val.H -= 24;
    if(Val.H < 0) 
        Val.H += 24;
    if(Val.M >= 60) 
        Val.M -= 60;
    if(Val.M < 0) 
        Val.M += 60;
    
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
