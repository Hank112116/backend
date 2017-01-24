"use strict";

import * as menu from "./modules/menu";
import * as icheck from "./modules/icheck";
import * as Notifier from "./libs/Notifier";

window.Notifier = Notifier;

$(function () {
    menu.init();
    icheck.init();

    Notifier.showTimedMessage(
        $("meta[name=noty-msg]").attr("content"),
        $("meta[name=noty-type]").attr("content"), 5
    );

    $.ajaxSetup({
        headers:
            { "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content") }
    });
});
