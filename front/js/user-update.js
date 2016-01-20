"use strict";

import * as FormUtility from "./libs/FormUtility";
import * as icheck from "./modules/icheck";
import * as SweetAlert from "./libs/SweetAlert";

import ProjectUpdater from "./libs/ProjectUpdater";

$(() => {
    icheck.initRadio();

    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));
    check_attachment_amount();
});

var user_id = $("#user_id").val();

var put_items        = [];
var delete_items     = [];

var attachment_items = {
    put_items:    put_items,
    delete_items: delete_items
};

// Variable to store your files
var files;

// Add events
$("#attachment_upload").on("change", function(event){
    var $this = $(this);
    files = event.target.files;
    event.stopPropagation(); // Stop stuff happening
    event.preventDefault();  // Totally stop stuff happening

    // Create a form data object and add the files
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    data.append("user_id", user_id);
    $("#attachment_upload").attr("disabled", "disabled");
    $(".btn-submit").attr("disabled", "disabled");
    $(".panel-body-attachment").append("<i class='fa fa-refresh fa-spin'></i>");
    $.ajax({
        url: "/user/put-attachment",
        type: "POST",
        data: data,
        cache: false,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        statusCode: {
            200: function($data) {
                put_items.push($data);
                //console.log(put_items);
                $("#attachments").val(JSON.stringify(attachment_items));

                $(".panel-body-attachment-list").append(
                    "<div class='photo-preview'>" +
                    "<div style='background-image:url(/images/attachment-previews/attach-cover-1.png);' class='photo-thumb'></div>" +
                    "<div class='file-info'>" +
                        "<span>" + $data.name + "</span><span> (</span><span>" + formatBytes($data.size, 2) + "</span><span>)</span>" +
                        "<i style='cursor:pointer' class='fa fa-trash-o attachment-trash-put' attachment='"+ JSON.stringify($data)  +"'></i>" +
                    "</div>" +
                    "</div>"
                );

                Notifier.showTimedMessage("Upload success", "information", 2);
                $(".fa-refresh").remove();
                $this.val("");
                check_attachment_amount();
                $(".btn-submit").removeAttr("disabled");
            },
            400: function() {
                Notifier.showTimedMessage("Update fail", "warning", 2);
                $(".fa-refresh").remove();
            },
            500: function() {
                Notifier.showTimedMessage("Server error", "warning", 2);
                $(".fa-refresh").remove();
            }
        }
    });
});

$(".panel-body-attachment-list").on("click", ".attachment-trash-put", function() {
    var $this = $(this);
    var attachment = JSON.parse($this.attr("attachment"));
    SweetAlert.alert({
        title: "Delete attachment?",
        confirmButton: "Yes!",
        handleOnConfirm: () => delete_put_attachment(attachment, $this)
    });
});

$(".attachment-trash").click(function(){
    var $this = $(this);
    var attachment = $this.attr("attachment");
    SweetAlert.alert({
        title: "Delete attachment?",
        confirmButton: "Yes!",
        handleOnConfirm: () => delete_attachment(attachment, $this)
    });
});

function delete_put_attachment(attachment, $this)
{
    put_items.map(function(obj, index){
        if (attachment.key == obj.key) {
            put_items.splice(index, 1);
        }
    });
    $this.parent().parent().remove();
    $("#attachments").val(JSON.stringify(attachment_items));
    check_attachment_amount();
}

function delete_attachment(attachment, $this)
{
    delete_items.push(JSON.parse(attachment));
    $this.parent().parent().remove();
    $("#attachments").val(JSON.stringify(attachment_items));
    check_attachment_amount();
}

function check_attachment_amount()
{
    var attachment_count = $(".photo-preview").length;
    console.log(attachment_count);
    if (attachment_count >= 3) {
        $("#attachment_upload").attr("disabled", "disabled");
    } else {
        $("#attachment_upload").removeAttr("disabled");
    }
}

function formatBytes(bytes, decimals)
{
    if (bytes === 0) {
        return "0 Byte";
    }
    var k = 1000;
    var dm = decimals + 1 || 3;
    var sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(dm) + " " + sizes[i];
}
