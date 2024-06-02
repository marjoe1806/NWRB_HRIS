$(function () {

	var page = "";
	base_url = commons.base_url;
	var table;
	var table_weekly;

	// table = $('#datatables').DataTable({
	// 	"pagingType": "full_numbers",
	// 	"lengthMenu": [
	// 		[10, 25, 50, -1],
	// 		[10, 25, 50, "All"]
	// 	],
	// 	responsive: true,
	// 	aaSorting: [],
	// 	language: {
	// 		search: "_INPUT_",
	// 		searchPlaceholder: "Search records",
	// 	}
	// });

	table_weekly = $('#datatables_weekly').DataTable({
		"paging": false,
		"ordering": false,
		"searching": false,
		"info": false,
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
		time: false
	});

	$('.timepicker').bootstrapMaterialDatePicker({
		format: 'HH:mm',
		clearButton: true,
		date: false
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
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})

	$(document).on('click', '#printEmployeeSchedules', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})

	//Confirms
	$(document).on('click', '.activateEmployeeSchedules,.deactivateEmployeeSchedules,.activateWeeklyEmployeeSchedules,.deactivateWeeklyEmployeeSchedules', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateEmployeeSchedules')) {
			content = 'Are you sure you want to activate selected employee schedules?';
		} else if (me.hasClass('deactivateSubEmployeeSchedules')) {
			content = 'Are you sure you want to deactivate selected sub employee schedules?';
		} else if (me.hasClass('activateWeeklyEmployeeSchedules')) {
			content = 'Are you sure you want to deactivate selected sub employee schedules?';
		} else if (me.hasClass('deactivateWeeklyEmployeeSchedules')) {
			content = 'Are you sure you want to deactivate selected sub employee schedules?';
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
													case 'activateWeeklyEmployeeSchedules':
													case 'deactivateWeeklyEmployeeSchedules':
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														$('#myModal').modal('hide');
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
	$(document).on('click', '#addEmployeeSchedulesForm, .updateEmployeeSchedulesForm, #addWeeklyEmployeeSchedulesForm, .updateWeeklyEmployeeSchedulesForm, #weeklyEmployeeSchedulesForm', function (e) {
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
						case 'addWeeklyEmployeeSchedules':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$('#shift_code_id').val(id);
							break;
						case 'weeklyEmployeeSchedules':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-title').html('Daily Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							loadTable();
							break;
						case 'updateEmployeeSchedules':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
							if (me.data("is_posted") == '1')
								$('.is_posted').prop('checked', true);
							if (me.data("is_semi_monthly") == '1')
								$('.is_semi_monthly').prop('checked', true);
							break;
						case 'updateWeeklyEmployeeSchedules':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Employee Schedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
							if (me.data("is_restday") == '1')
								$('.is_restday').prop('checked', true);
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
	$(document).on('submit', '#addEmployeeSchedules,#updateEmployeeSchedules,#addWeeklyEmployeeSchedules,#updateWeeklyEmployeeSchedules', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addEmployeeSchedules") {
			content = "Are you sure you want to add schedule?";
		}
		if (form.attr('id') == "updateEmployeeSchedules") {
			content = "Are you sure you want to update schedule?";
		}
		if (form.attr('id') == "addWeeklyEmployeeSchedules") {
			content = "Are you sure you want to add schedule?";
		}
		if (form.attr('id') == "updateWeeklyEmployeeSchedules") {
			content = "Are you sure you want to update schedule?";
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
														case 'addEmployeeSchedules':
														case 'updateEmployeeSchedules':
														case 'addWeeklyEmployeeSchedules':
														case 'updateWeeklyEmployeeSchedules':
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
				$("#table-holder").html(result.table);
				table_monthly = $('#datatables').DataTable({
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					destroy: true,
					responsive: true,
					aaSorting: [],
					language: {
						search: "_INPUT_",
						searchPlaceholder: "Search records",
					}

				});
				$("#table-holder-weekly").html(result.weekly);
				table_weekly = $('#datatables_weekly').DataTable({
					"paging": false,
					"ordering": false,
					"searching": false,
					"info": false,
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					destroy: true,
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
