$(function () {
	var base_url = commons.baseurl;

	$(document).on('change', '#filter1', function (e) {
		var filter = $(this).val();

		$('.filter2').hide();
		field_reset();
		switch (filter) {
			case 'position' :
				$('.position_id_div').show();
				$.when(
					getFields.positionWithItem()
				).done(function () {
					$.AdminBSB.select.activate();
				});
				break;
			case 'supervisor' :
				$('.employee_id_div').show();
				// $.when(
				// 	getFields.employee()
				// ).done(function () {
				// 	$.AdminBSB.select.activate();
				// });
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
		var position_id = $('#position_id').val();
		var supervisor_id = $('#employee_id').val();

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
			case 'position' :
				if (position_id == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a position.",
					});
					return false;
				}

				var data = {
					'position_id': position_id
				};

				break;
			case 'supervisor' :
				if (supervisor_id == '') {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: "Please select a supervisor.",
					});
					return false;
				}

				var data = {
					'supervisor_id': supervisor_id
				};

				break;
		}
		loadTable();
	});

	// ajax non-forms
	$(document).on(
		"click",
		"#addApplicantChecklistsForm, .updateApplicantChecklistsForm, .viewApplicantChecklistsForm",
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
								case 'addApplicantChecklist':
									page="";
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
									$('#myModal .modal-title').html('Add New Applicant Checklist');
									$('#myModal .modal-body').html(result.form);
									initValidation();
									$('#myModal').modal('show');
									$.when(
										getFields.positionWithItem(),
										getFields.employee()
									).done(function () {
										$.each(my.data(), function(i,v) {
											$('#' + i).val(my.data(i)).change();
										});
										$.AdminBSB.select.activate();
									});
									break;
								case 'updateApplicantChecklist':
									$('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
									$('#myModal .modal-title').html('Update Applicant Checklist');
									$('#myModal .modal-body').html(result.form);
									$('#myModal').modal('show');
									$.when(
										getFields.positionWithItem(),
										getFields.employee()
									).done(function () {
										$.each(my.data(), function(i,v) {
											if (i === 'employee_id') {
												$('#myModal #' + i).val(my.data(i));
											} else {
												$('#myModal #' + i).val(my.data(i)).change();
											}
										});
										$.AdminBSB.select.activate();
										if (result.key == "updateApplicantChecklist") {
											$.post(
												commons.baseurl + "recruitment/ApplicantChecklists/getApplicantChecklistItems",
												{ id: data.id },
												function (result) {
													result = JSON.parse(result);

													if (result.Code == "0") {
														if (result.Data.details.length > 0 ) {
															var guidelines = result.Data.details;
															var items = result.Data.items;
															var first_day = '';
															var policies = '';
															var administrative_procedure = '';
															var general_orientation = '';
															var position_information = '';
															var first_day_item = '';
															var policies_item = '';
															var administrative_procedure_item = '';
															var general_orientation_item = '';
															var position_information_item = '';

															// Get employee guidelines by category
															// static category: FIRST DAY, POLICIES, ADMINISTRATIVE PROCEDURES, POSITION INFORMATION
															$.each( guidelines, function(k,v) {
																if(v.category === 'FIRST DAY') {
																	first_day += '<input type="hidden" value="0" name="first_day[' + v.id + ']">';
																	first_day += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="first_day[' + v.id + ']" id="first_day[' + v.id + ']" ' + v.is_checked + ' >';
																	first_day += '<label for="first_day[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	first_day_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'POLICIES') {
																	policies += '<input type="hidden" value="0" name="policies[' + v.id + ']">';
																	policies += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="policies[' + v.id + ']" id="policies[' + v.id + ']" ' + v.is_checked + ' >';
																	policies += '<label for="policies[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	policies_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'ADMINISTRATIVE PROCEDURES') {
																	administrative_procedure += '<input type="hidden" value="0" name="administrative_procedure[' + v.id + ']">';
																	administrative_procedure += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="administrative_procedure[' + v.id + ']" id="administrative_procedure[' + v.id + ']" ' + v.is_checked + ' >';
																	administrative_procedure += '<label for="administrative_procedure[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	administrative_procedure_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'GENERAL ORIENTATION') {
																	general_orientation += '<input type="hidden" value="0" name="general_orientation[' + v.id + ']">';
																	general_orientation += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="general_orientation[' + v.id + ']" id="general_orientation[' + v.id + ']" ' + v.is_checked + ' >';
																	general_orientation += '<label for="general_orientation[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	general_orientation_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'POSITION INFORMATION') {
																	position_information += '<input type="hidden" value="0" name="position_information[' + v.id + ']">';
																	position_information += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="position_information[' + v.id + ']" id="position_information[' + v.id + ']" ' + v.is_checked + ' >';
																	position_information += '<label for="position_information[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	position_information_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
															});

															first_day += '<br>'; policies += '<br>'; administrative_procedure += '<br>'; general_orientation += '<br>'; position_information += '<br>';

															$("#myModal #first_day").html(first_day);
															$("#myModal #policies").html(policies);
															$("#myModal #administrative_procedure").html(administrative_procedure);
															$("#myModal #general_orientation").html(general_orientation);
															$("#myModal #position_information").html(position_information);
															$("#myModal #first_day_item").html(first_day_item);
															$("#myModal #policies_item").html(policies_item);
															$("#myModal #administrative_procedure_item").html(administrative_procedure_item);
															$("#myModal #general_orientation_item").html(general_orientation_item);
															$("#myModal #position_information_item").html(position_information_item);

															// display employee guideline items.
															// displays from left to right
															$.each( items, function(k, v) {
																$.each(guidelines, function(pk, pv) {
																	if(pv.id === v.guideline_id) {
																		var elem = "#guideline_id_" + pv.id;
																		$(elem).append('<div class="col-md-6">* ' + v.name + '</div>');
																	}
																});
															});
														}
													}
												}
											);
										}
									});
									break;
								case "viewApplicantChecklist":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										'Applicant Checklist Details'
									);
									$("#myModal .modal-body").html(result.form);
									$(".modal-lg").css("width", "1060px");
									$.when(
										getFields.positionWithItem(),
										getFields.employee()
									).done( function () {
										$.each(my.data(),function(i,v){
											if(i === 'employee_id') {
												$("#myModal #" + i).val(my.data(i));
												$('#myModal #' + i).attr('style', 'pointer-events: none;');
											} else {
												$('#myModal #' + i).val(my.data(i)).change();
												$('#myModal #' + i).attr('style', 'pointer-events: none;');
											}
										});

										if (result.key == "viewApplicantChecklist") {
											$.post(
												commons.baseurl + "recruitment/ApplicantChecklists/getApplicantChecklistItems",
												{ id: data.id },
												function (result) {
													result = JSON.parse(result);
													// console.log(result);
													if (result.Code == "0") {
														if (result.Data.details.length > 0 ) {
															var guidelines = result.Data.details;
															var items = result.Data.items;
															var first_day = '';
															var policies = '';
															var administrative_procedure = '';
															var general_orientation = '';
															var position_information = '';
															var first_day_item = '';
															var policies_item = '';
															var administrative_procedure_item = '';
															var general_orientation_item = '';
															var position_information_item = '';

															// Get employee guidelines by category
															// static category: FIRST DAY, POLICIES, ADMINISTRATIVE PROCEDURES, POSITION INFORMATION
															$.each( guidelines, function(k,v) {
																// console.log(v.id);

																if(v.category === 'FIRST DAY') {
																	first_day += '<input type="hidden" value="0" name="first_day[' + v.id + ']">';
																	first_day += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="first_day[' + v.id + ']" id="first_day[' + v.id + ']" ' + v.is_checked + ' disabled>';
																	first_day += '<label for="first_day[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	first_day_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'POLICIES') {
																	policies += '<input type="hidden" value="0" name="policies[' + v.id + ']">';
																	policies += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="policies[' + v.id + ']" id="policies[' + v.id + ']" ' + v.is_checked + ' disabled>';
																	policies += '<label for="policies[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	policies_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'ADMINISTRATIVE PROCEDURES') {
																	administrative_procedure += '<input type="hidden" value="0" name="administrative_procedure[' + v.id + ']">';
																	administrative_procedure += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="administrative_procedure[' + v.id + ']" id="administrative_procedure[' + v.id + ']" ' + v.is_checked + ' disabled>';
																	administrative_procedure += '<label for="administrative_procedure[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	administrative_procedure_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'GENERAL ORIENTATION') {
																	general_orientation += '<input type="hidden" value="0" name="general_orientation[' + v.id + ']">';
																	general_orientation += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="general_orientation[' + v.id + ']" id="general_orientation[' + v.id + ']" ' + v.is_checked + ' disabled>';
																	general_orientation += '<label for="general_orientation[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	general_orientation_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
																if(v.category === 'POSITION INFORMATION') {
																	position_information += '<input type="hidden" value="0" name="position_information[' + v.id + ']">';
																	position_information += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="position_information[' + v.id + ']" id="position_information[' + v.id + ']" ' + v.is_checked + ' disabled>';
																	position_information += '<label for="position_information[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
																	position_information_item += '<div class="row"><div class="col-md-12 items" id="guideline_id_' + v.id + '"></div></div>';
																}
															});

															first_day += '<br>'; policies += '<br>'; administrative_procedure += '<br>'; general_orientation += '<br>'; position_information += '<br>';

															$("#myModal #first_day").html(first_day);
															$("#myModal #policies").html(policies);
															$("#myModal #administrative_procedure").html(administrative_procedure);
															$("#myModal #general_orientation").html(general_orientation);
															$("#myModal #position_information").html(position_information);
															$("#myModal #first_day_item").html(first_day_item);
															$("#myModal #policies_item").html(policies_item);
															$("#myModal #administrative_procedure_item").html(administrative_procedure_item);
															$("#myModal #general_orientation_item").html(general_orientation_item);
															$("#myModal #position_information_item").html(position_information_item);

															// display employee guideline items.
															// displays from left to right
															$.each( items, function(k, v) {
																$.each(guidelines, function(pk, pv) {
																	if(pv.id === v.guideline_id) {
																		var elem = "#guideline_id_" + pv.id;
																		$(elem).append('<div class="col-md-6">* ' + v.name + '</div>');
																	}
																});
															});
														}
													}
												}
											);
										}
									});
									break;
							}
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
	$(document).on('submit', '#addApplicantChecklist, #updateApplicantChecklist', function(e) {
		e.preventDefault();
		var form = $(this);
		content = "Are you sure you want to proceed?";
		if(form.attr('id') == "addApplicantChecklist") {
			content = "Are you sure you want to add applicant checklist?";
		}
		if(form.attr('id') == 'updateApplicantChecklist') {
			content = "Are you sure you want to update applicant checklist?";
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
														case 'addApplicantChecklist':
														case 'updateApplicantChecklist':
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

	
});

function field_reset() {
	$('#name').val("").change();
	$('#position_id').val("").change();
	$('#employee_id').val("").change();
}

function loadTable() {
	plus_url = "";

	var filter1 = $('#filter1').val();
	var name = $('#name').val();
	var position_id = $('#position_id').val();
	var supervisor_id = $('#employee_id').val();

	switch (filter1) {
		case 'name' :
			plus_url = "?Name=" + name;
			break;
		case 'position' :
			plus_url = "?Position=" + position_id;
			break;
		case 'supervisor' :
			plus_url = "?Supervisor=" + supervisor_id;
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
					'<button id="search-applicant-checklist" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
				)
					.html('<i class="material-icons">search</i>')
					.click(function () {
						if (!$("#search-applicant-checklist").is(":disabled")) {
							$("#search-applicant-checklist").attr("disabled", true);
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
				
				if	($("#search-applicant-checklist").length === 0) {
					$('.dataTables_filter').append($searchButton);
				}

		},
		drawCallback: function (settings) {
			$('#search-loader').remove();
			$('#search-applicant-checklist').removeAttr('disabled');
			$('#datatables button').removeAttr('disabled');
		},
		ajax: {
			url: commons.baseurl + "recruitment/ApplicantChecklists/fetchRows" + plus_url,
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

function addRowAfter(element) {
	// var cloneRow = element.closest("div").find("table tbody tr:last").clone();
	var cloneRow = element.closest("tr").clone();
	// var elemName = element
	//   .closest("tr")
	//   .find("td:eq(1)")
	//   .find(".form-control")
	//   .attr("name")
	//   .slice(0, -3);
	// var inc = $("textarea[name^='" + elemName + "']").length;
	var inc = element
		.closest("tr")
		.find("td:eq(1) .form-control")
		.attr("name")
		.slice(0, -3);
	lst_elem = $("textarea[name^='" + inc + "']:last");
	inc = lst_elem.attr("name");
	// var inc = element.closest("div").find("table tbody tr:last").find(".form-control").attr("name");
	$is = false;
	if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
		inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
		$is = true;
	} else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
	for (var i = 0; i < 7; i++) {
		var elementName = "";
		if ($is)
			elementName =
				cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
				"[" +
				inc +
				"]";
		else
			elementName =
				cloneRow.find(".form-control").eq(i).attr("name").slice(0, -4) +
				"[" +
				inc +
				"]";
		cloneRow
			.find(".form-control")
			.eq(i)
			.attr("name", elementName)
			.attr("id", elementName);
	}
	cloneRow.find(".form-control").val("");
	cloneRow.find("td:eq(0)").html("");
	cloneRow
		.find("td:last")
		.html(
			'<button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button>'
		);
	lst_elem.closest("tr").after(cloneRow);
	addDateMask();
	initValidation();
	autosize($("textarea.auto-growth"));
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
	cloneRow.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position","").css("display","inline-table");
	cloneRow.find(".form-control").val("").end();
	if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI")
		element.closest("div").find("table tbody").prepend(cloneRow);
	else element.closest("div").find("table tbody").append(cloneRow);
	addDateMask();
	initValidation();
	autosize($("textarea.auto-growth"));
}

function initValidation() {
	// $("input:not(.inputRequired)").each(function () {
	// 	$(this).rules("remove", "required");
	// });

	$("textarea:not(.inputRequired)").each(function () {
		$(this).rules("remove", "required");
	});

	$(".chkradio").each(function () {
		$(this).rules("add", {
			required: true
		});
	});

	// $("input.inputRequired, textarea.inputRequired").each(function () {
	// 	$(this).rules("add", {
	// 		required: function (element) {
	// 			return $(element).val().trim() == "";
	// 		},
	// 		normalizer: function (value) {
	// 			return $.trim(value);
	// 		},
	// 	});
	// });

	$("input.inputifyes").each(function () {
		$(this).rules("add", {
			required: function (element) {
				var num = parseInt($(element).attr("name").slice(-2));
				console.log($(
					"input[name='radio_input_" +
					(num < 10 ? "0" + num : num) +
					"']:checked"
				).val()+" - "+$(
					"input[name='radio_input_" +
					(num < 10 ? "0" + num : num) +
					"']:checked"
				).attr("name"));
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

function tmpNextBtn() {
	var steps = $("#addEmployees .steps").find("ul");
	var actions = $("#addEmployees .actions").find("ul").find("li:eq(1)");
	if (steps.find("li:eq(0)").hasClass("current")) {
		if (actions.find("button").length == 0) {
			actions.append(
				"<button type='button' id='btntempnext' class='btn btn-lg waves-effect' style='background-color: #009688; color: #fff;padding: 0.5em 1em;font-size: 14px;'>Next</button>"
			);
		} else {
			actions.find("button").css("display", "");
		}
		actions.find("a").css("display", "none");
	} else {
		actions.find("a").css("display", "");
		if (actions.find("button").length > 0) {
			actions.find("button").css("display", "none");
		}
	}
}
