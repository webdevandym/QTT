'use strict';

var ajaxObj = {},
    wait = 500;

function insertData(object) {

  var idStore = [],
      lableStore = ['Project', 'Object Type', 'Object Name', 'Programmer', 'Account', 'Description', 'Job Type', 'Job Date', 'Job Hours'],
      valueStore = [],
      startPositionCol = 0,
      innerText = $(object).parents('tr').get(0),
      parent = $(object).parents('tr'),
      specHours = '',
      valObj = {};

  $('#editModal .modal-body').html('').append("<input type='hidden' id = 'hiddenVal'><div class='custEdit'><div><span>Customer:</span><select id = 'customer'></select></div></div><div class='objectsEdit'></div>");

  $('#editModal #hiddenVal').val($(innerText).attr('id'));

  $(innerText).children('td').each(function (i, elem) {
    if ($(elem).attr('data-toggle') === 'modal' || $(elem).hasClass('chekerDelete')) {
      startPositionCol += 1;
    } else {
      idStore.push($(elem).attr('class') === 'jobType' ? $(elem).attr('class') + "_m" : $(elem).attr('class'));
    }
  });

  idStore.splice(1, 0, 'objectType_m', 'objNameTS_m');

  for (var i = startPositionCol; i < $(innerText).children().length; i++) {
    if ($(innerText).children()[i].getAttribute('class') === 'name') {
      valueStore.push($(innerText).children()[i].innerHTML.split('<')[0]);
    } else {
      valueStore.push($(innerText).children()[i].innerHTML);
    }
  }

  for (var _i = 0, j = 0; _i < idStore.length; _i++, j++) {

    if (['objectType_m', 'objNameTS_m'].indexOf(idStore[_i]) > -1) {
      $('#editModal .modal-body .objectsEdit').append("<div><span>" + lableStore[_i] + ":</span><select id = '" + idStore[_i] + "'><option selected='selected'></option></select></div>");
      j--;
      continue;
    }
    if (['name', 'userName', 'account', 'jobType_m'].indexOf(idStore[_i]) > -1) {
      var cust = idStore[_i] === 'name' ? ' .custEdit' : '';
      $('#editModal .modal-body' + cust).append("<" + (cust != '' ? 'div' : 'p') + "><span>" + lableStore[_i] + ":</span><select id = '" + idStore[_i] + "'><option selected='selected'>" + valueStore[j] + "</option></select></" + (cust != '' ? 'div' : 'p') + ">");
    } else {
      switch (idStore[_i]) {
        case 'descr':
          $('#editModal .modal-body').append("<p><span>" + lableStore[_i] + ":<label id = 'descLegendEdit'>" + $('#descLegend').html() + "</label></span><textarea type='text'" + 'onblur= "dscrValidator($(this),[\'#jobType_m\',\'#descLegendEdit\']);"' + "id='" + idStore[_i] + "'>" + valueStore[j] + "</textarea></p>");
          break;
        case 'hoursJob':
          specHours = 'onblur="ValidDesc($(this),\'\',1);" onkeypress="return hoursChecker(0,null,event)"  onkeyup = "hoursChecker(1,$(this))" maxlength="3"';
        default:
          $('#editModal .modal-body').append("<p><span>" + lableStore[_i] + ":</span><input type='text' id='" + idStore[_i] + "' value ='" + valueStore[j] + "'" + specHours + "></p>");
      }
    }
  }

  $('#objectType_m').html($('#objectType').html());
  //get customer info;
  $.get(paths.main + 'getProject&object=' + {
    query: 'Customer',
    switch: $(parent).find('td.name span').html()
  }.parsetoJSON(), function (data) {
    $('#editModal select#customer').html(data);
  }).done(function () {
    var data = new requireDATA(),
        storeobj = {
      getProject: {
        id: "name",
        obj: {
          query: $('select#customer option:selected').val(),
          switch: $('select#name option').html()
        }
      },
      getUserName: {
        id: "userName",
        obj: {
          edit: $('select#userName option').val()
        }
      }
    };

    data.runQuery(paths.main, storeobj);

    //  $('select#userName').html($(''));

    var select = $(parent).find('td.jobType').html();
    $('select#jobType_m').html($('#jobType').html());
    $("select#jobType_m option:contains(\'" + select.replace(/^ | $/, '') + "\')").attr('selected', true);

    $("#customer").change(function () {
      $('#name').getPjName($('select#customer option:selected').val(), 1);
    });
  }).done(function () {

    var ProjectItems = ['#name', '#objectType_m'];

    for (var _i2 = 0; _i2 < ProjectItems.length; _i2++) {

      $(ProjectItems[_i2]).change(function () {
        getObjName(false, "#objNameTS_m", {
          name: $('#name option:selected').val(),
          type: $('#objectType_m option:selected').val()
        });
      });
    }
  }).done(function () {
    var descFull = $('#descr').val(),
        descPartText = descFull.indexOf(':') > 0 ? descFull.substring(descFull.indexOf(':') + 2) : descFull.indexOf(']') < 0 ? descFull : '',
        descrPartObject = descFull.indexOf(']') > 0 ? descFull.substring(1, descFull.indexOf(']')) : '',
        ObjectTypeName = descrPartObject.split('=>') || '';

    $('#descr').val(descPartText);

    var work = new objectWorker(ObjectTypeName);

    work.getObjTypeEditor().done(function () {
      this.getObjNameEditor().done(function () {
        $('#jobType_m').watchFromJobType(['#objectType_m', '#objNameTS_m'], true);
      });
    }),
    //get picked calendar;
    $('#editModal .modal-body input#dateJob').datepicker({
      format: "yyyy-mm-dd",
      language: "en",
      todayHighlight: true
    });
  });
}

