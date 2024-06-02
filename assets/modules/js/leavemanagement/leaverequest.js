$(function () {
  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 2, "td:eq(0),th:eq(0)");
  });
  loadTable();
  var vholidays;
  var leaveform = "";
  $(document).on("show.bs.modal", "#myModal", function () {
    $.when(getFields.holidays()).done(function () {});
    //$.AdminBSB.input.activate();
  });

  $(document).on(
    "change",
    "input[type=radio][name='table1[type]']",
    function () {
      if (
        $(this).val() === "rehab" ||
        $(this).val() === "maternity" ||
        $(this).val() === "study" ||
        $(this).val() === "benefits" ||
        $(this).val() === "violence"
      ) {
        $(".inclusiveDates, .isMedicalReason, .isTerminal").hide();
        $(".rehabDates").show();
      } else if ($(this).val() === "monetization") {
        $(
          ".inclusiveDates:eq(2),.inclusiveDates:eq(3),.rehabDates, .isTerminal, .offsetting-container"
        ).hide();
        $(
          ".inclusiveDates:eq(0),.inclusiveDates:eq(1), .isMedicalReason"
        ).show();
      } else {
        $(".inclusiveDates").show();
        $(".rehabDates, .isMedicalReason, .isTerminal").hide();
      }

      if($(this).val() === "sick"){
        $(".days_of_leave").daterangepicker({
          timePicker: false,
          autoApply: false,
          drops: "up",
          locale: { format: "YYYY-MM-DD" },
          maxDate: moment().add(6, "months"),
          parentEl: "#myModal .modal-body"  
        });
      } else if($(this).val() === "vacation" || $(this).val() === "force" || $(this).val() === "special"){
        $(".days_of_leave").daterangepicker({
          timePicker: false,
          autoApply: false,
          drops: "up",
          locale: { format: "YYYY-MM-DD" },
          // minDate: moment().startOf("day"),
          minDate: moment().add(5, "days"),
          maxDate: moment().add(6, "months"),
          parentEl: "#myModal .modal-body"  
        });
      }else{
        $(".days_of_leave").daterangepicker({
          timePicker: false,
          autoApply: false,
          drops: "up",
          locale: { format: "YYYY-MM-DD" },
          minDate: moment().startOf("day"),
          maxDate: moment().add(6, "months"),
          parentEl: "#myModal .modal-body"  
        });
      }

      if ($(this).val() === "monetization") {
        computeTotMonetized();
        $("input[type=number][name='table1[number_of_days]']").removeAttr(
          "readonly"
        );
        $("input[name='table2[days_of_leave][]']").removeAttr("required");
        $("input[name='table1[commutation]']")
          .prop("checked", true)
          .prop("readonly", true);
        $(".switch").css("pointer-events", "none");
      } else if ($(this).val() === "terminal") {
        $(".inclusiveDates,.rehabDates,.offsetting-container").hide();
        $(".isMedicalReason").hide();
        $(".isTerminal").show();
        $("#isMedical").iCheck("uncheck");
      } else if ($(this).val() === "offset") {
        $(".offsetting-container").show();
        $(".inclusiveDates,.rehabDates").hide();
        $(".isMedicalReason").hide();
        $(".isTerminal").hide();
        $("#isMedical").iCheck("uncheck");
      }else {
        $("input[name='table1[amount_monetized]']").val("0.00");
        $("input[type=number][name='table1[number_of_days]']").prop(
          "readonly",
          true
        );
        $("input[name='table2[days_of_leave][]']").prop("required", true);
        $("input[name='table1[commutation]']")
          .prop("checked", false)
          .prop("readonly", false);
        $(".switch").css("pointer-events", "");
        if($("form").attr("id") == "addLeaveRequest") addValidateDate();
        $("#isMedical").iCheck("uncheck");
      }
    }
  );

  $(document).on("change", "input[name='table1[number_of_days]']", function () {
    if (
      $("input[type=radio][name='table1[type]']:checked").val() ===
      "monetization" || $("input[type=radio][name='table1[type]']:checked").val() ===
      "paternity" 
    )
      computeTotMonetized();
    else $("input[name='table1[amount_monetized]']").val("0.00");
  });

  //Confirms
  $(document).on('click', '.cancelLeaveRequestForm', function (e) {
    e.preventDefault();
    var me = $(this);
    var url = me.attr('href');
    var id = me.attr('data-id');
    $.confirm({
      title: '<label class="text-warning">Confirm!</label>',
      content: 'Are you sure you want to proceed?',
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
                  url: url,
                  data: {id: id},
                  dataType: "json",
                  success: function (result) {
                    if (result.Code == "0") {
                            self.setContent(result.Message);
                            self.setTitle('<label class="text-success">Success</label>');
                            loadTable();
                    } else {
                      self.setContent(result.Message);
                      self.setTitle('<label class="text-danger">Failed</label>');
                    }
                  },
                  error: function (result) {
                    self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                    self.setTitle('<label class="text-danger">Failed</label>');
                  }
                });
              }
            });
          }

        },
        cancel: function () {}
      }
    });
  });

  //Ajax non-forms
  $(document).on(
    "click",
    "#addLeaveRequestForm,.updateLeaveRequestForm,.viewLeaveRequestDetails",
    function (e) {
      e.preventDefault();
      me = $(this);
      id = me.attr("data-id");
      url = me.attr("href");
      getFields.reloadModal();
      $.ajax({
        type: "POST",
        url: url,
        data: { id: id },
        dataType: "json",
        success: function (result) {
          page = me.attr("id");
          if (result.hasOwnProperty("key")) {
            var sess_division_id = $("#sess_division_id").val();
            var sess_employee_id = $("#sess_employee_id").val();
            var sess_position = $("#sess_position").val();
            var sess_salary = $("#sess_salary").val();

            switch (result.key) {
              case "addLeaveRequest":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Add New Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(".offsetting-container").hide();daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  drops: "up",
                  locale: { format: "YYYY-MM-DD" },
                  minDate: moment().startOf("day"),
                  maxDate: moment().add(6, "months"),
                  parentEl: "#myModal .modal-body"  
                });
                $("input[type=radio][name='table1[type]']:checked").val();

                $(".paternity_vacation_dates").removeClass("days_of_leave");
                $(".paternity_vacation").hide();
                $(".not_paternity_vacation").show();

                $(".days_of_leave").daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  drops: "up",
                  locale: { format: "YYYY-MM-DD" },
                  minDate: moment().startOf("day"),
                  maxDate: moment().add(6, "months"),
                  parentEl: "#myModal .modal-body"  
                });

                $("#rehabilitation_dates, #study_dates").daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  drops: "up",
                  locale: { format: "YYYY-MM-DD" },
                  minDate: moment().startOf("day"),
                  maxDate: moment().add(6, "months"),
                  parentEl: "#myModal .modal-body"  
                });

                $(".sick_leave_dates").daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  drops: "up",
                  locale: { format: "YYYY-MM-DD" },
                  // minDate: moment().startOf("day"),
                  maxDate: moment().add(6, "months"),
                  parentEl: "#myModal .modal-body"  
                });



                $(".chk").iCheck("destroy");
                $(".chk").iCheck({
                  checkboxClass: "icheckbox_square-blue",
                });
                $(".isMedicalReason").hide();
                break;
              case "updateLeaveRequest":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Add New Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");

                $("#radio_" + me.data("type"))
                  .change()
                  .click();
                $(".chk").iCheck("destroy");
                $(".chk").iCheck({
                  checkboxClass: "icheckbox_square-blue",
                });
                if (
                  (me.data("ismedical") == "1" || me.data("ismedical") == 1) &&
                  me.data("type") == "monetization"
                ) {
                  $("#isMedical").iCheck("check");
                  $(
                    ".inclusiveDates:eq(2),.inclusiveDates:eq(3),.rehabDates, .isTerminal"
                  ).hide();
                  $(
                    ".inclusiveDates:eq(0),.inclusiveDates:eq(1), .isMedicalReason"
                  ).show();
                }
                if (me.data("type") == "terminal") {
                  $("#isTerminal").val(me.data("is_terminal"));
                  $(".inclusiveDates,.rehabDates").hide();
                  $(".isMedicalReason").hide();
                  $(".isTerminal").show();
                }
                $.each(me.data(), function (i, v) {
                  if (i == "vacation_loc") {
                    $("#radio_vacation_" + me.data(i))
                      .change()
                      .click();
                  }
                  if (i == "commutation" && v == "Requested") {
                    $(".commutation").attr("checked", true).change();
                  }
                  if (i == "sick_loc") {
                    $("#radio_sick_" + me.data(i))
                      .change()
                      .click();
                  }
                  if (i == "type_study") {
                    $("#radio_study_" + me.data(i))
                      .change()
                      .click();
                  }
                  el = $("." + i);
                  el_type = $("." + i).attr("type");
                  if (
                    el.is("select") ||
                    el.is("textarea") ||
                    el_type == "text" ||
                    el_type == "hidden" ||
                    el_type == "number"
                  ) {
                    $("." + i)
                      .val(me.data(i))
                      .change();
                  }
                });

                $("#viewAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/"+me.data("employee_id")+"/" +
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/"+me.data("employee_id")+"/" +
                    me.data("filename")
                );
                if (me.data("filename") == "") {
                  $("#viewAttachment").hide();
                  $("#downloadAttachment").hide();
                }
                var getLeaveDates = (function () {
                  var temp = null;
                  url2 =
                    commons.baseurl +
                    "leavemanagement/LeaveRequest/getLeaveDates";
                  data2 = { id: id };
                  $.ajax({
                    async: false,
                    url: url2,
                    data: data2,
                    type: "POST",
                    dataType: "JSON",
                    success: function (res) {
                      temp = res;
                    },
                  });

                  return temp;
                })();
                if (getLeaveDates.Code == 0) {
                  $(".days_of_container").html("");
                  $.each(getLeaveDates.Data.details, function (i, v) {
                    if (i == 0) {
                      button = "";
                    } else {
                      button =
                        '<button type="button" class="removeLeaveDays btn btn-danger btn-circle waves-effect waves-circle waves-float">' +
                        '<i class="material-icons">remove</i>' +
                        "</button>";
                    }
                    leaveform =
                      "" +
                      '<div class="col-md-10">' +
                      '<div class="form-group form-float">' +
                      '<div class="form-line">' +
                      '<input type="text" class="days_of_leave not_paternity_vacation_dates form-control" value="' +
                      v.days_of_leave +
                      '" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>' +
                      "</div>" +
                      "</div>" +
                      "</div>" +
                      '<div class="col-md-2 text-right">' +
                      button +
                      "</div>";
                    $(".days_of_container").append(leaveform);
                  });
                  $(".daysleave").daterangepicker({
                    timePicker: false,
                    autoApply: false,
                    drops: "up",
                    locale: { format: "YYYY-MM-DD" },
                    minDate: moment().startOf("day"),
                    maxDate: moment().add(6, "months"),
                    parentEl: "#myModal .modal-body"  
                  });
                }
                break;
              case "viewLeaveRequestDetails":

                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Leave Details");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(".number_of_hrs").val( me.attr("data-offset_hrs"))
                $(".number_of_mins").val( me.attr("data-offset_mins"))
                $(".days_of_offset").val( me.attr("data-offset_date_effectivity"))
                $(".hide_inclusive_dates").hide("show");
                $(".days_of_offset").hide();
                
                
                var rad = me.data("type");
                if (
                  (me.data("ismedical") == "1" || me.data("ismedical") == 1) &&
                  me.data("type") == "monetization"
                ) {
                  $("#isMedical").iCheck("check");
                  $(
                    ".inclusiveDates:eq(2),.inclusiveDates:eq(3),.rehabDates, .isTerminal"
                  ).hide();
                  $(
                    ".inclusiveDates:eq(0),.inclusiveDates:eq(1), .isMedicalReason"
                  ).show();
                }
                if (me.data("type") == "terminal") {
                  $("#isTerminal").val(me.data("is_terminal"));
                  $(".inclusiveDates,.rehabDates").hide();
                  $(".isMedicalReason").hide();
                  $(".isTerminal").show();
                }
               
                if($("#radio_paternity").is(":checked")){
                  $(".paternity_vacation").show();
                    $(".not_paternity_vacation").hide();
                    $('.paternity_vacation_dates').val(me.data("inclusive_dates"));
                }else if ($("#radio_vacation").is(":checked")){
                    $(".paternity_vacation").show();
                    $(".not_paternity_vacation").hide();
                    $('.paternity_vacation_dates').val(me.data("inclusive_dates"));
                }else{
                    $(".paternity_vacation").hide();
                    $(".not_paternity_vacation").show();
                            
                }

                if($("#radio_offset").is(":checked")){
                  $('.no_hours').show();
                  $('.no_mins').show();
                }else{
                    $('.no_mins').hide();
                    $('.no_hours').hide();              
                }
                
                $("#radio_" + me.data("type"))
                  .change()
                  .click();
                $.each(me.data(), function (i, v) {
                  if (i == "vacation_loc") {
                    $("#radio_vacation_" + me.data(i))
                      .change()
                      .click();
                  }
                  if (i == "commutation" && v == "Requested") {
                    $(".commutation").attr("checked", true).change();
                  }
                  if (i == "sick_loc") {
                    $("#radio_sick_" + me.data(i))
                      .change()
                      .click();
                  }
                  if (i == "type_study") {
                    $("#radio_study_" + me.data(i))
                      .change()
                      .click();
                  }
                  el = $("." + i);
                  el_type = $("." + i).attr("type");
                  if (
                    el.is("select") ||
                    el.is("textarea") ||
                    el_type == "text" ||
                    el_type == "hidden" ||
                    el_type == "number"
                  ) {
                    $("." + i)
                      .val(me.data(i))
                      .change();
                  }
                });

                $("#viewAttachment").attr(
                  "href",
                  commons.baseurl +
                  "assets/uploads/leaveattachment/"+me.data("employee_id")+"/" +
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                  "assets/uploads/leaveattachment/"+me.data("employee_id")+"/" +
                    me.data("filename")
                );
                if (me.data("filename") == "") {
                  $("#viewAttachment").hide();
                  $("#downloadAttachment").hide();
                }

                var getLeaveDates = (function () {
                  var temp = null;
                  url2 =
                    commons.baseurl +
                    "leavemanagement/LeaveRequest/getLeaveDates";
                  data2 = { id: id };
                  $.ajax({
                    async: false,
                    url: url2,
                    data: data2,
                    type: "POST",
                    dataType: "JSON",
                    success: function (res) {
                      temp = res;
                    },
                  });

                  return temp;
                })();

                if (getLeaveDates.Code == 0) {
                  $(".days_of_container").html("");
                  $.each(getLeaveDates.Data.details, function (i, v) {
                    var button = "";
                    if (
                      rad == "rehab" ||
                      rad == "maternity" ||
                      rad == "study" ||
                      rad == "benefits" ||
                      rad == "violence"
                    )
                      $("#rehabilitation_dates").val(v.days_of_leave);
                    else {
                      leaveform =
                        "" +
                        '<div class="col-md-10">' +
                        '<div class="form-group form-float">' +
                        '<div class="form-line">' +
                        '<input type="text" class="days_of_leave not_paternity_vacation_dates form-control" value="' +
                        v.days_of_leave +
                        '" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-2 text-right">' +
                        button +
                        "</div>";
                    }
                    $(".days_of_container").append(leaveform);
                  });
                }
                $("form :input").attr("disabled", true);
                $("#cancelUpdateForm").removeAttr("disabled");
                break;
        
            }

            $.when(getFields.division()).done(function () {
              $("#division_id").val($("#sess_division_id").val()).change();
              $("#division_id").css("pointer-events", "none");
              $.when(
                getFields.employee({division_id: $("#sess_division_id").val(),pay_basis: "Permanent",})
              ).done(function () {
                $("#employee_id").val($("#sess_employee_id").val()).change();
                $("#employee_id").css("pointer-events", "none");
              
                $.when(getFields.position({ pay_basis: "Permanent" })).done(
                  function () {
                    $("#position_id").val($("#sess_position").val()).change();
                    $("#position_id").css("pointer-events", "none");
                    $("input[name='table1[salary]']").val($("#sess_salary").val());
                    $("#salary").css("pointer-events", "none");
                    $("#filing_date").css("pointer-events", "none");
                  }
                );
              });
            });

            $.validator.addMethod(
              "ismonet",
              function (value, element) {
                if (
                  $("input[name='table1[type]']:checked").val() ==
                    "monetization" &&
                  $("#isMedical").is(":checked") == false
                ) {
                  if (
                    parseInt($("#vl_leave").val()) >= 15 &&
                    parseInt($("#number_of_days").val()) >= 10
                  )
                    return true;
                  else return false;
                } else {
                  return true;
                }
              },
              "No. of Days applied for should be >= 10 and VL should be >= 15"
            );

            $("#" + result.key).validate({
              rules: {
                "table1[number_of_days]": {
                  ismonet: true,
                },
                "table1[isTerminal]": {
                  required: function (data) {
                    return (
                      $(".type:checked").val() == "terminal" &&
                      $("#isTerminal").val().trim() == ""
                    );
                  },
                  normalizer: function (value) {
                    return $.trim(value);
                  },
                },
                "table1[vacation_loc]": {
                  required: function (data) {
                    return (
                      ($(".type:checked").val() == "vacation" || $(".type:checked").val() == "special") &&
                      ($("input[name='table1[vacation_loc]']:checked").val() ==
                        "" ||
                        $("input[name='table1[vacation_loc]']:checked").val() ==
                          undefined)
                    );
                  },
                },
                "table1[vacation_loc_details]": {
                  required: function (data) {
                    return (
                      ($(".type:checked").val() == "vacation" || $(".type:checked").val() == "special") &&
                      $("input[name='table1[vacation_loc_details]']").val() ==
                        ""
                    );
                  },
                },
                "table1[table1[sick_loc]]": {
                  required: function (data) {
                    return (
                      $(".type:checked").val() == "sick" &&
                      ($("input[name='table1[sick_loc]']:checked").val() ==
                        "" ||
                        $("input[name='table1[sick_loc]']:checked").val() ==
                          undefined)
                    );
                  },
                },
                "table1[sick_loc_details]": {
                  required: function (data) {
                    return (
                      $(".type:checked").val() == "sick" &&
                      $("input[name='table1[sick_loc_details]']").val() == ""
                    );
                  },
                },
                "table1[table1[type_study]]": {
                  required: function (data) {
                    return (
                      $(".type:checked").val() == "study" &&
                      ($("input[name='table1[type_study]']:checked").val() ==
                        "" ||
                        $("input[name='table1[type_study]']:checked").val() ==
                          undefined)
                    );
                  },
                },
              },
              highlight: function (input) {
                $(input).parents(".form-line").addClass("error");
                if ($(input).attr("name") == "table1[vacation_loc]")
                  dialogErrorV2("Select location.");
                if ($(input).attr("name") == "table1[sick_loc]")
                  dialogErrorV2("Select Sick type");
                if ($(input).attr("name") == "table1[type_study]")
                  dialogErrorV2("Select Study type.");
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
          errorDialog();
        },
      });
    }
  );

  //Ajax Forms
  $(document).on("submit", "#addLeaveRequest,#updateLeaveRequest", function (
    e
  ) {
    e.preventDefault();
    var form = $(this);
    if (!$(".type").is(":checked")) {
      $.alert({
        title: '<label class="text-danger">Failed</label>',
        content: "Select Type of Leave.",
      });
      return false;
    } else {
      var leavetype = $(".type:checked").val();
      var lv = ["vacation","sick","monetization"];
      if($.inArray(leavetype,lv) != -1){
        if(leavetype == 'vacation'){
          if(parseFloat($("#number_of_days").val()) > parseFloat($("#vl_leave").val())){
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content:
                "Insufficient leave balance.",
            });
            return false;
          }
        }else if (leavetype == 'sick'){
          if(parseFloat($("#number_of_days").val()) > parseFloat($("#sl_leave").val())){
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content:
                "Insufficient leave balance.",
            });
            return false;
          }
        }else{
          if(parseFloat($("#number_of_days").val()) > parseFloat($("#total_available_leave").val())){
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content:
                "Insufficient leave balance.",
            });
            return false;
          }
        }
      }
      if(leavetype == 'special'){        
        if(parseFloat($("#number_of_days").val()) > parseFloat($("#spl_leave").val())){
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content:
              "Insufficient leave balance.",
          });
          return false;
        }
      }
      if(leavetype == 'force'){        
        if(parseFloat($("#number_of_days").val()) > parseFloat($("#fl_leave").val())){
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content:
              "Insufficient leave balance.",
          });
          return false;
        }
        
        if(parseFloat($("#number_of_days").val()) > parseFloat($("#vl_leave").val())){
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content:
              "Insufficient leave balance.",
          });
          return false;
        }
      }

      if (!leavetype == "") {
        if (leavetype == "vacation") {
          var vacationloc = $(".vacation_loc:checked").val();
          var vacationlocdetails = $(".vacation_loc_details").val();
        } else if (leavetype == "sick") {
          var sick_loc = $(".sick_loc:checked").val();
          var sick_loc_details = $(".sick_loc_details").val();
          if (typeof sick_loc === "undefined") {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "Select Between Hospital or Out Patient.",
            });
            return false;
          }

          if (sick_loc_details == "") {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "Specify Illness.",
            });
            return false;
          }
        } else if (leavetype == "study") {
          var type_study = $(".type_study:checked").val();
          if (typeof type_study === "undefined") {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content:
                "Select Between Completion of Master's Degree or Board Examination Review.",
            });
            return false;
          }
        } else if (leavetype == "offset"){
         
          let offsetBal = parseInt(($(".totalOffset").attr("data-offset-hrs") * 60)) + parseInt($(".totalOffset").attr("data-offset-mins"));
          let inputedOffsetBal = parseInt(($(".number_of_hrs").val() * 60)) + parseInt($(".number_of_mins").val())
          // alert(inputedOffsetBal)
          if(inputedOffsetBal > offsetBal){
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content:
                "Insufficient Offset Balance",
            });
            return false;

          }
        }
      }
    }

    //Owen End
    content = "Are you sure you want to proceed?";
    if (form.attr("id") == "addLeaveRequest"){
      content = "Are you sure you want to request for this leave?";
      addValidateDate();
      content += '<div class="form-group">';
      content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
      content += '<div class="form-group form-float">';
      content += '<div class="form-line">';
      content +=
        '<input type="file" id="sig_file" name="sig_file" class="sig_file form-control">';
      content += "</div>";
      content += "</div>";
      content += "</div>";
      content += "</form>";
    }
    if (form.attr("id") == "updateLeaveRequest")
      content = "Are you sure you want to update?";

    if($("#radio_paternity").is(":checked")){
      $(".paternity_vacation_dates").addClass("days_of_leave");
       $(".not_paternity_vacation .days_of_leave").attr("disabled","disabled");
    }else if ($("#radio_vacation").is(":checked")){
        $(".paternity_vacation_dates").addClass("days_of_leave");
        $(".not_paternity_vacation .days_of_leave").attr("disabled","disabled");
    }else{
      $(".not_paternity_vacation .days_of_leave").removeAttr("disabled","disabled");
    }

    url = form.attr("action");
    var formData = new FormData(form[0]);
    $.confirm({
      title: '<label class="text-warning">Confirm!</label>',
      content: content,
      type: "orange",
      buttons: {
        confirm: {
          btnClass: "btn-blue",
          action: function () {
            //Code here
            if (form.attr("id") == "addLeaveRequest") {
              if($("#sig_file")[0].files[0] == 'undefined' || $("#sig_file")[0].files[0] == null){
                $(".esign").css("display","block")
                return false;
              }
              formData.append("sig_file", $("#sig_file")[0].files[0]);
            }
            for (const value of formData.values()) {
              console.log(value);
            }
            $.confirm({
              content: function () {
                var self = this;
                return $.ajax({
                  type: "POST",
                  url: url,
                  data: formData,
                  contentType: false,
                  processData: false,
                  dataType: "json",
                  success: function (result) {
                    if (result.hasOwnProperty("key")) {
                      if (result.Code == "0") {
                        if (result.hasOwnProperty("key")) {
                          switch (result.key) {
                            case "addLeaveRequest":
                            case "updateLeaveRequest":
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
                    self.setTitle('<label class="text-danger">Failed</label>');
                  },
                });
              },
            });
          },
        },
        cancel: function () {},
      },
    });
  });

  // $(document).on("click", ".addLeaveDays", function () {
  //   var inc = $("input.days_of_leave:last").attr("name");
  //   var inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
  //   leaveform =
  //     "" +
  //     '<div class="col-md-10">' +
  //     '<div class="form-group form-float">' +
  //     '<div class="form-line">' +
  //     '<input type="text" class="days_of_leave datepicker form-control" name="table2[days_of_leave][' +
  //     inc +
  //     ']" placeholder="yyyy-mm-dd" required>' +
  //     "</div>" +
  //     "</div>" +
  //     "</div>" +
  //     '<div class="col-md-2 text-right">' +
  //     '<button type="button" class="removeLeaveDays btn btn-danger btn-circle waves-effect waves-circle waves-float">' +
  //     '<i class="material-icons">remove</i>' +
  //     "</button>" +
  //     "</div>";
  //   $(".days_of_container").append(leaveform);
  //   $(".number_of_days").val(parseInt($(".number_of_days").val()) + 1);
    
    
  //   $(".days_of_leave").bootstrapMaterialDatePicker({
  //     format: "YYYY-MM-DD",
  //     clearButton: false,
  //     time: false,
  //     minDate : new Date()
  //     });
  //   $.when(getFields.holidays()).done(function () {});
  //   computeTotMonetized();
  //   addValidateDate();
  // });

  $(document).on("click", ".removeLeaveDays", function (e) {
    $(this).closest("div").prev().remove();
    $(this).closest("div").remove();
    $(".number_of_days").val(parseInt($(".number_of_days").val()) - 1);
    computeTotMonetized();
  });

  $(document).on("click", "#changeAttachment", function (e) {
    e.preventDefault();
    $("#updateFileButtons").hide();
    $("#hiddenFileInput").show();
  });

  $(document).on("click", "#cancelChange", function (e) {
    e.preventDefault();
    $("#updateFileButtons").show();
    $("#hiddenFileInput").hide();
  });

  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  $(document).on("change", ".type", function () {
    if (
      $("#radio_vacation").is(":checked") ||
      $("#radio_special").is(":checked") ||
      $("#radio_benefits").is(":checked")
    ) {
      $("#spl_other_content").css('display', 'none');
      if($("#radio_special").is(":checked")){
        $("#spl_other_content").css('display', 'block');
        $(".spl_other_details").removeAttr("disabled");
      }
      if($("#radio_benefits").is(":checked")){
        $(".sick_loc").removeAttr("disabled");
        $(".sick_loc_details").removeAttr("disabled");
      }else{
        $(".sick_loc").attr("disabled", true);
        $(".sick_loc").attr("checked", false);
        $(".sick_loc_details").attr("disabled", true);
        $(".sick_loc_details").val("");
      }
      $(".vacation_loc").removeAttr("disabled");
      $(".vacation_loc_details").removeAttr("disabled");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    } else if ($("#radio_sick").is(":checked")) {
      $("#spl_other_content").css('display', 'none');
      $(".sick_loc").removeAttr("disabled");
      $(".sick_loc_details").removeAttr("disabled");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".spl_other_details").attr("disabled", true);
      $(".spl_other_details").val("");
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    } else if ($("#radio_study").is(":checked")) {
      $("#spl_other_content").css('display', 'none');
      $(".sick_loc").attr("disabled", true);
      $(".sick_loc").attr("checked", false);
      $(".sick_loc_details").attr("disabled", true);
      $(".sick_loc_details").val("");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".spl_other_details").attr("disabled", true);
      $(".spl_other_details").val("");
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").removeAttr("disabled");
    } else {
      $("#spl_other_content").css('display', 'none');
      $(".sick_loc").attr("disabled", true);
      $(".sick_loc").attr("checked", false);
      $(".sick_loc_details").attr("disabled", true);
      $(".sick_loc_details").val("");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".spl_other_details").attr("disabled", true);
      $(".spl_other_details").val("");
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    }
  });

  function computeTotMonetized() {
    if (
      $("input[type=radio][name='table1[type]']:checked").val() ==
      "monetization"
    ) {
      var tot =
        parseInt($(".number_of_days").val()) *
        parseFloat($("input[name='table1[salary]']").val().replace(/,/g, ""));
      var grandTot = tot * 0.0481927;
      $("input[name='table1[amount_monetized]']").val(
        grandTot.toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      );
    }
  }

  $(document).on("click", ".applyBtn", function(){
    var inclusivedaterange= $('.days_of_leave').val();
    // if( $('.paternity_vacation_dates').val() != "" ||  $('.paternity_vacation_dates').val() != null){
    //   inclusivedaterange= $('.paternity_vacation_dates').val();
    // }
    // if( $('.not_paternity_vacation_dates').val() != "" ||  $('.not_paternity_vacation_dates').val() != null){
    //   inclusivedaterange= $('.not_paternity_vacation_dates').val();
    // }
     var splitDate = inclusivedaterange.split(' - ');
     var firstDate = splitDate[0];
     var secondDate = splitDate[1];
     var noWorkingDays = datediff(parseDate(firstDate), parseDate(secondDate));
    //  $('.number_of_days').val(noWorkingDays +1);
    $('#saveLeaveRequest').prop("disabled", true);
     $.ajax({
      type: "POST",
      url: commons.baseurl + "leavemanagement/LeaveRequest/validateDaterange",
      data: {inclusivedaterange: inclusivedaterange},
      dataType: "json",
      success: function (result) {
        $('#saveLeaveRequest').prop("disabled", false);
        if (result.Code == "0") {
          $('.number_of_days').val(result.Data['number_of_days']);
        } else {
          $('.number_of_days').val("");
        }
      },
      error: function (result) {
        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
        self.setTitle('<label class="text-danger">Failed</label>');
      }
    });


     computeTotMonetized();
});

  // function loadTable() {
  //   kind = $("#kind_search").val();
  //   plus_url = "";
  //   if (kind != "") {
  //     plus_url = "?Kind=" + kind;
  //   }
  //   table = $("#datatables").DataTable({
  //     processing: true,
  //     serverSide: true,
  //     scrollCollapse: true,
  //     autoWidth: false,
  //     order: [],
  //     ajax: {
  //       url:
  //         commons.baseurl + "leavemanagement/LeaveRequest/fetchRows" + plus_url,
  //       type: "POST",
  //     },
  //     columnDefs: [{ orderable: false }],
  //   });
  // }

  $(document).on("click", "#btnsearch", function(){
      loadTable();
  });

  function loadTable(){
      $("#datatables").DataTable().clear().destroy();
      my = $(this);
      url = my.attr("href");
      status = $("#status").val();
      table = $('#datatables').DataTable({  
          "processing":true,  
          "serverSide":true,  
          // "stateSave": true, // presumably saves state for reloads -- entries
          // "bStateSave": true, // presumably saves state for reloads -- page number
          "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
          ],
          "order":[],
          scroller: {
              displayBuffer: 20
          },
          "columnDefs": [ {
            "targets"  : [0],
            "orderable": false
          }],
          initComplete : function() {
              
              var input = $('.dataTables_filter input').unbind(),
              self = this.api(),
              $searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
              .html('<i class="material-icons">search</i>')
              .click(function() {
                  
                  if(!$('#search-employee').is(':disabled')){
                      $('#search-employee').attr('disabled',true);
                      self.search(input.val()).draw();
                      $('#datatables button').attr('disabled',true);
                      $('.dataTables_filter').append('<div id="search-loader"><br>' 
                          +'<div class="preloader pl-size-xs">'
                          +    '<div class="spinner-layer pl-red-grey">'
                          +        '<div class="circle-clipper left">'
                          +            '<div class="circle"></div>'
                          +        '</div>'
                          +        '<div class="circle-clipper right">'
                          +            '<div class="circle"></div>'
                          +        '</div>'
                          +    '</div>'
                          +'</div>'
                          +'&emsp;Please Wait..</div>');
                  }
              })
              if  ($("#search-employee").length === 0) {
                $('.dataTables_filter').append($searchButton);
              }
          },
          "drawCallback": function( settings ) {
              $('#search-loader').remove();
              $('#search-employee').removeAttr('disabled');
              $('#datatables button').removeAttr('disabled');
          },
          "ajax":{  
            
           url:commons.baseurl+ "leavemanagement/LeaveRequest/fetchRows", //?status=" + $("#status").val(),
            type:"POST",
            data: {status:status}
          },  
          oLanguage: {sProcessing: '<div class="preloader pl-size-sm">'
                                  +'<div class="spinner-layer pl-red-grey">'
                                  +    '<div class="circle-clipper left">'
                                  +        '<div class="circle"></div>'
                                  +    '</div>'
                                  +    '<div class="circle-clipper right">'
                                  +        '<div class="circle"></div>'
                                  +    '</div>'
                                  +'</div>'
                                  +'</div>'}
      });
  }
  function reloadTable() {
    table.ajax.reload();
  }

  function PrintElem(elem) {
    var mywindow = window.open("", "PRINT", "height=400,width=600");
    mywindow.document.write(
      "<html moznomarginboxes mozdisallowselectionprint><head>"
    );
    mywindow.document.write("</head><body >");
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write("</body></html>");

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
  }
});

function addValidateDate() {

  $("input.days_of_leave").each(function () {
    $(this).rules("add", {
      required: function (element) {
        return $(element).val().trim() == "";
      },
      normalizer: function (value) {
        return $.trim(value);
      },
    });
  });
}

function parseDate(firstDate) {
    var mdy = firstDate.split('-');
    return new Date(mdy[0], mdy[1]-1, mdy[2]);
}

function datediff(firstDate, secondDate) {
    // Take the difference between the dates and divide by milliseconds per day.
    // Round to nearest whole number to deal with DST.
    return Math.round((secondDate-firstDate)/(1000*60*60*24));
}



