"use strict";

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

// возвращает cookie с именем name, если есть, если нет, то undefined
var getCookie = function getCookie(name) {
  var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));

  return matches ? decodeURIComponent(matches[1]) : undefined;
},

//function - render web page dynamic
renderWeb = function renderWeb(page, refresh) {

  if (refresh) {
    page = $("meta[name='curentPage']").attr('value');
  } else {
    $("meta[name='curentPage']").attr('value', page || 'timeSheet');
  }

  $.get('web/render.php?q=' + page, function (data) {
    $('.inputHere').html(data);
  }).done(function () {

    putData(function () {
      timeSheetRunEvent(page);
    });

    $('#programmerName').change(function () {
      getCurentReport($(this).val(), 'getCalendar');
    });
  });
},
    refrSwitch = function refrSwitch() {
  renderWeb(null, true);
},
    timeSheetRunEvent = function timeSheetRunEvent(page) {

  if (!(page == 'timeSheet' || !page)) return;

  getCurentReport(getCookie('user'), '');
  renderCalendar();

  var reportConf = [['#hoursJob', '#descr'], ['', '#descLegendEdit'], ['#jobType_m', '#descLegendEdit']],
      repTool = new ReportEditTool(reportConf);
  repTool.runAllEvent();

  $('#jobType').watchFromJobType(['#objectType', '#objName']);
  extrafunc();
},
    printWeekDate = function printWeekDate(st, end, width, notoday) {
  if (!st || !end) throw "Start or End date of week incorect. Please check! Functuion printWeekDate()";

  $('#weekSEchk').val(st.substr(5) + ', ' + end.substr(5));

  var monthsFull = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      stday = st.split('-'),
      endday = end.split('-'),
      month = void 0;

  width && width.toLowerCase() == 'short' ? month = monthsShort : month = monthsFull;

  if (!notoday) {
    var $today = '<div id = "today" title = "Today!"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></div>';
    $('#today').remove();
    $('#calendar').append($today);
  }
  $('#weekSE').remove();
  $('#calendar').append('<div id = "weekSE">' + stday[2] + '  ' + month[stday[1] - 1] + '<i class="fa fa-arrows-h" aria-hidden="true"></i>' + endday[2] + '  ' + month[endday[1] - 1] + '</div>');
},
    loadClock = function loadClock(user) {

  function addZero(i) {
    if (i < 10) {
      i = "0" + i;
    }
    return i;
  }

  function O(i) {
    return (typeof i === "undefined" ? "undefined" : _typeof(i)) == 'object' ? i : document.getElementById(i);
  }

  var date = new Date(),
      userName = user,
      canvas = O('time'),
      context = canvas.getContext('2d');
  context.clearRect(0, 0, canvas.width, canvas.height);

  context.textBaseline = 'top';
  context.shadowColor = '#999';
  context.shadowBlur = 6;
  context.shadowOffsetX = 2;
  context.shadowOffsetY = 4;
  var color = '#2ea04d',
      marginTop = typeof InstallTrigger !== 'undefined' ? 20 : 15,
      paddingLeft = typeof InstallTrigger !== 'undefined' ? 140 : 135;

  context.fillStyle = color;
  context.font = 'bold 15px "PT Sans"';
  context.fillText(date.toJSON().split("T")[0] + " " + addZero(date.getHours()) + ":" + addZero(date.getMinutes()) + ":" + addZero(date.getSeconds()), 0, marginTop);
  context.fillText("  Logged user: " + userName, paddingLeft, marginTop);

  setInterval(function () {
    date = new Date();
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.font = 'bold 15px "PT Sans"';
    context.fillText(date.toJSON().split("T")[0] + " " + addZero(date.getHours()) + ":" + addZero(date.getMinutes()) + ":" + addZero(date.getSeconds()), 0, marginTop);
    context.fillText("  Logged user: " + userName, paddingLeft, marginTop);
  }, 1000);

  return true;
};

(function () {
  renderWeb();

  $(".nav li a").click(function (e) {
    e.preventDefault();
  });

  window.onload = function () {

    $ || document.write('<script src="/../assets/js/vendor/jquery-3.2.1.min.js" async defer><\/script>'); //jQuery
  };
})();