$(function () {
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
  
    function parseDate(firstDate) {
        var mdy = firstDate.split('-');
        return new Date(mdy[0], mdy[1]-1, mdy[2]);
    }
  
    function datediff(firstDate, secondDate) {
        // Take the difference between the dates and divide by milliseconds per day.
        // Round to nearest whole number to deal with DST.
        return Math.round((secondDate-firstDate)/(1000*60*60*24));
    }
  
    var employee_id;
  
    //Ajax non-forms
    $(document).on(
      "click",
      ".viewLocatorSlipsForm",
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
              url: `${commons.baseurl}timekeeping/LocatorSlipsTracking/fetchRows`,
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
          url: `${commons.baseurl}timekeeping/LocatorSlipsTracking/fetchRows?Status=${$("#status").val()}`,
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