!function(e){"use strict";var t=function(e){return e.replace(/</gm,"&lt;").replace(/>/gm,"&gt;")};e.fn.wpie_editable=function(r,i){if("function"!=typeof i&&(i=function(){}),"string"==typeof r)var a=this,n=r,s="input";else{if("object"!=typeof r)throw'Argument Error - jQuery.wpie_editable("click", function(){ ~~ })';var a=r.trigger||this;"string"==typeof a&&(a=e(a));var n=r.action||"click",s=r.type||"input"}var l=this,o={};return o.start=function(r){a.unbind("clickhold"===n?"mousedown":n),a!==l&&a.hide();var c=("textarea"===s?l.text().replace(/<br( \/)?>/gm,"\n").replace(/&gt;/gm,">").replace(/&lt;/gm,"<"):l.text()).replace(/^\s+/,"").replace(/\s+$/,""),u=e("textarea"===s?"<textarea>":"<input>");u.val(c).css("width","textarea"===s?"100%":l.width()+l.height()).css("font-size","100%").css("margin",0).attr("id","wpie_editable_"+1*new Date).addClass("wpie_editable"),l.addClass("wpie_editable_wrapper"),"textarea"===s&&u.css("height",l.height());var p=function(){l.removeClass("wpie_editable_wrapper");var e=u.val().replace(/^\s+/,"").replace(/\s+$/,""),r=t(e);"textarea"===s&&(r=r.replace(/[\r\n]/gm,"<br />")),l.html(r),i({value:e,target:l,old_value:c}),o.register(),a!==l&&a.show()};u.blur(p),"input"===s&&u.keydown(function(e){13===e.keyCode&&p()}),l.html(u),u.focus()},o.register=function(){if("clickhold"===n){var e=null;a.bind("mousedown",function(t){e=setTimeout(function(){o.start(t)},500)}),a.bind("mouseup mouseout",function(t){clearTimeout(e)})}else a.bind(n,o.start)},o.register(),this}}(jQuery);