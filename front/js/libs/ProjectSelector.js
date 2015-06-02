"use strict";

export default class ProjectSelector {
    constructor() {
        this.$root = $("body");
        this.$group = this.$root.find("#block-group").first();
        this._setFeatureBlocks();
        this.$root.find(".js-search-form").each( (kee, form_block) => this._setSearchForm(form_block) );
        this._textareaCount();
    }

    _setSearchForm(block) {
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

    _setFeatureBlocks() {
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

    _resetBlocksOrder() {
        var order = 1;

        this.$root.find(".js-block").each(function (kee, block) {
            $(block).find(".js-order").first().attr("value", order);
            order++;
        });
    }
    _textareaCount() {
        $('textarea[maxlength]').keyup(function(){
            var limit = parseInt($(this).attr('maxlength'));
            var text = $(this).val();
            var chars = text.length;
            var userId = $(this).attr("rel");
            var tag = "count_"+userId.toString();
            console.log(tag);
            $("#"+tag).html(chars+"/"+limit);
        }); 
    }
}
