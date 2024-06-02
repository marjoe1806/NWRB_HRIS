$(document).ready(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	// loadTable();
	$.when(getFields.payBasis3(),getFields.division()).done(function () {
		// $("#division_id option:first").text("All");
		$.AdminBSB.select.activate();
	});
	$(".datepicker").bootstrapMaterialDatePicker({
		format: "YYYY-MM-DD",
		clearButton: true,
		weekStart: 1,
		time: false,
	}); 
	$(document).on("show.bs.modal", "#myModal", function () {
		$(".datepicker").bootstrapMaterialDatePicker({
			format: "YYYY-MM-DD",
			clearButton: true,
			weekStart: 1,
			time: false,
		});
		$('[data-toggle="popover"]').popover();
		//$.AdminBSB.input.activate();
	});
	$(document).on("click", function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (
				!$(this).is(e.target) &&
				$(this).has(e.target).length === 0 &&
				$(".popover").has(e.target).length === 0
			) {
				(
					($(this).popover("hide").data("bs.popover") || {}).inState || {}
				).click = false; // fix for BS 3.3.6
			}
		});
	});
	$(document).on("click", "#printClearance", function (e) {
		e.preventDefault();
		printPrev(document.getElementById("clearance-div").innerHTML);
	});	
	$(document).on("keypress keyup keydown", "form #amount", function (e) {
		$("form #balance").val($(this).val());
	});
	$(document).on("change", "#pay_basis ", function (e) {
		pay_basis = $(this).val();
		$("#payroll_type").parent().parent().parent().parent().hide();
		if (pay_basis == "Permanent") {
			$("#payroll_type").parent().parent().parent().parent().show();
		}
		$.when(getFields.payrollperiodcutoff(pay_basis)).done(function () {
			$.AdminBSB.select.activate();
		});
	});
	const addCommas = (x) => {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	};
	//View Payroll Register
	$(document).on("click", "#viewPagibigContributionSummary", function (e) {
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		pay_basis = $(".search_entry #pay_basis").val();
		division_id = $(".search_entry #division_id").val();
		// week_period = $(".search_entry #week_period").val();
		//console.log(me.data())
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Pay Basis.",
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Period.",
			});

		} else if (division_id == "") {
		// }  else if (week_period == "" && pay_basis == "Permanent") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Division.",
			});
		} else {
			loader =
				'<div id="btn-loader" class="preloader pl-size-xl">' +
				'<div class="spinner-layer pl-blue">' +
				'<div class="circle-clipper left">' +
				'<div class="circle"></div>' +
				"</div>" +
				'<div class="circle-clipper right">' +
				'<div class="circle"></div>' +
				"</div>" +
				"</div>" +
				"</div>";

			modal_title = "PAG-IBIG REMITTANCE REPORT";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"remittance/PagibigContribution/viewPagibigContributionSummary",
				data: {
					payroll_period_id: payroll_period_id,
					pay_basis: pay_basis,
					division_id: division_id,
					week_period: "",
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewPagibigContributionSummary":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-body").html(result.form);
								$("#division_label").html(
									$("#division_id option:selected").text()
								);
								$("form input:not(:button),form input:not(:submit)").attr(
									"disabled",
									true
								);
								break;
						}
					}
				},
				error: function (result) {
					errorDialog();
				},
			});
		}
	});

	// $(document).on("change", "#pay_basis", function () {
	// 	if ($(this).val() == "Permanent") $(".dv_wk_period").css("display", "");
	// 	else $(".dv_wk_period").css("display", "none");
	// });

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
