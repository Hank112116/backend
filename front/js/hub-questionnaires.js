/* jshint quotmark: false */
"use strict";
$(function () {
    //open dialog
    $( ".sendmail" ).click(function () {
        var $this = $(this);
        $("#expert1").val("") ;
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
        $("#dialog").dialog({
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
            url: "./get-expert",
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
            url: "./get-expert",
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
    $("#sendMail").click(function(){
        var expert1 = $("#expert1").val();
        var expert2 = $("#expert2").val();
        var projectId = $("#projectId").val();
        var projectTitle = $("#projectTitle").val();
        var userId = $("#userId").val();
        var PM = $("#PM").val();
        if(expert1 && expert2 && PM){
            $("#dialog").html('<i class="fa fa-refresh fa-spin" style="font-size: 150px;"></i>');
            $.ajax({
                type: "POST",
                url: "/hub_email-send",
                data: { 
                    expert1:  expert1,
                    expert2:  expert2,
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
                    $( "#dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Send mail successful", "information", 2);
                    location.reload();
                }
            });
        }else{
            var errorMsg = "";
            if(!PM){
                errorMsg = errorMsg + "PM is empty! ";
            }
            if(!expert1 || !expert2){
                errorMsg = errorMsg + "Expert is empty!";
            }
            Notifier.showTimedMessage(errorMsg, "warning", 2);
        }
    });

});