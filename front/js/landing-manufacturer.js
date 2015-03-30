"use strict";

import superagent from "superagent"

$(function () {
    $("#js-add-manufacturer").click(function (event) {
        event.preventDefault();

        superagent
            .get("/landing/get-new-manufacturer")
            .end((err, res) => $("#manufacturers").append(res.text));

        return false;
    });

    $("body").on("click", ".icon-remove", function() {
        $(this).parent().remove()
    });
});
