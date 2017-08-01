var ajaxObj = {},
  wait = 500;

function insertData(object) {

  let idStore = [],
    lableStore = ['Project', 'Object Type', 'Object Name', 'Programmer', 'Account', 'Description', 'Job Type', 'Job Date', 'Job Hours'],
    valueStore = [],
    startPositionCol = 0,
    innerText = $(object).parents('tr').get(0),
    parent = $(object).parents('tr'),
    specHours = '',
    valObj = {};

  $('#editModal .modal-body').html('').append("<input type='hidden' id = 'hiddenVal'><div class='custEdit'><div><span>Customer:</span><select id = 'customer'></select></div></div><div class='objectsEdit'></div>")

  $('#editModal #hiddenVal').val($(innerText).attr('id'));

  $(innerText).children('td').each(function(i, elem) {
    if ($(elem).attr('data-toggle') === 'modal' || $(elem).hasClass('chekerDelete')) {
      startPositionCol += 1
    } else {
      idStore.push(($(elem).attr('class') === 'jobType') ? $(elem).attr('class') + "_m" : $(elem).attr('class'))
    }
  });

  idStore.splice(1, 0, 'objectType_m', 'objNameTS_m')

  for (let i = startPositionCol; i < $(innerText).children().length; i++) {
    if ($(innerText).children()[i].getAttribute('class') === 'name') {
      valueStore.push($(innerText).children()[i].innerHTML.split('<')[0]);
    } else {
      valueStore.push($(innerText).children()[i].innerHTML);
    }
  }

  for (let i = 0, j = 0; i < idStore.length; i++, j++) {

    if (['objectType_m', 'objNameTS_m'].indexOf(idStore[i]) > -1) {
      $('#editModal .modal-body .objectsEdit').append("<div><span>" + lableStore[i] + ":</span><select id = '" + idStore[i] + "'><option selected='selected'></option></select></div>")
      j--
      continue;
    }
    if (['name', 'userName', 'account', 'jobType_m'].indexOf(idStore[i]) > -1) {
      let cust = idStore[i] === 'name' ? ' .custEdit' : ''
      $('#editModal .modal-body' + cust).append("<" + (cust != '' ? 'div' : 'p') + "><span>" + lableStore[i] + ":</span><select id = '" + idStore[i] + "'><option selected='selected'>" + valueStore[j] + "</option></select></" + (cust != '' ? 'div' : 'p') + ">")
    } else {
      switch (idStore[i]) {
        case 'descr':
          $('#editModal .modal-body').append("<p><span>" + lableStore[i] +
            ":<label id = 'descLegendEdit'>" + $('#descLegend').html() + "</label></span><textarea type='text'" +
            'onblur= "dscrValidator($(this),[\'#jobType_m\',\'#descLegendEdit\']);"' + "id='" + idStore[i] + "'>" + valueStore[j] + "</textarea></p>")
          break;
        case 'hoursJob':
          specHours = 'onblur="ValidDesc($(this),\'\',1);" onkeypress="return hoursChecker(0,null,event)"  onkeyup = "hoursChecker(1,$(this))" maxlength="3"'
        default:
          $('#editModal .modal-body').append("<p><span>" + lableStore[i] + ":</span><input type='text' id='" + idStore[i] + "' value ='" + valueStore[j] + "'" + specHours + "></p>")
      }
    }
  }

  $('#objectType_m').html($('#objectType').html());
  //get customer info;
  $.get(paths.main + 'getProject&object=' + {
      query: 'Customer',
      switch: $(parent).find('td.name span').html()
    }.parsetoJSON(), function(data) {
      $('#editModal select#customer').html(data)
    })
    .done(() => {
      let data = new requireDATA,

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


      data.runQuery(paths.main, storeobj)

      //  $('select#userName').html($(''));

      let select = $(parent).find('td.jobType').html()
      $('select#jobType_m').html($('#jobType').html())
      $("select#jobType_m option:contains(\'" + select.replace(/^ | $/, '') + "\')").attr('selected', true);

      $("#customer").change(function() {
        $('#name').getPjName($('select#customer option:selected').val(), 1)
      });

    })
    .done(function() {

      var ProjectItems = ['#name', '#objectType_m'];

      for (let i = 0; i < ProjectItems.length; i++) {

        $(ProjectItems[i]).change(function() {
          getObjName(false, "#objNameTS_m", {
            name: $('#name option:selected').val(),
            type: $('#objectType_m option:selected').val()
          });
        })
      }
    })
    .done(() => {
      let descFull = $('#descr').val(),
        descPartText = descFull.indexOf(':') > 0 ? descFull.substring(descFull.indexOf(':') + 2) : descFull.indexOf(']') < 0 ? descFull : '',
        descrPartObject = descFull.indexOf(']') > 0 ? descFull.substring(1, descFull.indexOf(']')) : '',
        ObjectTypeName = descrPartObject.split('=>') || '';

      $('#descr').val(descPartText)

      var work = new objectWorker(ObjectTypeName)

      work.getObjTypeEditor()
        .done(function() {
          this.getObjNameEditor()
            .done(() => {
              $('#jobType_m').watchFromJobType(['#objectType_m', '#objNameTS_m'], true)
            })
        }),
        //get picked calendar;
        $('#editModal .modal-body input#dateJob').datepicker({
          format: "yyyy-mm-dd",
          language: "en",
          todayHighlight: true,
        });
    });
}

