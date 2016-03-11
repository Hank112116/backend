"use strict";
require("./libs/EventNote.js");
import * as SweetAlert from "./libs/SweetAlert";
$(function () {

    var event_id = $("#event_id").val();
    //change user type to user checkbox
    $(document).on("ifChecked", ".approve_event_user", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Select this user?",
            confirmButton: "Yes!",
            handleOnConfirm: () =>
                $.ajax({
                    type: "POST",
                    url: "/report/events/approve-user",
                    data: {
                        user_id: user_id
                    },
                    dataType: "JSON",
                    success: function success(feeback) {
                        if (feeback.status === "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            return;
                        }
                        Notifier.showTimedMessage("Update successful", "information", 2);
                        window.location = "/report/questionnaires?event=" + event_id;
                    }
                })
        });
    });

    $(".fa-commenting-o").click(function(){
        var message = $(this).attr("rel");
        var $dialog =  $("#dialog");
        $dialog.text(message);
        $dialog.dialog({
            height: 270,
            width: 600
        });
    });

    $(".fa-clipboard").click(function(){
        var $this = $(this);
        var questionnaire_id = $this.attr("rel");
        $.ajax({
            type: "POST",
            url: "/report/events/user-questionnaire",
            data: {
                questionnaire_id: questionnaire_id
            },
            success: function success(feeback) {
                var $questionnaire_dialog = $("#questionnaire_dialog");
                $questionnaire_dialog.html(feeback);
                $questionnaire_dialog.dialog({
                    height: 670,
                    width: 600
                });
            }
        });
    });
});
