$(function () {
	var page = "";
	base_url = commons.base_url;
	table_gsis = $('#datatables-deductionoptions').DataTable({
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

	$('.timepicker').bootstrapMaterialDatePicker({
		format: 'HH:mm',
		clearButton: true,
		date: false,
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
	});

	$("#addImportFromPCBasePayrollSetup").validate({
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

	var files = [];

	Dropzone.options.myDropzone = {
		createImageThumbnails: false,
		autoProcessQueue: false,
		uploadMultiple: false,
		parallelUploads: 1,
		maxFiles: 3,
		timeout: 0,
		chunking: true,
		chunkSize: 2097152,
		retryChunks: true,
		retryChunksLimit: 3,
		acceptedFiles: ".mdb",
		chunksUploaded: (file, done) => {
			done()
		},
		init: function () {
			var submitButton = document.querySelector("#importRawDTR")
			myDropzone = this;
			submitButton.addEventListener("click", function () {
				if (myDropzone.getQueuedFiles().length < 3) {
					$.alert({
						title: '<label class="text-danger">Import Failed</label>',
						content: 'Please upload the (3) required files to proceed.'
					});
				} else {
					myDropzone.processQueue();
				}
			});

			this.on("success", function (file) {
				console.log(file);
				// files.push(file);
				myDropzone.processQueue();
			});

			this.on("maxfilesexceeded", function (file) {
				myDropzone.removeFile(file);
			});

			this.on('uploadprogress', (file, progress, bytesSent) => {
				if (file.upload.chunked &&
					progress === 100 &&
					!!(file.upload.chunks.length < file.upload.totalChunkCount)
				) return
			})

			this.on("queuecomplete", function (file) {
				url = commons.baseurl + '/timekeeping/ImportFromPCBase/readFileContents';
				$.ajax({
					url: url,
					async: false,
					success: function (result) {
						if(result == 'true') {
							$.alert({
								title: '<label class="text-primary">Done</label>',
								content: 'Queue Completed.'
							});
						} else {
							$.alert({
								title: '<label class="text-danger">Failed</label>',
								content: 'Files uploaded are not compatible.'
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
				myDropzone.removeAllFiles();
			});
		}
	};

	$(document).on('click', '#clear-dropzone', function (e) {
		e.preventDefault();
		myDropzone.removeAllFiles();
	})

	$(document).on('click', '#printImportFromPCBasePayrollSetup', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	})
	//Confirms
	$(document).on('click', '.activateImportFromPCBasePayrollSetup,.deactivateImportFromPCBasePayrollSetup,.activateImportFromPCBaseDeductionOptions,.deactivateImportFromPCBaseDeductionOptions', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		content = 'Are you sure you want to proceed?';
		if (me.hasClass('activateImportFromPCBasePayrollSetup')) {
			content = 'Are you sure you want to activate selected payroll setup?';
		} else if (me.hasClass('deactivateSubImportFromPCBasePayrollSetup')) {
			content = 'Are you sure you want to deactivate selected payroll setup?';
		} else if (me.hasClass('activateImportFromPCBaseDeductionOptions')) {
			content = 'Are you sure you want to deactivate selected deduction option?';
		} else if (me.hasClass('deactivateSubImportFromPCBaseDeductionOptions')) {
			content = 'Are you sure you want to deactivate selected deduction option?';
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
													case 'activateImportFromPCBaseDeductionOptions':
													case 'deactivateImportFromPCBaseDeductionOptions':
													case 'activateImportFromPCBasePayrollSetup':
													case 'deactivateImportFromPCBasePayrollSetup':
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
	$(document).on('click', '#addImportFromPCBasePayrollSetupForm,.updateImportFromPCBasePayrollSetupForm,#addImportFromPCBaseDeductionOptionsForm,.updateImportFromPCBaseDeductionOptionsForm', function (e) {
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
						case 'addImportFromPCBasePayrollSetup':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New GSIS Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateImportFromPCBasePayrollSetup':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update GSIS Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
							break;
						case 'addImportFromPCBaseDeductionOptions':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New PhilHealth Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateImportFromPCBaseDeductionOptions':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update PhilHealth Contribution');
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
	// Ajax Forms
	$(document).on('submit', '#addImportFromPCBasePayrollSetup,#updateImportFromPCBasePayrollSetup,#addImportFromPCBaseDeductionOptions,#updateImportFromPCBaseDeductionOptions', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addImportFromPCBasePayrollSetup") {
			content = "Are you sure you want to import raw DTR?";
		}
		if (form.attr('id') == "updateImportFromPCBasePayrollSetup") {
			content = "Are you sure you want to import raw DTR?";
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
														case 'addImportFromPCBaseDeductionOptions':
														case 'updateImportFromPCBaseDeductionOptions':
														case 'addImportFromPCBasePayrollSetup':
														case 'updateImportFromPCBasePayrollSetup':
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
				$("#table-holder-deductionoptions").html(result.table_deduction_options);
				table_philhealth = $('#datatables-deductionoptions').DataTable({
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
