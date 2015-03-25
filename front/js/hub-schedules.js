"use strict";

import SweetAlert from "./libs/SweetAlert";

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var link = this.href;

        SweetAlert.alert({
            title: "Approve?",
            desc: "",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () => window.location = link
        });

        return false;
    });
});
