(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    var $internal_tag_input = $("#internal-tag");
    $internal_tag_input.tagsinput({
        confirmKeys: [13],
        allowDuplicates: false,
        tagClass: "bootstrap-tagsinput--tag"
    });

    //open dialog
    $(".internal-tag").click(function () {
        var $this = $(this);
        var tech_tag = $this.attr("tech-tags");
        var internal_tag = $this.attr("tags");
        var project_id = $this.attr("rel");
        $internal_tag_input.tagsinput('removeAll');
        $internal_tag_input.tagsinput('add', internal_tag);
        $("#internal_tag_project_id").val(project_id);
        $("#tech-tag").text(tech_tag);
        $("#internal-tag-dialog").dialog({
            height: 350,
            width: 1000
        });
    });

    $("#add-tags").click(function () {
        var project_id = $("#internal_tag_project_id").val();
        var tags = $internal_tag_input.val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                tags: tags
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal-tag-dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    $(".grade").click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var note = $this.attr("note");
        var grade = $this.attr("grade");
        $("#grade_note").text(note);
        $("#grade").val(grade);
        $("#grade_project_id").val(project_id);
        $("#grade_dialog").dialog({
            height: 350,
            width: 530
        });
    });

    $("#edit_grade").click(function () {
        var project_id = $("#grade_project_id").val();
        var note = $("#grade_note").val();
        var grade = $("#grade").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                schedule_note: note,
                schedule_note_grade: grade
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#grade_dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    //open dialog
    $(".internal-description").click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var internal_description = $this.attr("description");
        $("#internal_description").text(internal_description);
        $("#internal_description_project_id").val(project_id);
        $("#internal-description-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $("#edit_internal_description").click(function () {
        var project_id = $("#internal_description_project_id").val();
        var internal_description = $("#internal_description").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                description: internal_description
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#internal-description-dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    //open dialog
    $(".schedule-manager").click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var pm = $this.attr("pm");
        $("input[name='managers[]']").iCheck("uncheck");
        if (pm) {
            pm = JSON.parse(pm);
            pm.forEach(function (hwtrek_member) {
                $("#hwtrek_member_" + hwtrek_member).iCheck('check');
            });
        }
        $("#schedule_manager_project_id").val(project_id);
        $("#schedule-manager-dialog").dialog({
            height: 270,
            width: 600
        });
    });

    $("#update-schedule-manager").click(function () {
        var project_id = $("#schedule_manager_project_id").val();
        var managers = [];
        $("input[type=checkbox]").each(function () {
            if (this.checked) {
                managers.push($(this).val());
            }
        });

        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                project_managers: JSON.stringify(managers)
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#schedule-manager-dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });

    //open dialog
    $(".project-report-action").click(function () {
        var $this = $(this);
        var project_id = $this.attr("rel");
        var report_action = $this.attr("action");
        $("#project-report-action").text(report_action);
        $("#project-report-action-project-id").val(project_id);
        $("#project-report-action-dialog").dialog({
            height: 300,
            width: 700
        });
    });

    $("#edi-project-report-action").click(function () {
        var project_id = $("#project-report-action-project-id").val();
        var report_action = $("#project-report-action").val();
        $.ajax({
            type: "POST",
            url: "/project/update-memo",
            data: {
                project_id: project_id,
                report_action: report_action
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status === "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                $("#project-report-action-dialog").dialog("close");
                Notifier.showTimedMessage("Update successful", "information", 2);
                location.reload();
            }
        });
    });
});

},{}],2:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    var $propose_dialog = $("#propose-solution-dialog");
    var $recommend_dialog = $("#recommend-expert-dialog");
    var $match_statistics_dialog = $("#project-match-statistics-dialog");
    var $dstart = $("#statistic-start-date").val();
    var $dend = $("#statistic-end-date").val();
    $(".project_propose").click(function () {
        $propose_dialog.html('');
        var $this = $(this);
        var title = $this.attr("title");
        var project_id = $this.attr("rel");
        var propose_type = $this.attr("propose");
        $.ajax({
            type: "POST",
            url: "/project/propose-solution",
            data: {
                project_id: project_id,
                propose_type: propose_type,
                dstart: $dstart,
                dend: $dend
            },
            dataType: "JSON",
            success: function success(feeback) {
                var propose_list = '';
                $.each(feeback, function (index, value) {
                    propose_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>" + "#" + value.solution_id + ". " + "<a href='" + value.solution_url + "' target='_blank' style='color: #428bca'> " + value.solution_title + " </a>" + " By " + "<a href='" + value.user_url + "' target='_blank' style='color: #428bca'> " + value.user_name + " </a>" + " At " + value.at_time + "</div>";
                });
                if (propose_list === "") {
                    propose_list = "N/A";
                }
                $propose_dialog.html(propose_list);
            }
        });
        $propose_dialog.dialog({
            title: title,
            height: 400,
            width: 700
        });
    });

    var $recommend_email_out = $("#email-out-recommend");
    var $recommend_applicant = $("#applicant-recommend");
    $(".project_recommend").click(function () {
        $recommend_email_out.html('');
        $recommend_applicant.html('');
        var $this = $(this);
        var title = $this.attr("title");
        var project_id = $this.attr("rel");
        var recommend_type = $this.attr("recommend");
        $.ajax({
            type: "POST",
            url: "/project/recommend-expert",
            data: {
                project_id: project_id,
                recommend_type: recommend_type,
                dstart: $dstart,
                dend: $dend
            },
            dataType: "JSON",
            success: function success(feeback) {
                var applicant_recommend_list = '';
                var email_out_recommend_list = '';
                $.each(feeback, function (index, value) {

                    if (value.type == 'email-out') {
                        email_out_recommend_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>";
                        if (value.company_name) {
                            email_out_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a> from " + value.company_name;
                        } else {
                            email_out_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a>";
                        }

                        if (value.referral_user_name) {
                            email_out_recommend_list += " By " + value.referral_user_name;
                        }
                        email_out_recommend_list += " At " + value.at_time;
                        email_out_recommend_list += "</div>";
                    }

                    if (value.type == 'applicant') {
                        applicant_recommend_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>";
                        if (value.company_name) {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a> from " + value.company_name;
                        } else {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a>";
                        }

                        if (value.referral_user_name) {
                            applicant_recommend_list += " By " + "<a href='" + value.referral_user_url + "' target='_blank' style='color: #428bca'> " + value.referral_user_name + " </a>";
                        }
                        applicant_recommend_list += " At " + value.at_time;
                        applicant_recommend_list += "</div>";
                    }
                });
                if (email_out_recommend_list === "") {
                    email_out_recommend_list = "N/A";
                }
                if (applicant_recommend_list === "") {
                    applicant_recommend_list = "N/A";
                }
                $recommend_email_out.html(email_out_recommend_list);
                $recommend_applicant.html(applicant_recommend_list);
            }
        });

        if (recommend_type == "internal") {
            $("#email-out-tr").show();
        } else if (recommend_type == "external") {
            $("#email-out-tr").hide();
        }

        $recommend_dialog.dialog({
            title: title,
            height: 400,
            width: 1100
        });
    });
    $(".match-statistics-btn").click(function () {
        var statistics = JSON.parse($("#match-statistics").val());
        var statistics_list = "";
        $.each(statistics, function (index, value) {
            console.log('My array has at position ' + index + ', this value: ' + value.total_count);
            statistics_list += "<tr><td class='col-md-3'>" + index + "</td>" + "<td>Proposed:" + value.propose_count + " Referrals: " + value.recommend_count + " Total: " + value.total_count + "</td>" + "<td>Project: " + value.project_count + " </td>" + "</tr>";
        });
        $("#project-match-statistics-table").html(statistics_list);
        $match_statistics_dialog.dialog({
            height: 400,
            width: 700
        });
    });
});

},{}],3:[function(require,module,exports){
/* jshint quotmark: false */
"use strict";

$(function () {
    //open dialog
    $(".sendmail").click(function () {
        var $this = $(this);
        $("#expert1").val("");
        $("#expert2").val("");
        $("#expert1Info").empty();
        $("#expert2Info").empty();
        var projectId = $this.attr("projectId");
        var projectTitle = $this.attr("projectTitle");
        var userId = $this.attr("userId");
        var PM = $this.attr("PM");
        $("#projectId").val(projectId);
        $("#projectTitle").val(projectTitle);
        $("#userId").val(userId);
        $("#PM").val(PM);
        $("#email-recommend-expert-dialog").dialog({
            height: 270,
            width: 600
        });
    });
    //search expert info
    $("#expert1").change(function () {
        var $expert1Info = $("#expert1Info");
        $expert1Info.empty();
        $expert1Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/hub/get-expert",
            data: {
                expertId: expertId
            },
            dataType: "JSON",
            success: function success(feeback) {
                $expert1Info.text(feeback.msg);
            }
        });
    });
    $("#expert2").change(function () {
        var $expert2Info = $("#expert2Info");
        $expert2Info.empty();
        $expert2Info.append('<i class="fa fa-refresh fa-spin"></i>');
        var $this = $(this);
        var expertId = $this.val();
        $.ajax({
            type: "POST",
            url: "/hub/get-expert",
            data: {
                expertId: expertId
            },
            dataType: "JSON",
            success: function success(feeback) {
                $expert2Info.text(feeback.msg);
            }
        });
    });
    //send mail
    $("#sendMail").click(function () {
        var expert1 = $("#expert1").val();
        var expert2 = $("#expert2").val();
        var projectId = $("#projectId").val();
        var projectTitle = $("#projectTitle").val();
        var userId = $("#userId").val();
        var PM = $("#PM").val();
        if (expert1 && expert2 && PM) {
            $("#email-recommend-expert-dialog").html('<i class="fa fa-refresh fa-spin" style="font-size: 150px;"></i>');
            $.ajax({
                type: "POST",
                url: "/hub_email-send",
                data: {
                    expert1: expert1,
                    expert2: expert2,
                    projectId: projectId,
                    projectTitle: projectTitle,
                    userId: userId,
                    PM: PM
                },
                dataType: "JSON",
                success: function success(feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        location.reload();
                        return;
                    }
                    $("#email-recommend-expert-dialog").dialog("close");
                    Notifier.showTimedMessage("Send mail successful", "information", 2);
                    location.reload();
                }
            });
        } else {
            var errorMsg = "";
            if (!PM) {
                errorMsg = errorMsg + "PM is empty! ";
            }
            if (!expert1 || !expert2) {
                errorMsg = errorMsg + "Expert is empty!";
            }
            Notifier.showTimedMessage(errorMsg, "warning", 2);
        }
    });
});

},{}],4:[function(require,module,exports){
// jshint unused: false
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.alert = alert;
var sweetAlert = require("../vendor/sweetalert/sweetalert.es6.js");

function alert(param) {

    window.sweetAlert({
        title: param.title || "Are you sure?",
        text: param.desc || "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: param.confirmButton || "Yes!!",
        closeOnConfirm: true
    }, param.handleOnConfirm);
}

},{"../vendor/sweetalert/sweetalert.es6.js":14}],5:[function(require,module,exports){
"use strict";

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj["default"] = obj; return newObj; } }

