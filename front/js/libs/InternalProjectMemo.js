/* jshint quotmark: false */
$(function () {
    var $document = $(document);
    var $internal_tag_input = $("#internal-tag");
    $internal_tag_input.tagsinput({
        confirmKeys: [13],
        allowDuplicates: false,
        tagClass: "bootstrap-tagsinput--tag"
    });
    
    //open dialog
    $document.on("click", ".internal-tag", function () {
        var $this = $(this);
        var tech_tag     = $this.attr("tech-tags");
        var internal_tag = $this.attr("tags");
        var project_id   = $this.attr("rel");
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
        var project_id = $("#internal_tag_project_id").val();
        var tags       = $internal_tag_input.val();
        var route_path = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                tags: tags,
                route_path: route_path
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
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });

    });

    $document.on("click", ".grade", function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var note = $this.attr("note");
        var grade = $this.attr("grade");
        $("#grade_note").val(note);
        $("#grade").val(grade);
        $("#grade_project_id").val(project_id);
        $("#grade_dialog").dialog({
            height: 350,
            width: 530
        });
    });

    $document.on("click", "#edit_grade", function(){
        var project_id  = $("#grade_project_id").val();
        var note        = $("#grade_note").val();
        var grade       = $("#grade").val();
        var route_path  = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                schedule_note: note,
                schedule_note_grade: grade,
                route_path: route_path
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
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });


    //open dialog
    $document.on("click", ".internal-description", function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var internal_description = $this.attr("description");
        $("#internal_description").val(internal_description);
        $("#internal_description_project_id").val(project_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit_internal_description", function () {
        var project_id           = $("#internal_description_project_id").val();
        var internal_description = $("#internal_description").val();
        var route_path           = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                description: internal_description,
                route_path: route_path
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
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".schedule-manager", function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var pm         = $this.attr("pm");
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
        var project_id = $("#schedule_manager_project_id").val();
        var managers   = [];
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
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    $("#schedule-manager-dialog" ).dialog( "close" );
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });

    //open dialog
    $document.on("click", ".project-report-action", function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var report_action = $this.attr("action");
        $("#project-report-action").val(report_action);
        $("#project-report-action-project-id").val(project_id);
        $("#project-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edi-project-report-action", function () {
        var project_id    = $("#project-report-action-project-id").val();
        var report_action = $("#project-report-action").val();
        var route_path    = $("#route-path").val();
        var dstart        = $("#statistic-start-date").val();
        var dend          = $("#statistic-end-date").val();
        var time_type     = $("#time_type").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                report_action: report_action,
                route_path: route_path,
                dstart: dstart,
                dend: dend,
                time_type: time_type
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
                    var $project_row =  $("#row-" + project_id);
                    $project_row.html(feeback.view);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });
    });
});
