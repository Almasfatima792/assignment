!function(t){var o=function(o,i){var e,s,r=!1,n=!1,c=!1,a={},f={to:"top",offset:0,effectsOffset:0,parent:!1,classes:{sticky:"ekit-sticky",stickyActive:"ekit-sticky-active",stickyEffects:"ekit-sticky-effects",spacer:"ekit-sticky-spacer"}},p=function(t,o,i){var e={},s=t[0].style;i.forEach(function(t){e[t]=void 0!==s[t]?s[t]:""}),t.data("css-backup-"+o,e)},l=function(t,o){return t.data("css-backup-"+o)},m=function(){p(e,"unsticky",["position","width","margin-top","margin-bottom","top","bottom"]);var t={position:"fixed",width:u(e,"width"),marginTop:0,marginBottom:0};t[s.to]=s.offset,t["top"===s.to?"bottom":"top"]="",e.css(t).addClass(s.classes.stickyActive)},d=function(){e.css(l(e,"unsticky")).removeClass(s.classes.stickyActive)},u=function(t,o,i){var e=getComputedStyle(t[0]),s=parseFloat(e[o]),r="height"===o?["top","bottom"]:["left","right"],n=[];return"border-box"!==e.boxSizing&&n.push("border","padding"),i&&n.push("margin"),n.forEach(function(t){r.forEach(function(o){s+=parseFloat(e[t+"-"+o])})}),s},k=function(t){var o=a.$window.scrollTop(),i=u(t,"height"),e=innerHeight,s=t.offset().top-o,r=s-e;return{top:{fromTop:s,fromBottom:r},bottom:{fromTop:s+i,fromBottom:r+i}}},y=function(){a.$spacer=e.clone().addClass(s.classes.spacer).css({visibility:"hidden",transition:"none",animation:"none"}),e.after(a.$spacer),m(),r=!0,e.trigger("sticky:stick")},h=function(){d(),a.$spacer.remove(),r=!1,e.trigger("sticky:unstick")},v=function(){var t=k(e),o="top"===s.to;if(n){(o?t.top.fromTop>s.offset:t.bottom.fromBottom<-s.offset)&&(a.$parent.css(l(a.$parent,"childNotFollowing")),e.css(l(e,"notFollowing")),n=!1)}else{var i=k(a.$parent),r=getComputedStyle(a.$parent[0]),c=parseFloat(r[o?"borderBottomWidth":"borderTopWidth"]),f=o?i.bottom.fromTop-c:i.top.fromBottom+c;(o?f<=t.bottom.fromTop:f>=t.top.fromBottom)&&function(){p(a.$parent,"childNotFollowing",["position"]),a.$parent.css("position","relative"),p(e,"notFollowing",["position","top","bottom"]);var t={position:"absolute"};t[s.to]="",t["top"===s.to?"bottom":"top"]=0,e.css(t),n=!0}()}},g=function(){var t,o=s.offset;if(r){var i=k(a.$spacer);t="top"===s.to?i.top.fromTop-o:-i.bottom.fromBottom-o,s.parent&&v(),t>0&&h()}else{var n=k(e);(t="top"===s.to?n.top.fromTop-o:-n.bottom.fromBottom-o)<=0&&(y(),s.parent&&v())}!function(t){c&&-t<s.effectsOffset?(e.removeClass(s.classes.stickyEffects),c=!1):!c&&-t>=s.effectsOffset&&(e.addClass(s.classes.stickyEffects),c=!0)}(t)},b=function(){g()},w=function(){r&&(d(),m())};this.destroy=function(){r&&h(),a.$window.off("scroll",b).off("resize",w),e.removeClass(s.classes.sticky)},s=jQuery.extend(!0,f,i),e=t(o).addClass(s.classes.sticky),a.$window=t(window),s.parent&&("parent"===s.parent?a.$parent=e.parent():a.$parent=e.closest(s.parent)),a.$window.on({scroll:b,resize:w}),g()};t.fn.ekit_sticky=function(i){var e="string"==typeof i;return this.each(function(){var s=t(this);if(e){var r=s.data("ekit_sticky");if(!r)throw Error("Trying to perform the `"+i+"` method prior to initialization");if(!r[i])throw ReferenceError("Method `"+i+"` not found in sticky instance");r[i].apply(r,Array.prototype.slice.call(arguments,1)),"destroy"===i&&s.removeData("ekit_sticky")}else s.data("ekit_sticky",new o(this,i))}),this},window.EkitSticky=o}(jQuery);