function objectWorker(val) {
  this._val = val
  this.doIt = true
  this.stat = $.Deferred()
}

objectWorker.prototype = {

  getObjTypeEditor: function() {
    let objType = this._val[0] || 'Dataset';

    $('#objectType_m option:contains(' + objType.replace(/^ | $/, '') + ')').attr('selected', true)

    if (this._val[1] == undefined) this.doIt = false;
    this.stat.resolve('and')
    if (this.getObName()) return this;
  },

  getObName: function() {

    if (getObjName(false, "#objNameTS_m", {
        name: $('#name option:selected').val(),
        type: $('#objectType_m option:selected').val()
      })) return true;
  },

  getObjNameEditor: function() {
    let _this = this;

    this.doIt && this.wait(200, function() {
      $("#objNameTS_m option:contains(\'" + _this._val[1].replace(/^ | $/, '') + "\')").attr('selected', true);
    });
    this.stat.resolve('and')
    return this;
  },

  wait: (t, f) => {
    setTimeout(() => {
      f();
    }, t)
    return this;
  },

  done: function(f) {
    this.stat.promise().done(() => {
      f.call(this);
      return this;
    })

  }
}

function getTDID(object) {
  $('#deleteModal #hiddenVal').val($(object).parents('tr').attr('id').split('_')[0].split('it')[1])
}

function ReportEditTool(confArray) {
  this._confArray = confArray;
}

