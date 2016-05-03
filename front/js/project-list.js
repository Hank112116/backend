"use strict";
require("./libs/RecommendExpert.js");
require("./libs/InternalProjectMemo.js");
require("./libs/ProjectProposeRecommend.js");
import * as SweetAlert from "./libs/SweetAlert";

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var link = this.href;

        SweetAlert.alert({
            title: "Approve and release the Schedule?",
            desc: "Once confirmed, the Hub schedule will be released to the Project owner.",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () => window.location = link

        });
        return false;
    });

    $("input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $("form").submit();
        }
    });
});
