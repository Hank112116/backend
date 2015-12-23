"use strict";
import * as SweetAlert from "./libs/SweetAlert";
$(function () {
    //change user type to pm checkbox
    $(document).on("ifChecked", ".change_pm", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Change User to HWTrek PM?",
            confirmButton: "Yes!",
            handleOnConfirm: () =>
                post_data(user_id, "/user/change-hwtrek-pm-type", 1)
        });
    });
    //change user type to user checkbox
    $(document).on("ifChecked", ".change_user", function (e) {
        e.preventDefault();
        var user_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Change HWTrek PM to User?",
            confirmButton: "Yes!",
            handleOnConfirm: () =>
                post_data(user_id, "/user/change-hwtrek-pm-type", 0)
        });
    });

    $(".fa-commenting-o").click(function(){
        var user_id = $(this).attr("rel");
        $.ajax({
            type: "POST",
            url: "/apply-expert-message/messages",
            data: {
                user_id: user_id,
            },
            success: function success(feeback) {
                $("#dialog").html(feeback);
                $("#dialog").dialog({
                    height: 270,
                    width: 600
                });
            }
        });
    });

    function post_data(user_id, url, is_hwtrek_pm){
        $.ajax({
            type: "POST",
            url: url,
            data: { 
              user_id:         user_id,
              is_hwtrek_pm:    is_hwtrek_pm
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