var _libsSweetAlert = require("./libs/SweetAlert");

var SweetAlert = _interopRequireWildcard(_libsSweetAlert);

require("./libs/RecommendExpert.js");
require("./libs/InternalProjectMemo.js");
require("./libs/ProjectProposeRecommend.js");

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var link = this.href;

        SweetAlert.alert({
            title: "Approve and release the Schedule?",
            desc: "Once confirmed, the Hub schedule will be released to the Project owner.",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: function handleOnConfirm() {
                return window.location = link;
            }

        });
        return false;
    });
});

},{"./libs/InternalProjectMemo.js":1,"./libs/ProjectProposeRecommend.js":2,"./libs/RecommendExpert.js":3,"./libs/SweetAlert":4}],6:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});
var defaultParams = {
  title: '',
  text: '',
  type: null,
  allowOutsideClick: false,
  showConfirmButton: true,
  showCancelButton: false,
  closeOnConfirm: true,
  closeOnCancel: true,
  confirmButtonText: 'OK',
  confirmButtonColor: '#AEDEF4',
  cancelButtonText: 'Cancel',
  imageUrl: null,
  imageSize: null,
  timer: null,
  customClass: '',
  html: false,
  animation: true,
  allowEscapeKey: true,
  inputType: 'text',
  inputPlaceholder: ''
};

