/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/courier.js":
/*!******************************!*\
  !*** ./assets/js/courier.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _frontend_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./frontend/core */ "./assets/js/frontend/core.js");
/* harmony import */ var _frontend_dismiss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./frontend/dismiss */ "./assets/js/frontend/dismiss.js");
/* harmony import */ var _frontend_modal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./frontend/modal */ "./assets/js/frontend/modal.js");




jquery__WEBPACK_IMPORTED_MODULE_0___default()(function () {
  Object(_frontend_core__WEBPACK_IMPORTED_MODULE_1__["default"])();
  Object(_frontend_dismiss__WEBPACK_IMPORTED_MODULE_2__["default"])();
  Object(_frontend_modal__WEBPACK_IMPORTED_MODULE_3__["default"])();
});

/***/ }),

/***/ "./assets/js/frontend/cookie.js":
/*!**************************************!*\
  !*** ./assets/js/frontend/cookie.js ***!
  \**************************************/
/*! exports provided: getItem, setItem, hasItem, removeItem, keys, clear */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getItem", function() { return getItem; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setItem", function() { return setItem; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasItem", function() { return hasItem; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeItem", function() { return removeItem; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "keys", function() { return keys; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "clear", function() { return clear; });
var _this = undefined;

var getItem = function getItem(sKey) {
  if (!sKey) {
    return null;
  }

  return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
};

var setItem = function setItem(sKey, sValue, vEnd, sPath, sDomain, bSecure) {
  if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
    return false;
  }

  if (!sPath) {
    sPath = '/';
  }

  var sExpires = "";

  if (vEnd) {
    switch (vEnd.constructor) {
      case Number:
        sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
        break;

      case String:
        sExpires = "; expires=" + vEnd;
        break;

      case Date:
        sExpires = "; expires=" + vEnd.toUTCString();
        break;
    }
  }

  document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
  return true;
};

var removeItem = function removeItem(sKey, sPath, sDomain) {
  if (!_this.hasItem(sKey)) {
    return false;
  }

  document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
  return true;
};

var hasItem = function hasItem(sKey) {
  if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
    return false;
  }

  return new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=").test(document.cookie);
};

var keys = function keys() {
  var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, '').split(/\s*(?:\=[^;]*)?;\s*/);

  for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
    aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
  }

  return aKeys;
};

var clear = function clear(sPath, sDomain) {
  var aKeys = _this.keys();

  for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
    _this.removeItem(aKeys[nIdx], sPath, sDomain);
  }
};



/***/ }),

/***/ "./assets/js/frontend/core.js":
/*!************************************!*\
  !*** ./assets/js/frontend/core.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return core; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");


var $ = jquery__WEBPACK_IMPORTED_MODULE_0___default.a;
function core() {
  /**
   * Check to make sure the dom is ready before we display any notices
   * @since 1.0.5
   *
   * @param callback
   */
  function domReady(callback) {
    document.readyState === "interactive" || document.readyState === "complete" ? callback() : document.addEventListener("DOMContentLoaded", callback);
  }
  /**
   * Initialize the core display of notices
   *
   * @since 1.0.0
   */


  function init() {
    var $notice_container = $('.courier-notices[data-courier-ajax="true"]');
    $notice_container.attr('data-loaded', false); // If no notice containers expecting ajax, die early.

    if ($notice_container.length === 0) {
      return;
    }

    var courierContainers = document.querySelectorAll('.courier-notices[data-courier-ajax="true"]');
    var observer = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.intersectionRatio === 1 && 'false' === entry.target.getAttribute('data-loaded')) {
          var settings = {
            contentType: "application/json",
            placement: entry.target.getAttribute('data-courier-placement'),
            format: 'html',
            post_info: {}
          };

          if (typeof courier_data.post_info !== 'undefined') {
            settings.post_info = courier_data.post_info;
          } // let data = $.extend( {}, courier_data.post_info, settings );


          var dismissed_notice_ids = Object(_cookie__WEBPACK_IMPORTED_MODULE_1__["getItem"])('dismissed_notices');
          dismissed_notice_ids = JSON.parse(dismissed_notice_ids);
          dismissed_notice_ids = dismissed_notice_ids || [];
          $.ajax({
            method: 'GET',
            beforeSend: function beforeSend(xhr) {
              xhr.setRequestHeader('X-WP-Nonce', courier_data.wp_rest_nonce);
            },
            'url': courier_data.notices_endpoint,
            'data': settings
          }).success(function (response) {
            if (response.notices) {
              $.each(response.notices, function (index) {
                // If the notice is dismissed don't show it.
                if (dismissed_notice_ids.indexOf(parseInt(index)) !== -1) {
                  return;
                }

                var $notice = $(response.notices[index]).hide();
                $('.courier-notices[data-courier-placement="' + settings.placement + '"]').append($notice);
                $notice.slideDown('fast');
              });
            }
          });
          entry.target.setAttribute('data-loaded', true);
        }
      });
    }, {
      threshold: 1
    });
    Array.prototype.forEach.call(courierContainers, function (element) {
      observer.observe(element);
    });
  }

  domReady(init);
}

/***/ }),

