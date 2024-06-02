//www.tlcpay.ph/EPS/usermanagement/UserLevelConfig/updateUserLevelConfigForm//    var tblusers;
fhttps: $(document).ready(function () {
  var page = "";
  var base_url = commons.base_url;

  var tblusers = $("#dtusers").DataTable({
    language: {
      emptyTable: "No data available",
    },
    ajax: {
      url: commons.baseurl + "usermanagement/UserLevelConfig/getAllUserLevels",
      dataSrc: function (result) {
        console.log(result);
        //console.log(result.Data.details);
        if (result.Code == 0) {
          return result.Data.details;
        } else {
          return {
            sEcho: 1,
            iTotalRecords: "0",
            iTotalDisplayRecords: "0",
            aaData: [],
          };
        }
      },
    },
    columns: [
      { data: "userlevel_id" }, 
      { data: "userlevelname" },
      { data: "description" },
      {
        data: function (data) {
          if (data.status == "ACTIVE") {
            return (
              '<b><span class="text-success">' + data.status + "</span></b>"
            );
          } else {
            return (
              '<b><span class="text-danger">' + data.status + "</span></b>"
            );
          }
        },
      },
      {
        data: function (data) {
          //console.log($data);
          if (
            data.userid == $("#sessionuser").val() ||
            $("#viewtable").val() == "false"
          ) {
            return "n/a";
          } else {
            return (
              '<button onclick="viewUserLevelDetails(' +
              data.userlevel_id +
              ')" class="btn btn-xs btn-fill btn-info" type="button"><i class="fa fa-check-circle"></i>Select</button></td>'
            );

            //return '<button onclick="viewUserLevelDetails('+data.userlevel_id+')" class="btn btn-xs btn-fill btn-info" type="button"><i class="fa fa-check-circle"></i>Select</button></td>';
          }
        },
        bSortable: false,
      },
    ],
  });

$("#tbl-addUserLevelConfig").DataTable({
    ordering: false,
    info: false,
    paging: false,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    destroy: true,
    responsive: false,
    aaSorting: [],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search records",
    },
  });
  //Check and uncheck all
  $(document).on(
    "click",
    "#check_allupdateUserLevelConfig,#check_alladdUserLevelConfig",
    function () {
      if (!$(this).prop("checked")) {
        $(".checkbox_table").prop("checked", false);
      } else {
        $(".checkbox_table").prop("checked", true);
      }
      //$(".checkBoxClass").prop('checked', $(this).prop('checked'));
    }
  );

  $("#addUserLevelConfig").validate({
    rules: {
      ".required": {
        required: true,
      },
      ".email": {
        required: true,
        email: true,
      },
    },
    highlight: function (input) {
      $(input).parents(".form-line").addClass("error");
    },
    unhighlight: function (input) {
      $(input).parents(".form-line").removeClass("error");
    },
    errorPlacement: function (error, element) {
      $(element).parents(".form-group").append(error);
    },
  });

  $("#module_wrapper")
    .children(".row:first")
    .find("div:first")
    .html('<label>Roles <span class="text-danger">*</span></label>');
  //Confirms
  $(document).on(
    "click",
    ".activateUserLevelConfig,.deactivateUserLevelConfig",
    function (e) {
      e.preventDefault();
      me = $(this);
      url = me.attr("href");
      var id = me.attr("data-id");
      content = "Are you sure you want to proceed?";
      if (me.hasClass("activateUserLevelConfig")) {
        content = "Are you sure you want to activate selected user level?";
      } else if (me.hasClass("deactivateUserLevelConfig")) {
        content = "Are you sure you want to deactivate selected user level?";
      }
      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              //Code here
              $.confirm({
                content: function () {
                  var self = this;
                  return $.ajax({
                    type: "POST",
                    url: url,
                    data: { id: id },
                    dataType: "json",
                    success: function (result) {
                      if (result.Code == "0") {
                        if (result.hasOwnProperty("key")) {
                          switch (result.key) {
                            case "activateUserLevelConfig":
                            case "deactivateUserLevelConfig":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              //loadTable();

                              $("#myModal").modal("hide");
                              break;
                          }
                        }
                      } else {
                        self.setContent(result.Message);
                        self.setTitle(
                          '<label class="text-danger">Failed</label>'
                        );
                      }
                    },
                    error: function (result) {
                      self.setContent(
                        "There was an error in the connection. Please contact the administrator for updates."
                      );
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                    },
                  });
                },
                onDestroy: function () {
                  location.reload();
                },
              });
            },
          },
          cancel: function () {},
        },
      });
    }
  );
  //Ajax non-forms
  $(document).on("click", "#updateUserLevelConfigForm", function (e) {
    e.preventDefault();
    me = $(this);
    id = me.attr("data-id");
    url = me.attr("href");
    $.ajax({
      type: "POST",
      url: url,
      data: { id: id },
      dataType: "json",
      success: function (result) {
        page = me.attr("id");
        if (result.hasOwnProperty("key")) {
          switch (result.key) {
            case "updateUserLevelConfig":
              $("#myModal .modal-dialog").attr(
                "class",
                "modal-dialog modal-lg"
              );
              $("#myModal .modal-title").html("User Level Details");
              $("#myModal .modal-body").html(result.form);
             $("#tbl-updateUserLevelConfig").DataTable({
                ordering: false,
                info: false,
                paging: false,
                lengthMenu: [
                  [10, 25, 50, -1],
                  [10, 25, 50, "All"],
                ],
                destory: true,
                responsive: true,
                aaSorting: [],
                language: {
                  search: "_INPUT_",
                  searchPlaceholder: "Search records",
                },
              });
              $("#addUserLevelConfig").validate({
                rules: {
                  ".required": {
                    required: true,
                  },
                  ".email": {
                    required: true,
                    email: true,
                  },
                },
                highlight: function (input) {
                  $(input).parents(".form-line").addClass("error");
                },
                unhighlight: function (input) {
                  $(input).parents(".form-line").removeClass("error");
                },
                errorPlacement: function (error, element) {
                  $(element).parents(".form-group").append(error);
                },
              });
              $("#activateUserLevelConfig").attr("data-id", id);
              $("#deactivateUserLevelConfig").attr("data-id", id);
              $("#module_wrapper")
                .children(".row:first")
                .find("div:first")
                .html(
                  '<label>Roles <span class="text-danger">*</span></label>'
                );
              $("#myModal").modal("show");
              break;
          }
          $("#" + result.key).validate({
            rules: {
              ".required": {
                required: true,
              },
              ".email": {
                required: true,
                email: true,
              },
            },
            highlight: function (input) {
              $(input).parents(".form-line").addClass("error");
            },
            unhighlight: function (input) {
              $(input).parents(".form-line").removeClass("error");
            },
            errorPlacement: function (error, element) {
              $(element).parents(".form-group").append(error);
            },
          });
        }
      },
      error: function (result) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content:
            "There was an error in the connection. Please contact the administrator for updates.",
        });
      },
    });
  });

  //Ajax Forms
  $(document).on(
    "submit",
    "#addUserLevelConfig,#updateUserLevelConfig",
    function (e) {
      e.preventDefault();
      form = $(this);
      content = "Are you sure you want to proceed?";
      if (form.attr("id") == "addUserLevelConfig") {
        content = "Are you sure you want to add user level?";
      }
      if (form.attr("id") == "updateLevelUserConfig") {
        content = "Are you sure you want to update user level?";
      }
      url = form.attr("action");

      if ($('#myModal input[type="search"]').val() != '' && $('#myModal input[type="search"]').val() != undefined) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please clear the search box before saving.",
        });
        return false;
      }

      // console.log(form.serialize());
      // return false;
      // $("#tbl-addUserLevelConfig").DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
      //       var data = this.node();
      //       if ($(data).find('input').prop('checked')) {
      //             console.log(this.data()[0]);
      //       }
      // });
			var rolesCheck = 0;
			
			var boxes = $('input[name="Roles[]"]:checked');

			boxes.each(function(){
				rolesCheck = boxes.length;
			});

			if(rolesCheck > 0){
      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              //Code here
              $.confirm({
                content: function () {
                  var self = this;
                  return $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    dataType: "json",
                    success: function (result) {
                      if (result.hasOwnProperty("key")) {
                        if (result.Code == "0") {
                          if (result.hasOwnProperty("key")) {
                            switch (result.key) {
                              case "addUserLevelConfig":
                              case "updateUserLevelConfig":
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
                                $("#myModal .modal-body").html("");
                                $("#myModal").modal("hide");
                                loadTable();
                                break;
                            }
                          }
                        } else {
                          self.setContent(result.Message);
                          self.setTitle(
                            '<label class="text-danger">Failed</label>'
                          );
                        }
                      }
                    },
                    error: function (result) {
                      self.setContent(
                        "There was an error in the connection. Please contact the administrator for updates."
                      );
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                    },
                  });
                },
                onDestroy: function () {
                  location.reload();
                },
              });
            },
          },
          cancel: function () {},
        },
      });
		}else{
      $.alert({
        title: '<label class="text-danger">Failed</label>',
        content: "Please select user level role(s)!",
      });
		// 	$.dialog({
		// 		title: '<label class="text-danger">Warning!</label>',
		// 		content: 'Please select user level role(s)!',
		// 		type: 'red',
		// });
		}
    }
  );

  $(document).on(
    "click",
    "#addUserLevelConfigForm, .updateUserConfigForm",
    function (e) {
      e.preventDefault();
      me = $(this);
      url = me.attr("href");

      $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        success: function (result) {
          page = me.attr("id");
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "addUserLevelConfig":
                //page="";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Register New User Level");
                $("#myModal .modal-body").html(result.form);

                $("#myModal").modal("show");

                break;
              case "updateUserConfig":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("User Details");
                $("#myModal .modal-body").html(result.form);

                getUserLevels(me.attr("data-userlevel"));
                $("#myModal").modal("show");
                break;
            }
            $("#" + result.key).validate({
              rules: {
                ".required": {
                  required: true,
                },
                ".email": {
                  required: true,
                  email: true,
                },
              },
              highlight: function (input) {
                $(input).parents(".form-line").addClass("error");
              },
              unhighlight: function (input) {
                $(input).parents(".form-line").removeClass("error");
              },
              errorPlacement: function (error, element) {
                $(element).parents(".form-group").append(error);
              },
            });
            $("#frmChangePass").validate({
              rules: {
                ".required": {
                  required: true,
                },
                ".email": {
                  required: true,
                  email: true,
                },
              },
              highlight: function (input) {
                $(input).parents(".form-line").addClass("error");
              },
              unhighlight: function (input) {
                $(input).parents(".form-line").removeClass("error");
              },
              errorPlacement: function (error, element) {
                $(element).parents(".form-group").append(error);
              },
            });
          }
        },
        error: function (result) {
          self.setContent(
            "There was an error in the connection. Please contact the administrator for updates."
          );
          self.setTitle('<label class="text-danger">Failed</label>');
        },
      });
    }
  );

  //show user level update

  ///load module reg
});