exports['default'] = defaultParams;
module.exports = exports['default'];

},{}],7:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _utils = require('./utils');

var _handleSwalDom = require('./handle-swal-dom');

var _handleDom = require('./handle-dom');

/*
 * User clicked on "Confirm"/"OK" or "Cancel"
 */
var handleButton = function handleButton(event, params, modal) {
  var e = event || window.event;
  var target = e.target || e.srcElement;

  var targetedConfirm = target.className.indexOf('confirm') !== -1;
  var targetedOverlay = target.className.indexOf('sweet-overlay') !== -1;
  var modalIsVisible = (0, _handleDom.hasClass)(modal, 'visible');
  var doneFunctionExists = params.doneFunction && modal.getAttribute('data-has-done-function') === 'true';

  // Since the user can change the background-color of the confirm button programmatically,
  // we must calculate what the color should be on hover/active
  var normalColor, hoverColor, activeColor;
  if (targetedConfirm && params.confirmButtonColor) {
    normalColor = params.confirmButtonColor;
    hoverColor = (0, _utils.colorLuminance)(normalColor, -0.04);
    activeColor = (0, _utils.colorLuminance)(normalColor, -0.14);
  }

  function shouldSetConfirmButtonColor(color) {
    if (targetedConfirm && params.confirmButtonColor) {
      target.style.backgroundColor = color;
    }
  }

  switch (e.type) {
    case 'mouseover':
      shouldSetConfirmButtonColor(hoverColor);
      break;

    case 'mouseout':
      shouldSetConfirmButtonColor(normalColor);
      break;

    case 'mousedown':
      shouldSetConfirmButtonColor(activeColor);
      break;

    case 'mouseup':
      shouldSetConfirmButtonColor(hoverColor);
      break;

    case 'focus':
      var $confirmButton = modal.querySelector('button.confirm');
      var $cancelButton = modal.querySelector('button.cancel');

      if (targetedConfirm) {
        $cancelButton.style.boxShadow = 'none';
      } else {
        $confirmButton.style.boxShadow = 'none';
      }
      break;

    case 'click':
      var clickedOnModal = modal === target;
      var clickedOnModalChild = (0, _handleDom.isDescendant)(modal, target);

      // Ignore click outside if allowOutsideClick is false
      if (!clickedOnModal && !clickedOnModalChild && modalIsVisible && !params.allowOutsideClick) {
        break;
      }

      if (targetedConfirm && doneFunctionExists && modalIsVisible) {
        handleConfirm(modal, params);
      } else if (doneFunctionExists && modalIsVisible || targetedOverlay) {
        handleCancel(modal, params);
      } else if ((0, _handleDom.isDescendant)(modal, target) && target.tagName === 'BUTTON') {
        sweetAlert.close();
      }
      break;
  }
};

/*
 *  User clicked on "Confirm"/"OK"
 */
var handleConfirm = function handleConfirm(modal, params) {
  var callbackValue = true;

  if ((0, _handleDom.hasClass)(modal, 'show-input')) {
    callbackValue = modal.querySelector('input').value;

    if (!callbackValue) {
      callbackValue = '';
    }
  }

  params.doneFunction(callbackValue);

  if (params.closeOnConfirm) {
    sweetAlert.close();
  }
};

/*
 *  User clicked on "Cancel"
 */
var handleCancel = function handleCancel(modal, params) {
  // Check if callback function expects a parameter (to track cancel actions)
  var functionAsStr = String(params.doneFunction).replace(/\s/g, '');
  var functionHandlesCancel = functionAsStr.substring(0, 9) === 'function(' && functionAsStr.substring(9, 10) !== ')';

  if (functionHandlesCancel) {
    params.doneFunction(false);
  }

  if (params.closeOnCancel) {
    sweetAlert.close();
  }
};

exports['default'] = {
  handleButton: handleButton,
  handleConfirm: handleConfirm,
  handleCancel: handleCancel
};
module.exports = exports['default'];

},{"./handle-dom":8,"./handle-swal-dom":10,"./utils":13}],8:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});
var hasClass = function hasClass(elem, className) {
  return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
};

var addClass = function addClass(elem, className) {
  if (!hasClass(elem, className)) {
    elem.className += ' ' + className;
  }
};

var removeClass = function removeClass(elem, className) {
  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  }
};

var escapeHtml = function escapeHtml(str) {
  var div = document.createElement('div');
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
};

var _show = function _show(elem) {
  elem.style.opacity = '';
  elem.style.display = 'block';
};

var show = function show(elems) {
  if (elems && !elems.length) {
    return _show(elems);
  }
  for (var i = 0; i < elems.length; ++i) {
    _show(elems[i]);
  }
};

var _hide = function _hide(elem) {
  elem.style.opacity = '';
  elem.style.display = 'none';
};

var hide = function hide(elems) {
  if (elems && !elems.length) {
    return _hide(elems);
  }
  for (var i = 0; i < elems.length; ++i) {
    _hide(elems[i]);
  }
};

