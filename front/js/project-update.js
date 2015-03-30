"use strict";

var FormUtility = require("./libs/FormUtility");

import ProjectUpdater from "./libs/ProjectUpdater";

$(() => {
    FormUtility.editor();
    new ProjectUpdater().bootProject();
});