function objectWorker(val) {
  this._val = val;
  this.doIt = true;
  this.stat = $.Deferred();
}

objectWorker.prototype = {

  getObjTypeEditor: function getObjTypeEditor() {
    var objType = this._val[0] || 'Dataset';

    $('#objectType_m option:contains(' + objType.replace(/^ | $/, '') + ')').attr('selected', true);

    if (this._val[1] == undefined) this.doIt = false;
    this.stat.resolve('and');
    if (this.getObName()) return this;
  },

  getObName: function getObName() {

    if (getObjName(false, "#objNameTS_m", {
      name: $('#name option:selected').val(),
      type: $('#objectType_m option:selected').val()
    })) return true;
  },

  getObjNameEditor: function getObjNameEditor() {
    var _this = this;

    this.doIt && this.wait(200, function () {
      $("#objNameTS_m option:contains(\'" + _this._val[1].replace(/^ | $/, '') + "\')").attr('selected', true);
    });
    this.stat.resolve('and');
    return this;
  },

  wait: function wait(t, f) {
    setTimeout(function () {
      f();
    }, t);
    return undefined;
  },

  done: function done(f) {
    var _this2 = this;

    this.stat.promise().done(function () {
      f.call(_this2);
      return _this2;
    });
  }
};

function getTDID(object) {
  $('#deleteModal #hiddenVal').val($(object).parents('tr').attr('id').split('_')[0].split('it')[1]);
}

function ReportEditTool(confArray) {
  this._confArray = confArray;
}

