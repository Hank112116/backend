(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

var expert = require("./libs/Expert.js");

new expert();

},{"./libs/Expert.js":2}],2:[function(require,module,exports){
"use strict";

var Expert = function Expert() {
    this.$root = $("body");
    this.$group = this.$root.find("#sortablettt").first();
    var instance = this;
    this.$root.find(".js-search-form").each(function (kee, form_block) {
        instance._setSearchForm(form_block);
    });
    this._setSortablettt();
    this._setExpertBlocks();
    this._setSortTable();
    this.btnSubmit();
    this.textareaCount();
};
Expert.prototype._setSearchForm = function (block) {
    var instance = this,
        $block = $(block),
        $btn = $block.find("button");

    $btn.click(function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: block.action,
            data: { id: $block.find(".search_id")[0].value },
            dataType: "JSON",
            success: function success(feeback) {
                $(block).find(".search_id")[0].value = "";

                if (feeback.status == "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }

                Notifier.showTimedMessage("Add successful", "information", 2);
                instance.$group.append(feeback.new_block);
                instance._setExpertBlocks();
                instance._setSortTable();
                instance.textareaCount();
            }
        });
    });
};
Expert.prototype._setExpertBlocks = function (block) {
    var instance = this;
    this.$root.find(".js-block").each(function (kee, block) {
        var $block = $(block);
        var remove_btn = $block.find(".js-remove").first();
        var remove_btn = $block.find(".js-remove");
        remove_btn.unbind("click").click(function () {
            $block.fadeOut("fast", function () {
                $block.remove();
            });
        });
    });
};
Expert.prototype._setSortablettt = function () {
    $("#sortablettt").sortable({
        stop: function stop() {
            // enable text select on inputs
            $("#sortablettt").find("textarea").bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function (e) {
                e.stopImmediatePropagation();
            });
        },
        revert: true
    }).disableSelection();
    $("ul, li").disableSelection();
};
Expert.prototype._setSortTable = function () {
    $("#sortablettt").find("textarea").bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function (e) {
        e.stopImmediatePropagation();
    });
};
Expert.prototype.textareaCount = function () {
    $("textarea[maxlength]").keyup(function () {
        var limit = parseInt($(this).attr("maxlength"));
        var text = $(this).val();
        var chars = text.length;
        var userId = $(this).attr("rel");
        var tag = "count_" + userId.toString();
        console.log(tag);
        $("#" + tag).html(chars + "/" + limit);
    });
};
Expert.prototype.btnSubmit = function () {
    var instance = this;
    this.$root.find(".btn-submit").click(function () {
        var user = [];
        var description = [];
        $(".panel-body").each(function (index) {
            user[index] = $(this).attr("rel");
            description[index] = $(this).find("textarea").val();
        });
        $.ajax({
            type: "POST",
            url: "./update-expert",
            data: {
                user: user,
                description: description
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status == "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                Notifier.showTimedMessage("Update successful", "information", 2);
            }
        });
    });
};
module.exports = Expert;

},{}]},{},[1]);
