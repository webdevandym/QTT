function putData(clb) {

  // get customer

  $.get(paths.main + 'getProject&object=' + {
      query: 'Customer'
    }.parsetoJSON(), function(data) {
      $('#customerID').html(data)
    })
    // get Project and Users
    .done(() => {

      var ProjectItems = ['#projectID', '#objectType'];

      for (let i = 0; i < ProjectItems.length; i++) {

        $(ProjectItems[i]).change(function() {
          getObjName()
        })
      };

      $('#projectID').getPjName(document.getElementById("customerID").value)

      let jobQuery = function() {
        if ($('#jobType')) {
          $.get(paths.reportEditor + 'getJobType', function(data) {
            $('#jobType').html(data);
          })
        }
      }
      jobQuery()

      let data = new requireDATA
      data.runQuery(paths.main, {
          getUserName: {
            id: "programmerName"
          },
          getObjectType: {
            id: "objectType"
          }
        })
        .done(() => {
          getObjName(true, null, null, () => {

            $('body').addClass('loaded')
            if (typeof clb === 'function') {
              clb();
            }
          })
        })

    })
}

function requireDATA() {
  this.stat = $.Deferred()
}

requireDATA.prototype = {
  runQuery: function(paths, jsobj) {

    $.get(paths + jsobj.parsetoJSON(), function(data) {
      console.log(data);
      $.each(JSON.parse(data), (it, v) => {

        if (v.condition != undefined && !v.condition) return;

        let selector = v.id != undefined ? '#' + v.id : '.' + v.class,
          store = '';

        if (v.val instanceof Array) {
          $.each(v.val, (item, val) => {
            if (val != '') store += val;
          })
        }

        store = store || v.val;

        $(selector).html(store.replace(/^ | $/, ''))

      })

    }).done(() => {
      this.stat.resolve('and')
    })


    return this;
  },

  done: function(f) {
    this.stat.promise().done(() => {
      f();
      return this;
    })
  }
}

Object.prototype.getPjName = function(name, editor) {
  let path = "./web/forms",

    _this = this;

  $.get(paths.main + 'getProject&object=' + {
    query: name
  }.parsetoJSON(), function(data) {
    $(_this).html(data)
  }).done(function() {
    if (document.getElementById('jobType')) {
      let transfVal = 0,
        obj = 0;

      if (editor) {
        transfVal = trval || {
            name: $('#name option:selected').val(),
            type: $('#objectType_m option:selected').val()
          },
          obj = "#objNameTS_m";
      };
      getObjName(0, obj, transfVal)
      firstRun = false;
    }
  })
}


Object.defineProperty(Object.prototype, 'getPjName', {
  enumerable: false
});
