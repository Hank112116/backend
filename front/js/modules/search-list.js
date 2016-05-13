"use strict";

export function init() {
    $(".js-btn-search").click(function () {
        $(this).closest("form").submit();
    });
}

$("input").keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        $("form").submit();
    }
});
