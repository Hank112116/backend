(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var _libsRestrictedObjectSelector = require("./libs/RestrictedObjectSelector");

var _libsRestrictedObjectSelector2 = _interopRequireDefault(_libsRestrictedObjectSelector);

new _libsRestrictedObjectSelector2["default"]();

},{"./libs/RestrictedObjectSelector":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RestrictedObjectSelector = (function () {
    function RestrictedObjectSelector() {
        var _this = this;

        _classCallCheck(this, RestrictedObjectSelector);

        this.$root = $("body");
        this.$root.find(".js-search-form").each(function (kee, form_block) {
            return _this._setSearchForm(form_block);
        });
        this._revoke();
    }

    _createClass(RestrictedObjectSelector, [{
        key: "_setSearchForm",
        value: function _setSearchForm(block) {
            var $block = $(block),
                $btn = $block.find("button");

            $block.submit(function (event) {
                event.preventDefault();
                $btn.click();
            });

            $btn.click(function (event) {
                event.preventDefault();

                $.ajax({
                    type: "POST",
                    url: block.action,
                    data: { id: $block.find(".search_id")[0].value },
                    dataType: "JSON",
                    statusCode: {
                        200: function _(feeback) {
                            if (feeback.status == "fail") {
                                Notifier.showTimedMessage(feeback.msg, "warning", 2);
                                return;
                            }
                            Notifier.showTimedMessage("Add successful", "information", 2);

                            window.location.reload();
                        },
                        412: function _() {
                            location.href = "/";
                        }
                    }
                });

                return false;
            });
        }
    }, {
        key: "_revoke",
        value: function _revoke() {
            var $btn = $(".js-revoke");
            $btn.click(this, function () {
                var id = $(this).attr("rel");
                var object_type = $(this).attr("object");
                $.ajax({
                    type: "POST",
                    url: "/landing/remove-restricted-object",
                    data: { id: id, type: object_type },
                    dataType: "JSON",
                    statusCode: {
                        200: function _(feeback) {
                            if (feeback.status == "fail") {
                                Notifier.showTimedMessage(feeback.msg, "warning", 2);
                                return;
                            }
                            Notifier.showTimedMessage("Revoke successful", "information", 2);

                            window.location.reload();
                        },
                        412: function _() {
                            location.href = "/";
                        }
                    }
                });
            });
            return false;
        }
    }]);

    return RestrictedObjectSelector;
})();

exports["default"] = RestrictedObjectSelector;
module.exports = exports["default"];

},{}]},{},[1]);
