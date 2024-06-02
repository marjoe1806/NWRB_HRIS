$(function () {
	var base_url = commons.baseurl;

	$(document).on('change', '#filter1', function (e) {
		var filter = $(this).val();

		$('.filter2').hide();
		field_reset();
		switch (filter) {
			case 'vacancy' :
				$('.vacancy_id_div').show();
				$.when(
					getFields.vacant_job()
				).done(function () {
					$.AdminBSB.select.activate();
				});
				break;
			default :
				$('.' + filter + '_div').show();
				break;
		}
	});

	$(document).on("show.bs.modal", "#myModal", function () {
		addDateMask();
	});

	// Ajax Forms
	$(document).on('click', '.search_btn', function (e) {
		var url = '';
		var filter1 = $('#filter1').val();
		var name = $('#name').val();
		var vacancy_id = $('#vacancy_id').val();

		if (filter1 == '') {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select a filter.",
			});
			return false;
		}

		switch (filter1) {
			case 'name' :
				if (name == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please enter a name.",
					});
					return false;
				}

				var data = {
					'name': name
				};
				break;
			case 'vacancy' :
				if (vacancy_id == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a position title.",
					});
					return false;
				}

				var data = {
					'vacancy_id': vacancy_id
				};

				break;
		}
		loadTable();
	});

	// ajax non-forms
	$(document).on(
		"click",
		"#addExaminationSchedulesForm, .updateExaminationSchedulesForm, .viewExaminationSchedulesForm",
		function (e) {
			e.preventDefault();
			my = $(this);
			data = my.data();
			id = my.attr("id");
			url = my.attr("href");

			if (!my.find("button").is(":disabled")) {
				getFields.reloadModal();
				$.ajax({
					type: "POST",
					url: url,
					data: {id: id},
					dataType: "json",
					success: function (result) {
						page = my.attr("id");
						if (result.hasOwnProperty("key")) {
							status_key = result.key;
							switch (status_key) {
								case 'addExaminationSchedule':
									page="";
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
									$('#myModal .modal-title').html('Add New Examination Schedule');
									$('#myModal .modal-body').html(result.form);
									$('#myModal').modal('show');
									$.when(

									).done(function() {
										$.AdminBSB.select.activate();
									})
									break;
								case 'updateExaminationSchedule':
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
									$('#myModal .modal-title').html('Update Examination Schedule');
									$('#myModal .modal-body').html(result.form);
									$('#myModal').modal('show');
									$.when(

									).done(function() {
										$.each(my.data(),function(i,v){
											if(i === 'schedule_date') {
												$('#examination_schedule_date').val(my.data(i)).change();
											}
											if(i === 'schedule_time') {
												$('#examination_schedule_time').val(my.data(i)).change();
											}
											if(i === 'remarks') {
												$('#examination_remarks').val(my.data(i)).change();
											}
											$('#' + i).val(my.data(i)).change();
										});
										$.AdminBSB.select.activate();
									})
									break;
								case "viewExaminationSchedule":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										'Examination Schedule Details'
									);
									$("#myModal .modal-body").html(result.form);
									$(".modal-lg").css("width", "1060px");
									$.each(my.data(),function(i,v){
										if(i === 'schedule_date') {
											$('#examination_schedule_date').val(my.data(i)).change();
											$('#examination_schedule_date').attr('style', 'pointer-events: none;');
										}
										if(i === 'schedule_time') {
											$('#examination_schedule_time').val(my.data(i)).change();
											$('#examination_schedule_time').attr('style', 'pointer-events: none;');
										}
										if(i === 'remarks') {
											$('#examination_remarks').val(my.data(i)).change();
											$('#examination_remarks').attr('style', 'pointer-events: none;');
										}
										$('#' + i).val(my.data(i)).change();
										$('.' + i).attr('style', 'pointer-events: none;');
									});
									break;
							}
							$.when(
								getFields.positionWithItem()
							).done(function () {
								$.each(my.data(), function (i, v) {
									if (parseInt($("#printPreview").length) > 0) {
										$("#myModal ." + i).html(my.data(i));
									} else {
										$("#myModal ." + i)
											.val(my.data(i))
											.change();
									}
								});
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
			}
		}
	);

	$(document).on('click', '.passedExamination, .failedExamination', function (e) {
		e.preventDefault();
		var form = $(this);
		my = $(this);
		data = my.data();
		id = my.data('id');

		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "passedExamination") {
			content = "Are you sure you want to set status to passed?";
			flag = 0;
		}
		if (form.attr('id') == "failedExamination") {
			content = "Are you sure you want to set status to failed?";
			flag = 1;
		}

		url = my.attr('href');
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
									data : {
										id : id,
									},
									dataType: "json",
									success: function (result) {
										if (result.hasOwnProperty("key")) {
											if (result.Code == "0") {
												if (result.hasOwnProperty("key")) {
													switch (result.key) {
														case 'passedExamination':
														case 'failedExamination':
															self.setContent(result.Message);
															self.setTitle('<label class="text-default">Send Notification</label>');
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
							},
						});
					}

				},
				cancel: function () {}
			}
		});
	});

	// ajax forms
	$(document).on('submit', '#addExaminationSchedule, #updateExaminationSchedule', function(e) {
		e.preventDefault();
		var form = $(this);

		if(form.attr('id') == 'addInterviewSchedule' || form.attr('id') == 'updateInterviewSchedule') {
			var flag = isValidDate($('#schedule_date').val());

			if (!flag) {
				return false;
			}
		}
		content = "Are you sure you want to proceed?";
		if(form.attr('id') == "addExaminationSchedule") {
			content = "Are you sure you want to add examination schedule?";
		}
		if(form.attr('id') == 'updateExaminationSchedule') {
			content = "Are you sure you want to update examination schedule?";
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
						// code here
						$.confirm({
							content: function () {
								var self = this;
								return $.ajax({
									type: "POST",
									url: url,
									data: form.serialize(),
									dataType: "json",
									success: function(result){
										if(result.hasOwnProperty("key")){
											if(result.Code == "0"){
												if(result.hasOwnProperty("key")){
													switch(result.key){
														case 'addExaminationSchedule':
														case 'updateExaminationSchedule':
															self.setContent(result.Message);
															self.setTitle('<label class="text-success">Success</label>');
															$('#myModal .modal-body').html('');
															$('#myModal').modal('hide');
															loadTable();
															break;
													}
												}
											}
											else{
												self.setContent(result.Message);
												self.setTitle('<label class="text-danger">Failed</label>');
											}
										}
									},
									error: function(result){
										self.setContent("There was an error in the connection. Please contact the administrator for updates.");
										self.setTitle('<label class="text-danger">Failed</label>');
									}
								});
							}
						});
					}
				},
				cancel: function () {

				}
			}
		});
	});

	$(document).on("click", "#btnPrintDetails", function () {
		var mywindow = window.open("", "PRINT", "height=400,width=600");

		mywindow.document.write(
			"<html moznomarginboxes mozdisallowselectionprint><head>"
		);
		mywindow.document.write("</head><body >");
		mywindow.document.write(document.getElementById("printPreview").innerHTML);
		mywindow.document.write("</body></html>");

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();
	});

	
});

