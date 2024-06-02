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
	
	$(document).on("click", "#addColumn1", function (e) {
		var pay_basis = $(this).data('pay_basis');
		if(pay_basis == 'Contract of Service'){
			$('#deduction_header').attr('colspan',7);
		}else{
			$('#deduction_header').attr('colspan',13);
		}
		$('.tmp_col1').show();
		$('.tmp_col1').css("background-color", "lightblue");
		$('#printClearance').attr('disabled','disabled')
		$('#printClearance').hide();
		$('#addColumn1').hide();
		$('#addColumn2').show();
		$('#btnSave').show();
		$('#deleteColumn1').show();
		$('#deleteColumn1').removeAttr('disabled');
		$('.tmp_col1 ').attr('contenteditable','true');  
		$("#header_1").blur(function(){
			//code code and more code
		 }).blur();
	});


	$(document).on("click", "#addColumn2", function (e) {
		var pay_basis = $(this).data('pay_basis');
		if(pay_basis == 'Contract of Service'){
			$('#deduction_header').attr('colspan',8);
		}else{
			$('#deduction_header').attr('colspan',14);
		}
		$('.tmp_col2').show();
		$('.tmp_col2 ').attr('contenteditable','true'); 
		$('.tmp_col2').css("background-color", "lightblue");
		$('#addColumn1').hide();
		$('#addColumn2').hide();
		//$('#removeColumns').show();
		$('#deleteColumn2').show();
		$('#removeColumns').removeAttr('disabled')
		$("#header_2").blur(function(){
			//code code and more code
		 }).blur();
	});
	$(document).on("click", "#removeColumns", function (e) {
		myBtn = $(this)
        $(this).attr('disabled','disabled')
		var pay_basis = $(this).data('pay_basis');
		$.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: 'Are you sure you want to remove these two additional columns?',
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
						if(pay_basis == 'Contract of Service'){
							$('#deduction_header').attr('colspan',6);
						}else{
							$('#deduction_header').attr('colspan',12);
						}
						$('.tmp_col1').hide();
						$('.tmp_col2').hide();
						$('#addColumn1').show();
						$('#addColumn2').hide();
						$('#removeColumns').hide();                        
						$('#btnSave').hide();  
						$('#printClearance').show();                 
						$('#printClearance').removeAttr('disabled');             
                    }

                },
                cancel: function () {
                    myBtn.removeAttr('disabled')
                }
            }
        });
	});

	const validateInput = (value) => {
		return /^(0|[1-9]\d*)(\.\d+)?$/.test(value);
	}
	let forUpdateDataHeader = [];
	let forUpdateDataCol1 = [];
	let forUpdateDataCol2 = [];
	$(document).on("blur", ".editableHeader", function (e) {
		forUpdateDataHeader = [];
		let obj = {
			period_id: $(this).data('period'),
			division_id: $(this).data('division'),
			pay_basis: $(this).data('pay_basis'),
			value: $(this).text().trim(),
			type: $(this).data('type')
		}
		forUpdateDataHeader.push(obj);
	});

	$(document).on("blur", ".tmp_col1.editable", function (e) {
		
		id = $(this).data('id');
		grand_total = $(this).data('value');
		value =  Number($('#tmp_col1_'+id).text().trim().replace(',', ''));
		col1_value_field = $('#'+id+'col1_value_field').val();
		if(Number(value) != col1_value_field){

		cut_off1 = $('#'+id+'_cut_off1').text().replace(',', '');
		cut_off2 = $('#'+id+'_cut_off2').text().replace(',', '');
		cut_off1_gross = $('#'+id+'_cut_off1_gross').text().replace(',', '');
		cut_off2_gross = $('#'+id+'_cut_off2_gross').text().replace(',', '');
		cut_off1_net = $('#'+id+'_cut_off1_net').text().replace(',', '');
		cut_off2_net = $('#'+id+'_cut_off2_net').text().replace(',', '');

		basic_pay = Number($('#'+id+'_basic_pay').text().replace(',', ''));
		pera_amt = Number($('#'+id+'_pera_amt').text().replace(',', ''));
		total_deduct = Number($('#'+id+'_total_deduct').text().replace(',', ''));

		cut_off_1st = (Number($('#'+id+'_cut_off_1st').val())) + (Number(col1_value_field) / 2);
		cut_off_1st_gross = Number($('#'+id+'_cut_off_1st_gross').val()) + (Number(col1_value_field) / 2);
		cut_off_2nd = Number($('#'+id+'_cut_off_2nd').val()) + (Number(col1_value_field) / 2);
		cut_off_2nd_gross = Number($('#'+id+'_cut_off_2nd_gross').val()) + (Number(col1_value_field) / 2);
		total_deduct_field = Number($('#'+id+'_total_deduct_field').val()) - Number(col1_value_field) / 2;

		if (!validateInput(value)) {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Invalid value",
			});
			$(this).text("0.00");
			return;
		}else{

			cut_off1_total = Number(cut_off_1st) - (Number(value) / 2);
				

			if(Number.isInteger(cut_off1_total)){
				cut_off1_total_replace = cut_off1_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off1').text(cut_off1_total_replace).change();

				cut_off2_total2 = cut_off_2nd - (Number(value) / 2);
				cut_off2_total2_replace = cut_off2_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off2').text(cut_off2_total2_replace).change();

			}else{
				cut_off1_total_explode = cut_off1_total.toString().split(".");
				cut_off1_total_replace = cut_off1_total_explode[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off1').text(cut_off1_total_replace).change();

				cut_off2_total2 = Number(cut_off_2nd) + Number(cut_off1_total_explode[1]) - Number(value);
				cut_off2_total2_replace = cut_off2_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off2').text(cut_off2_total2_replace).change();
			}
			
			
			cut_off1_gross_total = Number(cut_off_1st_gross) - (Number(value) / 2);
			cut_off1_gross_replace = cut_off1_gross_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off1_gross').text(cut_off1_gross_replace).change();

		
			cut_off2_gross_total2 = Number(cut_off_2nd_gross) - (Number(value) / 2);
			cut_off2_gross_replace = cut_off2_gross_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off2_gross').text(cut_off2_gross_replace).change();
			
			cut_off1_net_total = Number(cut_off1_gross_total);
			cut_off1_net_replace = cut_off1_net_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off1_net').text(cut_off1_gross_replace).change();

			total_deduct_total = Number(total_deduct_field) + Number(value);
			total_deduct_totalreplace = total_deduct_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_total_deduct').text(total_deduct_totalreplace).change();


			cut_off2_net_total2 = Number(cut_off2_net) - (Number(value) / 2);
			cut_off2_net_replace = cut_off2_net_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off2_net').text(cut_off2_net_replace).change();

			var sum = 0;
			$('.tmp_col1_value'+grand_total).each(function(){
				sum += Number($(this).text().replace(',', ''));
			});

			var sum_tmp_col2 = 0;
			$('.tmp_col2_value'+grand_total).each(function(){
				sum_tmp_col2 += Number($(this).text().replace(',', ''));
			});
				sum_replace = sum.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#tmp_col1_total'+grand_total).text(sum_replace).change();

				total_sum = (Number(sum) + Number(sum_tmp_col2)) * 2;
			cut_off_total_value = $("#cut_off_total_value"+grand_total).val() - total_sum;
				cut_off_total_value_replace = cut_off_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#cut_off_total'+grand_total).text(cut_off_total_value_replace).change();

			gross_total_value = $("#gross_total_value"+grand_total).val() - total_sum;
				gross_total_value_replace = gross_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#gross_total'+grand_total).text(gross_total_value_replace).change();

			total_deduction_value = Number($("#total_deduction_value"+grand_total).val()) + (Number(sum) + Number(sum_tmp_col2));
			total_deduction_value_replace = total_deduction_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#total_deduction_total'+grand_total).text(total_deduction_value_replace).change();

			net_amt_total_value = $("#net_amt_total_value"+grand_total).val() - total_sum;
				net_amt_total_value_replace = net_amt_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#net_amt_total'+grand_total).text(net_amt_total_value_replace).change();
	
		}

		let obj = {
			id: $(this).data('id'),
			value: value,
			type: "col1"
		}
		forUpdateDataCol1.push(obj);
		}
	});


	$(document).on("blur", ".tmp_col2.editable", function (e) {
		id = $(this).data('id');
		grand_total = $(this).data('value');
		value =  Number($('#tmp_col2_'+id).text().trim().replace(',', ''));
		value2 =  Number($('#tmp_col2_'+id).text().trim().replace(',', ''));
	   col2_value_field = $('#'+id+'col2_value_field').val();

	   if(Number(value) != col2_value_field){
	   cut_off1 = $('#'+id+'_cut_off1').text().replace(',', '');
	   cut_off2 = $('#'+id+'_cut_off2').text().replace(',', '');
	   cut_off1_gross = $('#'+id+'_cut_off1_gross').text().replace(',', '');
	   cut_off2_gross = $('#'+id+'_cut_off2_gross').text().replace(',', '');
	   cut_off1_net = $('#'+id+'_cut_off1_net').text().replace(',', '');
	   cut_off2_net = $('#'+id+'_cut_off2_net').text().replace(',', '');

	   basic_pay = Number($('#'+id+'_basic_pay').text().replace(',', ''));
	   pera_amt = Number($('#'+id+'_pera_amt').text().replace(',', ''));
	   total_deduct = Number($('#'+id+'_total_deduct').text().replace(',', ''));

	   cut_off_1st = Number($('#'+id+'_cut_off_1st').val()) + (Number(col2_value_field) / 2);
	   cut_off_1st_gross = Number($('#'+id+'_cut_off_1st_gross').val()) + (Number(col2_value_field) / 2);
	   cut_off_2nd = Number($('#'+id+'_cut_off_2nd').val()) + (Number(col2_value_field) / 2);
	   cut_off_2nd_gross = Number($('#'+id+'_cut_off_2nd_gross').val()) + (Number(col2_value_field) / 2);
	   total_deduct_field = Number($('#'+id+'_total_deduct_field').val()) + (Number(col2_value_field) / 2);

		if (!validateInput(value)) {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Invalid value",
			});
			$(this).text("0.00");
			return;
		}else{
			cut_off1_total = Number(cut_off_1st) - (Number(value) / 2);

			if(Number.isInteger(cut_off1_total)){
				cut_off1_total_replace = cut_off1_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off1').text(cut_off1_total_replace).change();

				cut_off2_total2 = cut_off_2nd - (Number(value) / 2);
				cut_off2_total2_replace = cut_off2_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off2').text(cut_off2_total2_replace).change();

			}else{
				cut_off1_total_explode = cut_off1_total.toString().split(".");
				cut_off1_total_replace = cut_off1_total_explode[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off1').text(cut_off1_total_replace).change();

				cut_off2_total2 = Number(cut_off_2nd) + Number(cut_off1_total_explode[1]) - (Number(value) / 2);
				cut_off2_total2_replace = cut_off2_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_cut_off2').text(cut_off2_total2_replace).change();
			}
			
			
			cut_off1_gross_total = Number(cut_off_1st_gross) - (Number(value) / 2);
			cut_off1_gross_replace = cut_off1_gross_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off1_gross').text(cut_off1_gross_replace).change();

		
			cut_off2_gross_total2 = Number(cut_off_2nd_gross) - (Number(value) / 2);
			cut_off2_gross_replace = cut_off2_gross_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off2_gross').text(cut_off2_gross_replace).change();
			
			cut_off1_net_total = Number(cut_off1_gross_total);
			cut_off1_net_replace = cut_off1_net_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off1_net').text(cut_off1_gross_replace).change();

			if(Number(value) > 0){
				total_deduct_total = Number(total_deduct_field) + Number(value) + Number(value2);
				total_deduct_totalreplace = total_deduct_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_total_deduct').text(total_deduct_totalreplace).change();
			}else{
				total_deduct_total = Number(total_deduct_field) + Number(value);
				total_deduct_totalreplace = total_deduct_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#'+id+'_total_deduct').text(total_deduct_totalreplace).change();
			}


			//total_deduct_all = total_deduct + value;
			cut_off2_net_total2 = Number(cut_off2_gross_total2) - (Number(value) / 2);
			cut_off2_net_replace = cut_off2_net_total2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			$('#'+id+'_cut_off2_net').text(cut_off2_net_replace).change();

			var sum = 0;
			$('.tmp_col2_value'+grand_total).each(function(){
				sum += Number($(this).text().replace(',', ''));
			});

			var sum_tmp_col1 = 0;
			$('.tmp_col1_value'+grand_total).each(function(){
				sum_tmp_col1 += Number($(this).text().replace(',', ''));
			});

				sum_replace = sum.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#tmp_col2_total'+grand_total).text(sum_replace).change();

				total_sum = (Number(sum) + Number(sum_tmp_col1)) * 2;
			cut_off_total_value = $("#cut_off_total_value"+grand_total).val() - total_sum;
				cut_off_total_value_replace = cut_off_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#cut_off_total'+grand_total).text(cut_off_total_value_replace).change();

			gross_total_value = $("#gross_total_value"+grand_total).val() - total_sum;
				gross_total_value_replace = gross_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#gross_total'+grand_total).text(gross_total_value_replace).change();

			total_deduction_value = Number($("#total_deduction_value"+grand_total).val()) + Number(sum) + Number(sum_tmp_col1);
				total_deduction_value_replace = total_deduction_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#total_deduction_total'+grand_total).text(total_deduction_value_replace).change();

			net_amt_total_value = $("#net_amt_total_value"+grand_total).val() - total_sum;
				net_amt_total_value_replace = net_amt_total_value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#net_amt_total'+grand_total).text(net_amt_total_value_replace).change();



		}
		let obj = {
			id: $(this).data('id'),
			value: value,
			type: "col2"
		}
		forUpdateDataCol2.push(obj);
	}
	});
	$(document).on("click", "#deleteColumn1, #deleteColumn2", function (e) {
		myBtn = $(this)
		e.preventDefault();
        var form = $(this);

		id = []
		if(myBtn.attr('id') == 'deleteColumn1'){
			pay_basis = $('#header_1').data('pay_basis');
			period = $('#header_1').data('period');
			division = $('#header_1').data('division');
			type = $('#header_1').data('type');
			$('.tmp_col1_fields').each(function(index, value){
				id.push($(value).data('id'))
			});
			forUpdateDataCol1 = [];			
		}else{
			pay_basis = $('#header_2').data('pay_basis');
			period = $('#header_2').data('period');
			division = $('#header_2').data('division');
			type = $('#header_2').data('type');
			$('.tmp_col2_fields').each(function(index, value){
				id.push($(value).data('id'))
			});
			forUpdateDataCol2 = [];
		}
	
        content = "Are you sure you want to proceed?";
			$.confirm({
				title: '<label class="text-warning">Confirm!</label>',
				content: content,
				type: 'orange',
				buttons: {
					confirm: {
						btnClass: 'btn-blue',
						action: function () {
								if(pay_basis == 'Contract of Service'){
									$('#deduction_header').attr('colspan',6);
								}else{
									$('#deduction_header').attr('colspan',12);
								}
								$('#addColumn1').show();
								$('#addColumn2').hide();
								if(myBtn.attr('id') == 'deleteColumn1'){
									$('#deleteColumn1').hide();
									$('.tmp_col1').hide();
								}else{
									$('#deleteColumn2').hide();
									$('.tmp_col2').hide();
								}
								$('#removeColumns').hide();        
								$('#btnSave').hide();  
								$('#printClearance').show();                 
								$('#printClearance').removeAttr('disabled');  
							$.confirm({
								content: function () {
									var self = this;
									return $.ajax({
										type: "POST",
										url: commons.baseurl + "payrollreports/PayrollRegister/deletePayrollRegisterSummary",
										data: {
											pay_basis: pay_basis,
											period: period,
											division: division,
											type: type,
											id :id
										},
										dataType: "json",
										success: function (result) {
											page = my.attr("id");
											if (result.hasOwnProperty("key")) {
												switch (result.key) {
													case "deletePayrollRegisterSummary":
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														// $('#myModal .modal-body').html('');
														$('#myModal').modal('hide');
														$('#btnSave').hide();                 
														$('#printClearance').show();                 
														$('#printClearance').removeAttr('disabled'); 
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
						}
	
					},
					cancel: function () {
						myBtn.removeAttr('disabled')
					}
				}
			});	
	});
	$(document).on('click', '#btnSave', function (e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
		if(forUpdateDataHeader.length === 0 &&
			forUpdateDataCol1.length === 0 &&
			forUpdateDataCol2.length === 0){
				$('#printClearance').show();                 
				$('#printClearance').removeAttr('disabled'); 
				$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "No Changes has been made.",
			});
        }else{
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
										url: commons.baseurl + "payrollreports/PayrollRegister/updatePayrollRegisterSummary",
										data: {
											forUpdateDataHeader: forUpdateDataHeader,
											forUpdateDataCol1: forUpdateDataCol1,
											forUpdateDataCol2: forUpdateDataCol2,
										},
										dataType: "json",
										success: function (result) {
											page = my.attr("id");
											if (result.hasOwnProperty("key")) {
												switch (result.key) {
													case "updatePayrollRegisterSummary":
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														// $('#myModal .modal-body').html('');
														$('#myModal').modal('hide');
														$('#btnSave').hide();                 
														$('#printClearance').show();                 
														$('#printClearance').removeAttr('disabled'); 
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
						}
	
					},
					cancel: function () {
					}
				}
			});
		}		
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

	$(document).on("click", "#viewPayrollRegisterSummary", function (e) {
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		pay_basis = $(".search_entry #pay_basis").val();
		division_id = $(".search_entry #division_id").val();
		
		var division_name;
		if(division_id == 1){
			division_name = "EDO";
		}else if(division_id == 2){
			division_name = "DEDO";
		}
		else if(division_id == 3){
			division_name = "PPD";
		}
		else if(division_id == 4){
			division_name = "MED";
		}
		else if(division_id == 5){
			division_name = "WUD";
		}
		else if(division_id == 6){
			division_name = "WRD";
		}
		else if(division_id == 7){
			division_name = "AFD";
		}
		else if(division_id == 11){
			division_name = "CEO";
		}
		else if(division_id == 12){
			division_name = "DEO";
		}
		else if(division_id == 13){
			division_name = "BAC";
		}
		else if(division_id == 14){
			division_name = "NEWLY HIRED";
		}
		// week_period = $(".search_entry #week_period").val();
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

			modal_title = "Payroll Register";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/PayrollRegister/viewPayrollRegisterSummary",
				data: {
					payroll_period_id: payroll_period_id,
					pay_basis: pay_basis,
					division_id: division_id,
					week_period: "",
					division_name: division_name
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewPayrollRegisterSummary":
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
	$(document).on("click", "#viewPayrollRegisterAllSummary", function (e) {
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
		payroll_period_id = $(".search_entry #payroll_period_id").val();
		pay_basis = $(".search_entry #pay_basis").val();
		division_id = $(".search_entry #division_id").val();
		
		var division_name;
		if(division_id == 1){
			division_name = "EDO";
		}else if(division_id == 2){
			division_name = "DEDO";
		}
		else if(division_id == 3){
			division_name = "PPD";
		}
		else if(division_id == 4){
			division_name = "MED";
		}
		else if(division_id == 5){
			division_name = "WUD";
		}
		else if(division_id == 6){
			division_name = "WRD";
		}
		else if(division_id == 7){
			division_name = "AFD";
		}
		else if(division_id == 11){
			division_name = "CEO";
		}
		else if(division_id == 12){
			division_name = "DEO";
		}
		else if(division_id == 13){
			division_name = "BAC";
		}
		else if(division_id == 14){
			division_name = "NEWLY HIRED";
		}

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

			modal_title = "Payroll Register";
			$("#myModal .modal-title").html(modal_title);
			$("#myModal .modal-body").html(loader);
			$("#myModal").modal("show");
			$.ajax({
				type: "POST",
				url:
					commons.baseurl +
					"payrollreports/PayrollRegister/viewPayrollRegisterAllSummary",
				data: {
					payroll_period_id: payroll_period_id,
					pay_basis: pay_basis,
					division_id: division_id,
					week_period: "",
					division_name: division_name
				},
				dataType: "json",
				success: function (result) {
					page = my.attr("id");
					if (result.hasOwnProperty("key")) {
						switch (result.key) {
							case "viewPayrollRegisterAllSummary":
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

	 function createTable(){
		id = $(this).data('id');
		grand_total = $(this).data('value');
		value =  Number($('#tmp_col2_'+id).text().trim().replace(',', ''));
		value2 =  Number($('#tmp_col2_'+id).text().trim().replace(',', ''));
	   col2_value_field = $('#'+id+'col2_value_field').val();

	   if(Number(value) != col2_value_field){
	   cut_off1 = $('#'+id+'_cut_off1').text().replace(',', '');
	   cut_off2 = $('#'+id+'_cut_off2').text().replace(',', '');
	   cut_off1_gross = $('#'+id+'_cut_off1_gross').text().replace(',', '');
	   cut_off2_gross = $('#'+id+'_cut_off2_gross').text().replace(',', '');
	   cut_off1_net = $('#'+id+'_cut_off1_net').text().replace(',', '');
	   cut_off2_net = $('#'+id+'_cut_off2_net').text().replace(',', '');

	   basic_pay = Number($('#'+id+'_basic_pay').text().replace(',', ''));
	   pera_amt = Number($('#'+id+'_pera_amt').text().replace(',', ''));
	   total_deduct = Number($('#'+id+'_total_deduct').text().replace(',', ''));

	   cut_off_1st = Number($('#'+id+'_cut_off_1st').val()) + (Number(col2_value_field) / 2);
	   cut_off_1st_gross = Number($('#'+id+'_cut_off_1st_gross').val()) + (Number(col2_value_field) / 2);
	   cut_off_2nd = Number($('#'+id+'_cut_off_2nd').val()) + (Number(col2_value_field) / 2);
	   cut_off_2nd_gross = Number($('#'+id+'_cut_off_2nd_gross').val()) + (Number(col2_value_field) / 2);
	   total_deduct_field = Number($('#'+id+'_total_deduct_field').val()) + (Number(col2_value_field) / 2);
	   }
	 }
});

