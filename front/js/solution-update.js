"use strict";

import * as FormUtility from "./libs/FormUtility";
import * as SolutionUpdater from "./libs/SolutionUpdater";

$(() => {
    FormUtility.editor();
    SolutionUpdater.boot();
});
