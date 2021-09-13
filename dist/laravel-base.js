/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/components/index.js":
/*!******************************************!*\
  !*** ./resources/js/components/index.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _notification__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./notification */ \"./resources/js/components/notification.js\");\n\ndocument.addEventListener('alpine:init', function () {\n  Alpine.data('notification', _notification__WEBPACK_IMPORTED_MODULE_0__[\"default\"]);\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY29tcG9uZW50cy9pbmRleC5qcy5qcyIsIm1hcHBpbmdzIjoiOztBQUFBO0FBRUFDLFFBQVEsQ0FBQ0MsZ0JBQVQsQ0FBMEIsYUFBMUIsRUFBeUMsWUFBTTtBQUMzQ0MsRUFBQUEsTUFBTSxDQUFDQyxJQUFQLENBQVksY0FBWixFQUE0QkoscURBQTVCO0FBQ0gsQ0FGRCIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9jb21wb25lbnRzL2luZGV4LmpzPzI0NDciXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IG5vdGlmaWNhdGlvbiBmcm9tICcuL25vdGlmaWNhdGlvbic7XG5cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2FscGluZTppbml0JywgKCkgPT4ge1xuICAgIEFscGluZS5kYXRhKCdub3RpZmljYXRpb24nLCBub3RpZmljYXRpb24pO1xufSk7XG4iXSwibmFtZXMiOlsibm90aWZpY2F0aW9uIiwiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwiQWxwaW5lIiwiZGF0YSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/components/index.js\n");

/***/ }),