var isDescendant = function isDescendant(parent, child) {
  var node = child.parentNode;
  while (node !== null) {
    if (node === parent) {
      return true;
    }
    node = node.parentNode;
  }
  return false;
};

var getTopMargin = function getTopMargin(elem) {
  elem.style.left = '-9999px';
  elem.style.display = 'block';

  var height = elem.clientHeight,
      padding;
  if (typeof getComputedStyle !== "undefined") {
    // IE 8
    padding = parseInt(getComputedStyle(elem).getPropertyValue('padding-top'), 10);
  } else {
    padding = parseInt(elem.currentStyle.padding);
  }

  elem.style.left = '';
  elem.style.display = 'none';
  return '-' + parseInt((height + padding) / 2) + 'px';
};

var fadeIn = function fadeIn(elem, interval) {
  if (+elem.style.opacity < 1) {
    interval = interval || 16;
    elem.style.opacity = 0;
    elem.style.display = 'block';
    var last = +new Date();
    var tick = function tick() {
      elem.style.opacity = +elem.style.opacity + (new Date() - last) / 100;
      last = +new Date();

      if (+elem.style.opacity < 1) {
        setTimeout(tick, interval);
      }
    };
    tick();
  }
  elem.style.display = 'block'; //fallback IE8
};

var fadeOut = function fadeOut(elem, interval) {
  interval = interval || 16;
  elem.style.opacity = 1;
  var last = +new Date();
  var tick = function tick() {
    elem.style.opacity = +elem.style.opacity - (new Date() - last) / 100;
    last = +new Date();

    if (+elem.style.opacity > 0) {
      setTimeout(tick, interval);
    } else {
      elem.style.display = 'none';
    }
  };
  tick();
};

var fireClick = function fireClick(node) {
  // Taken from http://www.nonobtrusive.com/2011/11/29/programatically-fire-crossbrowser-click-event-with-javascript/
  // Then fixed for today's Chrome browser.
  if (typeof MouseEvent === 'function') {
    // Up-to-date approach
    var mevt = new MouseEvent('click', {
      view: window,
      bubbles: false,
      cancelable: true
    });
    node.dispatchEvent(mevt);
  } else if (document.createEvent) {
    // Fallback
    var evt = document.createEvent('MouseEvents');
    evt.initEvent('click', false, false);
    node.dispatchEvent(evt);
  } else if (document.createEventObject) {
    node.fireEvent('onclick');
  } else if (typeof node.onclick === 'function') {
    node.onclick();
  }
};

var stopEventPropagation = function stopEventPropagation(e) {
  // In particular, make sure the space bar doesn't scroll the main window.
  if (typeof e.stopPropagation === 'function') {
    e.stopPropagation();
    e.preventDefault();
  } else if (window.event && window.event.hasOwnProperty('cancelBubble')) {
    window.event.cancelBubble = true;
  }
};

exports.hasClass = hasClass;
exports.addClass = addClass;
exports.removeClass = removeClass;
exports.escapeHtml = escapeHtml;
exports._show = _show;
exports.show = show;
exports._hide = _hide;
exports.hide = hide;
exports.isDescendant = isDescendant;
exports.getTopMargin = getTopMargin;
exports.fadeIn = fadeIn;
exports.fadeOut = fadeOut;
exports.fireClick = fireClick;
exports.stopEventPropagation = stopEventPropagation;

},{}],9:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _handleDom = require('./handle-dom');

var _handleSwalDom = require('./handle-swal-dom');

var handleKeyDown = function handleKeyDown(event, params, modal) {
  var e = event || window.event;
  var keyCode = e.keyCode || e.which;

  var $okButton = modal.querySelector('button.confirm');
  var $cancelButton = modal.querySelector('button.cancel');
  var $modalButtons = modal.querySelectorAll('button[tabindex]');

  if ([9, 13, 32, 27].indexOf(keyCode) === -1) {
    // Don't do work on keys we don't care about.
    return;
  }

  var $targetElement = e.target || e.srcElement;

  var btnIndex = -1; // Find the button - note, this is a nodelist, not an array.
  for (var i = 0; i < $modalButtons.length; i++) {
    if ($targetElement === $modalButtons[i]) {
      btnIndex = i;
      break;
    }
  }

  if (keyCode === 9) {
    // TAB
    if (btnIndex === -1) {
      // No button focused. Jump to the confirm button.
      $targetElement = $okButton;
    } else {
      // Cycle to the next button
      if (btnIndex === $modalButtons.length - 1) {
        $targetElement = $modalButtons[0];
      } else {
        $targetElement = $modalButtons[btnIndex + 1];
      }
    }

    (0, _handleDom.stopEventPropagation)(e);
    $targetElement.focus();

    if (params.confirmButtonColor) {
      (0, _handleSwalDom.setFocusStyle)($targetElement, params.confirmButtonColor);
    }
  } else {
    if (keyCode === 13) {
      if ($targetElement.tagName === 'INPUT') {
        $targetElement = $okButton;
        $okButton.focus();
      }

      if (btnIndex === -1) {
        // ENTER/SPACE clicked outside of a button.
        $targetElement = $okButton;
      } else {
        // Do nothing - let the browser handle it.
        $targetElement = undefined;
      }
    } else if (keyCode === 27 && params.allowEscapeKey === true) {
      $targetElement = $cancelButton;
      (0, _handleDom.fireClick)($targetElement, e);
    } else {
      // Fallback - let the browser handle it.
      $targetElement = undefined;
    }
  }
};

exports['default'] = handleKeyDown;
module.exports = exports['default'];

},{"./handle-dom":8,"./handle-swal-dom":10}],10:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _utils = require('./utils');

var _handleDom = require('./handle-dom');

var _defaultParams = require('./default-params');

