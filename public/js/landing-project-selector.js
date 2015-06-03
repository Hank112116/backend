(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var _libsProjectSelector = require("./libs/ProjectSelector");

var _libsProjectSelector2 = _interopRequireDefault(_libsProjectSelector);

new _libsProjectSelector2["default"]();

},{"./libs/ProjectSelector":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ProjectSelector = (function () {
    function ProjectSelector() {
        var _this = this;

        _classCallCheck(this, ProjectSelector);

        this.$root = $("body");
        this.$group = this.$root.find("#block-group").first();
        this._setFeatureBlocks();
        this.$root.find(".js-search-form").each(function (kee, form_block) {
            return _this._setSearchForm(form_block);
        });
        this._textareaCount();
    }

    _createClass(ProjectSelector, [{
        key: "_setSearchForm",
        value: function _setSearchForm(block) {
            var instance = this,
                $block = $(block),
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
                    success: function success(feeback) {
                        $(block).find(".search_id")[0].value = "";

                        if (feeback.status == "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            return;
                        }

                        Notifier.showTimedMessage("Add successful", "information", 2);
                        instance.$group.append(feeback.new_block);
                        instance._setFeatureBlocks();
                        instance._textareaCount();
                    }
                });

                return false;
            });
        }
    }, {
        key: "_setFeatureBlocks",
        value: function _setFeatureBlocks() {
            var instance = this;
            instance._resetBlocksOrder();

            this.$root.find(".js-block").each(function (kee, block) {
                var $block = $(block);
                var remove_btn = $block.find(".js-remove").first();
                var move_up_btn = $block.find(".js-move-up").first();
                var move_down_btn = $block.find(".js-move-down").first();

                remove_btn.unbind("click").click(function () {
                    $block.fadeOut("fast", function () {
                        $block.remove();
                    });
                });

                move_up_btn.unbind("click").click(function () {
                    instance.$group.animate({ opacity: 0 }, 500, "", function () {
                        $block.insertBefore($block.prev());
                        instance._resetBlocksOrder();
                        instance.$group.animate({ opacity: 1 }, 500);
                    });
                });

                move_down_btn.unbind("click").click(function () {
                    instance.$group.animate({ opacity: 0 }, 500, "", function () {
                        $block.insertAfter($block.next());
                        instance._resetBlocksOrder();
                        instance.$group.animate({ opacity: 1 }, 500);
                    });
                });
            });
        }
    }, {
        key: "_resetBlocksOrder",
        value: function _resetBlocksOrder() {
            var order = 1;

            this.$root.find(".js-block").each(function (kee, block) {
                $(block).find(".js-order").first().attr("value", order);
                order++;
            });
        }
    }, {
        key: "_textareaCount",
        value: function _textareaCount() {
            $("textarea[maxlength]").keyup(function () {
                var $this = $(this);
                var limit = parseInt($this.attr("maxlength"));
                var text = $this.val();
                var chars = text.length;
                var userId = $this.attr("rel");
                var tag = "count_" + userId.toString();
                $("#" + tag).text(chars + "/" + limit);
            });
        }
    }]);

    return ProjectSelector;
})();

exports["default"] = ProjectSelector;
module.exports = exports["default"];

},{}]},{},[1]);
