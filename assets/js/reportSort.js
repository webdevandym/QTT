function tableSortAlg() {
  let index = 0;
  $('#tableReport th').off()
  $('#tableReport th').on('click', function() {
    var nameList = [],
      sortVal = [],
      sorto = {},
      countSkip = 0,
      tableObject = [];

    // console.log(this)

    if ($(this).html()) {
      ++index;
      ($(this).attr('class') == undefined || !$(this).attr('class')) ? $(this).attr('class', 'asc'): ($(this).attr('class') == 'asc') ? $(this).attr('class', 'desc') : $(this).attr('class', '')

      if ($(this).attr('class')) $(this).attr('sortNum', index)
      else $(this).attr('sortNum', '')

      $('#tableReport th').each(function(j, elem) {
        if ($(elem).attr('sortNum')) {
          nameList[index - $(elem).attr('sortNum')] = $(elem).attr('attr')
          sortVal[index - $(elem).attr('sortNum')] = $(elem).attr('class')
        }
      })

      $('#tableReport th').each(function(ind, elem) {
        if (!$(elem).attr('sortNum')) {
          nameList.push($(elem).attr('attr'))
          sortVal.push('skip')
          countSkip++
        }
      })

      for (var k = 0; k < nameList.length; k++) {
        if (typeof nameList[k] != 'undefined') {
          sorto[nameList[k]] = sortVal[k]
        }
      }

      if (countSkip == 9) sorto['dateJob'] = 'asc'
      sorto.content = 'skip'

      $('#tableReport tbody tr')
        .disassembleTable(['name', 'userName', 'account', 'descr', 'jobType', 'dateJob', 'hoursJob'])
        .keySort(sorto)
        .renderSortReport('#tableReport tbody')
        .querySelectorAll("tr")
        .highlightRowTable('td.' + ((typeof nameList[0] == 'undefined') ? 'dateJob' : nameList[0]));
      // console.clear()
    }
  });
  $('#tableReport tbody tr').highlightRowTable('td.dateJob')

};

Object.prototype.disassembleTable = function(columnsName) {
  let obja = [],
    counter;

  $(this).each(function(i, elem) {
    let objectRow = {},
      counter = 0;

    $(this).children().each(function(j, child) {

      if ($(this).text() != '') {
        if (columnsName[counter] == 'name') {

          objectRow[columnsName[counter]] = $(this).children('span').text() + $(this).html().split('<br>')[0]
        } else {

          objectRow[columnsName[counter]] = (parseFloat($(this).text()) && columnsName[counter] !== 'dateJob') ? parseFloat($(this).text()) : $(this).text()
        }
        counter++
      }
    })
    objectRow['content'] = '<tr id = "' + $(this).attr('id') + '">' + $(this).html() + '<\/tr>'

    obja.push(objectRow)
  })

  return obja;
};

Object.defineProperty(Object.prototype, 'disassembleTable', {
  enumerable: false
});


Object.prototype.renderSortReport = function(outTable) {

  if (typeof outTable == 'undefined') {
    throw "Out Table value empty, check input value! renderSortReport();"
  } else

  {
    let obLen = function(obj) {
      var size = 0,
        key;
      for (key in obj) {
        if (obj.hasOwnProperty(key))
          size++;
      }
      return size;
    }

    try {
      $(outTable).html('')
      for (let i = 0; i < obLen(this); i++) {
        $(outTable).append(this[i]['content'])
      }

      return document.querySelector(outTable);

    } catch (e) {
      throwError(e)
    }

  }
};

Object.defineProperty(Object.prototype, 'renderSortReport', {
  enumerable: false
});

function throwError(err) {
  throw ('Ошибка ' + err.name + ":" + err.message + "\n" + err.stack);
}

