(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

exports.locationSelector = locationSelector;
exports.editor = editor;
Object.defineProperty(exports, "__esModule", {
    value: true
});
"use strict";

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
        buttons: ["bold", "italic", "formatting", "deleted", "outdent", "indent", "|", "horizontalrule", "unorderedlist", "orderedlist", "|", "image", "video", "link"],

        formattingTags: ["h1", "h2", "h3", "h4", "p"],
        minHeight: 500,
        autoresize: false,
        cleanup: true,
        convertImageLinks: true,
        convertVideoLinks: true,
        dragUpload: true,
        imageUpload: "/upload-editor-image",

        imageUploadCallback: function (image, response) {
            if (response.status == "fail") {
                Notifier.showMessage(response.msg, "warning");
                image.remove();
            }
        },

        imageUploadErrorCallback: function () {
            Notifier.showMessage("Some errors happened, try again later", "warning");
        }
    });
}

},{}],2:[function(require,module,exports){
"use strict";

var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { "default": obj }; };

var FormUtility = _interopRequireWildcard(require("./libs/FormUtility"));

$((function () {
    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));
    FormUtility.editor();
    FormUtility.preventEnter();
})());

},{"./libs/FormUtility":1}]},{},[2])