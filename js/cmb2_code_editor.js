//#############################################################################
//adds editor to CBM2 fields
function add_cm_editor(el){

    cm_editors[el.name + '_editor'] = CodeMirror.fromTextArea(el, {
        value: el.value,
        mode: el.dataset.mode,
        lineNumbers: true,
        theme: el.dataset.theme,
        extraKeys: {
            "F11": function(cm){
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm){
                if (cm.getOption("fullScreen"))
                    cm.setOption("fullScreen", false);
            }
        }
    });

    CodeMirror.autoLoadMode(cm_editors[el.name + '_editor'], el.dataset.mode);
    cm_editors[el.name + '_editor'].on('change', updateTextArea);

    var node = document.createElement("div");
    node.className = "cm_editor_label";
    node.innerHTML = el.dataset.mode;

    cm_editors[el.name + '_editor'].addPanel(node, {position: "top", stable: true})
}
//#############################################################################

//#############################################################################
function updateTextArea(e){
    e.save();
}
//#############################################################################
//get all textareas where an aditor will be attached
var cm_elements = document.querySelectorAll("[role='wp_codemirror']");

//stores all editors using the textarea name attribute as the key
var cm_editors = [];

//tells cm where to find the mode js files
CodeMirror.modeURL = cm_base_url + 'mode/%N/%N.js';

//#############################################################################


//attach editor to our textareas
for (i = 0; i < cm_elements.length; i++) {
    add_cm_editor(cm_elements[i]);
}

//#############################################################################

//#############################################################################
var MutationObserver = window.MutationObserver
        || window.WebKitMutationObserver
        || window.MozMutationObserver;

var editorObserver = new MutationObserver(function(ev){
    console.log(ev);
    if (ev[0].addedNodes.length > 0) {

        var editors = ev[0].target.querySelectorAll('.CodeMirror');
        for (i = 0; i < editors.length; i++) {
            editors[i].remove();

        }

        editors = ev[0].target.querySelectorAll('.cm_editor_label');

        for (i = 0; i < editors.length; i++) {
            editors[i].remove();

        }

        editors = ev[0].target.querySelectorAll("[role='wp_codemirror']");

        for (i = 0; i < editors.length; i++) {
            add_cm_editor(editors[i]);
        }
    }
});
//#############################################################################

//#############################################################################
//get all CBM2 repeater groups
var cbm_repeat_group = document.querySelectorAll(".cmb-repeatable-group");

var editorObserverConfig = {childList: true};

for (i = 0; i < cbm_repeat_group.length; i++) {
    if (cbm_repeat_group[i].querySelector("[role='wp_codemirror']") != null) {

        editorObserver.observe(cbm_repeat_group[i], editorObserverConfig);
    }
}

//#############################################################################
