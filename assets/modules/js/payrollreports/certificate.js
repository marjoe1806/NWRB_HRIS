$(function() {
    var page = "";
    base_url = commons.base_url;
    var table;
    // loadTable();

    $.when(getFields.loan(), getFields.payBasis3(), getFields.division()).done(function() {
        $('#loans_id').append(new Option('PhilHealth', '0'));
        $("#loans_id option[value='3']").remove();
        $("#loans_id option[value='4']").remove();
        // $("#loans_id option[value='4']").remove();
        // $("#loans_id option[value='5']").remove();
        // $("#division_id option:first").text("All");
        // $("#division_id option:first").val("");
        // $("#pay_basis")
        // 	.val("Permanent-Casual")
        // 	.change();
        $.AdminBSB.select.activate();
        // $("#loans_id").selectpicker("refresh");
    });

    // $(document).on('change','#pay_basis',function(e){
    //     pay_basis = $(this).val();
    //     $.when(
    //         getFields.payrollperiodcutoff(pay_basis)
    //     ).done(function(){
    //         $('.payroll_period_id').val(payroll_period_id);
    //         $.AdminBSB.select.activate();  
    //     })
    // });
    $(document).on("change", "#loans_id", function() {
        id = $(this).val();
        pay_basis = $("form #pay_basis").val();
        if (id == 0) {
            $('.sub_loan').css('display', 'none');
        } else {
            $('.sub_loan').css('display', 'block');
            $.when(getFields.subloan(id)).done(function() {
                $("#sub_loans_id").val(sub_loans_id).change();
                if (id == "0") {
                    $(".sub_loan").css("display", "none");
                }
                if (id == "1") {
                    $(".sub_loan").css("display", "block");
                    $("#sub_loans_id option[value='3']").remove();
                    $("#sub_loans_id option[value='4']").remove();
                    $("#sub_loans_id option[value='5']").remove();
                    $("#sub_loans_id option[value='6']").remove();
                    $("#sub_loans_id option[value='7']").remove();
                    $("#sub_loans_id option[value='8']").remove();
                    $("#sub_loans_id option[value='14']").remove();
                    $("#sub_loans_id option[value='15']").remove();
                    $("#sub_loans_id option[value='16']").remove();
                    $("#sub_loans_id option[value='17']").remove();
                    $("#sub_loans_id option[value='18']").remove();
                    $("#sub_loans_id option[value='35']").remove();
                    $("#sub_loans_id option[value='36']").remove();
                }
                if (id == "2") {
                    $(".sub_loan").css("display", "block");
                    $("#sub_loans_id option[value='34']").remove();
                    $("#sub_loans_id option[value='37']").remove();
                    $("#sub_loans_id option[value='20']").remove();
                    $("#sub_loans_id option[value='22']").remove();
                    $('#sub_loans_id').append(new Option('MP2 Contribution', '0'));
                }
                $.AdminBSB.select.activate();
            });
        }
    });

    $(".datepicker").bootstrapMaterialDatePicker({
        format: "YYYY-MM-DD",
        clearButton: true,
        weekStart: 1,
        time: false
    });

    $(document).on("show.bs.modal", "#myModal", function() {
        $(".datepicker").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            time: false
        });
        $('[data-toggle="popover"]').popover();
    });

    $(document).on("click", function(e) {
        $('[data-toggle="popover"],[data-original-title]').each(function() {
            if (!$(this).is(e.target) &&
                $(this).has(e.target).length === 0 &&
                $(".popover").has(e.target).length === 0
            ) {
                (
                    (
                        $(this)
                        .popover("hide")
                        .data("bs.popover") || {}
                    ).inState || {}
                ).click = false;
            }
        });
    });
    $(document).on("click", "#printClearance", function(e) {
        e.preventDefault();
        printPrev(document.getElementById("clearance-div").innerHTML);
    });

    $(document).on("keypress keyup keydown", "form #amount", function(e) {
        $("form #balance").val($(this).val());
    });

    // $(document).on("change", "#pay_basis ", function(e) {
    // 	pay_basis = $(this).val();
    // 	$.when(getFields.payrollperiodcutoff(pay_basis)).done(function() {
    // 		$.AdminBSB.select.activate();
    // 	});
    // });

    const addCommas = x => {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    };

    $(document).on("click", ".viewCertificateForm", function(e) {
        e.preventDefault();
        my = $(this);
        console.log(my.data());
        id = my.attr("data-id");
        url = my.attr("href");
        employee_id = my.data("employee_id");
        loans_id = $(".search_entry #loans_id").val();
        sub_loans_id = $(".search_entry #sub_loans_id").val();
        pay_basis = $(".search_entry #pay_basis").val();
        payroll_period_id = $(".search_entry #payroll_period_id").val();
        certificate_type = $(".search_entry #certificate_type").val();
        cutoff_id = $(".search_entry #cutoff_id").val();
        payroll = my.data();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                loans_id: loans_id,
                sub_loans_id: sub_loans_id,
                pay_basis: pay_basis,
                payroll_period_id: payroll_period_id,
                employee_id: employee_id,
                cutoff_id: cutoff_id,
                certificate_type: certificate_type,
                key: "viewCertificate"
            },
            dataType: "json",
            success: function(result) {
                page = my.attr("id");
                if (result.hasOwnProperty("key")) {
                    if (result.key === "viewCertificate") {
                        page = "";
                        $("#myModal .modal-dialog").attr("class", "modal-dialog modal-lg");
                        modal_title = my.data("employee_id_number") + " - " + my.data("last_name") + ", " + my.data("first_name") + " " + my.data("middle_name") + " " + my.data("extension");
                        $("#myModal .modal-title").html(modal_title);
                        $("#myModal .modal-body").html(result.form);
                        $("#myModal").modal("show");
                        // $.each(my.data(), function(i, v) {
                        // 	$("." + i).val(addCommas(v));
                        // });
                        $("form input:not(:button),form input:not(:submit)").attr(
                            "disabled",
                            true
                        );
                    }
                    $("#" + result.key).validate({
                        rules: {
                            ".required": { required: true },
                            ".email": { required: true, email: true }
                        },
                        highlight: function(input) {
                            $(input)
                                .parents(".form-line")
                                .addClass("error");
                        },
                        unhighlight: function(input) {
                            $(input)
                                .parents(".form-line")
                                .removeClass("error");
                        },
                        errorPlacement: function(error, element) {
                            $(element)
                                .parents(".form-group")
                                .append(error);
                        }
                    });
                }
            },
            error: function(result) {
                errorDialog();
            }
        });
    });

    $(document).on("click", "#viewCertificateSummary", function(e) {
        e.preventDefault();
        loans_id = $(".search_entry #loans_id").val();
        sub_loans_id = $(".search_entry #sub_loans_id").val();
        payroll_period_id = $(".search_entry #payroll_period_id").val();
        location_id = $(".search_entry #location_id").val();
        payroll_grouping_id = $(".search_entry #payroll_grouping_id").val();
        division_id = $(".search_entry #division_id").val();
        if (loans_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Category."
            });
            return false;
        } else if (sub_loans_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Type."
            });
            return false;
        } else if (payroll_period_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Period."
            });
        } else if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Department."
            });
        } else if (location_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Location."
            });
        } else {
            getFields.employeeModal(
                loans_id,
                sub_loans_id,
                location_id,
                division_id,
                null,
                1,
                null,
                payroll_grouping_id
            );
        }
    });

    //View Payroll Register
    // $(document).on("click", "#submitUserCheckList", function(e) {
    // 	e.preventDefault();
    // 	certificate_type = $(".search_entry #certificate_type").val();
    // 	payroll_period_id = $(".search_entry #payroll_period_id").val();
    // 	cutoff_id = $(".search_entry #cutoff_id").val();
    // 	myBtn = $(this);
    // 	$(this).attr("disabled", "disabled");
    // 	$.confirm({
    // 		title: '<label class="text-warning">Confirm!</label>',
    // 		content: "Are you sure you want to process certificate?",
    // 		type: "orange",
    // 		buttons: {
    // 			confirm: {
    // 				btnClass: "btn-blue",
    // 				action: function() {
    // 					var progressbar =
    // 						'<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
    // 						"0% Complete (success)" +
    // 						"</div></div>";
    // 					total_data = $(".checkbox_table:checked").length;
    // 					if (total_data > 0) {
    // 						$("#myModal #progress-container").html(progressbar);
    // 						$.ajax({
    // 							type: "POST",
    // 							url:
    // 								commons.baseurl + "payrollreports/Certificate/certificateContainer",
    // 							dataType: "json",
    // 							success: function(result) {
    // 								if (result.hasOwnProperty("key")) {
    // 									if (result.key === "certificateContainer") {
    // 										$("#myModal .modal-dialog").attr(
    // 											"class",
    // 											"modal-dialog modal-lg"
    // 										);
    // 										modal_title = "Certificate Summary";
    // 										$("#myModal .modal-title").html(modal_title);
    // 										$("#myModal .modal-body").append(result.form);
    // 										$("#module")
    // 											.DataTable()
    // 											.$('input[type="checkbox"]:checked')
    // 											.each(function(k, v) {
    // 												employee_id = $(this).attr("data-id");
    // 												queue = getEmployeeCertificate(
    // 													payroll_period_id,
    // 													employee_id,
    // 													k + 1
    // 												);
    // 												if (queue) {
    // 													percentage =
    // 														100 - ((total_data - (k + 1)) / total_data) * 100;
    // 													$(".progress-bar")
    // 														.attr("aria-valuenow", percentage)
    // 														.css("width", percentage + "%")
    // 														.html(
    // 															percentage.toFixed() + "% Complete (success)"
    // 														);
    // 													if (total_data == k + 1)
    // 														$("#employee-checklists").remove();
    // 												} else {
    // 													errorDialog();
    // 													$("#myModal").modal("hide");
    // 													return false;
    // 												}
    // 											});
    // 									}
    // 								}
    // 							},
    // 							error: function(result) {
    // 								errorDialog();
    // 							}
    // 						});
    // 					} else {
    // 						$.alert({
    // 							title: '<label class="text-danger">Failed</label>',
    // 							content: "Please select Employees."
    // 						});
    // 						myBtn.removeAttr("disabled");
    // 					}
    // 				}
    // 			},
    // 			cancel: function() {
    // 				myBtn.removeAttr("disabled");
    // 			}
    // 		}
    // 	});
    // });

    //Ajax Forms
    $(document).on("click", "#searchCertificate", function(e) {
        e.preventDefault();
        // loadTable();
        my = $(this);
        url = my.attr("href");
        loans_id = $(".search_entry #loans_id").val();
        sub_loans_id = $(".search_entry #sub_loans_id").val();
        pay_basis = $(".search_entry #pay_basis").val();
        payroll_period_id = $(".search_entry #payroll_period_id").val();
        location_id = $(".search_entry #location_id").val();
        division_id = $(".search_entry #division_id").val();
        if (loans_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Category."
            });
            return false;
        }
        if (sub_loans_id == "" && loans_id == 1) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Type."
            });
            return false;
        }
        if (pay_basis == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Pay Basis."
            });
            return false;
        }
        if (payroll_period_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select payroll period."
            });
            return false;
        }
        if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Department."
            });
            return false;
        }
        if (location_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select location."
            });
            return false;
        }
        if (division_id != "") {
            var plus_url =
                "?LoanId=" +
                loans_id +
                "&SubLoanId=" +
                sub_loans_id +
                "&PayBasis=" +
                pay_basis +
                // "&PayrollPeriod=" +
                // payroll_period_id +
                "&DivisionId=" +
                division_id;
        } else {
            var plus_url =
                "?LoanId=" +
                loans_id +
                "&SubLoanId=" +
                sub_loans_id +
                "&PayBasis=" +
                pay_basis;
            "&PayrollPeriod=" +
            payroll_period_id;
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(result) {
                $("#table-holder").html(result.table);
                table = $("#datatables").DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    order: [],
                    search: {
                        return: true,
                    },
                    //Set column definition initialisation properties.
                    "columnDefs": [{
                            "searchable": true,
                            "targets": [1, 2, 3, 4, 5, 6], //first column / numbering column 
                        },
                        {
                            "targets": [0], //first column / numbering column
                            "orderable": false, //set not orderable
                        },

                    ],
                    initComplete: function() {
                        var input = $(".dataTables_filter input").unbind(),
                            self = this.api(),
                            $searchButton = $(
                                '<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                            )
                            .html('<i class="material-icons">search</i>')
                            .click(function() {
                                if (!$("#search-employee").is(":disabled")) {
                                    $("#search-employee").attr("disabled", true);
                                    $("#datatables button").attr("disabled", true);
                                    self.search(input.val()).draw();
                                }
                            });
                        if ($("#search-employee").length === 0) {
                            $('.dataTables_filter').append($searchButton);
                        }
                    },
                    drawCallback: function(settings) {
                        $("#search-loader").remove();
                        $("#search-employee").removeAttr("disabled");
                        $("#datatables button").removeAttr("disabled");
                    },
                    ajax: {
                        url: commons.baseurl + "payrollreports/Certificate/fetchRows" + plus_url,
                        type: "POST",
                    },

                });

                button =
                    '<a id="viewCertificateSummary" href="' +
                    commons.baseurl +
                    'payrollreports/Certificate/viewCertificateSummary">' +
                    '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
                    '<i class="material-icons">people</i> Certificate Summary' +
                    "</button>" +
                    "</a>";
                $("#table-holder .button-holder").html(button);
            },
            error: function(result) {
                errorDialog();
            }
        });
    });

    function getEmployeeCertificate(payroll_period_id, employee_id, queue) {
        url2 = commons.baseurl + "payrollreports/Certificate/viewCertificate/";
        $.ajax({
            type: "POST",
            url: url2,
            async: false,
            data: {
                payroll_period_id: payroll_period_id,
                employee_id: employee_id,
                key: "viewCertificateSummary"
            },
            dataType: "json",
            success: function(result) {
                if (result.hasOwnProperty("key")) {
                    if (result.key === "viewCertificateSummary") {
                        $("#myModal #certificate-container").append(result.form);
                        queue += 1;
                    }
                }
            },
            error: function(result) {
                queue = false;
            }
        });
        return queue;
    }

    function loadTable() {
        plus_url = "";
        $("#datatables").DataTable({
            processing: true,
            serverSide: true,
            // stateSave: true, // presumably saves state for reloads -- entries
            // bStateSave: true, // presumably saves state for reloads -- page number
            order: [
                [2, 'asc'],
            ],
            ajax: {
                url: commons.baseurl + "payrollreports/Certificate/fetchRows" + plus_url,
                type: "POST"
            },

            columnDefs: [{
                targets: [0, 6],
                orderable: false
            }]
        });
    }

    function reloadTable() {
        employee_id = $(".search_entry #employee_id").val();
        plus_url = "?EmployeeId=" + employee_id;
        if (employee_id == "") {
            return false;
        }
        $("#searchEmployeePayroll").click();
    }

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
});