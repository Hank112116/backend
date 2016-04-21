/* jshint quotmark: false */
$(function () {
    var $internal_tag_input = $("#internal-tag");
    $internal_tag_input.tagsinput({
        confirmKeys: [13],
        allowDuplicates: false,
        tagClass: "bootstrap-tagsinput--tag"
    });

    //open dialog
    $( ".internal-tag" ).click(function () {
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

    $("#add-tags").click(function () {
        var project_id = $("#internal_tag_project_id").val();
        var tags       = $internal_tag_input.val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                tags: tags
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $( "#internal-tag-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });

    });

    $( ".grade" ).click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var note = $this.attr("note");
        var grade = $this.attr("grade");
        $("#grade_note").text(note);
        $("#grade").val(grade);
        $("#grade_project_id").val(project_id);
        $("#grade_dialog").dialog({
            height: 350,
            width: 530
        });
    });

    $("#edit_grade").click(function(){
        var project_id = $("#grade_project_id").val();
        var note = $("#grade_note").val();
        var grade = $("#grade").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                schedule_note: note,
                schedule_note_grade: grade
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $( "#grade_dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });


    //open dialog
    $( ".internal-description" ).click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var internal_description = $this.attr("description");
        $("#internal_description").text(internal_description);
        $("#internal_description_project_id").val(project_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $("#edit_internal_description").click(function () {
        var project_id           = $("#internal_description_project_id").val();
        var internal_description = $("#internal_description").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                description: internal_description
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal-description-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    //open dialog
    $( ".schedule-manager" ).click(function () {
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

    $("#update-schedule-manager").click(function () {
        var project_id = $("#schedule_manager_project_id").val();
        var managers   = [];
        $("input[type=checkbox]").each(function () {
            if (this.checked) {
                managers.push($(this).val());
            }
        });

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                project_managers: JSON.stringify(managers)
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#schedule-manager-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    //open dialog
    $( ".project-report-action" ).click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var report_action = $this.attr("action");
        $("#project-report-action").text(report_action);
        $("#project-report-action-project-id").val(project_id);
        $("#project-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $("#edi-project-report-action").click(function () {
        var project_id           = $("#project-report-action-project-id").val();
        var report_action = $("#project-report-action").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                report_action: report_action
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#project-report-action-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });
});
