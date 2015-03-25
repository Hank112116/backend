"use strict";

var swal = require("../vendor/sweet-alert/sweet-alert");

var SweetAlert = {
  /**
   * @param param {title, desc, confirmButton, handleOnConfirm}
   */
  alert: function(param) {
    swal({
      title: param.title || "Are you sure?",
      text: param.desc || "",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: param.confirmButton || "Yes!!",
      closeOnConfirm: true
    }, param.handleOnConfirm);
  }

};

module.exports = SweetAlert;
