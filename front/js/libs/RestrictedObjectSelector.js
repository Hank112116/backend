"use strict";

export default class RestrictedObjectSelector {
    constructor() {
        this.$root = $("body");
        this.$root.find(".js-search-form").each( (kee, form_block) => this._setSearchForm(form_block) );
        this._revoke();
    }

    _setSearchForm(block) {
        let $block = $(block),
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
                    204: function () {
                        Notifier.showTimedMessage("Add successful", "information", 2);

                        window.location.reload();
                    },
                    400: function (res) {
                        Notifier.showTimedMessage(res.responseJSON.error, "warning", 2);
                    },
                    404: function (res) {
                        Notifier.showTimedMessage(res.responseJSON.error, "warning", 2);
                    },
                    412: function () {
                        location.href = "/";
                    }
                }
            });

            return false;
        });
    }

    _revoke() {
        let $btn = $(".js-revoke");
        $btn.click(this, function () {
            let id = $(this).attr("rel");
            let object_type = $(this).attr("object");
            $.ajax({
                type: "POST",
                url: "/landing/remove-restricted-object",
                data: { id: id, type: object_type },
                dataType: "JSON",
                statusCode: {
                    204: function () {
                        Notifier.showTimedMessage("Revoke successful", "information", 2);

                        window.location.reload();
                    },
                    400: function (res) {
                        Notifier.showTimedMessage(res.responseJSON.error, "warning", 2);
                    },
                    404: function (res) {
                        Notifier.showTimedMessage(res.responseJSON.error, "warning", 2);
                    },
                    412: function () {
                        location.href = "/";
                    }
                }
            });
        });
        return false;
    }
}
