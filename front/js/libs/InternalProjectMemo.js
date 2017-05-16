/* jshint quotmark: false */
$(function () {
    let $document = $(document);
    let $internal_tag_input = $("#internal-tag");
    $internal_tag_input.tagsinput({
        confirmKeys: [13],
        allowDuplicates: false,
        tagClass: "bootstrap-tagsinput--tag"
    });
    
    //open dialog
    $document.on("click", ".internal-tag", function () {
        let $this = $(this);
        let tech_tag     = $this.attr("tech-tags");
        let internal_tag = $this.attr("tags");
        let project_id   = $this.attr("rel");
        $internal_tag_input.tagsinput('removeAll');
        $internal_tag_input.tagsinput('add', internal_tag);
        $("#internal_tag_project_id").val(project_id);
        $("#tech-tag").text(tech_tag);
        $("#internal-tag-dialog").dialog({
            height: 350,
            width: 1000
        });
    });

    $document.on("click", "#add-tags", function () {
        let project_id = $("#internal_tag_project_id").val();
        let tags       = $internal_tag_input.val();

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                tags: tags
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $( "#internal-tag-dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    let $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                400: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                404: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });

    });

    $document.on("click", ".grade", function () {
        let $this = $(this);
        let project_id = $this.attr("rel");
        let note = $this.attr("note");
        let grade = $this.attr("grade");
        $("#grade_note").val(note);
        $("#grade").val(grade);
        $("#grade_project_id").val(project_id);
        $("#grade_dialog").dialog({
            height: 350,
            width: 530
        });
    });

    $document.on("click", "#edit_grade", function(){
        let project_id  = $("#grade_project_id").val();
        let note        = $("#grade_note").val();
        let grade       = $("#grade").val();

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                schedule_note: note,
                schedule_note_grade: grade
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $( "#grade_dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    let $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                400: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                404: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });


    //open dialog
    $document.on("click", ".internal-description", function () {
        let $this = $(this);
        let project_id = $this.attr("rel");
        let internal_description = $this.attr("description");
        $("#internal_description").val(internal_description);
        $("#internal_description_project_id").val(project_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit_internal_description", function () {
        let project_id           = $("#internal_description_project_id").val();
        let internal_description = $("#internal_description").val();

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                description: internal_description
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#internal-description-dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    let $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                400: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                404: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".schedule-manager", function () {
        let $this = $(this);
        let project_id = $this.attr("rel");
        let pm         = $this.attr("pm");
        $("input[name='managers[]']").iCheck("uncheck");
        if (pm) {
            pm = JSON.parse(pm);
            pm.forEach(function(hwtrek_member) {
                $("#hwtrek_member_" + hwtrek_member).iCheck('check');
            });
        }
        $("#schedule_manager_project_id").val(project_id);
        $("#schedule-manager-dialog").dialog({
            height: 270,
            width: 600
        });
    });

    $document.on("click", "#update-schedule-manager", function () {
        let project_id = $("#schedule_manager_project_id").val();
        let managers   = [];
        $("input[type=checkbox]").each(function () {
            if (this.checked) {
                managers.push(Number($(this).val()));
            }
        });
        
        $.ajax({
            type: "POST",
            url: "/project/update-project-manager",
            data: {
                project_id: project_id,
                project_managers: JSON.stringify(managers)
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    $("#schedule-manager-dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    let $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                400: function (feeback) {
                    let msg = feeback.responseJSON.error[0].message;

                    Notifier.showTimedMessage(msg, "warning", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".project-report-action", function () {
        let $this = $(this);
        let project_id = $this.attr("rel");
        let report_action = $this.attr("action");
        $("#project-report-action").val(report_action);
        $("#project-report-action-project-id").val(project_id);
        $("#project-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edi-project-report-action", function () {
        let project_id    = $("#project-report-action-project-id").val();
        let report_action = $("#project-report-action").val();

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                report_action: report_action
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#project-report-action-dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    let $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                400: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                404: function (feeback) {
                    let error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });
});
