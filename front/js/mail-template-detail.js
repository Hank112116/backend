"use strict";

$(function () {
    var $block = $(".js-message");
    var msg = $block.html();
    var reg = /({[\w_]*})/gi;
    $block.html(msg.replace(reg, "<i class='msg-variable'>$1</i>"));
});
