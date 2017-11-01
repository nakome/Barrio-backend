"use strict";var Editor=function(e){var t=this,n=document,o=[],a=0,l=null;t.area=void 0!==e?e:n.getElementsByTagName("textarea")[0],o[a]={value:t.area.value,selectionStart:0,selectionEnd:0},a++,t.selection=function(){var e=t.area.selectionStart,n=t.area.selectionEnd;return{start:e,end:n,value:t.area.value.substring(e,n),before:t.area.value.substring(0,e),after:t.area.value.substring(n)}},t.select=function(e,o,a){var l=[n.documentElement.scrollTop,n.body.scrollTop,t.area.scrollTop];t.area.focus(),t.area.setSelectionRange(e,o),n.documentElement.scrollTop=l[0],n.body.scrollTop=l[1],t.area.scrollTop=l[2],"function"==typeof a&&a()},t.replace=function(e,n,o){var a=t.selection(),l=a.start,r=(a.end,a.value.replace(e,n));t.area.value=a.before+r+a.after,t.select(l,l+r.length),"function"==typeof o?o():t.updateHistory({value:t.area.value,selectionStart:l,selectionEnd:l+r.length})},t.insert=function(e,n){var o=t.selection(),a=o.start;o.end,t.area.value=o.before+e+o.after,t.select(a+e.length,a+e.length),"function"==typeof n?n():t.updateHistory({value:t.area.value,selectionStart:a+e.length,selectionEnd:a+e.length})},t.wrap=function(e,n,o){var a=t.selection(),l=a.value,r=a.before,i=a.after;t.area.value=r+e+l+n+i,t.select(r.length+e.length,r.length+e.length+l.length),"function"==typeof o?o():t.updateHistory({value:t.area.value,selectionStart:r.length+e.length,selectionEnd:r.length+e.length+l.length})},t.indent=function(e,n){var o=t.selection();o.value.length>0?t.replace(/(^|\n)([^\n])/gm,"$1"+e+"$2",n):(t.area.value=o.before+e+o.value+o.after,t.select(o.start+e.length,o.start+e.length),"function"==typeof n?n():t.updateHistory({value:t.area.value,selectionStart:o.start+e.length,selectionEnd:o.start+e.length}))},t.outdent=function(e,n){var o=t.selection();if(o.value.length>0)t.replace(RegExp("(^|\n)"+e,"gm"),"$1",n);else{var a=o.before.replace(RegExp(e+"$"),"");t.area.value=a+o.value+o.after,t.select(a.length,a.length),"function"==typeof n?n():t.updateHistory({value:t.area.value,selectionStart:a.length,selectionEnd:a.length})}},t.callHistory=function(e){return"number"==typeof e?o[e]:o},t.updateHistory=function(e,n){var l=void 0!==e?e:{value:t.area.value,selectionStart:t.selection().start,selectionEnd:t.selection().end};o["number"==typeof n?n:a]=l,a++},t.undo=function(e){if(o.length>1){a>1?a--:a=1;var n=t.callHistory(a-1);l=a>0?a:a-1,t.area.value=n.value,t.select(n.selectionStart,n.selectionEnd),"function"==typeof e&&e()}},t.redo=function(e){if(null!==l){var n=t.callHistory(l);l<o.length-1?l++:l=o.length-1,a=l<o.length-1?l:l+1,t.area.value=n.value,t.select(n.selectionStart,n.selectionEnd),"function"==typeof e&&e()}}};!function(){function e(e){a.selection().value.length>0?a.wrap(e+" ",""):function(){var t=e+" Heading "+e.length+"\n\n";a.insert(t,function(){var n=a.selection().start;a.select(n-t.length+e.length+1,n-2)})}()}function t(e,t,n,o){var a=document.createElement("div");a.className="custom-modal-overlay";var l=document.createElement("div");l.className="custom-modal custom-modal-prompt";var r=['<div class="custom-modal-header">',e,"</div>",'<div class="custom-modal-content"></div>','<div class="custom-modal-action"></div>'].join(" ");l.innerHTML=r;var i=function(e){a.parentNode.removeChild(a),l.parentNode.removeChild(l),"function"==typeof o&&o(e)},c=document.createElement("input");c.type="text",c.value=t,c.addEventListener("keyup",function(e){n?13==e.keyCode&&""!==this.value&&this.value!==t&&i(this.value):13==e.keyCode&&i(this.value==t?"":this.value)},!1);var u=document.createElement("button");u.innerHTML="OK",u.addEventListener("click",function(){n?""!==c.value&&c.value!==t&&i(c.value):i(c.value==t?"":c.value)},!1);var s=document.createElement("button");s.innerHTML="Cancel",s.addEventListener("click",function(){a.parentNode.removeChild(a),l.parentNode.removeChild(l)},!1),document.body.appendChild(a),document.body.appendChild(l),l.children[1].appendChild(c),l.children[2].appendChild(u),l.children[2].appendChild(s),c.focus()}String.prototype.capitalize=function(e){return(e?this.toLowerCase():this).replace(/(?:^|\s)\S/g,function(e){return e.toUpperCase()})};document.querySelector("#editor-area"),document.querySelector(".result"),document.querySelector(".close_window"),document.querySelector(".drag");var n=document.querySelector("#editor-area"),o=document.querySelector("#editor-control").getElementsByTagName("a"),a=new Editor(n),l={youtube:function(){a.wrap('{Youtube id="id_de_youtube','"}')},bloques:function(){a.wrap("{Bloques}","\n{/Bloques}")},bloque:function(){a.wrap("{Bloque col='4'}","\n{/Bloque}")},servicio:function(){a.wrap("{Servicio icon='heart'clase='mb-5'}","\n{/Servicio}")},card:function(){a.wrap("{Card col='4' title='heart' img='image_aqui'}","\n{/Card}")},alert:function(){a.wrap("{Alert type='info'}","\n{/Alert}")},btn:function(){a.wrap("{Btn color='link' text='Link' link='","'}")},icono:function(){a.wrap("{Icono type='","mobile'}")},bold:function(){a.wrap("**","**")},italic:function(){a.wrap("_","_")},code:function(){a.wrap("`","`")},quote:function(){a.indent("> ")},"ul-list":function(){var e=a.selection(),t="";e.value.length>0?a.indent("",function(){a.replace(/^[^\n\r]/gm,function(e){return t+="- ",e.replace(/^/,"- ")}),a.select(e.start,e.end+t.length)}):a.indent("- List Item",function(){a.select(e.start+2,e.start+"- List Item".length)})},"ol-list":function(){var e=a.selection(),t=0,n="";e.value.length>0?a.indent("",function(){a.replace(/^[^\n\r]/gm,function(e){return t++,n+=t+". ",e.replace(/^/,t+". ")}),a.select(e.start,e.end+n.length)}):a.indent("1. List Item",function(){a.select(e.start+3,e.start+"1. List Item".length)})},link:function(){a.selection();var e=null,n=null;t("Titulo:","Titulo del link",!1,function(o){e=o,t("URL:","{url}",!0,function(t){n=t,a.insert("\n["+e+"]("+t+")\n")})})},image:function(){t("Image URL:","{url}",!0,function(e){var t=e.substring(e.lastIndexOf("}")+1,e.lastIndexOf(".")).replace(/[\-\_\+]+/g," ").capitalize();t=t.includes("}")?"Image":decodeURIComponent(t),a.insert("\n!["+t+"]("+e+")\n")})},h1:function(){e("#")},h2:function(){e("##")},h3:function(){e("###")},hr:function(){a.insert("\n\n---\n\n")}};document.querySelector("#shortcodes").addEventListener("change",function(){var e=this.value;l[e]&&l[e]()});for(var r=0,i=o.length;r<i;++r)!function(e){var t=e.hash.replace("#","");l[t]&&e.addEventListener("click",function(e){e.preventDefault(),l[t]()},!1)}(o[r]),o[r].href="#";var c=0;a.area.addEventListener("keydown",function(e){c<5?c++:(a.updateHistory(),c=0),e.shiftKey&&9==e.keyCode?(e.preventDefault(),a.outdent("  ")):9==e.keyCode&&(e.preventDefault(),a.indent("    "))});var u=document.querySelector("#texto"),s=document.querySelector("#btnExpand");u&&s.addEventListener("click",function(){u.classList.toggle("full-width"),u.classList.contains("full-width")?document.body.style.overflow="hidden":document.body.style.overflow="auto"});var d=document.querySelectorAll(".image-link"),v=document.querySelector(".image-preview");d&&Array.from(d).forEach(function(e){e.addEventListener("mouseover",function(t){v.textContent="",v.textContent=e.textContent.trim()})})}();