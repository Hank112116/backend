"use strict";
require("./libs/RecommendExpert.js");
require("./libs/InternalProjectMemo.js");
import * as SweetAlert from "./libs/SweetAlert";

$(function () {
    var $document = $(document);
    $document.on("click", ".js-approve", function (e) {
        e.preventDefault();
        var $this      = $(this);
        var project_id = $this.attr("rel");
        SweetAlert.alert({
            title: "Approve and release the Schedule?",
            desc: "Once confirmed, the Hub schedule will be released to the Project owner.",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: (is_confirm) => {
                if(is_confirm){
                    approve_schedule(project_id);
                }else{
                    return false;
                }
            }
        });
    });

    function approve_schedule(project_id){
        $.ajax({
            type: "POST",
            url: "/project/approve-schedule",
            data: {
                project_id: project_id
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    }
});
