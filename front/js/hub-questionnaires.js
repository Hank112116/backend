/* jshint quotmark: false */
"use strict";
$(function () {
    //open dialog
    $( ".sendmail" ).click(function () {
        var $this = $(this);
        $("#expert1").val("");
        $("#expert2").val("");
        $("#expert1Info").html("");
        $("#expert2Info").html("");
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
        $("#expert1Info").html("");
        $("#expert1Info").append('<img id="theImg" width="25px" src="../images/loading_small.gif" />');
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
                $("#expert1Info").text(feeback.msg);
            }
        });
    });
    $("#expert2").change(function () {
        $("#expert2Info").html("");
        $("#expert2Info").append('<img id="theImg" width="25px" src="../images/loading_small.gif" />');
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
                $("#expert2Info").text(feeback.msg);
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
        if(expert1 &&  expert2){
            $("#dialog").html('<img id="theImg" width="150px" src="../images/loading.gif" />');
            $.ajax({
                type: "POST",
                url: "../hub_email-send",
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
                        return;
                    }
                    $( "#dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Send mail successful", "information", 2);
                    location.reload();
                }
            });
        }else{
            window.alert("Please enter expert id.");
        }
    });

});