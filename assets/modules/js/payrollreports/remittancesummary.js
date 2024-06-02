$(function () {


	getLoans();
	// getDepartments();
	// getDivisions();
	getPayrollGrouping();
	/*setTimeout(function(){  },5000);
	setTimeout(function(){  },3000);*/
	

	var page = "";
	base_url = commons.base_url;
	var table;
	loadTable();
	$.when(getFields.payBasis3(), getFields.division()).done(function() {
		$("#division_id option:first").text("All");
		$("#division_id option:first").val("");
		$.AdminBSB.select.activate();
		// $('#payroll_period_id').change();
	});
	// $.when(
	// 	getFields.payBasis3(),
	// 	// getFields.payrollGrouping()
	// ).done(function () {
	// 	$.AdminBSB.select.activate();
	// 	$('#payroll_period_id').change();
	// })
	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});
	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$('[data-toggle="popover"]').popover();
		//$.AdminBSB.input.activate();
	})
	$(document).on('click', function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
				(($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
			}

		});
	});
	$(document).on('click', '#printClearance', function (e) {
		e.preventDefault();
		printPrev(document.getElementById("clearance-div").innerHTML);
	})
	$(document).on('keypress keyup keydown', 'form #amount', function (e) {
		$('form #balance').val($(this).val())
	})

	$(document).on('click', '#printSummaryButton', function (e) {
		PrintElem('printable-table-holder-summary');
		// PrintElem('printMe');
	})

	$(document).on('change', '#pay_basis', function () {
		$("#period_covered_from").val('');
		$("#period_covered_to").val('');
		cutoff = $(this).val();
		$.when(
			getFields.payrollperiodcutoff(cutoff)
		).done(function () {
			$.AdminBSB.select.activate();
			$('#payroll_period_id').change();
		})
	});

	$(document).on("click","input[name='per']",function(){
		var per = $(this).attr("value");
		if(per==0){
			$("#divPerDept").removeClass("hidden");
			$("#divPerLoc").addClass("hidden");
		}else{
			$("#divPerDept").addClass("hidden");
			$("#divPerLoc").removeClass("hidden");
		}

	})

	$(document).on('change', '#payroll_period_id', function () {
		payroll_period = $(this).val();
		plus_url2 = '?Id=' + payroll_period
		url2 = commons.baseurl + "fieldmanagement/PeriodSettings/getPeriodSettingsById" + plus_url2;
		var inclusive_dates = function () {
			var temp;
			$.ajax({
				url: url2,
				async: false,
				dataType: "json",
				success: function (result) {
					temp = result;
				}
			});
			return temp;
		}();
		from = new Date(inclusive_dates.Data.details[0].start_date);
		to = new Date(inclusive_dates.Data.details[0].end_date);
		payroll_period2 = inclusive_dates.Data.details[0].payroll_period;
		let options = {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
		};
		$("#period_covered_from").val(from.toLocaleString('en-us', options));
		$("#period_covered_to").val(to.toLocaleString('en-us', options));
		$("#payroll_period_hidden").val(payroll_period2);
	});


	$(document).on('click', '#updateUserLevelConfigForm', function (e) {
		e.preventDefault();
		me = $(this)
		id = me.attr('data-id');
		url = me.attr('href');
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function (result) {
				page = me.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'updateUserLevelConfig':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-title').html('User Details');
							$('#myModal .modal-body').html(result.form);
							updatemoduletable = $('#module').DataTable({
								"ordering": false,
								"info": false,
								"paging": false,
								"lengthMenu": [
									[10, 25, 50, -1],
									[10, 25, 50, "All"]
								],
								destory: true,
								responsive: true,
								aaSorting: [],
								language: {
									search: "_INPUT_",
									searchPlaceholder: "Search records",
								}
							});
							$("#addUserLevelConfig").validate({
								rules: {
									".required": {
										required: true
									},
									".email": {
										required: true,
										email: true
									}
								},
								highlight: function (input) {
									$(input).parents('.form-line').addClass('error');
								},
								unhighlight: function (input) {
									$(input).parents('.form-line').removeClass('error');
								},
								errorPlacement: function (error, element) {
									$(element).parents('.form-group').append(error);
								}
							});
							$('#activateUserLevelConfig').attr('data-id', id)
							$('#deactivateUserLevelConfig').attr('data-id', id)
							$('#module_wrapper').children('.row:first').find('div:first').html('<label>Roles <span class="text-danger">*</span></label>')
							$('#myModal').modal('show');
							break;
					}
					$("#" + result.key).validate({
						rules: {
							".required": {
								required: true
							},
							".email": {
								required: true,
								email: true
							}
						},
						highlight: function (input) {
							$(input).parents('.form-line').addClass('error');
						},
						unhighlight: function (input) {
							$(input).parents('.form-line').removeClass('error');
						},
						errorPlacement: function (error, element) {
							$(element).parents('.form-group').append(error);
						}
					});
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})

	$(document).on('click', '#check_all', function () {
		!$(this).prop('checked') ? $(".checkbox_table").prop("checked", false) : $(".checkbox_table").prop("checked", true);
	});



	$(document).on('click', '#printPreviewButton', function (e) {
		e.preventDefault();
        PrintElem("clearance-div");
	});

	let filterState;

	$(document).on('click', '.filter-button', function (e) {
		e.preventDefault();
		$("#filterInput").val('');
		filterState = $(this).attr('id');
		$('#check_all').prop('checked', false)
		$('#filterInput').prop("disabled", false);
		$('#module').DataTable().search('').columns().search('').draw();
		$(".filter-buttons").find("a.filter-button").removeClass('bg-green');
		$(".employee-table-columns").find("th.employee-table-column").removeClass('bg-green');
		$(this).addClass('bg-green');
		switch (filterState) {
			case 'filterByDepartment': 
				$("#dept").addClass("bg-green");
				break;
			case 'filterByID': 
				$("#card-number-column").addClass("bg-green");
				break;
			case 'filterByName': 
				$("#name-column").addClass("bg-green");
				break;
			case 'filterByPSAmount': 
				$("#PSAmount").addClass("bg-green");
				break;
			case 'filterByGSAmount': 
				$("#GSAmount").addClass("bg-green");
				break;
			case 'filterByAmount': 
				$("#Amount").addClass("bg-green");
				break;
			default:
				e.preventDefault();
				break;
		}
	});

	$(document).on('keyup', '#filterInput', function (e) {
		e.preventDefault();
		switch (filterState) {
			case 'filterByID':
				$('#module').DataTable().columns(1).search($("#filterInput").val()).draw();
				break;
			case 'filterByName':
				$('#module').DataTable().columns(2).search($("#filterInput").val()).draw();
				break;
			case 'filterByDepartment':
				$('#module').DataTable().columns(3).search($("#filterInput").val()).draw();
				break;
			case 'filterByOffice':
				$('#module').DataTable().columns(4).search($("#filterInput").val()).draw();
				break;
			case 'filterByAgency':
				$('#module').DataTable().columns(5).search($("#filterInput").val()).draw();
				break;
			case 'filterByLocation':
				$('#module').DataTable().columns(6).search($("#filterInput").val()).draw();
				break;
			default:
				$('#module').DataTable().search($("#filterInput").val()).draw();
				break;
		}
	});



	$(document).on('click', '#summarizeAllEmployeeRemittance', function (e) {

		e.preventDefault();
		my = $(this)
		url = my.attr('href');
		var pay_basis = $('#pay_basis').val();
		var remittance_type = $('#remittance_type').val();
		var modal_title = "Generate Remittance or Deduction Report";
		var payroll_period_id = $('#payroll_period_id').val();
		var department = $("#department").val();
		var location = $("#location").val();
		var period_covered_from = $("#period_covered_from").val();
		var period_covered_to = $("#period_covered_to").val();
		var payroll_grouping_id = $("#payroll_grouping_id").val();
		var division_id = $("#division_id").val();
		if (remittance_type == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose deduction/remittance type.'
			});
		} else if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose pay basis.'
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose payroll period.'
			});
		} else if (payroll_grouping_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose payroll group.'
			});
		} else {
			getFields.reloadModal();
			$.ajax({
				type: "POST",
				url: commons.baseurl + "payrollreports/RemittanceSummary/getEmployeeList",
				data: {
					pay_basis: pay_basis,
					payroll_period_id: payroll_period_id,
					remittance_type: remittance_type,
					per: 0,
					location: location,
					period_covered_from: period_covered_from,
					period_covered_to: period_covered_to,
					payroll_grouping_id:payroll_grouping_id,
					division_id:division_id
				},
				dataType: "json",
				success: function (result) {
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case 'viewEmployees':
								$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
								$('#myModal .modal-dialog').attr('style', 'width: 95%');
								$('#myModal .modal-title').html(modal_title);
								$('#myModal .modal-body').html(result.table);
								var rem_type = $("#remittance_type").val();
								console.log($("input[name='per']:checked").val());
								if($("input[name='per']:checked").val() == 0){
									if($("#department").val()=="*"){
										$(".allDept").removeClass("hidden");
										$(".nonDept").addClass("hidden");
									}else{
										$(".allDept").addClass("hidden");
										$(".nonDept").removeClass("hidden");
									}
								}
								else{
									if($("#location").val()=="*"){
										$(".allDept").removeClass("hidden");
										$(".nonDept").addClass("hidden");
									}else{
										$(".allDept").addClass("hidden");
										$(".nonDept").removeClass("hidden");
									}
								}

								if( rem_type == "4,3" || rem_type == "4,4" || rem_type == "4,6"){
									$(".sAmount").removeClass("hidden");
									$("td.amount,th.amount").addClass("hidden");
								}else{
									$(".sAmount").addClass("hidden");
									$("td.amount,th.amount").removeClass("hidden");
								}
								$('#module').DataTable({
									"ordering": false,
									"info": false,
									"paging": false,
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									],
									searching: true,
									responsive: true,
									destroy: true,
									aaSorting: [],
									dom: 't',
									language: {
										search: "_INPUT_",
										searchPlaceholder: "Search records",
									}
								});
								$('#myModal').modal('show');
								break;
						}
					}
					setTimeout(function () {
						$('#print-all-preloader').hide()
					}, 1000);
				},
				error: function (result) {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: 'There was an error in the connection. Please contact the administrator for updates.'
					});
				}
			});
		}
	})

	const addCommas = (x) => {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	}

	//Ajax non-forms
	$(document).on('click', '.viewRemittanceSummaryForm', function (e) {
		e.preventDefault();
		my = $(this)
		id = my.attr('data-id');
		url = my.attr('href');
		employee_id = my.data('employee_id')
		year = $('.search_entry #search_year').val()
		month = $('.search_entry #month').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val();
		pay_basis = $('.search_entry #pay_basis').val();
		cutoff_id = $('.search_entry #cutoff_id').val();
		payroll = my.data();
		var department = $("#department").val();
		var location = $("#location").val();
		// console.log(employee_id);
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				payroll_period_id: payroll_period_id,
				employee_id: employee_id,
				cutoff_id: cutoff_id,
				payroll: payroll,
				pay_basis: pay_basis,
				department: department,
				location: location
			},
			dataType: "json",
			success: function (result) {
				page = my.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'viewRemittanceSummary':
							page = "";
							plus_url2 = '?Id=' + id;
							url2 = commons.baseurl + "employees/Employees/getEmployeesById" + plus_url2;
							var employee = function () {
								var temp;
								$.ajax({
									url: url2,
									async: false,
									dataType: "json",
									success: function (result) {
										temp = result;

									}
								});
								return temp;
							}();

							plus_url = '?Id=' + employee.Data.details[0].id + '&EmployeeNumber=' + employee.Data.details[0].employee_number + '&PayrollPeriodId=' + $("#payroll_period_id").val() + '&PayrollPeriod=' + $("#payroll_period_hidden").val() + '&ShiftId=' + employee.Data.details[0].shift_id;
							$.ajax({
								type: "POST",
								url: commons.baseurl + "payrollreports/RemittanceSummary/fetchRowsSummary" + plus_url,
								dataType: "json",
								success: function (result) {
									$('#table-holder-summary').html(result.table);
									$('#myModal #summary_name_label').html('<b>' + employee.Data.details[0].last_name + ', ' + employee.Data.details[0].first_name + ' ' + employee.Data.details[0].middle_name + '</b>');
									$('#myModal #summary_period_label').html('<b>From ' + $('#period_covered_from').val() + ' to ' + $('#period_covered_to').val() + '</b>');
								},
								error: function (result) {
									$.alert({
										title: '<label class="text-danger">Failed</label>',
										content: 'There was an error in the connection. Please contact the administrator for updates.'
									});
								}
							});
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-dialog').attr('style', 'width: 95%');
							modal_title = my.data("employee_id_number") + " - " + my.data("last_name") + ", " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("extension");
							$('#myModal .modal-title').html(modal_title);
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
					}
					$("#" + result.key).validate({
						rules: {
							".required": {
								required: true
							},
							".email": {
								required: true,
								email: true
							}
						},
						highlight: function (input) {
							$(input).parents('.form-line').addClass('error');
						},
						unhighlight: function (input) {
							$(input).parents('.form-line').removeClass('error');
						},
						errorPlacement: function (error, element) {
							$(element).parents('.form-group').append(error);
						}
					});
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})

	//Ajax Forms
	$(document).on('click', '#summarizeEmployeeRemittance', function (e) {
		e.preventDefault();
		my = $(this)
		url = my.attr('href');
		pay_basis = $('.search_entry #pay_basis').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val()
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please select pay basis.'
			});
			return false;
		}
		if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please select payroll period.'
			});
			return false;
		}
		plus_url = '?PayBasis=' + pay_basis + '&PayrollPeriod=' + payroll_period_id;
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			success: function (result) {
				$('#table-holder').html(result.table);
				table = $('#datatables').DataTable({
					"processing": true,
					"serverSide": true,
					"order": [],
					"ajax": {
						url: commons.baseurl + "payrollreports/RemittanceSummary/fetchRows" + plus_url,
						type: "POST"
					},
					"columnDefs": [{
						"targets": [0],
						"orderable": false,
					}, ],
				});
				button = '<a id="viewRemittanceSummaryAll" href="' + commons.baseurl + 'payrollreports/RemittanceSummary/viewRemittanceSummaryAll">' +
					'<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
					'<i class="material-icons">people</i> RemittanceSummary Summary' +
					'</button>' +
					'</a>'
				$('#table-holder .button-holder').html(button)
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})

	function loadTable() {
		plus_url = ""
		table = $('#datatables').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true, // presumably saves state for reloads -- entries
			"bStateSave": true, // presumably saves state for reloads -- page number
			"order": [],
			"ajax": {
				url: commons.baseurl + "payrollreports/RemittanceSummary/fetchRows" + plus_url,
				type: "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});
	}

	function reloadTable() {
		//table.ajax.reload();
		employee_id = $('.search_entry #employee_id').val()
		plus_url = '?EmployeeId=' + employee_id
		if (employee_id == "") {
			return false;
		}
		$("#searchEmployeePayroll").click();
	}

	function PrintElem(elem) {

		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
		// html, body { height: 100%; }
		mywindow.document.write('<style> * { font-family: arial; text-align: center; font-size: 12px; color: #000; background: #fff } body { -webkit-print-color-adjust: exact !important; } .text-danger { color: #c00808 !important } .text-danger td { color: #c00808 !important } small { font-size: 10px } .text-left { text-align: left } .text-right { text-align: right } table { width: 50% } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #000; height: 17px } @media print { .no-print, .no-print * { display: none; } } </style>')
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close();
		mywindow.focus();

		mywindow.print();
		mywindow.close();

		return true;
	}

	function getDepartments(){
		$.post(commons.baseurl + "payrollreports/RemittanceSummary/getDepartments",{id:"id"},function(result){
			jsonData = JSON.parse(result);
			$("#department").empty();
			$("#department").append("<option value='*'>ALL</option>");
			if(jsonData.Code == 0){
				$.each(jsonData.Data,function(key,value){
					$("#department").append("<option value='"+value.id+"'>"+value.department_name+"</option>");
				});
			}
			$("#department").selectpicker("refresh");
		});
	}

	function getLocations(){
		$.post(commons.baseurl + "payrollreports/RemittanceSummary/getLocations",{id:"id"},function(result){
			jsonData = JSON.parse(result);
			$("#location").empty();
			$("#location").append("<option value='*'>ALL</option>");
			if(jsonData.Code == 0){
				$.each(jsonData.Data,function(key,value){
					$("#location").append("<option value='"+value.id+"'>"+value.name+"</option>");
				});
			}
			$("#location").selectpicker("refresh");
		});
	}

	function getPayrollGrouping(){
		$.post(commons.baseurl + "payrollreports/RemittanceSummary/getPayrollGrouping",{id:"id"},function(result){
			jsonData = JSON.parse(result);
			$("#payroll_grouping_id").empty();
			// $("#payroll_grouping_id").append("<option value='*'>ALL</option>");
			if(jsonData.Code == 0){
				$.each(jsonData.Data,function(key,value){
					$("#payroll_grouping_id").append("<option value='"+value.id+"'>"+value.code+"</option>");
				});
			}
			$("#payroll_grouping_id").selectpicker("refresh");
		});
	}

	function getLoans(){
		$.post(commons.baseurl + "payrollreports/RemittanceSummary/getLoans",{id:"id"},function(result){
			jsonData = JSON.parse(result);
			if(jsonData.Code == 0){
				$.each(jsonData.Data,function(key,value){
					$("#remittance_type").append("<option value='"+value.loanId+","+value.subloanId+"'>"+value.loanCode+" - "+value.subloanCode+"</option>");
				});
				// $("#remittance_type").append("<option value='9998,1'>Damayan</option>");
				$("#remittance_type").append("<option value='9998,2'>Employees' Compensation Commission (E.C.C.)</option>");
				$("#remittance_type").append("<option value='9998,3'>GSIS Life and Retirement Share</option>");
				$("#remittance_type").append("<option value='9998,4'>Pagibig Fund Share</option>");
				$("#remittance_type").append("<option value='9998,5'>Personal Economic Relief Allowance (PERA)</option>");
				$("#remittance_type").append("<option value='9998,6'>Philhealth</option>");

				$("#remittance_type").append("<option value='9999,2'>Gross Pay</option>")/
				$("#remittance_type").append("<option value='9999,4'>Net Pay</option>");
				$("#remittance_type").append("<option value='9999,5'>Over Payment</option>");
				$("#remittance_type").append("<option value='9999,7'>Total Deduction</option>");
				$("#remittance_type").append("<option value='9999,8'>Under Payment</option>");
				$("#remittance_type").append("<option value='9999,9'>Withholding Tax</option>");
			}
			$("#remittance_type").selectpicker("refresh");
		});
	}

	function getDivisions(){
		$.post(commons.baseurl + "payrollreports/RemittanceSummary/getDivisions",{id:"id"},function(result){
			jsonData = JSON.parse(result);
			$("#division_id").empty();
			if(jsonData.Code == 0){
				$.each(jsonData.Data,function(key,value){
					$("#division_id").append("<option value='"+value.id+"'>"+value.department_name+"</option>");
				});
			}
			$("#division_id").selectpicker("refresh");
		});
	}

})
