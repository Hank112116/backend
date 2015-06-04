// jshint unused: false
"use strict";

var sweetAlert = require("../vendor/sweetalert/sweetalert.es6.js");

export function alert(param) {

    window.sweetAlert({
        title: param.title || "Are you sure?",
        text: param.desc || "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: param.confirmButton || "Yes!!",
        closeOnConfirm: true
    }, param.handleOnConfirm);
    
}
