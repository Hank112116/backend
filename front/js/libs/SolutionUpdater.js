"use strict";

import ProjectUpdater from "./ProjectUpdater";
import SolutionCategoryUpdater from "./SolutionCategoryUpdater";
import SolutionCustomerUpdater from "./SolutionCustomerUpdater";

export function boot() {
    var projectUpdater = new ProjectUpdater();

    projectUpdater.initSelectTag($("[data-select-tags=project_progress]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_progress]"));

    projectUpdater.initSelectTag($("[data-select-tags=project_category]"));
    projectUpdater.initSelectOtherTag($("[data-other-tag=project_category]"));

    projectUpdater.initSelectTag($("[data-select-tags=certification]"));
    projectUpdater.initTagsInputs(["certification_other"]);

    projectUpdater.initProjectTagSelector();

    new SolutionCategoryUpdater();
    new SolutionCustomerUpdater();
}

export function approveSolution() {
    $(".js-approve-solution").click(function(){
        var $this = $(this);
        var solution_id = $this.attr("rel");
        $.ajax({
            type: "POST",
            url: "/solution/approve/" + solution_id,
            dataType: "JSON",
            statusCode: {
                204: function() {
                    Notifier.showTimedMessage("Upload success", "information", 2);
                    location.href = "/solution/detail/" + solution_id;
                },
                403: function($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                404: function($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                412: function() {
                    location.href = "/";
                },
                500: function() {
                    Notifier.showTimedMessage("Server error", "warning", 2);
                }
            }
        });
    });
}

export function rejectSolution() {
    $(".js-reject-solution").click(function(){
        var $this = $(this);
        var solution_id = $this.attr("rel");
        $.ajax({
            type: "POST",
            url: "/solution/reject/" + solution_id,
            dataType: "JSON",
            statusCode: {
                204: function() {
                    Notifier.showTimedMessage("Upload success", "information", 2);
                    location.href = "/solution/detail/" + solution_id;
                },
                403: function($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                404: function($data) {
                    Notifier.showTimedMessage($data.responseJSON.error.message, "warning", 2);
                },
                412: function() {
                    location.href = "/";
                },
                500: function() {
                    Notifier.showTimedMessage("Server error", "warning", 2);
                }
            }
        });
    });
}
