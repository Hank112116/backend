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
            title: "Approve?",
            desc: "It'll take a bit long time to approve",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () => window.location = link

        });
        return false;
    });
});