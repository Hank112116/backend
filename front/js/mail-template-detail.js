"use strict";

$(() => {
    var $block = $(".js-message"),
        msg = $block.html(),
        reg = /({[\w_]*})/gi;
        
    $block.html(msg.replace(reg, "<i class='msg-variable'>$1</i>"));
});
