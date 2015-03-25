"use strict";

var humane = require("../vendor/humane-js/humane");

export var humane;

export function showMessage(content, level) {
    if (!content) {
        return;
    }

    humane.log(content, {
        addnCls: level || "info"
    });
}

export  function showTimedMessage(content, level, sec) {
    if (!content) {
        return;
    }

    humane.log(content, {
        timeout: sec * 1000,
        addnCls: level || "info"
    });
}
