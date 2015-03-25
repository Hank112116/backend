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
