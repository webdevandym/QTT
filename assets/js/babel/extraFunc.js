'use strict';

//mass delete
function extrafunc() {

  //click menu mass delete

  $('#multiDelete').on('click', function (e) {
    e.preventDefault();
    if ($('#multiReport').attr('status') == 'on') {
      disableMultiReport($('#multiReport'));
    }

    toggleSelectElemetnTable();
  });

  $('#lastReport').on('click', function (e) {
    e.preventDefault();
    getLastReport();
  });

  todayReruner();

  $('#multiReport').on('click', function (e) {
    e.preventDefault();

    if ($(this).attr('status') == 'on') {

      disableMultiReport(this);
    } else {

      $('#multiReport span').removeClass('ioDeleteOFF').addClass('ioDeleteON');
      $(this).attr('status', 'on');
      $('#table-c td').off();
      $('#nowDate').val($('#table-c .today').text());
      if ($('#multiDelete').find('span').attr('class') == 'ioDeleteON') {
        toggleSelectElemetnTable();
      }

      pickMultiReport();
    }
  });

  $('#closeError').on('click', function () {
    $(this).parent('div').css('visibility', 'hidden').removeClass('show');
  });
}

function todayReruner() {
  $('#today').off();
  $('#today').on('click', function () {

    showNowDay();
  });
}

var showNowDay = function showNowDay() {
  var nowDate = momentShort(new Date()),
      calDate = momentShort(new Date($('#calendar-box').attr('data-year') + "-" + todayMonthFromCalend() + "-" + $('#table-c td.today').html()));

  if (calDate == nowDate) {
    return showNotify('Already selected the current date!', wait);
  }

  renderCalendar(nowDate, todayReruner);
  getCurentReport($('#programmerName option:selected').val(), 'getCalendar');
  showNotify('Now is: ' + nowDate, wait);
};

function getLastReport() {

  var valStore = {
    userName: $('#programmerName option:selected').val(),
    path: './app/Controllers/extrafunc/'

  };

  $.get(valStore.path + 'getLastReport.php?name=' + valStore.userName, function (data) {
    console.log(data);
    var date = data.split(' ')[0],
        nowDate = momentShort(new Date($('#calendar-box').attr('data-year') + "-" + todayMonthFromCalend() + "-" + $('#table-c td.today').html()));

    if (nowDate != new Date(date).toJSON().split("T")[0]) {

      renderCalendar(date);
    }
  }).done(function () {
    getCurentReport(valStore.userName, 'getCalendar');
  }).done(function (data) {
    showNotify('Your last record: ' + data.split(' ')[0], wait);
  });
}

function disableMultiReport(obj) {
  $('#multiReport span').removeClass('ioDeleteON').addClass('ioDeleteOFF');
  $(obj).attr('status', 'off');
  $('#table-c td').off();
  takeMyDate();
  getDateForDB();
}

function takeMyDate() {
  $('#table-c td').removeClass('today');
  $('#table-c td').each(function () {
    if ($(this).text() == $('#nowDate').val()) {
      $(this).addClass('today');
    }
  });
}

function toggleSelectElemetnTable(way) {

  if ($('#tableReport').text() != '') {

    var fade = 'slow';
    if ($('#multiDelete').find('span').attr('class') == 'ioDeleteOFF' && !way) {

      $('#multiDelete').find('span').removeClass('ioDeleteOFF').addClass('ioDeleteON');
      $('#tableReport tbody tr').css('cursor', 'pointer');
      // $('.chekerDelete')
      fade = 0;
    } else {
      $('#tableReport tbody tr').css('cursor', 'auto');
      $('#multiDelete').find('span').removeClass('ioDeleteON').addClass('ioDeleteOFF');
    }

    if (!way) {
      $('.editRow').fadeToggle(fade);
      $('.deleteRow').fadeToggle(fade);
      $('.chekerDelete').toggle();
      $('.deleteApp').fadeToggle('slow');
    } else {
      $('.deleteApp').fadeOut('slow');
    }
  }
}

function pickMultiReport() {
  $('#table-c td').on('click', function () {
    $(this).hasClass('today') ? $(this).removeClass('today') : $(this).addClass('today');
  });
}

function tableMassDeletePicker() {
  $('#tableReport tbody tr').on('click', function () {
    var visElem = void 0;
    if ($('#multiDelete span').attr('class') == 'ioDeleteON') {
      $(this).find('td.chekerDelete i').css('visibility') == 'visible' ? visElem = 'hidden' : visElem = 'visible';
      $(this).find('td.chekerDelete i').css('visibility', visElem);
    }
  });
}

function genDeleteList() {
  var deList = '';
  $('#tableReport tr').each(function () {
    if ($(this).find('.chekerDelete i').css('visibility') == 'visible') {
      deList += $(this).attr('id').split('_')[0].split('it')[1] + ",";
    }
  });
  var record = {
    rec: deList.substring(0, deList.length - 1)
    // console.log(deList)

  };if ($('#flashSelect').hasClass('btn-info')) {
    $('#flashSelect').removeClass('btn-info').addClass('btn-primary');
  }

  $.get(paths.reportEditor + 'removeRecord&object=' + record.parsetoJSON(), function (data) {}).done(function () {
    toggleSelectElemetnTable();
    returnReportFromDB(returnDate());
  }).done(function () {
    showNotify('Deleting successful', wait);
  });

  // console.log(deList)
}

function flashSelectRecords() {
  if ($('#flashSelect').hasClass('btn-primary')) {
    $('#tableReport tr td.chekerDelete i').css('visibility', 'visible');
    $('#flashSelect').removeClass('btn-primary').addClass('btn-info');
  } else {
    $('#tableReport tr td.chekerDelete i').css('visibility', 'hidden');
    $('#flashSelect').removeClass('btn-info').addClass('btn-primary');
  }
}

String.prototype.quoteString = function (specChar) {

  var pattern = /(\\)/g;

  return this.replace(pattern, '\\$&');
};

function showNotify(text, wait) {

  window.setTimeout(function () {

    var position = $(document).height() - $(window).scrollTop() - $(window).height(),
        footerHeight = $('footer').outerHeight(true);

    $('#notifyTS').removeClass('showNotify').css({
      'bottom': position > footerHeight ? -footerHeight : -position
    });
    window.setTimeout(function () {
      $('#notifyTS').addClass('showNotify');
    }, 15);

    $('#notifyTS .text').html(text);
  }, wait);
}