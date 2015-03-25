"use strict";

export function init() {
    $("input[type=checkbox]").iCheck({
        checkboxClass: "icheckbox_minimal-blue icheckbox"
    });
}

export function initRadio() {
    $("input[type=radio]").iCheck({
        radioClass: "iradio_minimal-blue iradio"
    });
}
