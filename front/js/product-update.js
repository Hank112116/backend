"use strict";

import * as FormUtility from "./libs/FormUtility";

$(() => {
    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));
    FormUtility.editor();
    FormUtility.preventEnter();
});
