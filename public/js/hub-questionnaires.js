(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";
require("./libs/RecommendExpert.js");

},{"./libs/RecommendExpert.js":2}],2:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    //open dialog
    $(".sendmail").click(function () {
        var $this = $(this);
        $("#expert1").val("");
        $("#expert2").val("");
        $("#expert1Info").empty();
        $("#expert2Info").empty();
        var projectId = $this.attr("projectId");
        var projectTitle = $this.attr("projectTitle");
        var userId = $this.attr("userId");
        var PM = $this.attr("PM");
        $("#projectId").val(projectId);
        $("#projectTitle").val(projectTitle);
        $("#userId").val(userId);
        $("#PM").val(PM);
        $("#email-recommend-expert-dialog").dialog({
            height: 270,
            width: 600
        });
    });
    //search expert info
    $("#expert1").change(function () {
        var $expert1Info = $("#expert1Info");
        $expert1Info.empty();
        $expert1Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/hub/get-expert",
            data: {
                expertId: expertId
            },
            dataType: "JSON",
            success: function success(feeback) {
                $expert1Info.text(feeback.msg);
            }
        });
    });
    $("#expert2").change(function () {
        var $expert2Info = $("#expert2Info");
        $expert2Info.empty();
        $expert2Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/hub/get-expert",
            data: {
                expertId: expertId
            },
            dataType: "JSON",
            success: function success(feeback) {
                $expert2Info.text(feeback.msg);
            }
        });
    });
    //send mail
    $("#sendMail").click(function () {
        var expert1 = $("#expert1").val();
        var expert2 = $("#expert2").val();
        var projectId = $("#projectId").val();
        var projectTitle = $("#projectTitle").val();
        var userId = $("#userId").val();
        var PM = $("#PM").val();
        if (expert1 && expert2 && PM) {
            $("#email-recommend-expert-dialog").html('<i class="fa fa-refresh fa-spin" style="font-size: 150px;"></i>');
            $.ajax({
                type: "POST",
                url: "/hub_email-send",
                data: {
                    expert1: expert1,
                    expert2: expert2,
                    projectId: projectId,
                    projectTitle: projectTitle,
                    userId: userId,
                    PM: PM
                },
                dataType: "JSON",
                success: function success(feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        location.reload();
                        return;
                    }
                    $("#email-recommend-expert-dialog").dialog("close");
                    Notifier.showTimedMessage("Send mail successful", "information", 2);
                    var $project_row = $("#row-" + projectId);
                    $project_row.html(feeback.view);
                }
            });
        } else {
            var errorMsg = "";
            if (!PM) {
                errorMsg = errorMsg + "PM is empty! ";
            }
            if (!expert1 || !expert2) {
                errorMsg = errorMsg + "Expert is empty!";
            }
            Notifier.showTimedMessage(errorMsg, "warning", 2);
        }
    });
});

},{}]},{},[1]);
