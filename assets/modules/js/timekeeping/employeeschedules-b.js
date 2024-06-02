$(function () {
	var page = "";
	base_url = commons.base_url;
	var table_monthly;
	var table_semimonthly;
	table_monthly = $('#datatables_regular').DataTable({
		"pagingType": "full_numbers",
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		responsive: true,
		aaSorting: [],
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Search records",
		}
	});
	table_semimonthly = $('#datatables_shifting').DataTable({
		"pagingType": "full_numbers",
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		responsive: true,
		aaSorting: [],
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Search records",
		}
	});

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		maxDate: new Date(),
		time: false
	});

	

	$(document).on("change", "#break_time_end", function () {


	var checkin = $("start_time").val();
	var checkout = $("end_time").val();
	var breakout = $("break_time_start").val();
	var breakin = $("break_time_end").val();
	var timeStart = new Date("01/01/2018 " + checkin).getHours();
	var timeEnd = new Date("01/01/2018 " + checkout).getHours();
	
	// var hourDiff = timeEnd - timeStart; 
	console.log(timeStart);

	});

	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			maxDate: new Date(),
			time: false
		});
		$('.timepicker').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			clearButton: true,
			date: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})
	$(document).on('click', '#printEmployeeSchedules', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})
	//Confirms
	$(document).on('click', '.activateEmployeeSchedules,.deactivateEmployeeSchedules', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateEmployeeSchedules')) {
			content = 'Are you sure you want to activate selected Employee Schedules?';
		} else if (me.hasClass('deactivateSubEmployeeSchedules')) {
			content = 'Are you sure you want to deactivate selected sub Employee Schedules?';
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
													case 'activateEmployeeSchedules':
													case 'deactivateEmployeeSchedules':
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														loadTable();
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
	//Ajax non-forms
	$(document).on('click', '#addEmployeeSchedulesForm,.updateEmployeeSchedulesForm', function (e) {
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
						case 'addEmployeeSchedules':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'generateEmployeeSchedules':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateEmployeeSchedules':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
							if (me.data("shift_type") == '1')
								$('.shift_type').prop('checked', true);
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
	$(document).on('submit', '#addEmployeeSchedules,#updateEmployeeSchedules', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addEmployeeSchedules") {
			content = "Are you sure you want to add Employee Schedules?";
		}
		if (form.attr('id') == "updateEmployeeSchedules") {
			content = "Are you sure you want to update Employee Schedules?";
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
														case 'addEmployeeSchedules':
														case 'updateEmployeeSchedules':
															self.setContent(result.Message);
															self.setTitle('<label class="text-success">Success</label>');
															$('#myModal .modal-body').html('');
															$('#myModal').modal('hide');
															loadTable();
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
		var url = window.location.href;
		$.ajax({
			url: url,
			dataType: "json",
			success: function (result) {
				$("#table-holder-regular").html(result.table_monthly);
				table_monthly = $('#datatables_regular').DataTable({
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					responsive: true,
					aaSorting: [],
					language: {
						search: "_INPUT_",
						searchPlaceholder: "Search records",
					}

				});
				$("#table-holder-shifting").html(result.table_semimonthly);
				table_semimonthly = $('#datatables_shifting').DataTable({
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					responsive: true,
					aaSorting: [],
					language: {
						search: "_INPUT_",
						searchPlaceholder: "Search records",
					}

				});
			}
		});
	}
	console.log(commons.baseurl)

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
