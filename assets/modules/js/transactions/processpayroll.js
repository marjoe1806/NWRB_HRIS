$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	var clickBtn;
	// table = $('#datatables').DataTable({
	// 	"processing": true,
	// 	"serverSide": true,
	// 	"order": [],
	// 	"ajax": {
	// 		url: commons.baseurl + "transactions/ProcessPayroll/fetchRows",
	// 		type: "POST"
	// 	},
	// 	"columnDefs": [{
	// 		"orderable": true,
	// 	}, ],
	// });

	$.when(
		getFields.payBasis3(),
		getFields.payrollProcess(),
		// getFields.location(),
		// getFields.division(),
		getFields.division()
	).done(function () {
		$.AdminBSB.select.activate();
	})
	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});

	$(document).on("click","#btn_search", function(){
		loadTable();
	});
	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$('[data-toggle="popover"]').popover({placement:'bottom'});
		//$.AdminBSB.input.activate();
	})
	$(document).on('click', function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
				(($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
			}

		});
	});
	$(document).on('keyup','#vacation_leave_hrs,#sick_leave_hrs',function(e){
		time = $(this).val();
		my = $(this);
		url = commons.baseurl+"transactions/ProcessPayroll/convertTimeIntoFractions",
		$.ajax({
	        type: "POST",
	        url: url,
	        data:{time:time},
	        dataType: "json",
	        success: function(result){
	        	val = 0
	        	if(my.attr('id') == "vacation_leave_hrs"){
	        		cred = $('#vl_credits').text()
	        		if((parseFloat(cred) + 5) >  parseFloat(result.data))
	        			val = result.data;
	        		else{
	        			$.alert({
				            title:'<label class="text-danger">Failed</label>',
				            content:'Amount must be lower than credits. Must have maintaining 5 credits.'
				        });
				        my.val(0)
	        		}
	            	$('#vacation_leave_credits').val(val);
	        	}
	           	else{
	           		cred = $('#sl_credits').text()
	           		if((parseFloat(cred) + 5) >  parseFloat(result.data))
	        			val = result.data;
	        		else{
	        			$.alert({
				            title:'<label class="text-danger">Failed</label>',
				            content:'Amount must be lower than credits. Must have maintaining 5 credits.'
				        });
				        my.val(0)
	        		}
	           		$('#sick_leave_credits').val(val);
	           	}
	        }
	    });
	})
	$(document).on('click','#monetizeCredits',function(e){
		e.preventDefault();
		trans_id = $('.id').val();
		employee_id = $('.employee_id').val();
		division_id = $('.division_id').val();
		vac_hrs = $('#vacation_leave_hrs').val()
		vac_credits = $('#vacation_leave_credits').val()
		sick_hrs = $('#sick_leave_hrs').val()
		sick_credits = $('#sick_leave_credits').val()
		data = {
			trans_id: trans_id,
			employee_id: employee_id,
			division_id: division_id,
			vacation_leave_hrs: vac_hrs,
			vacation_leave_credits: vac_credits,
			sick_leave_hrs: sick_hrs,
			sick_leave_credits: sick_credits
		}
        var form = $(this)
        content = "Are you sure you want to monetize credits for tardiness?";
        url = commons.baseurl+"transactions/ProcessPayroll/monetizeLeaveCredits";
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
                                    data: data,
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'monetizeLeaveCredits':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            $.each(result.last_process[0],function(i,v){
                                                            	clickBtn.data(i,v);
                                                            })
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

	})
	$(document).on('click', '#printProcessPayroll', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})
	$(document).on('keypress keyup keydown', 'form #amount', function (e) {
		$('form #balance').val($(this).val())
	})
	$(document).on('change', '#pay_basis ', function (e) {
		pay_basis = $(this).val();
		$.when(
			getFields.payrollperiodcutoff(pay_basis)
		).done(function () {
			$.AdminBSB.select.activate();
			if(pay_basis == "Permanent-Casual"){
				$('.payrollProcess').show();
			}
			else{
				$('.payrollProcess').hide();
			}
		})
	})
	const addCommas = (x) => {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	}
	//Ajax non-forms
	$(document).on('click', '.viewProcessPayrollForm', function (e) {
		e.preventDefault();
		my = $(this)
		clickBtn = my;
		id = my.attr('data-id');
		url = my.attr('href');
		employee_id = my.data('employee_id')
		year = $('.search_entry #search_year').val()
		month = $('.search_entry #month').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val();
		//console.log(me.data())
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				payroll_period_id: payroll_period_id,
				employee_id: employee_id
			},
			dataType: "json",
			success: function (result) {
				page = my.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'viewProcessPayroll':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							modal_title = my.data("employee_id_number") + " - " + my.data("last_name") + ", " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("extension");
							$('#myModal .modal-title').html(modal_title);
							$('#myModal .modal-body').html(result.form);
							if(my.data("pay_basis") == 'Job Order') {
								$('.taxable_gross').parent().parent().parent().parent().hide();
								$('.gross_pay').parent().parent().parent().parent().hide();
								$('.cutoff_1').parent().parent().parent().parent().hide();
								$('.cutoff_2').parent().parent().parent().parent().hide();
							} else {
								$('.earned_for_period').parent().parent().parent().parent().hide();
								$('.net_earned').parent().parent().parent().parent().hide();
							}
							$('#myModal').modal('show');
							$.each(my.data(), function (i, v) {
								if(i == "employee_id" || i == "id" || i == "payroll_period_id")
									val = v
								else
									val = addCommas(v)
								$('#viewProcessPayroll .' + i).val(val);

							})
							// console.log(addCommas(my.data("salary")));
							break;
					}
					$("#" + result.key).validate({
						rules: {
							".required": {
								required: true
							},
							".email": {
								required: true,
								email: true
							}
						},
						highlight: function (input) {
							$(input).parents('.form-line').addClass('error');
						},
						unhighlight: function (input) {
							$(input).parents('.form-line').removeClass('error');
						},
						errorPlacement: function (error, element) {
							$(element).parents('.form-group').append(error);
						}
					});
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})

	//Ajax non-forms
	$(document).on('click', '.viewPayrollForm', function (e) {
		e.preventDefault();
		my = $(this)
		clickBtn = my;
		id = my.attr('data-id');
		url = my.attr('href');
		employee_id = my.data('employee_id')
		year = $('.search_entry #search_year').val()
		month = $('.search_entry #month').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val();
		//console.log(me.data())
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				payroll_period_id: payroll_period_id,
				employee_id: employee_id
			},
			dataType: "json",
			success: function (result) {
				page = my.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'viewProcessPayroll':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							modal_title = my.data("employee_id_number") + " - " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("last_name")
							$('#myModal .modal-title').html(modal_title);
							$('#myModal .modal-body').html(result.form);
							if(my.data("pay_basis") == 'Job Order') {
								$('.taxable_gross').parent().parent().parent().parent().hide();
								$('.gross_pay').parent().parent().parent().parent().hide();
								$('.cutoff_1').parent().parent().parent().parent().hide();
								$('.cutoff_2').parent().parent().parent().parent().hide();
							} else {
								$('.earned_for_period').parent().parent().parent().parent().hide();
								$('.net_earned').parent().parent().parent().parent().hide();
							}
							$('#myModal').modal('show');
							$.each(my.data(), function (i, v) {
								if(i == "employee_id" || i == "id" || i == "payroll_period_id")
									val = v
								else
									val = addCommas(v)
								$('#viewProcessPayroll .' + i).val(val);

							})
							// console.log(addCommas(my.data("salary")));
							$("form input:not(:button),form input:not(:submit)").attr("disabled", true);
							
							break;
					}
					$("#" + result.key).validate({
						rules: {
							".required": {
								required: true
							},
							".email": {
								required: true,
								email: true
							}
						},
						highlight: function (input) {
							$(input).parents('.form-line').addClass('error');
						},
						unhighlight: function (input) {
							$(input).parents('.form-line').removeClass('error');
						},
						errorPlacement: function (error, element) {
							$(element).parents('.form-group').append(error);
						}
					});
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})
	//Ajax Forms
	$(document).on('click','#submitUserCheckList',function(e){
		e.preventDefault();
		pay_basis = $('.search_entry #pay_basis').val()
		division_id = $('.search_entry #division_id').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val()
		payroll_process = $('.search_entry #payroll_process').val()
		myBtn = $(this)
		$(this).attr('disabled','disabled')
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: 'Are you sure you want to process payroll?',
			type: 'orange',
			buttons: {
				confirm: {
					btnClass: 'btn-blue',
					action: function () {

						//Code here
						$(".jconfirm-buttons .btn-blue").prop('disabled', true);
						var progressbar = 	'<div class="progress"><div data-counter="0" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
											'0% Complete (success)' +
											'</div></div>';
						total_data = $(".checkbox_table:checked").length;
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
										pay_basis: pay_basis,
										division_id: division_id,
										payroll_period_id: payroll_period_id,
										payroll_process: payroll_process,
										employee_ids: employee_ids,
										retry : 1,
										total_data: total_data,
										total_count: total_count
									}
									computePayroll(data);
								}
								
							});
							
							if(total_data == count){
                            	url2 = commons.baseurl+'transactions/ProcessPayroll/';
                                $.ajax({
                                    type: "POST",
                                    url: url2,
                                    dataType: "json",
                                    success: function(result2){
                                        $('#table-holder').html(result2.table);
                                        table = $('#datatables').DataTable({  
                                               "processing":true,  
                                               "serverSide":true,  
                                               "order":[],  
                                               "ajax":{  
                                                    url:commons.baseurl+ "transactions/ProcessPayroll/fetchRows",  
                                                    type:"POST",
                                                    data: {
                                                    	pay_basis: pay_basis,
                                                    	payroll_period_id: payroll_period_id,
                                                    	division_id: division_id
                                                    }
                                               },  
                                               "columnDefs":[  
                                                    {  
														"targets"  : [0],
														"orderable":false,  
                                                    },  
                                               ],  
                                        });
                                        $.alert({
                                            title:'<label class="text-success">Success</label>',
                                            content:"Successfully Computed Payroll for Selected Employees"
                                        });
                                        setTimeout(function(){ 
                                        	$('#myModal').modal('hide');
                                        	percentage = 1;
	                                        // percentage = 100 - (((total_data - (k+1)) / total_data) * 100);
	                                        $('.progress-bar').attr("aria-valuenow",percentage)
	                                        $('.progress-bar').css("width",percentage+"%");
	                                        $('.progress-bar').html(""+percentage.toFixed() + "% Complete (danger)");
                                        }, 2000);
                                        

                                    },
                                    error: function(){
                                        $.alert({
                                            title:'<label class="text-danger">Failed</label>',
                                            content:'There was an error in the connection. Please contact the administrator for updates.'
                                        });
                                        myBtn.removeAttr('disabled')
                                    }
                                }); 
                            }
                            myBtn.removeAttr('disabled');
						}
						else{
							$.alert({
								title: '<label class="text-danger">Failed</label>',
								content: 'Please select Employees.'
							});
							myBtn.removeAttr('disabled')
						}
						
					}

				},
				cancel: function () {
					myBtn.removeAttr('disabled')
				}
			}
		});
	})

	function computePayroll(data){
		var Id = [];
		var EmployeeNumber = [];
		var PayrollPeriodId = [];
		var PayrollPeriod = [];
		var ShiftId = [];
		var permanent_date = [];

		for(var i=0; i<data.employee_ids.length; i++) {
			plus_url2 = '?Id=' + data.employee_ids[i];
			url2 = commons.baseurl + "employees/Employees/getEmployeesById" + plus_url2;
			var employee = function () {
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

			Id.push(employee.Data.details[0].id);
			EmployeeNumber.push(employee.Data.details[0].employee_number);
			ShiftId.push(employee.Data.details[0].shift_id);		
			permanent_date.push(employee.Data.details[0].date_of_permanent);
		}
		
		data.ids = Id;
		data.employee_numbers = EmployeeNumber;
		data.shiftIds = ShiftId;
		data.permanent_date = permanent_date;

		url = commons.baseurl + "transactions/ProcessPayroll/computePayroll";
		// plus_url = '?pay_basis=' + data.pay_basis + '&payroll_period_id=' + data.payroll_period_id + '&payroll_process=' + data.payroll_process + '&payroll_grouping_id=' + data.payroll_grouping_id
		$.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(result){
                if(result.Code == "0"){
                    if(result.hasOwnProperty("key")){
                        switch(result.key){
                            case 'computePayroll':
                            	k = $('.progress-bar').data('counter')
                              	percentage = 100 - (((data.total_count - (k+1)) / data.total_count) * 100);
                            	$('.emp_count').html(k+1)
                                $('.progress-bar').data('counter',k+1);
                                $('.progress-bar').attr("aria-valuenow",percentage)
                                $('.progress-bar').css("width",percentage+"%");
                                $('.progress-bar').html(""+percentage.toFixed() + "% Complete (success)");
                                break;
                        }
                    }
                }
            },
            error: function(result){
            		console.log('error');
            		data.retry+=1;
            		if(data.retry < 6){
            			// computePayroll(data)
            		}
            		
            }
        });
        
	}
	$(document).on('click', '#computePayroll', function (e) {
		e.preventDefault();
		my = $(this)
		url = my.attr('href');
		pay_basis = $('.search_entry #pay_basis').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val()
		payroll_grouping_id = $('.search_entry #payroll_grouping_id').val()
		location_id = $('.search_entry #location_id').val()
		division_id = $('.search_entry #division_id').val()
		isPosted = $("#payroll_period_id").find('option:selected').attr("data-is-posted")
		plus_url = '?PayBasis=' + pay_basis + '&PayrollPeriod=' + payroll_period_id
		

	    content = "Are you sure you want to compute periodic payroll?";	
	    if (isPosted == 1) {
	    	$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Payroll Period was already Posted.'
			});
			return false;
	    }
		if (division_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please select Department.'
			});
		} else if (pay_basis == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please select Pay Basis.'
			});
		} else if (payroll_period_id == "") {
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please select Period.'
			});
		}  else {
			getFields.employeeModal(pay_basis,location_id,division_id,null,1,null,payroll_grouping_id)
		}
	})

	function loadTable() {
		pay_basis = $('.search_entry #pay_basis').val()
		division_id = $('.search_entry #division_id').val()
		payroll_period_id = $('.search_entry #payroll_period_id').val()
		url2 = commons.baseurl+'transactions/ProcessPayroll/';
                                $.ajax({
                                    type: "POST",
                                    url: url2,
                                    dataType: "json",
                                    success: function(result2){
                                        $('#table-holder').html(result2.table);
                                        table = $('#datatables').DataTable({  
                                               "processing":true,  
                                               "serverSide":true,  
											   "stateSave": true, // presumably saves state for reloads -- entries
											   "bStateSave": true, // presumably saves state for reloads -- page number
                                               "order":[],  
                                               "ajax":{  
                                                    url:commons.baseurl+ "transactions/ProcessPayroll/fetchRows",  
                                                    type:"POST",
                                                    data: {
                                                    	pay_basis: pay_basis,
                                                    	payroll_period_id: payroll_period_id,
                                                    	division_id: division_id
                                                    }
                                               },  
                                               "columnDefs":[  
                                                    {  
														"targets"  : [0],
                                                         "orderable":false,  
                                                    },  
                                               ],  
                                        });                                   

                                    },
                                    error: function(){
                                        $.alert({
                                            title:'<label class="text-danger">Failed</label>',
                                            content:'There was an error in the connection. Please contact the administrator for updates.'
                                        });
                                        myBtn.removeAttr('disabled')
                                    }
                                });
	}

	function reloadTable() {
		//table.ajax.reload();
		employee_id = $('.search_entry #employee_id').val()
		plus_url = '?EmployeeId=' + employee_id
		if (employee_id == "") {
			return false;
		}
		$("#searchEmployeePayroll").click();
	}

	function PrintElem(elem) {
		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();

		return true;
	}
})
