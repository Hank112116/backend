/* jshint quotmark: false */
$(function () {
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
