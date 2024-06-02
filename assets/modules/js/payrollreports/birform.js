

$(function() {

    let currentYear = new Date().getFullYear();    
	let earliestYear = 2019;     
	while (currentYear >= earliestYear) {      
	   $('#year_select').append(
		'<option value="'+currentYear+'">'+currentYear+'</option>'
	   );
	   currentYear -= 1;   
	}

    var update_flag = 0;
    base_url = commons.base_url;

    $(document).on("click", "#viewBirFormSummary", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        loadTable(url);
    });

    $.when(getFields.division(), getFields.payBasis3(), getFields.salary_grade(), getFields.positionName()).done(function() {
        $("#division_id option:first").text("All");
        $("#location_id option:first").text("All");
        $("#division_id option:first").val("");
        $("#location_id option:first").val("");

        $("#pay_basis option:first").text("All");
        $("#payroll_grouping_id option:first").text("All");
        $("#leave_grouping_id option:first").text("All");

        $("#pay_basis option:first").val("");
        $("#payroll_grouping_id option:first").val("");
        $("#leave_grouping_id option:first").val("");

        /*$('#payroll_period_id').change();*/
        $.AdminBSB.select.activate();
    });

	$(document).on('click', '#viewbirForm', function(e){
		e.preventDefault();
		my = $(this);
		url = my.attr("href");
        var pay_basis = $(this).data('pay_basis');
        var employee_id = $(this).data('employee_id');
        //console.log(pay_basis);
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
			"payrollreports/BIRform/viewbirForm",
        data: {
            employee_id: employee_id
        },
		dataType: "json",
		success: function (result) {
			page = my.attr("id");
			if (result.hasOwnProperty("key")) {
				switch (result.key) {
					case "viewbirForm":
						page = "";
						$("#myModal .modal-dialog").attr(
							"class",
							"modal-dialog modal-lg"
						);
						$("#myModal .modal-dialog").css("width", "100%");
                        
						$("#myModal .modal-body").html(result.form);
						$("#division_label").html(
							$("#division_id option:selected").text()
						);
						break;
                   
				}
			}
            $.when(getFields.payrollperiodcutoff(pay_basis)).done(function () {
                $.AdminBSB.select.activate();
            });
		},
		error: function (result) {
			errorDialog();
		},
	});

	})

});

function loadTable(url) {
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
                            "payrollreports/BIRform/fetchRows?dateYear=" +
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
}

function reloadTable() {
    $("#datatables").DataTable().ajax.reload();
}