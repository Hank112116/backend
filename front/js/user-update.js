"use strict";

import * as FormUtility from "./libs/FormUtility";
import * as icheck from "./modules/icheck";
import * as SweetAlert from "./libs/SweetAlert";

import ProjectUpdater from "./libs/ProjectUpdater";

$(() => {
    icheck.initRadio();

    FormUtility.locationSelector($("#country"));
    FormUtility.locationSelector($("#city"));

    FormUtility.editor();
    new ProjectUpdater().initSelectTag($("[data-select-tags=expertises]"));
});

$(".attachment-trash").click(function(){
    var key = $(this).attr("rel");
    var front_domain = $( "input[name$='front_domain']" ).val();

    SweetAlert.alert({
        title: "Delete attachment?",
        confirmButton: "Yes!",
        handleOnConfirm: () =>
            location.href = "https://"+ front_domain +"/apis/backend/delete-attachment/" + key
    });
});
