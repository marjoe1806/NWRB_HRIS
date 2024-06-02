$(function () {
  var page = "";
  var base_url = commons.baseurl;
  var table;
  $(document).on("show.bs.modal", "#myModal", function () {
    $.AdminBSB.dropdownMenu.activate();
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();
    $.AdminBSB.search.activate();
  });
  //Show Update Form
  $(document).on("click", "#showUpdateForm", function (e) {
    // $("#updateUserConfig").find("#username").prop("readonly", true);
    $("#myModal .modal-title").html("Update User");
    $(".form-elements-container").fadeIn();
    $("#cancelUpdateForm").fadeIn("fast");
    $("#saveUserConfig").fadeIn("fast");
    $(".table-responsive").fadeOut("fast");
    $("#showChangePasswordForm").fadeOut("fast");
    $("#activateUserConfig").fadeOut("fast");
    $("#deactivateUserConfig").fadeOut("fast");
    $(this).fadeOut();
  });
  $(document).on("click", "#showChangePasswordForm", function (e) {
    me = $(this);
    $("#myModal .modal-title").html("Change User Password");
    $(".form-changepass-container").fadeIn();
    $("#cancelUpdateForm").fadeIn("fast");
    $("#changeUserPassword").fadeIn("fast");
    $(".table-responsive").fadeOut("fast");
    $("#showUpdateForm").fadeOut("fast");
    $("#activateUserConfig").fadeOut("fast");
    $("#deactivateUserConfig").fadeOut("fast");
    $('input[name="UserId"]').val(me.data("id"));
    $('input[name="Username"]').val(me.data("username"));
    $(this).fadeOut();
  });
  $(document).on("click", "#cancelUpdateForm", function (e) {
    $("#myModal .modal-title").html("User Details");
    $(".table-responsive").fadeIn("fast");
    $("#showUpdateForm").fadeIn("fast");
    $("#showChangePasswordForm").fadeIn("fast");
    $("#changePasswordForm").fadeIn("fast");
    $("#activateUserConfig").fadeIn("fast");
    $("#deactivateUserConfig").fadeIn("fast");
    $("#saveUserConfig").fadeOut("fast");
    $("#changeUserPassword").fadeOut("fast");
    $(".form-elements-container").fadeOut("fast");
    $(".form-changepass-container").fadeOut("fast");
    $(this).hide();
  });
  var employee_id;

  $(document).on("change", "#division_id ", function (e) {
    division_id = $(this).val();
    $.when(getFields.employee({ division_id: division_id })).done(function () {
      $("#employee_id").val(employee_id).change();
      $.AdminBSB.select.activate();
    });
  });

  $(document).on("change, changed.bs.select", "#employee_id", function (e) {
    me = $(this);
    id = me.val();

    //alert('hello')
    employee_name = $(this).find("option:selected").text();
    url2 = commons.baseurl + "employees/Employees/getEmployeesById?Id=" + id;
    dept_id = "N/A";
    position = "N/A";
    $.ajax({
      async: false,
      url: url2,
      data: { id: id },
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          console.log(temp.Data.details[0]);
          position = temp.Data.details[0].position_name;
          dept_id = temp.Data.details[0].department_name;
          email = temp.Data.details[0].email;

          if(position == null){
            position = temp.Data.details[0].position_id;
          }
          //$('#myModal .employee_select').selectpicker('destroy')
        }
      },
    });
    console.log(position);
    $("form .department_name").val(dept_id);
    $("form .position_name").val(position);
    $("form .username").val(email);
    $("form .email").val(email);
  });
  $(document).on("click", "#changeUserPassword", function (e) {
    if (
      $("#frmChangePass .newpass").val() != $("#frmChangePass .newpass2").val()
    ) {
      $.alert({
        title:
          '<span class="fa fa-warning text-warning"></span>&nbsp;<label class="text-danger">Failed</label>',
        content: "New Password and Current Password does not match",
        type: "red",
      });
    } else {
      $("#frmChangePass").submit();
    }
  });

  function ClearFields() {
		document.getElementById("specific").value = "";
   }

  $(document).on("change", "#user-status", function (e) {
    e.preventDefault();
    loadTable();
  });

  $(document).on("click", "#specificbtn", function (e) {
    e.preventDefault();
   loadSearch();
  });

  //Confirms
  $(document).on(
    "click",
    ".activateUserConfig,.deactivateUserConfig,.unlockUserConfig,.logoutUser,.forgotUserPassword,.grantAccessUserConfig",
    function (e) {
      e.preventDefault();
      me = $(this);
      url = me.attr("href");
      var id = me.attr("data-id");
      var userlevel = me.attr("data-userlevel");
      content = "Are you sure you want to proceed?";
      if (me.hasClass("activateUserConfig")) {
        content = "Are you sure you want to activate selected user?";
      } else if (me.hasClass("deactivateUserConfig")) {
        content = "Are you sure you want to deactivate selected user?";
      } else if (me.hasClass("unlockUserConfig")) {
        content = "Are you sure you want to unlock selected user?";
      } else if (me.hasClass("logoutUser")) {
        content = "Are you sure you want to logout selected user?";
      } else if (me.attr("id") == "resetPassword") {
        content = "Are you sure you want to reset user password?";
      } else if (me.attr("id") == "grantAccessUserConfig") {
        content = "Are you sure you want to grant PDS access this user?";
      }
      console.log(me);
      data = { id: id ,userlevel: userlevel };
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
                    data: data,
                    dataType: "json",
                    success: function (result) {
                      if (result.Code == "0") {
                        if (result.hasOwnProperty("key")) {
                          switch (result.key) {
                            case "logoutUser":
                            case "forgotUserPassword":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              break;
                            case "unlockUserConfig":
                            case "activateUserConfig":
                            case "deactivateUserConfig":
                            case "grantAccessUserConfig":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              loadTable();
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
                buttons: {
                  ok: function () {},
                },
              });
            },
          },
          cancel: function () {},
        },
      });
    }
  );
  /*$(document).on('click','#resetPassword',function(e){
        content = 'Are you sure you want to reset the password?';
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $.confirm({
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: commons.baseurl + 'usermanagement/UserConfig/forgotUserPassword',
                                    data: {
										email:$("#emailcontainer").val(),
										usermgmt: 1
									},
                                    dataType: "JSON",
                                    success: function(result){
										console.log(result);
                                        if(result.Code == "0"){
                                           self.setContent(result.Message);
											self.setTitle('<label class="text-success">Success</label>');
											$('#myModal').modal('hide');  
                                        }
                                        else{
                                            self.setContent(result.Message);
                                            self.setTitle('<label class="text-danger">Failed</label>');
                                        } 
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
							},
							buttons: {
								ok: function() {}
							}
                        });
                    }

                },
                cancel: function () {
                }
            }
        });
    });*/

  //Ajax non-forms
  $(document).on("click", "#addUserConfigForm,.updateUserConfigForm", function (
    e
  ) {
    e.preventDefault();
    my = $(this);
    if (page == my.attr("id") && my.attr("id") == "addUserConfigForm") {
      $("#myModal").modal("show");
    } else {
      getFields.reloadModal();
      $("#myModal").modal("show");
      id = my.attr("data-id");
      url = my.attr("href");
      $.ajax({
        type: "POST",
        url: url,
        data: { id: id },
        dataType: "json",
        success: function (result) {
          page = my.attr("id");
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "addUserConfig":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Register New User Account");
                $("#myModal .modal-body").html(result.form);

                $('#myModal .username').val('')
                $('#myModal .password').val('')

                break;
              case "updateUserConfig":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("User Details");
                $("#myModal .modal-body").html(result.form);
                employee_id = my.data("employee_id");

                break;
            }
            $.when(getFields.division()).done(function () {
              console.log(my.data())
              $.each(my.data(), function (i, v) {
                if(i != 'position_name'){
                  $("." + i)
                  .val(my.data(i))
                  .change();
                }
              });
              if($.isNumeric(my.data('position_id'))){
                $(".position_name").val(my.data('position_name')).change();
              }else{
                $(".position_name").val(my.data('position_id')).change();

              }
              if (result.key == "viewEmployees") {
                $("form")
                  .find("input, textarea, button, select")
                  .attr("disabled", "disabled");
                $("form").find("#cancelUpdateForm").removeAttr("disabled");
              }
              $.AdminBSB.select.activate();
            });
            $("#" + result.key).validate({
              rules: {
                ".required": {
                  required: true,
                },
                ".email": {
                  // required: true,
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
  });
  //Ajax Forms
  $(document).on(
    "submit",
    "#addUserConfig,#updateUserConfig,#frmChangePass",
    function (e) {
      e.preventDefault();
      form = $(this);
      content = "Are you sure you want to proceed?";
      if (form.attr("id") == "addUserConfig") {
        content = "Are you sure you want to add user?";
      }
      if (form.attr("id") == "updateUserConfig") {
        content = "Are you sure you want to update user?";
      }
      if (form.attr("id") == "frmChangePass") {
        content = "Are you sure you want to change user password?";
      }
      url = form.attr("action");
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
                              case "changePass":
                              case "addUserConfig":
                              case "updateUserConfig":
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
                                loadTable();
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
              });
            },
          },
          cancel: function () {},
        },
      });
    }
  );
  function loadTable() {
    var status = $("#user-status").val();
    var url = window.location.href;

    $.ajax({
      url: url,
      dataType: "json",
      data: { Status: status },
      type: "POST",
      success: function (result) {
        $(".card .body").find(".listTable").remove();
        $(".card .body").append(result.table);
      },
    });
  }

  function loadSearch(){
    var specific = $("#specific").val();
    var status = $("#user-status").val();
    var url = window.location.href;
    if(specific != ""){
       ClearFields();
    }
   
    $.ajax({
      url: url,
      dataType: "json",
      data: { specific: specific, Status: status },
      type: "POST",
      success: function (result) {
        $(".card .body").find(".listTable").remove();
        $(".card .body").append(result.table);
      },
    });
  }
});
