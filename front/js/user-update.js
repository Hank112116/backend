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
});

var user_id = $("#user_id").val();
$(".attachment-trash").click(function(){
    var $this = $(this);
    var attachment = $this.attr("attachment");
    SweetAlert.alert({
        title: "Delete attachment?",
        confirmButton: "Yes!",
        handleOnConfirm: () => delete_attachment(attachment, $this)

    });
});

// Variable to store your files
var files;

// Add events
$("#attachment").on("change", function(event){
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
    $(".panel-body-attachment").append("<i class='fa fa-refresh fa-spin'></i>");
    $.ajax({
        url: "/user/create-attachment",
        type: "POST",
        data: data,
        cache: false,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        statusCode: {
            200: function() {
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            },
            400: function() {
                Notifier.showTimedMessage("Update fail", "warning", 2);
                $(".fa-refresh").remove();
            },
            500: function() {
                Notifier.showTimedMessage("Server error", "warning", 2);
                $(".fa-refresh").remove();
            },
            507: function() {
                Notifier.showTimedMessage("More than three files", "warning", 2);
                $(".fa-refresh").remove();
            }
        }
    });
});

function delete_attachment(attachment, $this)
{
    $this.attr("class", "fa fa-refresh fa-spin  attachment-trash");
    $.ajax({
        type: "POST",
        url: "/user/delete-attachment",
        data: {
            user_id:    user_id,
            attachment: attachment
        },
        dataType: "JSON",
        statusCode: {
            200: function() {
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            },
            400: function() {
                Notifier.showTimedMessage("Update fail", "warning", 2);
            },
            500: function() {
                Notifier.showTimedMessage("Server error", "warning", 2);
            }
        }
    })
}
