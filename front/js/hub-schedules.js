"use strict";

import * as SweetAlert from "./libs/SweetAlert";

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var link = this.href;

        SweetAlert.alert({
           title: "Approve?",
           desc: "It'll take a bit long time to approve",
           confirmButton: "Yes, Approve!",
           handleOnConfirm: () => window.location = link

        });

        // if(confirm('Approve?')) {
        //     window.location = link;
        // }

        return false;
    });

    $( ".note" ).click(function () {
      $("#note").text();
      $("#note_project_id").val();
      var $this = $(this);
      var projectId = $this.attr("rel");
      var note = $this.attr("note");
      var level = $this.attr("level");
      $("#note").text(note);
      $("#level").val(level);
      $("#note_project_id").val(projectId);
      $( "#dialog" ).dialog({
        height: 270,
        width: 500
      });
    });

    $("#edit_note").click(function(){
      var projectId = $("#note_project_id").val();
      var note = $("#note").val();
      var level = $("#level").val();
      $.ajax({
          type: "POST",
          url: "./update-project-note",
          data: { 
              projectId: projectId,
              note:  note,
              level: level
          },
          dataType: "JSON",
          success: function success(feeback) {
              if (feeback.status === "fail") {
                  Notifier.showTimedMessage(feeback.msg, "warning", 2);
                  return;
              }
              $( "#dialog" ).dialog( "close" );
              Notifier.showTimedMessage("Update successful", "information", 2);
              location.reload();
          }
      });
    });

});
