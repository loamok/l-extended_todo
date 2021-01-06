
const classWttBaseXPath = '.wtt';
const classDisplayBaseXPath = '.display';
const wttDisplayCbxp = classWttBaseXPath + classDisplayBaseXPath;

const letHiddenClass = 'let-hidden';
const letBgClass = 'let-lightgrey';
const btnOutline = 'btn-outline-';
const btn = 'btn-';
const Dark = 'dark';
const btnOutlineDark = btnOutline + Dark;
const btnDark = btn + Dark;
const Warning = 'warning';
const btnOutlineWarning = btnOutline + Warning;
const btnWarning = btn + Warning;
const Success = 'success';
const btnOutlineSuccess = btnOutline + Success;
const btnSuccess = btn + Success;
const Info = 'info';
const btnOutlineInfo = btnOutline + Info;
const btnInfo = btn + Info;
const Danger = 'danger';
const btnOutlineDanger = btnOutline + Danger;
const btnDanger = btn + Danger;
const disabled = 'disabled';
const btnClass = wttDisplayCbxp + '.actionsBtn';

const btnsClassesToggle = {
    'btn-calculator-action': {
        all: {
            all: {
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    },
    'btn-rewind-action': {
        beetwenModes: [btnOutlineDark, btnOutlineWarning, letBgClass, disabled],
        0: {
            all: {
                always: [btnOutlineDark, disabled],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        },
        1: {
            all: {
                always: [btnOutlineWarning],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    },
    'btn-playpause-action': {
        beetwenModes: [btnOutlineSuccess, btnSuccess, letBgClass],
        0: {
            all: {
                always: [btnOutlineSuccess],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        },
        1: {
            all: {
                always: [btnSuccess],
                elem: [],
                span: [letHiddenClass]
            }
        }
    },
    'btn-stop-action': {
        all: {
            all: {
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    },
    'btn-end-action': {
        beetwenModes: [btnOutlineDark, btnOutlineWarning, letBgClass, disabled],
        0: {
            all: {
                always: [btnOutlineDark, disabled],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        },
        1: {
            all: {
                always: [btnOutlineWarning],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    }
};

var runMode = 0;

var position = 0;

function getSel(elem) {
    const baseSelector = '#' + $(elem).attr('id') + ' span';
    const suffix = ($(baseSelector).length > 1) ? '#' + $(elem).attr('id') + '-' + runMode : '';
    const selector = baseSelector + suffix;
    
    return selector;
}

function getConfig(elem) {
    var id = $(elem).attr('id');
    var modeOrAll = (btnsClassesToggle[id].hasOwnProperty('all'))?btnsClassesToggle[id].all:btnsClassesToggle[id][runMode];
    var res = {
        beetwenModes: (btnsClassesToggle[id].hasOwnProperty('beetwenModes'))?btnsClassesToggle[id].beetwenModes:null,
        mode: modeOrAll
    };
    
    return res;
}

function setOverAnim(elem) {
    const selector = getSel(elem);
    const config = getConfig(elem);
    
    console.debug(config);
    
    if(config.beetwenModes !== null) {
        for(const className of config.beetwenModes) {
            $(elem).removeClass(className);
        }
    }
    if(config.mode.all.hasOwnProperty('always')) {
        for(const className of config.mode.all.always) {
            $(elem).addClass(className);
        }
    }
    
    if(!$(elem).hasClass(disabled)) {
        for(const className of config.mode.all.elem) {
            $(elem).toggleClass(className);
        }
        for(const className of config.mode.all.span) {
            $(selector).toggleClass(className);
        }
//        
//        $(selector).removeClass(hiddenClass)
//        $(elem).removeClass(bgClass);
    }
}

function setOutAnim(elem) {
    const selector = getSel(elem);
    const config = getConfig(elem);
    
    if(config.beetwenModes !== null) {
        for(const className of config.beetwenModes) {
            $(elem).removeClass(className);
        }
    }
    if(config.mode.all.hasOwnProperty('always')) {
        for(const className of config.mode.all.always) {
            $(elem).addClass(className);
        }
    }
    
    for(const className of config.mode.all.elem) {
            $(elem).toggleClass(className);
    }
    for(const className of config.mode.all.span) {
        $(selector).toggleClass(className);
    }
//    $(selector).addClass(hiddenClass);
//    $(elem).addClass(bgClass);
}


$(document).ready(function () {
    if($(btnClass).length) {
        $(btnClass).mouseenter(function (e) {
            setOverAnim(this);
        });
        $(btnClass).mouseleave(function (e) {
            setOutAnim(this);
        });
    }
});