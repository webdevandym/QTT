'use strict';

function putData(clb) {

  // get customer

  $.get(paths.main + 'getProject&object=' + {
    query: 'Customer'
  }.parsetoJSON(), function (data) {
    $('#customerID').html(data);
  })
  // get Project and Users
  .done(function () {

    var ProjectItems = ['#projectID', '#objectType'];

    for (var i = 0; i < ProjectItems.length; i++) {

      $(ProjectItems[i]).change(function () {
        getObjName();
      });
    };

    $('#projectID').getPjName(document.getElementById("customerID").value);

    var jobQuery = function jobQuery() {
      if ($('#jobType')) {
        $.get(paths.reportEditor + 'getJobType', function (data) {
          $('#jobType').html(data);
        });
      }
    };
    jobQuery();

    var data = new requireDATA();
    data.runQuery(paths.main, {
      getUserName: {
        id: "programmerName"
      },
      getObjectType: {
        id: "objectType"
      }
    }).done(function () {
      getObjName(true, null, null, function () {

        $('body').addClass('loaded');
        if (typeof clb === 'function') {
          clb();
        }
      });
    });
  });
}

function requireDATA() {
  this.stat = $.Deferred();
}

requireDATA.prototype = {
  runQuery: function runQuery(paths, jsobj) {
    var _this2 = this;

    $.get(paths + jsobj.parsetoJSON(), function (data) {
      console.log(data);
      $.each(JSON.parse(data), function (it, v) {

        if (v.condition != undefined && !v.condition) return;

        var selector = v.id != undefined ? '#' + v.id : '.' + v.class,
            store = '';

        if (v.val instanceof Array) {
          $.each(v.val, function (item, val) {
            if (val != '') store += val;
          });
        }

        store = store || v.val;

        $(selector).html(store.replace(/^ | $/, ''));
      });
    }).done(function () {
      _this2.stat.resolve('and');
    });

    return this;
  },

  done: function done(f) {
    var _this3 = this;

    this.stat.promise().done(function () {
      f();
      return _this3;
    });
  }
};

Object.prototype.getPjName = function (name, editor) {
  var path = "./web/forms",
      _this = this;

  $.get(paths.main + 'getProject&object=' + {
    query: name
  }.parsetoJSON(), function (data) {
    $(_this).html(data);
  }).done(function () {
    if (document.getElementById('jobType')) {
      var transfVal = 0,
          obj = 0;

      if (editor) {
        transfVal = trval || {
          name: $('#name option:selected').val(),
          type: $('#objectType_m option:selected').val()
        }, obj = "#objNameTS_m";
      };
      getObjName(0, obj, transfVal);
      firstRun = false;
    }
  });
};

Object.defineProperty(Object.prototype, 'getPjName', {
  enumerable: false
});