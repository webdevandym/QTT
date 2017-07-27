function returnReportFromDB() {
  let a = [],
    obj = {},
    valList = ['name', 'startDate', 'endDate'];

  if (arguments[0].split(',')[1]) {
    a = arguments[0].split(',')
  } else {
    a = arguments;
  }

  for (let i = 0; i < a.length; i++) {
    obj[valList[i]] = a[i];
  }

  $('#tableReport th').off()
  // console.log(path)
  $.get(paths.main + 'getReportWeek&object=' + obj.parsetoJSON(), function(data) {
    if (clearSpecSymbol(data) != clearSpecSymbol($('#ReportWeek').html())) $('#ReportWeek').html(data)
  }).done(() => {
    if (document.getElementById('tableReport')) {
      $('#multiDelete').css('display', 'initial')
    } else {
      $('#multiDelete').css('display', 'none')
    }
  });



  function clearSpecSymbol(val) {
    return val.replace(/[^\w]/g, '').toLowerCase();
  }
}

function todayMonthFromCalend(obj) {
  let monthDay
  obj = obj || $('#table-c td.today');
  if (obj.attr("data-month")) {
    monthDay = parseInt(obj.attr("data-month"));
  } else {
    monthDay = (parseInt($('#calendar-box').attr('data-month')) + 1);
  }
  // console.log(monthDay);
  return monthDay;
}


function getDateForDB(dateOfWeek, func) {
  $('#table-c td').off()

  printWeekDate(momentShort(dateOfWeek, 1), momentShort(dateOfWeek, 7), null, false)

  if (typeof func == 'function') {
    func.call();
  }

  $('#table-c td').on('click', function() {
    if ($(this).html() != '-') {

      toggleSelectElemetnTable(true)

      $('#table-c td').removeClass('today');
      $(this).addClass('today')

      let date = new Date($('#calendar-box').attr('data-year') + "-" + todayMonthFromCalend() + "-" + $(this).html()),
        checkedDate = momentShort(date).substring(5),
        curentWeek = $('#weekSEchk').val().match(/\d{2}-\d{2}/g),
        startOfWeek = momentShort(date, 1),
        endOfWeek = momentShort(date, 7),
        userName = $('#programmerName').val(),
        path = "./web/forms";

      if (!(curentWeek[0] <= checkedDate && checkedDate <= curentWeek[1])) {
        printWeekDate(startOfWeek, endOfWeek, null, true)
        // console.log(startOfWeek + endOfWeek)

        returnReportFromDB(userName, startOfWeek, endOfWeek)
      }
    }
  });
}

function getCurentReport(name, date) {
  let dateEdit;

  if (date == 'getCalendar' && $('td.today').text() != '') {
    dateEdit = momentShort(new Date($('#calendar-box').attr('data-year') + " " + todayMonthFromCalend() + " " + $('td.today').html()));
  } else {
    dateEdit = momentShort(new Date());
  }

  if (!name || !dateEdit || name == undefined) throw "Check user login or date. Maybe incorect transfer. Function getCurentReport() file calendar.js"
  // date = new Date('2006-12-27').toJSON().slice(0,10)
  // console.log($('td.today').text() + dateEdit)

  var startOfWeek = momentShort(dateEdit, 1),
    endOfWeek = momentShort(dateEdit, 7),
    userName = name,
    path = "./web/forms";

  returnReportFromDB(userName, startOfWeek, endOfWeek)

};
