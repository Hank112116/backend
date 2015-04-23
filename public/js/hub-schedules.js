(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { "default": obj }; };

var SweetAlert = _interopRequireWildcard(require("./libs/SweetAlert"));

$(function () {
    $(".js-approve").click(function (e) {
        e.preventDefault();

        var link = this.href;

        SweetAlert.alert({
            title: "Approve?",
            desc: "It'll take a bit long time to approve",
            confirmButton: "Yes, Approve!",
            handleOnConfirm: function () {
                return window.location = link;
            }

        });

        // if(confirm('Approve?')) {
        //     window.location = link;
        // }

        return false;
    });
});

},{"./libs/SweetAlert":2}],2:[function(require,module,exports){
"use strict";

exports.alert = alert;
Object.defineProperty(exports, "__esModule", {
    value: true
});
"use strict";

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

},{"../vendor/sweetalert/sweetalert.es6.js":11}],3:[function(require,module,exports){
"use strict";

var defaultParams = {
  title: "",
  text: "",
  type: null,
  allowOutsideClick: false,
  showConfirmButton: true,
  showCancelButton: false,
  closeOnConfirm: true,
  closeOnCancel: true,
  confirmButtonText: "OK",
  confirmButtonColor: "#AEDEF4",
  cancelButtonText: "Cancel",
  imageUrl: null,
  imageSize: null,
  timer: null,
  customClass: "",
  html: false,
  animation: true,
  allowEscapeKey: true,
  inputType: "text",
  inputPlaceholder: ""
};

module.exports = defaultParams;

},{}],4:[function(require,module,exports){
"use strict";

var colorLuminance = require("./utils").colorLuminance;

var getModal = require("./handle-swal-dom").getModal;

var _handleDom = require("./handle-dom");

var hasClass = _handleDom.hasClass;
var isDescendant = _handleDom.isDescendant;

/*
 * User clicked on "Confirm"/"OK" or "Cancel"
 */
var handleButton = function handleButton(event, params, modal) {
  var e = event || window.event;
  var target = e.target || e.srcElement;

  var targetedConfirm = target.className.indexOf("confirm") !== -1;
  var targetedOverlay = target.className.indexOf("sweet-overlay") !== -1;
  var modalIsVisible = hasClass(modal, "visible");
  var doneFunctionExists = params.doneFunction && modal.getAttribute("data-has-done-function") === "true";

  // Since the user can change the background-color of the confirm button programmatically,
  // we must calculate what the color should be on hover/active
  var normalColor, hoverColor, activeColor;
  if (targetedConfirm && params.confirmButtonColor) {
    normalColor = params.confirmButtonColor;
    hoverColor = colorLuminance(normalColor, -0.04);
    activeColor = colorLuminance(normalColor, -0.14);
  }

  function shouldSetConfirmButtonColor(color) {
    if (targetedConfirm && params.confirmButtonColor) {
      target.style.backgroundColor = color;
    }
  }

  switch (e.type) {
    case "mouseover":
      shouldSetConfirmButtonColor(hoverColor);
      break;

    case "mouseout":
      shouldSetConfirmButtonColor(normalColor);
      break;

    case "mousedown":
      shouldSetConfirmButtonColor(activeColor);
      break;

    case "mouseup":
      shouldSetConfirmButtonColor(hoverColor);
      break;

    case "focus":
      var $confirmButton = modal.querySelector("button.confirm");
      var $cancelButton = modal.querySelector("button.cancel");

      if (targetedConfirm) {
        $cancelButton.style.boxShadow = "none";
      } else {
        $confirmButton.style.boxShadow = "none";
      }
      break;

    case "click":
      var clickedOnModal = modal === target;
      var clickedOnModalChild = isDescendant(modal, target);

      // Ignore click outside if allowOutsideClick is false
      if (!clickedOnModal && !clickedOnModalChild && modalIsVisible && !params.allowOutsideClick) {
        break;
      }

      if (targetedConfirm && doneFunctionExists && modalIsVisible) {
        handleConfirm(modal, params);
      } else if (doneFunctionExists && modalIsVisible || targetedOverlay) {
        handleCancel(modal, params);
      } else if (isDescendant(modal, target) && target.tagName === "BUTTON") {
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

  if (hasClass(modal, "show-input")) {
    callbackValue = modal.querySelector("input").value;

    if (!callbackValue) {
      callbackValue = "";
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
  var functionAsStr = String(params.doneFunction).replace(/\s/g, "");
  var functionHandlesCancel = functionAsStr.substring(0, 9) === "function(" && functionAsStr.substring(9, 10) !== ")";

  if (functionHandlesCancel) {
    params.doneFunction(false);
  }

  if (params.closeOnCancel) {
    sweetAlert.close();
  }
};

module.exports = {
  handleButton: handleButton,
  handleConfirm: handleConfirm,
  handleCancel: handleCancel
};

},{"./handle-dom":5,"./handle-swal-dom":7,"./utils":10}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var hasClass = function hasClass(elem, className) {
  return new RegExp(" " + className + " ").test(" " + elem.className + " ");
};

var addClass = function addClass(elem, className) {
  if (!hasClass(elem, className)) {
    elem.className += " " + className;
  }
};

var removeClass = function removeClass(elem, className) {
  var newClass = " " + elem.className.replace(/[\t\r\n]/g, " ") + " ";
  if (hasClass(elem, className)) {
    while (newClass.indexOf(" " + className + " ") >= 0) {
      newClass = newClass.replace(" " + className + " ", " ");
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, "");
  }
};

var escapeHtml = function escapeHtml(str) {
  var div = document.createElement("div");
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
};

var _show = function _show(elem) {
  elem.style.opacity = "";
  elem.style.display = "block";
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
  elem.style.opacity = "";
  elem.style.display = "none";
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
  elem.style.left = "-9999px";
  elem.style.display = "block";

  var height = elem.clientHeight,
      padding;
  if (typeof getComputedStyle !== "undefined") {
    // IE 8
    padding = parseInt(getComputedStyle(elem).getPropertyValue("padding-top"), 10);
  } else {
    padding = parseInt(elem.currentStyle.padding);
  }

  elem.style.left = "";
  elem.style.display = "none";
  return "-" + parseInt((height + padding) / 2) + "px";
};

var fadeIn = function fadeIn(elem, interval) {
  if (+elem.style.opacity < 1) {
    interval = interval || 16;
    elem.style.opacity = 0;
    elem.style.display = "block";
    var last = +new Date();
    var tick = (function (_tick) {
      var _tickWrapper = function tick() {
        return _tick.apply(this, arguments);
      };

      _tickWrapper.toString = function () {
        return _tick.toString();
      };

      return _tickWrapper;
    })(function () {
      elem.style.opacity = +elem.style.opacity + (new Date() - last) / 100;
      last = +new Date();

      if (+elem.style.opacity < 1) {
        setTimeout(tick, interval);
      }
    });
    tick();
  }
  elem.style.display = "block"; //fallback IE8
};

var fadeOut = function fadeOut(elem, interval) {
  interval = interval || 16;
  elem.style.opacity = 1;
  var last = +new Date();
  var tick = (function (_tick) {
    var _tickWrapper = function tick() {
      return _tick.apply(this, arguments);
    };

    _tickWrapper.toString = function () {
      return _tick.toString();
    };

    return _tickWrapper;
  })(function () {
    elem.style.opacity = +elem.style.opacity - (new Date() - last) / 100;
    last = +new Date();

    if (+elem.style.opacity > 0) {
      setTimeout(tick, interval);
    } else {
      elem.style.display = "none";
    }
  });
  tick();
};

var fireClick = function fireClick(node) {
  // Taken from http://www.nonobtrusive.com/2011/11/29/programatically-fire-crossbrowser-click-event-with-javascript/
  // Then fixed for today's Chrome browser.
  if (typeof MouseEvent === "function") {
    // Up-to-date approach
    var mevt = new MouseEvent("click", {
      view: window,
      bubbles: false,
      cancelable: true
    });
    node.dispatchEvent(mevt);
  } else if (document.createEvent) {
    // Fallback
    var evt = document.createEvent("MouseEvents");
    evt.initEvent("click", false, false);
    node.dispatchEvent(evt);
  } else if (document.createEventObject) {
    node.fireEvent("onclick");
  } else if (typeof node.onclick === "function") {
    node.onclick();
  }
};

var stopEventPropagation = function stopEventPropagation(e) {
  // In particular, make sure the space bar doesn't scroll the main window.
  if (typeof e.stopPropagation === "function") {
    e.stopPropagation();
    e.preventDefault();
  } else if (window.event && window.event.hasOwnProperty("cancelBubble")) {
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

},{}],6:[function(require,module,exports){
"use strict";

var _handleDom = require("./handle-dom");

var stopEventPropagation = _handleDom.stopEventPropagation;
var fireClick = _handleDom.fireClick;

var setFocusStyle = require("./handle-swal-dom").setFocusStyle;

var handleKeyDown = function handleKeyDown(event, params, modal) {
  var e = event || window.event;
  var keyCode = e.keyCode || e.which;

  var $okButton = modal.querySelector("button.confirm");
  var $cancelButton = modal.querySelector("button.cancel");
  var $modalButtons = modal.querySelectorAll("button[tabindex]");

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

    stopEventPropagation(e);
    $targetElement.focus();

    if (params.confirmButtonColor) {
      setFocusStyle($targetElement, params.confirmButtonColor);
    }
  } else {
    if (keyCode === 13) {
      if ($targetElement.tagName === "INPUT") {
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
      fireClick($targetElement, e);
    } else {
      // Fallback - let the browser handle it.
      $targetElement = undefined;
    }
  }
};

module.exports = handleKeyDown;

},{"./handle-dom":5,"./handle-swal-dom":7}],7:[function(require,module,exports){
"use strict";

var _interopRequire = function (obj) { return obj && obj.__esModule ? obj["default"] : obj; };

Object.defineProperty(exports, "__esModule", {
  value: true
});

var hexToRgb = require("./utils").hexToRgb;

var _handleDom = require("./handle-dom");

var removeClass = _handleDom.removeClass;
var getTopMargin = _handleDom.getTopMargin;
var fadeIn = _handleDom.fadeIn;
var show = _handleDom.show;
var addClass = _handleDom.addClass;

var defaultParams = _interopRequire(require("./default-params"));

var modalClass = ".sweet-alert";
var overlayClass = ".sweet-overlay";

/*
 * Add modal + overlay to DOM
 */

var injectedHTML = _interopRequire(require("./injected-html"));

var sweetAlertInitialize = function sweetAlertInitialize() {
  var sweetWrap = document.createElement("div");
  sweetWrap.innerHTML = injectedHTML;

  // Append elements to body
  while (sweetWrap.firstChild) {
    document.body.appendChild(sweetWrap.firstChild);
  }
};

/*
 * Get DOM element of modal
 */
var getModal = (function (_getModal) {
  var _getModalWrapper = function getModal() {
    return _getModal.apply(this, arguments);
  };

  _getModalWrapper.toString = function () {
    return _getModal.toString();
  };

  return _getModalWrapper;
})(function () {
  var $modal = document.querySelector(modalClass);

  if (!$modal) {
    sweetAlertInitialize();
    $modal = getModal();
  }

  return $modal;
});

/*
 * Get DOM element of input (in modal)
 */
var getInput = function getInput() {
  var $modal = getModal();
  if ($modal) {
    return $modal.querySelector("input");
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
  var rgbColor = hexToRgb(bgColor);
  $button.style.boxShadow = "0 0 2px rgba(" + rgbColor + ", 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)";
};

/*
 * Animation when opening modal
 */
var openModal = function openModal() {
  var $modal = getModal();
  fadeIn(getOverlay(), 10);
  show($modal);
  addClass($modal, "showSweetAlert");
  removeClass($modal, "hideSweetAlert");

  window.previousActiveElement = document.activeElement;
  var $okButton = $modal.querySelector("button.confirm");
  $okButton.focus();

  setTimeout(function () {
    addClass($modal, "visible");
  }, 500);

  var timer = $modal.getAttribute("data-timer");

  if (timer !== "null" && timer !== "") {
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

  removeClass($modal, "show-input");
  $input.value = "";
  $input.setAttribute("type", defaultParams.inputType);
  $input.setAttribute("placeholder", defaultParams.inputPlaceholder);

  resetInputError();
};

var resetInputError = function resetInputError(event) {
  // If press enter => ignore
  if (event && event.keyCode === 13) {
    return false;
  }

  var $modal = getModal();

  var $errorIcon = $modal.querySelector(".sa-input-error");
  removeClass($errorIcon, "show");

  var $errorContainer = $modal.querySelector(".sa-error-container");
  removeClass($errorContainer, "show");
};

/*
 * Set "margin-top"-property on modal based on its computed height
 */
var fixVerticalPosition = function fixVerticalPosition() {
  var $modal = getModal();
  $modal.style.marginTop = getTopMargin(getModal());
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

},{"./default-params":3,"./handle-dom":5,"./injected-html":8,"./utils":10}],8:[function(require,module,exports){
"use strict";

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

module.exports = injectedHTML;

},{}],9:[function(require,module,exports){
"use strict";

var alertTypes = ["error", "warning", "info", "success", "input", "prompt"];

var isIE8 = require("./utils").isIE8;

var _handleSwalDom = require("./handle-swal-dom");

var getModal = _handleSwalDom.getModal;
var getInput = _handleSwalDom.getInput;
var setFocusStyle = _handleSwalDom.setFocusStyle;

var _handleDom = require("./handle-dom");

var hasClass = _handleDom.hasClass;
var addClass = _handleDom.addClass;
var removeClass = _handleDom.removeClass;
var escapeHtml = _handleDom.escapeHtml;
var _show = _handleDom._show;
var show = _handleDom.show;
var _hide = _handleDom._hide;
var hide = _handleDom.hide;

/*
 * Set type, text and actions on modal
 */
var setParameters = function setParameters(params) {
  var modal = getModal();

  var $title = modal.querySelector("h2");
  var $text = modal.querySelector("p");
  var $cancelBtn = modal.querySelector("button.cancel");
  var $confirmBtn = modal.querySelector("button.confirm");

  /*
   * Title
   */
  $title.innerHTML = params.html ? params.title : escapeHtml(params.title).split("\n").join("<br>");

  /*
   * Text
   */
  $text.innerHTML = params.html ? params.text : escapeHtml(params.text || "").split("\n").join("<br>");
  if (params.text) show($text);

  /*
   * Custom class
   */
  if (params.customClass) {
    addClass(modal, params.customClass);
    modal.setAttribute("data-custom-class", params.customClass);
  } else {
    // Find previously set classes and remove them
    var customClass = modal.getAttribute("data-custom-class");
    removeClass(modal, customClass);
    modal.setAttribute("data-custom-class", "");
  }

  /*
   * Icon
   */
  hide(modal.querySelectorAll(".sa-icon"));

  if (params.type && !isIE8()) {
    var _ret = (function () {

      var validType = false;

      for (var i = 0; i < alertTypes.length; i++) {
        if (params.type === alertTypes[i]) {
          validType = true;
          break;
        }
      }

      if (!validType) {
        logStr("Unknown alert type: " + params.type);
        return {
          v: false
        };
      }

      var typesWithIcons = ["success", "error", "warning", "info"];
      var $icon = undefined;

      if (typesWithIcons.indexOf(params.type) !== -1) {
        $icon = modal.querySelector(".sa-icon." + "sa-" + params.type);
        show($icon);
      }

      var $input = getInput();

      // Animate icon
      switch (params.type) {

        case "success":
          addClass($icon, "animate");
          addClass($icon.querySelector(".sa-tip"), "animateSuccessTip");
          addClass($icon.querySelector(".sa-long"), "animateSuccessLong");
          break;

        case "error":
          addClass($icon, "animateErrorIcon");
          addClass($icon.querySelector(".sa-x-mark"), "animateXMark");
          break;

        case "warning":
          addClass($icon, "pulseWarning");
          addClass($icon.querySelector(".sa-body"), "pulseWarningIns");
          addClass($icon.querySelector(".sa-dot"), "pulseWarningIns");
          break;

        case "input":
        case "prompt":
          $input.setAttribute("type", params.inputType);
          $input.setAttribute("placeholder", params.inputPlaceholder);
          addClass(modal, "show-input");
          setTimeout(function () {
            $input.focus();
            $input.addEventListener("keyup", swal.resetInputError);
          }, 400);
          break;
      }
    })();

    if (typeof _ret === "object") {
      return _ret.v;
    }
  }

  /*
   * Custom image
   */
  if (params.imageUrl) {
    var $customIcon = modal.querySelector(".sa-icon.sa-custom");

    $customIcon.style.backgroundImage = "url(" + params.imageUrl + ")";
    show($customIcon);

    var _imgWidth = 80;
    var _imgHeight = 80;

    if (params.imageSize) {
      var dimensions = params.imageSize.toString().split("x");
      var imgWidth = dimensions[0];
      var imgHeight = dimensions[1];

      if (!imgWidth || !imgHeight) {
        logStr("Parameter imageSize expects value with format WIDTHxHEIGHT, got " + params.imageSize);
      } else {
        _imgWidth = imgWidth;
        _imgHeight = imgHeight;
      }
    }

    $customIcon.setAttribute("style", $customIcon.getAttribute("style") + "width:" + _imgWidth + "px; height:" + _imgHeight + "px");
  }

  /*
   * Show cancel button?
   */
  modal.setAttribute("data-has-cancel-button", params.showCancelButton);
  if (params.showCancelButton) {
    $cancelBtn.style.display = "inline-block";
  } else {
    hide($cancelBtn);
  }

  /*
   * Show confirm button?
   */
  modal.setAttribute("data-has-confirm-button", params.showConfirmButton);
  if (params.showConfirmButton) {
    $confirmBtn.style.display = "inline-block";
  } else {
    hide($confirmBtn);
  }

  /*
   * Custom text on cancel/confirm buttons
   */
  if (params.cancelButtonText) {
    $cancelBtn.innerHTML = escapeHtml(params.cancelButtonText);
  }
  if (params.confirmButtonText) {
    $confirmBtn.innerHTML = escapeHtml(params.confirmButtonText);
  }

  /*
   * Custom color on confirm button
   */
  if (params.confirmButtonColor) {
    // Set confirm button to selected background color
    $confirmBtn.style.backgroundColor = params.confirmButtonColor;

    // Set box-shadow to default focused button
    setFocusStyle($confirmBtn, params.confirmButtonColor);
  }

  /*
   * Allow outside click
   */
  modal.setAttribute("data-allow-outside-click", params.allowOutsideClick);

  /*
   * Callback function
   */
  var hasDoneFunction = params.doneFunction ? true : false;
  modal.setAttribute("data-has-done-function", hasDoneFunction);

  /*
   * Animation
   */
  if (!params.animation) {
    modal.setAttribute("data-animation", "none");
  } else if (typeof params.animation === "string") {
    modal.setAttribute("data-animation", params.animation); // Custom animation
  } else {
    modal.setAttribute("data-animation", "pop");
  }

  /*
   * Timer
   */
  modal.setAttribute("data-timer", params.timer);
};

module.exports = setParameters;

},{"./handle-dom":5,"./handle-swal-dom":7,"./utils":10}],10:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
/*
 * Allow user to pass their own params
 */
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
  return result ? parseInt(result[1], 16) + ", " + parseInt(result[2], 16) + ", " + parseInt(result[3], 16) : null;
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
    window.console.log("SweetAlert: " + string);
  }
};

/*
 * Set hover, active and focus-states for buttons 
 * (source: http://www.sitepoint.com/javascript-generate-lighter-darker-color)
 */
var colorLuminance = function colorLuminance(hex, lum) {
  // Validate hex string
  hex = String(hex).replace(/[^0-9a-f]/gi, "");
  if (hex.length < 6) {
    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  }
  lum = lum || 0;

  // Convert to decimal and change luminosity
  var rgb = "#";
  var c;
  var i;

  for (i = 0; i < 3; i++) {
    c = parseInt(hex.substr(i * 2, 2), 16);
    c = Math.round(Math.min(Math.max(0, c + c * lum), 255)).toString(16);
    rgb += ("00" + c).substr(c.length);
  }

  return rgb;
};

exports.extend = extend;
exports.hexToRgb = hexToRgb;
exports.isIE8 = isIE8;
exports.logStr = logStr;
exports.colorLuminance = colorLuminance;

},{}],11:[function(require,module,exports){
"use strict";

var _interopRequire = function (obj) { return obj && obj.__esModule ? obj["default"] : obj; };

// SweetAlert
// 2014-2015 (c) - Tristan Edwards
// github.com/t4t5/sweetalert

/*
 * jQuery-like functions for manipulating the DOM
 */

var _modulesHandleDom = require("./modules/handle-dom");

var hasClass = _modulesHandleDom.hasClass;
var addClass = _modulesHandleDom.addClass;
var removeClass = _modulesHandleDom.removeClass;
var escapeHtml = _modulesHandleDom.escapeHtml;
var _show = _modulesHandleDom._show;
var show = _modulesHandleDom.show;
var _hide = _modulesHandleDom._hide;
var hide = _modulesHandleDom.hide;
var isDescendant = _modulesHandleDom.isDescendant;
var getTopMargin = _modulesHandleDom.getTopMargin;
var fadeIn = _modulesHandleDom.fadeIn;
var fadeOut = _modulesHandleDom.fadeOut;
var fireClick = _modulesHandleDom.fireClick;
var stopEventPropagation = _modulesHandleDom.stopEventPropagation;

/*
 * Handy utilities
 */

var _modulesUtils = require("./modules/utils");

var extend = _modulesUtils.extend;
var hexToRgb = _modulesUtils.hexToRgb;
var isIE8 = _modulesUtils.isIE8;
var logStr = _modulesUtils.logStr;
var colorLuminance = _modulesUtils.colorLuminance;

/*
 *  Handle sweetAlert's DOM elements
 */

var _modulesHandleSwalDom = require("./modules/handle-swal-dom");

var sweetAlertInitialize = _modulesHandleSwalDom.sweetAlertInitialize;
var getModal = _modulesHandleSwalDom.getModal;
var getOverlay = _modulesHandleSwalDom.getOverlay;
var getInput = _modulesHandleSwalDom.getInput;
var setFocusStyle = _modulesHandleSwalDom.setFocusStyle;
var openModal = _modulesHandleSwalDom.openModal;
var resetInput = _modulesHandleSwalDom.resetInput;
var fixVerticalPosition = _modulesHandleSwalDom.fixVerticalPosition;

// Handle button events and keyboard events

var _modulesHandleClick = require("./modules/handle-click");

var handleButton = _modulesHandleClick.handleButton;
var handleConfirm = _modulesHandleClick.handleConfirm;
var handleCancel = _modulesHandleClick.handleCancel;

var handleKeyDown = _interopRequire(require("./modules/handle-key"));

// Default values

var defaultParams = _interopRequire(require("./modules/default-params"));

var setParameters = _interopRequire(require("./modules/set-params"));

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

  addClass(document.body, "stop-scrolling");
  resetInput();

  /*
   * Use argument if defined or default value from params object otherwise.
   * Supports the case where a default value is boolean true and should be
   * overridden by a corresponding explicit argument which is boolean false.
   */
  function argumentOrDefault(key) {
    var args = customizations;
    return args[key] === undefined ? defaultParams[key] : args[key];
  }

  if (customizations === undefined) {
    logStr("SweetAlert expects at least 1 attribute!");
    return false;
  }

  var params = extend({}, defaultParams);

  switch (typeof customizations) {

    // Ex: swal("Hello", "Just testing", "info");
    case "string":
      params.title = customizations;
      params.text = arguments[1] || "";
      params.type = arguments[2] || "";
      break;

    // Ex: swal({ title:"Hello", text: "Just testing", type: "info" });
    case "object":
      if (customizations.title === undefined) {
        logStr("Missing \"title\" argument!");
        return false;
      }

      params.title = customizations.title;

      for (var customName in defaultParams) {
        params[customName] = argumentOrDefault(customName);
      }

      // Show "Confirm" instead of "OK" if cancel button is visible
      params.confirmButtonText = params.showCancelButton ? "Confirm" : defaultParams.confirmButtonText;
      params.confirmButtonText = argumentOrDefault("confirmButtonText");

      // Callback function when clicking on "OK"/"Cancel"
      params.doneFunction = arguments[1] || null;

      break;

    default:
      logStr("Unexpected type of argument! Expected \"string\" or \"object\", got " + typeof customizations);
      return false;

  }

  setParameters(params);
  fixVerticalPosition();
  openModal();

  // Modal interactions
  var modal = getModal();

  /* 
   * Make sure all modal buttons respond to all events
   */
  var $buttons = modal.querySelectorAll("button");
  var buttonEvents = ["onclick", "onmouseover", "onmouseout", "onmousedown", "onmouseup", "onfocus"];
  var onButtonEvent = function (e) {
    return handleButton(e, params, modal);
  };

  for (var btnIndex = 0; btnIndex < $buttons.length; btnIndex++) {
    for (var evtIndex = 0; evtIndex < buttonEvents.length; evtIndex++) {
      var btnEvt = buttonEvents[evtIndex];
      $buttons[btnIndex][btnEvt] = onButtonEvent;
    }
  }

  // Clicking outside the modal dismisses it (if allowed by user)
  getOverlay().onclick = onButtonEvent;

  previousWindowKeyDown = window.onkeydown;

  var onKeyEvent = function (e) {
    return handleKeyDown(e, params, modal);
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
    throw new Error("userParams is required");
  }
  if (typeof userParams !== "object") {
    throw new Error("userParams has to be a object");
  }

  extend(defaultParams, userParams);
};

/*
 * Animation when closing modal
 */
sweetAlert.close = swal.close = function () {
  var modal = getModal();

  fadeOut(getOverlay(), 5);
  fadeOut(modal, 5);
  removeClass(modal, "showSweetAlert");
  addClass(modal, "hideSweetAlert");
  removeClass(modal, "visible");

  /*
   * Reset icon animations
   */
  var $successIcon = modal.querySelector(".sa-icon.sa-success");
  removeClass($successIcon, "animate");
  removeClass($successIcon.querySelector(".sa-tip"), "animateSuccessTip");
  removeClass($successIcon.querySelector(".sa-long"), "animateSuccessLong");

  var $errorIcon = modal.querySelector(".sa-icon.sa-error");
  removeClass($errorIcon, "animateErrorIcon");
  removeClass($errorIcon.querySelector(".sa-x-mark"), "animateXMark");

  var $warningIcon = modal.querySelector(".sa-icon.sa-warning");
  removeClass($warningIcon, "pulseWarning");
  removeClass($warningIcon.querySelector(".sa-body"), "pulseWarningIns");
  removeClass($warningIcon.querySelector(".sa-dot"), "pulseWarningIns");

  // Make page scrollable again
  removeClass(document.body, "stop-scrolling");

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
  var modal = getModal();

  var $errorIcon = modal.querySelector(".sa-input-error");
  addClass($errorIcon, "show");

  var $errorContainer = modal.querySelector(".sa-error-container");
  addClass($errorContainer, "show");

  $errorContainer.querySelector("p").innerHTML = errorMessage;

  modal.querySelector("input").focus();
};

/*
 * Reset input error DOM elements
 */
sweetAlert.resetInputError = swal.resetInputError = function (event) {
  // If press enter => ignore
  if (event && event.keyCode === 13) {
    return false;
  }

  var $modal = getModal();

  var $errorIcon = $modal.querySelector(".sa-input-error");
  removeClass($errorIcon, "show");

  var $errorContainer = $modal.querySelector(".sa-error-container");
  removeClass($errorContainer, "show");
};

/*
 * Use SweetAlert with RequireJS
 */
if (typeof define === "function" && define.amd) {
  define(function () {
    return sweetAlert;
  });
} else if (typeof window !== "undefined") {
  window.sweetAlert = window.swal = sweetAlert;
} else if (typeof module !== "undefined" && module.exports) {
  module.exports = sweetAlert;
}

},{"./modules/default-params":3,"./modules/handle-click":4,"./modules/handle-dom":5,"./modules/handle-key":6,"./modules/handle-swal-dom":7,"./modules/set-params":9,"./modules/utils":10}]},{},[1])