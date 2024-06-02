$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;

	loadTable();

	$(document).on("show.bs.modal", "#myModal", function () {
		var vholidays = [];
		$.when(getFields.holidays()).done(function () {
			$(".timepicker").inputmask("hh:mm:ss", {
				placeholder: "__:__:__ _m",
				alias: "time24",
				hourFormat: "24",
			});
			$.AdminBSB.input.activate();
			$.AdminBSB.select.activate();
		});
	});
	$(document).on("click", "#printOffsetting", function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	});

	$(document).on("click", "#changeAttachment", function (e) {
		e.preventDefault();
		$("#updateFileButtons").hide();
		$("#hiddenFileInput").show();
	});

	$(document).on("click", "#cancelChange", function (e) {
		e.preventDefault();
		$("#updateFileButtons").show();
		$("#hiddenFileInput").hide();
	});

	$(document).on("change", "#division_id ", function (e) {
		division_id = $(this).val();
		card = $(this).closest(".card");
		select =
			'<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required><option value="">Loading...</option></select>';
		card.find(".employee_select").html(select);
		url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
		data = { division_id: division_id };
		$.ajax({
			url: url2,
			data: data,
			type: "POST",
			dataType: "JSON",
			success: function (res) {
				temp = res;
				select =
					'<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required>';
				options = '<option value=""></option>';
				if (temp.Code == "0") {
					$.each(temp.Data.details, function (i, v) {
						options +=
							'<option value="' +
							v.id +
							'">' +
							v.employee_id_number +
							" - " +
							v.first_name +
							" " +
							v.middle_name +
							" " +
							v.last_name +
							"</option>";
					});
					//$('.selectpicker').selectpicker('refresh')
				}
				select += options;
				select += "</select>";
				card.find(".employee_select").html(select);
				card.find("#employee_id").val(employee_id).change();
				$.AdminBSB.select.activate();
			},
		});
	});

	$(document).on("change", "#employee_id", function (e) {
		if ($(this).val() !== "") {
			$.post(
				"../Offsetting/getAvailableHours",
				{ id: $(this).val() },
				function (result) {
					var jsonData = JSON.parse(result);
					if (jsonData.Code === "0") {
						$("#hours_available").val(jsonData.Data.available);
						$("#hours_pending").val(jsonData.Data.pending);
					}
				}
			);
		}
	});

	//Confirms
	$(document).on(
		"click",
		".activateOffsetting,.deactivateOffsetting",
		function (e) {
			e.preventDefault();
			me = $(this);
			url = me.attr("href");
			var id = me.attr("data-id");
			content = "Are you sure you want to proceed?";
			if (me.hasClass("activateOffsetting")) {
				content = "Are you sure you want to activate selected report?";
			} else if (me.hasClass("deactivateSubOffsetting")) {
				content = "Are you sure you want to deactivate selected sub report?";
			}
			data = {
				id: id,
			};
			$.confirm({
				title: '<label class="text-warning">Confirm!</label>',
				content: content,
				type: "orange",
				buttons: {
					confirm: {
						btnClass: "btn-blue",
						action: function () {
							//Code here
							$.confirm({
								content: function () {
									var self = this;
									return $.ajax({
										type: "POST",
										url: url,
										data: {
											id: id,
										},
										dataType: "json",
										success: function (result) {
											if (result.Code == "0") {
												if (result.hasOwnProperty("key")) {
													switch (result.key) {
														case "activateOffsetting":
														case "deactivateOffsetting":
															self.setContent(result.Message);
															self.setTitle(
																'<label class="text-success">Success</label>'
															);
															loadTable();
															break;
													}
												}
											} else {
												self.setContent(result.Message);
												self.setTitle(
													'<label class="text-danger">Failed</label>'
												);
											}
										},
										error: function (result) {
											self.setContent(
												"There was an error in the connection. Please contact the administrator for updates."
											);
											self.setTitle(
												'<label class="text-danger">Failed</label>'
											);
										},
									});
								},
							});
						},
					},
					cancel: function () {},
				},
			});
		}
	);

	//Ajax non-forms
	$(document).on(
		"click",
		"#addOffsettingForm,.updateOffsettingForm,.viewOffsettingForm",
		function (e) {
			e.preventDefault();
			me = $(this);
			id = me.attr("data-id");
			offset_id = $(this).attr("data-offsetting_id");
			url = me.attr("href");
			$.ajax({
				type: "POST",
				url: url,
				data: {
					id: id,
					offsetting_id: offset_id
				},
				dataType: "json",
				success: function (result) {
					page = me.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "addOffsetting":
								$.when(getFields.division(), getFields.document()).done(
									function () {
										$.AdminBSB.select.activate();
									}
								);
								
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-title").html("Add New Offsetting");
								$("#myModal .modal-body").html(result.form);
								$("#myModal").modal("show");
								break;
							case "generateOffsetting":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-md"
								);
								$("#myModal .modal-title").html("Employee report");
								$("#myModal .modal-body").html(result.form);
								$("#myModal").modal("show");
								break;
							case "viewOffsetting":
							case "updateOffsetting":


								
								$.when(getFields.division(), getFields.document()).done(
									function () {
										$(".division_id").selectpicker(
											"val",
											me.data("division_id")
										);
										$(".division_id").val(me.data("division_id")).change();
										$(".remarks").selectpicker("val", me.data("remarks"));
										$(".remarks").val(me.data("remarks")).change();
										$("#date_requested").val(me.data("date_requested"));
										employee_id = me.data("employee_id");
										transaction_date = me.data("transaction_date");
										$(".employee_id_2").selectpicker(
											"val",
											me.data("checked_by")
										);
										$(".type_id").selectpicker("val", me.data("type_id"));
										$.AdminBSB.select.activate();
									}
								);
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-title").html("Offsetting Details");
								$("#myModal .modal-body").html(result.form);

								$("#myModal").modal("show");
								$.each(me.data(), function (i, v) {
									if (
										i != "division_id" &&
										i != "employee_id" &&
										i != "transaction_date"
									)
										$("." + i)
											.val(me.data(i))
											.change();
								});

								$("#viewAttachment").attr(
									"href",
									commons.baseurl +
										"assets/uploads/Offsetting/" +
										me.data("employee_id") +
										"/" +
										me.data("filename")
								);
								$("#downloadAttachment").attr(
									"href",
									commons.baseurl +
										"assets/uploads/Offsetting/" +
										me.data("employee_id") +
										"/" +
										me.data("filename")
								);
								if (result.key == "viewOffsetting") {
									$("#changeAttachment").hide();
									$("form")
										.find("input, textarea, select")
										.attr("disabled", "disabled");
									$("form").find("#cancelUpdateForm").removeAttr("disabled");
								}
								break;
						}

						$.validator.addMethod("lessEqualTo", function (
							value,
							element,
							param
						) {
							var otherElement = $(param);
							return parseInt(value) <= parseInt(otherElement.val());
						});
						$.validator.messages.lessEqualTo =
							"Value must be less than or equal to No. of OT Available Hours.";

						$("#" + result.key).validate({
							rules: {
								number_of_hrs: {
									number: true,
									lessEqualTo: "#hours_available",
									min: 1,
									max: 8,
								},
								".required": {
									required: true,
								},
								".email": {
									required: true,
									email: true,
								},
							},
							highlight: function (input) {
								$(input).parents(".form-line").addClass("error");
							},
							unhighlight: function (input) {
								$(input).parents(".form-line").removeClass("error");
							},
							errorPlacement: function (error, element) {
								$(element).parents(".form-group").append(error);
							},
						});
						initValidation();
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
	);
	//Ajax Forms
	$(document).on("submit", "#addOffsetting,#updateOffsetting", function (e) {
		e.preventDefault();
		var form = $(this);
		
		content = "Are you sure you want to proceed?";
		if (form.attr("id") == "addOffsetting") {
			content = "Are you sure you want to add report?";
		}
		if (form.attr("id") == "updateOffsetting") {
			content = "Are you sure you want to update report?";
		}
		url = form.attr("action");
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: content,
			type: "orange",
			buttons: {
				confirm: {
					btnClass: "btn-blue",
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
														case "addOffsetting":
														case "updateOffsetting":
															self.setContent(result.Message);
															self.setTitle(
																'<label class="text-success">Success</label>'
															);
															$("#myModal .modal-body").html("");
															$("#myModal").modal("hide");
															loadTable();
															break;
													}
												}
											} else {
												self.setContent(result.Message);
												self.setTitle(
													'<label class="text-danger">Failed</label>'
												);
											}
										}
									},
									error: function (result) {
										self.setContent(
											"There was an error in the connection. Please contact the administrator for updates."
										);
										self.setTitle('<label class="text-danger">Failed</label>');
									},
								});
							},
						});
					},
				},
				cancel: function () {},
			},
		});
	});

	$(document).on(
		"click",
		".certifyPendingOffset, .recommendPendingOffset, .approvedPendingOffset, .rejectPendingOffset",
		function (e) {
			e.preventDefault();
			url = $(this).attr("href");
			id = $(this).attr("data-id");
			empid = $(this).attr("data-empid");
			nohrs = $(this).attr("data-nohrs");
			offset_id = $(this).attr("data-offsetting_id");
			console.log(offset_id)
			$.confirm({
				title: '<label class="text-warning">Confirm!</label>',
				content: "Are you sure want to submit this request?",
				type: "orange",
				buttons: {
					confirm: {
						btnClass: "btn-blue",
						action: function () {
							//Code here
							$.confirm({
								content: function () {
									var self = this;
									return $.ajax({
										type: "POST",
										url: url,
										data: { id: id, employee_id: empid, nohrs: nohrs, offsetting_id: offset_id},
										dataType: "json",
										success: function (result) {
											if (result.Code == "0") {
												self.setContent(result.Message);
												self.setTitle(
													'<label class="text-success">Success</label>'
												);
												loadTable();
											} else {
												self.setContent(result.Message);
												self.setTitle(
													'<label class="text-danger">Failed</label>'
												);
											}
										},
										error: function (result) {
											self.setContent(
												"There was an error in the connection. Please contact the administrator for updates."
											);
											self.setTitle(
												'<label class="text-danger">Failed</label>'
											);
										},
									});
								},
							});
						},
					},
					cancel: function () {},
				},
			});
		}
	);

	$(document).on("click", "#btnsearch", function (e) {
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
		leave_type = $("#leave_type").val();
		status = $("#status").val();
		if (leave_type == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select leave type.",
			});
			return false;
		}
		if (status == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select status.",
			});
			return false;
		}
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			success: function (result) {
				$("#table-holder").html(result.table);
				table = $("#datatables").DataTable({
					processing: true,
					serverSide: true,
					order: [],
					ajax: {
						url:
							commons.baseurl +
							"timekeeping/Offsetting/fetchRows?Status=" +
							status,
						type: "POST",
					},
					columnDefs: [
						{
							"targets": [0],
							orderable: false,
						},
					],
				});
				button =
					'<a id="viewLeaveCreditsSummary">' +
					'<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
					'<i class="material-icons">people</i> Balance of Leave Credits Summary' +
					"</button>" +
					"</a>";
				$("#table-holder .button-holder").html(button);
			},
			error: function (result) {
				errorDialog();
			},
		});
	});

	function loadTable() {
		var url = window.location.href;
		$.ajax({
			url: url,
			dataType: "json",
			success: function (result) {
				$("#table-holder").html(result.table);
				table = $("#datatables").DataTable({
					processing: true,
					serverSide: true,
					stateSave: true, // presumably saves state for reloads -- entries
					bStateSave: true, // presumably saves state for reloads -- page number
					order: [],
					ajax: {
						url: commons.baseurl + "timekeeping/Offsetting/fetchRows",
						type: "POST",
					},
					columnDefs: [{ orderable: false }],
				});
			},
		});
	}

	function PrintElem(elem) {
		var mywindow = window.open("", "PRINT", "height=400,width=600");
		mywindow.document.write(
			"<html moznomarginboxes mozdisallowselectionprint><head>"
		);
		mywindow.document.write("</head><body >");
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write("</body></html>");

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();

		return true;
	}
});

function initValidation() {
	$(".inputFile").each(function () {
		$(this).rules("add", {
			extension: "jpg,jpeg,pdf,png",
			messages: {
				extension:
					"Please enter a value with a valid extension. (.jpg, .jpeg, and .pdf)",
			},
		});
	});
}

$(document).on("keypress", ".input-integer", function (e) {
	var regex = new RegExp("^[0-9 ]*$");
	var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (regex.test(str)) return true;

	e.preventDefault();
	return false;
});
