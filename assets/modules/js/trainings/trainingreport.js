$(function () {
  base_url = commons.base_url;
  var table;
  $.when(
    getFields.payBasis3(),
    getFields.division()
    // getFields.location()
  ).done(function () {
		$('#division_id option:first').text("All");
		$("#division_id option:first").val("");
    $("#pay_basis").val("Permanent-Casual").change();
    $.AdminBSB.select.activate();
    $("#pay_basis option").eq(2).remove();
    $("#pay_basis").selectpicker("refresh");
  });

  $(document).on("show.bs.modal", "#myModal", function () {
    $(".datepicker").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      weekStart: 1,
      time: false,
    });
    $('[data-toggle="popover"]').popover();
    //$.AdminBSB.input.activate();
  });

  $(document).on("click", function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
      //the 'is' for buttons that trigger popups
      //the 'has' for icons within a button that triggers a popup
      if (
        !$(this).is(e.target) &&
        $(this).has(e.target).length === 0 &&
        $(".popover").has(e.target).length === 0
      ) {
        (
          ($(this).popover("hide").data("bs.popover") || {}).inState || {}
        ).click = false; // fix for BS 3.3.6
      }
    });
  });

  const addCommas = (x) => {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  };

  //Ajax non-forms
  $(document).on("click", ".viewTrainingRecord", function (e) {
    e.preventDefault();
    my = $(this);
    url = my.attr("href");
    employee_id = my.data("employee_id");
    year = $(".search_entry #search_year").val();
    $.ajax({
      type: "POST",
      url: url,
      data: {
        employee_id: employee_id,
        year: year,
        key: "viewTrainingReport"
      },
      dataType: "json",
      success: function (result) {
        page = my.attr("id");
        if (result.hasOwnProperty("key")) {
          switch (result.key) {
            case "viewTrainingReport":
              page = "";
              $("#myModal .modal-dialog").attr(
                "class",
                "modal-dialog modal-lg"
              );
              modal_title =
                "Balance of Leave Credit: " +
                result.data.employee_id_number +
                " - " +
                result.data.first_name +
                " " +
                result.data.middle_name +
                " " +
                result.data.last_name +
                ' <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success">Print Preview</button>';
              $("#myModal .modal-title").html(modal_title);
              $("#myModal .modal-body").html(result.form);
              $("#myModal").modal("show");
              $.each(my.data(), function (i, v) {
                $("." + i).val(addCommas(v));
              });
              $("form input:not(:button),form input:not(:submit)").attr(
                "disabled",
                true
              );
              break;
          }
          $("#" + result.key).validate({
            rules: {
              ".required": { required: true },
              ".email": { required: true, email: true },
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
        errorDialog();
      },
    });
  });

  $(document).on("click", "#loadEmployees", function (e) {
    e.preventDefault();
    my = $(this);
    url = my.attr("href");
    division_id = $(".search_entry #division_id").val();
    // if (division_id == "") {
    //   $.alert({
    //     title: '<label class="text-danger">Failed</label>',
    //     content: "Please select Department.",
    //   });
    //   return false;
    // }
    loadTable();
  });

  $(document).on("click", "#viewTrainingReportSummary", function (e) {
    e.preventDefault();
    division_id = $(".search_entry #division_id").val();
    // if (division_id == "") {
    //   $.alert({
    //     title: '<label class="text-danger">Failed</label>',
    //     content: "Please select Department.",
    //   });
    // } else {
      getFields.employeeModal(null, null, division_id, null, 1, null, null);
    // }
  });

  $(document).on("click", "#submitUserCheckList", function (e) {
    e.preventDefault();
    myBtn = $(this);
    $(this).attr("disabled", "disabled");
    $.confirm({
      title: '<label class="text-warning">Confirm!</label>',
      content: "Are you sure you want to generate training report?",
      type: "orange",
      buttons: {
        confirm: {
          btnClass: "btn-blue",
          action: function () {
            var progressbar =
              '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
              "0% Complete (success)" +
              "</div></div>";
            total_data = $(".checkbox_table:checked").length;
            if (total_data > 0) {
              $.ajax({
                type: "POST",
                data: { key: "formContainer" },
                url:
                  commons.baseurl +
                  "trainings/TrainingReport/formContainer",
                dataType: "json",
                success: function (result) {
                  if (result.hasOwnProperty("key")) {
                    if (result.key === "formContainer") {
                      $("#myModal .modal-dialog").attr(
                        "class",
                        "modal-dialog modal-lg"
                      );
                      modal_title =
                        'Leave Credit Summary <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success">Print Preview</button>';
                      $("#myModal .modal-title").html(modal_title);
                      $("#myModal .modal-body").append(result.form);
                      $("#myModal #progress-container").html(progressbar);
                      $("#module")
                        .DataTable()
                        .$('input[type="checkbox"]:checked')
                        .each(function (k, v) {
                          employee_id = $(this).attr("data-id");
                          queue = getEmployeeTraining(employee_id, k + 1);
                          if (queue) {
                            percentage =
                              100 - ((total_data - (k + 1)) / total_data) * 100;
                            $(".progress-bar")
                              .attr("aria-valuenow", percentage)
                              .css("width", percentage + "%")
                              .html(
                                percentage.toFixed() + "% Complete (success)"
                              );
                            if (percentage == 100 || percentage == "100")
                              $("#progress-container").css("display", "none");
                            if (total_data == k + 1)
                              $("#employee-checklists").remove();
                          } else {
                            errorDialog();
                            $("#myModal").modal("hide");
                            return false;
                          }
                        });
                    }
                  }
                },
                error: function (result) {
                  errorDialog();
                },
              });
            } else {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Employees.",
              });
              myBtn.removeAttr("disabled");
            }
          },
        },
        cancel: function () {
          myBtn.removeAttr("disabled");
        },
      },
    });
  });

  $(document).on("click", "#btnPrintDetails", function () {
    printPrev(document.getElementById("printPreview").innerHTML);
  });

  function getEmployeeTraining(employee_id, queue) {
    var url = commons.baseurl + "trainings/TrainingReport/viewTrainingReport/";    
    year = $(".search_entry #search_year").val();
    $.ajax({
      type: "POST",
      url: url,
      async: false,
      data: {
        employee_id: employee_id,
        year: year,
        key: "viewTrainingReportSummary",
        queue: queue,
      },
      dataType: "json",
      success: function (result) {
        if (result.hasOwnProperty("key")) {
          if (result.key === "viewTrainingReportSummary") {
            $("#myModal #form-container").append(result.form);
            queue += 1;
          }
        }
      },
      error: function (result) {
        queue = false;
      },
    });
    return queue;
  }

  function loadTable() {
    $("#datatables").DataTable().clear().destroy();
    // $("#table-holder").html(result.table);
    table = $("#datatables").DataTable({
      processing: true,
      serverSide: true,
      stateSave: true, // presumably saves state for reloads -- entries
      bStateSave: true, // presumably saves state for reloads -- page number
      order: [],
      ajax: {
        url:
          commons.baseurl +
          "trainings/TrainingReport/fetchRows?DivisionId=" +
          $("#division_id").val(),
        type: "POST",
      },
      columnDefs: [
        {
          "targets": [0],
          orderable: false,
        },
      ],
    });
    button =
      '<a id="viewTrainingReportSummary">' +
      '<button type="button" class="btn btn-block btn-lg btn-info waves-effect">' +
      '<i class="material-icons">people</i> Training Report Summary' +
      "</button>" +
      "</a>";
    $("#table-holder .button-holder").html(button);
  }
});
