"use strict";
import * as SweetAlert from "./libs/SweetAlert";
$(function () {
    var $document = $(document);
    //change solution type to program alert button
    $(".approve_pending_program").click(function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
           title: "Upgrade Solution to Program?",
           confirmButton: "Yes, Approve!",
           handleOnConfirm: (is_confirm) => {
               if(is_confirm){
                   post_data(solution_id , "/solution/to-program");
               }else{
                   return false;
               }
           }
        });
    });
    $(".approve_pending_solution").click(function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        SweetAlert.alert({
           title: "Change Program to Solution?",
           confirmButton: "Yes, Approve!",
           handleOnConfirm: (is_confirm) => {
               if(is_confirm){
                   post_data(solution_id, "/solution/to-solution");
               }else{
                   return false;
               }
           }
        });
    });
    //change solution type to program checkbox
    $document.on("ifChecked", ".approve_program", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Upgrade Solution to Program?",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: (is_confirm) => {
                if (is_confirm) {
                    self.iCheck("uncheck");
                    post_data(solution_id, "/solution/to-program");
                }else{
                    self.iCheck("uncheck");
                }
            }
        });
    });
    //change solution type to solution checkbox
    $document.on("ifChecked", ".approve_solution", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Change Program to Solution?",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: (is_confirm) => {
                if (is_confirm) {
                    self.iCheck("uncheck");
                    post_data(solution_id, "/solution/to-solution");
                }else{
                    self.iCheck("uncheck");
                }
            }
        });
    });
    //cancel pending solution type to program checkbox
    $document.on("ifChecked", ".cancel_solution", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Cancel Pending Solution to Program?",
            confirmButton: "Yes, Cancel!",
            handleOnConfirm: (is_confirm) => {
                if (is_confirm) {
                    self.iCheck("uncheck");
                    post_data(solution_id, "/solution/cancel-pending-solution");
                } else {
                    self.iCheck("uncheck");
                }
            }
        });
    });
    //cancel pending program type to solution checkbox
    $document.on("ifChecked", ".cancel_program", function (e) {
        e.preventDefault();
        var solution_id = $(this).attr("rel");
        var self = $(this);
        SweetAlert.alert({
            title: "Cancel Pending Program to Solution?",
            confirmButton: "Yes, Cancel!",
            handleOnConfirm: (is_confirm) => {
                if (is_confirm) {
                    self.iCheck("uncheck");
                    post_data(solution_id, "/solution/cancel-pending-program");
                } else {
                    self.iCheck("uncheck");
                }
            }
        });
    });
    function post_data(solution_id, url){
        $.ajax({
            type: "POST",
            url: url,
            data: { 
              solution_id: solution_id
            },
            dataType: "JSON",
            statusCode: {
                200: function() {
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    location.reload();
                },
                400: function() {
                    Notifier.showTimedMessage("Change type fail!", "warning", 2);
                },
                403: function() {
                    Notifier.showTimedMessage("Permissions denied!", "warning", 2);
                },
                404: function() {
                    Notifier.showTimedMessage("Solution not found!", "warning", 2);
                },
                500: function() {
                    Notifier.showTimedMessage("Change type fail!", "warning", 2);
                }
            }
        });
    }

});
