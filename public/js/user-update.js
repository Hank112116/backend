(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

exports.locationSelector = locationSelector;
exports.editor = editor;
Object.defineProperty(exports, "__esModule", {
    value: true
});
"use strict";

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

        imageUploadCallback: function (image, response) {
            if (response.status == "fail") {
                Notifier.showMessage(response.msg, "warning");
                image.remove();
            }
        },

        imageUploadErrorCallback: function () {
            Notifier.showMessage("Some errors happened, try again later", "warning");
        }
    });
}

},{}],2:[function(require,module,exports){
"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var key in props) { var prop = props[key]; prop.configurable = true; if (prop.value) prop.writable = true; } Object.defineProperties(target, props); } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _classCallCheck = function (instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } };

/*
 * @dependency bootstrap-tagsinput.js
 */

var ProjectUpdater = (function () {
    function ProjectUpdater() {
        _classCallCheck(this, ProjectUpdater);
    }

    _createClass(ProjectUpdater, {
        bootProject: {
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
        },
        initProjectTagSelector: {
            value: function initProjectTagSelector() {
                this.initSelectTag($("[data-select-tags=project-tag]"));
            }
        },
        initTagsInputs: {
            value: function initTagsInputs(ids) {
                var _self = this;
                _.each(ids, function (id) {
                    _self.initTagsInput($("#" + id));
                });
            }
        },
        initTagsInput: {
            value: function initTagsInput($block) {
                $block.tagsinput({
                    confirmKeys: [13],
                    removeable: true,
                    allowDuplicates: false,
                    tagClass: "bootstrap-tagsinput--tag"
                });
            }
        },
        initSelectTag: {
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
        },
        initSelectOtherTag: {
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
        },
        initSelectOne: {
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
        },
        initSelectUnsure: {
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
        },
        initDatePicker: {
            value: function initDatePicker(ids) {
                _.each(ids, function (id) {
                    return $("#" + id).datepicker({ format: "yyyy-mm-dd" });
                });
            }
        }
    });

    return ProjectUpdater;
})();

module.exports = ProjectUpdater;

},{}],3:[function(require,module,exports){
"use strict";

exports.init = init;
exports.initRadio = initRadio;
Object.defineProperty(exports, "__esModule", {
    value: true
});
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

var _interopRequire = function (obj) { return obj && obj.__esModule ? obj["default"] : obj; };

var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { "default": obj }; };

var FormUtility = _interopRequireWildcard(require("./libs/FormUtility"));

var icheck = _interopRequireWildcard(require("./modules/icheck"));

var ProjectUpdater = _interopRequire(require("./libs/ProjectUpdater"));

$(function () {
    icheck.initRadio();

    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));
});

},{"./libs/FormUtility":1,"./libs/ProjectUpdater":2,"./modules/icheck":3}]},{},[4])