function loadTable() {
  var url = window.location.href;
  $.ajax({
    url: url,
    dataType: "json",
    success: function (result) {
      $("#userLevelForm .body").html(result.form);
      $("#tbl-addUserLevelConfig").DataTable({
        ordering: false,
        info: false,
        paging: false,
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "All"],
        ],
        responsive: false,
        aaSorting: [],
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records",
        },
      });
      $("#userLevelTable .body").html(result.table);
      $("#datatables").DataTable({
        pagingType: "full_numbers",
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "All"],
        ],
        responsive: true,
        aaSorting: [],
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records",
        },
      });
    },
  });
}

var $modal = $("#myModal");
var editform = $("#editForm");
function viewUserLevelDetails(userlevelid) {
  $.get(
    commons.baseurl + "usermanagement/UserLevelConfig/rendereditform",
    function (form) {
      $.ajax({
        url: commons.baseurl + "usermanagement/UserLevelConfig/userlevel",
        data: {
          userlevelid: userlevelid,
        },
        dataType: "JSON",
        type: "POST",
        success: function (result) {
          if (result.Code == 0) {
            var data = result.Data.details[0];
            $modal.find(".modal-title").html("Manage Userlevel");
            $modal.find(".modal-body").html(form);
            $modal.modal("show");
            $("#userlevelid").val(userlevelid);
            $("#userlevelname").val(data.userlevelname);
            $("#description").val(data.description);
            loadEditDTRoles(data.modules);
            $("#bUpdate").attr(
              "onclick",
              'updateUserlevel("' + userlevelid + '")'
            );
            if (data.status == "ACTIVE") {
              $("#bActivate").hide();
              $("#bDeactivate").show();
              $("#bDeactivate").attr(
                "onclick",
                'updateStatus("' +
                  userlevelid +
                  '","INACTIVE","' +
                  data.userlevelname +
                  '")'
              );
            } else {
              $("#bDeactivate").hide();
              $("#bActivate").show();
              $("#bActivate").attr(
                "onclick",
                'updateStatus("' +
                  userlevelid +
                  '","ACTIVE","' +
                  data.userlevelname +
                  '")'
              );
            }
          }
        },
        error: function (err) {
          console.log(err);
        },
      });
    }
  );
}

