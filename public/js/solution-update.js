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

var _createClass = (function () { function defineProperties(target, props) { for (var key in props) { var prop = props[key]; prop.configurable = true; if (prop.value) prop.writable = true; } Object.defineProperties(target, props); } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _classCallCheck = function (instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } };

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

    _createClass(SolutionCategoryUpdater, {
        bindEvents: {
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
        },
        initSelected: {
            value: function initSelected() {
                var main_id = this.$main_input.val(),
                    sub_id = this.$sub_input.val() || "0";

                this.initMainSelected(main_id, sub_id);
                this.initSubSelected(main_id, sub_id);
            }
        },
        initMainSelected: {
            value: function initMainSelected(main_id, sub_id) {
                if (!main_id) {
                    return;
                }

                var $main_option = this.$main_options.find("[data-main-category-id=" + main_id + "][data-sub-category-id=" + sub_id + "]");

                if ($main_option.length == 0) {
                    $main_option = this.$main_options.find("[data-main-category-id=" + main_id + "][data-sub-category-id=0]");
                }

                if ($main_option.length == 0) {
                    return;
                }

                this.setMainSelection($main_option);
            }
        },
        initSubSelected: {
            value: function initSubSelected(main_id, sub_id) {
                var $sub_option = this.$sub_options.find("#sub-category-" + main_id + "-" + sub_id);

                if (!$sub_option) {
                    return;
                }

                this.setSubSelection($sub_option);
            }
        },
        setMainSelection: {
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
        },
        setSubSelection: {
            value: function setSubSelection($option) {
                var sub_id = $option.data("sub-category-id"),
                    sub_text = $option.html(),
                    main_id = $option.data("main-category-id");

                this.setSubInput(sub_id);
                this.setSubSelectedText(sub_text);
                this.activeSubSelectedItem($option, sub_id);

                this.$sub_options.removeClass("active");
            }
        },
        setMainInput: {
            value: function setMainInput(main_id) {
                this.$main_input.val(main_id);
            }
        },
        setSubInput: {
            value: function setSubInput(sub_id) {
                this.$sub_input.val(sub_id);
            }
        },
        activeMainSelectedItem: {
            value: function activeMainSelectedItem($option, main_id) {
                $option.addClass("active");
                this.$main_options.find("[data-main-category-id!=" + main_id + "]").removeClass("active");
            }
        },
        activeSubSelectedItem: {
            value: function activeSubSelectedItem($option, sub_id) {
                $option.addClass("active");
                this.$sub_options.find("[data-sub-category-id!=" + sub_id + "]").removeClass("active");
            }
        },
        setMainSelectedText: {
            value: function setMainSelectedText(main_text) {
                this.$main.html(main_text);
                this.setSubSelectedText("");
            }
        },
        setSubSelectedText: {
            value: function setSubSelectedText(sub_text) {
                this.$sub.html(sub_text);
            }
        },
        resetSubItems: {
            value: function resetSubItems(main_id) {
                this.$sub_options.find("[data-main-category-id!=" + main_id + "]").hide();
                this.$sub_options.find("[data-main-category-id=" + main_id + "]").show();
            }
        }
    });

    return SolutionCategoryUpdater;
})();

module.exports = SolutionCategoryUpdater;

},{}],4:[function(require,module,exports){
"use strict";

var _defineProperty = function (obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); };

var _createClass = (function () { function defineProperties(target, props) { for (var key in props) { var prop = props[key]; prop.configurable = true; if (prop.value) prop.writable = true; } Object.defineProperties(target, props); } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _classCallCheck = function (instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } };

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

    _createClass(SolutionCustomerUpdater, {
        buildCustomers: {
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
        },
        removeCustomer: {
            value: function removeCustomer($customer_block) {
                $customer_block.remove();
                this.resetCustomerInput();
            }
        },
        resetCustomerInput: {
            value: function resetCustomerInput() {
                var data = [];

                this.$customers.find(".js-customer-block").each(function (index, block) {
                    var $block = $(block);

                    data.push((function () {
                        var _data$push = {};

                        _defineProperty(_data$push, ATTRS.COMPANY_NAME, $block.find(".js-customer-name").val());

                        _defineProperty(_data$push, ATTRS.COMPANY_URL, $block.find(".js-customer-url").val());

                        return _data$push;
                    })());
                });

                this.$input.val(JSON.stringify(data));
            }
        }
    });

    return SolutionCustomerUpdater;
})();

module.exports = SolutionCustomerUpdater;

},{}],5:[function(require,module,exports){
"use strict";

var _interopRequire = function (obj) { return obj && obj.__esModule ? obj["default"] : obj; };

exports.boot = boot;
Object.defineProperty(exports, "__esModule", {
    value: true
});
"use strict";

var ProjectUpdater = _interopRequire(require("./ProjectUpdater"));

var SolutionCategoryUpdater = _interopRequire(require("./SolutionCategoryUpdater"));

var SolutionCustomerUpdater = _interopRequire(require("./SolutionCustomerUpdater"));

function boot() {
    var projectUpdater = new ProjectUpdater();

    projectUpdater.initSelectTag($("[data-select-tags=project_progress]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_progress]"));

    projectUpdater.initSelectTag($("[data-select-tags=project_category]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_category]"));

    projectUpdater.initSelectTag($("[data-select-tags=certification]"));
    projectUpdater.initTagsInputs(["certification_other"]);

    projectUpdater.initProjectTagSelector();

    new SolutionCategoryUpdater();
    new SolutionCustomerUpdater();
}

},{"./ProjectUpdater":2,"./SolutionCategoryUpdater":3,"./SolutionCustomerUpdater":4}],6:[function(require,module,exports){
"use strict";

var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { "default": obj }; };

var FormUtility = _interopRequireWildcard(require("./libs/FormUtility"));

var SolutionUpdater = _interopRequireWildcard(require("./libs/SolutionUpdater"));

$((function () {
    FormUtility.editor();
    SolutionUpdater.boot();
})());

},{"./libs/FormUtility":1,"./libs/SolutionUpdater":5}]},{},[6])