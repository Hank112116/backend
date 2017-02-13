"use strict";
require("./libs/InternalUserMemo.js");
import * as icheck from "./modules/icheck";
import * as SweetAlert from "./libs/SweetAlert";
$(function () {
    var $document = $(document);
    //change user type to pm checkbox
    $document.on("ifChecked", ".change_pm", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Change User to HWTrek PM?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) => {
                if(is_confirm){
                    post_data(user_id, "/user/change-hwtrek-pm-type", "pm");
                }else{
                    self.iCheck("uncheck");
                }
            }
        });
    });
    //change user type to creator checkbox
    $document.on("ifChecked", ".change_creator", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Change HWTrek PM to Creator?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) =>{
                if(is_confirm){
                    post_data(user_id, "/user/change-hwtrek-pm-type", "creator");
                }else{
                    self.iCheck("uncheck");
                }
            }
        });
    });
    //change user type to expert checkbox
    $document.on("ifChecked", ".change_expert", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Change HWTrek PM to Expert?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) =>{
                if(is_confirm){
                    post_data(user_id, "/user/change-hwtrek-pm-type", "expert");
                }else{
                    self.iCheck("uncheck");
                }
            }
        });
    });
    $document.on("click", ".fa-commenting-o", function() {
        var user_id = $(this).attr("rel");
        $.ajax({
            type: "POST",
            url: "/apply-expert-message/messages",
            data: {
                user_id: user_id,
            },
            statusCode: {
                200: function (feeback) {
                    $("#dialog").html(feeback);
                    $("#dialog").dialog({
                        height: 270,
                        width: 600
                    });
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    function post_data(user_id, url, user_type){
        var route_path = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                user_id: user_id,
                user_type: user_type,
                route_path: route_path
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    var $user_row =  $("#row-" + user_id);
                    $user_row.html(feeback.view);
                    icheck.init();
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    }
});