ReportEditTool.prototype = {
  updateReport: function() {
    $('#updateReport').on('click', () => {
      // console.log('ok!')
      if (ValidReport(this._confArray[0], this._confArray[1], this._confArray[2])) {

        var a = {},
          idStore = ['hiddenVal', 'objNameTS_m', 'jobType_m', 'userName', 'descr', 'dateJob', 'hoursJob'];

        $('.buttonPlace').addClass('success')

        for (var i = 0; i < idStore.length; i++) {

          if (['name', 'jobType_m', 'objNameTS_m'].indexOf(idStore[i]) > -1) {
            a[i] = $('#editModal select#' + idStore[i] + ' option:selected').val()
          } else {
            switch (idStore[i]) {
              case 'userName':
                a[i] = $('#editModal select#' + idStore[i] + ' option:selected').attr('idnum')
                break;
              case 'descr':
                a[i] = this.descriptionCreator(idStore[i])
                break;
              default:
                a[i] = $('#editModal input#' + idStore[i]).val()
                break

            }
          }
        };

        this.wait(200, () => {
            $.get(paths.reportEditor + 'UpDateDB&object=' + a.parsetoJSON(), function(data) {
              showSQLError(data);
            }).done(function() {
              returnReportFromDB(returnDate())
            })
          })
          .wait(300, () => {
            $('#editModal').modal('hide');
          })
          .wait(500, () => {
            $('.buttonPlace').removeClass('success')
            $('#editModal .modal-body').html('')
          })
          .disableModalWin();

        showNotify('Record update successful', 300);
      }
    })
    return this;
  },

  descriptionCreator: function(key) {
    let jopTypeChk;
    if (['1', '2'].indexOf($('#jobType_m').val()) > -1) {
      descr = "[" + $('#objectType_m option:selected').text() + ($('#objNameTS_m option:selected').text().toLowerCase() !== '-none-' ? ' => ' + $('#objNameTS_m option:selected').text() : '') + "]"
      jopTypeChk = jopTypeChk || true;
    }

    let descrVal = $('textarea#' + key).val().replace(/^ +|( )+$/, '').replace(/[\n\r]+$/gm, ' '),
      descr = descr || '';

    return encodeURIComponent((descr + (descrVal != '' ? ((jopTypeChk) ? "\: " : '') + descrVal : '')).quoteString());
  },

  deleteRecord: function() {
    $('.deleteButton .btn-danger').on('click', () => {

      $('.deleteButton').addClass('success')

      let id = {
          rec: $('#deleteModal #hiddenVal').val()
        },
        _this = this;

      $.get(paths.reportEditor + 'removeRecord&object=' + id.parsetoJSON(), (data) => {
        showSQLError(data);
      }).done(() => {
        _this.wait(200, function() {
          returnReportFromDB(returnDate());
        })
      })

      this.wait(300, function() {
          $('#deleteModal').modal('hide');
        })
        .wait(500, function() {
          $('.deleteButton').removeClass('success')
        })

      showNotify('Record delete successful', 300)
    })
    return this;
  },

  wait: function(t, f) {
    setTimeout(() => {
      f();
    }, t);
    return this;
  },

  disableModalWin: function() {
    $('#editModal').find('*').not('#updateReport').not('#removeReportLine').off()
    return this;
  },

  clearModalWhenCancel: function() {
    $('#removeReportLine').on('click', () => {
      this.disableModalWin().wait(300, () => {
        $('#editModal .modal-body').html('')
      });
    });
    return this;
  },
  runAllEvent: function() {
    return this.updateReport().deleteRecord().clearModalWhenCancel();
  }
}


function getObjName(skip, way, trVal, callback) {

  if (skip || !firstRun) {
    way = way || '#objName';
    $('#objectNameStore').attr('objname', $(way + ' option:selected').text())

    let transfVal = trVal || {
      name: $('#projectID option:selected').val(),
      type: $('#objectType option:selected').val()
    };

    let runAgain = function() {
      setTimeout(() => {
        getObjName(skip, way, trVal, callback)
      }, 100)
    }

    if (transfVal.name == undefined && transfVal.type == undefined) return runAgain();

    $.when($.get(paths.reportEditor + 'getObjectName&object=' + transfVal.parsetoJSON(), function(data) {
        $(way).html(data)
      }))
      .fail(() => {
        return runAgain()
      }).done(() => {

        let check = !$(way).html();
        if (check) return runAgain()
        let objNameAttr = $('#objectNameStore').attr('objname');

        if (objNameAttr && objNameAttr.toLowerCase() != '-none-') {

          $(way + ' option').each((i, elem) => {
            if ($(elem).text().replace(/^ | $/, '') == objNameAttr) {
              $(elem).attr('selected', true)
              return true;
            }
          })

        }

        if (typeof callback == 'function') {
          return callback()
        }
      })

    return true;
  }
}
