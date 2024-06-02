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
    url = commons.baseurl + "leavemanagement/PendingLeave/";
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
      // $(".days_of_leave").bootstrapMaterialDatePicker({
      //   format: "YYYY-MM-DD",
      //   clearButton: false,
      //   time: false,
      //   });
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
        // $(".days_of_leave").each(function () {
        //   $(this).rules("remove", "required");
        // });
      } else if ($(this).val() === "terminal") {
        $(".inclusiveDates,.rehabDates,.offsetting-container").hide();
        $(".isMedicalReason").hide();
        $(".isTerminal").show();
        $("#isMedical").iCheck("uncheck");
      } else if ($(this).val() === "offset") {
        // alert("hit")
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

      if($(this).val() === "vacation" || $(this).val() === "paternity"){
          $(".addLeaveDays ").hide();
          $(".paternity_vacation").show();
          $(".not_paternity_vacation").hide();
          $(".paternity_vacation_dates").removeClass("days_of_leave");
          $(".paternity_vacation_dates").daterangepicker({
            timePicker: false,
            autoApply: false,
            drops: "up",
            locale: { format: "YYYY-MM-DD" },
            minDate: moment().startOf("day"),
            maxDate: moment().add(6, "months"),
          });
      }else{
          $(".addLeaveDays ").show();
          $(".paternity_vacation_dates").addClass("days_of_leave");
          $(".paternity_vacation").hide();
          $(".not_paternity_vacation").show();
          $(".not_paternity_vacation .days_of_leave").removeAttr("disabled","disabled");
      }

       if($(this).val() === "offset"){
         $(".no_hours ").show();
         $(".no_mins ").show();
       }else{
          $(".no_hours ").hide();
          $(".no_mins ").hide();
       }

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

  $(document).on("click", "#printPendingLeave", function (e) {
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
        page != "viewPendingLeaveSpecialDetails" &&
        page != "viewPendingLeaveRegularDetails"
      ) {
        $.AdminBSB.select.activate();
      } else {
        $("#employee_id").attr("disabled", true);
        $.AdminBSB.select.activate();
      }
    });
  });

  $(document).on("change", "#employee_id", function (e) {
    url = commons.baseurl + "leavemanagement/PendingLeave/getDays";
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
          // date1: date,
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
            "#radio_MC6, #radio_MC8"
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
    ".certifyPendingLeave, .recommendPendingLeave, .recommendPendingLeaveHead, .approvePendingLeave, .rejectPendingLeave, .approveDisapprovedLeave, .rejectDisapprovedLeave",
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
      var inclusive_dates = me.attr("data-inclusive_dates");
      var inclusive_dates_original = me.attr("data-inclusive_dates_original");
      var content = "Are you sure you want to proceed?";
      var remarks = "";
      var sess_employee_id = $("#sess_employee_id").val() 

      formData.append("id", id);
      formData.append("employee_id", employee_id);
      formData.append("type", type);
      formData.append("nodays", nodays);
      formData.append("offset_hrs", offset_hrs);
      formData.append("offset_mins", offset_mins);
      formData.append("offset_date_effectivity", offset_date_effectivity);
      formData.append("date_filed", date_filed);
      formData.append("inclusive_dates", inclusive_dates);
      formData.append("inclusive_dates_original", inclusive_dates_original);
      formData.append("sess_employee_id", sess_employee_id);

      if (me.hasClass("certifyPendingLeave")) {
        content = "Are you sure you want to certify the leave request?";
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
      } else if (me.hasClass("recommendPendingLeave")) {
        content = "Are you sure you want to recommend the leave request?";
      } else if (me.hasClass("recommendPendingLeaveHead")) {
        content = "Are you sure you want to recommend the leave request?";
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
      } else if (me.hasClass("approvePendingLeave")) {
        content = "Are you sure you want to approve the leave request?";
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
      } else if (me.hasClass("rejectPendingLeave")) {
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
      } else if (me.hasClass("approveDisapprovedLeave")) {
        content = "Are you sure you want to approved the disapproval of this request?";
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
      } else if (me.hasClass("rejectDisapprovedLeave")) {
        content = "Are you sure you want to disapproved the disapproval of this request?";
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
              // file =
              //   typeof this.$content.find(".file").val() === "undefined"
              //     ? "N/A"
              //     : this.$content.find(".file").val();
              //     if(file != "") formData.file = $("#file")[0].files[0];

              formData.append("remarks", remarks);
              if (me.hasClass("certifyPendingLeave") 
                || me.hasClass("recommendPendingLeaveHead") 
                || me.hasClass("approvePendingLeave") 
                || me.hasClass("rejectPendingLeave") 
                || me.hasClass("approveDisapprovedLeave")
                || me.hasClass("rejectDisapprovedLeave")) {
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
                            case "approvePendingLeave":
                            case "rejectPendingLeave":
                            case "recommendPendingLeave":
                            case "certifyPendingLeave":
                            case "approveDisapprovedLeave":
                            case "rejectDisapprovedLeave":
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
    "#addPendingLeaveRegularForm,#addPendingLeaveSpecialForm,#generatePendingLeaveForm,.updatePendingLeaveRegularForm,.updatePendingLeaveSpecialForm,.viewPendingLeaveDetails",
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
              case "addPendingLeaveRegular":
              case "addPendingLeaveSpecial":
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
              case "generatePendingLeave":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Employee Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                break;
              case "updatePendingLeaveSpecial":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Update Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(
                  "#special_dates, #sick_spent_content, #milestone_content, #domestic_content, #personal_content, #calamity_content, #parental_content, #filial_content"
                ).hide();
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
                  $(".inclusiveDates,.rehabDates").hide();
                  $(".isMedicalReason").hide();
                  $(".isTerminal").show();
                }
                $.each(me.data(), function (i, v) {
                  if (i == "type")
                    $("#radio_" + me.data(i))
                      .attr("checked", true)
                      .change()
                      .click();
                  el = $("." + i);
                  el_type = $("." + i).attr("type");
                  if (
                    el.is("select") ||
                    el.is("textarea") ||
                    el_type == "text" ||
                    el_type == "hidden" ||
                    el_type == "number"
                  )
                    $("." + i)
                      .val(me.data(i))
                      .change();
                });
                $("#viewAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
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
                    "leavemanagement/PendingLeave/getLeaveDates";
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
                    if (i == 0) button = "";
                    else {
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
                      '<input type="text" class="days_of_leave daysleave form-control" value="' +
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
                }
                break;
              case "updatePendingLeaveRegular":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Update Leave");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(
                  "#other_vacation_type_content, #vacation_sick_header, #other_type_content, #abroad_location_content, #vacation_spent_content, #additional_info_header, #hospital_name_content, #vacation_type_content"
                ).hide();
                $.each(me.data(), function (i, v) {
                  if (i == "type")
                    $("#radio_" + me.data(i))
                      .change()
                      .click();
                  if (i == "type_vacation") {
                    $("#radio_vacation_" + me.data(i))
                      .change()
                      .click();
                  }
                  if (i == "type_vacation_location" && v == "Abroad") {
                    $(".type_vacation_location").change().click();
                  }
                  if (i == "commutation" && v == "Requested") {
                    $(".commutation").attr("checked", true).change();
                  }
                  if (i == "type_sick_location" && v == "Hospital") {
                    $("#type_sick_location").change().click();
                  }
                  if (i == "force_status")
                    $("#force_status" + me.data(i))
                      .change()
                      .click();
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
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
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
                    "leavemanagement/PendingLeave/getLeaveDates";
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
                      '<input type="text" class="days_of_leave daysleave form-control" value="' +
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
                }
                break;
              case "viewPendingLeaveDetails":
              // alert("here2")
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
                var rad = me.data("type");
                // alert(rad)
                $("#radio_" + me.data("type"))
                  .change()
                  .click();
                $(".chk").iCheck("destroy");
                $(".chk").iCheck({
                  checkboxClass: "icheckbox_square-blue",
                });
                if (me.data("ismedical") == "1" || me.data("ismedical") == 1) {
                  $("#isMedical").iCheck("check");
                  $(".inclusiveDates:eq(2), .inclusiveDates:eq(3)").hide();
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
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/leaveattachment/" +
                    me.data("employee_id") + "/"+
                    me.data("filename")
                );
                if (me.data("filename") == "") {
                  $("#viewAttachment").hide();
                  $("#downloadAttachment").hide();
                }

                if(rad == "offset"){
                  $("input[name='table3[offset_hrs]']").val(me.attr("data-offset_hrs"))
                  $("input[name='table3[offset_mins]']").val(me.attr("data-offset_mins"))
                  $("input[name='table3[date_effectivity]']").val(me.attr("data-offset_date_effectivity"))
   
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
                    button = "";
                    if (rad == "rehab" ||
                      rad == "maternity" ||
                      rad == "study" ||
                      rad == "benefits" ||
                      rad == "violence"){
                      $("#rehabilitation_dates, #study_dates").val(v.days_of_leave);
                    }
                    else {
                      leaveform =
                        "" +
                        '<div class="col-md-10">' +
                        '<div class="form-group form-float">' +
                        '<div class="form-line">' +
                        '<input type="text" class="days_of_leave daysleave form-control" value="' +
                        v.days_of_leave +
                        '" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-2 text-right">' +
                        button +
                        "</div>";
                      $(".days_of_container").append(leaveform);
                    }
                  });
                }
                $("form :input").attr("disabled", true);
                $("#cancelUpdateForm").removeAttr("disabled");
                break;
            }

            $(".daysleave").bootstrapMaterialDatePicker({
              format: "YYYY-MM-DD",
              clearButton: false,
              weekStart: 1,
              time: false,
            });
            $.when(
              // getFields.location()
              getFields.division(),
              getFields.position({ pay_basis: "Permanent" })
            ).done(function () {
              employee_id = me.data("employee_id");
              $("form #employee_id").attr("name", "table1[employee_id]");
              $.each(me.data(), function (i, v) {
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
              if ($("#leave_tracking_all_access").val() == 0) {
                $(".leave_grouping_id")
                  .val($("#leave_grouping_id_hide").val())
                  .change();
                $(".leave_grouping_id").attr("disabled", true);
              } else {
                if (
                  result.key != "viewPendingLeaveRegularDetails" &&
                  result.key != "viewPendingLeaveSpecialDetails"
                ) {
                  // alert('true')
                  $.AdminBSB.select.activate();
                } else {
                  $("#leave_grouping_id").attr("disabled", true);
                  $("#employee_id").attr("disabled", true);
                }
              }
              page = result.key;
            });
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
    "#addPendingLeaveRegular,#updatePendingLeaveRegular,#addPendingLeaveSpecial,#updatePendingLeaveSpecial,#generatePendingLeave",
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
      if (form.attr("id") == "addPendingLeaveRegular") {
        content = "Are you sure you want to add regular leave?";
      }
      if (form.attr("id") == "updatePendingLeaveRegular") {
        content = "Are you sure you want to update regular leave?";
      }
      if (form.attr("id") == "addPendingLeaveSpecial") {
        content = "Are you sure you want to add special leave?";
      }
      if (form.attr("id") == "updatePendingLeaveSpecial") {
        content = "Are you sure you want to update special leave?";
      }
      if (form.attr("id") == "generatePendingLeave") {
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
                              case "addPendingLeaveRegular":
                              case "updatePendingLeaveRegular":
                              case "addPendingLeaveSpecial":
                              case "updatePendingLeaveSpecial":
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
                                $("#myModal .modal-body").html("");
                                $("#myModal").modal("hide");
                                loadTable();
                                break;
                              case "generatePendingLeave":
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
  //       url: commons.baseurl + "leavemanagement/PendingLeave/fetchRows",
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
            url:commons.baseurl + "leavemanagement/PendingLeave/fetchRows",
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