function loadDTRoles() {
  var dtroles = (function () {
    var tblroles = $("#dtroles").DataTable({
      bDestroy: true,
      scrollY: 190,
      scrollCollapse: true,
      paging: false,
      dom: '<"customuifilter"f>t',
      language: {
        emptyTable: "No data available",
      },
      ajax: {
        url: commons.baseurl + "usermanagement/UserLevelConfig/userlevelmods",
        dataSrc: function (result) {
          if (result.Code == 0) {
            return result.Data.details;
          } else {
            return {
              sEcho: 1,
              iTotalRecords: "0",
              iTotalDisplayRecords: "0",
              aaData: [],
            };
          }
        },
      },
      columns: [
        { data: "module" },
        { data: "description" },
        { data: "status" },
        {
          data: function (data) {
            return (
              '<label class="checkbox mod" id="' +
              data.module +
              '">' +
              '<span class="icons">' +
              '<span class="first-icon fa fa-square-o"></span>' +
              '<span class="second-icon fa fa-check-square-o"></span>' +
              "</span>" +
              '<input type="checkbox" data-toggle="checkbox" />' +
              "</label>"
            );
          },
          bSortable: false,
        },
      ],
    });
    /*$('.customuifilter').find('input').removeClass("form-control");*/
    $(".customuifilter")
      .find("#dtroles_filter")
      .prepend(
        '<label class="pull-left" style="padding-top:8px;">Roles <span class="text-danger">*</span></label>'
      );
    return tblroles;
  })();
  return dtroles;
}
function loadEditDTRoles(userlevelmodules) {
  var dtroles = (function () {
    $(".selectallmod").on("change", function () {
      if ($(this).hasClass("checked")) {
        $(".mod").addClass("checked");
      } else {
        $(".mod").removeClass("checked");
      }
    });
    var tblroles = $("#dtroles").DataTable({
      bDestroy: true,
      scrollY: 190,
      scrollCollapse: true,
      paging: false,
      dom: '<"customuieditfilter"f>t',
      language: {
        emptyTable: "No data available",
      },
      ajax: {
        url: commons.baseurl + "usermanagement/UserLevelConfig/userlevelmods",
        dataSrc: function (result) {
          if (result.Code == 0) {
            return result.Data.details;
          } else {
            return {
              sEcho: 1,
              iTotalRecords: "0",
              iTotalDisplayRecords: "0",
              aaData: [],
            };
          }
        },
      },
      aoColumns: [
        { data: "module" },
        { data: "description" },
        {
          data: function (data) {
            return (
              '<label class="checkbox mod" id="' +
              data.module +
              '" onClick="check(this)">' +
              '<span class="icons">' +
              '<span class="first-icon fa fa-square-o"></span>' +
              '<span class="second-icon fa fa-check-square-o"></span>' +
              "</span>" +
              '<input type="checkbox" data-toggle="checkbox" />' +
              "</label>"
            );
          },
          bSortable: false,
        },
      ],
      initComplete: function (settings, json) {
        $(".mod").removeClass("checked");
        var i;
        for (i = 0; i < userlevelmodules.length; i++) {
          $("#" + userlevelmodules[i]).addClass("checked");
        }
      },
    });
    $(".customuieditfilter")
      .find("#dtroles_filter")
      .prepend(
        '<label class="pull-left" style="padding-top:8px;">Roles <span class="text-danger">*</span></label>'
      );
    $(".customuieditfilter")
      .find("input")
      .css("width", "200px")
      .addClass("pull-right")
      .removeClass("form-control");
    return tblroles;
  })();

  return dtroles;
}

