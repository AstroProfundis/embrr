/*!
 * jQuery Plugins Package for Embr
 * https://embr.in/
 *
 * Copyright 2010-2011, Plugins Authors
 * Packaged by Contributors
 * 
 * Currently including:
 * - Cookie
 * - Color
 * - Tipsy
 * - Lazy Load
 * - Infinite Scroll
 * - Autocomplete
 * - Timeago
 * - caret
 * - ajaxFileupload
 */

//Cookie
jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options=$.extend({},options);options.expires=-1}var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000))}else{date=options.expires}expires='; expires='+date.toUTCString()}var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('')}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break}}}return cookieValue}};

//Color
(function(d){d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(f,e){d.fx.step[e]=function(g){if(!g.colorInit){g.start=c(g.elem,e);g.end=b(g.end);g.colorInit=true}g.elem.style[e]="rgb("+[Math.max(Math.min(parseInt((g.pos*(g.end[0]-g.start[0]))+g.start[0]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[1]-g.start[1]))+g.start[1]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[2]-g.start[2]))+g.start[2]),255),0)].join(",")+")"}});function b(f){var e;if(f&&f.constructor==Array&&f.length==3){return f}if(e=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}if(e=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)){return[parseFloat(e[1])*2.55,parseFloat(e[2])*2.55,parseFloat(e[3])*2.55]}if(e=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}if(e=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}if(e=/rgba\(0, 0, 0, 0\)/.exec(f)){return a.transparent}return a[d.trim(f).toLowerCase()]}function c(g,e){var f;do{f=d.curCSS(g,e);if(f!=""&&f!="transparent"||d.nodeName(g,"body")){break}e="backgroundColor"}while(g=g.parentNode);return b(f)}var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);

//Tipsy
(function(a){a.fn.tipsy=function(b){b=a.extend({},a.fn.tipsy.defaults,b);return this.each(function(){var c=a.fn.tipsy.elementOptions(this,b);a(this).hover(function(){a.data(this,"cancel.tipsy",true);var b=a.data(this,"active.tipsy");if(!b){b=a('<div class="tipsy"><div class="tipsy-inner"/></div>');b.css({position:"absolute",zIndex:1e5});a.data(this,"active.tipsy",b)}if(a(this).attr("title")||typeof a(this).attr("original-title")!="string"){a(this).attr("original-title",a(this).attr("title")||"").removeAttr("title")}var d;if(typeof c.title=="string"){d=a(this).attr(c.title=="title"?"original-title":c.title)}else if(typeof c.title=="function"){d=c.title.call(this)}b.find(".tipsy-inner")[c.html?"html":"text"](d||c.fallback);var e=a.extend({},a(this).offset(),{width:this.offsetWidth,height:this.offsetHeight});b.get(0).className="tipsy";b.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).appendTo(document.body);var f=b[0].offsetWidth,g=b[0].offsetHeight;var h=typeof c.gravity=="function"?c.gravity.call(this):c.gravity;switch(h.charAt(0)){case"n":b.css({top:e.top+e.height,left:e.left+e.width/2-f/2}).addClass("tipsy-north");break;case"s":b.css({top:e.top-g,left:e.left+e.width/2-f/2}).addClass("tipsy-south");break;case"e":b.css({top:e.top+e.height/2-g/2,left:e.left-f}).addClass("tipsy-east");break;case"w":b.css({top:e.top+e.height/2-g/2,left:e.left+e.width}).addClass("tipsy-west");break}if(c.fade){b.css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:.8})}else{b.css({visibility:"visible"})}},function(){a.data(this,"cancel.tipsy",false);var b=this;setTimeout(function(){if(a.data(this,"cancel.tipsy"))return;var d=a.data(b,"active.tipsy");if(c.fade){d.stop().fadeOut(function(){a(this).remove()})}else{d.remove()}},100)})})};a.fn.tipsy.elementOptions=function(b,c){return a.metadata?a.extend({},c,a(b).metadata()):c};a.fn.tipsy.defaults={fade:false,fallback:"",gravity:"n",html:false,title:"title"};a.fn.tipsy.autoNS=function(){return a(this).offset().top>a(document).scrollTop()+a(window).height()/2?"s":"n"};a.fn.tipsy.autoWE=function(){return a(this).offset().left>a(document).scrollLeft()+a(window).width()/2?"e":"w"}})(jQuery);

