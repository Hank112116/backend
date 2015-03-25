"use strict";

import * as FormUtility from "./libs/FormUtility";
import ProjectUpdater from "./libs/ProjectUpdater";

$((function () {
    FormUtility.editor();
    FormUtility.preventEnter();

    new ProjectUpdater().bootProject();
})());
