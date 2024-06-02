$(function () {
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
      url = commons.baseurl + "leavemanagement/LeaveTracking/";
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
  
  
    //Ajax non-forms
    $(document).on(
      "click",
      ".viewLeaveTrackingDetails",
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
                case "viewLeaveTrackingDetails":
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
              url:commons.baseurl + "leavemanagement/LeaveTracking/fetchRows",
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
  