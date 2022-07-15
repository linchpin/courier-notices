/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/frontend/cookie.js":
/*!**************************************!*\
  !*** ./assets/js/frontend/cookie.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "clear": function() { return /* binding */ clear; },
/* harmony export */   "getItem": function() { return /* binding */ getItem; },
/* harmony export */   "hasItem": function() { return /* binding */ hasItem; },
/* harmony export */   "keys": function() { return /* binding */ keys; },
/* harmony export */   "removeItem": function() { return /* binding */ removeItem; },
/* harmony export */   "setItem": function() { return /* binding */ setItem; }
/* harmony export */ });
const getItem = sKey => {
  if (!sKey) {
    return null;
  }

  return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
};

const setItem = (sKey, sValue, vEnd, sPath, sDomain, bSecure) => {
  if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
    return false;
  }

  if (!sPath) {
    sPath = '/';
  }

  let sExpires = "";

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

const removeItem = (sKey, sPath, sDomain) => {
  if (!undefined.hasItem(sKey)) {
    return false;
  }

  document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
  return true;
};

const hasItem = sKey => {
  if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
    return false;
  }

  return new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=").test(document.cookie);
};

const keys = () => {
  let aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, '').split(/\s*(?:\=[^;]*)?;\s*/);

  for (let nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
    aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
  }

  return aKeys;
};

const clear = (sPath, sDomain) => {
  let aKeys = undefined.keys();

  for (let nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
    undefined.removeItem(aKeys[nIdx], sPath, sDomain);
  }
};



/***/ }),

/***/ "./assets/js/frontend/core.js":
/*!************************************!*\
  !*** ./assets/js/frontend/core.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ core; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");


let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
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
    let $notice_container = $('.courier-notices[data-courier-ajax="true"]');
    $notice_container.attr('data-loaded', false); // If no notice containers expecting ajax, die early.

    if ($notice_container.length === 0) {
      return;
    }

    let courierContainers = document.querySelectorAll('.courier-notices:not(.courier-location-popup-modal)[data-courier-ajax="true"]');
    let observer = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.intersectionRatio === 1 && 'false' === entry.target.getAttribute('data-loaded')) {
          let settings = {
            contentType: "application/json",
            placement: entry.target.getAttribute('data-courier-placement'),
            format: 'html',
            post_info: {},
            user_id: courier_notices_data.user_id !== '0' ? courier_notices_data.user_id : ''
          };

          if (typeof courier_notices_data.post_info !== 'undefined') {
            settings.post_info = courier_notices_data.post_info;
          }

          let dismissed_notice_ids = (0,_cookie__WEBPACK_IMPORTED_MODULE_1__.getItem)('dismissed_notices');
          dismissed_notice_ids = JSON.parse(dismissed_notice_ids);
          dismissed_notice_ids = dismissed_notice_ids || [];
          $.ajax({
            method: 'GET',
            beforeSend: function (xhr) {
              // only send nonce if the user is logged in.
              if (courier_notices_data.user_id !== '0') {
                xhr.setRequestHeader('X-WP-Nonce', courier_notices_data.wp_rest_nonce);
              }
            },
            'url': courier_notices_data.notices_endpoint,
            'data': settings
          }).success(function (response) {
            if (response.notices) {
              $.each(response.notices, function (index) {
                // If the notice is dismissed don't show it.
                if (dismissed_notice_ids.indexOf(parseInt(index)) !== -1) {
                  return;
                }

                let $notice = $(response.notices[index]).hide();
                $('.courier-notices[data-courier-placement="' + settings.placement + '"]').append($notice);
                $notice.slideDown('fast', 'swing', function () {
                  const event = new CustomEvent('courierNoticeDisplayed', {
                    detail: index
                  });
                  document.dispatchEvent(event);
                });
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
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ dismiss; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modal */ "./assets/js/frontend/modal.js");



