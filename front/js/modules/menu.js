"use strict";

export function init() {
    var width,
        path_name,
        $sidebar_collapse = $(".sidebar-collapse"),
        $page_collapse = $(".page-wrapper");

    $("#side-menu").metisMenu();

    $(window).bind("load resize", function () {
        width = this.window.innerWidth > 0 ? this.window.innerWidth : this.screen.width;
        path_name = window.location.pathname;
        if (path_name == "/") {
            $page_collapse.addClass("page-wrapper--collapse");
            $sidebar_collapse.addClass("collapse");
        }

        if (width < 768) {
            $sidebar_collapse.addClass("collapse");
        } else {
            $sidebar_collapse.removeClass("collapse");
        }
    });

    $(".btn-sidebar-toggle").click(function () {
        if ($page_collapse.hasClass("page-wrapper--collapse")) {
            $page_collapse.removeClass("page-wrapper--collapse");
            $sidebar_collapse.removeClass("collapse");
        } else {
            $page_collapse.addClass("page-wrapper--collapse");
            $sidebar_collapse.addClass("collapse");
        }
    });
}
