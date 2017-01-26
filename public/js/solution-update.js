(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.getCSRFToken = getCSRFToken;

function getCSRFToken() {
    return $("meta[name='csrf-token']").attr("content");
}

},{}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.locationSelector = locationSelector;
exports.editor = editor;
var CommonHelper = require("./CommonHelper");

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
        langs: {
            en: {
                bold: "Bold",
                italic: "Italic",
                formatting: "Formatting",
                header3: "Title",
                header4: "Sub Title",
                paragraph: "Normal text",
                deleted: "Deleted",
                underline: "Underline",
                horizontalrule: "Insert Horizontal Rule",
                unorderedlist: "Unordered List",
                orderedlist: "Ordered List",
                image: "Insert Image",
                video: "Insert Video",
                link: "Link",
                link_insert: "Insert link",
                unlink: "Unlink",
                upload: "Upload",
                drop_file_here: "Drop file here",
                or_choose: "Or choose",
                cancel: "Cancel",
                insert: "Insert",
                video_html_code: "Video Embed Code",
                link_new_tab: "Open link in new tab",
                anchor: "Anchor",
                text: "Text",
                mailto: "Email",
                web: "URL"
            }
        },
        buttons: ["bold", "italic", "formatting", "deleted", "underline", "|", "horizontalrule", "unorderedlist", "orderedlist", "|", "image", "video", "link"],
        formattingTags: ["h3", "h4", "p"],
        deniedTags: ["html", "head", "link", "body", "meta", "script", "style", "applet", "h1", "h2"],
        minHeight: 500,
        placeholder: "Describe the idea behind your hardware design, the journey so far, what\"s done, and what needs to be done.",
        removeEmptyTags: false,
        autoresize: false,
        cleanup: true,
        convertImageLinks: true,
        convertVideoLinks: true,
        dragUpload: true,
        imageUpload: "/upload-editor-image",
        uploadFields: { _token: CommonHelper.getCSRFToken() },
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

},{"./CommonHelper":1}],3:[function(require,module,exports){
"use strict";

/*
 * @dependency bootstrap-tagsinput.js
 */

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ProjectUpdater = (function () {
    function ProjectUpdater() {
        _classCallCheck(this, ProjectUpdater);
    }

    _createClass(ProjectUpdater, [{
        key: "bootProject",
        value: function bootProject() {
            this.initTagsInputs(["key_component", "strengths"]);

            this.initSelectTag($("[data-select-tags=resource]"));
            this.initSelectOtherTag($("[data-other-tag=resource]"));

            this.initSelectOne($("[data-select-one=quantity]"));
            this.initSelectOne($("[data-select-one=budget]"));
            this.initSelectOne($("[data-select-one=team-size]"));

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
                $(tag).click(function () {
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

                $selected.click(function () {
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

            $unsure.click(function () {
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

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SolutionCategoryUpdater = (function () {
    function SolutionCategoryUpdater() {
        _classCallCheck(this, SolutionCategoryUpdater);

        this.$wrapper = $("#category-wrapper");

        this.$main = this.$wrapper.find("#main-category");
        this.$main_options = $("#main-category-options");

        this.$sub = $("#sub-category");
        this.$sub_options = $("#sub-category-options");

        this.$main_input = this.$wrapper.find("[name=solution_type]");
        this.$sub_input = this.$wrapper.find("[name=solution_detail]");

        this.bindEvents();
        this.initSelected();
    }

    _createClass(SolutionCategoryUpdater, [{
        key: "bindEvents",
        value: function bindEvents() {
            var _self = this;

            _self.$main.click(function () {
                _self.$main_options.toggleClass("active");
                _self.$sub_options.removeClass("active");
            });

            _self.$sub.click(function () {
                _self.$sub_options.toggleClass("active");
                _self.$main_options.removeClass("active");
            });

            _self.$main_options.find("li").click(function () {
                _self.setMainSelection($(this));
            });

            _self.$sub_options.find("li").click(function () {
                _self.setSubSelection($(this));
            });
        }
    }, {
        key: "initSelected",
        value: function initSelected() {
            var main_id = this.$main_input.val(),
                sub_id = this.$sub_input.val() || "0";

            this.initMainSelected(main_id, sub_id);
            this.initSubSelected(main_id, sub_id);
        }
    }, {
        key: "initMainSelected",
        value: function initMainSelected(main_id, sub_id) {
            if (!main_id) {
                return;
            }

            var $main_option = this.$main_options.find("[data-main-category-id=" + main_id + "][data-sub-category-id=" + sub_id + "]");

            if ($main_option.length === 0) {
                $main_option = this.$main_options.find("[data-main-category-id=" + main_id + "][data-sub-category-id=0]");
            }

            if ($main_option.length === 0) {
                return;
            }

            this.setMainSelection($main_option);
        }
    }, {
        key: "initSubSelected",
        value: function initSubSelected(main_id, sub_id) {
            var $sub_option = this.$sub_options.find("#sub-category-" + main_id + "-" + sub_id);

            if (!$sub_option) {
                return;
            }

            this.setSubSelection($sub_option);
        }
    }, {
        key: "setMainSelection",
        value: function setMainSelection($option) {
            var main_id = $option.data("main-category-id"),
                main_text = $option.html(),
                sub_id = $option.data("sub-category-id");

            this.setMainInput(main_id);
            this.setMainSelectedText(main_text);
            this.activeMainSelectedItem($option, main_id);

            this.setSubInput(sub_id);
            this.resetSubItems(main_id);

            this.$main_options.removeClass("active");
        }
    }, {
        key: "setSubSelection",
        value: function setSubSelection($option) {
            var sub_id = $option.data("sub-category-id"),
                sub_text = $option.html();

            if ($option.length === 0) {
                return;
            }

            this.setSubInput(sub_id);
            this.setSubSelectedText(sub_text);
            this.activeSubSelectedItem($option, sub_id);

            this.$sub_options.removeClass("active");
        }
    }, {
        key: "setMainInput",
        value: function setMainInput(main_id) {
            this.$main_input.val(main_id);
        }
    }, {
        key: "setSubInput",
        value: function setSubInput(sub_id) {
            this.$sub_input.val(sub_id);
        }
    }, {
        key: "activeMainSelectedItem",
        value: function activeMainSelectedItem($option, main_id) {
            $option.addClass("active");
            this.$main_options.find("[data-main-category-id!=" + main_id + "]").removeClass("active");
        }
    }, {
        key: "activeSubSelectedItem",
        value: function activeSubSelectedItem($option, sub_id) {
            $option.addClass("active");
            this.$sub_options.find("[data-sub-category-id!=" + sub_id + "]").removeClass("active");
        }
    }, {
        key: "setMainSelectedText",
        value: function setMainSelectedText(main_text) {
            this.$main.html(main_text);
            this.setSubSelectedText("");
        }
    }, {
        key: "setSubSelectedText",
        value: function setSubSelectedText(sub_text) {
            this.$sub.html(sub_text);
        }
    }, {
        key: "resetSubItems",
        value: function resetSubItems(main_id) {
            this.$sub_options.find("[data-main-category-id!=" + main_id + "]").hide();
            this.$sub_options.find("[data-main-category-id=" + main_id + "]").show();
        }
    }]);

    return SolutionCategoryUpdater;
})();

exports["default"] = SolutionCategoryUpdater;
module.exports = exports["default"];

},{}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ATTRS = {
    COMPANY_NAME: "companyName",
    COMPANY_URL: "companyUrl"
};

var SolutionCustomerUpdater = (function () {
    function SolutionCustomerUpdater() {
        _classCallCheck(this, SolutionCustomerUpdater);

        this.$add = $("#add-customer");
        this.$wrapper = $("#customers-wrapper");
        this.$customers = this.$wrapper.find("#customers");
        this.$input = this.$wrapper.find("[name=customer_portfolio]");
        this.$template = this.$wrapper.find(".customer-template > div");

        _.each(this.$customers.data("customers"), this.buildCustomers.bind(this));

        this.$add.click(this.buildCustomers.bind(this));
        this.resetCustomerInput();
    }

    _createClass(SolutionCustomerUpdater, [{
        key: "buildCustomers",
        value: function buildCustomers(customer) {
            var $template = this.$template.clone(),
                $input_name = $template.find(".js-customer-name"),
                $input_url = $template.find(".js-customer-url"),
                $delete = $template.find(".js-customer-delete");

            customer = customer || { url: "", name: "" };

            $input_name.val(customer.name);
            $input_name.focusout(this.resetCustomerInput.bind(this));

            $input_url.val(customer.url);
            $input_url.focusout(this.resetCustomerInput.bind(this));

            $delete.click(this.removeCustomer.bind(this, $template));

            this.$customers.append($template);

            return $template;
        }
    }, {
        key: "removeCustomer",
        value: function removeCustomer($customer_block) {
            $customer_block.remove();
            this.resetCustomerInput();
        }
    }, {
        key: "resetCustomerInput",
        value: function resetCustomerInput() {
            var data = [];

            this.$customers.find(".js-customer-block").each(function (index, block) {
                var _data$push;

                var $block = $(block);

                data.push((_data$push = {}, _defineProperty(_data$push, ATTRS.COMPANY_NAME, $block.find(".js-customer-name").val()), _defineProperty(_data$push, ATTRS.COMPANY_URL, $block.find(".js-customer-url").val()), _data$push));
            });

            this.$input.val(JSON.stringify(data));
        }
    }]);

    return SolutionCustomerUpdater;
})();

exports["default"] = SolutionCustomerUpdater;
module.exports = exports["default"];

},{}],6:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.boot = boot;
exports.approveSolution = approveSolution;
exports.rejectSolution = rejectSolution;

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var _ProjectUpdater = require("./ProjectUpdater");

var _ProjectUpdater2 = _interopRequireDefault(_ProjectUpdater);

var _SolutionCategoryUpdater = require("./SolutionCategoryUpdater");

var _SolutionCategoryUpdater2 = _interopRequireDefault(_SolutionCategoryUpdater);

var _SolutionCustomerUpdater = require("./SolutionCustomerUpdater");

var _SolutionCustomerUpdater2 = _interopRequireDefault(_SolutionCustomerUpdater);

function boot() {
    var projectUpdater = new _ProjectUpdater2["default"]();

    projectUpdater.initSelectTag($("[data-select-tags=project_progress]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_progress]"));

    projectUpdater.initSelectTag($("[data-select-tags=project_category]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_category]"));

    projectUpdater.initSelectTag($("[data-select-tags=certification]"));
    projectUpdater.initTagsInputs(["certification_other"]);

    projectUpdater.initProjectTagSelector();

    new _SolutionCategoryUpdater2["default"]();
    new _SolutionCustomerUpdater2["default"]();
}

function approveSolution() {
    $(".js-approve-solution").click(function () {
        var $this = $(this);
        var solution_id = $this.attr("rel");
        $.ajax({
            type: "POST",
            url: "/solution/approve/" + solution_id,
            dataType: "JSON",
            statusCode: {
                204: function _() {
                    Notifier.showTimedMessage("Upload success", "information", 2);
                    location.href = "/solution/detail/" + solution_id;
                },
                403: function _($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                404: function _($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                500: function _() {
                    Notifier.showTimedMessage("Server error", "warning", 2);
                }
            }
        });
    });
}

function rejectSolution() {
    $(".js-reject-solution").click(function () {
        var $this = $(this);
        var solution_id = $this.attr("rel");
        $.ajax({
            type: "POST",
            url: "/solution/reject/" + solution_id,
            dataType: "JSON",
            statusCode: {
                204: function _() {
                    Notifier.showTimedMessage("Upload success", "information", 2);
                    location.href = "/solution/detail/" + solution_id;
                },
                403: function _($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                404: function _($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                500: function _() {
                    Notifier.showTimedMessage("Server error", "warning", 2);
                }
            }
        });
    });
}

},{"./ProjectUpdater":3,"./SolutionCategoryUpdater":4,"./SolutionCustomerUpdater":5}],7:[function(require,module,exports){
"use strict";

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } }

var _libsFormUtility = require("./libs/FormUtility");

var FormUtility = _interopRequireWildcard(_libsFormUtility);

var _libsSolutionUpdater = require("./libs/SolutionUpdater");

var SolutionUpdater = _interopRequireWildcard(_libsSolutionUpdater);

$(function () {
    FormUtility.editor();
    SolutionUpdater.boot();
    SolutionUpdater.approveSolution();
    SolutionUpdater.rejectSolution();
});

},{"./libs/FormUtility":2,"./libs/SolutionUpdater":6}]},{},[7]);
