"use strict";

$(function () {
    $("#js-add-manufacturer").click(function (event) {
        event.preventDefault();
        $.get("/landing/get-new-manufacturer", (block) => $("#manufacturers").append(block));
        return false;
    });

    $("body").on("click", ".icon-remove", function() {
        $(this).parent().remove()
    });
});
