$(function () {
    var page = "";
    base_url = commons.base_url;
    var table;
    // loadTable();
    $.when(getFields.division()).done(function () {
      if ($("#leave_tracking_all_access").val() == 0) {
        $(".division_id").val($("#division_id_hide").val()).change();
        $(".division_id").attr("disabled", true);
      } else {
        $(".division_id").selectpicker("render");
      }
      $(".month").selectpicker("render");
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
    $(document).on("click", "#printClearance", function (e) {
      e.preventDefault();
      printPrev(document.getElementById("clearance-div").innerHTML);
    });
    $(document).on("keypress keyup keydown", "form #amount", function (e) {
      $("form #balance").val($(this).val());
    });
    $(document).on("change", "#pay_basis ", function (e) {
      pay_basis = $(this).val();
      $.when(getFields.payrollperiodcutoff(pay_basis)).done(function () {
        $.AdminBSB.select.activate();
      });
    });
    /*$(document).on('change','#division_id ',function(e){
          division_id = $(this).val();
          $.when(
              getFields.periodweeklycutoff(division_id)
          ).done(function(){
              $.AdminBSB.select.activate();  
          })
      });*/
    const addCommas = (x) => {
      var parts = x.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
    };
    //Ajax non-forms
    $(document).on("click", ".viewCTOLedgerForm", function (e) {
      e.preventDefault();
      my = $(this);
      id = my.attr("data-id");
      end_date = my.attr("data-end_date");
      url = my.attr("href");
      employee_id = my.data("employee_id");
      year = $(".search_entry #search_year").val();
      month = 12;
      division_id = $(".search_entry #division_id").val();
      location_id = $(".search_entry #location_id").val();
      pay_basis = "Permanent";
      year_from = $(".search_entry #search_year_from").val();
      //console.log(me.data())
      $.ajax({
        type: "POST",
        url: url,
        data: {
          id: id,
          location_id: location_id,
          division_id: division_id,
          employee_id: employee_id,
          month: month,
          year: year,
          year_from: year_from,
          pay_basis: pay_basis,
          end_date: end_date,
        },
        dataType: "json",
        success: function (result) {
          page = my.attr("id");
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "viewCTOLedger":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                modal_title =
                  my.data("employee_id_number") +
                  " - " +
                  my.data("first_name") +
                  " " +
                  my.data("middle_name") +
                  " " +
                  my.data("last_name");
                $("#myModal .modal-title").html(modal_title);
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $.each(my.data(), function (i, v) {
                  $("." + i).val(addCommas(v));
                });
                // console.log(addCommas(my.data("salary")));
                $("form input:not(:button),form input:not(:submit)").attr(
                  "disabled",
                  true
                );
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
    });
    $(document).on("click", "#viewCTOLedgerSummary", function (e) {
      e.preventDefault();
      division_id = $(".search_entry #division_id").val();
      pay_basis = "Permanent";
      month = 12;
      year = $(".search_entry #search_year").val();
      year_from = $(".search_entry #search_year_from").val();
      //console.log(me.data())
      if (month == "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select a month.",
        });
      } else if (division_id == "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select Department.",
        });
      } else if (year == "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select a year.",
        });
      } else {
        // getFields.employeeModal(pay_basis,location_id,division_id,null,null,leave_grouping_id)
        content = 0;
        specific = null;
        getFields.reloadModal();
        lengthmenu = [
          [10, 25, 50, 100],
          [10, 25, 50, 100],
        ];
        if (content == 1) {
          lengthmenu = [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
          ];
        }
        // alert(division_id);
        $.ajax({
          type: "POST",
          url: commons.baseurl + "leavemanagement/CTOLedger/getEmployeeList",
          data: {
            pay_basis: pay_basis,
            division_id: division_id,
            specific: specific,
            year: year,
            year_from: year_from,
            month: month,
          },
          dataType: "json",
          success: function (result) {
            if (result.hasOwnProperty("key")) {
              switch (result.key) {
                case "viewEmployees":
                  $("#myModal .modal-dialog").attr(
                    "class",
                    "modal-dialog modal-lg"
                  );
                  $("#myModal .modal-title").html("Please Select Employee/s");
                  $("#myModal .modal-body").html(result.table);
                  // alert('yo')
                  $("#module").DataTable({
                    searching: true,
                    responsive: true,
                    destroy: true,
                    aaSorting: [],
                    columnDefs: [
                      {
                        targets: [0],
                        orderable: false,
                      },
                    ],
                    lengthMenu: lengthmenu,
                    /*"info": false,
                                      "paging": true,
                                      "lengthMenu": [
                                          [10, 25, 50, -1],
                                          [10, 25, 50, "All"]
                                      ],
                                      
                                      dom: 'tp',
                                      language: {
                                          search: "_INPUT_",
                                          searchPlaceholder: "Search records",
                                      }*/
                  });
  
                  break;
              }
              setTimeout(function () {
                $("#print-all-preloader").hide();
              }, 1000);
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
    });
  
    //View Payroll Register
    $(document).on("click", "#submitUserCheckList", function (e) {
      e.preventDefault();
      location_id = $(".search_entry #location_id").val();
      division_id = $(".search_entry #division_id").val();
      pay_basis = "Permanent";
      month = 12;
      year = $(".search_entry #search_year").val();
      year_from = $(".search_entry #search_year_from").val();
      myBtn = $(this);
      $(this).attr("disabled", "disabled");
      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: "Are you sure you want to process cto ledger?",
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              //Code here
              var progressbar =
                '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-addedval="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
                "0% Complete (success)" +
                "</div></div>";
              total_data = $(".checkbox_table:checked").length;
              if (total_data > 0) {
                $("#myModal #progress-container").html(progressbar);
                $(".progress-bar").focus();
                $.ajax({
                  type: "POST",
                  url:
                    commons.baseurl +
                    "leavemanagement/CTOLedger/ctoledgerContainer",
                  dataType: "json",
                  success: function (result) {
                    if (result.hasOwnProperty("key")) {
                      switch (result.key) {
                        case "ctoledgerContainer":
                          $("#myModal .modal-dialog").attr(
                            "class",
                            "modal-dialog modal-lg"
                          );
                          modal_title = "CTO Ledger Summary";
                          $("#myModal .modal-title").html(modal_title);
                          $("#myModal .modal-body").append(result.form);
                          $.each($(".checkbox_table:checked"), function (k, v) {
                            employee_id = $(this).attr("data-id");
                            queue = getEmployeeCTOLedger(
                              location_id,
                              division_id,
                              employee_id,
                              month,
                              year,
                              year_from,
                              k + 1,
                              total_data
                            );
                            // console.log(queue)
                            if (total_data == k + 1) {
                              // alert(total_data+" : "+ (k+1))
                              // Complete Loading
                              $("#employee-checklists").remove();
                            }
                          });
                          break;
                      }
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
    function getEmployeeCTOLedger(
      location_id,
      division_id,
      employee_id,
      month,
      year,
      year_from,
      queue,
      total_data
    ) {
      url2 =
        commons.baseurl + "leavemanagement/CTOLedger/viewCTOLedgerSummary/";
      $.ajax({
        type: "POST",
        url: url2,
        data: {
          location_id: location_id,
          division_id: division_id,
          employee_id: employee_id,
          month: month,
          year: year,
          year_from: year_from,
        },
        dataType: "json",
        success: function (result) {
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "viewCTOLedgerSummary":
                $("#myModal #certificate-container").append(result.form);
                queue += 1;
                progress = parseInt($(".progress-bar").attr("aria-addedval")) + 1;
                percentage =
                  ((total_data - (total_data - progress)) / total_data) * 100;
                /*console.log(percentage);
                              console.log(progress);*/
                $(".progress-bar").attr("aria-valuenow", percentage);
                $(".progress-bar").attr("aria-addedval", progress);
                $(".progress-bar").css("width", percentage + "%");
                $(".progress-bar").html(
                  "" + percentage.toFixed() + "% Complete (success)"
                );
                break;
            }
          }
        },
        error: function (result) {
          // $.alert({
          //     title:'<label class="text-danger">Failed</label>',
          //     content:'There was an error in the connection. Please contact the administrator for updates.'
          // });
          queue = false;
        },
      });
      return queue;
    }
    //Ajax Forms
    $(document).on("click", "#computePayroll", function (e) {
      e.preventDefault();
      my = $(this);
      url = my.attr("href");
      pay_basis = $(".search_entry #pay_basis").val();
      division_id = $(".search_entry #division_id").val();
      month = 12;
      year = $(".search_entry #search_year").val();
      // year_from = $('.search_entry #search_year_from').val()
      if (division_id == "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select Department.",
        });
        return false;
      }
      // if(month == ""){
      //     $.alert({
      //         title:'<label class="text-danger">Failed</label>',
      //         content:'Please select a month.'
      //     });
      //     return false;
      // }
      if (year == "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select a year.",
        });
        return false;
      }
      // if(division_id == ""){
      //     $.alert({
      //         title:'<label class="text-danger">Failed</label>',
      //         content:'Please select payroll period.'
      //     });
      //     return false;
      // }
      // if(location_id == ""){
      //     $.alert({
      //         title:'<label class="text-danger">Failed</label>',
      //         content:'Please select location.'
      //     });
      //     return false;
      // }
      // plus_url = '?PayBasis='+pay_basis+'&Division='+division_id+'&Location='+location_id
      // plus_url = '?Division='+division_id+'&Month='+month+'&Year='+year+'&YearFrom='+year_from
      plus_url = "?Division=" + division_id + "&Year=" + year;
  
      $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        success: function (result) {
          $("#table-holder").html(result.table);
          table = $("#datatables").DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
              url:
                commons.baseurl +
                "leavemanagement/CTOLedger/fetchRows" +
                plus_url,
              type: "POST",
            },
            columnDefs: [
              {
                orderable: false,
              },
            ],
          });
          button =
            '<a id="viewCTOLedgerSummary" href="' +
            commons.baseurl +
            'leavemanagement/CTOLedger/viewCTOLedgerSummary">' +
            '<button type="button" class="btn btn-block btn-lg btn-info waves-effect">' +
            '<i class="material-icons">people</i> CTO Ledger Summary' +
            "</button>" +
            "</a>";
          $("#table-holder .button-holder").html(button);
        },
        error: function (result) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content:
              "There was an error in the connection. Please contact the administrator for updates.",
          });
        },
      });
    });
    function loadTable() {
      //employment_status = $('#hide_emp_status').val();
      plus_url = "";
      /*if(employment_status != ""){
              plus_url = '?EmploymentStatus='+employment_status;
          }*/
      table = $("#datatables").DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // presumably saves state for reloads -- entries
        bStateSave: true, // presumably saves state for reloads -- page number
        order: [],
        ajax: {
          url:
            commons.baseurl + "leavemanagement/CTOLedger/fetchRows" + plus_url,
          type: "POST",
        },
        columnDefs: [
          {
            orderable: false,
          },
        ],
      });
    }
    function reloadTable() {
      //table.ajax.reload();
      employee_id = $(".search_entry #employee_id").val();
      plus_url = "?EmployeeId=" + employee_id;
      if (employee_id == "") {
        return false;
      }
      $("#searchEmployeePayroll").click();
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
  