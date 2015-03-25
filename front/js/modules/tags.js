"use strict";

export function init() {
    $(".expertise-category").each( (key, block) => {
        if ($(block).find("span").length > 0) {
            $(block).find("p").removeClass("hide");
        }
    });
}