/***/ "./assets/js/frontend/dismiss.js":
/*!***************************************!*\
  !*** ./assets/js/frontend/dismiss.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return dismiss; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");


var $ = jquery__WEBPACK_IMPORTED_MODULE_0___default.a;
function dismiss() {
  var $body = $('body'),
      $notices;
  init();
  /**
   * Initialize our dismiss
   * Add our events
   */

  function init() {
    $body.on('click', '.courier-close, .trigger-close', closeClick);
  }
  /**
   * Utility wrapper to block clicks.
   *
   * Grab the data-courier-notice-id from current notice.
   *
   * @param event
   */


  function closeClick(event) {
    var $this = $(this),
        href = $this.attr('href');

    if (true !== $this.data('dismiss')) {
      event.preventDefault();
      event.stopPropagation(); // Store that our notice should be dismissed
      // This will stop an infinite loop.

      $this.data('dismiss', true);
      var $notice = $this.parents('.courier-notice');
      var notice_id = parseInt($notice.data('courier-notice-id'), 10);

      if (0 === $notice.length || isNaN(notice_id)) {
        return;
      } // Only pass an ajax call if the user has an ID


      if (courier_data.user_id) {
        $.post({
          url: courier_data.notice_endpoint + notice_id + '/dismiss',
          data: {
            'dismiss_nonce': courier_data.dismiss_nonce,
            'user_id': courier_data.user_id
          },
          beforeSend: function beforeSend(request) {
            request.setRequestHeader('X-WP-Nonce', courier_data.wp_rest_nonce);
          }
        }).done(function () {
          $notice.find('.courier-close').trigger('click');
          hideNotice(notice_id);
        });
      } else {
        $notice.find('.courier-close').trigger('click');
        hideNotice(notice_id);
      }

      if (href && href !== '#') {
        $(document).ajaxComplete(function (event, request, settings) {
          window.location = href;
        });
      }
    }
  }
  /**
   * Hide the notice so it is not longer visible.
   *
   * @param notice_id
   */


  function hideNotice(notice_id) {
    $(".courier_notice[data-courier-notice-id='" + notice_id + "']").fadeOut();
    $('.courier-modal-overlay').hide();
    setCookie(notice_id);
  }
  /**
   * Send all the notices in a comma delimited list.
   *
   * @param event
   */


  function close_all_click(event) {
    var $this = $(this);

    if (true !== $this.data('dismiss')) {
      event.preventDefault();
      event.stopPropagation(); // Store that our notice should be dismissed
      // This will stop an infinite loop.

      $this.data('dismiss', true);
      $notices = $('.courier-notices').find('.courier-notice');
      var notice_ids = $notices.data('courier-notice-id');
      ajax(notice_ids.join(','));
    }
  }
  /**
   * Call out to our ajax endpoint pass either a single id or a comma
   * delimited list of id
   *
   * @param notice_ids example 1 or 1,2,3
   */


  function ajax(notice_ids) {
    $.get(courier_data.endpoint + notice_ids + '/').done(function () {
      $notices.find('.courier-close').trigger('click');
      notice_ids = String(notice_ids).split(',');
      $.each(notice_ids, function (index, value) {
        $(".courier_notice[data-courier-notice-id='" + value + "']").fadeOut();
        $('.courier-modal-overlay').hide();
        setCookie(value);
      });
    });
  }

  function setCookie(notice_id) {
    var notice_ids = Object(_cookie__WEBPACK_IMPORTED_MODULE_1__["getItem"])('dismissed_notices');
    notice_ids = JSON.parse(notice_ids);
    notice_ids = notice_ids || []; // Add to array if not already in there

    if (notice_ids.indexOf(notice_id) === -1) {
      notice_ids.push(notice_id);
    }

    Object(_cookie__WEBPACK_IMPORTED_MODULE_1__["setItem"])('dismissed_notices', JSON.stringify(notice_ids));
  }
}

/***/ }),

/***/ "./assets/js/frontend/modal.js":
/*!*************************************!*\
  !*** ./assets/js/frontend/modal.js ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return modal; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");


var $ = jquery__WEBPACK_IMPORTED_MODULE_0___default.a;
function modal() {
  var $doc = $(document),
      $body = $('body'),
      $window = $(window);

  var init = function init() {
    window.onload = display_modal;
  };
  /**
   * Shows the modal (if there is one) after the page is fully loaded
   */


  var display_modal = function display_modal() {
    var settings = {
      contentType: "application/json",
      placement: 'popup-modal',
      format: 'html',
      post_info: {}
    };
    var modalContainer = document.querySelector('.courier-notices.courier-location-popup-modal[data-courier-ajax="true"]'); // If no modal container die early.

    if (!modalContainer) {
      return;
    }

    if (typeof courier_data.post_info !== 'undefined') {
      settings.post_info = courier_data.post_info;
    }

    var dismissed_notice_ids = Object(_cookie__WEBPACK_IMPORTED_MODULE_1__["getItem"])('dismissed_notices');
    dismissed_notice_ids = JSON.parse(dismissed_notice_ids);
    dismissed_notice_ids = dismissed_notice_ids || [];
    $.ajax({
      method: 'GET',
      beforeSend: function beforeSend(xhr) {
        xhr.setRequestHeader('X-WP-Nonce', courier_data.wp_rest_nonce);
      },
      'url': courier_data.notices_endpoint,
      'data': settings
    }).success(function (response) {
      if (response.notices) {
        $.each(response.notices, function (index) {
          // If the notice is dismissed don't show it.
          if (dismissed_notice_ids.indexOf(parseInt(index)) !== -1) {
            return;
          }

          var $notice = $(response.notices[index]).hide();
          $('.courier-notices[data-courier-placement="' + settings.placement + '"]').append($notice);
          $notice.slideDown('fast');
        });
      }
    }); // .target.setAttribute( 'data-loaded', true );

    $('.modal_overlay').show();
  };

  init();
}

/***/ }),

/***/ 0:
/*!************************************!*\
  !*** multi ./assets/js/courier.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/aware/vvv/www/couriier/public_html/wp-content/plugins/courier/assets/js/courier.js */"./assets/js/courier.js");


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=courier.js.map