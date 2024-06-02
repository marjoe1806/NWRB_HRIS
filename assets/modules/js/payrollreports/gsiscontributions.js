$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;

	// $.when(
	// 	getFields.payrollperiod()
	// ).done(function () {
	// 	$.AdminBSB.select.activate();
	// })

	$.when(
		getFields.payBasisWithAll()
	).done(function () {
		$.AdminBSB.select.activate();
		$('#payroll_period_id').change();
	})

	$(document).on('change', '#pay_basis', function () {
		$("#period_covered_from").val('');
		$("#period_covered_to").val('');
		cutoff = $(this).val();
		$.when(
			getFields.payrollperiodcutoff(cutoff)
		).done(function () {
			$.AdminBSB.select.activate();
			$('#payroll_period_id').change();
		})
	});

	$(document).on('click', '#printSummaryButton', function (e) {
		PrintElem('printable-table-holder-summary');
	})

	$(document).on('change', '#payroll_period_id', function () {
		payroll_period = $(this).val();
		plus_url2 = '?Id=' + payroll_period
		url2 = commons.baseurl + "fieldmanagement/PeriodSettings/getPeriodSettingsById" + plus_url2;
		var inclusive_dates = function () {
			var temp;
			$.ajax({
				url: url2,
				async: false,
				dataType: "json",
				success: function (result) {
					temp = result;
				}
			});
			return temp;
		}();
		from = new Date(inclusive_dates.Data.details[0].start_date);
		to = new Date(inclusive_dates.Data.details[0].end_date);
		payroll_period2 = inclusive_dates.Data.details[0].payroll_period;
		let options = {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
		};
		$("#period_covered_from").val(from.toLocaleString('en-us', options));
		$("#period_covered_to").val(to.toLocaleString('en-us', options));
		$("#payroll_period_hidden").val(payroll_period2);
	});

	$(document).on('click', '#summarizeAllGSISContributions', function (e) {
		e.preventDefault();
		my = $(this)
		url = my.attr('href');
		pay_basis = $('#pay_basis').val()
		payroll_period_id = $('#payroll_period_id').val()
		if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose pay basis.'
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please choose payroll period.'
			});
		} else {
			plus_url = '?PayBasis=' + pay_basis + '&PayrollPeriodId=' + payroll_period_id + '&PayrollPeriod=' + $("#payroll_period_hidden").val();
			$.ajax({
				type: "POST",
				// url: commons.baseurl + "payrollreports/GSISContributions/attendanceSummaryContainer",
				url: commons.baseurl + "payrollreports/GSISContributions/fetchRowsSummaryAll" + plus_url,
				dataType: "json",
				success: function (result) {
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case 'viewGSISContributionsAll':
								if(result.success == true) {
									$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
									$('#myModal .modal-dialog').attr('style', 'width: 95%');
									modal_title = "Monthly GSIS Contributions Summary"
									$('#myModal .modal-title').html(modal_title);
									$('#myModal .modal-body').html(result.table);
									$('#myModal .summary_period_label').html('<b>From ' + $('#period_covered_from').val() + ' to ' + $('#period_covered_to').val() + '</b>');
									$('#myModal').modal('show');
								} else {
									$.alert({
										title: '<label class="text-danger">Failed</label>',
										content: 'No data found for this payroll period.'
									});
								}
								break;
						}
					}
				},
				error: function (result) {
					$.alert({
						title: '<label class="text-danger">Failed</label>',
						content: 'There was an error in the connection. Please contact the administrator for updates.'
					});
				}
			});
		}

	})

	function PrintElem(elem) {
		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
		// html, body { height: 100%; }
		mywindow.document.write('<style> * { font-family: arial; text-align: center } .header { font-size: 22px } .header-2 { font-size: 16px } .text-left { text-align: left } .text-right { text-align: right } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #ccc} @media print { .no-print, .no-print * { display: none !important; } } </style>')
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close();
		mywindow.focus();

		mywindow.print();
		mywindow.close();

		return true;
	}
})
