(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } }

var _libsFormUtility = require("./libs/FormUtility");

var FormUtility = _interopRequireWildcard(_libsFormUtility);

var _modulesIcheck = require("./modules/icheck");

var icheck = _interopRequireWildcard(_modulesIcheck);

var _libsProjectUpdater = require("./libs/ProjectUpdater");

var _libsProjectUpdater2 = _interopRequireDefault(_libsProjectUpdater);

$(function () {
    icheck.initRadio();

    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));

    FormUtility.editor();
    new _libsProjectUpdater2["default"]().initSelectTag($("[data-select-tags=expertises]"));
});

},{"./libs/FormUtility":2,"./libs/ProjectUpdater":3,"./modules/icheck":4}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.locationSelector = locationSelector;
exports.editor = editor;
var isKeyEnter = function isKeyEnter(e) {
    var code = e.keyCode || e.which;
    return code == 13;
};

var preventEnter = function preventEnter() {
    $("form").on("keydown", function (e) {
        if (isKeyEnter(e)) {
            e.preventDefault();
            return false;
        }
    });
};

var isKeyEnter;
exports.isKeyEnter = isKeyEnter;
var preventEnter;

exports.preventEnter = preventEnter;

function locationSelector($column) {
    if (!google) {
        console.log("No google variable, maybe network breakdown");
        return;
    }

    new google.maps.places.Autocomplete($column[0], { types: ["geocode"] });

    $column.focus(function () {
        return preventEnter();
    }).blur(function () {
        return $("form").unbind("keydown");
    });
}

function editor() {
    $(".js-editor").redactor({
        buttons: ["bold", "italic", "formatting", "deleted", "outdent", "indent", "|", "horizontalrule", "unorderedlist", "orderedlist", "|", "image", "video", "link"],

        formattingTags: ["h1", "h2", "h3", "h4", "p"],
        minHeight: 500,
        autoresize: false,
        cleanup: true,
        convertImageLinks: true,
        convertVideoLinks: true,
        dragUpload: true,
        imageUpload: "/upload-editor-image",

        imageUploadCallback: function imageUploadCallback(image, response) {
            if (response.status == "fail") {
                Notifier.showMessage(response.msg, "warning");
                image.remove();
            }
        },

        imageUploadErrorCallback: function imageUploadErrorCallback() {
            Notifier.showMessage("Some errors happened, try again later", "warning");
        }
    });
}

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/*
 * @dependency bootstrap-tagsinput.js
 */

var ProjectUpdater = (function () {
    function ProjectUpdater() {
        _classCallCheck(this, ProjectUpdater);
    }

    _createClass(ProjectUpdater, [{
        key: "bootProject",
        value: function bootProject() {
            this.initTagsInputs(["key_component", "team"]);

            this.initSelectTag($("[data-select-tags=resource]"));
            this.initSelectOtherTag($("[data-other-tag=resource]"));

            this.initSelectOne($("[data-select-one=quantity]"));

            this.initSelectUnsure($("[data-select-unsure=msrp]"));
            this.initSelectUnsure($("[data-select-unsure=shipping-date]"));

            this.initDatePicker(["launch_date"]);

            this.initProjectTagSelector();
        }
    }, {
        key: "initProjectTagSelector",
        value: function initProjectTagSelector() {
            this.initSelectTag($("[data-select-tags=project-tag]"));
        }
    }, {
        key: "initTagsInputs",
        value: function initTagsInputs(ids) {
            var _self = this;
            _.each(ids, function (id) {
                _self.initTagsInput($("#" + id));
            });
        }
    }, {
        key: "initTagsInput",
        value: function initTagsInput($block) {
            $block.tagsinput({
                confirmKeys: [13],
                removeable: true,
                allowDuplicates: false,
                tagClass: "bootstrap-tagsinput--tag"
            });
        }
    }, {
        key: "initSelectTag",
        value: function initSelectTag($tags_block) {
            var $input = $tags_block.find("input");

            $tags_block.find(".tag").each(function (index, tag) {
                $(tag).click(function (e) {
                    $(this).toggleClass("active");

                    var tags = _.map($tags_block.find(".tag.active"), function (tag) {
                        return $(tag).data("id");
                    });

                    $input.val(tags.join(","));
                });
            });
        }
    }, {
        key: "initSelectOtherTag",
        value: function initSelectOtherTag($other_block) {
            var $other_input = $other_block.find("input");

            $other_block.click(function () {
                return $other_input.focus();
            });

            $other_input.focus(function () {
                return !$other_block.hasClass("active") && $other_block.addClass("active");
            }).blur(function () {
                return !$other_input.val() && $other_block.removeClass("active");
            });
        }
    }, {
        key: "initSelectOne",
        value: function initSelectOne($tags_block) {
            var $input = $tags_block.find("input");

            $tags_block.find(".tag").each(function (index, tag) {
                var $selected = $(tag);

                $selected.click(function (e) {
                    $tags_block.find(".tag").not($selected).removeClass("active");
                    $selected.addClass("active");

                    $input.val($selected.data("id"));
                });
            });
        }
    }, {
        key: "initSelectUnsure",
        value: function initSelectUnsure($block) {
            var $input = $block.find("input"),
                $unsure = $block.find("[data-unsure]");

            $input.change(function () {
                if ($input.val().length > 0) {
                    $unsure.removeClass("active");
                }
            });

            $unsure.click(function (e) {
                $unsure.addClass("active");
                $input.val("");
            });
        }
    }, {
        key: "initDatePicker",
        value: function initDatePicker(ids) {
            _.each(ids, function (id) {
                return $("#" + id).datepicker({ format: "yyyy-mm-dd" });
            });
        }
    }]);

    return ProjectUpdater;
})();

exports["default"] = ProjectUpdater;
module.exports = exports["default"];

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.init = init;
exports.initRadio = initRadio;

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

},{}]},{},[1]);
