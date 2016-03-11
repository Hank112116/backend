(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";
require("./libs/EventNote.js");
$(function () {
    $(".fa-sticky-note-o").click(function () {
        var data = $(this).attr("rel");
        if (data) {
            data = JSON.parse(data);
            $("#phone").text(data.phone);

            $("#wechat").text(data.wechat_account);
        }
        var $dialog = $("#questionnaire-2016-q1");
        $dialog.dialog({
            height: 170,
            width: 500
        });
    });
});

},{"./libs/EventNote.js":2}],2:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    $(".note").click(function () {
        var $this = $(this);
        var id = $this.attr("rel");
        var note = $this.attr("note");
        $("#note").text(note);
        $("#id").val(id);
        $("#note_dialog").dialog({
            height: 270,
            width: 500
        });
    });

    $("#edit_note").click(function () {
        var id = $("#id").val();
        var note = $("#note").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-note",
            data: {
                id: id,
                note: note
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#note_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });
});

},{}]},{},[1]);