let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function dismiss() {
  let $body = $('body'),
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
    let $this = $(this),
        href = $this.attr('href');

    if (true !== $this.data('dismiss')) {
      event.preventDefault();
      event.stopPropagation(); // Store that our notice should be dismissed
      // This will stop an infinite loop.

      $this.data('dismiss', true);
      let $notice = $this.parents('.courier-notice');
      let notice_id = parseInt($notice.data('courier-notice-id'), 10);

      if (0 === $notice.length || isNaN(notice_id)) {
        return;
      } // Only pass an ajax call if the user has an ID


      if (courier_notices_data.user_id && courier_notices_data.user_id > 0) {
        $.post({
          url: courier_notices_data.notice_endpoint + notice_id + '/dismiss',
          data: {
            'dismiss_nonce': courier_notices_data.dismiss_nonce,
            'user_id': courier_notices_data.user_id
          },
          beforeSend: function (request) {
            request.setRequestHeader('X-WP-Nonce', courier_notices_data.wp_rest_nonce);
          }
        }).done(function () {
          hideNotice(notice_id);
        });

        if (href && href !== '#') {
          $(document).ajaxComplete(function (event, request, settings) {
            window.location = href;
          });
        }
      } else {
        hideNotice(notice_id);

        if (href && href !== '#') {
          window.location = href;
        }
      }
    }
  }
  /**
   * Hide the notice so it is not longer visible.
   *
   * @param notice_id
   */


  function hideNotice(notice_id) {
    $(".courier_notice[data-courier-notice-id='" + notice_id + "']").fadeOut(500, function () {
      if (0 === window.courier_notices_modal_notices.length) {
        $('.courier-modal-overlay').addClass('hide').hide();
      } else {
        (0,_modal__WEBPACK_IMPORTED_MODULE_2__.displayModal)(0);
      }
    });
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
    $.get(courier_notices_data.endpoint + notice_ids + '/').done(function () {
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
    let notice_ids = (0,_cookie__WEBPACK_IMPORTED_MODULE_1__.getItem)('dismissed_notices');
    notice_ids = JSON.parse(notice_ids);
    notice_ids = notice_ids || []; // Add to array if not already in there

    if (notice_ids.indexOf(notice_id) === -1) {
      notice_ids.push(notice_id);
    }

    (0,_cookie__WEBPACK_IMPORTED_MODULE_1__.setItem)('dismissed_notices', JSON.stringify(notice_ids));
  }
}

/***/ }),

/***/ "./assets/js/frontend/modal.js":
/*!*************************************!*\
  !*** ./assets/js/frontend/modal.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "displayModal": function() { return /* binding */ displayModal; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./assets/js/frontend/cookie.js");


let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
let $window = $(window);
let settings = {
  contentType: "application/json",
  placement: 'popup-modal',
  format: 'html',
  post_info: {}
};

const modal = () => {
  $window.on('load', loadModals);
};
/**
 * Shows the modal (if there is one) after the page is fully loaded
 */


const loadModals = () => {
  let modalContainer = document.querySelector('.courier-notices.courier-location-popup-modal[data-courier-ajax="true"]');
  let notices = []; // If no modal container die early.

  if (!modalContainer) {
    return;
  }

  if (typeof courier_notices_data.post_info !== 'undefined') {
    settings.post_info = courier_notices_data.post_info;
  }

  let dismissed_notice_ids = (0,_cookie__WEBPACK_IMPORTED_MODULE_1__.getItem)('dismissed_notices');
  dismissed_notice_ids = JSON.parse(dismissed_notice_ids);
  dismissed_notice_ids = dismissed_notice_ids || [];
  $.ajax({
    method: 'GET',
    beforeSend: function (xhr) {
      xhr.setRequestHeader('X-WP-Nonce', courier_notices_data.wp_rest_nonce);
    },
    'url': courier_notices_data.notices_endpoint,
    'data': settings
  }).done(function (response) {
    if (response.notices) {
      for (let notice in response.notices) {
        if (dismissed_notice_ids.indexOf(parseInt(notice)) !== -1) {
          continue;
        }

        notices.push(response.notices[notice]);
      }

      if (notices.length > 0) {
        window.courier_notices_modal_notices = notices;
        displayModal(0);
      }
    }
  });
};
/**
 * Display a modal notice by index based on the window.courier_notices_modal_notices array
 * also remove the element from the dataset.
 *
 * @since 1.3.0
 *
 * @param index
 */


function displayModal(index) {
  let $notice = $(window.courier_notices_modal_notices[index]);
  $notice.hide();

  if ($notice.length < 1) {
    return;
  }

  $('.courier-notices[data-courier-placement="' + settings.placement + '"] .courier-modal-overlay').append($notice);
  $('.courier-modal-overlay').removeClass('hide').show();
  $notice.slideDown('fast');
  window.courier_notices_modal_notices.splice(index, 1);
}
/* harmony default export */ __webpack_exports__["default"] = (modal);

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = jQuery;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!**************************************!*\
  !*** ./assets/js/courier-notices.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _frontend_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./frontend/core */ "./assets/js/frontend/core.js");
/* harmony import */ var _frontend_dismiss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./frontend/dismiss */ "./assets/js/frontend/dismiss.js");
/* harmony import */ var _frontend_modal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./frontend/modal */ "./assets/js/frontend/modal.js");




jquery__WEBPACK_IMPORTED_MODULE_0___default()(function () {
  (0,_frontend_core__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_frontend_dismiss__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_frontend_modal__WEBPACK_IMPORTED_MODULE_3__["default"])();
});
}();
/******/ })()
;
//# sourceMappingURL=courier-notices.js.map