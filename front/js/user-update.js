"use strict";
import * as FormUtility from "./libs/FormUtility";
import * as icheck from "./modules/icheck";
import ProjectUpdater from "./libs/ProjectUpdater";
require("./libs/UserAttachment.js");
require("./libs/UserSuspend.js");

$(() => {
    icheck.initRadio();

    var $country = $("#country");
    var $city    = $("#city");

    if ($country.length) {
        FormUtility.locationSelector($country);
    }

    if ($city.length) {
        FormUtility.locationSelector($city);
    }

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));
});
