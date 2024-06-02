$(function () {
  var page = "";
  base_url = commons.base_url;
  var table;
  loadTable();
  $(document).on("click", "#addBtn", function (e) {
    e.preventDefault();
    $("select").selectpicker("destroy");
    index = $("form .card").length;
    row_1 = $("#row_0").html();
    row_1 = row_1.split("[0]").join("[" + index + "]");
    // console.log(row_1);
    $(
      '<div id="row_' + index + '" class="col-md-6">' + row_1 + "</div>"
    ).insertBefore("#addBtnCotainer");
    $("#row_" + index)
      .find(".removeBtn")
      .css("visibility", "visible");
    $("select").selectpicker("refresh");
    $(".datepicker").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      weekStart: 1,
      maxDate: new Date(),
      time: false,
    });
    $(".timepicker").inputmask({
      mask: "h:s:s t\\m",
      placeholder: "hh:mm:s xm",
      alias: "datetime",
      hourFormat: "12",
    });
  });
  $(document).on("click", ".removeBtn", function (e) {
    $(this).closest(".card").parent().remove();
  });
  $(document).on("click", "#printLocatorSlips", function (e) {
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

  $(document).on("change", "#remarks", function (e) {
    e.preventDefault();
    if ($(this).val() == "Defective") {
      $("#location").parent().parent().parent().hide();
    } else {
      $("#location").parent().parent().parent().show();
    }
  });

  function parseDate(firstDate) {
      var mdy = firstDate.split('-');
      return new Date(mdy[0], mdy[1]-1, mdy[2]);
  }

  function datediff(firstDate, secondDate) {
      // Take the difference between the dates and divide by milliseconds per day.
      // Round to nearest whole number to deal with DST.
      return Math.round((secondDate-firstDate)/(1000*60*60*24));
  }

  //Confirms
  $(document).on("click", ".approveLocatorSlips,.rejectLocatorSlips,.AssignDriverVehicle,.recommendation, .approveToComplete", function (
    e
  ) {
    e.preventDefault();
    var formData = new FormData();
    me = $(this);
    url = me.attr("href");
    var id = me.attr("data-id");
    var transaction_date = me.attr("data-transaction_date");
    var transaction_date_end = me.attr("data-transaction_date_end");
    var no_days = datediff(parseDate(transaction_date), parseDate(transaction_date_end)) + 1;
    var remarkstable = me.attr("data-control_no");
    var is_vehicle = me.attr("data-is_vehicle");
    var employee_id = me.attr("data-employee_id");
    var reject_remarks = me.attr("data-reject_remarks");
    var remarks = me.attr("data-remarks");
    var driver = "";
    var vehicle = "";
    var locator_id = me.attr("data-locator_id");

    formData.append("id", id);
    formData.append("locator_id", locator_id);
    formData.append("reject_remarks", reject_remarks);
    formData.append("remarks", remarks);
    formData.append("transaction_date", transaction_date);
    formData.append("transaction_date_end", transaction_date_end);
    formData.append("employee_id", employee_id);
    formData.append("no_days", no_days);
    formData.append("remarkstable", remarkstable);
    formData.append("is_vehicle", is_vehicle);
    content = "Are you sure you want to proceed?";

    if (me.hasClass("approveLocatorSlips")) {
      content = "Are you sure you want to approve selected request?";
      content += '<div class="form-group">';
      content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
      content += '<div class="form-group form-float">';
      content += '<div class="form-line">';
      content +=
        '<input type="file" id="file" name="file" class="file form-control" accept="image/*" >';
      content += "</div>";
      content += "</div>";
      content += "</div>";
      content += "</form>";
    } else if (me.hasClass("approveToComplete")) {
      content = "Are you sure you want to complete?";
    
    } else if (me.hasClass("rejectLocatorSlips")) {
      //content = 'Are you sure you want to reject selected request?';
      content += '<div class="form-group">';
      content += '<label class="form-label">Remarks</label>';
      content += '<div class="form-group form-float">';
      content += '<div class="form-line">';
      content += '<input type="text" class="remarks form-control" >';
      content += "</div>";
      content += "</div>";
      content += "</div>";
      content += "</form>";
    }else if (me.hasClass("AssignDriverVehicle")){
      content = "Input driver and vehicle.";
      content += '<div class="form-group">';
      content += '<label class="form-label">Driver</label>';
      content += '<div class="form-group form-float">';
      content += '<div class="form-line">';
      content += '<input type="text" class="driver form-control" >';
      content += "</div>";
      content += "</div>";
      content += "</div>";
      content += '<div class="form-group">';
      content += '<label class="form-label">Vehicle</label>';
      content += '<div class="form-group form-float">';
      content += '<div class="form-line">';
      content += '<input type="text" class="vehicle form-control" >';
      content += "</div>";
      content += "</div>";
      content += "</div>";
    }
    data = {
      id: id,
    };
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
            driver =
              typeof this.$content.find(".driver").val() === "undefined"
                ? "N/A"
                : this.$content.find(".driver").val();
            vehicle =
              typeof this.$content.find(".vehicle").val() === "undefined"
                ? "N/A"
                : this.$content.find(".vehicle").val();
            if (this.$content.find(".vehicle").val() == "" || this.$content.find(".driver").val() == ""){
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content:
                  "Please input driver and vehicle.",
              });
              return false;
            }
            formData.append("remarks", remarks);
            formData.append("driver", driver);
            formData.append("vehicle", vehicle);
            if (me.hasClass("approveLocatorSlips")) {
              if($("#file")[0].files[0] == 'undefined' || $("#file")[0].files[0] == null){
                $(".esign").css("display","block")
                return false;
              }
              formData.append("file", $("#file")[0].files[0]);
            }
            reloadTable();
            $.confirm({
              content: function () {
                var self = this;
                return $.ajax({
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
                          case "approveLocatorSlips":
                          case "rejectLocatorSlips":
                          case "AssignDriverVehicle":
                          case "recommendation":
                          case "approveToComplete":
                            self.setContent(result.Message);
                            self.setTitle(
                              '<label class="text-success">Success</label>'
                              
                            );
                            reloadTable();
                            break;
                        }
                      }
                      reloadTable();
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

  var employee_id;

  //Ajax non-forms
  $(document).on(
    "click",
    "#addLocatorSlipsForm,.updateLocatorSlipsForm,.viewLocatorSlipsForm",
    function (e) {
      e.preventDefault();
      me = $(this);
      id = me.attr("data-id");
      url = me.attr("href");
      $.ajax({
        type: "POST",
        url: url,
        data: {
          id: id,
        },
        dataType: "json",
        success: function (result) {
          page = me.attr("id");
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "viewLocatorSlips":
              case "updateLocatorSlips":
                $.when(getFields.division()).done(function () {
                  $.when(
                    getFields.employee({
                      division_id: me.data("division_id"),
                      pay_basis: "Permanent",
                    })
                  ).done(function () {
                    $("#division_id").val(me.data("division_id")).change();
                    $("#division_id").css("pointer-events", "none");
                    $("#employee_id").val(me.data("employee_id")).change();
                    $("#employee_id").css("pointer-events", "none");
                  });
                });

                $("#position_id").css("pointer-events", "none");
                $("#filing_date").css("pointer-events", "none");
                $.when(getFields.position({ pay_basis: "Permanent" })).done(
                  function () {
                    $("#position_id").val(me.data("position_id")).change();
                    $("#position_id").css("pointer-events", "none");
                    $("#filing_date").css("pointer-events", "none");
                  }
                );

                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                  "Official Business Permission Form"
                );
                $("#myModal .modal-body").html(result.form);

                $("#myModal").modal("show");
                $.each(me.data(), function (i, v) {
                  if (i == "transaction_date") {
                    $(".transaction_date").val(
                      me.data("transaction_date_end") != "0000-00-00"
                        ? v + " - " + me.data("transaction_date_end")
                        : v + " - " + v
                    );
                  } else {
                    $("." + i)
                      .val(me.data(i))
                      .change();
                  }
                  
                  if (i == "is_vehicle")
                  $("#radio_" + me.data(i))
                    .attr("checked", true)
                    .change()
                    .click();

                  if (i == "purpose")
                    $("#radio_" + me.data(i))
                      .attr("checked", true)
                      .change()
                      .click();
                      
                  if (i == "is_return"){
                    $("#radio_" + me.data(i))
                      .attr("checked", true)
                      .change()
                      .click();
                      
                    if (me.data(i) == '1') {
                      $('.expected_time_return_input').show();
                    }else{
                      $('.expected_time_return_input').hide();
                    }
                    $(".expected_return").val(me.data("expected_time_return"));
                  }
                });

                $("#locator_time_out")
                  .attr("type", "text")
                  .val(me.data("transaction_time_end"));
                $("#locator_time_in")
                  .attr("type", "text")
                  .val(me.data("transaction_time"));
                if(me.data("filename") == ""){
                  $("#downloadAttachment, #viewAttachment").css("pointer-events","none");
                }
                
                $("#viewAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/locatorslips/" +
                    me.data("employee_id") +
                    "/" +
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/locatorslips/" +
                    me.data("employee_id") +
                    "/" +
                    me.data("filename")
                );
                if (result.key == "viewLocatorSlips") {
                  $("#changeAttachment").hide();
                  $("form")
                    .find("input, textarea, select")
                    .attr("disabled", "disabled");
                  $("form").find("#cancelUpdateForm").removeAttr("disabled");
                }

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
  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  $(document).on("submit", "#addLocatorSlips,#updateLocatorSlips", function (
    e
  ) {
    e.preventDefault();
    var form = $(this);

    content = "Are you sure you want to proceed?";
    if (form.attr("id") == "addLocatorSlips") {
      content = "Are you sure you want to add report?";
    }
    if (form.attr("id") == "updateLocatorSlips") {
      content = "Are you sure you want to update report?";
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
                    // if (result.hasOwnProperty("key")) {
                    // 	if (result.Code == "0") {
                    // 		if (result.hasOwnProperty("key")) {
                    // 			switch (result.key) {
                    // 				case 'addLocatorSlips':
                    // 				case 'updateLocatorSlips':
                    // 					self.setContent(result.Message);
                    // 					self.setTitle('<label class="text-success">Success</label>');
                    // 					$('#myModal .modal-body').html('');
                    // 					$('#myModal').modal('hide');
                    // 					loadTable();
                    // 					break;
                    // 			}
                    // 		}
                    // 	} else {
                    // 		self.setContent(result.Message);
                    // 		self.setTitle('<label class="text-danger">Failed</label>');
                    // 	}
                    // }
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

  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
  });

  $(document).on("click", "#btnSearch", function (e) {
    e.preventDefault();
    const my = $(this);
    const url = my.attr("href");
    $.ajax({
      type: "POST",
      url: url,
      dataType: "json",
      success: function (result) {
        $("#table-holder").html(result.table);
        const table = $("#datatables").DataTable({
          processing: true,
          serverSide: true,
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
          ],
          columnDefs: [
            {
              targets: [0],
              orderable: false,
            },
          ],
          "order": [],
          ajax: {
            url: `${commons.baseurl}timekeeping/LocatorSlips/fetchRows`,
            type: "POST",
            data: { "status": $("#status").val() },
          },
          // Add the pagination settings
          paging: true,
          pageLength: 10, // Number of rows to display per page
        });
        const button =
          '<a id="viewLeaveCreditsSummary">' +
          '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
          '<i class="material-icons">people</i> Balance of Leave Credits Summary' +
          "</button>" +
          "</a>";
        $("#table-holder .button-holder").html(button);
  
        // Store the DataTable instance in a variable accessible to reloadTable()
        window.datatableInstance = table;
      },
      error: function (result) {
        errorDialog();
      },
    });
  });
  
  function loadTable() {
    const table = $("#datatables").DataTable({
      processing: true,
      serverSide: true,
      stateSave: true,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'All'],
      ],
      columnDefs: [
        { 
          targets: 1, // Assuming "date_created" is the second column (index 1)
          orderable: true, // Set "orderable" to true to enable sorting for this column
        },
      ],
      "order": [], // Disable initial sorting
      ajax: {
        url: `${commons.baseurl}timekeeping/LocatorSlips/fetchRows?Status=${$("#status").val()}`,
        type: "POST",
      },
      // Add the pagination settings
      paging: true,
      pageLength: 10, // Number of rows to display per page
    });
  
    // Store the DataTable instance in a variable accessible to reloadTable()
    window.datatableInstance = table;
  }
  
  function reloadTable() {
    if (window.datatableInstance) {
      // Reload the DataTable
      window.datatableInstance.ajax.reload();
    }
  }
  

});