var _defaultParams2 = _interopRequireDefault(_defaultParams);

/*
 * Add modal + overlay to DOM
 */

var _injectedHtml = require('./injected-html');

var _injectedHtml2 = _interopRequireDefault(_injectedHtml);

var modalClass = '.sweet-alert';
var overlayClass = '.sweet-overlay';

var sweetAlertInitialize = function sweetAlertInitialize() {
  var sweetWrap = document.createElement('div');
  sweetWrap.innerHTML = _injectedHtml2['default'];

  // Append elements to body
  while (sweetWrap.firstChild) {
    document.body.appendChild(sweetWrap.firstChild);
  }
};

/*
 * Get DOM element of modal
 */
var getModal = function getModal() {
  var $modal = document.querySelector(modalClass);

  if (!$modal) {
    sweetAlertInitialize();
    $modal = getModal();
  }

  return $modal;
};

/*
 * Get DOM element of input (in modal)
 */
var getInput = function getInput() {
  var $modal = getModal();
  if ($modal) {
    return $modal.querySelector('input');
  }
};

/*
 * Get DOM element of overlay
 */
var getOverlay = function getOverlay() {
  return document.querySelector(overlayClass);
};

/*
 * Add box-shadow style to button (depending on its chosen bg-color)
 */
var setFocusStyle = function setFocusStyle($button, bgColor) {
  var rgbColor = (0, _utils.hexToRgb)(bgColor);
  $button.style.boxShadow = '0 0 2px rgba(' + rgbColor + ', 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)';
};

/*
 * Animation when opening modal
 */
var openModal = function openModal() {
  var $modal = getModal();
  (0, _handleDom.fadeIn)(getOverlay(), 10);
  (0, _handleDom.show)($modal);
  (0, _handleDom.addClass)($modal, 'showSweetAlert');
  (0, _handleDom.removeClass)($modal, 'hideSweetAlert');

  window.previousActiveElement = document.activeElement;
  var $okButton = $modal.querySelector('button.confirm');
  $okButton.focus();

  setTimeout(function () {
    (0, _handleDom.addClass)($modal, 'visible');
  }, 500);

  var timer = $modal.getAttribute('data-timer');

  if (timer !== 'null' && timer !== '') {
    $modal.timeout = setTimeout(function () {
      swal.close();
    }, timer);
  }
};

/*
 * Reset the styling of the input
 * (for example if errors have been shown)
 */
var resetInput = function resetInput() {
  var $modal = getModal();
  var $input = getInput();

  (0, _handleDom.removeClass)($modal, 'show-input');
  $input.value = '';
  $input.setAttribute('type', _defaultParams2['default'].inputType);
  $input.setAttribute('placeholder', _defaultParams2['default'].inputPlaceholder);

  resetInputError();
};

var resetInputError = function resetInputError(event) {
  // If press enter => ignore
  if (event && event.keyCode === 13) {
    return false;
  }

  var $modal = getModal();

  var $errorIcon = $modal.querySelector('.sa-input-error');
  (0, _handleDom.removeClass)($errorIcon, 'show');

  var $errorContainer = $modal.querySelector('.sa-error-container');
  (0, _handleDom.removeClass)($errorContainer, 'show');
};

/*
 * Set "margin-top"-property on modal based on its computed height
 */
var fixVerticalPosition = function fixVerticalPosition() {
  var $modal = getModal();
  $modal.style.marginTop = (0, _handleDom.getTopMargin)(getModal());
};

exports.sweetAlertInitialize = sweetAlertInitialize;
exports.getModal = getModal;
exports.getOverlay = getOverlay;
exports.getInput = getInput;
exports.setFocusStyle = setFocusStyle;
exports.openModal = openModal;
exports.resetInput = resetInput;
exports.resetInputError = resetInputError;
exports.fixVerticalPosition = fixVerticalPosition;

},{"./default-params":6,"./handle-dom":8,"./injected-html":11,"./utils":13}],11:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var injectedHTML =

// Dark overlay
"<div class=\"sweet-overlay\" tabIndex=\"-1\"></div>" +

// Modal
"<div class=\"sweet-alert\">" +

// Error icon
"<div class=\"sa-icon sa-error\">\n      <span class=\"sa-x-mark\">\n        <span class=\"sa-line sa-left\"></span>\n        <span class=\"sa-line sa-right\"></span>\n      </span>\n    </div>" +

// Warning icon
"<div class=\"sa-icon sa-warning\">\n      <span class=\"sa-body\"></span>\n      <span class=\"sa-dot\"></span>\n    </div>" +

// Info icon
"<div class=\"sa-icon sa-info\"></div>" +

// Success icon
"<div class=\"sa-icon sa-success\">\n      <span class=\"sa-line sa-tip\"></span>\n      <span class=\"sa-line sa-long\"></span>\n\n      <div class=\"sa-placeholder\"></div>\n      <div class=\"sa-fix\"></div>\n    </div>" + "<div class=\"sa-icon sa-custom\"></div>" +

// Title, text and input
"<h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type=\"text\" tabIndex=\"3\" />\n      <div class=\"sa-input-error\"></div>\n    </fieldset>" +

// Input errors
"<div class=\"sa-error-container\">\n      <div class=\"icon\">!</div>\n      <p>Not valid!</p>\n    </div>" +

// Cancel and confirm buttons
"<div class=\"sa-button-container\">\n      <button class=\"cancel\" tabIndex=\"2\">Cancel</button>\n      <button class=\"confirm\" tabIndex=\"1\">OK</button>\n    </div>" +

// End of modal
"</div>";

exports["default"] = injectedHTML;
module.exports = exports["default"];

},{}],12:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _utils = require('./utils');

var _handleSwalDom = require('./handle-swal-dom');

var _handleDom = require('./handle-dom');

/*
 * Set type, text and actions on modal
 */
