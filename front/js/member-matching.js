"use strict";
require("./libs/InternalUserMemo.js");

$(function () {
    let $document = $(document);

    $document.on("click", ".matching-data", function () {
        let $this = $(this);
        let $dialog =  $("#matching-dialog");
        let dstart = $("input[name=dstart]").val();
        let dend = $("input[name=dend]").val();
        let user_id = $this.attr("rel");

        $dialog.html("");

        $.ajax({
            type: "POST",
            url: "/report/matching-data",
            data: {
                user_id: user_id,
                dstart: dstart,
                dend: dend
            },
            statusCode: {
                200: function ($res) {
                    $dialog.html($res);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });

        $dialog.dialog({
            title: "Matching data (User #" + user_id + ")",
            height: 600,
            width: 1100
        });
    });
});
