$(function () {
  
  base_url = commons.base_url;
  $(document).on("click", "#printClearance", function (e) {
    e.preventDefault();
		printPrev(document.getElementById("clearance-div").innerHTML);
  });

  $(document).on("click", "#printSummaryButton", function (e) {
    // $('.tplacement').text('');
		// printPrev(document.getElementById("printable-table-holder-summary").innerHTML);
    PrintElem("printable-table-holder-summary");
    // $('.tplacement').text('Time Placement');
    // PrintElem('printMe');
  });

  var temp = null;
    select =
      '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required><option value="">Loading...</option></select>';
    $(".payroll_period_select").html(select);
    url2 = commons.baseurl + "fieldmanagement/PeriodSettings/getActivePeriodSettingsCutOff?PayBasis=" + $("#pay_basis").val();
    $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select = '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required>';
          options = '<option value=""></option>';
          first = true;
          $.each(temp.Data.details, function (i, v) {
            if (first) {
              selected1 = "selected";
              first = false;
            }
            options += '<option value="' + v.id + '" ' + selected1 + "data-is-posted ="+ v.is_posted +">" + v.period_id + " - " + v.start_date + " to " + v.end_date + "</option>";
            selected1 = "";
          });
          select += options;
          select += "</select>";
          $(".payroll_period_select").html(select);
          $.AdminBSB.select.activate();
        }
      },
    });

    $(document).on("change", "#payroll_period_id", function () {
      payroll_period = $(this).val();
      plus_url2 = "?Id=" + payroll_period;
      url2 =
        commons.baseurl +
        "fieldmanagement/PeriodSettings/getPeriodSettingsById" +
        plus_url2;
      var inclusive_dates = (function () {
        var temp;
        $.ajax({
          url: url2,
          async: false,
          dataType: "json",
          success: function (result) {
            temp = result;
          },
        });
        return temp;
      })();
      from = new Date(inclusive_dates.Data.details[0].start_date);
      to = new Date(inclusive_dates.Data.details[0].end_date);
      payroll_period2 = inclusive_dates.Data.details[0].payroll_period;
      let options = {
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      $("#period_covered_from").val(from.toLocaleString("en-us", options));
      $("#period_covered_to").val(to.toLocaleString("en-us", options));
      $("#payroll_period_hidden").val(payroll_period2);
    });

  //Ajax non-forms
  $(document).on("click", "#viewDailyTimeRecordSummaryForm", function (e) {
              $.ajax({
                type: "POST",
                url: commons.baseurl + "timekeepingreports/EmployeeDTRSummary/fetchRowsSummary?PayrollPeriod=" + $(".search_entry #payroll_period_hidden").val() + "&PayrollPeriodId=" + $("#payroll_period_id").val(),
                dataType: "json",
                success: function (result) {
                  $("#table-holder-summary").html(result.table);
                  // $("#myModal #summary_name_label").html( "<b>" + employee.Data.details[0].last_name + ", " + employee.Data.details[0].first_name + " " + employee.Data.details[0].middle_name + "</b>" );
                  $("#myModal #summary_period_label").html("<b>From " + $("#period_covered_from").val() + " to " + $("#period_covered_to").val() + "</b>" );
                  $("#myModal .modal-dialog").attr( "class", "modal-dialog modal-lg" );
                  // modal_title = my.data("employee_id_number") + " - " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("last_name");
                  $("#myModal .modal-title").html("Daily Time Record Summary");
                  $("#myModal .modal-body").html(
                    '<div class="row">' +
                      '<div class="col-md-12">' +
                      '<div class="no-print text-right" style="margin: 10px 0 10px 0">' +
                      '<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">' +
                      '<i class="material-icons">print</i>' +
                      "<span>Print Report</span>" +
                      "</button>" +
                      "</div>" +
                      "</div>" +
                      "</div>" +
                      '<div id="table-holder-summary">' +
                      '<div id="printable-table-holder-summary" class="border"></div></div>'
                  );
                  $("#myModal .modal-body #table-holder-summary #printable-table-holder-summary").append(result.table);
                  $("#myModal").modal("show");

                },
                error: function (result) {
                  payroll_period = $('#payroll_period_hidden').val();
                  console.log(payroll_period); 
                   if(payroll_period == ''){
                      $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please choose payroll period.",
                      });
                  }else
                    $.alert({
                      title: '<label class="text-danger">Failed</label>',
                      content:
                        "There was an error in the connection. Please contact the administrator for updates.",
                    });
                },
              });
  });

});


function PrintElem(elem) {
  var body = document.getElementById(elem).innerHTML;
  body = body
    .split('<span class="tplacement">Time Placement</span>')
    .join('<span class="tplacement"></span>');
  // body = body.replace('<span class="tplacement">Time Placement</span>', '<span class="tplacement"></span>');
  var mywindow = window.open("", "PRINT", "height=400,width=600");
  mywindow.document.write(
    "<html moznomarginboxes mozdisallowselectionprint><head>"
  );
  // html, body { height: 100%; }
  mywindow.document.write(
    "<style> * { font-family: arial; text-align: center; font-size: 12px; color: #000; background: #fff } body { -webkit-print-color-adjust: exact !important; size: auto; } @page { margin: 3mm 5mm 3mm 5mm; } .fixed-height { height: 16pt !important } .text-danger { color: #c00808 !important } .text-danger td { color: #c00808 !important } small { font-size: 10px } .text-left { text-align: left } .text-right { text-align: right } table { width: 50% } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #000; } @media print { .no-print, .no-print * { display: none; } } </style>"
  );
  mywindow.document.write("</head><body >");
  mywindow.document.write(body);
  mywindow.document.write("</body></html>");

  mywindow.document.close();
  mywindow.focus();

  mywindow.print();
  mywindow.close();

  return true;
}