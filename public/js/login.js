(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

$(function () {
    var $oauth_form = $("#oauth-form");
    var $login_form = $("#login-form");

    var $oauth_login_form_link = $("#oauth-login-form-link");
    var $login_form_link = $("#login-form-link");

    $oauth_form.hide();

    $login_form_link.click(function (e) {
        $login_form.delay(100).fadeIn(100);
        $oauth_form.fadeOut(100);
        $oauth_login_form_link.removeClass("active");
        $(this).addClass("active");
        e.preventDefault();
    });
    $oauth_login_form_link.click(function (e) {
        $oauth_form.delay(100).fadeIn(100);
        $login_form.fadeOut(100);
        $login_form_link.removeClass("active");
        $(this).addClass("active");
        e.preventDefault();
    });
});

},{}]},{},[1]);
