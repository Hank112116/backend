"use strict";

var isKeyEnter = function(e) {
    var code = e.keyCode || e.which;
    return code == 13;
};

var preventEnter = function() {
    $("form").on("keydown", (e) => {
        if (isKeyEnter(e)) {
            e.preventDefault();
            return false;
        }
    });
};

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
        langs: {
            en: {
                bold: "Bold",
                italic: "Italic",
                formatting: "Formatting",
                header3: "Title",
                header4: "Sub Title",
                paragraph: "Normal text",
                deleted: "Deleted",
                underline: "Underline",
                horizontalrule: "Insert Horizontal Rule",
                unorderedlist: "Unordered List",
                orderedlist: "Ordered List",
                image: "Insert Image",
                video: "Insert Video",
                link: "Link",
                link_insert: "Insert link",
                unlink: "Unlink",
                upload: "Upload",
                drop_file_here: "Drop file here",
                or_choose: "Or choose",
                cancel: "Cancel",
                insert: "Insert",
                video_html_code: "Video Embed Code",
                link_new_tab: "Open link in new tab",
                anchor: "Anchor",
                text: "Text",
                mailto: "Email",
                web: "URL"
            }
        },
        buttons: [
            "bold", "italic", "formatting", "deleted", "underline", "|",
            "horizontalrule", "unorderedlist", "orderedlist", "|",
            "image", "video", "link"
        ],
        formattingTags: ["h3", "h4", "p"],
        deniedTags: ["html", "head", "link", "body", "meta", "script", "style", "applet", "h1", "h2"],
        minHeight: 500,
        placeholder: "Describe the idea behind your hardware design, the journey so far, what\"s done, and what needs to be done.",
        removeEmptyTags: false,
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