function updateUserlevel(userlevelid) {
  var form = $("#editForm");
  var url = form.attr("action");
  form.parsley().validate();
  if (form.parsley().isValid()) {
    var mods = (function () {
      var arr = null,
        arrayOfModules = [];
      $(".mod").each(function () {
        if ($(this).hasClass("checked")) {
          arrayOfModules.push($(this).attr("id"));
        }
        arr = arrayOfModules;
      });
      return arr;
    })();
    if (mods.length > 0) {
      $.confirm({
        title: '<label class="text-warning">Confirm</label>',
        content: "Are you sure you want to update this userlevel?",
        type: "orange",
        buttons: {
          yes: {
            btnClass: "btn-blue",
            action: function () {
              if (form.length == 1) {
                var resultCode;
                $.confirm({
                  content: function () {
                    var self = this;
                    return $.ajax({
                      url: url,
                      data: {
                        userlevelid: userlevelid,
                        userlevelname: $("#userlevelname").val(),
                        description: $("#description").val(),
                        roles: mods,
                      },
                      type: "POST",
                      dataType: "JSON",
                    })
                      .done(function (result) {
                        resultCode = result.Code;
                        if (result.Code == 0) {
                          self.setTitle(
                            '<label class="text-success">Success</label>'
                          );
                          $modal.modal("toggle");
                        } else {
                          self.setTitle(
                            '<label class="text-danger">Failed</label>'
                          );
                        }
                        self.setContent(result.Message);
                      })
                      .fail(function () {
                        self.setContent("Url Loading Failed");
                      });
                  },
                  buttons: {
                    ok: {
                      action: function () {
                        if (resultCode == 0) {
                          form[0].reset();
                        }
                      },
                    },
                  },
                });
              }
            },
          },
          cancel: function () {},
        },
      });
    } else {
      $("#errRoles").html("Roles are missing");
    }
  }
}

