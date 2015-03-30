"use strict";

import * as FormUtility from "./libs/FormUtility";
import ProjectUpdater from "./libs/ProjectUpdater";

$(() => {
    FormUtility.editor();
    FormUtility.preventEnter();

    new ProjectUpdater().bootProject();
});
