/*!
 * --------------------------------------------------------------------
 *  SIMPLE TEXT SELECTION LIBRARY FOR ONLINE TEXT EDITING (2015-02-21)
 * --------------------------------------------------------------------
 * Source code available at https://github.com/tovic/simple-text-editor-library
 *
 */
var Editor=function(e){var t=this,a=document,n=[],l=0,r=null;t.area=void 0!==e?e:a.getElementsByTagName("textarea")[0],n[l]={value:t.area.value,selectionStart:0,selectionEnd:0},l++,t.selection=function(){var e=t.area.selectionStart,a=t.area.selectionEnd,n=t.area.value.substring(e,a),l=t.area.value.substring(0,e),r=t.area.value.substring(a),o={start:e,end:a,value:n,before:l,after:r};return o},t.select=function(e,n,l){var r=[a.documentElement.scrollTop,a.body.scrollTop,t.area.scrollTop];t.area.focus(),t.area.setSelectionRange(e,n),a.documentElement.scrollTop=r[0],a.body.scrollTop=r[1],t.area.scrollTop=r[2],"function"==typeof l&&l()},t.replace=function(e,a,n){var l=t.selection(),r=l.start,o=(l.end,l.value.replace(e,a));t.area.value=l.before+o+l.after,t.select(r,r+o.length),"function"==typeof n?n():t.updateHistory({value:t.area.value,selectionStart:r,selectionEnd:r+o.length})},t.insert=function(e,a){var n=t.selection(),l=n.start;n.end,t.area.value=n.before+e+n.after,t.select(l+e.length,l+e.length),"function"==typeof a?a():t.updateHistory({value:t.area.value,selectionStart:l+e.length,selectionEnd:l+e.length})},t.wrap=function(e,a,n){var l=t.selection(),r=l.value,o=l.before,c=l.after;t.area.value=o+e+r+a+c,t.select(o.length+e.length,o.length+e.length+r.length),"function"==typeof n?n():t.updateHistory({value:t.area.value,selectionStart:o.length+e.length,selectionEnd:o.length+e.length+r.length})},t.indent=function(e,a){var n=t.selection();n.value.length>0?t.replace(/(^|\n)([^\n])/gm,"$1"+e+"$2",a):(t.area.value=n.before+e+n.value+n.after,t.select(n.start+e.length,n.start+e.length),"function"==typeof a?a():t.updateHistory({value:t.area.value,selectionStart:n.start+e.length,selectionEnd:n.start+e.length}))},t.outdent=function(e,a){var n=t.selection();if(n.value.length>0)t.replace(RegExp("(^|\n)"+e,"gm"),"$1",a);else{var l=n.before.replace(RegExp(e+"$"),"");t.area.value=l+n.value+n.after,t.select(l.length,l.length),"function"==typeof a?a():t.updateHistory({value:t.area.value,selectionStart:l.length,selectionEnd:l.length})}},t.callHistory=function(e){return"number"==typeof e?n[e]:n},t.updateHistory=function(e,a){var r=void 0!==e?e:{value:t.area.value,selectionStart:t.selection().start,selectionEnd:t.selection().end};n["number"==typeof a?a:l]=r,l++},t.undo=function(e){if(n.length>1){l>1?l--:l=1;var a=t.callHistory(l-1);r=l>0?l:l-1,t.area.value=a.value,t.select(a.selectionStart,a.selectionEnd),"function"==typeof e&&e()}},t.redo=function(e){if(null!==r){var a=t.callHistory(r);r<n.length-1?r++:r=n.length-1,l=r<n.length-1?r:r+1,t.area.value=a.value,t.select(a.selectionStart,a.selectionEnd),"function"==typeof e&&e()}}};

'use strict';