function updateStatus(userlevelid, status, userlevelname) {
  var question;
  if (status == "INACTIVE") {
    question = "Are you sure you want to deactivate " + userlevelname + "?";
  } else if (status == "ACTIVE") {
    question = "Are you sure you want to reactivate " + userlevelname + "?";
  }
  $.confirm({
    title: '<label class="text-warning">Confirm</label>',
    content: question,
    type: "orange",
    buttons: {
      yes: {
        btnClass: "btn-blue",
        action: function () {
          var resultCode;
          $.confirm({
            content: function () {
              var self = this;
              return $.ajax({
                url:
                  commons.baseurl +
                  "usermanagement/UserLevelConfig/changestatus",
                data: {
                  userlevelid: userlevelid,
                  status: status,
                },
                type: "POST",
                dataType: "JSON",
              })
                .done(function (result) {
                  resultCode = result.Code;
                  if (result.Code == 0) {
                    self.setTitle(
                      '<label class="text-success">Success</label>'
                    );
                    $modal.modal("toggle");
                  } else {
                    self.setTitle('<label class="text-danger">Failed</label>');
                  }
                  self.setContent(result.Message);
                })
                .fail(function () {
                  self.setContent("Url Loading Failed");
                });
            },
            buttons: {
              ok: {
                action: function () {},
              },
            },
          });
        },
      },
      cancel: function () {},
    },
  });
}

function getModules() {
  var modules = (function () {
    var temp = null;
    $.ajax({
      async: false,
      url: commons.baseurl + "usermanagement/UserLevelConfig/userlevelmods",
      type: "POST",
      dataType: "JSON",
      success: function (result) {
        if (result.Code == 0) {
          var i,
            arrayOfModules = [];
          for (i = 0; i < result.Data.details.length; i++) {
            arrayOfModules.push(result.Data.details[i].module);
          }
          temp = arrayOfModules;
        }
      },
    });
    return temp;
  })();
  return modules;
}

function check(elem) {
  if (hasClass(elem, "checked")) {
    elem.click();
    elem.classList.add("checked");
  } else {
    elem.click();
    elem.classList.remove("checked");
  }
}

function hasClass(target, className) {
  return new RegExp("(\\s|^)" + className + "(\\s|$)").test(target.className);
}