function field_reset() {
	$('#id').val("").change();
	$('#name').val("").change();
	$('#vacancy_id').val("").change();
}

function loadTable() {
	plus_url = "";

	var filter1 = $('#filter1').val();
	var vacancy_id = $('#vacancy_id').val();
	var name = $('#name').val();

	switch (filter1) {
		case 'name' :
			plus_url = "?Name=" + name;
			break;
		case 'vacancy' :
			plus_url = "?VacancyID=" + vacancy_id;
			break;
	}

	$("#datatables").DataTable().clear().destroy();
	table = $("#datatables").DataTable({
		processing: true,
		serverSide: true,
		stateSave: true, // presumably saves state for reloads -- entries
		bStateSave: true, // presumably saves state for reloads -- page number
		order: [],
		scroller: {
			displayBuffer: 20,
		},
		columnDefs: [
			{
				targets: [0],
				orderable: false,
			},
		],
		initComplete: function () {
			var input = $('.dataTables_filter input').unbind(),
				self = this.api(),
				$searchButton = $(
					'<button id="search-examination-schedule" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
				)
					.html('<i class="material-icons">search</i>')
					.click(function () {
						if (!$("#search-examination-schedule").is(":disabled")) {
							$("#search-examination-schedule").attr("disabled", true);
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
				
				if	($("#search-examination-schedule").length === 0) {
					$('.dataTables_filter').append($searchButton);
				}

		},
		drawCallback: function (settings) {
			$('#search-loader').remove();
			$('#search-examination-schedule').removeAttr('disabled');
			$('#datatables button').removeAttr('disabled');
		},
		ajax: {
			url: commons.baseurl + "recruitment/ExaminationSchedules/fetchRows" + plus_url,
			type: 'POST',
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

function addDateMask() {
	$(".date_mask, .datepicker").each(function () {
		if ($(this).hasClass("date_mask") && $(this).closest("td").find("input:last").hasClass("chk")) {
			if (!$(this).closest("td").find("input:last").iCheck("update")[0].checked) {
				$(this).inputmask("mm/dd/yyyy", {placeholder: "mm/dd/yyyy",});
			}
		} else {
			$(this).inputmask("mm/dd/yyyy", {placeholder: "mm/dd/yyyy",});
		}
	});
}

function isValidDate(value) {
	var flag = 0;
	var message = "Invalid date format!";
	var timestamp = Date.parse(value);
	var schedule_date = false;
	var now = new Date();
	now.setHours(0,0,0,0);

	if (isNaN(timestamp) === false) {
		schedule_date = new Date(timestamp);
		flag = 1;
		if(schedule_date < now) {
			flag = 0;
			message = "Selected date is in the past";
		}
	}

	if(!flag) {
		$.alert({
			title: '<label class="text-danger">Failed</label>',
			content: message,
			buttons: {
				ok: function() {
					$(this).focus();
				},
			}
		});
	}

	return flag;
}

function addrow(element, count) {
	var cloneRow = "";
	var inc = "";
	if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI") {
		var cloneRow = element.closest("div").find("table tbody tr:first").clone();
		inc = element
			.closest("div")
			.find("table tbody tr:first")
			.find(".form-control")
			.attr("name");
	} else {
		var cloneRow = element.closest("div").find("table tbody tr:last").clone();
		inc = element
			.closest("div")
			.find("table tbody tr:last")
			.find(".form-control")
			.attr("name");
	}

	$is = false;
	if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
		inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
		$is = true;
	} else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
	cloneRow.find(".form-group").find("label.error").remove();
	for (var i = 0; i < count; i++) {
		if (cloneRow.closest("td").find("a").length == 0) {
			var elementName = "";

			if ($is)
				elementName =
					cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
					"[" +
					inc +
					"]";
			else {
				elementName =
					cloneRow.find(".form-control").eq(i).attr("name").slice(0, -4) +
					"[" +
					inc +
					"]";
			}
			cloneRow
				.find(".form-control")
				.eq(i)
				.attr("name", elementName)
				.attr("id", elementName)
				.addClass(
					element.attr("id") === "btnViewAddFile" && i == 4
						? "inputRequired"
						: ""
				);
		} else {
			cloneRow.find("a").remove();
		}
	}

	cloneRow.find(".iCheck-helper").remove();
	cloneRow.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position", "").css("display", "inline-table");
	cloneRow.find(".form-control").val("").end();
	if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI")
		element.closest("div").find("table tbody").prepend(cloneRow);
	else element.closest("div").find("table tbody").append(cloneRow);
	addDateMask();
	initValidation();
	autosize($("textarea.auto-growth"));
}

function initValidation() {
	$("input:not(.inputRequired)").each(function () {
		$(this).rules("remove", "required");
	});

	$("textarea:not(.inputRequired)").each(function () {
		$(this).rules("remove", "required");
	});

	$(".chkradio").each(function () {
		$(this).rules("add", {
			required: true
		});
	});

	$("input.inputRequired, textarea.inputRequired").each(function () {
		$(this).rules("add", {
			required: function (element) {
				return $(element).val().trim() == "";
			},
			normalizer: function (value) {
				return $.trim(value);
			},
		});
	});

	$("input.inputifyes").each(function () {
		$(this).rules("add", {
			required: function (element) {
				var num = parseInt($(element).attr("name").slice(-2));
				return (
					$(
						"input[name='radio_input_" +
						(num < 10 ? "0" + num : num) +
						"']:checked"
					).val() == "Yes" && $(element).val().trim() == ""
				);
			},
		});
	});

	$(".inputFile").each(function () {
		$(this).rules("add", {
			required: function (element) {
				var tdelem = $(element)
					.closest("tr")
					.find("td:first-child")
					.find(".form-control:eq(2)");
				return tdelem.val() == "" && $(element).val().trim() == "";
			},
		});
	});

	$("input.is_first_col_required, textarea.is_first_col_required").each(
		function () {
			$(this).rules("add", {
				required: function (element) {
					var tdelem = $(element)
						.closest("tr")
						.find("td:first-child")
						.find(".form-control:last");
					return (
						tdelem.val() != "N/A" &&
						tdelem.val() != "n/a" &&
						$(element).val().trim() == ""
					);
				},
			});
		}
	);

	$("input.is_sec_col_required, textarea.is_sec_col_required").each(
		function () {
			$(this).rules("add", {
				required: function (element) {
					var tdelem = $(element)
						.closest("tr")
						.find("td:eq(1)")
						.find(".form-control");
					return (
						tdelem.val() != "N/A" &&
						tdelem.val() != "n/a" &&
						$(element).val().trim() == ""
					);
				},
			});
		}
	);

	$("input.is_third_col_required, textarea.is_third_col_required").each(
		function () {
			$(this).rules("add", {
				required: function (element) {
					var tdelem = $(element)
						.closest("tr")
						.find("td:eq(2)")
						.find(".form-control");
					return (
						tdelem.val() != "N/A" &&
						tdelem.val() != "n/a" &&
						$(element).val().trim() == ""
					);
				},
			});
		}
	);

	$(".chk").iCheck("destroy");
	$(".chk").iCheck({
		checkboxClass: "icheckbox_square-grey",
	});
}
