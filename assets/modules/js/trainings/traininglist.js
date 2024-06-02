$(function () {
	var base_url = commons.baseurl;

	// Ajax Forms
	$(document).on('click', '.search_btn', function (e) {
		var url = '';
		loadTable();
	});

	// ajax non-forms
	$(document).on(
		"click",
		".viewTrainingListForm, .viewTrainingForm",
		function (e) {
			e.preventDefault();
			my = $(this);
			data = my.data();
			id = my.attr("id");
			url = my.attr("href");

			if (!my.find("button").is(":disabled")) {
				getFields.reloadModal();
				$.ajax({
					type: "POST",
					url: url,
					data: {
						id: id,
						seminar_id: data.id
					},
					dataType: "json",
					success: function (result) {
						page = my.attr("id");
						if (result.hasOwnProperty("key")) {
							status_key = result.key;
							switch (status_key) {
								case "viewTraining":                                    
                                    page = "";
                                    $("#myModal .modal-dialog").attr(
                                        "class",
                                        "modal-dialog modal-lg"
                                    );
                                    $("#myModal .modal-title").html(
                                        'Print Preview <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success pull-right"> Print <i class="material-icons">print</i></button>'
                                    );
                                    $("#myModal .modal-body").html(result.form);
                                    $(".modal-lg").css("width", "1060px");
                                    break;
								case "viewTrainingList":
									page = "";
									$("#myModal .modal-dialog").attr(
										"class",
										"modal-dialog modal-lg"
									);
									$("#myModal .modal-title").html(
										'Trainings Details'
									);
									$("#myModal .modal-body").html(result.form);
									$(".modal-lg").css("width", "1060px");
									// $('#employee_ms').multiSelect();
									$.when(
										$.ajax({
											url: commons.baseurl +
												"trainings/TrainingList/getTrainingListAttendees",
											data: {
												seminar_id: data.id
											},
											type: "POST",
											dataType: "json",
											success: function(result) {
												if (result.Code === "0") {
													var selectedAttendees = result.Data.map(function(attendee) {
														return attendee.employee_id;
													});									
													$('#employee_ms').val(selectedAttendees).multiSelect('refresh');
												}
											},
										})
									).done( function () {
										$.each(my.data(),function(i,v){
											$('#myModal #' + i).val(my.data(i)).change();
											$('#myModal .' + i).attr('style', 'pointer-events: none;');
										});
										$('#select-all').hide();
										$('#deselect-all').hide();
									});
									break;
							}
							// $.when(
								
							// ).done(function () {
							// 	$.each(my.data(), function (i, v) {
							// 		if (parseInt($("#printPreview").length) > 0) {
							// 			$("#myModal ." + i).html(my.data(i));
							// 		} else {
							// 			$("#myModal ." + i)
							// 				.val(my.data(i))
							// 				.change();
							// 		}
							// 	});
							// });
						}
					},
					error: function (result) {
						$.alert({
							title: '<label class="text-danger">Failed</label>',
							content:
								"There was an error in the connection. Please contact the administrator for updates.",
						});
					},
				});
			}
		}
	);

	$(document).on("click", "#btnPrintDetails", function () {
		var mywindow = window.open("", "PRINT", "height=400,width=600");

		mywindow.document.write(
			"<html moznomarginboxes mozdisallowselectionprint><head>"
		);
		mywindow.document.write("</head><body >");
		mywindow.document.write(document.getElementById("printPreview").innerHTML);
		mywindow.document.write("</body></html>");

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();
	});
	
});

function loadTable() {
	plus_url = "";
	var year = $('#search_year').val();
    plus_url = "?Year=" + year;
	$("#datatables").DataTable().clear().destroy();
	table = $("#datatables").DataTable({
		processing: true,
		serverSide: true,
		order: [],
		scroller: {
			displayBuffer: 20,
		},
		columnDefs: [
			{
				targets: [-1],
				orderable: false,
			},
		],
		initComplete: function () {
			var input = $('.dataTables_filter input').unbind(),
				self = this.api(),
				$searchButton = $(
					'<button id="search-manage-trainings" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
				)
					.html('<i class="material-icons">search</i>')
					.click(function () {
						if (!$("#search-manage-trainings").is(":disabled")) {
							$("#search-manage-trainings").attr("disabled", true);
							self.search(input.val()).draw();
							$("#datatables button").attr("disabled", true);
							$(".dataTables_filter").append(
								'<div id="search-loader"><br>' +
								'<div class="preloader pl-size-xs">' +
								'<div class="spinner-layer pl-red-grey">' +
								'<div class="circle-clipper left">' +
								'<div class="circle"></div>' +
								"</div>" +
								'<div class="circle-clipper right">' +
								'<div class="circle"></div>' +
								"</div>" +
								"</div>" +
								"</div>" +
								"&emsp;Please Wait..</div>"
							);
						}
					});
				
				if	($("#search-manage-trainings").length === 0) {
					$('.dataTables_filter').append($searchButton);
				}

		},
		drawCallback: function (settings) {
			$('#search-loader').remove();
			$('#search-manage-trainings').removeAttr('disabled');
			$('#datatables button').removeAttr('disabled');
		},
		ajax: {
			url: commons.baseurl + "trainings/TrainingList/fetchRows" + plus_url,
			type: 'POST',
		},
		oLanguage: {
			sProcessing:
				'<div class="preloader pl-size-sm">' +
				'<div class="spinner-layer pl-red-grey">' +
				'<div class="circle-clipper left">' +
				'<div class="circle"></div>' +
				"</div>" +
				'<div class="circle-clipper right">' +
				'<div class="circle"></div>' +
				"</div>" +
				"</div>" +
				"</div>",
		},
	});
}
