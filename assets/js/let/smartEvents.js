const debug = true;
/**
 * A smart event base definition
 * @type Object
 */
export const smartEventDefine = {
    /*[Object])*/ owner: null, /*String*/ event: null, /*Function*/ handler: null, /*Boolean*/ once: null
};
const sOrderKey = 'sOrder_';
/**
 * 
 * @type Object
 */
var smartEvents = {}
global.smartEvents = smartEvents;
global.smartEventDefine = smartEventDefine;

/**
 * 
 * @param {Object} definition
 * @param {integer} order
 * @returns {void}
 */
export function recordSmartEvent(/*[Object])*/ definition, /*integer*/ order, /*Boolean*/ isLast) {
    var smartEvent;
    var sOrder = sOrderKey + order;
    
    if(definition.owner) {
        if(definition.owner.attr('id')) {
            if(!global.smartEvents[definition.owner.attr('id')]) {
                global.smartEvents[definition.owner.attr('id')] = [];
            } 
            smartEvent = global.smartEvents[definition.owner.attr('id')];
            if(!smartEvent[definition.event]) {
                smartEvent[definition.event] = {toTrigger: [], defined: false, last: (isLast === true)? sOrder: null};
            }
            if(!smartEvent[definition.event][sOrder]) {
                smartEvent[definition.event][sOrder] = definition;
                smartEvent[definition.event].toTrigger.push(sOrder);
                if(!smartEvent[definition.event].defined) {
                    $(definition.owner).on(definition.event, {obj: definition.owner, eventName: definition.event}, triggerMeOn);
                    smartEvent[definition.event].defined = true;
                }
            } else if(!smartEvent[definition.event][sOrder].once){
                recordSmartEvent(definition, order + 1);
            }
        }
        smartEvent[definition.event].toTrigger.sort();
    }
}

export function setMeFirst(/*[Object])*/ definition) {
    var previous;
    
    if(!global.smartEvents[definition.owner.attr('id')]) { // no deal here there is no events recorded yet we simply record a simple event with order 0
        recordSmartEvent(definition, 0);
        return;
    }
    // more complex to come, replace the definitions with new one with current def as first call
    
    recordSmartEvent(definition, 999);
    global.smartEvents[definition.owner.attr('id')][definition.event].toTrigger.unshift(global.smartEvents[definition.owner.attr('id')][definition.event].toTrigger.pop());

    console.log('next', global.smartEvents[definition.owner.attr('id')]);
//    delete previous;
}

export function setMeLast(/*[Object])*/ definition) {
    const smartEvent = global.smartEvents[definition.owner.attr('id')];
    if(smartEvent === undefined) {
        recordSmartEvent(definition, 0, true);
        return;
    }
        
    const lastIndexValue = smartEvent[definition.event].toTrigger[smartEvent[definition.event].toTrigger.length - 1];
    recordSmartEvent(definition, 999, true);
}

export function triggerMeOn(/*[Object])*/ event) {
    if(event.data.obj.attr('id')) {
        var smartEvent = global.smartEvents[event.data.obj.attr('id')];
        if(smartEvent === undefined)
            return;
        if(debug)
            console.log(smartEvent);
        if(smartEvent[event.data.eventName] && smartEvent[event.data.eventName].toTrigger) {
            for (const sOrder of smartEvent[event.data.eventName].toTrigger) {
                if(sOrder !== smartEvent[event.data.eventName].last) {
                    console.log('tagada', sOrder);
                    runMe(smartEvent[event.data.eventName][sOrder].handler, event, sOrder);
                }
            }
            if(smartEvent[event.data.eventName].last !== null) {
                    console.log('tagada2', smartEvent[event.data.eventName].last);
                console.log(smartEvent[event.data.eventName]);
                runMe(smartEvent[event.data.eventName][smartEvent[event.data.eventName].last].handler, event, smartEvent[event.data.eventName].last);
            }
        }
    }
}

function runMe(/*Function*/ me, /*[Object])*/ event, /*String*/ sOrder) {
    console.log('plop', sOrder);
    console.log('me: ', me);
    me(event.data.obj, event);
}