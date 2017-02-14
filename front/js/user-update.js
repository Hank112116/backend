"use strict";
import * as FormUtility from "./libs/FormUtility";
import * as icheck from "./modules/icheck";
import ProjectUpdater from "./libs/ProjectUpdater";
require("./libs/UserAttachment.js");
require("./libs/UserSuspend.js");

$(() => {
    icheck.initRadio();

    let $country  = $("#country");
    let $city     = $("#city");
    let $active   = $("input[name='active']" );

    if ($country.length) {
        FormUtility.locationSelector($country);
    }

    if ($city.length) {
        FormUtility.locationSelector($city);
    }

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));

    $active.on("ifChanged", function () {
        let active = $("input[name='active']:checked").val();
        let $email_verify_1 = $("#email_verify_1");
        let $email_verify_2 = $("#email_verify_2");
        if (active == 0) {
            $email_verify_2.removeAttr("checked");
            $email_verify_1.attr("checked", "checked");
            $email_verify_1.iCheck("check");
        } else if (active == 1) {
            $email_verify_1.removeAttr("checked");
            $email_verify_2.attr("checked", "checked");
            $email_verify_2.iCheck("check");
        }
    });
});
