"use strict";
$(function () {
    $(".fa-sticky-note-o").click(function(){
        var data = $(this).attr("rel");
        data = JSON.parse(data);
        console.log(data);
        var $dialog =  $("#questionnaire-2016-q1");

        $("#phone").text(data.phone);

        if (data.other_member_to_join == "1") {
            $("#join").text("Yes");
        } else {
            $("#join").text("No");
        }

        if (data.wechat_account) {
            $("#wechat").text(data.wechat_account);
        } else {
            $("#wechat").text("No");
        }

        if (data.forward_material == "1") {
            $("#material").text("Yes");
        } else {
            $("#material").text("No");
        }

        $dialog.dialog({
            height: 270,
            width: 500
        });
    });
});
