"use strict";

var FormUtility = require("./libs/FormUtility");

import ProjectUpdater from "./libs/ProjectUpdater";
import * as SweetAlert from "./libs/SweetAlert";

$(() => {
    FormUtility.editor();
    new ProjectUpdater().bootProject();
});

$(function () {
    $(".js-delete").click(function (e) {
        e.preventDefault();
        var link = this.href;

        SweetAlert.alert({
            title: "Delete?",
            desc: "Sure to delete this project？",
            confirmButton: "Yes",
            handleOnConfirm: (is_confirm) => {
                if (is_confirm){
                    window.location = link;
                } else {
                    return false;
                }
            }
        });
    });
});
