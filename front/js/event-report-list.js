"use strict";
import * as SweetAlert from "./libs/SweetAlert";
$(function () {

    //change user type to user checkbox
    $(document).on("ifChecked", ".approve_event_user", function (e) {
        e.preventDefault();
        var id = $(this).attr("rel");
        SweetAlert.alert({
            title: "Select this user?",
            confirmButton: "Yes!",
            handleOnConfirm: () =>
                $.ajax({
                    type: "POST",
                    url: "/report/events/approve-user",
                    data: {
                        id: id
                    },
                    dataType: "JSON",
                    success: function success(feeback) {
                        if (feeback.status === "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            return;
                        }
                        Notifier.showTimedMessage("Update successful", "information", 2);
                        location.reload();
                    }
                })
        });
    });

    $(".fa-commenting-o").click(function(){
        var message = $(this).attr("rel");
        var $dialog =  $("#dialog");
        $dialog.text(message);
        $dialog.dialog({
            height: 270,
            width: 600
        });
    });

    $( ".note" ).click(function () {
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

    $("#edit_note").click(function(){
        var id = $("#id").val();
        var note = $("#note").val();
        $.ajax({
            type: "POST",
            url: "/report/events/update-note",
            data: {
                id: id,
                note:  note
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $( "#note_dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });
});
