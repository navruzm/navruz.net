/* http://keith-wood.name/countdown.html
   Countdown for jQuery v1.5.2.
   Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */
(function($){function Countdown(){this.regional=[];this.regional['']={labels:['Yıl','Ay','Hafta','Gün','Saat','Dakika','Saniye'],labels1:['Yıl','Ay','Hafta','Gün','Saat','Dakika','Saniye'],compactLabels:['y','m','w','d'],timeSeparator:':',isRTL:false};this._defaults={until:null,since:null,timezone:null,format:'dHMS',layout:'',compact:false,description:'',expiryUrl:'',expiryText:'',alwaysExpire:false,onExpiry:null,onTick:null};$.extend(this._defaults,this.regional[''])}var s='countdown';var Y=0;var O=1;var W=2;var D=3;var H=4;var M=5;var S=6;$.extend(Countdown.prototype,{markerClassName:'hasCountdown',_timer:setInterval(function(){$.countdown._updateTargets()},980),_timerTargets:[],setDefaults:function(a){this._resetExtraLabels(this._defaults,a);extendRemove(this._defaults,a||{})},UTCDate:function(a,b,c,e,f,g,h,i){if(typeof b=='object'&&b.constructor==Date){i=b.getMilliseconds();h=b.getSeconds();g=b.getMinutes();f=b.getHours();e=b.getDate();c=b.getMonth();b=b.getFullYear()}var d=new Date();d.setUTCFullYear(b);d.setUTCDate(1);d.setUTCMonth(c||0);d.setUTCDate(e||1);d.setUTCHours(f||0);d.setUTCMinutes((g||0)-(Math.abs(a)<30?a*60:a));d.setUTCSeconds(h||0);d.setUTCMilliseconds(i||0);return d},_attachCountdown:function(a,b){var c=$(a);if(c.hasClass(this.markerClassName)){return}c.addClass(this.markerClassName);var d={options:$.extend({},b),_periods:[0,0,0,0,0,0,0]};$.data(a,s,d);this._changeCountdown(a)},_addTarget:function(a){if(!this._hasTarget(a)){this._timerTargets.push(a)}},_hasTarget:function(a){return($.inArray(a,this._timerTargets)>-1)},_removeTarget:function(b){this._timerTargets=$.map(this._timerTargets,function(a){return(a==b?null:a)})},_updateTargets:function(){for(var i=0;i<this._timerTargets.length;i++){this._updateCountdown(this._timerTargets[i])}},_updateCountdown:function(a,b){var c=$(a);b=b||$.data(a,s);if(!b){return}c.html(this._generateHTML(b));c[(this._get(b,'isRTL')?'add':'remove')+'Class']('countdown_rtl');var d=this._get(b,'onTick');if(d){d.apply(a,[b._hold!='lap'?b._periods:this._calculatePeriods(b,b._show,new Date())])}var e=b._hold!='pause'&&(b._since?b._now.getTime()<=b._since.getTime():b._now.getTime()>=b._until.getTime());if(e&&!b._expiring){b._expiring=true;if(this._hasTarget(a)||this._get(b,'alwaysExpire')){this._removeTarget(a);var f=this._get(b,'onExpiry');if(f){f.apply(a,[])}var g=this._get(b,'expiryText');if(g){var h=this._get(b,'layout');b.options.layout=g;this._updateCountdown(a,b);b.options.layout=h}var i=this._get(b,'expiryUrl');if(i){window.location=i}}b._expiring=false}else if(b._hold=='pause'){this._removeTarget(a)}$.data(a,s,b)},_changeCountdown:function(a,b,c){b=b||{};if(typeof b=='string'){var d=b;b={};b[d]=c}var e=$.data(a,s);if(e){this._resetExtraLabels(e.options,b);extendRemove(e.options,b);this._adjustSettings(e);$.data(a,s,e);var f=new Date();if((e._since&&e._since<f)||(e._until&&e._until>f)){this._addTarget(a)}this._updateCountdown(a,e)}},_resetExtraLabels:function(a,b){var c=false;for(var n in b){if(n.match(/[Ll]abels/)){c=true;break}}if(c){for(var n in a){if(n.match(/[Ll]abels[0-9]/)){a[n]=null}}}},_destroyCountdown:function(a){var b=$(a);if(!b.hasClass(this.markerClassName)){return}this._removeTarget(a);b.removeClass(this.markerClassName).empty();$.removeData(a,s)},_pauseCountdown:function(a){this._hold(a,'pause')},_lapCountdown:function(a){this._hold(a,'lap')},_resumeCountdown:function(a){this._hold(a,null)},_hold:function(a,b){var c=$.data(a,s);if(c){if(c._hold=='pause'&&!b){c._periods=c._savePeriods;var d=(c._since?'-':'+');c[c._since?'_since':'_until']=this._determineTime(d+c._periods[0]+'y'+d+c._periods[1]+'o'+d+c._periods[2]+'w'+d+c._periods[3]+'d'+d+c._periods[4]+'h'+d+c._periods[5]+'m'+d+c._periods[6]+'s');this._addTarget(a)}c._hold=b;c._savePeriods=(b=='pause'?c._periods:null);$.data(a,s,c);this._updateCountdown(a,c)}},_getTimesCountdown:function(a){var b=$.data(a,s);return(!b?null:(!b._hold?b._periods:this._calculatePeriods(b,b._show,new Date())))},_get:function(a,b){return(a.options[b]!=null?a.options[b]:$.countdown._defaults[b])},_adjustSettings:function(a){var b=new Date();var c=this._get(a,'timezone');c=(c==null?-new Date().getTimezoneOffset():c);a._since=this._get(a,'since');if(a._since){a._since=this.UTCDate(c,this._determineTime(a._since,null))}a._until=this.UTCDate(c,this._determineTime(this._get(a,'until'),b));a._show=this._determineShow(a)},_determineTime:function(k,l){var m=function(a){var b=new Date();b.setTime(b.getTime()+a*1000);return b};var n=function(a){a=a.toLowerCase();var b=new Date();var c=b.getFullYear();var d=b.getMonth();var e=b.getDate();var f=b.getHours();var g=b.getMinutes();var h=b.getSeconds();var i=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;var j=i.exec(a);while(j){switch(j[2]||'s'){case's':h+=parseInt(j[1],10);break;case'm':g+=parseInt(j[1],10);break;case'h':f+=parseInt(j[1],10);break;case'd':e+=parseInt(j[1],10);break;case'w':e+=parseInt(j[1],10)*7;break;case'o':d+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break;case'y':c+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break}j=i.exec(a)}return new Date(c,d,e,f,g,h,0)};var o=(k==null?l:(typeof k=='string'?n(k):(typeof k=='number'?m(k):k)));if(o)o.setMilliseconds(0);return o},_getDaysInMonth:function(a,b){return 32-new Date(a,b,32).getDate()},_generateHTML:function(c){c._periods=periods=(c._hold?c._periods:this._calculatePeriods(c,c._show,new Date()));var d=false;var e=0;for(var f=0;f<c._show.length;f++){d|=(c._show[f]=='?'&&periods[f]>0);c._show[f]=(c._show[f]=='?'&&!d?null:c._show[f]);e+=(c._show[f]?1:0)}var g=this._get(c,'compact');var h=this._get(c,'layout');var i=(g?this._get(c,'compactLabels'):this._get(c,'labels'));var j=this._get(c,'timeSeparator');var k=this._get(c,'description')||'';var l=function(a){var b=$.countdown._get(c,'compactLabels'+periods[a]);return(c._show[a]?periods[a]+(b?b[a]:i[a])+' ':'')};var m=function(a){var b=$.countdown._get(c,'labels'+periods[a]);return(c._show[a]?'<span class="countdown_section"><span class="countdown_amount">'+periods[a]+'</span><br/>'+(b?b[a]:i[a])+'</span>':'')};return(h?this._buildLayout(c,h,g):((g?'<span class="countdown_row countdown_amount'+(c._hold?' countdown_holding':'')+'">'+l(Y)+l(O)+l(W)+l(D)+(c._show[H]?this._twoDigits(periods[H]):'')+(c._show[M]?(c._show[H]?j:'')+this._twoDigits(periods[M]):'')+(c._show[S]?(c._show[H]||c._show[M]?j:'')+this._twoDigits(periods[S]):''):'<span class="countdown_row countdown_show'+e+(c._hold?' countdown_holding':'')+'">'+m(Y)+m(O)+m(W)+m(D)+m(H)+m(M)+m(S))+'</span>'+(k?'<span class="countdown_row countdown_descr">'+k+'</span>':'')))},_buildLayout:function(b,c,d){var e=(d?this._get(b,'compactLabels'):this._get(b,'labels'));var f=function(a){return($.countdown._get(b,(d?'compactLabels':'labels')+b._periods[a])||e)[a]};var g={yl:f(Y),yn:b._periods[Y],ynn:this._twoDigits(b._periods[Y]),ol:f(O),on:b._periods[O],onn:this._twoDigits(b._periods[O]),wl:f(W),wn:b._periods[W],wnn:this._twoDigits(b._periods[W]),dl:f(D),dn:b._periods[D],dnn:this._twoDigits(b._periods[D]),hl:f(H),hn:b._periods[H],hnn:this._twoDigits(b._periods[H]),ml:f(M),mn:b._periods[M],mnn:this._twoDigits(b._periods[M]),sl:f(S),sn:b._periods[S],snn:this._twoDigits(b._periods[S])};var h=c;for(var i=0;i<7;i++){var j='yowdhms'.charAt(i);var k=new RegExp('\\{'+j+'<\\}(.*)\\{'+j+'>\\}','g');h=h.replace(k,(b._show[i]?'$1':''))}$.each(g,function(n,v){var a=new RegExp('\\{'+n+'\\}','g');h=h.replace(a,v)});return h},_twoDigits:function(a){return(a<10?'0':'')+a},_determineShow:function(a){var b=this._get(a,'format');var c=[];c[Y]=(b.match('y')?'?':(b.match('Y')?'!':null));c[O]=(b.match('o')?'?':(b.match('O')?'!':null));c[W]=(b.match('w')?'?':(b.match('W')?'!':null));c[D]=(b.match('d')?'?':(b.match('D')?'!':null));c[H]=(b.match('h')?'?':(b.match('H')?'!':null));c[M]=(b.match('m')?'?':(b.match('M')?'!':null));c[S]=(b.match('s')?'?':(b.match('S')?'!':null));return c},_calculatePeriods:function(f,g,h){f._now=h;f._now.setMilliseconds(0);var i=new Date(f._now.getTime());if(f._since&&h.getTime()<f._since.getTime()){f._now=h=i}else if(f._since){h=f._since}else{i.setTime(f._until.getTime());if(h.getTime()>f._until.getTime()){f._now=h=i}}var j=[0,0,0,0,0,0,0];if(g[Y]||g[O]){var k=$.countdown._getDaysInMonth(h.getFullYear(),h.getMonth());var l=$.countdown._getDaysInMonth(i.getFullYear(),i.getMonth());var m=(i.getDate()==h.getDate()||(i.getDate()>=Math.min(k,l)&&h.getDate()>=Math.min(k,l)));var n=function(a){return(a.getHours()*60+a.getMinutes())*60+a.getSeconds()};var o=Math.max(0,(i.getFullYear()-h.getFullYear())*12+i.getMonth()-h.getMonth()+((i.getDate()<h.getDate()&&!m)||(m&&n(i)<n(h))?-1:0));j[Y]=(g[Y]?Math.floor(o/12):0);j[O]=(g[O]?o-j[Y]*12:0);var p=function(a,b,c){var d=(a.getDate()==c);var e=$.countdown._getDaysInMonth(a.getFullYear()+b*j[Y],a.getMonth()+b*j[O]);if(a.getDate()>e){a.setDate(e)}a.setFullYear(a.getFullYear()+b*j[Y]);a.setMonth(a.getMonth()+b*j[O]);if(d){a.setDate(e)}return a};if(f._since){i=p(i,-1,l)}else{h=p(new Date(h.getTime()),+1,k)}}var q=Math.floor((i.getTime()-h.getTime())/1000);var r=function(a,b){j[a]=(g[a]?Math.floor(q/b):0);q-=j[a]*b};r(W,604800);r(D,86400);r(H,3600);r(M,60);r(S,1);return j}});function extendRemove(a,b){$.extend(a,b);for(var c in b){if(b[c]==null){a[c]=null}}return a}$.fn.countdown=function(a){var b=Array.prototype.slice.call(arguments,1);if(a=='getTimes'){return $.countdown['_'+a+'Countdown'].apply($.countdown,[this[0]].concat(b))}return this.each(function(){if(typeof a=='string'){$.countdown['_'+a+'Countdown'].apply($.countdown,[this].concat(b))}else{$.countdown._attachCountdown(this,a)}})};$.countdown=new Countdown()})(jQuery);