Object.prototype.highlightRowTable = function(colName) {
  let allVal = [],
    allID = [],
    result = {},
    summH = [],
    alltime = 0;

  $(this).find(colName).each(function(index, element) {
    if (colName == 'name') allVal.push($(this).children('span').text() + $(this).html().split('<br>')[0])
    else allVal.push($(this).html())
    allID.push($(this).parent('tr').attr('id'))
  })

  for (let i = 0; i < allID.length; i++) {
    summH[allVal[i]] = summH[allVal[i]] == undefined ? 0 : summH[allVal[i]];

    result[allVal[i]] = allID[i]

    if (colName == 'td.dateJob') summH[allVal[i]] += parseFloat($('#' + allID[i]).find('.hoursJob').text())
    alltime += parseFloat($('#' + allID[i]).find('.hoursJob').text());
  }

  $('head style').remove();

  $.each(result, function(index, value) {
    $('#' + value).css({
      "border-bottom": "2px solid #b5b3b3"
    });
    if (colName == 'td.dateJob') {
      let sumChar = "position: absolute;text-shadow:0px 1px 1px white;color:grey;font-size:14px;margin-top:30px;margin-left:10px;content: '" + summH[index] + " h';font-family: FontAwesome;font-style: normal;font-weight: bold;text-decoration: inherit;"
      $('<style>#' + value + ':after{' + sumChar + '}</style>').appendTo('head');
    }
  });

  let alltimeChar = alltime > 40 ? (alltime + ' h (OverTime: ' + (alltime - 40) + ' h)') : alltime + " h",
    tableOffset = $('#tableReport tbody').offset().left + $('#tableReport tbody').outerWidth() - (alltime > 40 ? 230 : 120),
    allHStyle = "position: absolute;text-shadow:0px 1px 1px white;color:grey;font-size:14px;margin-top:5px;left:" + tableOffset + "px;content:'Hours summ: " + alltimeChar + "';font-family: FontAwesome;font-style: normal;font-weight: bold;text-decoration: inherit;";


  $('<style>#tableReport tbody:after{' + allHStyle + '}</style>').appendTo('head');
  // console.log(result)

}

Object.defineProperty(Object.prototype, 'highlightRowTable', {
  enumerable: false
});

Array.prototype.keySort = function(keys) {

  keys = keys || {};

  // via
  // http://stackoverflow.com/questions/5223/length-of-javascript-object-ie-associative-array
  var obLen = function(obj) {
    var size = 0,
      key;
    for (key in obj) {
      if (obj.hasOwnProperty(key))
        size++;
    }
    return size;
  };

  // avoiding using Object.keys because I guess did it have IE8 issues?
  // else var obIx = function(obj, ix){ return Object.keys(obj)[ix]; } or
  // whatever
  var obIx = function(obj, ix) {
    var size = 0,
      key;
    for (key in obj) {
      if (obj.hasOwnProperty(key)) {
        if (size == ix)
          return key;
        size++;
      }
    }
    return false;
  };

  var keySort = function(a, b, d) {
    d = d !== null ? d : 1;
    // a = a.toLowerCase(); // this breaks numbers
    // b = b.toLowerCase();
    if (a == b)
      return 0;
    return a > b ? 1 * d : -1 * d;
  };

  var KL = obLen(keys);

  if (!KL)
    return this.sort(keySort);

  for (var k in keys) {
    // asc unless desc or skip
    keys[k] =
      keys[k] == 'desc' || keys[k] == -1 ? -1 : (keys[k] == 'skip' || keys[k] === 0 ? 0 : 1);
  }

  this.sort(function(a, b) {
    var sorted = 0,
      ix = 0;

    while (sorted === 0 && ix < KL) {
      var k = obIx(keys, ix);
      if (k) {
        var dir = keys[k];
        sorted = keySort(a[k], b[k], dir);
        ix++;
      }
    }
    return sorted;
  });
  return this;
};

var print = function(obj, delp, delo, ind) {
  delp = delp != null ? delp : ""; // property delimeter
  delo = delo != null ? delo : "\n"; // object delimeter
  ind = ind != null ? ind : " "; // indent; ind+ind geometric addition not great for deep objects
  var str = '';

  for (var prop in obj) {
    if (typeof obj[prop] == 'string' || typeof obj[prop] == 'number') {
      var q = typeof obj[prop] == 'string' ? "" : ""; // make this "'" to quote strings
      str += ind + prop + ': ' + q + obj[prop] + q + '; ' + delp;
    } else {
      str += ind + prop + ': {' + delp + print(obj[prop], delp, delo, ind + ind) + ind + '}' + delo;
    }
  }
  return str;
};
