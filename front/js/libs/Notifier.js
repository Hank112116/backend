"use strict";

var humane = require("../vendor/humane/humane");

export var humane;

export function showMessage(content, level) {
    if (!content) {
        return;
    }

    humane.create({
        addnCls: level || "info"
    }).log(content);
}

export  function showTimedMessage(content, level, sec) {
    if (!content) {
        return;
    }

    humane.create({
        timeout: sec * 1000,
        addnCls: level || "info"
    }).log(content);
}
