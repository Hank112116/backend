(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

var _interopRequireWildcard = function (obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } };

var _import = require("./modules/menu");

var menu = _interopRequireWildcard(_import);

var _import2 = require("./modules/icheck");

var icheck = _interopRequireWildcard(_import2);

var _import3 = require("./libs/Notifier");

var Notifier = _interopRequireWildcard(_import3);

"use strict";

window.Notifier = Notifier;

$(function () {
    menu.init();
    icheck.init();

    Notifier.showTimedMessage($("meta[name=noty-msg]").attr("content"), $("meta[name=noty-type]").attr("content"), 5);
});

},{"./libs/Notifier":2,"./modules/icheck":3,"./modules/menu":4}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.showMessage = showMessage;
exports.showTimedMessage = showTimedMessage;
"use strict";

var humane = require("../vendor/humane/humane");

var humane;

exports.humane = humane;

function showMessage(content, level) {
    if (!content) {
        return;
    }

    humane.log(content, {
        addnCls: level || "info"
    });
}

function showTimedMessage(content, level, sec) {
    if (!content) {
        return;
    }

    humane.log(content, {
        timeout: sec * 1000,
        addnCls: level || "info"
    });
}

},{"../vendor/humane/humane":5}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.init = init;
exports.initRadio = initRadio;
"use strict";

function init() {
    $("input[type=checkbox]").iCheck({
        checkboxClass: "icheckbox_minimal-blue icheckbox"
    });
}

function initRadio() {
    $("input[type=radio]").iCheck({
        radioClass: "iradio_minimal-blue iradio"
    });
}

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.init = init;
"use strict";

