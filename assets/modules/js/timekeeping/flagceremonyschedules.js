$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	var table_weekly;

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: false,
		weekStart: 1,
		time: false,
		year: false
	});


	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: false,
			weekStart: 1,
			time: false,
			// year: false
		});
		
		// $('.dtp-select-month-before').css('display','none');
		// $('.dtp-select-month-after').css('display','none');
		// $('.dtp-select-year-before').css('display','none');
		// $('.dtp-select-year-after').css('display','none');
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})

	//Ajax non-forms
	$(document).on('click', '.updateFlagCeremonySchedulesForm, .addFlagCeremonySchedulesForm', function (e) {
		e.preventDefault();
		me = $(this)
		month = me.attr('data-month');
		year = me.attr('data-year');
		flagdateceremony = me.attr('data-flagdateceremony');
		key = me.attr('data-key');
		url = me.attr('href');
		$.ajax({
			type: "POST",
			url: url,
			data: {
				month: month,
				year : year,
				flagdateceremony : flagdateceremony,
				key : key,
			},
			dataType: "json",
			success: function (result) {
				page = me.attr('id');
				if (result.hasOwnProperty("key")) {
					// switch (result.key) {
					// 	case 'updateFlagCeremonySchedules':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-sm');
							$('#myModal .modal-title').html('Flag Ceremony Shedule');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
					// 		break;
					// }
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

	$(document).on('click', '.updateFlagCeremonySchedules, .addFlagCeremonySchedules', function (e) {
		e.preventDefault();
		me = $(this)
		var month = me.attr('data-month');
		var year = me.attr('data-year');
		var flagdateceremony = $('#flagdateceremony').val();
		var url = me.attr('href');

		content = 'Are you sure you want to proceed?';
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
										month : month,
										year : year,
										flagdateceremony : flagdateceremony
									},
									dataType: "json",
									success: function (result) {
										if (result.hasOwnProperty("key")) {
											if (result.Code == "0") {
												if (result.hasOwnProperty("key")) {
													switch (result.key) {
														case 'updateFlagCeremonySchedules':
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

		
	});

	$(document).on('click', '.activateFlagCeremonySchedules,.deactivateFlagCeremonySchedules', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		month = me.attr('data-month');
		year = me.attr('data-year');
		flagdateceremony = me.attr('data-flagdateceremony');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateFlagCeremonySchedulesForm')) {
			content = 'Are you sure you want to activate selected employee schedules?';
		} else if (me.hasClass('deactivateFlagCeremonySchedules')) {
			content = 'Are you sure you want to deactivate selected sub employee schedules?';
		} 
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
										month: month,
										year : year,
										flagdateceremony : flagdateceremony
									},
									dataType: "json",
									success: function (result) {
										if (result.Code == "0") {
											if (result.hasOwnProperty("key")) {
												 switch (result.key) {
												// 	case 'activateEmployeeSchedules':
												// 	case 'deactivateEmployeeSchedules':
													case 'activateFlagCeremonySchedules':
													case 'deactivateFlagCeremonySchedules':
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
			}
		});
	}

})
