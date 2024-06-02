$(function () {
  var page = "";
  base_url = commons.base_url;
  var table;

  $.when(getFields.places()).done(function () {
    $.AdminBSB.select.activate();
  });
  $(".datepicker").bootstrapMaterialDatePicker({
    format: "YYYY-MM-DD",
    clearButton: true,
    weekStart: 1,
    time: false,
  });
  $(document).on("show.bs.modal", "#myModal", function () {
    $(".datepicker").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      weekStart: 1,
      time: false,
    });
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();
  });
  $(document).on("change", ".employee_select", function (e) {
    me = $(this);
    employee_name = $(this).find("option:selected").text();
    tr = me.closest("tr");
    tr.find(".employee_select_name").val(employee_name);
  });
  $(document).on("click", "#printClearance", function (e) {
    e.preventDefault();
    PrintElem("clearance-div");
  });
  $(document).on("click", ".addParticipant", function (e) {
    e.preventDefault();
    index = $("#participant_table tbody").find("tr").length;
    select =
      '<div class="form-group form-float">' +
      '<div class="form-line">' +
      '<select class="employee_select form-control" id="employee_select' +
      index +
      '" name="table2[tmp_participant][' +
      index +
      ']" data-live-search="true" required>' +
      $("select[id^=employee_select]:eq(0)").clone().html() +
      "</select>" +
      "</div>" +
      '<input type="hidden" class="employee_select_name" name="table2[tmp_participant_name][' +
      index +
      ']">' +
      "</div>";
    tbody =
      "<tr>" +
      "<td>" +
      select +
      "</td>" +
      '<td style="text-align:right;">' +
      '<button class="removeParticipant btn btn-danger btn-circle waves-effect waves-circle waves-float" type="button">' +
      '<i class="material-icons">remove</i>' +
      "</button>" +
      "</td>" +
      "</tr>";
    $("#participant_table tbody").append(tbody);
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();
  });
  $(document).on("click", ".removeParticipant", function (e) {
    $(this).closest("tr").remove();
  });
  $(document).on("click", "#btnPrint", function (e) {
    $.ajax({
      url: commons.baseurl + "trainingmonitoring/AllRecord/getAllRecords",
      type: "POST",
      dataType: "json",
      success: function (result) {
        //console.log(result)
        var tbody = "";
        if (result.Code == "0") {
          $.each(result.Data.details, function (i, v) {
            var getParticipants = (function () {
              var temp = null;
              url2 =
                commons.baseurl +
                "trainingmonitoring/AllRecord/getParticipants";
              console.log(v);
              data2 = { id: v.tm_id };
              $.ajax({
                async: false,
                url: url2,
                data: data2,
                type: "POST",
                dataType: "JSON",
                success: function (res) {
                  temp = res;
                  //console.log("meron "+res);
                },
              });

              return temp;
            })();

            var participantsList = "";
            if (getParticipants.Code == "0") {
              $.each(getParticipants.Data.details, function (i1, v1) {
                participantsList += v1.tmp_participant_name + "<br>";
              });
            }
            tbody +=
              `<tr><td valign="top">` +
              v.tm_seminar_training +
              `</td>` +
              `<td valign="top">` +
              v.tm_start_date +
              `</td><td valign="top">` +
              v.tm_end_date +
              `</td><td valign="top">` +
              v.tm_place +
              `</td><td valign="top">` +
              v.tm_country +
              `</td><td valign="top">` +
              participantsList +
              `</td></tr>`;
          });
        }
        $("#tblupdatereport .tblupdatereportbody").html(tbody);
        PrintElem("printThis");
        $("#myModal").modal("hide");
      },
    });
  });
  $(document).on("click", "#btnPrintPerCountry", function (e) {
    $.ajax({
      url:
        commons.baseurl + "trainingmonitoring/AllRecord/getAllRecordsByCountry",
      data: { country: $("#printCountrySelect").val() },
      type: "POST",
      dataType: "json",
      success: function (result) {
        //console.log(result)
        var tbody = "";
        if (result.Code == "0") {
          $.each(result.Data.details, function (i, v) {
            var getParticipants = (function () {
              var temp = null;
              url2 =
                commons.baseurl +
                "trainingmonitoring/AllRecord/getParticipants";
              console.log(v);
              data2 = { id: v.tm_id };
              $.ajax({
                async: false,
                url: url2,
                data: data2,
                type: "POST",
                dataType: "JSON",
                success: function (res) {
                  temp = res;
                  //console.log("meron "+res);
                },
              });

              return temp;
            })();

            var participantsList = "";
            if (getParticipants.Code == "0") {
              $.each(getParticipants.Data.details, function (i1, v1) {
                participantsList += v1.tmp_participant_name + "<br>";
              });
            }
            tbody +=
              `<tr><td valign="top">` +
              v.tm_seminar_training +
              `</td>` +
              `<td valign="top">` +
              v.tm_start_date +
              `</td><td valign="top">` +
              v.tm_end_date +
              `</td><td valign="top">` +
              v.tm_place +
              `</td><td valign="top">` +
              v.tm_country +
              `</td><td valign="top">` +
              participantsList +
              `</td></tr>`;
          });
        }
        $("#tblupdatereport .tblupdatereportbody").html(tbody);
        PrintElem("printThis");
        $("#myModal").modal("hide");
      },
    });
  });
  //Ajax non-forms
  $(document).on(
    "click",
    "#addAllRecordForm,.updateAllRecordForm,.previewAllRecord,#printRecordForm",
    function (e) {
      e.preventDefault();
      me = $(this);
      id = me.data("tm_id");
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
              case "addAllRecord":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Add All Record");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $.ajax({
                  url:
                    commons.baseurl + "employees/Employees/getActiveEmployees",
                  data: { id: "id" },
                  type: "POST",
                  dataType: "JSON",
                  success: function (res) {
                    temp = res;
                    if (temp.Code == "0") {
                      $("#employee_select").empty();
                      $("#employee_select").append(
                        "<option value=''></option>"
                      );
                      $.each(temp.Data.details, function (i, v) {
                        $("#employee_select").append(
                          '<option value="' +
                            v.id +
                            '">' +
                            v.employee_number +
                            " - " +
                            v.first_name +
                            " " +
                            v.middle_name +
                            " " +
                            v.last_name +
                            "</option>"
                        );
                      });
                      $(".employee_select").selectpicker("refresh");
                    }
                  },
                });
                break;
              case "printRecord":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-sm"
                );
                $("#myModal .modal-title").html("Print Records");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                break;
              case "previewAllRecord":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html("Preview All Record");
                $("#myModal .modal-body").html(result.table);
                $.each(me.data(), function (i2, v2) {
                  $("#myModal ." + i2).html(me.data(i2));
                });
                if (me.data("is_travel_time_inclusive") == "1")
                  $(".is_travel_time_inclusive").html("INCLUSIVE");
                else $(".is_travel_time_inclusive").html("EXCLUSIVE");
                if (me.data("is_with_travel_report") == "1")
                  $(".is_with_travel_report").html("YES");
                else $(".is_with_travel_report").html("NO");
                var getParticipants = (function () {
                  var temp = null;
                  url2 =
                    commons.baseurl +
                    "trainingmonitoring/AllRecord/getParticipants";
                  data2 = { id: id };
                  $.ajax({
                    async: false,
                    url: url2,
                    data: data2,
                    type: "POST",
                    dataType: "JSON",
                    success: function (res) {
                      temp = res;
                      //console.log("meron "+res);
                    },
                  });

                  return temp;
                })();
                if (getParticipants.Code == 0) {
                  $.each(getParticipants.Data.details, function (i, v) {
                    $("#myModal .participants").append(
                      '<input id="print_name[]" type="text" style="border:none; width: 100%" value="' +
                        v.tmp_participant_name +
                        '">'
                    );
                  });
                }
                $("#myModal").modal("show");
                break;
              case "updateAllRecord":
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-md"
                );
                $("#myModal .modal-title").html("Update Record");
                $("#myModal .modal-body").html(result.form);
                $.each(me.data(), function (i2, v2) {
                  $("." + i2)
                    .val(me.data(i2))
                    .change();
                });
                if (me.data("is_travel_time_inclusive") == "1") {
                  $("#travel_time_flag").prop("checked", true);
                }
                if (me.data("is_with_travel_report") == "1") {
                  $("#travel_report_flag").prop("checked", true);
                }
                var getParticipants = (function () {
                  var temp = null;
                  url2 =
                    commons.baseurl +
                    "trainingmonitoring/AllRecord/getParticipants";
                  data2 = { id: id };
                  $.ajax({
                    async: false,
                    url: url2,
                    data: data2,
                    type: "POST",
                    dataType: "JSON",
                    success: function (res) {
                      temp = res;
                      //console.log("meron "+res);
                    },
                  });

                  return temp;
                })();
                if (getParticipants.Code == 0) {
                  $("#participant_table tbody").html("");
                  var options = "<option value=''></option>";
                  $.ajax({
                    url:
                      commons.baseurl +
                      "employees/Employees/getActiveEmployees",
                    data: { id: "id" },
                    type: "POST",
                    dataType: "JSON",
                    success: function (res) {
                      temp = res;
                      if (temp.Code == "0") {
                        $.each(temp.Data.details, function (i, v) {
                          options +=
                            '<option value="' +
                            v.id +
                            '">' +
                            v.employee_number +
                            " - " +
                            v.first_name +
                            " " +
                            v.middle_name +
                            " " +
                            v.last_name +
                            "</option>";
                        });
                        $.each(getParticipants.Data.details, function (i, v2) {
                          if (i == 0) {
                            button = "";
                          } else {
                            button =
                              '<button class="removeParticipant btn btn-danger btn-circle waves-effect waves-circle waves-float" type="button">' +
                              '<i class="material-icons">remove</i>' +
                              "</button>";
                          }
                          select =
                            '<div class="form-group form-float">' +
                            '<div class="form-line">' +
                            '<select class="employee_select form-control" id="employee_select' +
                            i +
                            '" name="table2[tmp_participant][]" data-live-search="true" required>' +
                            options +
                            "</select>" +
                            "</div>" +
                            '<input type="hidden" class="employee_select_name" name="table2[tmp_participant_name][]">' +
                            "</div>";
                          tbody =
                            "<tr>" +
                            "<td>" +
                            select +
                            "</td>" +
                            '<td style="text-align:right;">' +
                            button +
                            "</td>" +
                            "</tr>";
                          $("#participant_table tbody").append(tbody);
                        });
                        $.each(getParticipants.Data.details, function (i, v2) {
                          $("#myModal #employee_select" + i)
                            .val(v2.tmp_participant)
                            .change();
                        });
                        $.AdminBSB.select.activate();
                      }
                    },
                  });
                }
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
    }
  );
  //Ajax Forms
  $(document).on("submit", "#addAllRecord,#updateAllRecord", function (e) {
    e.preventDefault();
    var form = $(this);
    content = "Are you sure you want to proceed?";
    if (form.attr("id") == "addAllRecord") {
      content = "Are you sure you want to add all record?";
    }
    if (form.attr("id") == "updateAllRecord") {
      content = "Are you sure you want to update all record?";
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
                            case "addAllRecord":
                            case "updateAllRecord":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              $("#myModal .modal-body").html("");
                              $("#myModal").modal("hide");
                              $("#datatables").DataTable().ajax.reload(); //loadTable();

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

  $(document).on("click", "#btnXls", function () {
    exportEXCELv2("#datatables", 1, "td:eq(7),th:eq(7)", "td:eq(6),th:eq(6)");
  });

  loadTable();

  $(document).on("click", "#btnsearch", function (e) {
    var country = $("#country").val();
    var place = $("#place").val();
    $.ajax({
      type: "POST",
      url: window.location.href,
      dataType: "json",
      success: function (result) {
        $("#table-holder").html(result.table);
        table = $("#datatables").DataTable({
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
          processing: true,
          serverSide: true,
          stateSave: true, // presumably saves state for reloads -- entries
          bStateSave: true, // presumably saves state for reloads -- page number
          order: [],
          ajax: {
            url:
              commons.baseurl +
              "trainingmonitoring/AllRecord/fetchRows?Country=" +
              country +
              "&Place=" +
              place,
            type: "POST",
          },
          columnDefs: [
            { orderable: false },
            { sClass: "displaynone", aTargets: [6] },
          ],
        });
      },
    });
  });
});

function loadTable() {
  table = $("#datatables").DataTable({
    pagingType: "full_numbers",
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    processing: true,
    serverSide: true,
    order: [],
    ajax: {
      url: commons.baseurl + "trainingmonitoring/AllRecord/fetchRows",
      type: "POST",
    },
    columnDefs: [
      { orderable: false },
      { sClass: "displaynone", aTargets: [6] },
    ],
    oLanguage: {
      sProcessing:
        '<div class="preloader pl-size-sm">' +
        '<div class="spinner-layer pl-red-grey">' +
        '<div class="circle-clipper left">' +
        '<div class="circle"></div>' +
        "</div>" +
        '<div class="circle-clipper right">' +
        '<div class="circle"></div>' +
        "</div>" +
        "</div>" +
        "</div>",
    },
  });
}