//Lazy Load
(function(a){a.fn.lazyload=function(b){var c={threshold:0,failurelimit:0,event:"scroll",effect:"show",container:window};if(b){a.extend(c,b)}var d=this;if("scroll"==c.event){a(c.container).bind("scroll",function(b){var e=0;d.each(function(){if(a.abovethetop(this,c)||a.leftofbegin(this,c)){}else if(!a.belowthefold(this,c)&&!a.rightoffold(this,c)){a(this).trigger("appear")}else{if(e++>c.failurelimit){return false}}});var f=a.grep(d,function(a){return!a.loaded});d=a(f)})}this.each(function(){var b=this;if(undefined==a(b).attr("original")){a(b).attr("original",a(b).attr("src"))}if("scroll"!=c.event||undefined==a(b).attr("src")||c.placeholder==a(b).attr("src")||a.abovethetop(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.rightoffold(b,c)){if(c.placeholder){a(b).attr("src",c.placeholder)}else{a(b).removeAttr("src")}b.loaded=false}else{b.loaded=true}a(b).one("appear",function(){if(!this.loaded){a("<img />").bind("load",function(){a(b).hide().attr("src",a(b).attr("original"))[c.effect](c.effectspeed);b.loaded=true}).attr("src",a(b).attr("original"))}});if("scroll"!=c.event){a(b).bind(c.event,function(c){if(!b.loaded){a(b).trigger("appear")}})}});a(c.container).trigger(c.event);return this};a.belowthefold=function(b,c){if(c.container===undefined||c.container===window){var d=a(window).height()+a(window).scrollTop()}else{var d=a(c.container).offset().top+a(c.container).height()}return d<=a(b).offset().top-c.threshold};a.rightoffold=function(b,c){if(c.container===undefined||c.container===window){var d=a(window).width()+a(window).scrollLeft()}else{var d=a(c.container).offset().left+a(c.container).width()}return d<=a(b).offset().left-c.threshold};a.abovethetop=function(b,c){if(c.container===undefined||c.container===window){var d=a(window).scrollTop()}else{var d=a(c.container).offset().top}return d>=a(b).offset().top+c.threshold+a(b).height()};a.leftofbegin=function(b,c){if(c.container===undefined||c.container===window){var d=a(window).scrollLeft()}else{var d=a(c.container).offset().left}return d>=a(b).offset().left+c.threshold+a(b).width()};a.extend(a.expr[":"],{"below-the-fold":"$.belowthefold(a, {threshold : 0, container: window})","above-the-fold":"!$.belowthefold(a, {threshold : 0, container: window})","right-of-fold":"$.rightoffold(a, {threshold : 0, container: window})","left-of-fold":"!$.rightoffold(a, {threshold : 0, container: window})"})})(jQuery);

/*
	--------------------------------
	Infinite Scroll
	--------------------------------
	+ https://github.com/paulirish/infinite-scroll
	+ version 2.0b2.111027
	+ Copyright 2011 Paul Irish & Luke Shumard
	+ Licensed under the MIT license
	
	+ Documentation: http://infinite-scroll.com/
	
*/

(function (window, $, undefined) {
	
	$.infinitescroll = function infscr(options, callback, element) {
		
		this.element = $(element);
		this._create(options, callback);
	
	};
	
	$.infinitescroll.defaults = {
		loading: {
			finished: undefined,
			finishedMsg: "<em>Congratulations, you've reached the edge of the timeline.</em>",
			img: "http://www.infinite-scroll.com/loading.gif",
			msg: null,
			msgText: "<em>Loading more tweets...</em>",
			selector: null,
			speed: 'fast',
			start: undefined
		},
		state: {
			isDuringAjax: false,
			isInvalidPage: false,
			isDestroyed: false,
			isDone: false, // For when it goes all the way through the archive.
			isPaused: false,
			currPage: 1
		},
		callback: undefined,
		debug: false,
		behavior: undefined,
		binder: $(window), // used to cache the selector
		nextSelector: "div.navigation a:first",
		navSelector: "div.navigation",
		contentSelector: null, // rename to pageFragment
		extraScrollPx: 150,
		itemSelector: "div.post",
		animate: false,
		pathParse: undefined,
		dataType: 'html',
		appendCallback: true,
		bufferPx: 40,
		errorCallback: function () { },
		infid: 0, //Instance ID
		pixelsFromNavToBottom: undefined,
		path: undefined
	};


    $.infinitescroll.prototype = {

        /*	
        ----------------------------
        Private methods
        ----------------------------
        */

        // Bind or unbind from scroll
        _binding: function infscr_binding(binding) {

            var instance = this,
				opts = instance.options;
				
			opts.v = '2.0b2.111027';

            // if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_binding_'+opts.behavior] !== undefined) {
				this['_binding_'+opts.behavior].call(this);
				return;
			}

			if (binding !== 'bind' && binding !== 'unbind') {
                this._debug('Binding value  ' + binding + ' not valid')
                return false;
            }

            if (binding == 'unbind') {

                (this.options.binder).unbind('smartscroll.infscr.' + instance.options.infid);

            } else {

                (this.options.binder)[binding]('smartscroll.infscr.' + instance.options.infid, function () {
                    instance.scroll();
                });

            };

            this._debug('Binding', binding);

        },

		// Fundamental aspects of the plugin are initialized
		_create: function infscr_create(options, callback) {

            // If selectors from options aren't valid, return false
            if (!this._validate(options)) { return false; }
            // Define options and shorthand
            var opts = this.options = $.extend(true, {}, $.infinitescroll.defaults, options),
				// get the relative URL - everything past the domain name.
				relurl = /(.*?\/\/).*?(\/.*)/,
				path = $(opts.nextSelector).attr('href');

            // contentSelector is 'page fragment' option for .load() / .ajax() calls
            opts.contentSelector = opts.contentSelector || this.element;

            // loading.selector - if we want to place the load message in a specific selector, defaulted to the contentSelector
            opts.loading.selector = opts.loading.selector || opts.contentSelector;

            // if there's not path, return
            if (!path) { this._debug('Navigation selector not found'); return; }

            // Set the path to be a relative URL from root.
            opts.path = this._determinepath(path);

            // Define loading.msg
            opts.loading.msg = $('<div id="infscr-loading"><img alt="Loading..." src="' + opts.loading.img + '" /><div>' + opts.loading.msgText + '</div></div>');

            // Preload loading.img
            (new Image()).src = opts.loading.img;

            // distance from nav links to bottom
            // computed as: height of the document + top offset of container - top offset of nav link
            opts.pixelsFromNavToBottom = $(document).height() - $(opts.navSelector).offset().top;

			// determine loading.start actions
            opts.loading.start = opts.loading.start || function() {
				
				$(opts.navSelector).hide();
				opts.loading.msg
					.appendTo(opts.loading.selector)
					.show(opts.loading.speed, function () {
	                	beginAjax(opts);
	            });
			};
			
			// determine loading.finished actions
			opts.loading.finished = opts.loading.finished || function() {
				opts.loading.msg.fadeOut('normal');
			};

            // callback loading
            opts.callback = function(instance,data) {
				if (!!opts.behavior && instance['_callback_'+opts.behavior] !== undefined) {
					instance['_callback_'+opts.behavior].call($(opts.contentSelector)[0], data);
				}
				if (callback) {
					callback.call($(opts.contentSelector)[0], data, opts);
				}
			};

            this._setup();

        },

        // Console log wrapper
        _debug: function infscr_debug() {

			if (this.options && this.options.debug) {
                return window.console && console.log.call(console, arguments);
            }

        },

        // find the number to increment in the path.
        _determinepath: function infscr_determinepath(path) {

            var opts = this.options;

			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_determinepath_'+opts.behavior] !== undefined) {
				this['_determinepath_'+opts.behavior].call(this,path);
				return;
			}

            if (!!opts.pathParse) {

                this._debug('pathParse manual');
                return opts.pathParse(path, this.options.state.currPage+1);

            } else if (path.match(/^(.*?)\b2\b(.*?$)/)) {
                path = path.match(/^(.*?)\b2\b(.*?$)/).slice(1);

                // if there is any 2 in the url at all.    
            } else if (path.match(/^(.*?)2(.*?$)/)) {

                // page= is used in django:
                // http://www.infinite-scroll.com/changelog/comment-page-1/#comment-127
                if (path.match(/^(.*?page=)2(\/.*|$)/)) {
                    path = path.match(/^(.*?page=)2(\/.*|$)/).slice(1);
                    return path;
                }

                path = path.match(/^(.*?)2(.*?$)/).slice(1);

            } else {

                // page= is used in drupal too but second page is page=1 not page=2:
                // thx Jerod Fritz, vladikoff
                if (path.match(/^(.*?page=)1(\/.*|$)/)) {
                    path = path.match(/^(.*?page=)1(\/.*|$)/).slice(1);
                    return path;
                } else {
                    this._debug('Sorry, we couldn\'t parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.');
                    // Get rid of isInvalidPage to allow permalink to state
                    opts.state.isInvalidPage = true;  //prevent it from running on this page.
                }
            }
            this._debug('determinePath', path);
            return path;

        },

        // Custom error
        _error: function infscr_error(xhr) {

            var opts = this.options;

			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_error_'+opts.behavior] !== undefined) {
				this['_error_'+opts.behavior].call(this,xhr);
				return;
			}

            if (xhr !== 'destroy' && xhr !== 'end') {
                xhr = 'unknown';
            }

            this._debug('Error', xhr);

            if (xhr == 'end') {
                this._showdonemsg();
            }

            opts.state.isDone = true;
            opts.state.currPage = 1; // if you need to go back to this instance
            opts.state.isPaused = false;
            this._binding('unbind');

        },

        // Load Callback
        _loadcallback: function infscr_loadcallback(box, data) {

            var opts = this.options,
	    		callback = this.options.callback, // GLOBAL OBJECT FOR CALLBACK
	    		result = (opts.state.isDone) ? 'done' : (!opts.appendCallback) ? 'no-append' : 'append',
	    		frag;
	
			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_loadcallback_'+opts.behavior] !== undefined) {
				this['_loadcallback_'+opts.behavior].call(this,box,data);
				return;
			}

            switch (result) {

                case 'done':

                    this._showdonemsg();
                    return false;

                    break;

                case 'no-append':

                    if (opts.dataType == 'html') {
                        data = '<div>' + data + '</div>';
                        data = $(data).find(opts.itemSelector);
                    };

                    break;

                case 'append':

                    var children = box.children();

                    // if it didn't return anything
                    if (children.length == 0) {
                        return this._error('end');
                    }
					
					// added by esmizzle 2012-01-26 - update the path to the link for the next set of elements
					var nexturl = $(data).find(opts.nextSelector).attr('href');
                    this._debug('nexturl: '+ nexturl)
					this.options.path[0] = nexturl;
					this.options.path[1] = '#pathcomplete';

                    // use a documentFragment because it works when content is going into a table or UL
                    frag = document.createDocumentFragment();
                    while (box[0].firstChild) {
                        frag.appendChild(box[0].firstChild);
                    }

                    this._debug('contentSelector', $(opts.contentSelector)[0])
                    $(opts.contentSelector)[0].appendChild(frag);
                    // previously, we would pass in the new DOM element as context for the callback
                    // however we're now using a documentfragment, which doesnt havent parents or children,
                    // so the context is the contentContainer guy, and we pass in an array
                    //   of the elements collected as the first argument.

                    data = children.get();


                    break;

            }

            // loadingEnd function
			opts.loading.finished.call($(opts.contentSelector)[0],opts)
            

            // smooth scroll to ease in the new content
            if (opts.animate) {
                var scrollTo = $(window).scrollTop() + $('#infscr-loading').height() + opts.extraScrollPx + 'px';
                $('html,body').animate({ scrollTop: scrollTo }, 800, function () { opts.state.isDuringAjax = false; });
            }

            if (!opts.animate) opts.state.isDuringAjax = false; // once the call is done, we can allow it again.

            callback(this,data);

        },

        _nearbottom: function infscr_nearbottom() {

            var opts = this.options,
	        	pixelsFromWindowBottomToBottom = 0 + $(document).height() - (opts.binder.scrollTop()) - $(window).height();

            // if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_nearbottom_'+opts.behavior] !== undefined) {
				return this['_nearbottom_'+opts.behavior].call(this);
			}

			this._debug('math:', pixelsFromWindowBottomToBottom, opts.pixelsFromNavToBottom);

            // if distance remaining in the scroll (including buffer) is less than the orignal nav to bottom....
            return (pixelsFromWindowBottomToBottom - opts.bufferPx < opts.pixelsFromNavToBottom);

        },

		// Pause / temporarily disable plugin from firing
        _pausing: function infscr_pausing(pause) {

            var opts = this.options;

            // if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_pausing_'+opts.behavior] !== undefined) {
				this['_pausing_'+opts.behavior].call(this,pause);
				return;
			}

			// If pause is not 'pause' or 'resume', toggle it's value
            if (pause !== 'pause' && pause !== 'resume' && pause !== null) {
                this._debug('Invalid argument. Toggling pause value instead');
            };

            pause = (pause && (pause == 'pause' || pause == 'resume')) ? pause : 'toggle';

            switch (pause) {
                case 'pause':
                    opts.state.isPaused = true;
                    break;

                case 'resume':
                    opts.state.isPaused = false;
                    break;

                case 'toggle':
                    opts.state.isPaused = !opts.state.isPaused;
                    break;
            }

            this._debug('Paused', opts.state.isPaused);
            return false;

        },

		// Behavior is determined
		// If the behavior option is undefined, it will set to default and bind to scroll
		_setup: function infscr_setup() {
			
			var opts = this.options;
			
			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_setup_'+opts.behavior] !== undefined) {
				this['_setup_'+opts.behavior].call(this);
				return;
			}
			
			this._binding('bind');
			
			return false;
			
		},

        // Show done message
        _showdonemsg: function infscr_showdonemsg() {

            var opts = this.options;

			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['_showdonemsg_'+opts.behavior] !== undefined) {
				this['_showdonemsg_'+opts.behavior].call(this);
				return;
			}

            opts.loading.msg
	    		.find('img')
	    		.hide()
	    		.parent()
	    		.find('div').html(opts.loading.finishedMsg).animate({ opacity: 1 }, 2000, function () {
	    		    $(this).parent().fadeOut('normal');
	    		});

            // user provided callback when done    
            opts.errorCallback.call($(opts.contentSelector)[0],'done');

        },

		// grab each selector option and see if any fail
        _validate: function infscr_validate(opts) {

            for (var key in opts) {
                if (key.indexOf && key.indexOf('Selector') > -1 && $(opts[key]).length === 0) {
                    this._debug('Your ' + key + ' found no elements.');
                    return false;
                }
                return true;
            }

        },

        /*	
        ----------------------------
        Public methods
        ----------------------------
        */

		// Bind to scroll
		bind: function infscr_bind() {
			this._binding('bind');
		},

        // Destroy current instance of plugin
        destroy: function infscr_destroy() {

            this.options.state.isDestroyed = true;
            return this._error('destroy');

        },

		// Set pause value to false
		pause: function infscr_pause() {
			this._pausing('pause');
		},
		
		// Set pause value to false
		resume: function infscr_resume() {
			this._pausing('resume');
		},

        // Retrieve next set of content items
        retrieve: function infscr_retrieve(pageNum) {

            var instance = this,
				opts = instance.options,
				path = opts.path,
				box, frag, desturl, method, condition,
	    		pageNum = pageNum || null,
				getPage = (!!pageNum) ? pageNum : opts.state.currPage;
				beginAjax = function infscr_ajax(opts) {
					
					// increment the URL bit. e.g. /page/3/
	                opts.state.currPage++;

	                instance._debug('heading into ajax', path);

	                // if we're dealing with a table we can't use DIVs
	                box = $(opts.contentSelector).is('table') ? $('<tbody/>') : $('<div/>');

					desturl = (path[1] == '#pathcomplete') ? path[0] : path.join(opts.state.currPage); // only throw the currPage in there if we need it
	                instance._debug('desturl: '+desturl);

	                method = (opts.dataType == 'html' || opts.dataType == 'json') ? opts.dataType : 'html+callback';
	                if (opts.appendCallback && opts.dataType == 'html') method += '+callback'

	                switch (method) {

	                    case 'html+callback':
	                        instance._debug('Using HTML via .load() method');
							box.load(desturl + ' ' + opts.itemSelector, null, function infscr_ajax_callback(responseText) {
								instance._loadcallback(box, responseText);
							});

	                        break;

	                    case 'html':
	                    case 'json':

	                        instance._debug('Using ' + (method.toUpperCase()) + ' via $.ajax() method');
	                        $.ajax({
	                            // params
	                            url: desturl,
	                            dataType: opts.dataType,
	                            complete: function infscr_ajax_callback(jqXHR, textStatus) {
	                                condition = (typeof (jqXHR.isResolved) !== 'undefined') ? (jqXHR.isResolved()) : (textStatus === "success" || textStatus === "notmodified");
	                                (condition) ? instance._loadcallback(box, jqXHR.responseText) : instance._error('end');
	                            }
	                        });
	
	                        break;
	                }
				};
				
			// if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['retrieve_'+opts.behavior] !== undefined) {
				this['retrieve_'+opts.behavior].call(this,pageNum);
				return;
			}

            
			// for manual triggers, if destroyed, get out of here
			if (opts.state.isDestroyed) {
                this._debug('Instance is destroyed');
                return false;
            };

            // we dont want to fire the ajax multiple times
            opts.state.isDuringAjax = true;

            opts.loading.start.call($(opts.contentSelector)[0],opts);

        },

        // Check to see next page is needed
        scroll: function infscr_scroll() {

            var opts = this.options,
				state = opts.state;

            // if behavior is defined and this function is extended, call that instead of default
			if (!!opts.behavior && this['scroll_'+opts.behavior] !== undefined) {
				this['scroll_'+opts.behavior].call(this);
				return;
			}

			if (state.isDuringAjax || state.isInvalidPage || state.isDone || state.isDestroyed || state.isPaused) return;

            if (!this._nearbottom()) return;

            this.retrieve();

        },
		
		// Toggle pause value
		toggle: function infscr_toggle() {
			this._pausing();
		},
		
		// Unbind from scroll
		unbind: function infscr_unbind() {
			this._binding('unbind');
		},
		
		// update options
		update: function infscr_options(key) {
			if ($.isPlainObject(key)) {
				this.options = $.extend(true,this.options,key);
			}
		}

    }


    /*	
    ----------------------------
    Infinite Scroll function
    ----------------------------
	
    Borrowed logic from the following...
	
    jQuery UI
    - https://github.com/jquery/jquery-ui/blob/master/ui/jquery.ui.widget.js
	
    jCarousel
    - https://github.com/jsor/jcarousel/blob/master/lib/jquery.jcarousel.js
	
    Masonry
    - https://github.com/desandro/masonry/blob/master/jquery.masonry.js		
	
    */

    $.fn.infinitescroll = function infscr_init(options, callback) {


        var thisCall = typeof options;

        switch (thisCall) {

            // method 
            case 'string':

                var args = Array.prototype.slice.call(arguments, 1);

                this.each(function () {

                    var instance = $.data(this, 'infinitescroll');

                    if (!instance) {
                        // not setup yet
                        // return $.error('Method ' + options + ' cannot be called until Infinite Scroll is setup');
						return false;
                    }
                    if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {
                        // return $.error('No such method ' + options + ' for Infinite Scroll');
						return false;
                    }

                    // no errors!
                    instance[options].apply(instance, args);

                });

                break;

            // creation 
            case 'object':

                this.each(function () {

                    var instance = $.data(this, 'infinitescroll');

                    if (instance) {

                        // update options of current instance
                        instance.update(options);

                    } else {

                        // initialize new instance
                        $.data(this, 'infinitescroll', new $.infinitescroll(options, callback, this));

                    }

                });

                break;

        }

        return this;

    };



    /* 
    * smartscroll: debounced scroll event for jQuery *
    * https://github.com/lukeshumard/smartscroll
    * Based on smartresize by @louis_remi: https://github.com/lrbabe/jquery.smartresize.js *
    * Copyright 2011 Louis-Remi & Luke Shumard * Licensed under the MIT license. *
    */

    var event = $.event,
		scrollTimeout;

    event.special.smartscroll = {
        setup: function () {
            $(this).bind("scroll", event.special.smartscroll.handler);
        },
        teardown: function () {
            $(this).unbind("scroll", event.special.smartscroll.handler);
        },
        handler: function (event, execAsap) {
            // Save the context
            var context = this,
		      args = arguments;

            // set correct event type
            event.type = "smartscroll";

            if (scrollTimeout) { clearTimeout(scrollTimeout); }
            scrollTimeout = setTimeout(function () {
                $.event.handle.apply(context, args);
            }, execAsap === "execAsap" ? 0 : 100);
        }
    };

    $.fn.smartscroll = function (fn) {
        return fn ? this.bind("smartscroll", fn) : this.trigger("smartscroll", ["execAsap"]);
    };


})(window, jQuery);


