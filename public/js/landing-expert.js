(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";
var expert = require("./libs/Expert.js");
new expert();

},{"./libs/Expert.js":2}],2:[function(require,module,exports){
"use strict";

var Expert = function Expert() {
    this.$root = $("body");
    this.$group = this.$root.find("#sortable");
    var instance = this;
    this.$root.find(".js-search-form").each(function (kee, formBlock) {
        instance._setSearchForm(formBlock);
    });
    this._setSortable(this.$group);
    this._setExpertBlocks();
    this._setSortTable(this.$group);
    this.btnSubmit();
    this.textareaCount(this.$group);
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

                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }

                Notifier.showTimedMessage("Add successful", "information", 2);
                instance.$group.append(feeback.newBlock);
                instance._setExpertBlocks();
                instance._setSortTable(instance.$group);
                instance.textareaCount(instance.$group);
            }
        });
    });
};
Expert.prototype._setExpertBlocks = function () {
    this.$root.find(".js-block").each(function (kee, block) {
        var $block = $(block);
        var removeBtn = $block.find(".js-remove").first();
        removeBtn.unbind("click").click(function () {
            $block.fadeOut("fast", function () {
                $block.remove();
            });
        });
    });
};
Expert.prototype._setSortable = function ($Sortable) {
    $Sortable.sortable({
        stop: function stop() {
            // enable text select on inputs
            $(this).find("textarea").bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function (e) {
                e.stopImmediatePropagation();
            });
        },
        revert: true
    }).disableSelection();
    $("ul, li").disableSelection();
};
Expert.prototype._setSortTable = function ($Sortable) {
    $Sortable.find("textarea").bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function (e) {
        e.stopImmediatePropagation();
    });
};
Expert.prototype.textareaCount = function ($Sortable) {
    $Sortable.find("textarea[maxlength]").keyup(function () {
        var $this = $(this);
        var limit = parseInt($this.attr("maxlength"));
        var text = $this.val();
        var chars = text.length;
        var userId = $this.attr("rel");
        var tag = "count_" + userId.toString();
        $("#" + tag).text(chars + "/" + limit);
    });
};
Expert.prototype.btnSubmit = function () {
    this.$root.find(".btn-submit").click(function () {
        var user = [];
        var description = [];
        $(".panel-body").each(function (index) {
            var $this = $(this);
            user[index] = $this.attr("rel");
            description[index] = $this.find("textarea").val();
        });
        $.ajax({
            type: "POST",
            url: "/landing/update-expert",
            data: {
                user: user,
                description: description
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
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
