"use strict";
require("./libs/RecommendExpert.js");
require("./libs/InternalProjectMemo.js");
require("./libs/ProjectProposeRecommend.js");
import * as SweetAlert from "./libs/SweetAlert";

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var $this      = $(this);
        var project_id = $this.attr("rel");
        SweetAlert.alert({
            title: "Approve and release the Schedule?",
            desc: "Once confirmed, the Hub schedule will be released to the Project owner.",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () =>
                approve_schedule(project_id)

        });
        return false;
    });
    $("#user_referral_total").text($("#user_referral_count").val());

    function approve_schedule(project_id){
        $.ajax({
            type: "POST",
            url: "/project/approve-schedule",
            data: {
                project_id: project_id
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    }
});
