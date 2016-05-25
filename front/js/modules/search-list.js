"use strict";

export function init() {
    $(".js-btn-search").click(function () {
        var $this = $(this);
        $this.html("<i class='fa fa-refresh fa-spin'></i> Searching!");
        $this.closest("form").submit();
    });

    $(".search-bar :input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".js-btn-search").html("<i class='fa fa-refresh fa-spin'></i> Searching!");
            $("form[name='search-form']").submit();
        }
    });
}