var alertTypes = ['error', 'warning', 'info', 'success', 'input', 'prompt'];

var setParameters = function setParameters(params) {
  var modal = (0, _handleSwalDom.getModal)();

  var $title = modal.querySelector('h2');
  var $text = modal.querySelector('p');
  var $cancelBtn = modal.querySelector('button.cancel');
  var $confirmBtn = modal.querySelector('button.confirm');

  /*
   * Title
   */
  $title.innerHTML = params.html ? params.title : (0, _handleDom.escapeHtml)(params.title).split('\n').join('<br>');

  /*
   * Text
   */
  $text.innerHTML = params.html ? params.text : (0, _handleDom.escapeHtml)(params.text || '').split('\n').join('<br>');
  if (params.text) (0, _handleDom.show)($text);

  /*
   * Custom class
   */
  if (params.customClass) {
    (0, _handleDom.addClass)(modal, params.customClass);
    modal.setAttribute('data-custom-class', params.customClass);
  } else {
    // Find previously set classes and remove them
    var customClass = modal.getAttribute('data-custom-class');
    (0, _handleDom.removeClass)(modal, customClass);
    modal.setAttribute('data-custom-class', '');
  }

  /*
   * Icon
   */
  (0, _handleDom.hide)(modal.querySelectorAll('.sa-icon'));

  if (params.type && !(0, _utils.isIE8)()) {
    var _ret = (function () {

      var validType = false;

      for (var i = 0; i < alertTypes.length; i++) {
        if (params.type === alertTypes[i]) {
          validType = true;
          break;
        }
      }

      if (!validType) {
        logStr('Unknown alert type: ' + params.type);
        return {
          v: false
        };
      }

      var typesWithIcons = ['success', 'error', 'warning', 'info'];
      var $icon = undefined;

      if (typesWithIcons.indexOf(params.type) !== -1) {
        $icon = modal.querySelector('.sa-icon.' + 'sa-' + params.type);
        (0, _handleDom.show)($icon);
      }

      var $input = (0, _handleSwalDom.getInput)();

      // Animate icon
      switch (params.type) {

        case 'success':
          (0, _handleDom.addClass)($icon, 'animate');
          (0, _handleDom.addClass)($icon.querySelector('.sa-tip'), 'animateSuccessTip');
          (0, _handleDom.addClass)($icon.querySelector('.sa-long'), 'animateSuccessLong');
          break;

        case 'error':
          (0, _handleDom.addClass)($icon, 'animateErrorIcon');
          (0, _handleDom.addClass)($icon.querySelector('.sa-x-mark'), 'animateXMark');
          break;

        case 'warning':
          (0, _handleDom.addClass)($icon, 'pulseWarning');
          (0, _handleDom.addClass)($icon.querySelector('.sa-body'), 'pulseWarningIns');
          (0, _handleDom.addClass)($icon.querySelector('.sa-dot'), 'pulseWarningIns');
          break;

        case 'input':
        case 'prompt':
          $input.setAttribute('type', params.inputType);
          $input.setAttribute('placeholder', params.inputPlaceholder);
          (0, _handleDom.addClass)(modal, 'show-input');
          setTimeout(function () {
            $input.focus();
            $input.addEventListener('keyup', swal.resetInputError);
          }, 400);
          break;
      }
    })();

    if (typeof _ret === 'object') return _ret.v;
  }

  /*
   * Custom image
   */
  if (params.imageUrl) {
    var $customIcon = modal.querySelector('.sa-icon.sa-custom');

    $customIcon.style.backgroundImage = 'url(' + params.imageUrl + ')';
    (0, _handleDom.show)($customIcon);

    var _imgWidth = 80;
    var _imgHeight = 80;

    if (params.imageSize) {
      var dimensions = params.imageSize.toString().split('x');
      var imgWidth = dimensions[0];
      var imgHeight = dimensions[1];

      if (!imgWidth || !imgHeight) {
        logStr('Parameter imageSize expects value with format WIDTHxHEIGHT, got ' + params.imageSize);
      } else {
        _imgWidth = imgWidth;
        _imgHeight = imgHeight;
      }
    }

    $customIcon.setAttribute('style', $customIcon.getAttribute('style') + 'width:' + _imgWidth + 'px; height:' + _imgHeight + 'px');
  }

  /*
   * Show cancel button?
   */
  modal.setAttribute('data-has-cancel-button', params.showCancelButton);
  if (params.showCancelButton) {
    $cancelBtn.style.display = 'inline-block';
  } else {
    (0, _handleDom.hide)($cancelBtn);
  }

  /*
   * Show confirm button?
   */
  modal.setAttribute('data-has-confirm-button', params.showConfirmButton);
  if (params.showConfirmButton) {
    $confirmBtn.style.display = 'inline-block';
  } else {
    (0, _handleDom.hide)($confirmBtn);
  }

  /*
   * Custom text on cancel/confirm buttons
   */
  if (params.cancelButtonText) {
    $cancelBtn.innerHTML = (0, _handleDom.escapeHtml)(params.cancelButtonText);
  }
  if (params.confirmButtonText) {
    $confirmBtn.innerHTML = (0, _handleDom.escapeHtml)(params.confirmButtonText);
  }

  /*
   * Custom color on confirm button
   */
  if (params.confirmButtonColor) {
    // Set confirm button to selected background color
    $confirmBtn.style.backgroundColor = params.confirmButtonColor;

    // Set box-shadow to default focused button
    (0, _handleSwalDom.setFocusStyle)($confirmBtn, params.confirmButtonColor);
  }

  /*
   * Allow outside click
   */
  modal.setAttribute('data-allow-outside-click', params.allowOutsideClick);

  /*
   * Callback function
   */
  var hasDoneFunction = params.doneFunction ? true : false;
  modal.setAttribute('data-has-done-function', hasDoneFunction);

  /*
   * Animation
   */
  if (!params.animation) {
    modal.setAttribute('data-animation', 'none');
  } else if (typeof params.animation === 'string') {
    modal.setAttribute('data-animation', params.animation); // Custom animation
  } else {
      modal.setAttribute('data-animation', 'pop');
    }

  /*
   * Timer
   */
  modal.setAttribute('data-timer', params.timer);
};

