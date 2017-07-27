'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var paths = {
  main: './app/Controllers/mainInfoLoader.php?method=',
  reportEditor: './app/Controllers/reportEditeTools.php?method='
},
    firstRun = true;

Object.prototype.parsetoJSON = function () {
  if (_typeof(this) == 'object') {
    return JSON.stringify(this);
  } else throw 'This val is not Object!';
};

Object.defineProperty(Object.prototype, 'parsetoJSON', {
  enumerable: false
});