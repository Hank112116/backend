"use strict";

var isKeyEnter = function(e) {
    var code = e.keyCode || e.which;
    return code == 13;
}

var preventEnter = function() {
    $("form").on("keydown", (e) => {
        if (isKeyEnter(e)) {
            e.preventDefault();
            return false;
        }
    });
}

export var isKeyEnter;
export var preventEnter;

export function locationSelector($column) {
    if (!google) {
        console.log("No google variable, maybe network breakdown");
        return;
    }

    new google.maps.places.Autocomplete($column[0], {types: ["geocode"]});

    $column
        .focus(() => preventEnter() )
        .blur( () => $("form").unbind("keydown"));
}

export function editor() {
    $(".js-editor").redactor({
        buttons: [
            "bold", "italic", "formatting", "deleted", "outdent", "indent", "|",
            "horizontalrule", "unorderedlist", "orderedlist", "|",
            "image", "video", "link"
        ],

        formattingTags: ["h1", "h2", "h3", "h4", "p"],
        minHeight: 500,
        autoresize: false,
        cleanup: true,
        convertImageLinks: true,
        convertVideoLinks: true,
        dragUpload: true,
        imageUpload: "/upload-editor-image",

        imageUploadCallback: (image, response) => {
            if (response.status == "fail") {
                Notifier.showMessage(response.msg, "warning");
                image.remove();
            }
        },

        imageUploadErrorCallback: () => {
            Notifier.showMessage("Some errors happened, try again later", "warning");
        }
    });
}