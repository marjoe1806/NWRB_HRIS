$(function () {
	var page = "";
	base_url = commons.base_url;
	var table_monthly;
	var table_semimonthly;
	table_monthly = $('#datatables_monthly').DataTable({
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
	table_semimonthly = $('#datatables_semimonthly').DataTable({
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

	weekly = $('#datatables_weekly').DataTable({
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

	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})

	$(document).on('click', '#printSalaryGrades', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})

	$(document).on('submit', '#SalaryStepsForm', function (e) {
		let id = $(this).attr('data-id');
		let url = $(this).attr('action');
		e.preventDefault();
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: 'Are you sure you want to save?',
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
										grade_id: id,
										step_1: $('#step_1').val(),
										step_2: $('#step_2').val(),
										step_3: $('#step_3').val(),
										step_4: $('#step_4').val(),
										step_5: $('#step_5').val(),
										step_6: $('#step_6').val(),
										step_7: $('#step_7').val(),
										step_8: $('#step_8').val()
									},
									dataType: "json",
									success: function (result) {
										self.setContent(result.Message);
										self.setTitle('<label class="text-success">Success</label>');
										$('#myModal').modal('hide');
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


	//Confirms
	$(document).on('click', '.activateSalaryGrades,.deactivateSalaryGrades,.activateWeeklySalaryGrades,.deactivateWeeklySalaryGrades', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateSalaryGrades')) {
			content = 'Are you sure you want to activate selected salary grades?';
		} else if (me.hasClass('deactivateSubSalaryGrades')) {
			content = 'Are you sure you want to deactivate selected sub salary grades?';
		} else if (me.hasClass('activateWeeklySalaryGrades')) {
			content = 'Are you sure you want to deactivate selected sub salary grades?';
		} else if (me.hasClass('deactivateWeeklySalaryGrades')) {
			content = 'Are you sure you want to deactivate selected sub salary grades?';
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
													case 'activateSalaryGrades':
													case 'deactivateSalaryGrades':
													case 'activateWeeklySalaryGrades':
													case 'deactivateWeeklySalaryGrades':
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
	$(document).on('click', '#addSalaryGradesForm, .updateSalaryGradesForm, #addWeeklySalaryGradesForm, .updateWeeklySalaryGradesForm, #weeklySalaryGradesForm', function (e) {
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
						case 'addSalaryGrades':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Salary Grades');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;

						case 'addWeeklySalaryGrades':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Salary Grades');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'generateSalaryGrades':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Salary Grades');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'weeklySalaryGrades':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-title').html('Salary Grade ' + id);
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$('#myModal #SalaryStepsForm').attr('data-id', id);
							break;
						case 'updateSalaryGrades':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Salary Grades');
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
						case 'updateWeeklySalaryGrades':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Salary Grades');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
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
	$(document).on('submit', '#addSalaryGrades,#updateSalaryGrades,#addWeeklySalaryGrades,#updateWeeklySalaryGrades', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addSalaryGrades") {
			content = "Are you sure you want to add salary grades?";
		}
		if (form.attr('id') == "updateSalaryGrades") {
			content = "Are you sure you want to update salary grades?";
		}


		if (form.attr('id') == "addWeeklySalaryGrades") {
			content = "Are you sure you want to add salary grades?";
		}
		if (form.attr('id') == "updateWeeklySalaryGrades") {
			content = "Are you sure you want to update salary grades?";
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
														case 'addSalaryGrades':
														case 'updateSalaryGrades':
														case 'addWeeklySalaryGrades':
														case 'updateWeeklySalaryGrades':
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
				$("#table-holder-monthly").html(result.table_monthly);
				table_monthly = $('#datatables_monthly').DataTable({
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
				$("#table-holder-semimonthly").html(result.table_semimonthly);
				table_semimonthly = $('#datatables_semimonthly').DataTable({
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
