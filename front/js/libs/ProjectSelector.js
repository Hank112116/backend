"use strict";

export default class ProjectSelector {
    constructor() {
        this.$root = $("body");
        this.$group = this.$root.find("#block-group").first();
        this.$editFeature = this.$root.find("#edit-feature-dialog").first();
        this._setFeatureBlocks();
        this.$root.find(".js-search-form").each( (kee, form_block) => this._setSearchForm(form_block) );
        this._openEditFeatureDialog();
        this._sortBlocks();
        this._updateFeatureList();
        this._editFeature();
    }

    _setSearchForm(block) {
        let instance = this,
            $block = $(block),
            $btn = $block.find("button");

        $block.submit(function (event) {
            event.preventDefault();
            $btn.click();
        });

        $btn.click(function (event) {
            event.preventDefault();

            let objectId   = $block.find(".search_id")[0].value;
            let objectType = $block.find(".search_type")[0].value;

            if (objectId === "" || objectType === "") {
                Notifier.showTimedMessage("Please enter object type and object id.", "warning", 2);
                return;
            }

            $.ajax({
                type: "POST",
                url: block.action,
                data: {
                    id: objectId,
                    type: objectType
                },
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
                }
            });

            return false;
        });
    }

    _setFeatureBlocks() {
        let instance = this;
        instance._resetBlocksOrder();

        this.$root.find(".js-block").each(function (kee, block) {
            let $block = $(block);
            let remove_btn = $block.find(".js-remove").first();
            let move_up_btn = $block.find(".js-move-up").first();
            let move_down_btn = $block.find(".js-move-down").first();

            remove_btn.unbind("click").click(function () {
                $block.fadeOut("fast", function () {
                    $block.remove();
                    instance._resetBlocksOrder();
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
        let order = 1;

        this.$root.find(".js-block").each(function (kee, block) {
            $(block).find(".js-order").first().attr("value", order);
            order++;
        });

        this._sortBlocks();
    }

    _sortBlocks() {
        let order = 1;
        this.$root.find(".js-block").each(function (kee, block) {
            $(block).find(".js-order-number").first().text(order);
            order++;
        });
    }

    _updateFeatureList() {
        this.$root.find(".btn-submit").click(function () {
            let features = [];
            $(".panel-body").each(function(index){
                let $this   = $(this);
                let feature = {};
                feature["objectType"] = $this.attr("object");
                feature["objectId"]   = $this.attr("rel");
                feature["order"]      = index + 1;
                features[index]       = feature;
            });

            if (features.length < 10) {
                Notifier.showTimedMessage("Feature item must be greater than 10", "warning", 3);
                return;
            }

            $.ajax({
                type: "POST",
                url: "/landing/update-feature",
                data: {
                    features: JSON.stringify(features)
                },
                dataType: "JSON",
                statusCode: {
                    200: function (feeback) {
                        if (feeback.status === "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            return;
                        }
                        Notifier.showTimedMessage("Update successful", "information", 2);
                        window.location.reload();
                    },
                    412: function () {
                        location.href = "/";
                    }
                }
            });
        });
    }

    _openEditFeatureDialog() {
        let instance = this;

        $(document).on("click", ".js-feature-edit", function () {
            $("#object_type").val("");
            $("#object_id").val("");

            let $this      = $(this);
            let objectType = $this.attr("object");
            let objectId   = $this.attr("rel");

            $("#block_id").val(objectType + "_" + objectId);

            instance.$editFeature.dialog({
                title: "Edit feature",
                height: 250,
                width: 400
            });
        });
    }

    _editFeature() {
        let instance = this;

        $("#edit-feature").click(function (event) {
            let $block     = $("#" + $("#block_id").val());
            let objectType = $("#object_type").val();
            let objectId   = $("#object_id").val();

            if (objectType === "" || objectId === "") {
                Notifier.showTimedMessage("Please enter object type and object id.", "warning", 2);
                return;
            }

            $.ajax({
                type: "POST",
                url: "/landing/find-feature",
                data: {
                    id: objectId,
                    type: objectType
                },
                dataType: "JSON",
                success: function success(feeback) {
                    if (feeback.status == "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    instance.$editFeature.dialog( "close" );
                    Notifier.showTimedMessage("Edit successful", "information", 2);
                    $block.replaceWith(feeback.new_block);
                    instance._setFeatureBlocks();
                    instance._sortBlocks();
                    event.preventDefault();
                }
            });
        });
    }
}
