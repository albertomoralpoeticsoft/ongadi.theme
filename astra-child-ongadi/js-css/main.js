/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/app/flickitygallery.js":
/*!************************************!*\
  !*** ./src/app/flickitygallery.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// https://github.com/metafizzy/flickity
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  var $slidergallery = $('.wp-block-gallery.slider');
  if ($slidergallery.length) {
    $slidergallery.each(function () {
      var $this = $(this);
      $this.flickity({
        autoPlay: true,
        prevNextButtons: false,
        wrapAround: true,
        pageDots: false
      });
      window.addEventListener('resize', function () {
        $this.flickity('resize');
      });
    });
  }
});

/***/ }),

/***/ "./src/app/flickityhero.js":
/*!*********************************!*\
  !*** ./src/app/flickityhero.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// https://github.com/metafizzy/flickity

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  var $sliderhero = $('.wp-block-group.slider .wp-block-group__inner-container');
  if (!$sliderhero.length) {
    $sliderhero = $('.wp-block-group.slider');
  }
  if ($sliderhero.length) {
    $sliderhero.each(function () {
      var $this = $(this);
      $this.flickity({
        autoPlay: false,
        prevNextButtons: false,
        wrapAround: true,
        pageDots: false,
        friction: 0.5
      });
      window.addEventListener('resize', function () {
        $this.flickity('resize');
      });
      if ($this.hasClass('autoplay')) {
        setInterval(function () {
          $this.flickity('next', true, false);
        }, 6000);
      }
      $this.flickity('resize');
    });
  }
});

/***/ }),

/***/ "./src/app/inviewport.js":
/*!*******************************!*\
  !*** ./src/app/inviewport.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  var lastScrollTop = 0;
  var inViewport = function inViewport(e) {
    var b = e.get(0).getBoundingClientRect();
    return !(b.top > window.innerHeight || b.bottom < 0);
  };
  var checkInViewport = function checkInViewport(els) {
    els.each(function () {
      var $this = $(this);
      if (inViewport($this)) {
        $this.removeClass('ps-not-in-viewport');
        $this.removeClass('ps-in-viewport');
        $this.addClass('ps-in-viewport');
      } else {
        $this.removeClass('ps-in-viewport');
        $this.removeClass('ps-not-in-viewport');
        $this.addClass('ps-not-in-viewport');
      }
    });
  };
  function init() {
    var els = $('.ps-visual');
    if (els.length) {
      checkInViewport(els);
      document.addEventListener('scroll', function () {
        var st = window.pageYOffset || document.documentElement.scrollTop;
        // const scrollDir = st > lastScrollTop ? 'down' : 'up'

        lastScrollTop = st <= 0 ? 0 : st;
        checkInViewport(els);
      });
    }
  }
  init();
});

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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************!*\
  !*** ./src/app/main.js ***!
  \*************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _inviewport__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./inviewport */ "./src/app/inviewport.js");
/* harmony import */ var _flickitygallery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./flickitygallery */ "./src/app/flickitygallery.js");
/* harmony import */ var _flickityhero__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./flickityhero */ "./src/app/flickityhero.js");



(function ($) {
  (0,_inviewport__WEBPACK_IMPORTED_MODULE_0__["default"])($);
  (0,_flickitygallery__WEBPACK_IMPORTED_MODULE_1__["default"])($);
  (0,_flickityhero__WEBPACK_IMPORTED_MODULE_2__["default"])($);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map