//Autocomplete
(function(a){a.fn.extend({autocomplete:function(b,c){var d=typeof b=="string";c=a.extend({},a.Autocompleter.defaults,{url:d?b:null,data:d?null:b,delay:d?a.Autocompleter.defaults.delay:10,max:c&&!c.scroll?10:150},c);c.highlight=c.highlight||function(a){return a};c.formatMatch=c.formatMatch||c.formatItem;return this.each(function(){new a.Autocompleter(this,c)})},result:function(a){return this.bind("result",a)},search:function(a){return this.trigger("search",[a])},flushCache:function(){return this.trigger("flushCache")},setOptions:function(a){return this.trigger("setOptions",[a])},unautocomplete:function(){return this.trigger("unautocomplete")}});a.Autocompleter=function(b,c){function x(){e.removeClass(c.loadingClass)}function w(b){var d=[];var e=b.split("\n");for(var f=0;f<e.length;f++){var g=a.trim(e[f]);if(g){g=g.split("|");d[d.length]={data:g,value:g[0],result:c.formatResult&&c.formatResult(g,g[0])||g[0]}}}return d}function v(d,e,f){if(!c.matchCase)d=d.toLowerCase();var g=h.load(d);if(g&&g.length){e(d,g)}else if(typeof c.url=="string"&&c.url.length>0){var i={timestamp:+(new Date)};a.each(c.extraParams,function(a,b){i[a]=typeof b=="function"?b():b});a.ajax({mode:"abort",port:"autocomplete"+b.name,dataType:c.dataType,url:c.url,data:a.extend({q:q(d),limit:c.max},i),success:function(a){var b=c.parse&&c.parse(a)||w(a);h.add(d,b);e(d,b)}})}else{l.emptyList();f(d)}}function u(a,b){if(b&&b.length&&i){x();l.display(b,a);r(a,b[0].value);l.show()}else{t()}}function t(){var d=l.visible();l.hide();clearTimeout(f);x();if(c.mustMatch){e.search(function(a){if(!a){if(c.multiple){var b=p(e.val()).slice(0,-1);e.val(b.join(c.multipleSeparator)+(b.length?c.multipleSeparator:""))}else e.val("")}})}if(d)a.Autocompleter.Selection(b,b.value.length,b.value.length)}function s(){clearTimeout(f);f=setTimeout(t,200)}function r(f,h){if(c.autoFill&&q(e.val()).toLowerCase()==f.toLowerCase()&&j!=d.BACKSPACE){e.val(e.val()+h.substring(q(g).length));a.Autocompleter.Selection(b,g.length,g.length+h.length)}}function q(a){if(!c.multiple)return a;var b=p(a);return b[b.length-1]}function p(b){if(!b){return[""]}var d=b.split(c.multipleSeparator);var e=[];a.each(d,function(b,c){if(a.trim(c))e[b]=a.trim(c)});return e}function o(a,b){if(j==d.DEL){l.hide();return}var f=e.val();if(!b&&f==g)return;g=f;f=q(f);if(f.length>=c.minChars){e.addClass(c.loadingClass);if(!c.matchCase)f=f.toLowerCase();v(f,u,t)}else{x();l.hide()}}function n(){var a=l.selected();if(!a)return false;var b=a.result;g=b;if(c.multiple){var d=p(e.val());if(d.length>1){b=d.slice(0,d.length-1).join(c.multipleSeparator)+c.multipleSeparator+b}b+=c.multipleSeparator}e.val(b);t();e.trigger("result",[a.data,a.value]);return true}var d={UP:38,DOWN:40,DEL:46,TAB:9,RETURN:13,ESC:27,COMMA:188,PAGEUP:33,PAGEDOWN:34,BACKSPACE:8};var e=a(b).attr("autocomplete","off").addClass(c.inputClass);var f;var g="";var h=a.Autocompleter.Cache(c);var i=0;var j;var k={mouseDownOnSelect:false};var l=a.Autocompleter.Select(c,b,n,k);var m;a.browser.opera&&a(b.form).bind("submit.autocomplete",function(){if(m){m=false;return false}});e.bind((a.browser.opera?"keypress":"keydown")+".autocomplete",function(b){j=b.keyCode;switch(b.keyCode){case d.UP:b.preventDefault();if(l.visible()){l.prev()}else{o(0,true)}break;case d.DOWN:b.preventDefault();if(l.visible()){l.next()}else{o(0,true)}break;case d.PAGEUP:b.preventDefault();if(l.visible()){l.pageUp()}else{o(0,true)}break;case d.PAGEDOWN:b.preventDefault();if(l.visible()){l.pageDown()}else{o(0,true)}break;case c.multiple&&a.trim(c.multipleSeparator)==","&&d.COMMA:case d.TAB:case d.RETURN:if(n()){b.preventDefault();m=true;return false}break;case d.ESC:l.hide();break;default:clearTimeout(f);f=setTimeout(o,c.delay);break}}).focus(function(){i++}).blur(function(){i=0;if(!k.mouseDownOnSelect){s()}}).click(function(){if(i++>1&&!l.visible()){o(0,true)}}).bind("search",function(){function c(a,c){var d;if(c&&c.length){for(var f=0;f<c.length;f++){if(c[f].result.toLowerCase()==a.toLowerCase()){d=c[f];break}}}if(typeof b=="function")b(d);else e.trigger("result",d&&[d.data,d.value])}var b=arguments.length>1?arguments[1]:null;a.each(p(e.val()),function(a,b){v(b,c,c)})}).bind("flushCache",function(){h.flush()}).bind("setOptions",function(){a.extend(c,arguments[1]);if("data"in arguments[1])h.populate()}).bind("unautocomplete",function(){l.unbind();e.unbind();a(b.form).unbind(".autocomplete")});};a.Autocompleter.defaults={inputClass:"ac_input",resultsClass:"ac_results",loadingClass:"ac_loading",minChars:1,delay:400,matchCase:false,matchSubset:true,matchContains:false,cacheLength:10,max:100,mustMatch:false,extraParams:{},selectFirst:true,formatItem:function(a){return a[0]},formatMatch:null,autoFill:false,width:0,multiple:false,multipleSeparator:", ",highlight:function(a,b){return a.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+b.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi,"\\$1")+")(?![^<>]*>)(?![^&;]+;)","gi"),"<strong>$1</strong>")},scroll:true,scrollHeight:180};a.Autocompleter.Cache=function(b){function h(){c={};d=0}function g(){if(!b.data)return false;var c={},d=0;if(!b.url)b.cacheLength=1;c[""]=[];for(var e=0,g=b.data.length;e<g;e++){var h=b.data[e];h=typeof h=="string"?[h]:h;var i=b.formatMatch(h,e+1,b.data.length);if(i===false)continue;var j=i.charAt(0).toLowerCase();if(!c[j])c[j]=[];var k={value:i,data:h,result:b.formatResult&&b.formatResult(h)||i};c[j].push(k);if(d++<b.max){c[""].push(k)}}a.each(c,function(a,c){b.cacheLength++;f(a,c)})}function f(a,e){if(d>b.cacheLength){h()}if(!c[a]){d++}c[a]=e}function e(a,c){if(!b.matchCase)a=a.toLowerCase();var d=a.indexOf(c);if(b.matchContains=="word"){d=a.toLowerCase().search("\\b"+c.toLowerCase())}if(d==-1)return false;return d==0||b.matchContains}var c={};var d=0;setTimeout(g,25);return{flush:h,add:f,populate:g,load:function(f){if(!b.cacheLength||!d)return null;if(!b.url&&b.matchContains){var g=[];for(var h in c){if(h.length>0){var i=c[h];a.each(i,function(a,b){if(e(b.value,f)){g.push(b)}})}}return g}else if(c[f]){return c[f]}else if(b.matchSubset){for(var j=f.length-1;j>=b.minChars;j--){var i=c[f.substr(0,j)];if(i){var g=[];a.each(i,function(a,b){if(e(b.value,f)){g[g.length]=b}});return g}}}return null}}};a.Autocompleter.Select=function(b,c,d,e){function s(){m.empty();var c=r(i.length);for(var d=0;d<c;d++){if(!i[d])continue;var e=b.formatItem(i[d].data,d+1,c,i[d].value,j);if(e===false)continue;var k=a("<li/>").html(b.highlight(e,j)).addClass(d%2==0?"ac_even":"ac_odd").appendTo(m)[0];a.data(k,"ac_data",i[d])}g=m.find("li");if(b.selectFirst){g.slice(0,1).addClass(f.ACTIVE);h=0}if(a.fn.bgiframe)m.bgiframe()}function r(a){return b.max&&b.max<a?b.max:a}function q(a){h+=a;if(h<0){h=g.size()-1}else if(h>=g.size()){h=0}}function p(a){g.slice(h,h+1).removeClass(f.ACTIVE);q(a);var c=g.slice(h,h+1).addClass(f.ACTIVE);if(b.scroll){var d=0;g.slice(0,h).each(function(){d+=this.offsetHeight});if(d+c[0].offsetHeight-m.scrollTop()>m[0].clientHeight){m.scrollTop(d+c[0].offsetHeight-m.innerHeight())}else if(d<m.scrollTop()){m.scrollTop(d)}}}function o(a){var b=a.target;while(b&&b.tagName!="LI")b=b.parentNode;if(!b)return[];return b}function n(){if(!k)return;l=a("<div/>").hide().addClass(b.resultsClass).css("position","absolute").appendTo(document.body);m=a("<ul/>").appendTo(l).mouseover(function(b){if(o(b).nodeName&&o(b).nodeName.toUpperCase()=="LI"){h=a("li",m).removeClass(f.ACTIVE).index(o(b));a(o(b)).addClass(f.ACTIVE)}}).click(function(b){a(o(b)).addClass(f.ACTIVE);d();c.focus();return false}).mousedown(function(){e.mouseDownOnSelect=true}).mouseup(function(){e.mouseDownOnSelect=false});if(b.width>0)l.css("width",b.width);k=false}var f={ACTIVE:"ac_over"};var g,h=-1,i,j="",k=true,l,m;return{display:function(a,b){n();i=a;j=b;s()},next:function(){p(1)},prev:function(){p(-1)},pageUp:function(){if(h!=0&&h-8<0){p(-h)}else{p(-8)}},pageDown:function(){if(h!=g.size()-1&&h+8>g.size()){p(g.size()-1-h)}else{p(8)}},hide:function(){l&&l.hide();g&&g.removeClass(f.ACTIVE);h=-1},visible:function(){return l&&l.is(":visible")},current:function(){return this.visible()&&(g.filter("."+f.ACTIVE)[0]||b.selectFirst&&g[0])},show:function(){var d=a(c).offset();l.css({width:typeof b.width=="string"||b.width>0?b.width:a(c).width(),top:d.top+c.offsetHeight,left:d.left}).show();if(b.scroll){m.scrollTop(0);m.css({maxHeight:b.scrollHeight,overflow:"auto"});if(a.browser.msie&&typeof document.body.style.maxHeight==="undefined"){var e=0;g.each(function(){e+=this.offsetHeight});var f=e>b.scrollHeight;m.css("height",f?b.scrollHeight:e);if(!f){g.width(m.width()-parseInt(g.css("padding-left"))-parseInt(g.css("padding-right")))}}}},selected:function(){var b=g&&g.filter("."+f.ACTIVE).removeClass(f.ACTIVE);return b&&b.length&&a.data(b[0],"ac_data")},emptyList:function(){m&&m.empty()},unbind:function(){l&&l.remove()}}};a.Autocompleter.Selection=function(a,b,c){if(a.createTextRange){var d=a.createTextRange();d.collapse(true);d.moveStart("character",b);d.moveEnd("character",c);d.select()}else if(a.setSelectionRange){a.setSelectionRange(b,c)}else{if(a.selectionStart){a.selectionStart=b;a.selectionEnd=c}}a.focus()}})(jQuery);