ReportEditTool.prototype = {
  updateReport: function updateReport() {
    var _this3 = this;

    $('#updateReport').on('click', function () {
      // console.log('ok!')
      if (ValidReport(_this3._confArray[0], _this3._confArray[1], _this3._confArray[2])) {

        var a = {},
            idStore = ['hiddenVal', 'objNameTS_m', 'jobType_m', 'userName', 'descr', 'dateJob', 'hoursJob'];

        $('.buttonPlace').addClass('success');

        for (var i = 0; i < idStore.length; i++) {

          if (['name', 'jobType_m', 'objNameTS_m'].indexOf(idStore[i]) > -1) {
            a[i] = $('#editModal select#' + idStore[i] + ' option:selected').val();
          } else {
            switch (idStore[i]) {
              case 'userName':
                a[i] = $('#editModal select#' + idStore[i] + ' option:selected').attr('idnum');
                break;
              case 'descr':
                a[i] = _this3.descriptionCreator(idStore[i]);
                break;
              default:
                a[i] = $('#editModal input#' + idStore[i]).val();
                break;

            }
          }
        };

        _this3.wait(200, function () {
          $.get(paths.reportEditor + 'UpDateDB&object=' + a.parsetoJSON(), function (data) {
            showSQLError(data);
          }).done(function () {
            returnReportFromDB(returnDate());
          });
        }).wait(300, function () {
          $('#editModal').modal('hide');
        }).wait(500, function () {
          $('.buttonPlace').removeClass('success');
          $('#editModal .modal-body').html('');
        }).disableModalWin();

        showNotify('Record update successful', 300);
      }
    });
    return this;
  },

  descriptionCreator: function descriptionCreator(key) {
    var jopTypeChk = void 0;
    if (['1', '2'].indexOf($('#jobType_m').val()) > -1) {
      descr = "[" + $('#objectType_m option:selected').text() + ($('#objNameTS_m option:selected').text().toLowerCase() !== '-none-' ? ' => ' + $('#objNameTS_m option:selected').text() : '') + "]";
      jopTypeChk = jopTypeChk || true;
    }

    var descrVal = $('textarea#' + key).val().replace(/^ +|( )+$/, '').replace(/[\n\r]+$/gm, ' '),
        descr = descr || '';

    return encodeURIComponent((descr + (descrVal != '' ? (jopTypeChk ? "\: " : '') + descrVal : '')).quoteString());
  },

  deleteRecord: function deleteRecord() {
    var _this4 = this;

    $('.deleteButton .btn-danger').on('click', function () {

      $('.deleteButton').addClass('success');

      var id = {
        rec: $('#deleteModal #hiddenVal').val()
      },
          _this = _this4;

      $.get(paths.reportEditor + 'removeRecord&object=' + id.parsetoJSON(), function (data) {
        showSQLError(data);
      }).done(function () {
        _this.wait(200, function () {
          returnReportFromDB(returnDate());
        });
      });

      _this4.wait(300, function () {
        $('#deleteModal').modal('hide');
      }).wait(500, function () {
        $('.deleteButton').removeClass('success');
      });

      showNotify('Record delete successful', 300);
    });
    return this;
  },

  wait: function wait(t, f) {
    setTimeout(function () {
      f();
    }, t);
    return this;
  },

  disableModalWin: function disableModalWin() {
    $('#editModal').find('*').not('#updateReport').not('#removeReportLine').off();
    return this;
  },

  clearModalWhenCancel: function clearModalWhenCancel() {
    var _this5 = this;

    $('#removeReportLine').on('click', function () {
      _this5.disableModalWin().wait(300, function () {
        $('#editModal .modal-body').html('');
      });
    });
    return this;
  },
  runAllEvent: function runAllEvent() {
    return this.updateReport().deleteRecord().clearModalWhenCancel();
  }
};

function getObjName(skip, way, trVal, callback) {

  if (skip || !firstRun) {
    way = way || '#objName';
    $('#objectNameStore').attr('objname', $(way + ' option:selected').text());

    var transfVal = trVal || {
      name: $('#projectID option:selected').val(),
      type: $('#objectType option:selected').val()
    };

    var runAgain = function runAgain() {
      setTimeout(function () {
        getObjName(skip, way, trVal, callback);
      }, 100);
    };

    if (transfVal.name == undefined && transfVal.type == undefined) return runAgain();

    $.when($.get(paths.reportEditor + 'getObjectName&object=' + transfVal.parsetoJSON(), function (data) {
      $(way).html(data);
    })).fail(function () {
      return runAgain();
    }).done(function () {

      var check = !$(way).html();
      if (check) return runAgain();
      var objNameAttr = $('#objectNameStore').attr('objname');

      if (objNameAttr && objNameAttr.toLowerCase() != '-none-') {

        $(way + ' option').each(function (i, elem) {
          if ($(elem).text().replace(/^ | $/, '') == objNameAttr) {
            $(elem).attr('selected', true);
            return true;
          }
        });
      }

      if (typeof callback == 'function') {
        return callback();
      }
    });

    return true;
  }
}