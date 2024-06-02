$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	table = $('#datatables').DataTable({
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
	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			maxDate: new Date(),
			time: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})
	$(document).on('click', '#printAccomplishmentReports', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})

	//Confirms
	$(document).on('click', '.activateAccomplishmentReports,.deactivateAccomplishmentReports', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateAccomplishmentReports')) {
			content = 'Are you sure you want to activate selected report?';
		} else if (me.hasClass('deactivateSubAccomplishmentReports')) {
			content = 'Are you sure you want to deactivate selected sub report?';
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
													case 'activateAccomplishmentReports':
													case 'deactivateAccomplishmentReports':
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
	$(document).on('click', '#addAccomplishmentReportsForm,.updateAccomplishmentReportsForm', function (e) {
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
						case 'addAccomplishmentReports':
							$.when(
								getFields.employee(),
								getFields.document()
							).done(function () {
								$.AdminBSB.select.activate();
							})
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Upload New Accomplishment Report');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'generateAccomplishmentReports':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Employee report');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateAccomplishmentReports':
							$.when(
								getFields.employee(),
								getFields.document()
							).done(function () {
								$.AdminBSB.select.activate();
								$('.employee_id').selectpicker('val', me.data('employee_id'));
								$('.type_id').selectpicker('val', me.data('type_id'));
								$('.employee_id').selectpicker('refresh');
							})
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Accomplishment Report');
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
	$(document).on('submit', '#addAccomplishmentReports,#updateAccomplishmentReports', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addAccomplishmentReports") {
			content = "Are you sure you want to add report?";
		}
		if (form.attr('id') == "updateAccomplishmentReports") {
			content = "Are you sure you want to update report?";
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
									data: new FormData(form[0]),
									contentType: false,
									processData: false,
									dataType: "json",
									success: function (result) {
										if (result.hasOwnProperty("key")) {
											if (result.Code == "0") {
												if (result.hasOwnProperty("key")) {
													switch (result.key) {
														case 'addAccomplishmentReports':
														case 'updateAccomplishmentReports':
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
				table = $('#datatables').DataTable({
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
