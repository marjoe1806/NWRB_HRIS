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

	$("#addImportDTRFromDevicePayrollSetup").validate({
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


	Dropzone.options.myDropzone = {
		createImageThumbnails: false,
		autoProcessQueue: false,
		uploadMultiple: true,
		parallelUploads: 1,
		maxFiles: 20,
		timeout: 0,
		acceptedFiles: ".dat, .mdb",
		init: function () {
			var submitButton = document.querySelector("#importRawDTR")
			myDropzone = this;
			submitButton.addEventListener("click", function () {
				if (myDropzone.getQueuedFiles().length === 0) {
					$.alert({
						title: '<label class="text-danger">Import Failed</label>',
						content: 'Please drop or select file to upload.'
					});
				} else {
					myDropzone.processQueue();
				}
			});

			this.on("success", function (file) {
				myDropzone.processQueue();
			});

			this.on("maxfilesexceeded", function (file) {
				myDropzone.removeFile(file);
			});

			this.on("error", function(file, message, xhr) {
				var header = xhr.status+": "+message.error;
				$(file.previewElement).find('.dz-error-message').text(header);
			});

		}
	};

	$(document).on('click', '#clear-dropzone', function (e) {
		e.preventDefault();
		myDropzone.removeAllFiles();
	})


	//Ajax non-forms
	$(document).on('click', '#addImportDTRFromDevicePayrollSetupForm,.updateImportDTRFromDevicePayrollSetupForm,#addImportDTRFromDeviceDeductionOptionsForm,.updateImportDTRFromDeviceDeductionOptionsForm', function (e) {
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
						case 'addImportDTRFromDevicePayrollSetup':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New GSIS Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateImportDTRFromDevicePayrollSetup':
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Update GSIS Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							$.each(me.data(), function (i, v) {
								$('.' + i).val(me.data(i)).change();
							});
							break;
						case 'addImportDTRFromDeviceDeductionOptions':
							page = "";
							$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
							$('#myModal .modal-title').html('Add New PhilHealth Contribution');
							$('#myModal .modal-body').html(result.form);
							$('#myModal').modal('show');
							break;
						case 'updateImportDTRFromDeviceDeductionOptions':
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
	$(document).on('submit', '#addImportDTRFromDevicePayrollSetup,#updateImportDTRFromDevicePayrollSetup,#addImportDTRFromDeviceDeductionOptions,#updateImportDTRFromDeviceDeductionOptions', function (e) {
		e.preventDefault();
		var form = $(this)
		content = "Are you sure you want to proceed?";
		if (form.attr('id') == "addImportDTRFromDevicePayrollSetup") {
			content = "Are you sure you want to import raw DTR?";
		}
		if (form.attr('id') == "updateImportDTRFromDevicePayrollSetup") {
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
														case 'addImportDTRFromDeviceDeductionOptions':
														case 'updateImportDTRFromDeviceDeductionOptions':
														case 'addImportDTRFromDevicePayrollSetup':
														case 'updateImportDTRFromDevicePayrollSetup':
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


})
