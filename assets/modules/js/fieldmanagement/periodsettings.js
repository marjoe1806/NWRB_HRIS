$(function () {
	base_url = commons.base_url;
	// table_monthly = $('#datatables_monthly').DataTable({
	// 	"pagingType": "full_numbers",
	// 	"lengthMenu": [
	// 		[10, 25, 50, -1],
	// 		[10, 25, 50, "All"]
	// 	],
	// 	aaSorting: [],
	// 	language: {
	// 		search: "_INPUT_",
	// 		searchPlaceholder: "Search records",
	// 	}
	// });

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});

	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	});

	$(document).on('click', '#printPeriodSettings', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	});

	$(document).on('change', '#payroll_period', function (e) {
		e.preventDefault();

		// $("#period_id").val('');
		date = $(this).val().split("-");
		year = date[0] || null;
		month = date[1] || null;
		day = date[2] || null;
		$(this).val(year + '-' + month + '-01');
		if($(this).val() != '' && $(this).val() != null){
			$("#period_id").change();
		}
	});

	$(document).on('change', '#period_id', function (e) {
		e.preventDefault();
		date = $("#payroll_period").val().split("-");
		year = date[0] || null;
		month = date[1] || null;
		days = daysInMonth(month, year);
		cutoff = days / 2;
		
		
		 if($("#payroll_period").val() != '' && $("#payroll_period").val() != null) {
			switch ($(this).val()) {
				case 'Whole Period':
					$("#start_date").val(year + '-' + month + '-01');
					$("#end_date").val(year + '-' + month + '-' + days);
					break;
				case '1st Period':
					$("#start_date").val(year + '-' + month + '-01');
					$("#end_date").val(year + '-' + month + '-' + '15');
					break;
				case '2nd Period':
					$("#start_date").val(year + '-' + month + '-' + '16');
					$("#end_date").val(year + '-' + month + '-' + days);
					break;
				case '1st Week':
					setweekPeriod(0);				
					break;
				case '2nd Week':
					setweekPeriod(1);				
					break;
				case '3rd Week':
					setweekPeriod(2);				
					break;
				case '4th Week':
					setweekPeriod(3);				
					break;
				case '5th Week':
					setweekPeriod(4);				
					break;
				case '6th Week':
					setweekPeriod(5);				
					break;
				case 'Monthly Period':
					$("#start_date").bootstrapMaterialDatePicker({
						format: 'YYYY-MM-DD',
						clearButton: true,
						weekStart: 1,
						time: false
					});
					$("#end_date").bootstrapMaterialDatePicker({
						format: 'YYYY-MM-DD',
						clearButton: true,
						weekStart: 1,
						time: false
					});
				default:
					$("#start_date").val('');
					$("#end_date").val('');
					break;
			}
		 }
	});

	//Confirms
	$(document).on('click', '.activatePeriodSettings,.deactivatePeriodSettings,.activateWeeklyPeriodSettings,.deactivateWeeklyPeriodSettings', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activatePeriodSettings')) {
			content = 'Are you sure you want to activate selected period settings?';
		} else if (me.hasClass('deactivateSubPeriodSettings')) {
			content = 'Are you sure you want to deactivate selected sub period settings?';
		} else if (me.hasClass('activateWeeklyPeriodSettings')) {
			content = 'Are you sure you want to deactivate selected sub period settings?';
		} else if (me.hasClass('deactivateWeeklyPeriodSettings')) {
			content = 'Are you sure you want to deactivate selected sub period settings?';
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
													case 'activatePeriodSettings':
													case 'deactivatePeriodSettings':
													case 'activateWeeklyPeriodSettings':
													case 'deactivateWeeklyPeriodSettings':
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														$('#myModal').modal('hide');
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
	//Ajax non-forms
	$(document).on('click', '#addPeriodSettingsForm, .updatePeriodSettingsForm, #addWeeklyPeriodSettingsForm, .updateWeeklyPeriodSettingsForm, #weeklyPeriodSettingsForm', function (e) {
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
						case 'addPeriodSettings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;

						case 'addWeeklyPeriodSettings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$('#payroll_period_id').val(id);
							break;

						case 'generatePeriodSettings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'weeklyPeriodSettings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
							$('#myModal .modal-title').html('Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							loadTable();
							break;
						case 'updatePeriodSettings':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								console.log(i+" = "+v)
								$('.' + i).val(me.data(i)).change();
							});
							if (me.data("is_posted") == '1')
								$('.is_posted').prop('checked', true);
							if (me.data("is_semi_monthly") == '1')
								$('.is_semi_monthly').prop('checked', true);
							break;
						case 'updateWeeklyPeriodSettings':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Period Setting');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
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
	$(document).on('submit', '#addPeriodSettings,#updatePeriodSettings,#addWeeklyPeriodSettings,#updateWeeklyPeriodSettings', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addPeriodSettings") {
			content = "Are you sure you want to add period settings?";
		}
		if (form.attr('id') == "updatePeriodSettings") {
			content = "Are you sure you want to update period settings?";
		}


		if (form.attr('id') == "addWeeklyPeriodSettings") {
			content = "Are you sure you want to add period settings?";
		}
		if (form.attr('id') == "updateWeeklyPeriodSettings") {
			content = "Are you sure you want to update period settings?";
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
									data: form.serialize(),
									dataType: "json",
									success: function (result) {
										if (result.hasOwnProperty("key")) {
											if (result.Code == "0") {
												if (result.hasOwnProperty("key")) {
													switch (result.key) {
														case 'addPeriodSettings':
														case 'updatePeriodSettings':
														case 'addWeeklyPeriodSettings':
														case 'updateWeeklyPeriodSettings':
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

    $(document).on("click", "#btnsearch", function(){
        loadTable();
    });

    function loadTable(){
        $("#datatables_monthly").DataTable().clear().destroy();
        table = $('#datatables_monthly').DataTable({  
            "processing":true,  
            "serverSide":true,  
			"stateSave": true, // presumably saves state for reloads -- entries
			"bStateSave": true, // presumably saves state for reloads -- page number
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
              "targets"  : [0],
              "orderable": false
            }],
            initComplete : function() {
                
                var input = $('.dataTables_filter input').unbind(),
                self = this.api(),
                $searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
                .html('<i class="material-icons">search</i>')
                .click(function() {
                    
                    if(!$('#search-employee').is(':disabled')){
                        $('#search-employee').attr('disabled',true);
                        self.search(input.val()).draw();
                        $('#datatables button').attr('disabled',true);
                        $('.dataTables_filter').append('<div id="search-loader"><br>' 
                            +'<div class="preloader pl-size-xs">'
                            +    '<div class="spinner-layer pl-red-grey">'
                            +        '<div class="circle-clipper left">'
                            +            '<div class="circle"></div>'
                            +        '</div>'
                            +        '<div class="circle-clipper right">'
                            +            '<div class="circle"></div>'
                            +        '</div>'
                            +    '</div>'
                            +'</div>'
                            +'&emsp;Please Wait..</div>');
                    }

                })
                if	($("#search-employee").length === 0) {
					$('.dataTables_filter').append($searchButton);
				}
                
            },
            "drawCallback": function( settings ) {
                $('#search-loader').remove();
                $('#search-employee').removeAttr('disabled');
                $('#datatables button').removeAttr('disabled');
            },
            "ajax":{  
                url:commons.baseurl+ "fieldmanagement/PeriodSettings/fetchRows?status="+$("#status").val()+"&month="+$("#month").val(),
                type:"GET",
            },  
            oLanguage: {sProcessing: '<div class="preloader pl-size-sm">'
                                    +'<div class="spinner-layer pl-red-grey">'
                                    +    '<div class="circle-clipper left">'
                                    +        '<div class="circle"></div>'
                                    +    '</div>'
                                    +    '<div class="circle-clipper right">'
                                    +        '<div class="circle"></div>'
                                    +    '</div>'
                                    +'</div>'
                                    +'</div>'}
        });
    }

	// function loadTable() {
	// 	var url = window.location.href;
	// 	$.ajax({
	// 		url: url,
	// 		dataType: "json",
	// 		success: function (result) {
	// 			$("#table-holder-monthly").html(result.table_monthly);
	// 			table_monthly = $('#datatables_monthly').DataTable({
	// 				"pagingType": "full_numbers",
	// 				"lengthMenu": [
	// 					[10, 25, 50, -1],
	// 					[10, 25, 50, "All"]
	// 				],
	// 				aaSorting: [],
	// 				language: {
	// 					search: "_INPUT_",
	// 					searchPlaceholder: "Search records",
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	function daysInMonth (month, year) { // Use 1 for January, 2 for February, etc.
		return new Date(year, month, 0).getDate();
	}

	function setweekPeriod(key){
		var officiald = new Date($("#payroll_period").val());

	    var d = new Date($("#payroll_period").val()),
	        month = d.getMonth(),
	        mondays = [];
	        monday = "";
	    var fistdayvalid = d.getDay();
	    var fistDatevalid = d.getDate();
	    var pd = new Date(officiald.setMonth(officiald.getMonth()-1)),
	        pmonth = pd.getMonth(),
	        pmondays = [];
	        pmonday = "";
	    d.setDate(1);

	    // setter ng hahanapin na day every month
	    while (d.getDay() !== 1) {
	        d.setDate(d.getDate() + 1);
	        monday = d.setDate(d.getDate());
	    }
	     while (pd.getDay() !== 1) {
	        pd.setDate(pd.getDate() + 1);
	        pmonday = pd.setDate(pd.getDate());
	    }
	    // makuha lng lahat ng moday sa buwan ng prev, cur
	    while (d.getMonth() === month) {
	        mondays.push(new Date(d.getTime()));
	        d.setDate(d.getDate() + 7);
	    }
	    while (pd.getMonth() === pmonth) {
	        pmondays.push(new Date(pd.getTime()));
	        pd.setDate(pd.getDate() + 7);
	    }

	    var dateindexfrom = (new Date(pmondays[pmondays.length - 1])).toISOString();
	    var dateindexto = (new Date(pmondays[pmondays.length - 1].setDate(pmondays[pmondays.length - 1].getDate() + 4))).toISOString();
	    pd.setDate(pd.getDate() + 4);
	    var dataindexrev = (new Date(pmondays[pmondays.length - 1])).toISOString();
	    dateindexfrom = dateindexfrom.substring(0, 10);
	    dateindexto = dateindexto.substring(0, 10);
	    weeksarr = [];
	    if(	fistdayvalid != 1){
	        weeksarr = [
	                        {
	                            "from": dateindexfrom,
	                            "to": dateindexto
	                        }
	                    ]
	    }
		mondays.forEach(function(entry) {
			dateto  = new Date(entry).toISOString()
			dateform = new Date(entry.setDate(entry.getDate()+4)).toISOString()
			weeksarr.push({
			     "from": dateto.substring(0, 10),
			     "to": dateform.substring(0, 10)
			})
	    });

	    if(parseInt(key+1) <= weeksarr.length){
	    	$("#start_date").val(weeksarr[key].from)
	    	$("#end_date").val(weeksarr[key].to)
	    }else{
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Period not allowed"
			});
	    }


	}

})
