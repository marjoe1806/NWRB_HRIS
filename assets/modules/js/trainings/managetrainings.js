$(function () {
	var base_url = commons.baseurl;

	$(document).on('change', '#filter1', function (e) {
		var filter = $(this).val();

		$('.filter2').hide();
		field_reset();
		switch (filter) {
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
		var title = $('#title').val();
		var sponsor = $('#sponsor').val();
		var venue = $('#venue').val();
		var office_order = $('#office_order').val();

		if (filter1 == '') {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select a filter.",
			});
			return false;
		}

		switch (filter1) {
			case 'title' :
				if (title == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please enter a title.",
					});
					return false;
				}

				var data = {
					'title': title
				};
				break;
			case 'sponsor' :
				if (sponsor == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a sponsor.",
					});
					return false;
				}

				var data = {
					'sponsor': sponsor
				}

				break;
			case 'venue' :
				if (venue == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a venue.",
					});
					return false;
				}

				var data = {
					'venue': venue
				}

				break;
			case 'office_order' :
				if (office_order == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a office_order.",
					});
					return false;
				}

				var data = {
					'office_order': office_order
				}

				break;
		}
		loadTable();
	});

	// ajax non-forms
	$(document).on(
		"click",
		"#addManageTrainingsForm, .updateManageTrainingsForm, .viewManageTrainingsForm, .viewTrainingsAttendeesForm, .addTrainingsAttendeesForm",
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
					data: {
						id: id,
						seminar_id: data.id
					},
					dataType: "json",
					success: function (result) {
						page = my.attr("id");
						if (result.hasOwnProperty("key")) {
							status_key = result.key;
							switch (status_key) {
								case 'addManageTrainings':
									page="";
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
									$('#myModal .modal-title').html('Add New Trainings');
									$('#myModal .modal-body').html(result.form);
									$('#myModal').modal('show');
									$('#employee_ms').multiSelect();
									$.when(

									).done(function() {
										$.AdminBSB.select.activate();
									})
									break;
								case 'updateManageTrainings':
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
									$('#myModal .modal-title').html('Update Trainings');
									$('#myModal .modal-body').html(result.form);
									$('#myModal').modal('show');
									// $('#employee_ms').multiSelect();
									$.when(
										$.ajax({
											url: commons.baseurl +
												"trainings/ManageTrainings/getManageTrainingsAttendees",
											data: {
												seminar_id: data.id
											},
											type: "POST",
											dataType: "json",
											success: function(result) {
												if (result.Code === "0") {
													var selectedAttendees = result.Data.map(function(attendee) {
														return attendee.employee_id;
													});
									
													$('#employee_ms').val(selectedAttendees).multiSelect('refresh');
												}
											},
										})
									).done(function() {
										$.each(my.data(),function(i,v){
											$('#myModal .'+i).val(my.data(i)).change();
										});
										$.AdminBSB.select.activate();
									})
									break;
								case "viewManageTrainings":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										'Trainings Details'
									);
									$("#myModal .modal-body").html(result.form);
									$(".modal-lg").css("width", "1060px");
									// $('#employee_ms').multiSelect();
									$.when(
										$.ajax({
											url: commons.baseurl +
												"trainings/ManageTrainings/getManageTrainingsAttendees",
											data: {
												seminar_id: data.id
											},
											type: "POST",
											dataType: "json",
											success: function(result) {
												if (result.Code === "0") {
													var selectedAttendees = result.Data.map(function(attendee) {
														return attendee.employee_id;
													});									
													$('#employee_ms').val(selectedAttendees).multiSelect('refresh');
												}
											},
										})
									).done( function () {
										$.each(my.data(),function(i,v){
											$('#myModal #' + i).val(my.data(i)).change();
											$('#myModal .' + i).attr('style', 'pointer-events: none;');
										});
										$('#select-all').hide();
										$('#deselect-all').hide();
									});
									break;
							}
							// $.when(
								
							// ).done(function () {
							// 	$.each(my.data(), function (i, v) {
							// 		if (parseInt($("#printPreview").length) > 0) {
							// 			$("#myModal ." + i).html(my.data(i));
							// 		} else {
							// 			$("#myModal ." + i)
							// 				.val(my.data(i))
							// 				.change();
							// 		}
							// 	});
							// });
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

	// ajax forms
	$(document).on('submit', '#addManageTrainings, #updateManageTrainings, #addTrainingsAttendees', function(e) {
		e.preventDefault();
		var form = $(this);
		content = "Are you sure you want to proceed?";
		if(form.attr('id') == "addManageTrainings") {
			content = "Are you sure you want to add manage trainings?";
		}
		if(form.attr('id') == 'updateManageTrainings') {
			content = "Are you sure you want to update manage trainings?";
		}
		if(form.attr('id') == 'addManageTrainingsAttendees') {
			content = "Are you sure you want to add attendees?";
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
														case 'addManageTrainings':
														case 'updateManageTrainings':
														case 'addManageTrainingsAttendees':
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
										console.log(form.serialize());
										console.log(result);
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

	//event triggered when clicking select all button
	//selects all available options
	$(document).on('click','#select-all',function(e){
		$('#employee_ms').multiSelect('select_all');
		return false;
	});

	//event triggered when clicking deselect all button
	//deselects all selected options
	$(document).on('click','#deselect-all',function(e){
		$('#employee_ms').multiSelect('deselect_all');
		return false;
	});
	
});

function field_reset() {
	$('#title').val("").change();
	$('#sponsor').val("").change();
	$('#venue').val("").change();
	$('#office_order').val("").change();
}

function loadTable() {
	plus_url = "";

	var filter1 = $('#filter1').val();
	var title = $('#title').val();
	var sponsor = $('#sponsor').val();
	var venue = $('#venue').val();
	var office_order = $('#office_order').val();

	switch (filter1) {
		case 'title' :
			plus_url = "?Title=" + title;
			break;
		case 'sponsor' :
			plus_url = "?Sponsor=" + sponsor;
			break;
		case 'venue' :
			plus_url = "?Venue=" + venue;
			break;
		case 'office_order' :
			plus_url = "?OfficeOrder=" + office_order;
			break;
	}

	$("#datatables").DataTable().clear().destroy();
	table = $("#datatables").DataTable({
		processing: true,
		serverSide: true,
		order: [],
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
			var input = $('.dataTables_filter input').unbind(),
				self = this.api(),
				$searchButton = $(
					'<button id="search-manage-trainings" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
				)
					.html('<i class="material-icons">search</i>')
					.click(function () {
						if (!$("#search-manage-trainings").is(":disabled")) {
							$("#search-manage-trainings").attr("disabled", true);
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
				
				if	($("#search-manage-trainings").length === 0) {
					$('.dataTables_filter').append($searchButton);
				}

		},
		drawCallback: function (settings) {
			$('#search-loader').remove();
			$('#search-manage-trainings').removeAttr('disabled');
			$('#datatables button').removeAttr('disabled');
		},
		ajax: {
			url: commons.baseurl + "trainings/ManageTrainings/fetchRows" + plus_url,
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
