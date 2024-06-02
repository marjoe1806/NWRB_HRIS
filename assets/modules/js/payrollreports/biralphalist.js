$(function () {
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
	$(document).on('click', '#addBIRform', function(e){
		e.preventDefault();
		my = $(this);
		url = my.attr("href");

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

	modal_title = "BIR Alphalist";
	$("#myModal .modal-title").html(modal_title);
	$("#myModal .modal-body").html(loader);
	$("#myModal").modal("show");
	$.ajax({
		type: "POST",
		url:
			commons.baseurl +
			"payrollreports/BIRalphalist/addBIRform",
		dataType: "json",
		success: function (result) {
			page = my.attr("id");
			if (result.hasOwnProperty("key")) {
				switch (result.key) {
					case "addBir":
						page = "";
						$("#myModal .modal-dialog").attr(
							"class",
							"modal-dialog modal-lg"
						);
						$("#myModal .modal-dialog").css("width", "98%");
						$("#myModal .modal-body").html(result.form);
						$("#division_label").html(
							$("#division_id option:selected").text()
						);
						break;
				}
				lengthmenu = [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"],
				  ];

				$('#module').dataTable({
					searching: true,
					destroy: true,
					aaSorting: [],
					columnDefs: [
					  {
						targets: [0],
						orderable: false,
					  },
					],
					lengthMenu: lengthmenu,
					
				});
			}
		},
		error: function (result) {
			errorDialog();
		},
	});

	})
	$(document).on("submit", "form", "#update1", function(e) {
		//console.log('test');
        e.preventDefault();
        var form = $(this);
        content = "Are you sure you want to proceed?";
        if (form.attr("id") == "addBir") {
            content = "Are you sure you want to submit?";
			url = form.attr("action");
			$.confirm({
				title: '<label class="text-warning">Confirm!</label>',
				content: content,
				type: "orange",
				buttons: {
					confirm: {
						btnClass: "btn-blue",
						action: function() {
							$(".jconfirm-buttons .btn-blue").prop('disabled', true);
							var progressbar = 	'<div class="progress"><div data-counter="0" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
												'0% Complete (success)' +
												'</div></div>';
						$.confirm({
							content: function() {
							total_data = $(".checkbox_table:checked").length;
							//console.log(total_data);
							if(total_data > 0){
								$('#myModal #progress-container').html(progressbar);
								$('#myModal').animate({ scrollTop: 0 }, 'fast');
								employee_ids = [];
								count = 0;
								var retry = 0;
								per_batch = 50;
								total_count = Math.ceil(total_data/per_batch); 
								$('#module').DataTable().$('input[type="checkbox"]:checked').each(function(k,v){
									employee_ids.push($(this).attr('data-id'));
									count++
									if(count == per_batch || total_data == count){
										data = {
											employee_ids: employee_ids,
											total_count: total_count
										}
									}
									
								});
							//console.log(data);
							var self = this;
							return $.ajax({
								type: "POST",
								url: url,
								data: data,
								dataType: "json",
								success: function(result) {
									//console.log(result);
									if (result.hasOwnProperty("key")) {
										
										if (result.Code == "0") {
											
											if (result.hasOwnProperty("key")) {
												
												switch (result.key) {
													case "addBir":
														
														self.setContent(result.Message);
														self.setTitle(
															'<label class="text-success">Success</label>'
														);
														$("#myModal .modal-body").html("");
														$("#myModal").modal("hide");
														//$('.search_entry #employee_id').val($('#myModal #employee_id').val());
														//reloadTable();
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
								error: function(result) {
									self.setContent(
										"There was an error in the connection. Please contact the administrator for updates."
									);
									self.setTitle('<label class="text-danger">Failed</label>');
								},
							});
							   
						  
							}
						  }
						})
						},
					},
					cancel: function() {},
				},
			});
        }

    });
	$(document).on("submit", "form", "#update", function(e) {
		//console.log('test');
        e.preventDefault();
        var form = $(this);
        content = "Are you sure you want to proceed?";
  
		if (form.attr("id") == "editBirAlpalist") {
            content = "Are you sure you want to Save?";
			url = form.attr("action");
			$.confirm({
				title: '<label class="text-warning">Confirm!</label>',
				content: content,
				type: "orange",
				buttons: {
					confirm: {
						btnClass: "btn-blue",
						action: function() {
							//Code here
							$.confirm({
								content: function() {
									var self = this;
									return $.ajax({
										type: "POST",
										url: url,
										data: form.serialize(),
										dataType: "json",
										success: function(result) {
											if (result.hasOwnProperty("key")) {
												if (result.Code == "0") {
													if (result.hasOwnProperty("key")) {
														switch (result.key) {
															case "addBir":
															case "editBirAlpalist":
																self.setContent(result.Message);
																self.setTitle(
																	'<label class="text-success">Success</label>'
																);
																$("#myModal .modal-body").html("");
																$("#myModal").modal("hide");
																//$('.search_entry #employee_id').val($('#myModal #employee_id').val());
																//reloadTable();
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
										error: function(result) {
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
					cancel: function() {},
				},
			});
        }

    });

	$(document).on('click', '#cancel', function(){
		$.confirm({
			title: 'Warning!',
			type: 'red',
			content: 'Are you sure to cancel it?',
			buttons: {
				cancel: function () {
					$('#myModal').modal('hide');
					}
				}
		});
	})
	$(document).on('keyup', '.number', function(event){
		//var value = $(this).val().trim();

		if(event.which >= 37 && event.which <= 40){
			event.preventDefault();
		   }
		 
		   $(this).val(function(index, value) {
			   value = value.replace(/,/g,''); // remove commas from existing input
			   return numberWithCommas(value); // add commas back in
		   });
	});

	//View Payroll Register
    $(document).on("click", "#viewPayrollRegisterSummary", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        date_year = $(".search_entry #year_select").val();
        // if (date_year == "") {
        //     $.alert({
        //         title: '<label class="text-danger">Failed</label>',
        //         content: "Please select year.",
        //     });
        //     return false;
        // }

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
                        url: commons.baseurl +
                            "payrollreports/BIRalphalist/fetchRows?dateYear=" +
                            date_year,
                        type: "POST",
                    },
                    columnDefs: [{
						"targets": [0],
                        orderable: false,
                    }, ],
                });

            },
            error: function(result) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates.",
                });
            },
        });
    });

	$(document).on("click", "#viewBirAlphalist", function (e) {

		e.preventDefault();
		my = $(this);
		url = my.attr("href");

		date_year =  $('#year_select').val();

		if (date_year == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Year.",
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

			modal_title = "Alphalist";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/BIRalphalist/viewBirAlphalistALL",
				data: {
					date_year : date_year
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewBirAlphalistALL":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-dialog").css("width", "98%");
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
	$(document).on('change', '.fields', function(){

		my = $(this);
		id =  my.attr("id");
		
		if(id == "total_taxable_compe_12j"){
			//console.log(id);
			value = Number($('#'+id).val().replace(',','')) + Number($('#total_taxable_compe_income_pre_empr_7j').val().replace(',',''));

			$('#total_taxable_compe_income_13').val(value);
		}
		if(id == "tax_due_14" || id == "tax_withheld_15a"|| id == "present_employer_15b"){

			value1 = Number($('#tax_due_14').val().replace(',',''));
			value2 = Number($('#tax_withheld_15a').val().replace(',',''));
			value3 = Number($('#present_employer_15b').val().replace(',',''));

			total = value1 - (value2 + value3);
			total2 = (value2 + value3) - value1;
			value_16a = 0;
			value_16b = 0;
				if(total > 0){
					$('#year_end_adjustment_16a').val(total);
					value_16a = total;
				}else{
					$('#year_end_adjustment_16a').val('0.00');
					value_16a = 0;
				}

				if(total2 > 0){
					$('#year_end_adjustment_16b').val(total2);
					value_16b = total2;
				}else{
					$('#year_end_adjustment_16b').val('0.00');
					value_16b = 0;
				}

				if(id == "present_employer_15b"){
					value_17 = 0;
					if(value_16a > 0){
						value_17 = value3 + value_16a
					}else if(value_16b > 0){
						value_17 = value3 + value_16b
					}

					$('#amount_tax_withheld_adjusted_17').val(value_17);
				}
		}
	});

	$(document).on("click", ".viewBirAlphalist", function (e) {

		e.preventDefault();
		my = $(this);
		url = my.attr("href");

		id =  my.data("id");
		if (id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Year.",
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

			modal_title = "Alphalist";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/BIRalphalist/viewBirAlphalist",
				data: {
					id : id
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewBirAlphalist":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-dialog").css("width", "98%");
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

	$(document).on("click", "#viewBirAlphalistdat", function (e) {

		e.preventDefault();
		my = $(this);
		url = my.attr("href");

		date_year =  $("#year_select").val();

		if (date_year == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Year.",
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

			modal_title = "Alphalist";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/BIRalphalist/viewBirAlphalistdat",
				data: {
					date_year : date_year
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewBirAlphalistdat":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-dialog").css("width", "98%");
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
	$(document).on("click", ".editBirAlpalistform", function (e) {

		e.preventDefault();
		my = $(this);
		url = my.attr("href");

		id =  my.data("id");

		if (date_year == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Please select Year.",
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

			modal_title = "BIR Alphalist";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/BIRalphalist/editBirAlpalistform",
				data: {
					id : id
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "editBirAlpalist":
								page = "";
								$("#myModal .modal-dialog").attr(
									"class",
									"modal-dialog modal-lg"
								);
								$("#myModal .modal-dialog").css("width", "98%");
								$("#myModal .modal-body").html(result.form);
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

    $(document).on("click", "#btnXls", function() {
		date_year = $(this).data('date');
		//console.log(date_year);
        $('#table1').tableExport({
			type:"excel",
			fileName:'Alphalist-'+date_year
		});
    });

	$(document).on("click", "#btnXls2", function() {
		date_year = $(this).data('date');
		//console.log(date_year);
        $('#table1').tableExport({
			type:"txt",
			fileName:'00079563600001231'+date_year+'1604C' 
		});
    });
	// $(document).on("change", "#pay_basis", function () {
	// 	if ($(this).val() == "Permanent") $(".dv_wk_period").css("display", "");
	// 	else $(".dv_wk_period").css("display", "none");
	// });

	// function PrintElem(elem) {
	// 	var mywindow = window.open("", "PRINT", "height=400,width=600");
	// 	mywindow.document.write(
	// 		"<html moznomarginboxes mozdisallowselectionprint><head>"
	// 	);
	// 	mywindow.document.write("</head><body >");
	// 	mywindow.document.write(document.getElementById(elem).innerHTML);
	// 	mywindow.document.write("</body></html>");

	// 	mywindow.document.close(); // necessary for IE >= 10
	// 	mywindow.focus(); // necessary for IE >= 10*/

	// 	mywindow.print();
	// 	mywindow.close();

	// 	return true;
	// }

       
	let currentYear = new Date().getFullYear();    
	let earliestYear = 2019;     
	while (currentYear >= earliestYear) {      
	   $('#year_select').append(
		'<option value="'+currentYear+'">'+currentYear+'</option>'
	   );
	   currentYear -= 1;   
	}
});

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
