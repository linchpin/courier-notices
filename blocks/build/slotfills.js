/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/courier-notices/controls.js":
/*!************************************************!*\
  !*** ./src/blocks/courier-notices/controls.js ***!
  \************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   withCourierNoticesQueryControls: function() { return /* binding */ withCourierNoticesQueryControls; }
/* harmony export */ });
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _filter__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./filter */ "./src/blocks/courier-notices/filter.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);





const withCourierNoticesQueryControls = BlockEdit => props => {
  const isCourierNoticesQueryLoopVariation = props => {
    const {
      attributes: {
        namespace
      }
    } = props;
    return namespace === 'courier/courier-notices';
  };
  return isCourierNoticesQueryLoopVariation(props) ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(BlockEdit, {
      ...props
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_filter__WEBPACK_IMPORTED_MODULE_3__["default"], {
          ...props
        })
      })
    })]
  }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(BlockEdit, {
    ...props
  });
};
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__.addFilter)('editor.BlockEdit', 'core/query', withCourierNoticesQueryControls);

/***/ }),

/***/ "./src/blocks/courier-notices/filter.js":
/*!**********************************************!*\
  !*** ./src/blocks/courier-notices/filter.js ***!
  \**********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const CourierNoticeFilter = ({
  attributes,
  setAttributes
}) => {
  const {
    query
  } = attributes;
  const postId = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => select('core/editor').getCurrentPostId());
  if (!postId) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Spinner, {})
    });
  }
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    setAttributes({
      query: {
        ...query,
        parents: [postId]
      },
      parentId: postId
    });
  }, [postId]);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
    children: JSON.stringify(attributes)
  });
};
/* harmony default export */ __webpack_exports__["default"] = (CourierNoticeFilter);

/***/ }),

/***/ "./src/blocks/courier-notices/index.js":
/*!*********************************************!*\
  !*** ./src/blocks/courier-notices/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_logo_mark__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../components/logo-mark */ "./src/components/logo-mark.js");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _controls__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./controls */ "./src/blocks/courier-notices/controls.js");



 // Load our controls

const VARIATION_NAME = 'courier/courier-notices';
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__.registerBlockVariation)('core/query', {
  name: VARIATION_NAME,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Courier Notices', 'courier-notices'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Displays the Child pages of a proposal.', 'courier-notices'),
  isActive: ({
    namespace,
    query
  }) => {
    return namespace === VARIATION_NAME && query.postType === 'courier_notice';
  },
  isCourierNoticesQueryLoopVariation: ({
    namespace,
    query
  }) => {
    return namespace === VARIATION_NAME;
  },
  icon: _components_logo_mark__WEBPACK_IMPORTED_MODULE_1__["default"],
  attributes: {
    namespace: VARIATION_NAME,
    query: {
      perPage: 100,
      pages: 0,
      paged: 1,
      offset: 0,
      postType: 'courier_notice',
      orderBy: 'menu_order',
      order: 'asc',
      author: '',
      search: '',
      exclude: [],
      sticky: '',
      inherit: false
    },
    parentID: null
  },
  scope: ['inserter'],
  innerBlocks: [['core/post-template', {}, [['core/post-content']]]],
  allowedControls: ['postType', 'search', 'taxQuery', 'filters'],
  allowedBlocks: ["courier/courier-notices"]
});

/***/ }),

/***/ "./src/components/logo-mark.js":
/*!*************************************!*\
  !*** ./src/components/logo-mark.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);


const LogoMark = () => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, {
    x: "0px",
    y: "0px",
    viewBox: "0 0 256 256",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
      fill: "white",
      d: "M170.388,202.971l34.026,28.796l-12.598,12.598c-14.639,14.637-21.192,8.085-29.276,0c-8.085-8.084-8.244-14.847,0-29.276 L170.388,202.971z M11.288,199.816c11.879,11.879,21.509,21.509,43.017,0l52.224-52.225c-5.893-5.692-11.638-11.45-17.153-17.239 c-13.421-14.089-23.842-25.941-31.63-35.987l-46.458,62.435C-6.635,181.891-0.59,187.936,11.288,199.816z M128.221,153.821 l83.483,70.654l35.605-35.604c14.638-14.638,8.085-21.191,0-29.276l0,0c-8.085-8.085-13.509-9.966-29.276,0l-20.264,13.124 l-38.568-45.518c-5.103-5.645-10.317-11.196-15.632-16.65l53.614-53.614c21.509-21.508,11.88-31.138,0-43.017 c-11.878-11.878-17.155-17.155-43.016,0L88.761,61.161l-0.005,0.006c-4.288-3.173-8.088-5.715-11.467-7.713l24.065-24.065 c2.204-2.204,2.204-5.776,0-7.981c-2.203-2.203-5.776-2.203-7.98,0L66.43,48.353c-6.469-2.013-10.484-0.864-13.241,1.893 c-2.757,2.sdsd757-3.905,6.772-1.893,13.241L24.468,90.316c-2.204,2.203-2.204,5.777,0,7.981c2.203,2.203,5.776,2.203,7.98,0 L56.4,74.344c1.747,2.95,3.903,6.217,6.531,9.848c7.739,10.447,19.115,23.568,33.886,39.074 C106.683,133.619,117.248,143.899,128.221,153.821z"
    })
  });
};
/* harmony default export */ __webpack_exports__["default"] = (LogoMark);

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ (function(module) {

module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["primitives"];

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
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _blocks_courier_notices__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./blocks/courier-notices */ "./src/blocks/courier-notices/index.js");
// import 'slotfills';

}();
/******/ })()
;
//# sourceMappingURL=slotfills.js.map