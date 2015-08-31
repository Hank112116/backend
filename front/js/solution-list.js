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
              post_date(solution_id , "/solution/to-program")
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
              post_date(solution_id, "/solution/to-solution")
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
                post_date(solution_id, "/solution/to-program")
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
                post_date(solution_id, "/solution/to-solution")
        });
    });
    //cancel pending solution type to program checkbox
    $(document).on("ifChecked", ".cancel_solution", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Cancel Pending Solution to Program?",
            confirmButton: "Yes, Cancel!",
            handleOnConfirm: () =>
                post_date(solution_id, "/solution/cancel-pending-solution")
        });
    });
    //cancel pending program type to solution checkbox
    $(document).on("ifChecked", ".cancel_program", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Cancel Pending Program to Solution?",
            confirmButton: "Yes, Cancel!",
            handleOnConfirm: () =>
                post_date(solution_id, "/solution/cancel-pending-program")
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
        });
    }

});
