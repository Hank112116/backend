/* jshint quotmark: false */
$(function () {
    var $document = $(document);
    //open dialog
    $document.on("click", ".sendmail", function() {
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
        $("#email-recommend-expert-dialog").dialog({
            height: 270,
            width: 600
        });
    });
    //search expert info
    $document.on("change", "#expert1", function() {
        var $expert1Info = $("#expert1Info");
        $expert1Info.empty();
        $expert1Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/project/get-expert",
            data: { 
                expertId: expertId
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    $expert1Info.text(feeback.msg);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    $document.on("change", "#expert2", function() {
        var $expert2Info = $("#expert2Info");
        $expert2Info.empty();
        $expert2Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/project/get-expert",
            data: { 
                expertId: expertId
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    $expert2Info.text(feeback.msg);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });
    //send mail
    $document.on("click", "#sendMail", function() {
        var expert1 = $("#expert1").val();
        var expert2 = $("#expert2").val();
        var projectId = $("#projectId").val();
        var projectTitle = $("#projectTitle").val();
        var userId = $("#userId").val();
        var PM = $("#PM").val();
        if(expert1 && expert2 && PM){
            $("#email-recommend-expert-dialog").html('<i class="fa fa-refresh fa-spin" style="font-size: 150px;"></i>');
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
                statusCode: {
                    200: function (feeback) {
                        if (feeback.status === "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            location.reload();
                            return;
                        }
                        $( "#email-recommend-expert-dialog" ).dialog( "close" );
                        Notifier.showTimedMessage("Send mail successful", "information", 2);
                        var $project_row =  $("#row-" + projectId);
                        $project_row.html(feeback.view);
                    },
                    412: function () {
                        location.href = "/";
                    }
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