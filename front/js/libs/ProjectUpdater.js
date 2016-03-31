"use strict";

/*
 * @dependency bootstrap-tagsinput.js
 */

export default class ProjectUpdater {
    constructor() {

    }

    bootProject() {
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

    initProjectTagSelector() {
        this.initSelectTag($("[data-select-tags=project-tag]"));
    }

    initTagsInputs(ids) {
        var _self = this;
        _.each(ids, function (id) {
            _self.initTagsInput($("#" + id));
        });
    }

    initTagsInput($block) {
        $block.tagsinput({
            confirmKeys: [13],
            removeable: true,
            allowDuplicates: false,
            tagClass: "bootstrap-tagsinput--tag"
        });
    }

    initSelectTag($tags_block) {
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

    initSelectOtherTag($other_block) {
        var $other_input = $other_block.find("input");

        $other_block.click( () => $other_input.focus() );

        $other_input
            .focus( () => !$other_block.hasClass("active") && $other_block.addClass("active") )
            .blur( () => !$other_input.val() && $other_block.removeClass("active") );
    }

    initSelectOne($tags_block) {
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

    initSelectUnsure($block) {
        var $input = $block.find("input"),
            $unsure = $block.find("[data-unsure]");

        $input.change( () => {
            if ($input.val().length > 0) {
                $unsure.removeClass("active");
            }
        });

        $unsure.click( () => {
            $unsure.addClass("active");
            $input.val("");
        });
    }

    initDatePicker(ids) {
        _.each(ids, (id) => $("#" + id).datepicker({ format: "yyyy-mm-dd" }) );
    }
}
