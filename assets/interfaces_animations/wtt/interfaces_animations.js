
const classWttBaseXPath = '.wtt';
const classDisplayBaseXPath = '.display';
const wttDisplayCbxp = classWttBaseXPath + classDisplayBaseXPath;

const hiddenClass = 'let-hidden';
const bgClass = 'let-lightgrey';
const disabledClass = 'disabled';
const btnClass = wttDisplayCbxp + '.actionsBtn';

var runMode = 0;

var position = 0;

function getSel(elem) {
    const baseSelector = '#' + $(elem).attr('id') + ' span';
    const suffix = ($(baseSelector).length > 1) ? '#' + $(elem).attr('id') + '-' + runMode : '';
    const selector = baseSelector + suffix;
    
    return selector;
}

function setHoverAnim(elem) {
    const selector = getSel(elem);
    
    if(!$(elem).hasClass(disabledClass)) {
        $(selector).removeClass(hiddenClass)
        $(elem).removeClass(bgClass);
    }
}

function setOutAnim(elem) {
    const selector = getSel(elem);
    
    $(selector).addClass(hiddenClass);
    $(elem).addClass(bgClass);
}


$(document).ready(function () {
    if($(btnClass).length) {
        $(btnClass).mouseenter(function (e) {
            setHoverAnim(this);
        });
        $(btnClass).mouseleave(function (e) {
            setOutAnim(this);
        });
    }
});