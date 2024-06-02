var getUrl = window.location;
var commons = {
  baseurl: window.location.protocol + "//" + window.location.host + "/NWRB_HRIS/",
};

function dialogGetList(url, data, tablename) {
  $.confirm({
    content: function () {
      var self = this;
      return $.ajax({
        url: url,
        data: data,
        dataType: "json",
        method: "POST",
      })
        .done(function (result) {
          var table = $("#" + tablename).DataTable();
          table.clear().draw();
          if (result.Code == "1" || result.Code == 1) {
            self.close();
          } else {
            if (result.Data.length > 0) {
              self.close();
              table.rows.add(result.Data);
              table.columns.adjust().draw();
            } else {
              self.close();
            }
          }
        })
        .fail(function () {
          self.setTitle("Error!");
          self.setContent("Something went wrong.");
        });
    },
  });
}

function printPrev(elem) {
    var mywindow = window.open("", "Print-Window");
    mywindow.document.write(
      "<html moznomarginboxes mozdisallowselectionprint><head>"
    );
    mywindow.document.write("</head><body >");
    mywindow.document.write(elem);
    mywindow.document.write("</body></html>");

    mywindow.document.close();
    mywindow.focus();
    
    setTimeout(() => {
      mywindow.print();
      mywindow.close();
    }, 500);
    return true;

  // var newWin = window.open("", "Print-Window");

  // newWin.document.open();

  // newWin.document.write(
  //   '<html><body onload="window.print()">' + elem + "</body></html>"
  // );


  // newWin.focus(); // necessary for IE >= 10*/

  // newWin.print();
  // newWin.document.close();
}

function submitRequest(url, type, isformed, data, callback, ask) {
  $.confirm({
    title: '<label class="text-warning">Confirm!</label>',
    content: "Are you sure " + ask,
    type: "orange",
    columnClass: "col-md-4 col-md-offset-4",
    buttons: {
      confirm: {
        btnClass: "btn-blue",
        action: function () {
          var jsonObj = [];
          $.confirm({
            content: function () {
              var self = this;
              var jsondata = {
                url: url,
                type: type,
                data: data,
                dataType: "json",
                success: function (result) {
                  if (result) {
                    if (result.Code == "0") {
                      self.setTitle(
                        '<label class="text-success">Success</label>'
                      );
                      self.setContent(result.Message);
                      jsonObj = result;
                    } else {
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                      self.setContent(result.Message);
                    }
                  } else {
                    self.setTitle('<label class="text-danger">Failed</label>');
                    self.setContent("Internal Error.");
                  }
                },
                error: function (result) {
                  self.setTitle('<label class="text-danger">Failed</label>');
                  self.setContent(
                    "There was an error in the connection. Please contact the administrator for updates. "
                  );
                },
              };
              if (isformed === 1) {
                jsondata["cache"] = false;
                jsondata["contentType"] = false;
                jsondata["processData"] = false;
              }
              return $.ajax(jsondata);
            },
            buttons: {
              ok: {
                action: function () {
                  if (jsonObj.Code == "0") {
                    callback(jsonObj);
                  }
                },
              },
            },
          });
        },
      },
      cancel: function () {},
    },
  });
}

function submitRequestv2(url, data, callback, ask) {
  $.confirm({
    title: '<label class="text-warning">Confirm!</label>',
    content:
      "<center><i class='material-icons' style='font-size:70pt' align='center'>warning</i><br>Are you sure?<br>" +
      ask +
      "</center>",
    type: "orange",
    buttons: {
      confirm: {
        btnClass: "btn-blue",
        action: function () {
          var jsonObj = [];
          $.confirm({
            content: function () {
              var self = this;
              return $.ajax({
                url: url,
                type: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (result) {
                  if (result) {
                    if (result.Code == "0") {
                      self.setTitle(
                        '<label class="text-success">Success</label>'
                      );
                      self.setContent(result.Message);
                      jsonObj = result;
                    } else {
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                      self.setContent(result.Message);
                    }
                  } else {
                    self.setTitle('<label class="text-danger">Failed</label>');
                    self.setContent("Internal Error.");
                  }
                },
                error: function (result) {
                  self.setTitle('<label class="text-danger">Failed</label>');
                  self.setContent(
                    "There was an error in the connection. Please contact the administrator for updates. "
                  );
                },
              });
            },
            buttons: {
              ok: {
                action: function () {
                  if (jsonObj.Code == "0") {
                    callback(jsonObj);
                  }
                },
              },
            },
          });
        },
      },
      cancel: function () {},
    },
  });
}