//Timeago
(function(a){function f(a){return(new Date).getTime()-a.getTime()}function e(a){return b.inWords(f(a))}function d(b){b=a(b);var c=a.trim(b.attr("id"));if(!b.data("timeago")){b.data("timeago",{datetime:new Date(c*1e3)})}return b.data("timeago")}function c(){var b=d(this);if(!isNaN(b.datetime)){a(this).text(e(b.datetime))}return this}a.timeago=function(a){if(a instanceof Date){return e(a)}};var b=a.timeago;a.extend(a.timeago,{settings:{refreshMillis:6e4,allowFuture:false,strings:{prefixAgo:null,prefixFromNow:null,suffixAgo:"ago",suffixFromNow:"from now",seconds:"%d seconds",minute:"about a minute",minutes:"%d minutes",hour:"about an hour",hours:"about %d hours",day:"a day",days:"%d days",month:"about a month",months:"%d months",year:"about a year",years:"%d years",numbers:[]}},inWords:function(b){function k(d,e){var f=a.isFunction(d)?d(e,b):d;var g=c.numbers&&c.numbers[e]||e;return f.replace(/%d/i,g)}var c=this.settings.strings;var d=c.prefixAgo;var e=c.suffixAgo;if(this.settings.allowFuture){if(b<0){d=c.prefixFromNow;e=c.suffixFromNow}b=Math.abs(b)}var f=b/1e3;var g=f/60;var h=g/60;var i=h/24;var j=i/365;var l=f<45&&k(c.seconds,Math.round(f))||f<90&&k(c.minute,1)||g<45&&k(c.minutes,Math.round(g))||g<90&&k(c.hour,1)||h<24&&k(c.hours,Math.round(h))||h<48&&k(c.day,1)||i<30&&k(c.days,Math.floor(i))||i<60&&k(c.month,1)||i<365&&k(c.months,Math.floor(i/30))||j<2&&k(c.year,1)||k(c.years,Math.floor(j));return a.trim([d,l,e].join(" "))}});a.fn.timeago=function(){var a=this;a.each(c);var d=b.settings;if(d.refreshMillis>0){setInterval(function(){a.each(c)},d.refreshMillis)}return a}})(jQuery);

