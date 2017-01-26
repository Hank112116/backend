"use strict";

export function getCSRFToken() {
    return $("meta[name='csrf-token']").attr("content");
}
