(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.getCSRFToken = getCSRFToken;

function getCSRFToken() {
    return $("meta[name='csrf-token']").attr("content");
}

},{}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.locationSelector = locationSelector;
exports.editor = editor;
var CommonHelper = require("./CommonHelper");

var isKeyEnter = function isKeyEnter(e) {
    var code = e.keyCode || e.which;
    return code == 13;
};

var preventEnter = function preventEnter() {
    $("form").on("keydown", function (e) {
        if (isKeyEnter(e)) {
            e.preventDefault();
            return false;
        }
    });
};

var isKeyEnter;
exports.isKeyEnter = isKeyEnter;
var preventEnter;

exports.preventEnter = preventEnter;

function locationSelector($column) {
    if (!google) {
        console.log("No google variable, maybe network breakdown");
        return;
    }

    new google.maps.places.Autocomplete($column[0], { types: ["geocode"] });

    $column.focus(function () {
        return preventEnter();
    }).blur(function () {
        return $("form").unbind("keydown");
    });
}

function editor() {
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
        buttons: ["bold", "italic", "formatting", "deleted", "underline", "|", "horizontalrule", "unorderedlist", "orderedlist", "|", "image", "video", "link"],
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
        uploadFields: { _token: CommonHelper.getCSRFToken() },
        imageUploadCallback: function imageUploadCallback(image, response) {
            if (response.status == "fail") {
                Notifier.showMessage(response.msg, "warning");
                image.remove();
            }
        },

        imageUploadErrorCallback: function imageUploadErrorCallback() {
            Notifier.showMessage("Some errors happened, try again later", "warning");
        }
    });
}

},{"./CommonHelper":1}],3:[function(require,module,exports){
"use strict";

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } }

var _libsFormUtility = require("./libs/FormUtility");

var FormUtility = _interopRequireWildcard(_libsFormUtility);

$(function () {
    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));
    FormUtility.editor();
    FormUtility.preventEnter();
});

},{"./libs/FormUtility":2}]},{},[3]);
