$(function () {
	var page = "";
	base_url = commons.base_url;
	// var table;
	// table = $('#datatables').DataTable({
	// 	"pagingType": "full_numbers",
	// 	"lengthMenu": [
	// 		[10, 25, 50, -1],
	// 		[10, 25, 50, "All"]
	// 	],
	// 	responsive: true,
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
		maxDate: new Date(),
		time: false
	});
	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			maxDate: new Date(),
			time: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	})
	$(document).on('click', '#printOtherEarnings', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})
	//Confirms
	$(document).on('click', '.activateOtherEarnings,.deactivateOtherEarnings', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateOtherEarnings')) {
			content = 'Are you sure you want to activate selected other earning?';
		} else if (me.hasClass('deactivateSubOtherEarnings')) {
			content = 'Are you sure you want to deactivate selected sub other earning?';
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
													case 'activateOtherEarnings':
													case 'deactivateOtherEarnings':
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
	//Ajax non-forms
	$(document).on('click', '#addOtherEarningsForm,.updateOtherEarningsForm', function (e) {
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
						case 'addOtherEarnings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New Other Earning Record');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'generateOtherEarnings':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Other Earning Record');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateOtherEarnings':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update Other Earning Record');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								if(i != "is_taxable")
									$('.' + i).val(me.data(i)).change();
							});
							if(me.data("is_taxable") == '1') $('.is_taxable').prop('checked', true);
							$.AdminBSB.select.activate();

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
	$(document).on('submit', '#addOtherEarnings,#updateOtherEarnings', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addOtherEarnings") {
			content = "Are you sure you want to add other earning?";
		}
		if (form.attr('id') == "updateOtherEarnings") {
			content = "Are you sure you want to update other earning?";
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
														case 'addOtherEarnings':
														case 'updateOtherEarnings':
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
        $("#datatables").DataTable().clear().destroy();
        table = $('#datatables').DataTable({  
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
                url:commons.baseurl+ "fieldmanagement/OtherEarnings/fetchRows?status="+$("#status").val(),
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
