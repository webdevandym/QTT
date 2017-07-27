'use strict';

function CalendarTable(aDate, weekdays) {
  this.getLastDayMonth = function () {
    var end = new Date(aDate.getFullYear(), aDate.getMonth() + 1, 0);
    return end.getDate();
  };
  this.getStartWeekday = function () {
    var start = new Date(aDate.getFullYear(), aDate.getMonth(), 1);
    var wd = start.getDay();
    return wd === 0 ? 7 : wd;
  };
  this.getDays = function () {
    var days = [];
    for (var i = 1; i <= this.getLastDayMonth(); i++) {
      days.push(i);
    }return days;
  };

  function emptyTD(td, d, days, ck, way) {

    if (days.length == 0 && ck != 'empty') {

      if (!way) {
        var end = new Date(aDate.getFullYear(), aDate.getMonth(), 0);
        var lastDay = end.getDate();
        td.innerHTML = lastDay - d;
        td.setAttribute('data-month', aDate.getMonth());
        td.setAttribute('month', 'previous');
      } else {
        td.innerHTML = d;
        td.setAttribute('data-month', aDate.getMonth() + 2);
        td.setAttribute('month', 'next');
      }
    } else {
      td.innerHTML = '-';
    }

    td.className = 'other';
  }

  var today = aDate;

  function fillTD(td, day) {
    td.innerHTML = day;
    if (aDate.getFullYear() == today.getFullYear() && aDate.getMonth() == today.getMonth() && day == today.getDate()) {
      td.className = 'today';
    }
  }
  this.getTable = function () {
    var table = document.createElement('table');
    table.id = 'table-c';
    table.className = 'table table-bordered text-center';
    var tr = document.createElement('tr');
    var i;
    for (i = 0; i < weekdays.length; i++) {
      var th = document.createElement('th');
      th.innerHTML = weekdays[i];
      tr.appendChild(th);
    }
    table.appendChild(tr);
    var getStartWeekday = this.getStartWeekday();
    var days = this.getDays();
    var td;
    tr = document.createElement('tr');
    for (i = 1; i <= 7; i++) {
      td = document.createElement('td');
      if (i < getStartWeekday) {
        emptyTD(td, -i + getStartWeekday - 1, [], tr.getAttribute('class'), false);
      } else {
        fillTD(td, days.shift());
      }
      tr.appendChild(td);
    }
    table.appendChild(tr);
    for (var row = 1; row <= 5; row++) {
      var j = 1;
      if (days.length == 0) {
        // tr.setAttribute('class', 'empty')
        continue;
      }
      tr = document.createElement('tr');
      for (i = 0; i < 7; i++) {
        td = document.createElement('td');
        if (days.length > 0) {
          fillTD(td, days.shift());
        } else {
          emptyTD(td, j++, days, tr.getAttribute('class'), true);
        }
        tr.appendChild(td);
      }
      table.appendChild(tr);
    }
    return table;
  };
}

function Calendar(date) {

  var locale = 'en' || window.navigator.language;

  function getLocalYearMonth(d) {
    return d.toLocaleDateString(locale, {
      year: 'numeric',
      month: 'long'
    });
  }

  function getFirstMonday() {
    var day = new Date();
    var weekday = day.getDay();
    var diff = day.getDate() - weekday + (weekday === 0 ? -6 : 1);
    day.setDate(diff);
    return day;
  }

  function getWeekdays() {

    var day = getFirstMonday();
    var days = [];

    function getLocalDay(num, day) {
      var wd = new Date(day);
      wd.setDate(day.getDate() + num);
      return wd.toLocaleDateString(locale, {
        weekday: 'short'
      });
    }

    for (var i = 0; i < 7; i++) {
      days.push(getLocalDay(i, day));
    }
    return days;
  }

  function createButton(html) {
    var btn = document.createElement('button');
    btn.className = 'btn btn-success btn-sm';
    btn.innerHTML = html;
    return btn;
  }

  var weekdays = getWeekdays();

  function getCalendar() {

    var container = document.createElement('div');
    container.id = 'calendar-box';
    container.setAttribute('data-year', date.getFullYear());
    container.setAttribute('data-month', date.getMonth());

    var tableNav = document.createElement('table');
    tableNav.id = 'table-nc';
    tableNav.className = 'table text-center';
    var trNav = document.createElement('tr');

    var tdTitle = document.createElement('td');
    var title = document.createElement('h4');
    title.id = 'c-title';
    title.innerHTML = getLocalYearMonth(date);
    tdTitle.appendChild(title);

    var tdPrev = document.createElement('td');
    var btnPrev = createButton('<i class="fa fa-arrow-left" aria-hidden="true"></i>');
    tdPrev.appendChild(btnPrev);

    var tdNext = document.createElement('td');
    var btnNext = createButton('<i class="fa fa-arrow-right" aria-hidden="true"></i>');
    tdNext.appendChild(btnNext);

    trNav.appendChild(tdPrev);
    trNav.appendChild(tdTitle);
    trNav.appendChild(tdNext);
    tableNav.appendChild(trNav);
    container.appendChild(tableNav);

    var c_table = new CalendarTable(date, weekdays);
    container.appendChild(c_table.getTable());
    btnNext.addEventListener('click', getNextCalendar, false);
    btnPrev.addEventListener('click', getPrevCalendar, false);
    return container;
  }

  function switchCalendar(next) {

    $('#table-c td').off();

    var container = document.getElementById('calendar-box');
    var year = Number(container.getAttribute('data-year'));
    var month = Number(container.getAttribute('data-month')) + (next === true ? 1 : -1);

    var nDate = new Date(year, month, 1);
    container.setAttribute('data-year', nDate.getFullYear());
    container.setAttribute('data-month', nDate.getMonth());
    var oC = document.getElementById('table-c');
    var c_table = new CalendarTable(nDate, weekdays);
    container.replaceChild(c_table.getTable(), oC);
    var title = document.getElementById('c-title');
    title.innerHTML = getLocalYearMonth(nDate);

    getDateForDB(nDate, todayReruner);
  }

  function getNextCalendar() {
    return switchCalendar(true);
  }

  function getPrevCalendar() {
    return switchCalendar(false);
  }

  return getCalendar();
}

function renderCalendar(date, func) {
  var date = typeof date == 'undefined' ? new Date() : new Date(date);
  var calendar = Calendar(date);
  if (document.getElementById('calendar')) {
    $('#calendar div#calendar-box').remove();
    document.getElementById('calendar').appendChild(calendar);
  }

  getDateForDB(date);
  if (typeof func == 'function') {
    func.call();
  }
};

function momentShort(date, weekday) {
  var ndate = moment(date);

  if (weekday) {
    ndate.isoWeekday(weekday);
  }

  return ndate.format().split('T')[0];
}