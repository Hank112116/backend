"use strict";

export default class SolutionCategoryUpdater {
    constructor() {
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

    bindEvents() {
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

    initSelected() {
        var main_id = this.$main_input.val(),
            sub_id = this.$sub_input.val() || "0";

        this.initMainSelected(main_id, sub_id);
        this.initSubSelected(main_id, sub_id);
    }

    initMainSelected(main_id, sub_id) {
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

    initSubSelected(main_id, sub_id) {
        var $sub_option = this.$sub_options.find("#sub-category-" + main_id + "-" + sub_id);

        if (!$sub_option) {
            return;
        }

        this.setSubSelection($sub_option);
    }

    setMainSelection($option) {
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

    setSubSelection($option) {
        var sub_id = $option.data("sub-category-id"),
            sub_text = $option.html();

        if($option.length === 0) {
            return;
        }

        this.setSubInput(sub_id);
        this.setSubSelectedText(sub_text);
        this.activeSubSelectedItem($option, sub_id);

        this.$sub_options.removeClass("active");
    }

    setMainInput(main_id) {
        this.$main_input.val(main_id);
    }

    setSubInput(sub_id) {
        this.$sub_input.val(sub_id);
    }

    activeMainSelectedItem($option, main_id) {
        $option.addClass("active");
        this.$main_options.find("[data-main-category-id!=" + main_id + "]").removeClass("active");
    }

    activeSubSelectedItem($option, sub_id) {
        $option.addClass("active");
        this.$sub_options.find("[data-sub-category-id!=" + sub_id + "]").removeClass("active");
    }

    setMainSelectedText(main_text) {
        this.$main.html(main_text);
        this.setSubSelectedText("");
    }

    setSubSelectedText(sub_text) {
        this.$sub.html(sub_text);
    }

    resetSubItems(main_id) {
        this.$sub_options.find("[data-main-category-id!=" + main_id + "]").hide();
        this.$sub_options.find("[data-main-category-id=" + main_id + "]").show();
    }
}
