"use strict";

export function init() {
    var $start = $("#js-datepicker-sdate"),
        $end = $("#js-datepicker-edate"),
        $picker = $(".datepicker");

    $start.datepicker({format: "yyyy-mm-dd"});
    $end.datepicker({format: "yyyy-mm-dd"});

    //Fix the calendar position
    $end.on("focus", () => $picker.css("left", "50%"));
};
