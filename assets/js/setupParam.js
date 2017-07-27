'use strict';


var paths = {
    main: './app/Controllers/mainInfoLoader.php?method=',
    reportEditor: './app/Controllers/reportEditeTools.php?method='
  },
  firstRun = true;

Object.prototype.parsetoJSON = function() {
  if (typeof this == 'object') {
    return JSON.stringify(this);
  } else throw 'This val is not Object!';

}

Object.defineProperty(Object.prototype, 'parsetoJSON', {
  enumerable: false
});
