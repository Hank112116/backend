(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } }

var _modulesIcheck = require("../modules/icheck");

var icheck = _interopRequireWildcard(_modulesIcheck);

$(function () {
    var $document = $(document);
    var $internal_tag_input = $("#internal-tag");
    $internal_tag_input.tagsinput({
        confirmKeys: [13],
        allowDuplicates: false,
        tagClass: "bootstrap-tagsinput--tag"
    });

    //open dialog
    $document.on("click", ".internal-tag", function () {
        var $this = $(this);
        var expertise_tags = $this.attr("expertise-tags");
        var internal_tag = $this.attr("tags");
        var user_id = $this.attr("rel");
        $internal_tag_input.tagsinput('removeAll');
        $internal_tag_input.tagsinput('add', internal_tag);
        $("#internal_tag_user_id").val(user_id);
        $("#expertise-tags").text(expertise_tags);
        $("#internal-tag-dialog").dialog({
            height: 350,
            width: 1000
        });
    });

    $document.on("click", "#add-tags", function () {
        var user_id = $("#internal_tag_user_id").val();
        var tags = $internal_tag_input.val();
        var route_path = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                tags: tags,
                route_path: route_path
            },
            dataType: "JSON",
            statusCode: {
                200: function _(feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#internal-tag-dialog").dialog("close");
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    var $user_row = $("#row-" + user_id);
                    $user_row.html(feeback.view);
                    icheck.init();
                },
                412: function _() {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".internal-description", function () {
        var $this = $(this);
        var user_id = $this.attr("rel");
        var internal_description = $this.attr("description");
        $("#internal_description").val(internal_description);
        $("#internal_description_user_id").val(user_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit_internal_description", function () {
        var user_id = $("#internal_description_user_id").val();
        var internal_description = $("#internal_description").val();
        var route_path = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                description: internal_description,
                route_path: route_path
            },
            dataType: "JSON",
            statusCode: {
                200: function _(feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#internal-description-dialog").dialog("close");
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    if (route_path === "report/member-matching") {
                        location.reload();
                    }
                    var $user_row = $("#row-" + user_id);
                    $user_row.html(feeback.view);
                    icheck.init();
                },
                412: function _() {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".user-report-action", function () {
        var $this = $(this);
        var user_id = $this.attr("rel");
        var report_action = $this.attr("action");
        $("#user-report-action").val(report_action);
        $("#user-report-action-user-id").val(user_id);
        $("#user-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit-user-report-action", function () {
        var user_id = $("#user-report-action-user-id").val();
        var report_action = $("#user-report-action").val();
        var route_path = $("#route-path").val();
        var time_type = $("#time_type").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                report_action: report_action,
                route_path: route_path,
                time_type: time_type
            },
            dataType: "JSON",
            statusCode: {
                200: function _(feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#user-report-action-dialog").dialog("close");
                    if (route_path === "report/member-matching") {
                        location.reload();
                    }
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    var $user_row = $("#row-" + user_id);
                    $user_row.html(feeback.view);
                    icheck.init();
                },
                412: function _() {
                    location.href = "/";
                }
            }
        });
    });
});

},{"../modules/icheck":3}],2:[function(require,module,exports){
"use strict";
require("./libs/InternalUserMemo.js");

$(function () {
    var $document = $(document);

    $document.on("click", ".matching-data", function () {
        var $this = $(this);
        var $dialog = $("#matching-dialog");
        var dstart = $("input[name=dstart]").val();
        var dend = $("input[name=dend]").val();
        var user_id = $this.attr("rel");

        $dialog.html("");

        $.ajax({
            type: "POST",
            url: "/report/matching-data",
            data: {
                user_id: user_id,
                dstart: dstart,
                dend: dend
            },
            statusCode: {
                200: function _($res) {
                    $dialog.html($res);
                },
                412: function _() {
                    location.href = "/";
                }
            }
        });

        $dialog.dialog({
            title: "Matching data (User #" + user_id + ")",
            height: 600,
            width: 1100
        });
    });
});

},{"./libs/InternalUserMemo.js":1}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.init = init;
exports.initRadio = initRadio;

function init() {
    $("input[type=checkbox]").iCheck({
        checkboxClass: "icheckbox_minimal-blue icheckbox"
    });
}

function initRadio() {
    $("input[type=radio]").iCheck({
        radioClass: "iradio_minimal-blue iradio"
    });
}

},{}]},{},[2]);