/***/ "./resources/js/components/notification.js":
/*!*************************************************!*\
  !*** ./resources/js/components/notification.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (options) {\n  return {\n    notices: [],\n    visible: [],\n    timeShown: options.timeout || 5000,\n    // in ms\n    add: function add(notice) {\n      this.notices.push(notice);\n      this.fire(notice.id);\n    },\n    fire: function fire(id) {\n      var _this = this;\n\n      var notification = this.notices.find(function (notice) {\n        return notice.id === id;\n      });\n\n      if (!notification) {\n        return;\n      }\n\n      this.visible.push(notification);\n\n      if (notification.autoDismiss) {\n        setTimeout(function () {\n          _this.remove(id);\n        }, this.timeShown);\n      }\n    },\n    remove: function remove(id) {\n      this.removeFrom('visible', id);\n      this.removeFrom('notices', id);\n    },\n    removeFrom: function removeFrom(arrayName, id) {\n      var notice = this[arrayName].find(function (notice) {\n        return notice.id === id;\n      });\n      var index = this[arrayName].indexOf(notice);\n\n      if (index > -1) {\n        this[arrayName].splice(index, 1);\n      }\n    }\n  };\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY29tcG9uZW50cy9ub3RpZmljYXRpb24uanMuanMiLCJtYXBwaW5ncyI6Ijs7OztBQUFBLGlFQUFlLFVBQUNBLE9BQUQ7QUFBQSxTQUFjO0FBQ3pCQyxJQUFBQSxPQUFPLEVBQUUsRUFEZ0I7QUFFekJDLElBQUFBLE9BQU8sRUFBRSxFQUZnQjtBQUd6QkMsSUFBQUEsU0FBUyxFQUFFSCxPQUFPLENBQUNJLE9BQVIsSUFBbUIsSUFITDtBQUdXO0FBRXBDQyxJQUFBQSxHQUx5QixlQUtyQkMsTUFMcUIsRUFLYjtBQUNSLFdBQUtMLE9BQUwsQ0FBYU0sSUFBYixDQUFrQkQsTUFBbEI7QUFDQSxXQUFLRSxJQUFMLENBQVVGLE1BQU0sQ0FBQ0csRUFBakI7QUFDSCxLQVJ3QjtBQVV6QkQsSUFBQUEsSUFWeUIsZ0JBVXBCQyxFQVZvQixFQVVoQjtBQUFBOztBQUNMLFVBQU1DLFlBQVksR0FBRyxLQUFLVCxPQUFMLENBQWFVLElBQWIsQ0FBa0IsVUFBQUwsTUFBTTtBQUFBLGVBQUlBLE1BQU0sQ0FBQ0csRUFBUCxLQUFjQSxFQUFsQjtBQUFBLE9BQXhCLENBQXJCOztBQUVBLFVBQUksQ0FBRUMsWUFBTixFQUFvQjtBQUNoQjtBQUNIOztBQUVELFdBQUtSLE9BQUwsQ0FBYUssSUFBYixDQUFrQkcsWUFBbEI7O0FBRUEsVUFBSUEsWUFBWSxDQUFDRSxXQUFqQixFQUE4QjtBQUMxQkMsUUFBQUEsVUFBVSxDQUFDLFlBQU07QUFDYixlQUFJLENBQUNDLE1BQUwsQ0FBWUwsRUFBWjtBQUNILFNBRlMsRUFFUCxLQUFLTixTQUZFLENBQVY7QUFHSDtBQUNKLEtBeEJ3QjtBQTBCekJXLElBQUFBLE1BMUJ5QixrQkEwQmxCTCxFQTFCa0IsRUEwQmQ7QUFDUCxXQUFLTSxVQUFMLENBQWdCLFNBQWhCLEVBQTJCTixFQUEzQjtBQUNBLFdBQUtNLFVBQUwsQ0FBZ0IsU0FBaEIsRUFBMkJOLEVBQTNCO0FBQ0gsS0E3QndCO0FBK0J6Qk0sSUFBQUEsVUEvQnlCLHNCQStCZEMsU0EvQmMsRUErQkhQLEVBL0JHLEVBK0JDO0FBQ3RCLFVBQU1ILE1BQU0sR0FBRyxLQUFLVSxTQUFMLEVBQWdCTCxJQUFoQixDQUFxQixVQUFBTCxNQUFNO0FBQUEsZUFBSUEsTUFBTSxDQUFDRyxFQUFQLEtBQWNBLEVBQWxCO0FBQUEsT0FBM0IsQ0FBZjtBQUNBLFVBQU1RLEtBQUssR0FBRyxLQUFLRCxTQUFMLEVBQWdCRSxPQUFoQixDQUF3QlosTUFBeEIsQ0FBZDs7QUFFQSxVQUFJVyxLQUFLLEdBQUcsQ0FBQyxDQUFiLEVBQWdCO0FBQ1osYUFBS0QsU0FBTCxFQUFnQkcsTUFBaEIsQ0FBdUJGLEtBQXZCLEVBQThCLENBQTlCO0FBQ0g7QUFDSjtBQXRDd0IsR0FBZDtBQUFBLENBQWYiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY29tcG9uZW50cy9ub3RpZmljYXRpb24uanM/NDU4MCJdLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgZGVmYXVsdCAob3B0aW9ucykgPT4gKHtcbiAgICBub3RpY2VzOiBbXSxcbiAgICB2aXNpYmxlOiBbXSxcbiAgICB0aW1lU2hvd246IG9wdGlvbnMudGltZW91dCB8fCA1MDAwLCAvLyBpbiBtc1xuXG4gICAgYWRkKG5vdGljZSkge1xuICAgICAgICB0aGlzLm5vdGljZXMucHVzaChub3RpY2UpO1xuICAgICAgICB0aGlzLmZpcmUobm90aWNlLmlkKTtcbiAgICB9LFxuXG4gICAgZmlyZShpZCkge1xuICAgICAgICBjb25zdCBub3RpZmljYXRpb24gPSB0aGlzLm5vdGljZXMuZmluZChub3RpY2UgPT4gbm90aWNlLmlkID09PSBpZCk7XG5cbiAgICAgICAgaWYgKCEgbm90aWZpY2F0aW9uKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnZpc2libGUucHVzaChub3RpZmljYXRpb24pO1xuXG4gICAgICAgIGlmIChub3RpZmljYXRpb24uYXV0b0Rpc21pc3MpIHtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlKGlkKTtcbiAgICAgICAgICAgIH0sIHRoaXMudGltZVNob3duKTtcbiAgICAgICAgfVxuICAgIH0sXG5cbiAgICByZW1vdmUoaWQpIHtcbiAgICAgICAgdGhpcy5yZW1vdmVGcm9tKCd2aXNpYmxlJywgaWQpO1xuICAgICAgICB0aGlzLnJlbW92ZUZyb20oJ25vdGljZXMnLCBpZCk7XG4gICAgfSxcblxuICAgIHJlbW92ZUZyb20oYXJyYXlOYW1lLCBpZCkge1xuICAgICAgICBjb25zdCBub3RpY2UgPSB0aGlzW2FycmF5TmFtZV0uZmluZChub3RpY2UgPT4gbm90aWNlLmlkID09PSBpZCk7XG4gICAgICAgIGNvbnN0IGluZGV4ID0gdGhpc1thcnJheU5hbWVdLmluZGV4T2Yobm90aWNlKTtcblxuICAgICAgICBpZiAoaW5kZXggPiAtMSkge1xuICAgICAgICAgICAgdGhpc1thcnJheU5hbWVdLnNwbGljZShpbmRleCwgMSk7XG4gICAgICAgIH1cbiAgICB9LFxufSk7XG4iXSwibmFtZXMiOlsib3B0aW9ucyIsIm5vdGljZXMiLCJ2aXNpYmxlIiwidGltZVNob3duIiwidGltZW91dCIsImFkZCIsIm5vdGljZSIsInB1c2giLCJmaXJlIiwiaWQiLCJub3RpZmljYXRpb24iLCJmaW5kIiwiYXV0b0Rpc21pc3MiLCJzZXRUaW1lb3V0IiwicmVtb3ZlIiwicmVtb3ZlRnJvbSIsImFycmF5TmFtZSIsImluZGV4IiwiaW5kZXhPZiIsInNwbGljZSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/components/notification.js\n");

/***/ }),

/***/ "./resources/js/index.js":
/*!*******************************!*\
  !*** ./resources/js/index.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components */ "./resources/js/components/index.js");


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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/js/index.js");
/******/ 	
/******/ })()
;