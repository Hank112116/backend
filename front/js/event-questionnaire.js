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
});
