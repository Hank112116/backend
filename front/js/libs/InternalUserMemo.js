/* jshint quotmark: false */
import * as icheck from "../modules/icheck";
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
        var expertise_tags  = $this.attr("expertise-tags");
        var internal_tag    = $this.attr("tags");
        var user_id         = $this.attr("rel");
        $internal_tag_input.tagsinput('removeAll');
        $internal_tag_input.tagsinput('add', internal_tag);
        $("#internal_tag_user_id").val(user_id);
        $("#expertise-tags").text(expertise_tags);
        $("#internal-tag-dialog").dialog({
            height: 350,
            width: 1000
        });
    });

    $document.on("click", "#add-tags", function () {
        var user_id = $("#internal_tag_user_id").val();
        var tags       = $internal_tag_input.val();
        var route_path = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                tags: tags,
                route_path: route_path
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $( "#internal-tag-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                var $user_row =  $("#row-" + user_id);
                $user_row.html(feeback.view);
                icheck.init();
            }
        });

    });



    //open dialog
    $document.on("click", ".internal-description", function () {
        var $this = $(this);
        var user_id = $this.attr("rel");
        var internal_description = $this.attr("description");
        $("#internal_description").val(internal_description);
        $("#internal_description_user_id").val(user_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit_internal_description", function () {
        var user_id              = $("#internal_description_user_id").val();
        var internal_description = $("#internal_description").val();
        var route_path           = $("#route-path").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                description: internal_description,
                route_path: route_path
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal-description-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                var $user_row =  $("#row-" + user_id);
                $user_row.html(feeback.view);
                icheck.init();
            }
        });
    });

    //open dialog
    $document.on("click", ".user-report-action", function () {
        var $this = $(this);
        var user_id = $this.attr("rel");
        var report_action = $this.attr("action");
        $("#user-report-action").val(report_action);
        $("#user-report-action-user-id").val(user_id);
        $("#user-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $document.on("click", "#edit-user-report-action", function () {
        var user_id       = $("#user-report-action-user-id").val();
        var report_action = $("#user-report-action").val();
        var route_path    = $("#route-path").val();
        var time_type     = $("#time_type").val();
        $.ajax({
            type: "POST",
            url: "/user/update-memo",
            data: {
                user_id: user_id,
                report_action: report_action,
                route_path: route_path,
                time_type: time_type
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#user-report-action-dialog" ).dialog( "close" );
                Notifier.showTimedMessage("Update successful", "information", 2);
                var $user_row =  $("#row-" + user_id);
                $user_row.html(feeback.view);
                icheck.init();
            }
        });
    });
});
