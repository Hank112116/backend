"use strict";
import * as SweetAlert from "./libs/SweetAlert";
$(function () {
    //change solution type to program alert button
    $(".approve_pending_program").click(function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
           title: "Upgrade Solution to Program?",
           confirmButton: "Yes, Approve!",
           handleOnConfirm: () => 
              post_date(solution_id , "./to-program")
        });
        return false;
    });
    $(".approve_pending_solution").click(function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
           title: "Change Program to Solution?",
           confirmButton: "Yes, Approve!",
           handleOnConfirm: () => 
              post_date(solution_id, "./to-solution")
        });
        return false;
    });
    //change solution type to program checkbox
    $(document).on("ifChecked", ".approve_program", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Upgrade Solution to Program?",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () =>
                post_date(solution_id, "./to-program")
        });
    });
    //change solution type to solution checkbox
    $(document).on("ifChecked", ".approve_solution", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Change Program to Solution?",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: () =>
                post_date(solution_id, "./to-solution")
        });
    });
    function post_date(solution_id, url){
        $.ajax({
            type: "POST",
            url: url,
            data: { 
              solution_id: solution_id
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
        })
    }

});