//caret
(function(a){a.extend(a.fn,{caret:function(a,b){var c=this[0];if(c){if(typeof a=="undefined"){if(c.selectionStart){a=c.selectionStart;b=c.selectionEnd}else if(document.selection){var d=this.val();var e=document.selection.createRange().duplicate();e.moveEnd("character",d.length);a=e.text==""?d.length:d.lastIndexOf(e.text);e=document.selection.createRange().duplicate();e.moveStart("character",-d.length);b=e.text.length}}else{var d=this.val();if(typeof a!="number")a=-1;if(typeof b!="number")b=-1;if(a<0)a=0;if(b>d.length)b=d.length;if(b<a)b=a;if(a>b)a=b;c.focus();if(c.selectionStart){c.selectionStart=a;c.selectionEnd=b}else if(document.selection){var e=c.createTextRange();e.collapse(true);e.moveStart("character",a);e.moveEnd("character",b-a);e.select()}}return{start:a,end:b}}}})})(jQuery);

//ajaxFileupload
jQuery.extend({createUploadIframe:function(a,b){var c="jUploadFrame"+a;var d='<iframe id="'+c+'" name="'+c+'" style="position:absolute; top:-9999px; left:-9999px"';if(window.ActiveXObject){if(typeof b=="boolean"){d+=' src="'+"javascript:false"+'"'}else if(typeof b=="string"){d+=' src="'+b+'"'}}d+=" />";jQuery(d).appendTo(document.body);return jQuery("#"+c).get(0)},createUploadForm:function(a,b,c){var d="jUploadForm"+a;var e="jUploadFile"+a;var f=jQuery('<form  action="" method="POST" name="'+d+'" id="'+d+'" enctype="multipart/form-data"></form>');if(c){for(var g in c){jQuery('<input type="hidden" name="'+g+'" value="'+c[g]+'" />').appendTo(f)}}var h=jQuery("#"+b);var i=jQuery(h).clone();jQuery(h).attr("id",e);jQuery(h).before(i);jQuery(h).appendTo(f);jQuery(f).css("position","absolute");jQuery(f).css("top","-1200px");jQuery(f).css("left","-1200px");jQuery(f).appendTo("body");return f},ajaxFileUpload:function(a){a=jQuery.extend({},jQuery.ajaxSettings,a);var b=(new Date).getTime();var c=jQuery.createUploadForm(b,a.fileElementId,typeof a.data=="undefined"?false:a.data);var d=jQuery.createUploadIframe(b,a.secureuri);var e="jUploadFrame"+b;var f="jUploadForm"+b;if(a.global&&!(jQuery.active++)){jQuery.event.trigger("ajaxStart")}var g=false;var h={};if(a.global)jQuery.event.trigger("ajaxSend",[h,a]);var i=function(b){var d=document.getElementById(e);try{if(d.contentWindow){h.responseText=d.contentWindow.document.body?d.contentWindow.document.body.innerHTML:null;h.responseXML=d.contentWindow.document.XMLDocument?d.contentWindow.document.XMLDocument:d.contentWindow.document}else if(d.contentDocument){h.responseText=d.contentDocument.document.body?d.contentDocument.document.body.innerHTML:null;h.responseXML=d.contentDocument.document.XMLDocument?d.contentDocument.document.XMLDocument:d.contentDocument.document}}catch(f){jQuery.handleError(a,h,null,f)}if(h||b=="timeout"){g=true;var i;try{i=b!="timeout"?"success":"error";if(i!="error"){var j=jQuery.uploadHttpData(h,a.dataType);if(a.success)a.success(j,i);if(a.global)jQuery.event.trigger("ajaxSuccess",[h,a])}else jQuery.handleError(a,h,i)}catch(f){i="error";jQuery.handleError(a,h,i,f)}if(a.global)jQuery.event.trigger("ajaxComplete",[h,a]);if(a.global&&!--jQuery.active)jQuery.event.trigger("ajaxStop");if(a.complete)a.complete(h,i);jQuery(d).unbind();setTimeout(function(){try{jQuery(d).remove();jQuery(c).remove()}catch(b){jQuery.handleError(a,h,null,b)}},100);h=null}};if(a.timeout>0){setTimeout(function(){if(!g)i("timeout")},a.timeout)}try{var c=jQuery("#"+f);jQuery(c).attr("action",a.url);jQuery(c).attr("method","POST");jQuery(c).attr("target",e);if(c.encoding){jQuery(c).attr("encoding","multipart/form-data")}else{jQuery(c).attr("enctype","multipart/form-data")}jQuery(c).submit()}catch(j){jQuery.handleError(a,h,null,j)}jQuery("#"+e).load(i);return{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;data=type=="xml"||data?r.responseXML:r.responseText;if(type=="script")jQuery.globalEval(data);if(type=="json")eval("data = "+data);if(type=="html")jQuery("<div>").html(data).evalScripts();return data}});
