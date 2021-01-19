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
export function recordSmartEvent(/*[Object])*/ definition, /*integer*/ order) {
    var smartEvent;
    var sOrder = sOrderKey + order;
    
    if(definition.owner) {
        if(definition.owner.attr('id')) {
            if(!global.smartEvents[definition.owner.attr('id')]) {
                global.smartEvents[definition.owner.attr('id')] = [];
            } 
            smartEvent = global.smartEvents[definition.owner.attr('id')];
            if(!smartEvent[definition.event]) {
                smartEvent[definition.event] = {toTrigger: [], defined: false};
            }
            if(!smartEvent[definition.event][sOrder]) {
                smartEvent[definition.event][sOrder] = definition;
                smartEvent[definition.event].toTrigger.push(order);
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

export function triggerMeOn(/*[Object])*/ event) {
    if(event.data.obj.attr('id')) {
        var smartEvent = global.smartEvents[event.data.obj.attr('id')];
        if(debug)
            console.log(smartEvent);
        if(smartEvent[event.data.eventName] && smartEvent[event.data.eventName].toTrigger) {
            for (const order of smartEvent[event.data.eventName].toTrigger) {
                if(debug)
                    console.log('sOrder: ', (sOrderKey + order));
                smartEvent[event.data.eventName][sOrderKey + order].handler(event.data.obj, event);
            }
        }
    }
}