"use strict";
require("./libs/EventNote.js");
$(function () {
    $(".fa-sticky-note-o").click(function(){
        var data = $(this).attr("rel");
        if(data) {
            data = JSON.parse(data);
            $("#phone").text(data.phone);

            $("#wechat").text(data.wechat_account);
        }
        var $dialog =  $("#questionnaire-2016-q1");
        $dialog.dialog({
            height: 170,
            width: 500
        });
    });

    $(".fa-user-plus").click(function(){
        var guest_data = JSON.parse($(this).attr("rel"));
        $("#guest_attendee_name").text(guest_data.guest_attendee_name);
        $("#guest_job_title").text(guest_data.guest_job_title);
        $("#guest_email").text(guest_data.guest_email);
        $("#guest_phone").text(guest_data.guest_phone);

        var $dialog =  $("#questionnaire_guest_info_dialog");
        $dialog.dialog({
            height: 370,
            width: 600
        });
    });

    $(".fa-bed").click(function(){
        var hotel_name = $(this).attr("rel");
        $("#hotel_dialog").text(hotel_name);

        var $dialog =  $("#hotel_dialog");
        $dialog.dialog({
            height: 100,
            width: 500
        });
    });
});
