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
                post_date(user_id, "./change-hwtrek-pm-type", 1)
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
                post_date(user_id, "./change-hwtrek-pm-type", 0)
        });
    });

    function post_date(user_id, url, is_hwtrek_pm){
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
