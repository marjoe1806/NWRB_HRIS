$(function () {
  loadTablePromotions();
  $( document ).ready(function() {
    var cto = 0;
    var leave = 0;
    var locator = 0;
    var to = 0;
    $.ajax({
      url: commons.baseurl + "home/Home/fetchDashboardApprovals",
      dataType: "json",
      success: function (result) {
        //for approval
        cto = result.total_cto;
        leave = result.total_leave;
        locator = result.total_locator;
        to = result.total_to;
        total_pending_cto = result.total_pending_cto;
        total_pending_leave = result.total_pending_leave;
        total_pending_ob = result.total_pending_ob;
        total_pending_to = result.total_pending_to;
        total_approved_cto = result.total_approved_cto;
        total_approved_leave = result.total_approved_leave;
        total_approved_ob = result.total_approved_ob;
        total_approved_to = result.total_approved_to;
        $(".total_cto").html(cto);
        $(".total_leave").html(leave);
        $(".total_locator").html(locator);
        $(".total_to").html(to);
        $(".total_pending_cto").html(total_pending_cto);
        $(".total_pending_leave").html(total_pending_leave);
        $(".total_pending_ob").html(total_pending_ob);
        $(".total_pending_to").html(total_pending_to);
        $(".total_approved_cto").html(total_approved_cto);
        $(".total_approved_leave").html(total_approved_leave);
        $(".total_approved_ob").html(total_approved_ob);
        $(".total_approved_to").html(total_approved_to);
      },
    });
  });
  // $.when(getFields.payrollperiods("Monthly")).done(function () {
  $.when(getFields.payrollperiods()).done(function () {
    $.AdminBSB.select.activate();
    $("#payroll_period_id").change();
    $.ajax({
      url: commons.baseurl + "home/Home/fetchDashboardEmployees",
      dataType: "json",
      success: function (result) {
        $(".total_employees").html(result.total_employees.totemp);
        $(".total_permanents").html(result.total_employees.totemppermanent);
        $(".total_coterminous").html(result.total_employees.totempcoterminous);
        $(".total_males").html(result.total_employees.totempmale);
        $(".total_females").html(result.total_employees.totempfemale);
      },
    });
  });
  $(document).on("click", "#btnXls", function() {
      $('#datatables1').tableExport({
      type:"excel",
      fileName:'application_list'
    });
  });
  
  $(document).on("change", "#payroll_period_id", function () {
    var p = $(this).val();
    $.ajax({
      url: commons.baseurl + "home/Home/fetchDashboard?PayrollPeriod=" + p,
      dataType: "json",
      success: function (result) {
        $.each(result.payroll[0], function (i, v) {
          if(v != null){
            format_num = v.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("." + i).html(format_num);
          }
        });
      },
    });
  });

  $(".datepicker").bootstrapMaterialDatePicker({
    format: "YYYY-MM-DD",
    clearButton: true,
    maxDate: new Date(),
    time: false,
  });

  $(document).on("change", "#asofdate", function () {
    $.ajax({
      url: commons.baseurl + "home/Home/fetchDashboardEmployees",
      type: "POST",
      data: { asofdate: $("#asofdate").val() },
      dataType: "json",
      success: function (result) {
        $(".total_employees").html(result.total_employees.totemp);
        $(".total_permanents").html(result.total_employees.totemppermanent);
        $(".total_coterminous").html(result.total_employees.totempcoterminous);
        $(".total_males").html(result.total_employees.totempmale);
        $(".total_females").html(result.total_employees.totempfemale);
      },
    });
  });

	$(document).on('click', '#downloadMobileApp', function () {
		var result_key = $(this).attr("id");
		title = 'Download Mobile Applications';
		var res = title;
	    loader =
			'<div id="btn-loader" class="preloader pl-size-xl" style="left:44%";>' +
			'<div class="spinner-layer pl-blue">' +
			'<div class="circle-clipper left">' +
			'<div class="circle"></div>' +
			"</div>" +
			'<div class="circle-clipper right">' +
			'<div class="circle"></div>' +
			"</div>" +
			"</div>" +
			"</div>";
		$('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
		$('#myModal .modal-title').html(res);
		$('#myModal .modal-body').html(loader);
		$('#myModal').modal('show');
		
		$.ajax({
			url: commons.baseurl + "home/Home/DonwloadMobileApp",
			data :{result_key:result_key},
			type: "POST",
			dataType:"json", 
			success: function(result){
			$('#myModal .modal-body').html(result.form);
		}});
	});

  $("#yr_service").click( function(e){
    e.preventDefault();
    var result_key = $(this).attr("id");

    loader =
      '<div id="btn-loader" class="preloader pl-size-xl" style="left:44%";>' +
      '<div class="spinner-layer pl-blue">' +
      '<div class="circle-clipper left">' +
      '<div class="circle"></div>' +
      "</div>" +
      '<div class="circle-clipper right">' +
      '<div class="circle"></div>' +
      "</div>" +
      "</div>" +
      "</div>";
    $('#myModal .modal-dialog').attr('class','modal-dialog');
    $('#myModal .modal-dialog').css('width','1250px');
    $('#myModal .modal-title').html("Years in Service");
    $('#myModal .modal-body').html(loader);
    $('#myModal').modal('show');

    $.ajax({
        type: "POST",
        url: commons.baseurl+"home/Home/fetchYrService",
        data: {
          result_key: result_key,
        },
        dataType: "json",
        success: function (result) {
           $('#myModal .modal-body').html(result.form);
           loadTablePromotions();
           $.when(getFields.division()).done(function() {
            $.AdminBSB.select.activate();
            $("#division_id").selectpicker("refresh");           
          });
           $.when(getFields.payBasis3()).done(function() {
            $.AdminBSB.select.activate();
            $("#pay_basis").selectpicker("refresh");           
          });
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

  $("#viewCTO, #viewLeave, #viewOB, #viewTO").click( function(e){
    e.preventDefault();
    var result_key = $(this).attr("id");

    loader =
      '<div id="btn-loader" class="preloader pl-size-xl" style="left:44%";>' +
      '<div class="spinner-layer pl-blue">' +
      '<div class="circle-clipper left">' +
      '<div class="circle"></div>' +
      "</div>" +
      '<div class="circle-clipper right">' +
      '<div class="circle"></div>' +
      "</div>" +
      "</div>" +
      "</div>";
    $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
    if(result_key == 'viewCTO'){ 
      $('#myModal .modal-title').html("PENDING CTO APPLICATIONS");
    }else if(result_key == 'viewLeave'){
      $('#myModal .modal-title').html("PENDING LEAVE APPLICATIONS");
    }else if(result_key == 'viewOB'){
      $('#myModal .modal-title').html("PENDING LOCATOR SLIP APPLICATIONS");
    }else if(result_key == 'viewTO'){
      $('#myModal .modal-title').html("PENDING TRAVEL ORDER APPLICATIONS");
    }
    $('#myModal .modal-body').html(loader);
    $('#myModal').modal('show');
    // if(result_key == 'viewCTO'){      
      $.ajax({
          type: "POST",
          url: commons.baseurl+"home/Home/fetchPending",
          data: {
            result_key: result_key,
          },
          dataType: "json",
          success: function (result) {
            $('#myModal .modal-body').html(result.form);
            if(result_key == 'viewCTO'){ 
              loadPendingCTO();
            }else if(result_key == 'viewLeave'){
              loadPendingLeave();
            }else if(result_key == 'viewOB'){
              loadPendingOB();
            }else if(result_key == 'viewTO'){
              loadPendingTO();
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
    // }else if(result_key == 'viewLeave'){
    //   $.ajax({
    //     type: "POST",
    //     url: commons.baseurl+"home/Home/fetchTotalPendingLeave",
    //     data: {
    //       result_key: result_key,
    //     },
    //     dataType: "json",
    //     success: function (result) {
    //       $('#myModal .modal-body').html(result.form);
    //     },
    //     error: function (result) {
    //       $.alert({
    //         title: '<label class="text-danger">Failed</label>',
    //         content:
    //           "There was an error in the connection. Please contact the administrator for updates.",
    //       });
    //     },
    //   });
    // }
  });
  
  $("#viewApprovedCTO, #viewApprovedLeave, #viewApprovedOB, #viewApprovedTO").click( function(e){
    e.preventDefault();
    var result_key = $(this).attr("id");

    loader =
      '<div id="btn-loader" class="preloader pl-size-xl" style="left:44%";>' +
      '<div class="spinner-layer pl-blue">' +
      '<div class="circle-clipper left">' +
      '<div class="circle"></div>' +
      "</div>" +
      '<div class="circle-clipper right">' +
      '<div class="circle"></div>' +
      "</div>" +
      "</div>" +
      "</div>";
    $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
    if(result_key == 'viewApprovedCTO'){ 
      $('#myModal .modal-title').html("APPROVED CTO APPLICATIONS");
    }else if(result_key == 'viewApprovedLeave'){
      $('#myModal .modal-title').html("APPROVED LEAVE APPLICATIONS");
    }else if(result_key == 'viewApprovedOB'){
      $('#myModal .modal-title').html("APPROVED LOCATOR SLIP APPLICATIONS");
    }else if(result_key == 'viewApprovedTO'){
      $('#myModal .modal-title').html("APPROVED TRAVEL ORDER APPLICATIONS");
    }
    $('#myModal .modal-body').html(loader);
    $('#myModal').modal('show');
    // if(result_key == 'viewCTO'){      
      $.ajax({
          type: "POST",
          url: commons.baseurl+"home/Home/fetchApproved",
          data: {
            result_key: result_key,
          },
          dataType: "json",
          success: function (result) {
            $('#myModal .modal-body').html(result.form);
            if(result_key == 'viewApprovedCTO'){ 
              loadApprovedCTO();
            }else if(result_key == 'viewApprovedLeave'){
              loadApprovedLeave();
            }else if(result_key == 'viewApprovedOB'){
              loadApprovedOB();
            }else if(result_key == 'viewApprovedTO'){
              loadApprovedTO();
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
    // }else if(result_key == 'viewLeave'){
    //   $.ajax({
    //     type: "POST",
    //     url: commons.baseurl+"home/Home/fetchTotalPendingLeave",
    //     data: {
    //       result_key: result_key,
    //     },
    //     dataType: "json",
    //     success: function (result) {
    //       $('#myModal .modal-body').html(result.form);
    //     },
    //     error: function (result) {
    //       $.alert({
    //         title: '<label class="text-danger">Failed</label>',
    //         content:
    //           "There was an error in the connection. Please contact the administrator for updates.",
    //       });
    //     },
    //   });
    // }
  });

  $(document).on("change", "#myModal #candidate", function(){
    if ($(this).val() == "step_inc"){
      $('.period_div').show();
      $('.loyalty_div').hide();
      $("#forloyal").val("").change();
      $("#period").val("this_month").change();
    }else if ($(this).val() == "loyalty") {
      $('.period_div').hide();
      $('.loyalty_div').show();
      $("#period").val("").change();
    }else{
      $("#forloyal").val("").change();
      $("#period").val("").change();
      $('.period_div').hide();
      $('.loyalty_div').hide();
    }
  });

  $(document).on("click", "#btnsearch", function(){
    pay_basis = $("#pay_basis").val();
    period = $("#period").val();
    forloyal = $("#forloyal").val();
    division = $("#division_id").val();
    candidate = $("#candidate").val();
      loadTablePromotions(candidate,pay_basis,period,forloyal,division);
  });
  

  function loadTablePromotions(candidate,pay_basis,period,forloyal,division) {

    $("#datatables").DataTable().clear().destroy();
    table = $("#datatables").DataTable({
        processing: true,
        serverSide: true,
        order: [[4, 'asc']],
        lengthMenu: [
          [5, 10, -1],
          [5, 10, "ALL"],
        ],
        scroller: {
            displayBuffer: 20,
        },
        columnDefs: [
            {
                targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowspromotion",
            type: "POST",
            data : {pay_basis: pay_basis ,period: period, forloyal: forloyal, division: division, candidate: candidate},
        },
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

  function loadPendingCTO() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsPendingCTO",
            type: "POST",
            // data : {},
        },
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
  function loadPendingLeave() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsPendingLeave",
            type: "POST",
            // data : {},
        },
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
  function loadPendingOB() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsPendingOB",
            type: "POST",
            // data : {},
        },
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
  function loadPendingTO() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsPendingTO",
            type: "POST",
            // data : {},
        },
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

  function loadApprovedCTO() {
    console.log('hit')
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsApprovedCTO",
            type: "POST",
            // data : {},
        },
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
  function loadApprovedLeave() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsApprovedLeave",
            type: "POST",
            // data : {},
        },
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
  function loadApprovedOB() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsApprovedOB",
            type: "POST",
            // data : {},
        },
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
  function loadApprovedTO() {
    $("#datatables1").DataTable().clear().destroy();
    table = $("#datatables1").DataTable({
      "pagingType": "full_numbers",
      "lengthMenu": [
          [5, 10, 20, 50, 100, -1],
          [5, 10, 20, 50, 100, "All"]
      ],
      processing: true,
      serverSide: true,
      order: [],
      scroller: {
          displayBuffer: 20,
      },
        columnDefs: [
            {
                // targets: [-1],
                orderable: false,
            },
        ],
        initComplete: function () {
            var input = $(".dataTables_filter input").unbind(),
            self = this.api(),
            $searchButton = $(
                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
            )
            .html('<i class="material-icons">search</i>')
            .click(function () {
                if (!$("#search-employee").is(":disabled")) {
                    $("#search-employee").attr("disabled", true);
                    self.search(input.val()).draw();
                    $("#datatables1 button").attr("disabled", true);
                    $(".dataTables_filter").append(
                        '<div id="search-loader"><br>' +
                        '<div class="preloader pl-size-xs">' +
                        '<div class="spinner-layer pl-red-grey">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        '<div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "&emsp;Please Wait..</div>"
                    );
                }
            });

      if ($('#search-employee').length === 0) {
        $(".dataTables_filter").append($searchButton);
      }
        },
        drawCallback: function (settings) {
            $("#search-loader").remove();
            $("#search-employee").removeAttr("disabled");
            $("#datatables1 button").removeAttr("disabled");
        },
        ajax: {
            url: commons.baseurl + "home/Home/fetchRowsApprovedTO",
            type: "POST",
            // data : {},
        },
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
});
