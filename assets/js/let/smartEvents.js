
/**
 * A smart event base definition
 * @type Object
 */
export const smartEventDefine = {
    /*[Object])*/ owner: null, /*String*/ event: null, /*Function*/ handler: null, /*Boolean*/ once: null
};

// For internal use only
/**
 * 
 * @type integer
 */
const firstOrder = -999;
/**
 * 
 * @type integer
 */
const lastOrder = 999;
/**
 * 
 * @type String
 */
const sOrderKey = 'sOrder';
/**
 * 
 * @type String
 */
const sOrderKeySep = '_';

/**
 * 
 * @type Object
 */
export var smartEvents = {};

/**
 * Optimized BubbleSort function
 * 
 * @param {Array} toSort
 * @returns {Array}
 */
function bubbleSortMe(/*Array*/ toSort) {
    var swapped = true;
    
    do {
        swapped = false;
        for(var j = 0; j < toSort.length; j++) {
            for(var i = toSort.length - 1; i > 0; i--) {
                if(i === j)
                    break;
            
                const elemOrder = parseInt(toSort[j].split(sOrderKeySep)[1]);
                const nextOrder = parseInt(toSort[i].split(sOrderKeySep)[1]);

                if(elemOrder > nextOrder) {
                    const tmp = toSort[j];
                    toSort[j] = toSort[i];
                    toSort[i] = tmp;
                    swapped = true;
                }
            }
            if(i === j)
                break;
        }
    } while (swapped);
    
    return toSort;
}

/**
 * Register an ordered handler in the smartEvent system
 * only the "definition" parameter is mandatory
 * other parameters are optionnals
 * 
 * @param {Object} definition
 * @param {integer} order*
 * @param {Boolean} isLast*
 * @param {Boolean} isFirst*
 * @returns {void}
 */
export function recordSmartEvent(/*[Object])*/ definition, /*integer*/ order, /*Boolean*/ isLast, /*Boolean*/ isFirst) {
    // let use some vars
    var smartEvent;
    
    if(order === undefined) 
        order = 0;
    
    var sOrder = sOrderKey + sOrderKeySep + order; // order is used as a string since Js confuse 0 with null
    
    if(definition.owner) { // objet to put handlers on is mandatory
        if(definition.owner.attr('id')) { // with an id, ids are kept in collection
            if(!smartEvents[definition.owner.attr('id')]) 
                smartEvents[definition.owner.attr('id')] = [];
            
            smartEvent = smartEvents[definition.owner.attr('id')];
            
            // we need an event with some structural datas
            if(!smartEvent[definition.event]) 
                smartEvent[definition.event] = {toTrigger: [], defined: false, last: null, first: null};
            
            smartEvent[definition.event].last = (isLast === true)? sOrder: smartEvent[definition.event].last;
            smartEvent[definition.event].first = (isFirst === true)? sOrder: smartEvent[definition.event].first;
            
            if(!smartEvent[definition.event][sOrder]) {
                // store the handler definition like smartEvents.myId.click.sOrder_0
                smartEvent[definition.event][sOrder] = definition;
                // ordering the handler calls (array natural sort)
                smartEvent[definition.event].toTrigger.push(sOrder);
                if(!smartEvent[definition.event].defined) {
                    // we only put one callback for all of the handlers since "triggerMeOn" is warant of the call order
                    $(definition.owner).on(definition.event, {obj: definition.owner, eventName: definition.event}, triggerMeOn);
                    // and never put another jquery callback on the html element
                    smartEvent[definition.event].defined = true;
                }
              // avoiding order numbers collisions if number is already registered increase and redo
            } else if(!smartEvent[definition.event][sOrder].once) {
                recordSmartEvent(definition, order + 1, isLast, isFirst);
                return;
            }
            
            // sorting the handlers to call (optimized bubble sort, my personal choice)
            smartEvent[definition.event].toTrigger = bubbleSortMe(smartEvent[definition.event].toTrigger);
            
        }
        
    }
}

export function setMeFirst(/*[Object])*/ definition) {
    // more complex not work (replacing the definitions with new one with current def as first call) 
    // instead give it a '-999' order
    recordSmartEvent(definition, firstOrder, false, true);

}

export function setMeLast(/*[Object])*/ definition) {
    // no deal here there is no events recorded yet we simply record a simple event with order 999
    // since ordinary orders would be before 999 and 999 could only be replaced by another 'setMe"Something"' call
    // with recordSmartEvent auto increase
    recordSmartEvent(definition, lastOrder, true);
}

export function triggerMeOn(/*[Object])*/ event) {
    if(event.data.obj.attr('id')) { // do nothing wihtout a definition
        var smartEvent = smartEvents[event.data.obj.attr('id')];
        if(smartEvent === undefined)
            return; // and ensure it
        
        if(smartEvent[event.data.eventName] && smartEvent[event.data.eventName].toTrigger) {
            // trigger the first handler if it exists
            if(smartEvent[event.data.eventName].first !== null) 
                smartEvent[event.data.eventName][smartEvent[event.data.eventName].first].handler(event.data.obj, event);
            
            // have we got a collection of handlers and an ordered one
            for (const sOrder of smartEvent[event.data.eventName].toTrigger) {
                // trigger all handlers that are not flagged "last"
                if(sOrder !== smartEvent[event.data.eventName].last && sOrder !== smartEvent[event.data.eventName].first) {
                    smartEvent[event.data.eventName][sOrder].handler(event.data.obj, event);
                }
            }
            
            // finally trigger the last if it exists
            if(smartEvent[event.data.eventName].last !== null) 
                smartEvent[event.data.eventName][smartEvent[event.data.eventName].last].handler(event.data.obj, event);
        }
    }
}