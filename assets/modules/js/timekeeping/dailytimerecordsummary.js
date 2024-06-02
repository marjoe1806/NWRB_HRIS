$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	loadTable();
	$.when(
		getFields.employee(),
		getFields.payBasis3()
	).done(function () {
		$.AdminBSB.select.activate();
		// $('#payroll_period_id').change();
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
		$('.timepicker').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			clearButton: true,
			date: false
		});
	})
	$(document).on('click', '#printDailyTimeRecordSummary', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})
	$(document).on('keypress keyup keydown', 'form #amount', function (e) {
		$('form #balance').val($(this).val())
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
		// console.log(payroll_period2);
	});

	//Confirms
	$(document).on('click', '.activateDailyTimeRecordSummary,.deactivateDailyTimeRecordSummary', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateDailyTimeRecordSummary')) {
			content = 'Are you sure you want to activate selected Daily Time Record?';
		} else if (me.hasClass('deactivateSubDailyTimeRecordSummary')) {
			content = 'Are you sure you want to deactivate selected Daily Time Record?';
		}
		data = {
			id: id
		};
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: content,
			type: 'orange',
			buttons: {
				confirm: {
					btnClass: 'btn-blue',
					action: function () {
						//Code here
						$.confirm({
							content: function () {
								var self = this;
								return $.ajax({
									type: "POST",
									url: url,
									data: {
										id: id
									},
									dataType: "json",
									success: function (result) {
										if (result.Code == "0") {
											if (result.hasOwnProperty("key")) {
												switch (result.key) {
													case 'activateDailyTimeRecordSummary':
													case 'deactivateDailyTimeRecordSummary':
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														reloadTable();
														break;
												}
											}
										} else {
											self.setContent(result.Message);
											self.setTitle('<label class="text-danger">Failed</label>');
										}
									},
									error: function (result) {
										self.setContent("There was an error in the connection. Please contact the administrator for updates.");
										self.setTitle('<label class="text-danger">Failed</label>');
									}
								});
							}
						});
					}

				},
				cancel: function () {}
			}
		});
	})
	$(document).on('click', '#summarizeEmployeeDailyTimeRecord', function (e) {
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
			plus_url = '?PayBasis=' + pay_basis;
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
							url: commons.baseurl + "timekeeping/DailyTimeRecordSummary/fetchRows" + plus_url,
							type: "POST"
						},
						"columnDefs": [{
							"targets": [0],
							"orderable": false,
						}, ],
					});
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
	//Ajax non-forms
	$(document).on('click', '#addDailyTimeRecordSummaryForm,.updateDailyTimeRecordSummaryForm,.viewDailyTimeRecordSummaryForm,.viewSubDailyTimeRecordSummaryForm', function (e) {
		e.preventDefault();
		my = $(this)
		id = my.attr('data-id');
		name = my.attr('data-last_name') + ', ' + my.attr('data-first_name') + ' ' + my.attr('data-middle_name');
		url = my.attr('href');
		employee_id = $('.search_entry #employee_id').val()
		year = $('.search_entry #search_year').val()
		month = $('.search_entry #month').val()
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function (result) {
				page = my.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'addDailyTimeRecordSummary':
								page = "";
								$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
								$('#myModal .modal-title').html('Add New Daily Time Record');
								$('#myModal .modal-body').html(result.form);
								$('#employee_number').val(employee.Data.details[0].employee_number);
								$('#myModal').modal('show');

							// }
							break;
						case 'viewDailyTimeRecordSummary':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-dialog').attr('style', 'width: 95%');
							$('#myModal .modal-title').html('Daily Time Record Details');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');



							plus_url2 = '?Id=' + id
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
									url: commons.baseurl + "timekeeping/DailyTimeRecordSummary/fetchRowsSummary" + plus_url,
									dataType: "json",
									success: function (result) {
										$('#table-holder-summary').html(result.table);
										$('#myModal #summary_name_label').html('<b>' + employee.Data.details[0].last_name + ', ' +employee.Data.details[0].first_name + ' ' +employee.Data.details[0].middle_name + '</b>');
										$('#myModal #summary_period_label').html('<b>From ' + $('#period_covered_from').val() + ' to ' + $('#period_covered_to').val() + '</b>');
									},
									error: function (result) {
										$.alert({
											title: '<label class="text-danger">Failed</label>',
											content: 'There was an error in the connection. Please contact the administrator for updates.'
										});
									}
								});
							break;
						case 'updateDailyTimeRecordSummary':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Daily Time Record');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');

							break;
					}
					// $.when(
					// 	getFields.employee(),
					// 	getFields.loan(),
					// 	getFields.payrollperiod()
					// ).done(function () {
					// 	sub_loan_id = my.data('sub_loans_id')
					// 	$.each(my.data(), function (i, v) {
					// 		if (i == "hold_tag")
					// 			$('.hold_tag' + v).click();
					// 		else
					// 			$('.' + i).val(my.data(i)).change();
					// 	});
						// $('.payment_status_text').html(my.data('payment_status'));
						// if (result.key == "viewDailyTimeRecordSummary") {
							// $('form').find('input, textarea, button, select').attr('disabled', 'disabled');
							// $('form').find('#cancelUpdateForm').removeAttr('disabled');
						// }

						// $.AdminBSB.select.activate();
					// })
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
	$(document).on('submit', '#addDailyTimeRecordSummary,#updateDailyTimeRecordSummary', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addDailyTimeRecordSummary") {
			content = "Are you sure you want to add Daily Time Record?";
		}
		if (form.attr('id') == "updateDailyTimeRecordSummary") {
			content = "Are you sure you want to update Daily Time Record?";
		}
		url = form.attr('action');
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: content,
			type: 'orange',
			buttons: {
				confirm: {
					btnClass: 'btn-blue',
					action: function () {
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
														case 'addDailyTimeRecordSummary':
														case 'updateDailyTimeRecordSummary':
															self.setContent(result.Message);
															self.setTitle('<label class="text-success">Success</label>');
															$('#myModal .modal-body').html('');
															$('#myModal').modal('hide');
															reloadTable();
															break;
													}
												}
											} else {
												self.setContent(result.Message);
												self.setTitle('<label class="text-danger">Failed</label>');
											}
										}
									},
									error: function (result) {
										self.setContent("There was an error in the connection. Please contact the administrator for updates.");
										self.setTitle('<label class="text-danger">Failed</label>');
									}
								});
							}
						});
					}

				},
				cancel: function () {}
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
				url: commons.baseurl + "timekeeping/DailyTimeRecordSummary/fetchRows" + plus_url,
				type: "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});
	}

	function reloadTable() {
		table.ajax.reload();
	}

	// console.log(commons.baseurl)

	function PrintElem(elem) {
		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();

		return true;
	}

})
