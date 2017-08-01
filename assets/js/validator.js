function ValidDesc(object, legendID, hour) {
  if ($(object).val() == '' || (hour && parseFloat(object.val()) <= 0)) {

    if (legendID != '') $(legendID).css({
      'opacity': '1',
      'animation': 'h 0.5s'
    })
    $(object).addClass('incorrectInput')
    return false;

  } else {

    if (legendID != '') {
      $(legendID).css({
        'margin-left': '',
        'animation': ''
      })
      $(legendID).css('opacity', '0')
    }
    // if ($(object).val().split('::').length < 2 && $(object).attr('id') == 'descrTextTS') $(object).val(':: ' + $(object).val())
    $(object).removeClass('incorrectInput')
    return true;
  }
}

function hoursChecker(flag, object, e) {
  if (flag == 1) {
    return $(object).val($(object).val().replace(/^(?![1-9]+)0*\.+/, '0.'));
  } else {
    return e.charCode >= 48 && e.charCode <= 57 || [46, 44].indexOf(e.charCode) > -1;
  }
}


function dscrValidator(obj, valArray) {
  if (['1', '2'].indexOf($(valArray[0] + ' option:selected').val()) < 0) {
    return ValidDesc(obj, valArray[1]);
  } else {
    offValidDescr(obj, valArray[1]);
    return true;
  }
}

function offValidDescr(obj, selector) {
  $(selector).css({
    'margin-left': '',
    'animation': '',
    'opacity': '0'
  })
  $(obj).removeClass('incorrectInput')
}

function changeButton(switcher, buttonID) {
  if (switcher == 'disable') $(buttonID).addClass('disableButton')
  else $(buttonID).removeClass('disableButton')
}


function ValidReport(val, error, specval) {
  let checkTrue = true;

  checkTrue = ValidDesc(val[0], error[0]) && dscrValidator($(val[1] || '#descrTextTS'), specval || ['#jobType', '#descLegend']) && checkTrue

  return checkTrue
}

function validObjectName(object) {
  if ($('#objName option:selected').html() == '-None-' && $('#jobType option:selected').val() != '4' && $('#objectType option:selected').val() != 6) {

    $('#errorObjectName').css({
      'opacity': '1',
      'animation': 'showError 0.5s'
    })
    $('#objName').addClass('incorrectInput')
    return false;
  } else {
    $('#errorObjectName').css({
      'margin-left': '',
      'animation': ''
    })
    $('#errorObjectName').css('opacity', '0')
    $('#objName').removeClass('incorrectInput')
    return true;
  }
}


Object.prototype.watchFromJobType = function(checkValue, specRun) {
  let disStatus,
    classDest,
    s = specRun;

  function editObjClass(role, cls) {
    if (role == 'add') {
      this.addClass(cls)
    } else {
      this.removeClass(cls)
    }
  }

  let switchSlectBlock = function(o) {
      if (['1', '2'].indexOf($(o).children('option:selected').val()) < 0) {
        disStatus = true;
        classDest = 'add';
      } else {
        offValidDescr()
        disStatus = false;
        classDest = 'remove';
      }

      for (let i = 0; i < checkValue.length; i++) {
        editObjClass.call($(checkValue[i]), classDest, 'disabledElement')
        $(checkValue[i]).prop('disabled', disStatus)
      }
    },

    firstCorrectingRun = (obj, swt) => {
      if (swt) {
        let _this = obj;

        if ($(_this).children('option:selected').val() != '') {
          switchSlectBlock(_this);
        }
      }
      return true;
    },

    clThis = this;

  if (firstCorrectingRun(this, s)) {
    $(clThis).change(function() {
      switchSlectBlock(clThis);
    })
  }

  return true;
};

Object.defineProperty(Object.prototype, 'watchFromJobType', {
  enumerable: false
});

function insertNewReport() {

}

