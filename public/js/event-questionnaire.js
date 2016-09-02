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

    $(".fa-user-plus").click(function () {
        var guest_data = JSON.parse($(this).attr("rel"));
        $("#guest_attendee_name").text(guest_data.guest_attendee_name);
        $("#guest_job_title").text(guest_data.guest_job_title);
        $("#guest_email").text(guest_data.guest_email);
        $("#guest_phone").text(guest_data.guest_phone);
        $("#guest_info").text(guest_data.guest_info);

        var $dialog = $("#questionnaire_guest_info_dialog");
        $dialog.dialog({
            height: 370,
            width: 600
        });
    });
});

},{"./libs/EventNote.js":2}],2:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    $(".internal-selection").click(function () {
        var $this = $(this);
        var id = $this.attr("rel");
        var status = $this.attr("status");
        var isTour = $this.attr("tour");
        if (isTour) {
            $("#internal_select_tour_status").val(status);
            $("#internal-selection-tour-id").val(id);
            $("#internal_selection_tour_dialog").dialog({
                height: 200,
                width: 500
            });
        } else {
            $("#internal_select_meetup_status").val(status);
            $("#internal-selection-meetup-id").val(id);
            $("#internal_selection_meetup_dialog").dialog({
                height: 200,
                width: 500
            });
        }
    });

    $("#edit_internal_selection_tour").click(function () {
        var id = $("#internal-selection-tour-id").val();
        var status = $("#internal_select_tour_status").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-memo",
            data: {
                id: id,
                internal_selection: status
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal_selection_tour_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    $("#edit_internal_selection_meetup").click(function () {
        var id = $("#internal-selection-meetup-id").val();
        var status = $("#internal_select_meetup_status").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-memo",
            data: {
                id: id,
                internal_selection: status
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal_selection_meetup_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    $(".follow-pm").click(function () {
        var $this = $(this);
        var id = $this.attr("rel");
        var pm = $this.attr("pm");
        $("#follow_pm").val(pm);
        $("#id").val(id);
        $("#follow_pm_dialog").dialog({
            height: 200,
            width: 500
        });
    });

    $("#edit_follow_pm").click(function () {
        var id = $("#id").val();
        var pm = $("#follow_pm").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-memo",
            data: {
                id: id,
                follow_pm: pm
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#follow_pm_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    $(".note").click(function () {
        var $this = $(this);
        var id = $this.attr("rel");
        var note = $this.attr("note");
        $("#note").val(note);
        $("#note_id").val(id);
        $("#note_dialog").dialog({
            height: 270,
            width: 500
        });
    });

    $("#edit_note").click(function () {
        var id = $("#note_id").val();
        var note = $("#note").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-memo",
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

    $(".pm-mark-form-status-selection").click(function () {
        var $this = $(this);
        var id = $this.attr("rel");
        var status = $this.attr("status");
        $("#mark_status").val(status);
        $("#mark_status_event_id").val(id);
        $("#pm_mark_form_status_dialog").dialog({
            height: 200,
            width: 500
        });
    });

    $("#edit_pm_mark_status").click(function () {
        var id = $("#mark_status_event_id").val();
        var status = $("#mark_status").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-memo",
            data: {
                id: id,
                internal_form_selection: status
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#pm_mark_form_status_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });
});

},{}]},{},[1]);
