"use strict";

var FormUtility = require("./libs/FormUtility");

import ProjectUpdater from "./libs/ProjectUpdater";

$((function () {
    FormUtility.editor();
    new ProjectUpdater().bootProject();
})());