function errorDialog() {
  $.alert({
    title: '<label class="text-danger">Failed</label>',
    content:
      "There was an error in the connection. Please contact the administrator for updates.",
  });
}

function successDialog(message) {
  $.alert({
    title: '<label class="text-success">Success</label>',
    content: message,
  });
}
//console.log(window.location.pathname.split('/'));

function dialogErrorV2(message) {
  $.confirm({
    title: "Failed!",
    content: message,
    type: "red",
    typeAnimated: true,
    buttons: {
      event: {
        text: "Ok",
        btnClass: "btn-blue",
        action: function () {},
      },
    },
  });
}

function exportEXCEL(tablename, isAction, find) {
  $.confirm({
    title: '<label class="text-warning">Prompt!</label>',
    content:
      "" +
      '<form action="" class="formName">' +
      "<label>Enter file name</label>" +
      '<div class="form-group">' +
      '<input type="text" placeholder="Your name" id="inputName" class="form-control" required />' +
      "</div>" +
      "</form>",
    buttons: {
      formSubmit: {
        text: "Submit",
        btnClass: "btn-blue",
        action: function () {
          var name = this.$content.find("#inputName").val();
          if (isAction)
            $(tablename + " tr")
              .find(find)
              .hide();
          // $.fn.tableExport.ignoreCSS = ".displaynone";
          $.fn.tableExport.prototype.ignoreCSS = ".displaynone";
          $(tablename).tableExport({
            type: "excel",
            autotable: {
              theme: "plain",
              orientation: "l",
              format: "bestfit",
              styles: {
                fontSize: 9,
              },
            },
            footers: true,
            escape: "false",
            tableName: $("#inputName").val(),
            displayTablename: "true",
            fileName: $("#inputName").val(),
            ignoreCSS: ".displaynone",
          });
          if (isAction)
            $(tablename + " tr")
              .find(find)
              .show();
          successDialog("Table has been successful exported");
        },
      },
      cancel: function () {},
    },
    onContentReady: function () {
      var jc = this;
      this.$content.find("form").on("submit", function (e) {
        e.preventDefault();
        jc.$$formSubmit.trigger("click");
      });
    },
  });
}

function exportEXCELv2(tablename, isAction, find, isShow) {
  $.confirm({
    title: '<label class="text-warning">Prompt!</label>',
    content:
      "" +
      '<form action="" class="formName">' +
      "<label>Enter file name</label>" +
      '<div class="form-group">' +
      '<input type="text" placeholder="Your name" id="inputName" class="form-control" required />' +
      "</div>" +
      "</form>",
    buttons: {
      formSubmit: {
        text: "Submit",
        btnClass: "btn-blue",
        action: function () {
          var name = this.$content.find("#inputName").val();
          if (isAction)
            $(tablename + " tr")
              .find(find)
              .hide();

          $(tablename + " tr")
            .find(isShow)
            .show();
          $(tablename).tableExport({
            type: "excel",
            autotable: {
              theme: "plain",
              orientation: "l",
              format: "bestfit",
              styles: {
                fontSize: 9,
              },
            },
            footers: true,
            escape: "false",
            tableName: $("#inputName").val(),
            displayTablename: "true",
            fileName: $("#inputName").val(),
            ignoreCSS: ".displaynone",
          });
          $(tablename + " tr")
            .find(isShow)
            .hide();
          if (isAction)
            $(tablename + " tr")
              .find(find)
              .show();
          successDialog("Table has been successful exported");
        },
      },
      cancel: function () {},
    },
    onContentReady: function () {
      var jc = this;
      this.$content.find("form").on("submit", function (e) {
        e.preventDefault();
        jc.$$formSubmit.trigger("click");
      });
    },
  });
}
