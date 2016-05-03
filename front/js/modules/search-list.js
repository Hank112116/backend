"use strict";

export function init() {
    $(".js-btn-search").click(function () {
        $(this).closest("form").submit();
    });
}
