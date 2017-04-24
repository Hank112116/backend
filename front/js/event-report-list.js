"use strict";
require("./libs/EventNote.js");
import * as SweetAlert from "./libs/SweetAlert";
$(function () {

    var event_id = $("#event_id").val();
    //change user type to user checkbox
    $(document).on("ifChecked", ".approve_event_user", function (e) {
        e.preventDefault();
        var $this   = $(this);
        var user_id = $this.attr("rel");
        SweetAlert.alert({
            title: "Select this user?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) => {
                if(is_confirm){
                    $.ajax({
                        type: "POST",
                        url: "/report/events/approve-user",
                        data: {
                            user_id: user_id,
                            event_id: event_id
                        },
                        dataType: "JSON",
                        statusCode: {
                            200: function () {
                                Notifier.showTimedMessage("Update successful", "information", 2);
                                window.location = "/report/tour-form?event=" + event_id;
                            },
                            400: function (res) {
                                Notifier.showTimedMessage(res.responseJSON.error, "warning", 2);
                            },
                            401: function () {
                                location.href = "/";
                            },
                            403: function () {
                                location.href = "/";
                            },
                            404: function () {
                                Notifier.showTimedMessage('User not found', "warning", 2);
                            },
                            412: function () {
                                location.href = "/";
                            }
                        }
                    });
                }else{
                    $this.iCheck("uncheck");
                }
            }
        });
    });

    var $dialog =  $("#dialog");
    $(".fa-commenting-o")
        .mouseover(function(){
            var message = $(this).attr("rel");

            $dialog.text(message);
            $dialog.dialog({
                height: 270,
                width: 600
            });
        })
        .mouseout(function(){
            $dialog.dialog( "close" );
        });

    $(".established-since")
        .mouseover(function(){
            var message = $(this).attr("rel");

            $dialog.text(message);
            $dialog.dialog({
                height: 270,
                width: 600
            });
        })
        .mouseout(function(){
            $dialog.dialog( "close" );
        });

    $(".fa-user-plus").click(function(){
        var guest_info = JSON.parse($(this).attr("rel"));
        $("#guest_full_name").text(guest_info.full_name);
        $("#guest_job_title").text(guest_info.job_title);
        $("#guest_email").text(guest_info.email);

        var $dialog =  $("#guest_info_dialog");
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
