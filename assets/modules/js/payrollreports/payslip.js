$(function() {
	var page = "";
	base_url = commons.base_url;
	var table;
	// loadTable();
	$.when(getFields.payBasis3(), getFields.division()).done(function() {
		$("#division_id option:first").text("All");
		$("#division_id option:first").val("");
		$("#pay_basis")
			.val("Permanent-Casual")
			.change();
		$.AdminBSB.select.activate();
		$("#pay_basis").selectpicker("refresh");
	});

	$(".datepicker").bootstrapMaterialDatePicker({
		format: "YYYY-MM-DD",
		clearButton: true,
		weekStart: 1,
		time: false
	});

	$(document).on("show.bs.modal", "#myModal", function() {
		$(".datepicker").bootstrapMaterialDatePicker({
			format: "YYYY-MM-DD",
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$('[data-toggle="popover"]').popover();
	});

	$(document).on("click", function(e) {
		$('[data-toggle="popover"],[data-original-title]').each(function() {
			if (
				!$(this).is(e.target) &&
				$(this).has(e.target).length === 0 &&
				$(".popover").has(e.target).length === 0
			) {
				(
					(
						$(this)
							.popover("hide")
							.data("bs.popover") || {}
					).inState || {}
				).click = false;
			}
		});
	});
	$(document).on("click", "#printClearance", function(e) {
		e.preventDefault();
		printPrev(document.getElementById("clearance-div").innerHTML);
	});

	$(document).on("keypress keyup keydown", "form #amount", function(e) {
		$("form #balance").val($(this).val());
	});

	$(document).on("change", "#pay_basis ", function(e) {
		pay_basis = $(this).val();
		$.when(getFields.payrollperiodcutoff(pay_basis)).done(function() {
			$.AdminBSB.select.activate();
		});
	});

	const addCommas = x => {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	};

	$(document).on("click", ".viewPayslipForm", function(e) {
		e.preventDefault();
		my = $(this);
		id = my.attr("data-id");
		url = my.attr("href");
		employee_id = my.data("employee_id");
		year = $(".search_entry #search_year").val();
		month = $(".search_entry #month").val();
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		pay_basis = $(".search_entry #pay_basis").val();
		cutoff_id = $(".search_entry #cutoff_id").val();
		payroll = my.data();
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				payroll_period_id: payroll_period_id,
				employee_id: employee_id,
				cutoff_id: cutoff_id,
				pay_basis: pay_basis,
				key: "viewPayslip"
			},
			dataType: "json",
			success: function(result) {
				page = my.attr("id");
				if (result.hasOwnProperty("key")) {
					if (result.key === "viewPayslip") {
						page = "";
						$("#myModal .modal-dialog").attr("class", "modal-dialog modal-lg");
						modal_title = my.data("employee_id_number") + " - " + my.data("last_name") + ", " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("extension");
						$("#myModal .modal-title").html(modal_title);
						$("#myModal .modal-body").html(result.form);
						$("#myModal").modal("show");
						$.each(my.data(), function(i, v) {
							$("." + i).val(addCommas(v));
						});
						$("form input:not(:button),form input:not(:submit)").attr(
							"disabled",
							true
						);
					}
					$("#" + result.key).validate({
						rules: {
							".required": { required: true },
							".email": { required: true, email: true }
						},
						highlight: function(input) {
							$(input)
								.parents(".form-line")
								.addClass("error");
						},
						unhighlight: function(input) {
							$(input)
								.parents(".form-line")
								.removeClass("error");
						},
						errorPlacement: function(error, element) {
							$(element)
								.parents(".form-group")
								.append(error);
						}
					});
				}
			},
			error: function(result) {
				errorDialog();
			}
		});
	});

	$(document).on("click", "#viewPayslipSummary", function(e) {
		e.preventDefault();
		pay_basis = $(".search_entry #pay_basis").val();
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		location_id = $(".search_entry #location_id").val();
		payroll_grouping_id = $(".search_entry #payroll_grouping_id").val();
		division_id = $(".search_entry #division_id").val();
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Pay Basis."
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Period."
			});
		// } else if (division_id == "") {
		// 	$.alert({
		// 		title: '<label class="text-danger">Failed</label>',
		// 		content: "Please select Department."
		// 	});
		} else if (location_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Location."
			});
		} else {
			getFields.employeeModal(
				pay_basis,
				location_id,
				division_id,
				null,
				1,
				null,
				payroll_grouping_id
			);
		}
	});

	//View Payroll Register
	$(document).on("click", "#submitUserCheckList", function(e) {
		e.preventDefault();
		pay_basis = $(".search_entry #pay_basis").val();
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		cutoff_id = $(".search_entry #cutoff_id").val();
		myBtn = $(this);
		$(this).attr("disabled", "disabled");
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: "Are you sure you want to process payslip?",
			type: "orange",
			buttons: {
				confirm: {
					btnClass: "btn-blue",
					action: function() {
						var progressbar =
							'<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
							"0% Complete (success)" +
							"</div></div>";
						total_data = $(".checkbox_table:checked").length;
						if (total_data > 0) {
							$("#myModal #progress-container").html(progressbar);
							$.ajax({
								type: "POST",
								url:
									commons.baseurl + "payrollreports/Payslip/payslipContainer",
								dataType: "json",
								success: function(result) {
									if (result.hasOwnProperty("key")) {
										if (result.key === "payslipContainer") {
											$("#myModal .modal-dialog").attr(
												"class",
												"modal-dialog modal-lg"
											);
											modal_title = "Payslip Summary";
											$("#myModal .modal-title").html(modal_title);
											$("#myModal .modal-body").append(result.form);
											$("#module")
												.DataTable()
												.$('input[type="checkbox"]:checked')
												.each(function(k, v) {
													employee_id = $(this).attr("data-id");
													queue = getEmployeePayslip(
														payroll_period_id,
														employee_id,
														k + 1
													);
													if (queue) {
														percentage =
															100 - ((total_data - (k + 1)) / total_data) * 100;
														$(".progress-bar")
															.attr("aria-valuenow", percentage)
															.css("width", percentage + "%")
															.html(
																percentage.toFixed() + "% Complete (success)"
															);
														if (total_data == k + 1)
															$("#employee-checklists").remove();
													} else {
														errorDialog();
														$("#myModal").modal("hide");
														return false;
													}
												});
										}
									}
								},
								error: function(result) {
									errorDialog();
								}
							});
						} else {
							$.alert({
								title: '<label class="text-danger">Failed</label>',
								content: "Please select Employees."
							});
							myBtn.removeAttr("disabled");
						}
					}
				},
				cancel: function() {
					myBtn.removeAttr("disabled");
				}
			}
		});
	});

	//Ajax Forms
	$(document).on("click", "#computePayroll", function(e) {
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
		pay_basis = $(".search_entry #pay_basis").val();
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		location_id = $(".search_entry #location_id").val();
		division_id = $(".search_entry #division_id").val();
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select pay basis."
			});
			return false;
		}
		if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select payroll period."
			});
			return false;
		}
		// if (division_id == "") {
		// 	$.alert({
		// 		title: '<label class="text-danger">Failed</label>',
		// 		content: "Please select Department."
		// 	});
		// 	return false;
		// }
		if (location_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select location."
			});
			return false;
		}
		if(division_id != ""){
			var plus_url =
			"?PayBasis=" +
			pay_basis +
			"&PayrollPeriod=" +
			payroll_period_id +
			"&DivisionId=" +
			division_id;
		}else{
			var plus_url =
				"?PayBasis=" +
				pay_basis +
				"&PayrollPeriod=" +
				payroll_period_id;
		}
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			success: function(result) {
				$("#table-holder").html(result.table);
				table = $("#datatables").DataTable({
					processing: true,
					serverSide: true,
					order: [],
					ajax: {
						url:
							commons.baseurl + "payrollreports/Payslip/fetchRows" + plus_url,
						type: "POST"
					},
					columnDefs: [{ 
						"targets": [0],
						orderable: false 
					}]
				});
				button =
					'<a id="viewPayslipSummary" href="' +
					commons.baseurl +
					'payrollreports/Payslip/viewPayslipSummary">' +
					'<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
					'<i class="material-icons">people</i> Payslip Summary' +
					"</button>" +
					"</a>";
				$("#table-holder .button-holder").html(button);
			},
			error: function(result) {
				errorDialog();
			}
		});
	});

	function getEmployeePayslip(payroll_period_id, employee_id, queue) {
		url2 = commons.baseurl + "payrollreports/Payslip/viewPayslip/";
		$.ajax({
			type: "POST",
			url: url2,
			async: false,
			data: {
				payroll_period_id: payroll_period_id,
				employee_id: employee_id,
				key: "viewPayslipSummary"
			},
			dataType: "json",
			success: function(result) {
				if (result.hasOwnProperty("key")) {
					if (result.key === "viewPayslipSummary") {
						$("#myModal #certificate-container").append(result.form);
						queue += 1;
					}
				}
			},
			error: function(result) {
				queue = false;
			}
		});
		return queue;
	}

	function loadTable() {
		plus_url = "";
		table = $("#datatables").DataTable({
			processing: true,
			serverSide: true,
			stateSave: true, // presumably saves state for reloads -- entries
			bStateSave: true, // presumably saves state for reloads -- page number
			order: [],
			ajax: {
				url: commons.baseurl + "payrollreports/Payslip/fetchRows" + plus_url,
				type: "POST"
			},
			columnDefs: [{ orderable: false }]
		});
	}

	function reloadTable() {
		employee_id = $(".search_entry #employee_id").val();
		plus_url = "?EmployeeId=" + employee_id;
		if (employee_id == "") {
			return false;
		}
		$("#searchEmployeePayroll").click();
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
