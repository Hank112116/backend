"use strict";

import * as FormUtility from "./libs/FormUtility";

$((function () {
    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));
    FormUtility.editor();
    FormUtility.preventEnter();
})());
