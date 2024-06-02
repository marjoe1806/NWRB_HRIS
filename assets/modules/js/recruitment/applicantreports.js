$(function () {
    var page = "";
	base_url = commons.base_url;
	var table;
	// loadTable();
	$.when(
        // get fields for dropdown filter options
		getFields.applicantStatus(),
		getFields.vacant_job()
    ).done(function () {

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

    //View Applicant Report
    $(document).on("click", "#viewApplicantReportsSummary", function (e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        status = $(".search_entry #application_status").val();
        vacancy_id = $(".search_entry #vacancy_id").val();
        if (status == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Status.",
            });
        } else {
            loader =
                '<div id="btn-loader" class="preloader pl-size-xl" style="left:44%";>' +
                '<div class="spinner-layer pl-blue">' +
                '<div class="circle-clipper left">' +
                '<div class="circle"></div>' +
                "</div>" +
                '<div class="circle-clipper right">' +
                '<div class="circle"></div>' +
                "</div>" +
                "</div>" +
                "</div>";

            modal_title = "Applicant Report";
            $("#myModal .modal-title").html(modal_title);
            $("#myModal .modal-body").html(loader);
            $("#myModal").modal("show");

            $.ajax({
                type: "POST",
                url:
                    commons.baseurl +
                    "recruitment/ApplicantReports/viewApplicantReportsSummary",
                data: {
                    status: status,
					vacancy_id: vacancy_id
                },
                dataType: "json",
                success: function (result) {
                    page = my.attr("id");
                    if (result.hasOwnProperty("key")) {
                        switch (result.key) {
                            case "viewApplicantReportsSummary":
                                page = "";
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-dialog").css("width", "90%");
                                $("#myModal .modal-body").html(result.form);
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

	// Hide 'visible columns' option when opening page
	$(document).ready(function() {
		$("#table-column-toggle-checkbox").hide();
	});
});
