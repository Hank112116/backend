"use strict";

import * as FormUtility from "./libs/FormUtility";
import * as icheck from "./modules/icheck";

import ProjectUpdater from "./libs/ProjectUpdater";

$(() => {
    icheck.initRadio();

    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));
});
