/* jshint quotmark: false */
$(function () {
    let $document = $(document);
    //open dialog
    $document.on("click", ".sendmail", function () {
        let $this = $(this);
        $("#expert1").val("");
        $("#expert2").val("");
        $("#expert1Info").empty();
        $("#expert2Info").empty();
        let projectId = $this.attr("projectId");
        let projectTitle = $this.attr("projectTitle");
        let userId = $this.attr("userId");
        let PM = $this.attr("PM");
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
    $document.on("change", "#expert1", function () {
        let $expert1Info = $("#expert1Info");
        $expert1Info.empty();
        $expert1Info.append('<i class="fa fa-refresh fa-spin"></i>');
        let $this = $(this);
        let expertId = $this.val();
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

    $document.on("change", "#expert2", function () {
        let $expert2Info = $("#expert2Info");
        $expert2Info.empty();
        $expert2Info.append('<i class="fa fa-refresh fa-spin"></i>');
        let $this = $(this);
        let expertId = $this.val();
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
    $document.on("click", "#sendMail", function () {
        let expert1 = $("#expert1").val();
        let expert2 = $("#expert2").val();
        let projectId = $("#projectId").val();
        let projectTitle = $("#projectTitle").val();
        let userId = $("#userId").val();
        let PM = $("#PM").val();

        if (expert1 === expert2) {
            Notifier.showTimedMessage("Duplicate expert.", "warning", 2);
            return;
        }

        if (expert1 && expert2 && PM) {
            $("#email-recommend-expert-dialog").html('<i class="fa fa-refresh fa-spin" style="font-size: 150px;"></i>');
            $.ajax({
                type: "POST",
                url: "/project/staff-recommend-experts",
                data: {
                    expert1: expert1,
                    expert2: expert2,
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
                        $("#email-recommend-expert-dialog").dialog("close");
                        Notifier.showTimedMessage("Send mail successful", "information", 2);
                        let $project_row = $("#row-" + projectId);
                        $project_row.html(feeback.view);
                    },
                    400: function (feeback) {
                        let error_message = feeback.responseJSON.error.message;
                        Notifier.showTimedMessage(error_message, "warning", 2);
                        location.reload();
                    },
                    412: function () {
                        location.href = "/";
                    }
                }
            });
        } else {
            let errorMsg = "";
            if (!PM) {
                errorMsg = errorMsg + "PM is empty! ";
            }
            if (!expert1 || !expert2) {
                errorMsg = errorMsg + "Expert is empty!";
            }
            Notifier.showTimedMessage(errorMsg, "warning", 2);
        }
    });

});