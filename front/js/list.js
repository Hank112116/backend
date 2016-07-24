"use strict";

import Moment from "./libs/Moment";

import * as datePicker from "./modules/date-picker";
import * as searchList from "./modules/search-list";

window.Moment = Moment;

$(() => {
    datePicker.init();
    searchList.init();

    $("[data-ago]").each( (index, block) => {
        var $self = $(block);
        $self.html(Moment.ago($self.data("ago")));
    });

    $(".float-thead").floatThead({
        position: "fixed",
        zIndex: 100
    });
});
