$(function () {
  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 2, "td:eq(0),th:eq(0)");
  });
  var page = "";
  base_url = commons.base_url;
  var table;
  var lvs = [
    "benefits",
    "calamity",
    "force",
    "monetization",
    "paternity",
    "sick",
    "solo",
    "special",
    "study",
    "terminal",
    "vacation",
    "violence",
  ];
  loadTable();
  $(document).on("change", "#kind_search", function (e) {
    url = commons.baseurl + "leavemanagement/CTOApproval/";
    url += "?Kind=" + $(this).val();
    window.location = url;
  });

  $(document).on("show.bs.modal", "#myModal", function () {
    $(".datepicker").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      weekStart: 1,
      time: false,
    });
    $.AdminBSB.select.activate();
  });

  $(document).on(
    "change",
    "input[type=radio][name='table1[type]']",
    function () {
      if ($(this).val() === "rehab" || $(this).val() === "maternity") {
        $(".inclusiveDates").hide();
        $(".rehabDates").show();
      } else {
        $(".inclusiveDates").show();
        $(".rehabDates").hide();
      }
      if ($(this).val() === "monetization") {
        $(".offsetting-container").hide();
        computeTotMonetized();
      }
       if ($(this).val() === "offset") {
        $(".inclusiveDates, .isMedicalReason").hide();
      }

      else $("input[name='table1[amount_monetized]']").val("0.00");
    }
  );

  $(document).on("change", "input[name='table1[number_of_days]']", function () {
    if (
      $("input[type=radio][name='table1[type]']:checked").val() ===
      "monetization"
    )
      computeTotMonetized();
    else $("input[name='table1[amount_monetized]']").val("0.00");
  });

  $(document).on("click", "#printCTOApproval", function (e) {
    e.preventDefault();
    printPrev(document.getElementById("servicerecords-container").innerHTML);
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

  $(document).on("change", ".leave_from", function (e) {
    from = $(this).val();
    $("#leave_to").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      startDate: from,
      minDate: from,
      weekStart: 1,
      destroy: true,
      time: false,
    });
    $("#leave_to").bootstrapMaterialDatePicker("setMinDate", from);
    $("#leave_to").bootstrapMaterialDatePicker("setStartDate", from);
  });

  var employee_id = null;
  var page = null;

  $(document).on("change", "#division_id ", function (e) {
    division_id = $(this).val();
    $.when(
      getFields.employee({ division_id: division_id, pay_basis: "Permanent" })
    ).done(function () {
      $("#employee_id").val(employee_id).change();
      if (
        page != "viewCTOApprovalSpecialDetails" &&
        page != "viewCTOApprovalRegularDetails"
      ) {
        $.AdminBSB.select.activate();
      } else {
        $("#employee_id").attr("disabled", true);
        $.AdminBSB.select.activate();
      }
    });
  });

  $(document).on("change", "#employee_id", function (e) {
    url = commons.baseurl + "leavemanagement/CTOApproval/getDays";
    employee_name = $(this).find("option:selected").text();
    var employee_id = $(this).find("option:selected").val();
    var MC6 = (MC8 = maternity = paternity = null);
    var date = new Date().toISOString().slice(0, 10);
    $("#employee_name").val(employee_name);
    if (employee_name != "") {
      $.ajax({
        url: url,
        data: {
          employee_id: employee_id,
          date: date,
          // employee_id2: employee_id,
          // date2: date,
          // employee_id3: employee_id,
          // date3: date,
          // employee_id4: employee_id,
          // date4: date,
          // employee_id5: employee_id,
          // date5: date,
        },
        type: "POST",
        dataType: "JSON",
        success: function (result) {
          if (result.Code == "0") {
            res = result.Data.details[0];
            MC6 = res.MC6_total;
            MC8 = res.MC8_total;
            maternity = res.maternity_total;
            paternity = res.paternity_total;
            force = res.force_total;

            if (MC6 == 3) $("#radio_MC6").attr("disabled", true);
            if (MC8 == 3) $("#radio_MC8").attr("disabled", true);
            if (maternity == 105) $("#radio_maternity").attr("disabled", true);
            if (paternity == 7) $("#radio_paternity").attr("disabled", true);
            if (force == 5) $("#radio_force").attr("disabled", true);
          }
        },
        error: function (result) {
          $(
            "#radio_MC6, #radio_MC8, #radio_maternity, #radio_paternity, #radio_force"
          ).removeAttr("disabled");
        },
      });
    }
  });

  $(document).on("click", ".addLeaveDays", function () {
    leaveform =
      "" +
      '<div class="row days_row clearfix">' +
      '<div class="col-md-12">' +
      '<div class="row">' +
      '<div class="col-md-10">' +
      '<label class="form-label" style="font-size: 1.25rem">' +
      "<!-- <small>From</small> -->" +
      "</label>" +
      '<div class="form-group form-float">' +
      '<div class="form-line">' +
      '<input type="text" class="days_of_leave daysleave form-control" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>' +
      "</div>" +
      "</div>" +
      "</div>" +
      '<div class="col-md-2 text-right">' +
      '<button type="button" class="removeLeaveDays btn btn-danger btn-circle waves-effect waves-circle waves-float">' +
      '<i class="material-icons">remove</i>' +
      "</button>" +
      "</div>" +
      "</div>" +
      "</div>" +
      "</div>";
    $(".days_of_container").append(leaveform);
    $(".number_of_days").val(parseInt($(".number_of_days").val()) + 1);
    $(".daysleave").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: false,
      weekStart: 1,
      time: false,
    });
  });

  $(document).on("click", ".removeLeaveDays", function (e) {
    $(this).closest(".days_row").remove();
    $(".number_of_days").val(parseInt($(".number_of_days").val()) - 1);
  });

  //Confirms
  $(document).on(
    "click",
    ".certifyCTOApproval, .recommendCTOApproval, .recommendCTOApproval2, .approveCTOApproval, .rejectCTOApproval",
    function (e) {

      e.preventDefault();
      var formData = new FormData();
      me = $(this);
      url = me.attr("href");
      var id = me.attr("data-id");
      var type = me.attr("data-type");
      var nodays = me.attr("data-number_of_days");
      var employee_id = me.attr("data-employee_id");
      var offset_hrs = me.attr("data-offset_hrs");
      var offset_mins = me.attr("data-offset_mins");
      var offset_date_effectivity = me.attr("data-offset_date_effectivity");
      var date_filed = me.attr("data-date_filed");
      var content = "Are you sure you want to proceed?";
      var remarks = "";
      formData.append("id", id);
      formData.append("employee_id", employee_id);
      formData.append("type", type);
      formData.append("nodays", nodays);
      formData.append("offset_hrs", offset_hrs);
      formData.append("offset_mins", offset_mins);
      formData.append("offset_date_effectivity", offset_date_effectivity);
      formData.append("date_filed", date_filed);
      console.log(me.hasClass());
      if (me.hasClass("certifyCTOApproval")) {
        content = "Are you sure you want to certify this CTO request?";
        content += '<div class="form-group">';
        content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="file" id="file" name="file" class="file form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      } else if (me.hasClass("recommendCTOApproval")) {
        content = "Are you sure you want to recommend this CTO request?";
        // content += '<div class="form-group">';
        // content += '<label class="form-label">E-Signature</label>';
        // content += '<div class="form-group form-float">';
        // content += '<div class="form-line">';
        // content +=
        //   '<input type="file" id="file" name="file" class="file form-control">';
        // content += "</div>";
        // content += "</div>";
        // content += "</div>";
        // content += "</form>";
      } else if (me.hasClass("recommendCTOApproval2")) {
        content = "Are you sure you want to recommend this CTO request?";
        content += '<div class="form-group">';
        content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="file" id="file" name="file" class="file form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      } else if (me.hasClass("approveCTOApproval")) {
        content = "Are you sure you want to approve this CTO request?";
        content += '<div class="form-group">';
        content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="file" id="file" name="file" class="file form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      } else if (me.hasClass("rejectCTOApproval")) {
        content = "Are you sure you want to disapprove this request?";
        content += '<div class="form-group">';
        content += '<label class="form-label">Remarks</label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="text" name="remarks" id="remarks" class="remarks form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += '<div class="form-group">';
        content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="file" id="file" name="file" class="file form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      }

      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              remarks =
                typeof this.$content.find(".remarks").val() === "undefined"
                  ? "N/A"
                  : this.$content.find(".remarks").val();
                  // if(remarks != "") formData.remarks = remarks;
              formData.append("remarks", remarks);  
              if (me.hasClass("certifyCTOApproval") || me.hasClass("recommendCTOApproval2") || me.hasClass("approveCTOApproval") || me.hasClass("rejectCTOApproval")) {
                if($("#file")[0].files[0] == 'undefined' || $("#file")[0].files[0] == null){
                  $(".esign").css("display","block")
                  return false;
                }
                formData.append("file", $("#file")[0].files[0]);
              }
              $.confirm({
                content: function () {
                  var self = this;
                  var jsondata = {
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (result) {
                      if (result.Code == "0") {
                        if (result.hasOwnProperty("key")) {
                          switch (result.key) {
                            case "certifyCTOApproval":
                            case "recommendCTOApproval":
                            case "recommendCTOApproval2":
                            case "approveCTOApproval":
                            case "rejectCTOApproval":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
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
                    },
                    error: function (result) {
                      self.setContent(
                        "There was an error in the connection. Please contact the administrator for updates."
                      );
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                    },
                  };
                  return $.ajax(jsondata);
                },
              });
            },
          },
          cancel: function () {},
        },
        onContentReady: function () {
          $(".chkDates").iCheck("destroy");
          $(".chkDates").iCheck({
            checkboxClass: "icheckbox_square-blue",
          });
        },
      });
    }
  );

  //Ajax non-forms
  $(document).on(
    "click",
    "#addCTOApprovalRegularForm,#addCTOApprovalSpecialForm,#generateCTOApprovalForm,.updateCTOApprovalRegularForm,.updateCTOApprovalSpecialForm,.viewCTOApprovalDetails",
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
            global_key = result.key;

            switch (result.key) {
              case "addCTOApprovalRegular":
              case "addCTOApprovalSpecial":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Add New Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(
                  "#other_vacation_type_content, #vacation_sick_header, #other_type_content, #abroad_location_content, #vacation_spent_content, #additional_info_header"
                ).hide();
                $(
                  "#hospital_name_content, #vacation_type_content, #special_dates, #sick_spent_content, #milestone_content, #domestic_content, #personal_content"
                ).hide();
                $(
                  "#calamity_content, #parental_content, #filial_content, #force_type_content, #force_remarks"
                ).hide();
                break;
              case "generateCTOApproval":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Employee Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                break;
              case "updateCTOApprovalSpecial":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Update Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                break;
              case "updateCTOApprovalRegular":
              case "viewCTOApprovalDetails":
                $.when(
                  // getFields.location()
                  getFields.division(),
                  getFields.position({ pay_basis: "Permanent" })
                ).done(function () {
                  
                  $(".division_id").selectpicker("val", me.data("division_id"));
                  $(".division_id").val(me.data("division_id")).change();
                  $(".position_id").selectpicker("val", me.data("position_id"));
                  $(".position_id").val(me.data("position_id")).change();
                  employee_id = me.data("employee_id");
                  $(".employee_id_2").selectpicker(
                    "val",
                    me.data("checked_by")
                  );
                  $(".type_id").selectpicker("val", me.data("type_id"));
                });
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Compensatory Time Off Details");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $("#no_of_hrs")
                  .attr("type", "text")
                  .val(me.data("offset_hrs"));
                 // $("#expected_return").removeAttr('disabled','disabled');
                $("#no_of_mins")
                  .attr("type", "text")
                  .val(me.data("offset_mins"));
                $('.number_of_days').val(me.data("number_of_days"));
                $('.inclusive_dates').val(me.data("offset_date_effectivity"));
                $("form :input").attr("disabled", true);
                $("#cancelUpdateForm").removeAttr("disabled");
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
    }
  );
  //Ajax Forms
  $(document).on(
    "submit",
    "#addCTOApprovalRegular,#updateCTOApprovalRegular,#addCTOApprovalSpecial,#updateCTOApprovalSpecial,#generateCTOApproval",
    function (e) {
      e.preventDefault();
      var form = $(this);
      //Owen Validation
      if (!$(".type").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Type of Leave not set, Please select one...",
        });
        return false;
      } else {
        var leavetype = $(".type:checked").val();

        if (!leavetype == "") {
          if (leavetype == "vacation") {
            var vacationtype = $(".type_vacation:checked").val();
            if (!vacationtype == "") {
            } else {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Type of vacation not set, Please select one..",
              });
              return false;
            }
          }
        }
      }

      //Owen End
      content = "Are you sure you want to proceed?";
      console.log('there')
      if (form.attr("id") == "addCTOApprovalRegular") {
        content = "Are you sure you want to add regular leave?";
      }
      if (form.attr("id") == "updateCTOApprovalRegular") {
        content = "Are you sure you want to update regular leave?";
      }
      if (form.attr("id") == "addCTOApprovalSpecial") {
        content = "Are you sure you want to add special leave?";
      }
      if (form.attr("id") == "updateCTOApprovalSpecial") {
        content = "Are you sure you want to update special leave?";
      }
      if (form.attr("id") == "generateCTOApproval") {
        content = "Are you sure you want to generate employee leave?";
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
                    data: new FormData(form[0]),
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (result) {
                      if (result.hasOwnProperty("key")) {
                        if (result.Code == "0") {
                          if (result.hasOwnProperty("key")) {
                            switch (result.key) {
                              case "addCTOApprovalRegular":
                              case "updateCTOApprovalRegular":
                              case "addCTOApprovalSpecial":
                              case "updateCTOApprovalSpecial":
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
                                $("#myModal .modal-body").html("");
                                $("#myModal").modal("hide");
                                loadTable();
                                break;
                              case "generateCTOApproval":
                                $("#myModal .modal-dialog").attr(
                                  "class",
                                  "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-title").html(
                                  "Employee Leave"
                                );
                                $("#myModal .modal-body").html(result.table);
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
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
  //Owen Codes
  //Regular Owen Codes

  $("#agency_name").alphanum({
    allowSpace: true,
    allowNumeric: false,
    maxLength: 50,
  });

  $("#first_name").alphanum({
    allowSpace: true,
    allowNumeric: false,
    maxLength: 30,
  });

  $("#middle_name").alphanum({
    allowSpace: true,
    allowNumeric: false,
    maxLength: 30,
  });

  $("#last_name").alphanum({
    allowSpace: true,
    allowNumeric: false,
    maxLength: 30,
  });

  $(document).on("click", ".type_vacation", function () {
    if ($("#radio_vacation_seek").is(":checked"))
      $("#other_vacation_type_content").hide();
    if ($("#radio_vacation_others").is(":checked"))
      $("#other_vacation_type_content").show();
  });

  $(document).on("click", ".force_status", function () {
    if ($("#force_status1").is(":checked")) $("#force_remarks").hide();
    if ($("#force_status0").is(":checked")) $("#force_remarks").show();
  });

  $(document).on("click", ".type_vacation_location", function () {
    $("#abroad_location_content").toggle();
  });

  $(document).on("click", ".type_sick_location", function () {
    $("#hospital_name_content").toggle();
  });

  $(document).on("click", "#print_preview_appleave", function () {
    $("#hospital_name_content").toggle();
    printPrev(document.getElementById("content").innerHTML);
  });

  $(document).on("change", ".type", function () {
    if (
      $("#radio_vacation").is(":checked") ||
      $("#radio_special").is(":checked") ||
      $("#radio_benefits").is(":checked")
    ) {
      $(".vacation_loc").removeAttr("disabled");
      $(".vacation_loc_details").removeAttr("disabled");
      $(".sick_loc").attr("disabled", true);
      $(".sick_loc").attr("checked", false);
      $(".sick_loc_details").attr("disabled", true);
      $(".sick_loc_details").val("");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    } else if ($("#radio_sick").is(":checked")) {
      $(".sick_loc").removeAttr("disabled");
      $(".sick_loc_details").removeAttr("disabled");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    } else if ($("#radio_study").is(":checked")) {
      $(".sick_loc").attr("disabled", true);
      $(".sick_loc").attr("checked", false);
      $(".sick_loc_details").attr("disabled", true);
      $(".sick_loc_details").val("");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").removeAttr("disabled");
    } else {
      $(".sick_loc").attr("disabled", true);
      $(".sick_loc").attr("checked", false);
      $(".sick_loc_details").attr("disabled", true);
      $(".sick_loc_details").val("");
      $(".vacation_loc").attr("disabled", true);
      $(".vacation_loc").attr("checked", false);
      $(".vacation_loc_details").attr("disabled", true);
      $(".vacation_loc_details").val("");
      $(".type_study").attr("disabled", true);
      $(".type_study").attr("checked", false);
    }
  });
  //End Owen Codes

  $(document).on("click", "#btnsearch", function (e) {
    e.preventDefault();
    loadTable();
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

  // function loadTable() {
  //   table = $("#datatables").DataTable({
  //     processing: true,
  //     serverSide: true,
  //     order: [],
  //     ajax: {
  //       url: commons.baseurl + "leavemanagement/CTOApproval/fetchRows",
  //       type: "POST",
  //     },
  //     columnDefs: [{ orderable: false }],
  //   });
  // }
  function loadTable(){    
    $("#datatables").DataTable().clear().destroy();
    my = $(this);
    url = my.attr("href");
    leave_type = $("#leave_type").val();
    status = $("#status").val();
    table = $('#datatables').DataTable({  
        "processing":true,  
        "serverSide":true,
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
            $searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float search-employee">')
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
            if	($("#search-employee").length === 0) {
              $('.dataTables_filter').append($searchButton);
            }
            
        },
        "drawCallback": function( settings ) {
            $('#search-loader').remove();
            $('#search-employee').removeAttr('disabled');
            $('#datatables button').removeAttr('disabled');
        },
        "ajax":{  
            url:commons.baseurl + "leavemanagement/CTOApproval/fetchRows",
            type:"POST",
            data: {LeaveType:leave_type, Status:status}
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
    // table.ajax.reload();
    $("#datatables").DataTable().ajax.reload();
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