exports['default'] = setParameters;
module.exports = exports['default'];

},{"./handle-dom":8,"./handle-swal-dom":10,"./utils":13}],13:[function(require,module,exports){
/*
 * Allow user to pass their own params
 */
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});
var extend = function extend(a, b) {
  for (var key in b) {
    if (b.hasOwnProperty(key)) {
      a[key] = b[key];
    }
  }
  return a;
};

/*
 * Convert HEX codes to RGB values (#000000 -> rgb(0,0,0))
 */
var hexToRgb = function hexToRgb(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? parseInt(result[1], 16) + ', ' + parseInt(result[2], 16) + ', ' + parseInt(result[3], 16) : null;
};

/*
 * Check if the user is using Internet Explorer 8 (for fallbacks)
 */
var isIE8 = function isIE8() {
  return window.attachEvent && !window.addEventListener;
};

/*
 * IE compatible logging for developers
 */
var logStr = function logStr(string) {
  if (window.console) {
    // IE...
    window.console.log('SweetAlert: ' + string);
  }
};

/*
 * Set hover, active and focus-states for buttons 
 * (source: http://www.sitepoint.com/javascript-generate-lighter-darker-color)
 */
var colorLuminance = function colorLuminance(hex, lum) {
  // Validate hex string
  hex = String(hex).replace(/[^0-9a-f]/gi, '');
  if (hex.length < 6) {
    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  }
  lum = lum || 0;

  // Convert to decimal and change luminosity
  var rgb = '#';
  var c;
  var i;

  for (i = 0; i < 3; i++) {
    c = parseInt(hex.substr(i * 2, 2), 16);
    c = Math.round(Math.min(Math.max(0, c + c * lum), 255)).toString(16);
    rgb += ('00' + c).substr(c.length);
  }

  return rgb;
};

