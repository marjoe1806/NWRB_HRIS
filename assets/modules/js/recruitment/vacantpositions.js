$(function () {
	var base_url = commons.baseurl;
	addDateMask();
	// $("#datatables").DataTable();

	$.when(
		getFields.division()
	).done(function () {
		$("#division_id option:first").text("All");
		$.AdminBSB.select.activate();
	});
	$('.no_filled').text("Employee name");
	// $(document).on('change', '#filter1', function (e) {
	// 	var filter = $(this).val();
	// 	if (filter == "filled" || filter == "all"){
	// 		$('.no_filled').text("Employee name");
	// 	}else{
	// 		$('.no_filled').text("No. of Vacant Positions");
	// 	}
	// });

	$(document).on("show.bs.modal", "#myModal", function () {
		// addDateMask();
	});

	// ajax Forms
	$(document).on('click', '.search_btn', function (e) {
		var url = '';
		var filter1 = $('#filter1').val();
		var division = $('#division_id').val();

		if (filter1 == '') {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select a filter.",
			});
			return false;
		}

		// switch (filter1) {
		// 	case 'all' :
		// 		var data = {
		// 			'all': filter1
		// 		};
		// 		break;
		// 	case 'salary_grade' :
		// 		if (salary_grade == '') {
		// 			$.alert({
		// 				title: '<label class="text-danger">Failed</label>',
		// 				content: "Please select a salary grade.",
		// 			});
		// 			return false;
		// 		}

		// 		var data = {
		// 			'salary_grade': salary_grade,
		// 			'division' : division
		// 		}

		// 		break;
		// 	case 'name' :
		// 		if (name == '') {
		// 			$.alert({
		// 				title: '<label class="text-danger">Failed</label>',
		// 				content: "Please enter a name.",
		// 			});
		// 			return false;
		// 		}

		// 		var data = {
		// 			'name': name,
		// 			'division' : division
		// 		}
		// 		break;
		// }
		loadTable();
	});

	// ajax non-forms
	$(document).on(
		"click",
		".addJobOpeningsForm, .updateJobOpeningsForm, .viewJobOpeningsForm",
		function(e) {
			e.preventDefault();
			my = $(this);
			data = my.data();
			id = my.data("id");
			job_opening_id = id;
			url = my.attr("href");
			if(!my.find("button").is(":disabled")) {
				getFields.reloadModal();
				$.ajax({
					type: "POST",
					url: url,
					data: { id: id },
					dataType: "json",
					success: function (result) {
						page = my.attr("id");
						if(result.hasOwnProperty("key")) {
							status_key = result.key;
							switch(status_key) {
								case "addJobOpening":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html("Add New Job Opening");
									$("#myModal .modal-body").html(result.form);
									$.when(
										getFields.positionWithItem()
									).done( function () {
										$.each(my.data(),function(i,v) {
											if (i == "position_id") {
												$('#' + i).val(my.data(i));
												$('#' + i).attr('style', 'pointer-events: none;');
											}
										});
									});
									
									$(".expiration_date").daterangepicker({
					                  timePicker: false,
					                  autoApply: false,
					                  drops: "down",
					                  // minDate: moment().startOf("day"),
					                  // maxDate: moment().add(6, "months"),
					                  locale: { format: "YYYY-MM-DD",cancelLabel: 'Clear' },
					                  autoUpdateInput: false,
									  parentEl: "#myModal .modal-body" 

					                });
					                $('.expiration_date').on('apply.daterangepicker', function(ev, picker) {
					                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
					                });
					                $('.expiration_date').on('cancel.daterangepicker', function(ev, picker) {
					                    $(this).val('');
					                });
					                $(".publication_date").daterangepicker({
					                  singleDatePicker: true,
					                  timePicker: false,
					                  autoApply: false,
					                  drops: "down",
					                  // minDate: moment().startOf("day"),
					                  // maxDate: moment().add(6, "months"),
					                  locale: { format: "YYYY-MM-DD",cancelLabel: 'Clear' },
					                  autoUpdateInput: false,

					                });
					                $('.publication_date').on('apply.daterangepicker', function(ev, picker) {
					                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
					                });
					                $('.publication_date').on('cancel.daterangepicker', function(ev, picker) {
					                    $(this).val('');
					                });
									// addDateMask();
									break;

								case "updateJobOpening":
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										"Update Job Opening"
									);
									$("#myModal .modal-body").html(result.form);
									init_form_wizard();
									initValidation();
									break;

								case "viewJobOpening":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										'Job Opening Details <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success">Print</button>'
									);
									$("#myModal .modal-body").html(result.form);
									$(".modal-lg").css("width", "1060px");
									break;
							}
						}
					},
					error: function(result) {
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
	$(document).on('submit', '#addJobOpening, #updateJobOpening', function(e) {
		e.preventDefault();
		var form = $(this);

		// var flag = isValidDate($('#expiration_date').val());

		// if (!flag) {
		// 	return false;
		// }

		content = "Are you sure you want to proceed?";
		if(form.attr('id') == "addJobOpening") {
			content = "Are you sure you want to add job opening?";
		}
		if(form.attr('id') == 'updateJobOpening') {
			content = "Are you sure you want to update job opening?";
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
														case 'addJobOpening':
														case 'updateJobOpening':
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

	// $(document).on("blur", "#expiration_date", function () {
	// 	isValidDate($(this).val());
	// });

	$(document).on("click", "#btnAddQualification, #btnAddFile", function () {
		addrow($(this), 2);
	});

	$(document).on("click", ".deleteRow", function () {
		var totTableRows = $(this).closest("tbody").find("tr").length;
		if (totTableRows > 1) $(this).closest("tr").remove();
	});
});

function field_reset() {
	$('#salary_grade_id').val("").change();
	$('#name').val("").change();
}

function loadTable() {
	// plus_url = "";

	var filter1 = $('#filter1').val();
	var division = $('#division_id').val();

	if (filter1 == "filled" || filter1 == "all"){
		$('.no_filled').text("Employee name");
	}else{
		$('.no_filled').text("No. of Vacant Positions");
	}

	$("#datatables").DataTable().clear().destroy();
	table = $("#datatables").DataTable({
		processing: true,
		serverSide: true,
		stateSave: true, // presumably saves state for reloads -- entries
		bStateSave: true, // presumably saves state for reloads -- page number
		scroller: {
			displayBuffer: 20,
		},
		columnDefs: [
			{
				targets: [0],
				orderable: false,
			},

		    { className: 'text-center', targets: [1,2,3,4,5,6,7,8,9,10] },
		        
		],
		initComplete: function () {
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $(
				'<button id="search-vacant-position" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
			)
				.html('<i class="material-icons">search</i>')
				.click(function () {
					if (!$("#search-vacant-position").is(":disabled")) {
						$("#search-vacant-position").attr("disabled", true);
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

			if ($("#search-vacant-position").length === 0) {
				$('.dataTables_filter').append($searchButton);
			}

		},
		drawCallback: function (settings) {
			$('#search-loader').remove();
			$('#search-vacant-position').removeAttr('disabled');
			$('#datatables button').removeAttr('disabled');
		},
		ajax: {
			url: commons.baseurl + "recruitment/VacantPositions/fetchRows",
			type: 'POST',
			data: {division : division , filter1 : filter1}
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

function addrow(element, count) {
	var cloneRow = "";
	var inc = "";
	if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI") {
		cloneRow = element.closest("div").find("table tbody tr:first").clone();
		inc = element
			.closest("div")
			.find("table tbody tr:first")
			.find(".form-control")
			.attr("name");
	} else {
		cloneRow = element.closest("div").find("table tbody tr:last").clone();
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
	// addDateMask();
	initValidation();
	autosize($("textarea.auto-growth"));
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
	var date = false;
	var now = new Date();
	now.setHours(0,0,0,0);

	if (isNaN(timestamp) === false) {
		date = new Date(timestamp);
		flag = 1;
		if(date < now) {
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

function initValidation() {
	$("textarea:not(.inputRequired)").each(function () {
		$(this).rules("remove", "required");
	});

	$(".chkradio").each(function () {
		$(this).rules("add", {
			required: true
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