insertNewReport.prototype = {

  install: function() {
    this.exitMethod = false;
    this.multiReport = false;
    this.insertVal = {};
    this.dateEdit = {};
    return this;
  },

  valid: function() {
    if (this.exitMethod) return this;
    if (!ValidReport(['#hours'], ['#errorHour'])) {
      this.exitMethod = true
    }
    return this;
  },

  getValues: function() {

    if (this.exitMethod) return this;

    let insertVal = {},
      idStore = ['objName', 'jobType', 'programmerName', 'descrTextTS'],
      dateEdit = {}

    if ($('#multiReport').attr('status') == 'on') this.multiReport = true
    //get date

    $('#table-c td.today').each(function(i) {
      dateEdit[i] = momentShort(
        new Date(
          (($(this).text() != '') ?
            $('#calendar-box').attr('data-year') + " " + todayMonthFromCalend($(this)) + " " + $(this).html() :
            new Date()
          )))
    });


    // console.log(dateEdit);
    for (let i = 0; i < idStore.length; i++) {
      switch (idStore[i]) {
        case 'programmerName':
          insertVal[i] = $('select#' + idStore[i] + ' option:selected').attr('idnum')
          break;
        case 'descrTextTS':
          insertVal[i] = this.descriptionCreator(idStore[i]);
          // console.log(insertVal[i]);
          break;
        default:
          insertVal[i] = $('select#' + idStore[i] + ' option:selected').val()
      }

    }

    // console.log(insertVal);
    let objLeng = idStore.length;

    insertVal[objLeng] = dateEdit
    insertVal[objLeng + 1] = parseFloat($('#hours').val())
    //temp val.
    insertVal[objLeng + 2] = 5
    console.log(insertVal);

    this.dateEdit = dateEdit;
    this.insertVal = insertVal || {};

    return this;
  },

  descriptionCreator: function(key) {
    let jopTypeChk;
    if (['1', '2'].indexOf($('#jobType').val()) > -1 && $('#checkDesk').is(':checked')) {
      descr = "[" + $('#objectType option:selected').text() + ($('#objName option:selected').text().toLowerCase() !== '-none-' ? ' => ' + $('#objName option:selected').text() : '') + "]"
      jopTypeChk = jopTypeChk || true;
    }

    let descrVal = $('textarea#' + key).val().replace(/^ +|( )+$/, '').replace(/[\n\r]+$/gm, ' '),
      descr = descr || '';

    return encodeURIComponent((descr + (descrVal != '' ? ((jopTypeChk) ? "\: " : '') + descrVal : '')).quoteString());
  },

  pushData: function() {
    if (this.exitMethod) return this;

    let startOfWeek = momentShort(this.dateEdit[0], 1),
      endOfWeek = momentShort(this.dateEdit[0], 7),
      userName = $('#programmerName').val()

    $('.buttonAddReport').addClass('success')

    $.get(paths.reportEditor + 'addReport&object=' + this.insertVal.parsetoJSON(), function(data) {
        if (data) {
          showSQLError(data);
        }
      })
      .done(function() {
        returnReportFromDB(userName, startOfWeek, endOfWeek)
      })

    return this;
  },

  closeEvent: function() {
    if (this.exitMethod) return this;

    $('.buttonAddReport').addClass('success')

    this.wait(350, () => {
      $('.buttonAddReport').removeClass('success')
      $('#descrTextTS').val('')
      $('#hours').val('')
    })

    if (this.multiReport) {
      $('#table-c td').off()
      disableMultiReport('#multiReport')
      takeMyDate()
      getDateForDB()
    };

    showNotify('New record insert', 500)

    return this;
  },

  wait: (t, f) => {
    setTimeout(() => {
      f();
    }, t)
    return this;
  },
}

let initAddAlg = () => {
    let insertNew = insertNew || new insertNewReport();
    insertNew.install().valid().getValues().pushData().closeEvent();
  },

  showSQLError = (data) => {
    if (data) {
      $('.SQLerror span').html(data)
      $('.SQLerror').css('visibility', 'visible').addClass('show')
    }
  };