exports.extend = extend;
exports.hexToRgb = hexToRgb;
exports.isIE8 = isIE8;
exports.logStr = logStr;
exports.colorLuminance = colorLuminance;

},{}],14:[function(require,module,exports){
// SweetAlert
// 2014-2015 (c) - Tristan Edwards
// github.com/t4t5/sweetalert

/*
 * jQuery-like functions for manipulating the DOM
 */
'use strict';

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _modulesHandleDom = require('./modules/handle-dom');

/*
 * Handy utilities
 */

var _modulesUtils = require('./modules/utils');

/*
 *  Handle sweetAlert's DOM elements
 */

var _modulesHandleSwalDom = require('./modules/handle-swal-dom');

// Handle button events and keyboard events

var _modulesHandleClick = require('./modules/handle-click');

var _modulesHandleKey = require('./modules/handle-key');

var _modulesHandleKey2 = _interopRequireDefault(_modulesHandleKey);

// Default values

var _modulesDefaultParams = require('./modules/default-params');

var _modulesDefaultParams2 = _interopRequireDefault(_modulesDefaultParams);

var _modulesSetParams = require('./modules/set-params');

var _modulesSetParams2 = _interopRequireDefault(_modulesSetParams);

/*
 * Remember state in cases where opening and handling a modal will fiddle with it.
 * (We also use window.previousActiveElement as a global variable)
 */
var previousWindowKeyDown;
var lastFocusedButton;

/*
 * Global sweetAlert function
 * (this is what the user calls)
 */
var sweetAlert, swal;

sweetAlert = swal = function () {
  var customizations = arguments[0];

  (0, _modulesHandleDom.addClass)(document.body, 'stop-scrolling');
  (0, _modulesHandleSwalDom.resetInput)();

  /*
   * Use argument if defined or default value from params object otherwise.
   * Supports the case where a default value is boolean true and should be
   * overridden by a corresponding explicit argument which is boolean false.
   */
  function argumentOrDefault(key) {
    var args = customizations;
    return args[key] === undefined ? _modulesDefaultParams2['default'][key] : args[key];
  }

  if (customizations === undefined) {
    (0, _modulesUtils.logStr)('SweetAlert expects at least 1 attribute!');
    return false;
  }

  var params = (0, _modulesUtils.extend)({}, _modulesDefaultParams2['default']);

  switch (typeof customizations) {

    // Ex: swal("Hello", "Just testing", "info");
    case 'string':
      params.title = customizations;
      params.text = arguments[1] || '';
      params.type = arguments[2] || '';
      break;

    // Ex: swal({ title:"Hello", text: "Just testing", type: "info" });
    case 'object':
      if (customizations.title === undefined) {
        (0, _modulesUtils.logStr)('Missing "title" argument!');
        return false;
      }

      params.title = customizations.title;

      for (var customName in _modulesDefaultParams2['default']) {
        params[customName] = argumentOrDefault(customName);
      }

      // Show "Confirm" instead of "OK" if cancel button is visible
      params.confirmButtonText = params.showCancelButton ? 'Confirm' : _modulesDefaultParams2['default'].confirmButtonText;
      params.confirmButtonText = argumentOrDefault('confirmButtonText');

      // Callback function when clicking on "OK"/"Cancel"
      params.doneFunction = arguments[1] || null;

      break;

    default:
      (0, _modulesUtils.logStr)('Unexpected type of argument! Expected "string" or "object", got ' + typeof customizations);
      return false;

  }

  (0, _modulesSetParams2['default'])(params);
  (0, _modulesHandleSwalDom.fixVerticalPosition)();
  (0, _modulesHandleSwalDom.openModal)();

  // Modal interactions
  var modal = (0, _modulesHandleSwalDom.getModal)();

  /* 
   * Make sure all modal buttons respond to all events
   */
  var $buttons = modal.querySelectorAll('button');
  var buttonEvents = ['onclick', 'onmouseover', 'onmouseout', 'onmousedown', 'onmouseup', 'onfocus'];
  var onButtonEvent = function onButtonEvent(e) {
    return (0, _modulesHandleClick.handleButton)(e, params, modal);
  };

  for (var btnIndex = 0; btnIndex < $buttons.length; btnIndex++) {
    for (var evtIndex = 0; evtIndex < buttonEvents.length; evtIndex++) {
      var btnEvt = buttonEvents[evtIndex];
      $buttons[btnIndex][btnEvt] = onButtonEvent;
    }
  }

  // Clicking outside the modal dismisses it (if allowed by user)
  (0, _modulesHandleSwalDom.getOverlay)().onclick = onButtonEvent;

  previousWindowKeyDown = window.onkeydown;

  var onKeyEvent = function onKeyEvent(e) {
    return (0, _modulesHandleKey2['default'])(e, params, modal);
  };
  window.onkeydown = onKeyEvent;

  window.onfocus = function () {
    // When the user has focused away and focused back from the whole window.
    setTimeout(function () {
      // Put in a timeout to jump out of the event sequence.
      // Calling focus() in the event sequence confuses things.
      if (lastFocusedButton !== undefined) {
        lastFocusedButton.focus();
        lastFocusedButton = undefined;
      }
    }, 0);
  };
};

/*
 * Set default params for each popup
 * @param {Object} userParams
 */
sweetAlert.setDefaults = swal.setDefaults = function (userParams) {
  if (!userParams) {
    throw new Error('userParams is required');
  }
  if (typeof userParams !== 'object') {
    throw new Error('userParams has to be a object');
  }

  (0, _modulesUtils.extend)(_modulesDefaultParams2['default'], userParams);
};

/*
 * Animation when closing modal
 */
sweetAlert.close = swal.close = function () {
  var modal = (0, _modulesHandleSwalDom.getModal)();

  (0, _modulesHandleDom.fadeOut)((0, _modulesHandleSwalDom.getOverlay)(), 5);
  (0, _modulesHandleDom.fadeOut)(modal, 5);
  (0, _modulesHandleDom.removeClass)(modal, 'showSweetAlert');
  (0, _modulesHandleDom.addClass)(modal, 'hideSweetAlert');
  (0, _modulesHandleDom.removeClass)(modal, 'visible');

  /*
   * Reset icon animations
   */
  var $successIcon = modal.querySelector('.sa-icon.sa-success');
  (0, _modulesHandleDom.removeClass)($successIcon, 'animate');
  (0, _modulesHandleDom.removeClass)($successIcon.querySelector('.sa-tip'), 'animateSuccessTip');
  (0, _modulesHandleDom.removeClass)($successIcon.querySelector('.sa-long'), 'animateSuccessLong');

  var $errorIcon = modal.querySelector('.sa-icon.sa-error');
  (0, _modulesHandleDom.removeClass)($errorIcon, 'animateErrorIcon');
  (0, _modulesHandleDom.removeClass)($errorIcon.querySelector('.sa-x-mark'), 'animateXMark');

  var $warningIcon = modal.querySelector('.sa-icon.sa-warning');
  (0, _modulesHandleDom.removeClass)($warningIcon, 'pulseWarning');
  (0, _modulesHandleDom.removeClass)($warningIcon.querySelector('.sa-body'), 'pulseWarningIns');
  (0, _modulesHandleDom.removeClass)($warningIcon.querySelector('.sa-dot'), 'pulseWarningIns');

  // Make page scrollable again
  (0, _modulesHandleDom.removeClass)(document.body, 'stop-scrolling');

  // Reset the page to its previous state
  window.onkeydown = previousWindowKeyDown;
  if (window.previousActiveElement) {
    window.previousActiveElement.focus();
  }
  lastFocusedButton = undefined;
  clearTimeout(modal.timeout);

  return true;
};

/*
 * Validation of the input field is done by user
 * If something is wrong => call showInputError with errorMessage
 */
sweetAlert.showInputError = swal.showInputError = function (errorMessage) {
  var modal = (0, _modulesHandleSwalDom.getModal)();

  var $errorIcon = modal.querySelector('.sa-input-error');
  (0, _modulesHandleDom.addClass)($errorIcon, 'show');

  var $errorContainer = modal.querySelector('.sa-error-container');
  (0, _modulesHandleDom.addClass)($errorContainer, 'show');

  $errorContainer.querySelector('p').innerHTML = errorMessage;

  modal.querySelector('input').focus();
};

/*
 * Reset input error DOM elements
 */
sweetAlert.resetInputError = swal.resetInputError = function (event) {
  // If press enter => ignore
  if (event && event.keyCode === 13) {
    return false;
  }

  var $modal = (0, _modulesHandleSwalDom.getModal)();

  var $errorIcon = $modal.querySelector('.sa-input-error');
  (0, _modulesHandleDom.removeClass)($errorIcon, 'show');

  var $errorContainer = $modal.querySelector('.sa-error-container');
  (0, _modulesHandleDom.removeClass)($errorContainer, 'show');
};

/*
 * Use SweetAlert with RequireJS
 */
if (typeof define === 'function' && define.amd) {
  define(function () {
    return sweetAlert;
  });
} else if (typeof window !== 'undefined') {
  window.sweetAlert = window.swal = sweetAlert;
} else if (typeof module !== 'undefined' && module.exports) {
  module.exports = sweetAlert;
}

},{"./modules/default-params":6,"./modules/handle-click":7,"./modules/handle-dom":8,"./modules/handle-key":9,"./modules/handle-swal-dom":10,"./modules/set-params":12,"./modules/utils":13}]},{},[5]);
