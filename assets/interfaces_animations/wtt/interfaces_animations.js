
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
const toolsIconsContainer = 'tools-icons';

const btnsActions = {
    baseActions: {
        disabledBtns: ['btn-rewind-action', 'btn-stop-action'],
        all: {
            before: {
                click: function(e) {
                    e.preventDefault();
                }
            }
        },
        runModeChanged: function () {
            switch (runMode) {
                case 0:
                    for(const disabled of baseActions.disabledBtns) {
                        $('#'+disabled).addClass(disabled);
                    }
                    break;
                    
                default:
                    for(const disabled of baseActions.disabledBtns) {
                        $('#'+disabled).removeClass(disabled);
                    }
                    
                    break;
            }
            $('#'+toolsIconsContainer + ' .btn').each(function (i,e) {
                setOutAnim(this);
            });
        }
    },
    'btn-calculator-action': {
        actions: {},
        all: {
            all: {
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        },
    },
    'btn-rewind-action': {
        actions: {},
        beetwenModes: [btnOutlineDark, btnOutlineWarning],
        0: {
            all: {
                always: [btnOutlineDark],
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
        },
        2: {
            all: {
                always: [btnOutlineWarning],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    },
    'btn-playpause-action': {
        actions: {
            all: {
                click: function(e) {
                    console.log('ok');
                    switch (runMode) {
                        case 0:
                            runMode = 1;
                            break;
                        case 1:
                            runMode = 2;
                            break;
                        case 2:
                            runMode = 1;
                            break;
                    }
                    btnsActions.runModeChanged();
                }
            }
        },
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
        },
        2: {
            all: {
                always: [btnSuccess],
                elem: [],
                span: [letHiddenClass]
            }
        }
    },
    'btn-stop-action': {
        actions: {
            all: {
                trigger: 'click',
                fn: function(e) {
                    switch (runMode) {
                        default:
                            runMode = 0;
                            break;
                    }
                    btnsActions.runModeChanged();
                }
            }
        },
        all: {
            all: {
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        }
    },
    'btn-end-action': {
        actions: {},
        beetwenModes: [btnOutlineDark, btnOutlineWarning],
        0: {
            all: {
                always: [btnOutlineDark],
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
        },
        2: {
            all: {
                always: [btnOutlineWarning],
                elem: [letBgClass],
                span: [letHiddenClass]
            }
        },
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
    var modeOrAll = (btnsActions[id].hasOwnProperty('all'))?btnsActions[id].all:btnsActions[id][runMode];
    var res = {
        beetwenModes: (btnsActions[id].hasOwnProperty('beetwenModes'))?btnsActions[id].beetwenModes:null,
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
            $(elem).removeClass(className);
        }
        for(const className of config.mode.all.span) {
            $(selector).removeClass(className);
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
            $(elem).addClass(className);
    }
    for(const className of config.mode.all.span) {
        $(selector).addClass(className);
    }
//    $(selector).addClass(hiddenClass);
//    $(elem).addClass(bgClass);
}

function runTrigeredAction(event, triggerName, elem) {
    const id = $(elem).attr('id');
    const fnDef = btnsActions[id].actions;
    var entryPoint = null;
    
    if(fnDef.hasOwnProperty(runMode)) {
        entryPoint = fnDef[runMode];
    } else if(fnDef.hasOwnProperty('all')) {
        entryPoint = fnDef.all;
    }
    
    if(entryPoint !== null && entryPoint.trigger === triggerName) {
        if(entryPoint.hasOwnProperty(triggerName)) {
            if(baseActions.all.hasOwnProperty(before)) {
                if( baseActions.all.before.hasOwnProperty(triggerName)) {
                    baseActions.all.before[triggerName](event);
                }
            }
            entryPoint[triggerName](event);
            if(baseActions.all.hasOwnProperty(after)) {
                if(baseActions.all.after.hasOwnProperty(triggerName)) {
                    baseActions.all.before[triggerName](event);
                }
            }
        }
    }
}

$(document).ready(function () {
    if($(btnClass).length) {
        $(btnClass).mouseenter(function (e) {
            setOverAnim(this);
        });
        $(btnClass).mouseleave(function (e) {
            setOutAnim(this);
        });
        $(btnClass).click(function (e) {
            runTrigeredAction(e, 'click', this);
        });
    }
});