function init() {
    var width,
        $sidebar_collapse = $(".sidebar-collapse"),
        $page_collapse = $(".page-wrapper");

    $("#side-menu").metisMenu();

    $(window).bind("load resize", function () {
        width = this.window.innerWidth > 0 ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $sidebar_collapse.addClass("collapse");
        } else {
            $sidebar_collapse.removeClass("collapse");
        }
    });

    $(".btn-sidebar-toggle").click(function () {
        if ($page_collapse.hasClass("page-wrapper--collapse")) {
            $page_collapse.removeClass("page-wrapper--collapse");
            $sidebar_collapse.removeClass("collapse");
        } else {
            $page_collapse.addClass("page-wrapper--collapse");
            $sidebar_collapse.addClass("collapse");
        }
    });
}

},{}],5:[function(require,module,exports){
/**
 * humane.js
 * Humanized Messages for Notifications
 * @author Marc Harter (@wavded)
 * @example
 *   humane.log('hello world');
 * @license MIT
 * See more usage examples at: http://wavded.github.com/humane-js/
 */

'use strict';

;!(function (name, context, definition) {
   if (typeof module !== 'undefined') module.exports = definition(name, context);else if (typeof define === 'function' && typeof define.amd === 'object') define(definition);else context[name] = definition(name, context);
})('humane', undefined, function (name, context) {
   var win = window;
   var doc = document;

   var ENV = {
      on: function on(el, type, cb) {
         'addEventListener' in win ? el.addEventListener(type, cb, false) : el.attachEvent('on' + type, cb);
      },
      off: function off(el, type, cb) {
         'removeEventListener' in win ? el.removeEventListener(type, cb, false) : el.detachEvent('on' + type, cb);
      },
      bind: function bind(fn, ctx) {
         return function () {
            fn.apply(ctx, arguments);
         };
      },
      isArray: Array.isArray || function (obj) {
         return Object.prototype.toString.call(obj) === '[object Array]';
      },
      config: function config(preferred, fallback) {
         return preferred != null ? preferred : fallback;
      },
      transSupport: false,
      useFilter: /msie [678]/i.test(navigator.userAgent), // sniff, sniff
      _checkTransition: function _checkTransition() {
         var el = doc.createElement('div');
         var vendors = { webkit: 'webkit', Moz: '', O: 'o', ms: 'MS' };

         for (var vendor in vendors) if (vendor + 'Transition' in el.style) {
            this.vendorPrefix = vendors[vendor];
            this.transSupport = true;
         }
      }
   };
   ENV._checkTransition();

   var Humane = function Humane(o) {
      o || (o = {});
      this.queue = [];
      this.baseCls = o.baseCls || 'humane';
      this.addnCls = o.addnCls || '';
      this.timeout = 'timeout' in o ? o.timeout : 2500;
      this.waitForMove = o.waitForMove || false;
      this.clickToClose = o.clickToClose || false;
      this.timeoutAfterMove = o.timeoutAfterMove || false;
      this.container = o.container;

      try {
         this._setupEl();
      } // attempt to setup elements
      catch (e) {
         ENV.on(win, 'load', ENV.bind(this._setupEl, this));
      }
   };

   Humane.prototype = {
      constructor: Humane,
      _setupEl: function _setupEl() {
         var el = doc.createElement('div');
         el.style.display = 'none';
         if (!this.container) {
            if (doc.body) this.container = doc.body;else throw 'document.body is null';
         }
         this.container.appendChild(el);
         this.el = el;
         this.removeEvent = ENV.bind(function () {
            var timeoutAfterMove = ENV.config(this.currentMsg.timeoutAfterMove, this.timeoutAfterMove);
            if (!timeoutAfterMove) {
               this.remove();
            } else {
               setTimeout(ENV.bind(this.remove, this), timeoutAfterMove);
            }
         }, this);

         this.transEvent = ENV.bind(this._afterAnimation, this);
         this._run();
      },
      _afterTimeout: function _afterTimeout() {
         if (!ENV.config(this.currentMsg.waitForMove, this.waitForMove)) this.remove();else if (!this.removeEventsSet) {
            ENV.on(doc.body, 'mousemove', this.removeEvent);
            ENV.on(doc.body, 'click', this.removeEvent);
            ENV.on(doc.body, 'keypress', this.removeEvent);
            ENV.on(doc.body, 'touchstart', this.removeEvent);
            this.removeEventsSet = true;
         }
      },
      _run: function _run() {
         if (this._animating || !this.queue.length || !this.el) {
            return;
         }this._animating = true;
         if (this.currentTimer) {
            clearTimeout(this.currentTimer);
            this.currentTimer = null;
         }

         var msg = this.queue.shift();
         var clickToClose = ENV.config(msg.clickToClose, this.clickToClose);

         if (clickToClose) {
            ENV.on(this.el, 'click', this.removeEvent);
            ENV.on(this.el, 'touchstart', this.removeEvent);
         }

         var timeout = ENV.config(msg.timeout, this.timeout);

         if (timeout > 0) this.currentTimer = setTimeout(ENV.bind(this._afterTimeout, this), timeout);

         if (ENV.isArray(msg.html)) msg.html = '<ul><li>' + msg.html.join('<li>') + '</ul>';

         this.el.innerHTML = msg.html;
         this.currentMsg = msg;
         this.el.className = this.baseCls;
         if (ENV.transSupport) {
            this.el.style.display = 'block';
            setTimeout(ENV.bind(this._showMsg, this), 50);
         } else {
            this._showMsg();
         }
      },
      _setOpacity: function _setOpacity(opacity) {
         if (ENV.useFilter) {
            try {
               this.el.filters.item('DXImageTransform.Microsoft.Alpha').Opacity = opacity * 100;
            } catch (err) {}
         } else {
            this.el.style.opacity = String(opacity);
         }
      },
      _showMsg: function _showMsg() {
         var addnCls = ENV.config(this.currentMsg.addnCls, this.addnCls);
         if (ENV.transSupport) {
            this.el.className = this.baseCls + ' ' + addnCls + ' ' + this.baseCls + '-animate';
         } else {
            var opacity = 0;
            this.el.className = this.baseCls + ' ' + addnCls + ' ' + this.baseCls + '-js-animate';
            this._setOpacity(0); // reset value so hover states work
            this.el.style.display = 'block';

            var self = this;
            var interval = setInterval(function () {
               if (opacity < 1) {
                  opacity += 0.1;
                  if (opacity > 1) opacity = 1;
                  self._setOpacity(opacity);
               } else clearInterval(interval);
            }, 30);
         }
      },
      _hideMsg: function _hideMsg() {
         var addnCls = ENV.config(this.currentMsg.addnCls, this.addnCls);
         if (ENV.transSupport) {
            this.el.className = this.baseCls + ' ' + addnCls;
            ENV.on(this.el, ENV.vendorPrefix ? ENV.vendorPrefix + 'TransitionEnd' : 'transitionend', this.transEvent);
         } else {
            var opacity = 1;
            var self = this;
            var interval = setInterval(function () {
               if (opacity > 0) {
                  opacity -= 0.1;
                  if (opacity < 0) opacity = 0;
                  self._setOpacity(opacity);
               } else {
                  self.el.className = self.baseCls + ' ' + addnCls;
                  clearInterval(interval);
                  self._afterAnimation();
               }
            }, 30);
         }
      },
      _afterAnimation: function _afterAnimation() {
         if (ENV.transSupport) ENV.off(this.el, ENV.vendorPrefix ? ENV.vendorPrefix + 'TransitionEnd' : 'transitionend', this.transEvent);

         if (this.currentMsg.cb) this.currentMsg.cb();
         this.el.style.display = 'none';

         this._animating = false;
         this._run();
      },
      remove: function remove(e) {
         var cb = typeof e == 'function' ? e : null;

         ENV.off(doc.body, 'mousemove', this.removeEvent);
         ENV.off(doc.body, 'click', this.removeEvent);
         ENV.off(doc.body, 'keypress', this.removeEvent);
         ENV.off(doc.body, 'touchstart', this.removeEvent);
         ENV.off(this.el, 'click', this.removeEvent);
         ENV.off(this.el, 'touchstart', this.removeEvent);
         this.removeEventsSet = false;

         if (cb && this.currentMsg) this.currentMsg.cb = cb;
         if (this._animating) this._hideMsg();else if (cb) cb();
      },
      log: function log(html, o, cb, defaults) {
         var msg = {};
         if (defaults) for (var opt in defaults) msg[opt] = defaults[opt];

         if (typeof o == 'function') cb = o;else if (o) for (var opt in o) msg[opt] = o[opt];

         msg.html = html;
         if (cb) msg.cb = cb;
         this.queue.push(msg);
         this._run();
         return this;
      },
      spawn: function spawn(defaults) {
         var self = this;
         return function (html, o, cb) {
            self.log.call(self, html, o, cb, defaults);
            return self;
         };
      },
      create: function create(o) {
         return new Humane(o);
      }
   };
   return new Humane();
});
// dom wasn't ready, wait till ready

},{}]},{},[1])