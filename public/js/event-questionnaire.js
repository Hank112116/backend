(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";
$(function () {
    $(".fa-sticky-note-o").click(function () {
        var data = $(this).attr("rel");
        data = JSON.parse(data);
        console.log(data);
        var $dialog = $("#questionnaire-2016-q1");

        $("#phone").text(data.phone);

        if (data.other_member_to_join == "true") {
            $("#join").text("Yes");
        } else {
            $("#join").text("No");
        }

        if (data.wechat_account == "true") {
            $("#wechat").text("Yes");
        } else {
            $("#wechat").text("No");
        }

        if (data.forward_material == "true") {
            $("#material").text("Yes");
        } else {
            $("#material").text("No");
        }

        $dialog.dialog({
            height: 270,
            width: 500
        });
    });
});

},{}]},{},[1]);