(function () {

    //  convert href to toUpperCase
    String.prototype.capitalize = function (lower) {
        return (lower ? this.toLowerCase() : this).replace(/(?:^|\s)\S/g, function (a) {
            return a.toUpperCase();
        });
    };

    // vars
    var y = document.querySelector('#editor-area'),
     o = document.querySelector('.result'),
     w = document.querySelector('.close_window'),
     d = document.querySelector('.drag'),
     myTextArea = document.querySelector('#editor-area'),
     btn = document.querySelector('#editor-control').getElementsByTagName('a'),
     editor = new Editor(myTextArea),
     controls = {

        'youtube': function(){
            editor.wrap('{Youtube id="id_de_youtube', '"}');
        },
        'bloques': function(){
            editor.wrap('{Bloques}', '\n{/Bloques}');
        },
        'bloque': function(){
            editor.wrap("{Bloque col='4'}", '\n{/Bloque}');
        },
        'servicio': function(){
            editor.wrap("{Servicio icon='heart'clase='mb-5'}", '\n{/Servicio}');
        },
        'card': function(){
            editor.wrap("{Card col='4' title='heart' img='image_aqui'}", '\n{/Card}');
        },
        'alert': function(){
            editor.wrap("{Alert type='info'}", '\n{/Alert}');
        },
        'btn': function(){
            editor.wrap("{Btn color='link' text='Link' link='","'}");
        },
        'icono': function(){
            editor.wrap("{Icono type='","mobile'}");
        },

        


        
        

        'bold': function bold() {
            editor.wrap('**', '**');
        },
        'italic': function italic() {
            editor.wrap('_', '_');
        },
        'code': function code() {
            editor.wrap('`', '`');
        },
        'quote': function quote() {
            editor.indent('> ');
        },
        'ul-list': function ulList() {
            var sel = editor.selection();
            var added = "";
            if (sel.value.length > 0) {
                editor.indent('', function () {
                    editor.replace(/^[^\n\r]/gm, function (str) {
                        added += '- ';
                        return str.replace(/^/, '- ');
                    });
                    editor.select(sel.start, sel.end + added.length);
                });
            } else {
                (function () {
                    var placeholder = '- List Item';
                    editor.indent(placeholder, function () {
                        editor.select(sel.start + 2, sel.start + placeholder.length);
                    });
                })();
            }
        },
        'ol-list': function olList() {
            var sel = editor.selection();
            var ol = 0;
            var added = "";
            if (sel.value.length > 0) {
                editor.indent('', function () {
                    editor.replace(/^[^\n\r]/gm, function (str) {
                        ol++;
                        added += ol + '. ';
                        return str.replace(/^/, ol + '. ');
                    });
                    editor.select(sel.start, sel.end + added.length);
                });
            } else {
                (function () {
                    var placeholder = '1. List Item';
                    editor.indent(placeholder, function () {
                        editor.select(sel.start + 3, sel.start + placeholder.length);
                    });
                })();
            }
        },
        'link': function link() {
            var sel = editor.selection();
            var title = null;
            var url = null;
            var placeholder = 'Title of link';
            fakePrompt('Titulo:', 'Titulo del link', false, function (t) {
                title = t;
                fakePrompt('URL:', '{url}', true, function (link) {
                    url = link;
                    editor.insert('\n[' + title + '](' + link + ')\n');
                });
            });
        },
        'image': function image() {
            fakePrompt('Image URL:', '{url}', true, function (r) {
                var altText = r.substring(r.lastIndexOf('}') + 1, r.lastIndexOf('.')).replace(/[\-\_\+]+/g, " ").capitalize();
                altText = !altText.includes('}') ? decodeURIComponent(altText) : 'Image';
                editor.insert('\n![' + altText + '](' + r + ')\n');
                //editor.insert("{Imagen texto='"+altText+"' img='"+r+"'}");
            });
        },
        'h1': function h1() {
            heading('#');
        },
        'h2': function h2() {
            heading('##');
        },
        'h3': function h3() {
            heading('###');
        },
        'hr': function hr() {
            editor.insert('\n\n---\n\n');
        }
    };

    document.querySelector('#shortcodes').addEventListener('change',function(){
        var hash = this.value;
        if (controls[hash]) {
            controls[hash]();
        }
    });

    // find and add function all btn
    for (var i = 0, len = btn.length; i < len; ++i) {
        click(btn[i]);
        btn[i].href = '#';
    }

    // key events
    var pressed = 0;
    editor.area.addEventListener('keydown', function (event) {

        if (pressed < 5) {
            pressed++;
        } else {
            editor.updateHistory();
            pressed = 0;
        }
        // shift + tab
        if (event.shiftKey && event.keyCode == 9) {
            event.preventDefault();
            editor.outdent('  ');
        // tab
        } else if(event.keyCode == 9) {
                event.preventDefault();
                editor.indent('    ');
        }
    });

    // heading wrap
    function heading(key) {
        if (editor.selection().value.length > 0) {
            editor.wrap(key + ' ', "");
        } else {
            (function () {
                var placeholder = key + ' Heading ' + key.length + '\n\n';
                editor.insert(placeholder, function () {
                    var s = editor.selection().start;
                    editor.select(s - placeholder.length + key.length + 1, s - 2);
                });
            })();
        }
    }

    // click events with hash
    function click(elem) {
        var hash = elem.hash.replace('#', "");
        if (controls[hash]) {
            elem.addEventListener('click', function (e) {
                e.preventDefault();
                controls[hash]();
            }, false);
        }
    }

    // fake prompt
    function fakePrompt(label, value, isRequired, callback) {
        var overlay = document.createElement('div');
        overlay.className = 'custom-modal-overlay';
        var modal = document.createElement('div');
        modal.className = 'custom-modal custom-modal-prompt';
        var modal_tmpl = ['<div class="custom-modal-header">', label, '</div>', '<div class="custom-modal-content"></div>', '<div class="custom-modal-action"></div>'].join(' ');
        modal.innerHTML = modal_tmpl;
        var onSuccess = function onSuccess(value) {
            overlay.parentNode.removeChild(overlay);
            modal.parentNode.removeChild(modal);
            if (typeof callback == "function") callback(value);
        };
        var input = document.createElement('input');
        input.type = 'text';
        input.value = value;
        input.addEventListener('keyup', function (e) {
            if (isRequired) {
                if (e.keyCode == 13 && this.value !== "" && this.value !== value) {
                    onSuccess(this.value);
                }
            } else {
                if (e.keyCode == 13) {
                    onSuccess(this.value == value ? "" : this.value);
                }
            }
        }, false);

        var buttonOK = document.createElement('button');
        buttonOK.innerHTML = 'OK';
        buttonOK.addEventListener('click', function () {
            if (isRequired) {
                if (input.value !== "" && input.value !== value) {
                    onSuccess(input.value);
                }
            } else {
                onSuccess(input.value == value ? "" : input.value);
            }
        }, false);
        var buttonCANCEL = document.createElement('button');
        buttonCANCEL.innerHTML = 'Cancel';
        buttonCANCEL.addEventListener('click', function () {
            overlay.parentNode.removeChild(overlay);
            modal.parentNode.removeChild(modal);
        }, false);
        document.body.appendChild(overlay);
        document.body.appendChild(modal);
        modal.children[1].appendChild(input);
        modal.children[2].appendChild(buttonOK);
        modal.children[2].appendChild(buttonCANCEL);
        input.focus();
    }


    // full size 
    var area = document.querySelector('#texto'),
    botonExpand = document.querySelector('#btnExpand');
    if(area){
        botonExpand.addEventListener('click',function(){
            area.classList.toggle('full-width');
            if(area.classList.contains('full-width')){
                document.body.style.overflow = 'hidden';
            }else{
                document.body.style.overflow = 'auto';
            }
        });
    }

    // image preview link
    var imageLink = document.querySelectorAll('.image-link'),
    imagePreview = document.querySelector('.image-preview');
    if(imageLink){
        Array.from(imageLink).forEach(function(item){
            item.addEventListener('mouseover',function(el){
                imagePreview.textContent = '';
                imagePreview.textContent = item.textContent.trim();
            });
        });
    }
})();



