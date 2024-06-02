$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	loadTable();
	$.when(
		getFields.payBasis3()
	).done(function () {
		$.AdminBSB.select.activate();
		$('#payroll_period_id').change();
	})
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
		PrintElem("clearance-div");
	})
	$(document).on('keypress keyup keydown', 'form #amount', function (e) {
		$('form #balance').val($(this).val())
	})

	$(document).on('click', '#printSummaryButton', function (e) {
		PrintElem('printable-table-holder-summary');
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

	$(document).on('click', '#summarizeAllEmployeeDailyTimeRecord', function (e) {
		e.preventDefault();
		my = $(this)
		url = my.attr('href');
		pay_basis = $('#pay_basis').val()
		payroll_period_id = $('#payroll_period_id').val()
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose pay basis.'
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose payroll period.'
			});
		} else {
			$('#print-all-preloader').show();
			plus_url = '?PayBasis=' + pay_basis + '&PayrollPeriodId=' + payroll_period_id + '&PayrollPeriod=' + $("#payroll_period_hidden").val();
			$.ajax({
				type: "POST",
				// url: commons.baseurl + "timekeepingreports/AttendanceSummary/attendanceSummaryContainer",
				url: commons.baseurl + "timekeepingreports/AttendanceSummary/fetchRowsSummaryAll" + plus_url,
				dataType: "json",
				success: function (result) {
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case 'viewAttendanceSummaryAll':
								$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
								$('#myModal .modal-dialog').attr('style', 'width: 95%');
								modal_title = "Attendance Summary"
								$('#myModal .modal-title').html(modal_title);
								$('#myModal .modal-body').html(result.table);
								$('#myModal .summary_period_label').html('<b>From ' + $('#period_covered_from').val() + ' to ' + $('#period_covered_to').val() + '</b>');
								$('#myModal').modal('show');
								break;
						}
					}
					setTimeout(function(){ $('#print-all-preloader').hide() }, 1000);
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
	$(document).on('click', '.viewAttendanceSummaryForm', function (e) {
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
				pay_basis: pay_basis
			},
			dataType: "json",
			success: function (result) {
				page = my.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'viewAttendanceSummary':
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
								url: commons.baseurl + "timekeepingreports/AttendanceSummary/fetchRowsSummary" + plus_url,
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
							modal_title = my.data("employee_id_number") + " - " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("last_name")
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
	$(document).on('click', '#summarizeEmployeeDailyTimeRecord', function (e) {
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
						url: commons.baseurl + "timekeepingreports/AttendanceSummary/fetchRows" + plus_url,
						type: "POST"
					},
					"columnDefs": [{
						"targets": [0],
						"orderable": false,
					}, ],
				});
				button = '<a id="viewAttendanceSummaryAll" href="' + commons.baseurl + 'timekeepingreports/AttendanceSummary/viewAttendanceSummaryAll">' +
					'<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
					'<i class="material-icons">people</i> AttendanceSummary Summary' +
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
				url: commons.baseurl + "timekeepingreports/AttendanceSummary/fetchRows" + plus_url,
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
		mywindow.document.write('<style> * { font-family: arial; text-align: center } .header { font-size: 22px } .header-2 { font-size: 16px } .text-left { text-align: left } .text-right { text-align: right } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #ccc} @media print { .no-print, .no-print * { display: none !important; } } </style>')
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close();
		mywindow.focus();

		mywindow.print();
		mywindow.close();

		return true;
	}
})
