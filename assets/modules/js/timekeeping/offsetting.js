$(function () {
	var page = "";
	base_url = commons.base_url;
	var table;
	table = $('#datatables').DataTable({
		"pagingType": "full_numbers",
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		responsive: true,
		aaSorting: [],
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Search records",
		}
	});
	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		maxDate: new Date(),
		time: false
	});
	$('.timepicker').inputmask('hh:mm:ss', { placeholder: '__:__:__ _m', alias: 'time24', hourFormat: '24' });
	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			maxDate: new Date(),
			time: false
		});
		$('.timepicker').inputmask('hh:mm:ss', { placeholder: '__:__:__ _m', alias: 'time24', hourFormat: '24' });
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})
	$(document).on('click', '#addBtn', function (e) {
		e.preventDefault();
		$('select').selectpicker('destroy');
		index = $('form .card').length;
		row_1 = $('#row_0').html();
		row_1 = row_1.split('[0]').join('['+index+']');
		// console.log(row_1);
		$('<div id="row_'+index+'" class="col-md-6">'+row_1+'</div>').insertBefore("#addBtnCotainer");
		$('#row_'+index).find('.removeBtn').css('visibility','visible');
		$('select').selectpicker('refresh');
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			maxDate: new Date(),
			time: false
		});
		$('.timepicker').inputmask('hh:mm:ss', { placeholder: '__:__:__ _m', alias: 'time24', hourFormat: '24' });
	})
	$(document).on('click', '.removeBtn', function (e) {
		$(this).closest('.card').parent().remove();
	})
	$(document).on('click', '#printOffsetting', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})

	$(document).on('click', '#changeAttachment', function (e) {
		e.preventDefault();
		$("#updateFileButtons").hide();
		$("#hiddenFileInput").show();
	})

	$(document).on('click', '#cancelChange', function (e) {
		e.preventDefault();
		$("#updateFileButtons").show();
		$("#hiddenFileInput").hide();
	})
	
	$(document).on('change','#division_id ',function(e){
        division_id = $(this).val();
        card = $(this).closest('.card')
        index = card.parent().attr('id').replace('row_','');
        select = '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required><option value="">Loading...</option></select>'
        card.find('.employee_select').html(select);
        url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
        data = {division_id:division_id}
        $.ajax({
            url: url2,
            data: data,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                select = '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required>'
                options = '<option value=""></option>'
                if(temp.Code == "0"){
                    $.each(temp.Data.details, function(i,v){
                        options += '<option value="'+v.id+'">'+v.employee_id_number+' - '+v.first_name+' '+v.middle_name+' '+v.last_name+'</option>'
                    })
                    //$('.selectpicker').selectpicker('refresh')
                }
                select += options
                select += '</select>'
                card.find('.employee_select').html(select)
                card.find('#employee_id').val(employee_id).change();
                if($('form').attr('id') == "addOffsetting"){
                	card.find('#employee_id').attr('name','table1['+index+'][employee_id]')	
                }
            	$.AdminBSB.select.activate();
            }
        });
    })

    var transaction_date = null;
	$(document).on('change','#employee_id',function(e){
		e.preventDefault()
		card = $(this).closest('.card')
		card.find("#transaction_date").val(transaction_date).change()
	})
	// $(document).on('change', '#transaction_date', function (e) {
	// 	e.preventDefault();
	// 	card = $(this).closest('.card')
	// 	if (card.find('#employee_id').val() != '' && card.find("#transaction_date").val() != '') {
	// 		$.ajax({
	// 			type: "POST",
	// 			url: commons.baseurl + "timekeeping/Offsetting/getActualLogs",
	// 			data: {
	// 				employee_id: $("#employee_id").val(),
	// 				transaction_date: card.find("#transaction_date").val()
	// 			},
	// 			dataType: "json",
	// 			success: function (temp) {
	// 				result = temp.dtr;
	// 				$('<input>').attr({
	// 					value: temp.number,
	// 					id: 'employee_number',
	// 					name: 'employee_number',
	// 					type: 'hidden'
	// 				}).appendTo('#actual_logs_container');
	// 				if (result.length > 0) {
	// 					$("#actual_logs_container").show();
	// 					result.forEach(record => {
	// 						switch (record.transaction_type) {
	// 							case '0':
	// 								card.find("#actual_time_in").val(record.transaction_time)
	// 								break;
	// 							case '2':
	// 								card.find("#actual_break_out").val(record.transaction_time)
	// 								break;
	// 							case '1':
	// 								card.find("#actual_time_out").val(record.transaction_time)
	// 								break;
	// 						}
	// 					});
	// 				} else {
	// 					var transaction_date = card.find('#transaction_date').val();
	// 					card.find('#transaction_date').val('');
	// 					card.find('#actual_time_in').val('');
	// 					card.find('#actual_break_out').val('');
	// 					card.find('#actual_time_out').val('');
	// 					$.alert({
	// 						title: '<label class="text-danger">Failed</label>',
	// 						content: 'There is no available offset from '+transaction_date+'.'
	// 					});
	// 				}
	// 			},
	// 			error: function (result) {
	// 				self.setContent("There was an error in the connection. Please contact the administrator for updates.");
	// 				self.setTitle('<label class="text-danger">Failed</label>');
	// 			}
	// 		});
	// 	}

	// })
	$(document).on('change', '#transaction_date', function (e) {
		e.preventDefault();
		transaction_date = '';
		employee_id = '';
		form_id = $('form').attr('id');
		my = $(this)
		card = my.closest('.card')
		index = card.parent().attr('id').replace('row_','');
		if (card.find("#employee_id").val() != '' && card.find("#transaction_date").val() != '') {
			card.find("#actual_time_in").val('')
			card.find("#actual_break_out").val('')
			card.find("#actual_time_out").val('')
			$.ajax({
				type: "POST",
				url: commons.baseurl + "timekeeping/Offsetting/getActualLogs",
				data: {
					employee_id: card.find("#employee_id").val(),
					transaction_date: card.find("#transaction_date").val()
				},
				dataType: "json",
				success: function (temp) {
					result = temp.dtr;
					$('<input>').attr({
						value: temp.number,
						id: 'employee_number',
						name: 'table1['+index+'][employee_number]',
						type: 'hidden'
					}).appendTo('#actual_logs_container');
					if (result.length > 0) {
						$("#actual_logs_container").show();
						result.forEach(record => {
							switch (record.transaction_type) {
								case '0':
									card.find("#actual_time_in").val(record.transaction_time)
									break;
								case '2':
									card.find("#actual_break_out").val(record.transaction_time)
									break;
								case '1':
									card.find("#actual_time_out").val(record.transaction_time)
									break;
							}
						});
					} else {
						var transaction_date = card.find('#transaction_date').val();
						card.find('#transaction_date').val('');
						card.find('#actual_time_in').val('');
						card.find('#actual_break_out').val('');
						card.find('#actual_time_out').val('');
						$.alert({
							title: '<label class="text-danger">Failed</label>',
							content: 'There is no available offset from '+transaction_date+'.'
						});
					}
				},
				error: function (result) {
					self.setContent("There was an error in the connection. Please contact the administrator for updates.");
					self.setTitle('<label class="text-danger">Failed</label>');
				}
			});
		}

	})

	//Confirms
	$(document).on('click', '.activateOffsetting,.deactivateOffsetting', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateOffsetting')) {
			content = 'Are you sure you want to activate selected report?';
		} else if (me.hasClass('deactivateSubOffsetting')) {
			content = 'Are you sure you want to deactivate selected sub report?';
		}
		data = {
			id: id
		};
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
									data: {
										id: id
									},
									dataType: "json",
									success: function (result) {
										if (result.Code == "0") {
											if (result.hasOwnProperty("key")) {
												switch (result.key) {
													case 'activateOffsetting':
													case 'deactivateOffsetting':
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														loadTable();
														break;
												}
											}
										} else {
											self.setContent(result.Message);
											self.setTitle('<label class="text-danger">Failed</label>');
										}
									},
									error: function (result) {
										self.setContent("There was an error in the connection. Please contact the administrator for updates.");
										self.setTitle('<label class="text-danger">Failed</label>');
									}
								});
							}
						});
					}

				},
				cancel: function () {}
			}
		});
	})

	var employee_id;
    $(document).on('change','#location_id ',function(e){
        location_id = $(this).val();
        $.when(
            getFields.employee({location_id:location_id})
        ).done(function(){
            $('#employee_id').val(employee_id).change();
            $.AdminBSB.select.activate();
        })
    })

    $(document).on('keyup','#locator_time_in, #locator_break_out, #locator_time_out',function(e){
    	card = $(this).closest('.card')

    	var locator_time_in = card.find('#locator_time_in').val().split('_').join('0');
    	var locator_break_out = card.find('#locator_break_out').val().split('_').join('0');
    	var locator_time_out = card.find('#locator_time_out').val().split('_').join('0');
    	var transaction_date_to = card.find('#transaction_date_to').val() != '' ? card.find('#transaction_date_to').val() : '2019-01-01';
    	var offset = 0;
    	var timeStart = '00:00:00';
    	var timeEnd = '00:00:00';
    	var hours = 0;
    	var mins = 0;

        if(locator_time_out == '00:00:00' || locator_time_out == '') {
        	timeStart = new Date(transaction_date_to+" " + locator_time_in).getTime();
			timeEnd = new Date(transaction_date_to+" " + locator_break_out).getTime();
        } else {
        	timeStart = new Date(transaction_date_to+" " + locator_time_in).getTime();
			timeEnd = new Date(transaction_date_to+" " + locator_time_out).getTime();
        }

        offset = (timeEnd - timeStart) / 1000;
        offset = (offset/60)/60;
        
        hours = Math.floor(offset);
        mins = Math.round((offset - hours) * 60);

        if(hours < 0) {
        	hours = 0;
        	mins = 0;
        } else if(hours >= 8) {
        	hours = 8;
        	mins = 0;
        }

        var number_of_hrs = hours+':'+mins;
        // console.log(locator_break_out);
        card.find('#number_of_hrs').val(number_of_hrs); 
    })

	//Ajax non-forms
	$(document).on('click', '#addOffsettingForm,.updateOffsettingForm,.viewOffsettingForm', function (e) {
		e.preventDefault();
		me = $(this)
		id = me.attr('data-id');
		url = me.attr('href');
		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function (result) {
				page = me.attr('id');
				if (result.hasOwnProperty("key")) {
					switch (result.key) {
						case 'addOffsetting':
							$.when(
								// getFields.location(),
								getFields.division(),
								// getFields.employee(),
								// getFields.employee_2(),
								getFields.document(),
								// getFields.location(),
							).done(function () {
								$('form #division_id').attr('name','table1[0][division_id]');
								$.AdminBSB.select.activate();
							})
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-title').html('Add New Offsetting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'generateOffsetting':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Employee report');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'viewOffsetting':
						case 'updateOffsetting':
							$.when(
								// getFields.employee(),
								// getFields.location(),
								getFields.division(),
								// getFields.employee_2(),
								getFields.document(),
								// getFields.location(),
							).done(function () {
								$('.division_id').selectpicker('val', me.data('division_id'));
								$('.division_id').val(me.data('division_id')).change();
								// $('.location_id').selectpicker('val', me.data('location_id'));
								// $('.location_id').val(me.data('location_id')).change();
								$('.remarks').selectpicker('val', me.data('remarks'));
								$('.remarks').val(me.data('remarks')).change();
								employee_id = me.data('employee_id')
								transaction_date = me.data('transaction_date')
								$('.employee_id_2').selectpicker('val', me.data('checked_by'));
								$('.type_id').selectpicker('val', me.data('type_id'));
								// $('.location_id').selectpicker('val', me.data('location_id'));
								// $('.employee_id').selectpicker('refresh');
								// $("#transaction_date").change();
								$.AdminBSB.select.activate();
							})
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Offsetting Details');
							$('#myModal .modal-body').html(result.form);

							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								if(i != 'division_id' && i != 'employee_id' && i != 'transaction_date')
									$('.' + i).val(me.data(i)).change();
							});

							$('#viewAttachment').attr('href', commons.baseurl + "assets/uploads/Offsetting/" + me.data("filename"));
							$('#downloadAttachment').attr('href', commons.baseurl + "assets/uploads/Offsetting/" + me.data("filename"));
							if(result.key == 'viewOffsetting'){
								$('#changeAttachment').hide();
								$('form').find('input, textarea, select').attr('disabled','disabled');
                                $('form').find('#cancelUpdateForm').removeAttr('disabled');
							}
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
	$(document).on('submit', '#addOffsetting,#updateOffsetting', function (e) {
		e.preventDefault();
		var form = $(this)

			content = "Are you sure you want to proceed?";
			if (form.attr('id') == "addOffsetting") {
				content = "Are you sure you want to add report?";
			}
			if (form.attr('id') == "updateOffsetting") {
				content = "Are you sure you want to update report?";
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
															case 'addOffsetting':
															case 'updateOffsetting':
																self.setContent(result.Message);
																self.setTitle('<label class="text-success">Success</label>');
																$('#myModal .modal-body').html('');
																$('#myModal').modal('hide');
																loadTable();
																break;
														}
													}
												} else {
													self.setContent(result.Message);
													self.setTitle('<label class="text-danger">Failed</label>');
												}
											}
										},
										error: function (result) {
											self.setContent("There was an error in the connection. Please contact the administrator for updates.");
											self.setTitle('<label class="text-danger">Failed</label>');
										}
									});
								}
							});
						}

					},
					cancel: function () {}
				}
			});

	})

	function loadTable() {
		var url = window.location.href;
		$.ajax({
			url: url,
			dataType: "json",
			success: function (result) {
				$("#table-holder").html(result.table);
				table = $('#datatables').DataTable({
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 25, 50, -1],
						[10, 25, 50, "All"]
					],
					responsive: true,
					aaSorting: [],
					language: {
						search: "_INPUT_",
						searchPlaceholder: "Search records",
					}

				});
			}
		});
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
