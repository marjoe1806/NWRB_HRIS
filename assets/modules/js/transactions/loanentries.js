$(function() {
    var page = "";
    base_url = commons.base_url;
    var table;
    var tbl_url = commons.baseurl + "transactions/LoanEntries/fetchRows";
    // loadTable();
    $.when(
        getFields.month(),
        // getFields.location()
        //getFields.division(),
        getFields.payBasis3()
    ).done(function() {
        $.AdminBSB.select.activate();
    });
    $(".datepicker").bootstrapMaterialDatePicker({
        format: "YYYY-MM-DD",
        clearButton: true,
        weekStart: 1,
        time: false,
    });
    $(document).on("show.bs.modal", "#myModal", function() {
        $(".datepicker").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            time: false,
        });
        //$.AdminBSB.input.activate();
    });
    $(document).on("click", "#printLoanEntries", function(e) {
        e.preventDefault();
        PrintElem("servicerecords-container");
    });
    $(document).on("keypress keyup keydown", "form #loan_amount", function(e) {
        $("form #loan_balance").val($(this).val());
        computeRMTP();
    });
    $(document).on("keypress keyup keydown", "form #loan_balance", function(e) {
        computeRMTP();
    });
    $(document).on(
        "keypress keyup keydown",
        "form #amortization_per_month",
        function(e) {
            computeRMTP();
        }
    );

    $(document).on("change", "#is_active", function(e) {
        status = $(this).val();
        if (status == 1) {
            $(".status_icon").removeClass("text-danger");
            $(".status_icon").addClass("text-success");
            $(".status_icon i").html("check_circle");
        } else {
            $(".status_icon").removeClass("text-success");
            $(".status_icon").addClass("text-danger");
            $(".status_icon i").html("remove_circle");
        }
    });

    var pay_basis;
    var division_id;
    var payroll_period_id;
    var employee_id;

    $(document).on("change", "form #pay_basis", function(e) {
        pay_basis = $(this).val();
        division_id = $("form #division_id").val();

        $(".search_entry #pay_basis").val(pay_basis).change();

        $.when(getFields.employee(pay_basis, division_id)).done(function() {
            $("form #employee_id").val(employee_id).change();
            $.AdminBSB.select.activate();
        });

        $.when(getFields.payrollperiodcutoff(pay_basis)).done(function() {
            $("form #payroll_period_id").val(payroll_period_id);
            $.AdminBSB.select.activate();

            getEmployeeList(pay_basis, division_id);
        });

        $.when(getFields.loan()).done(function() {
            $("form #loans_id").val(loans_id).change();
            $.AdminBSB.select.activate();
        });
    });

    // $(document).on("change", "form #division_id", function (e) {
    // 	division_id = $(this).val();
    // 	pay_basis = $("form #pay_basis").val();
    // 	getEmployeeList(pay_basis, division_id);

    // 	$(".search_entry #division_id").val(division_id).change();
    // });

    // $(document).on("change", "form #payroll_period_id", function (e) {
    // 	payroll_period_id = $(this).val();

    // 	$(".search_entry #payroll_period_id").val(payroll_period_id).change();
    // });

    $(document).on("change", ".search_entry #pay_basis", function(e) {
        pay_basis = $(this).val();

        $.when(getFields.payrollperiodcutoff(pay_basis)).done(function() {
            $(" form #payroll_period_id").val(payroll_period_id);
            $.AdminBSB.select.activate();
        });
    });

    function getEmployeeList(pay_basis, division_id) {
        if (division_id != "" && pay_basis != "") {
            $.when(
                getFields.employee({
                    division_id: division_id,
                    pay_basis: pay_basis,
                })
            ).done(function() {
                $("form #employee_id").val(employee_id).change();
                $.AdminBSB.select.activate();
            });
        }
    }

    function computeRMTP() {
        var loan_balance = $("form #loan_balance").val();
        var amortization_per_month = $("form #amortization_per_month").val();

        var rmtp = Math.ceil(loan_balance / amortization_per_month);
        $("form #rmtp").val(rmtp);
    }

    var sub_loans_id;
    $(document).on("change", "#loans_id", function() {
        id = $(this).val();
        pay_basis = $("form #pay_basis").val();
        $.when(getFields.subloan(id)).done(function() {
            $("#sub_loans_id").val(sub_loans_id).change();
            $.AdminBSB.select.activate();
        });
    });

    // $(document).on("change", "form #loans_id", function() {
    //     id = $(this).val();
    //     pay_basis = $("form #pay_basis").val();
    //     $.when(getFields.subloan(id)).done(function() {
    //         $("form #sub_loans_id").val(sub_loans_id).change();
    //         $.AdminBSB.select.activate();
    //     });
    // });

    var loans_id;

    $(document).on("change", ".division_id,.pay_basis", function(e) {
        division_id = $("#division_id").val();
        pay_basis = $("#pay_basis").val();
        if (division_id != "" && pay_basis != "") {
            $.when(
                getFields.employee({
                    division_id: division_id,
                    pay_basis: pay_basis,
                })
            ).done(function() {
                // alert(employee_id)

                $(".employee_id").val(employee_id).change();
                $.AdminBSB.select.activate();
            });
        }
    });

    //Confirms
    $(document).on(
        "click",
        ".activateLoanEntries,.deactivateLoanEntries",
        function(e) {
            e.preventDefault();
            me = $(this);
            url = me.attr("href");
            var id = me.attr("data-id");
            content = "Are you sure you want to proceed?";
            if (me.hasClass("activateLoanEntries")) {
                content = "Are you sure you want to activate selected Loan?";
            } else if (me.hasClass("deactivateSubLoanEntries")) {
                content = "Are you sure you want to deactivate selected Loan?";
            }
            data = { id: id };
            $.confirm({
                title: '<label class="text-warning">Confirm!</label>',
                content: content,
                type: "orange",
                buttons: {
                    confirm: {
                        btnClass: "btn-blue",
                        action: function() {
                            //Code here
                            $.confirm({
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: { id: id },
                                        dataType: "json",
                                        success: function(result) {
                                            if (result.Code == "0") {
                                                if (result.hasOwnProperty("key")) {
                                                    switch (result.key) {
                                                        case "activateLoanEntries":
                                                        case "deactivateLoanEntries":
                                                            self.setContent(result.Message);
                                                            self.setTitle(
                                                                '<label class="text-success">Success</label>'
                                                            );
                                                            reloadTable();
                                                            break;
                                                    }
                                                }
                                            } else {
                                                self.setContent(result.Message);
                                                self.setTitle(
                                                    '<label class="text-danger">Failed</label>'
                                                );
                                            }
                                        },
                                        error: function(result) {
                                            self.setContent(
                                                "There was an error in the connection. Please contact the administrator for updates."
                                            );
                                            self.setTitle(
                                                '<label class="text-danger">Failed</label>'
                                            );
                                        },
                                    });
                                },
                            });
                        },
                    },
                    cancel: function() {},
                },
            });
        }
    );
    $(document).on("click", "#searchEmployeeLoan", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        employee_id = $(".search_entry #employee_id").val();
        division_id = $(".search_entry #division_id").val();
        // payroll_period_id = $(".search_entry #payroll_period_id").val();
        pay_basis = $(".search_entry #pay_basis").val();
        plus_url =
            "?pay_basis=" +
            pay_basis +
            "&division_id=" +
            division_id
            // + "&payroll_period_id=" +
            // payroll_period_id;
        if (pay_basis == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select a Pay Basis.",
            });
            // } else if (division_id == "") {
            // 	$.alert({
            // 		title: '<label class="text-danger">Failed</label>',
            // 		content: "Please select a Department.",
            // 	});
        } else if (employee_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select an Employee.",
            });
        } else {
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function(result) {
                    $("#table-holder").html(result.table);
                    table = $("#datatables").DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        order: [],
                        scroller: {
                            displayBuffer: 20,
                        },
                        columnDefs: [{
                            targets: [0],
                            orderable: true,
                        }, ],
                        initComplete: function() {
                            $("#search-table").remove();
                            var input = $(".dataTables_filter input").unbind(),
                                self = this.api(),
                                $searchButton = $(
                                    '<button id="search-table" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                                )
                                .html('<i class="material-icons">search</i>')
                                .click(function() {
                                    if (!$("#search-table").is(":disabled")) {
                                        $("#search-table").attr("disabled", true);
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
                            $(".dataTables_filter").append($searchButton);
                        },
                        drawCallback: function(settings) {
                            $("#search-loader").remove();
                            $("#search-table").removeAttr("disabled");
                            $("#datatables button").removeAttr("disabled");
                        },
                        ajax: {
                            url: tbl_url + plus_url,
                            type: "POST",
                        },
                        oLanguage: {
                            sProcessing: '<div class="preloader pl-size-sm">' +
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
                },
                error: function(result) {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "There was an error in the connection. Please contact the administrator for updates.",
                    });
                },
            });
        }
    });
    //Ajax non-forms
    $(document).on(
        "click",
        "#addLoanEntriesForm,.updateLoanEntriesForm,.viewLoanEntriesForm,.viewSubLoanEntriesForm,.viewLoanEntriesUpdates",
        function(e) {
            e.preventDefault();
            my = $(this);
            id = my.attr("data-id");
            url = my.attr("href");
            employee_id = $('.search_entry #employee_id').val()
            division_id = $(".search_entry #division_id").val();
            pay_basis = $(".search_entry #pay_basis").val();
            year = $(".search_entry #search_year").val();
            month = $(".search_entry #month").val();

            //employee_id = my.data("employee_id");
            loans_id = my.data("loans_id");
            sub_loans_id = my.data("sub_loans_id");
            $("#myModal .modal-dialog").removeClass("full-screen");
            $.ajax({
                type: "POST",
                url: url,
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    page = my.attr("id");
                    if (result.hasOwnProperty("key")) {
                        switch (result.key) {
                            case "addLoanEntries":
                                page = "";
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-md"
                                );
                                $("#myModal .modal-title").html("Add New Loan");
                                $("#myModal .modal-body").html(result.form);
                                $("#myModal").modal("show");
                                break;
                            case "viewLoanEntries":
                                page = "";
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-md"
                                );
                                $("#myModal .modal-title").html("Loan Details");
                                $("#myModal .modal-body").html(result.form);
                                $("#myModal").modal("show");
                                break;
                            case "updateLoanEntries":
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-md"
                                );
                                $("#myModal .modal-title").html("Update Loan");
                                $("#myModal .modal-body").html(result.form);
                                $("#myModal").modal("show");

                                break;
                            case "viewLoanEntriesUpdates":
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-dialog").addClass("full-screen");
                                $("#myModal .modal-title").html("History");
                                $("#myModal .modal-body").html(result.table);
                                loadUpdatesTable(id);
                                $("#myModal").modal("show");

                                break;
                        }
                        division_id = my.data("division_id");
                        employee_id = my.data("employee_id");

                        $.when(
                            getFields.division(),
                            getFields.payBasis3()

                        ).done(function() {
                            $(".selectpicker").selectpicker("destroy");
                            // console.log("MYDATA");
                            // console.log(my.data());
                            // $.each(my.data(),function(i,v){
                            //     if(i == "hold_tag")
                            //         $('.hold_tag'+v).click();
                            //     else {
                            //         // if(i != "loans_id" && i != "sub_loans_id" && i != "location_id" && i != "division_id")
                            //         if(i != "loans_id" && i != "sub_loans_id" && i != "payroll_grouping_id" && i != "pay_basis")
                            //             $('.'+i).val(my.data(i)).change();
                            //     }
                            // });
                            $.each(my.data(), function(i, v) {
                                $("." + i)
                                    .val(my.data(i))
                                    .change();
                            });

                            // $('.location_id').val(my.data('location_id')).change();
                            // $('.division_id').val(my.data('division_id')).change();
                            // $('.loans_id').val(my.data('loans_id')).change();
                            $(".payment_status_text").html(my.data("payment_status"));
                            if (result.key == "viewLoanEntries") {
                                // $('form').find('input, textarea, button, select').attr('disabled','disabled');
                                // $('form').find('#cancelUpdateForm').removeAttr('disabled');

                                $("form")
                                    .find("input, textarea, button, select")
                                    .attr("disabled", "disabled");
                                cancelButton = $("#cancelUpdateForm").get(0).outerHTML;
                                $("#myModal .modal-body").append(
                                    '<div class="text-right" style="width:100%;">' +
                                    cancelButton +
                                    "</div>"
                                );
                                $("form").find("#cancelUpdateForm").remove();
                                $("#cancelUpdateForm").removeAttr("disabled");
                                $("form").css("pointer-events", "none");
                            }
                            //if (result.key == "addLoanEntries") {
                            //$(".search_entry #pay_basis").val(pay_basis).change();
                            //$(".search_entry #division_id").val(division_id).change();
                            //$(".search_entry #employee_id").val(employee_id).change();
                            // $('#myModal #employee_id').val(employee_id).change();
                            //alert('heelooo')
                            //}
                            // alert(result.key);
                            pay_basis = my.data("pay_basis");
                            payroll_period_id = my.data("payroll_period_id");
                            // alert(payroll_period_id)
                            $.AdminBSB.select.activate();
                            // $('.pay_basis option').eq(4).remove();
                            // $('.pay_basis option').eq(4).remove();
                            // $('.pay_basis option').eq(4).remove();
                            // $('#pay_basis').selectpicker('refresh')
                        });
                        $(document).on('change', '#division_id', function() {
                            $.when(getFields.employee({
                                division_id: $(this).val()
                            })).done(function() {
                                $.AdminBSB.select.activate();
                            });
                        });
                        $("#" + result.key).validate({
                            rules: {
                                ".required": {
                                    required: true,
                                },
                                ".email": {
                                    required: true,
                                    email: true,
                                },
                            },
                            highlight: function(input) {
                                $(input).parents(".form-line").addClass("error");
                            },
                            unhighlight: function(input) {
                                $(input).parents(".form-line").removeClass("error");
                            },
                            errorPlacement: function(error, element) {
                                $(element).parents(".form-group").append(error);
                            },
                        });
                    }
                },
                error: function(result) {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "There was an error in the connection. Please contact the administrator for updates.",
                    });
                },
            });
        }
    );

    //Ajax Forms
    $(document).on("submit", "#addLoanEntries,#updateLoanEntries", function(e) {
        e.preventDefault();        
        content = "Are you sure you want to proceed?";
        var form = $(this);
        employee_id = $('#myModal #employee_id').val()
        division_id = $("#myModal #division_id").val();
        pay_basis = $("#myModal #pay_basis").val();
        loans_id = $("#myModal #loans_id").val();
        sub_loans_id = $("#myModal #sub_loans_id").val();

        if(division_id == "" || division_id == "undefined"){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Division.",
            });
            return false;
        } else if (loans_id == "" || loans_id == "undefined") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Category.",
            });
            return false;
        } else if (sub_loans_id == "" || sub_loans_id == "undefined") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Loan Type.",
            });
            return false;
        } else{
            if (form.attr("id") == "addLoanEntries") {
                content = "Are you sure you want to add Loan?";
            }
            if (form.attr("id") == "updateLoanEntries") {
                content = "Are you sure you want to update Loan?";
            }
        }

        url = form.attr("action");
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: "orange",
            buttons: {
                confirm: {
                    btnClass: "btn-blue",
                    action: function() {
                        //Code here
                        $.confirm({
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(result) {
                                        if (result.hasOwnProperty("key")) {
                                            if (result.Code == "0") {
                                                if (result.hasOwnProperty("key")) {
                                                    switch (result.key) {
                                                        case "addLoanEntries":
                                                        case "updateLoanEntries":
                                                            self.setContent(result.Message);
                                                            self.setTitle(
                                                                '<label class="text-success">Success</label>'
                                                            );
                                                            $("#myModal .modal-body").html("");
                                                            $("#myModal").modal("hide");
                                                            //$('.search_entry #employee_id').val($('#myModal #employee_id').val());
                                                            reloadTable();
                                                            break;
                                                    }
                                                }
                                            } else {
                                                self.setContent(result.Message);
                                                self.setTitle(
                                                    '<label class="text-danger">Failed</label>'
                                                );
                                            }
                                        }
                                    },
                                    error: function(result) {
                                        self.setContent(
                                            "There was an error in the connection. Please contact the administrator for updates."
                                        );
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    },
                                });
                            },
                        });
                    },
                },
                cancel: function() {},
            },
        });
    });

    function loadTable() {
        //employment_status = $('#hide_emp_status').val();
        plus_url = "";
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
        table = $("#datatables").DataTable({
            processing: true,
            serverSide: true,
            stateSave: true, // presumably saves state for reloads -- entries
            bStateSave: true, // presumably saves state for reloads -- page number
            responsive: true,
            order: [],
            scroller: {
                displayBuffer: 20,
            },
            columnDefs: [{
                targets: [0],
                orderable: true,
            }, ],
            initComplete: function() {
                $("#search-table").remove();
                var input = $(".dataTables_filter input").unbind(),
                    self = this.api(),
                    $searchButton = $(
                        '<button id="search-table" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                    )
                    .html('<i class="material-icons">search</i>')
                    .click(function() {
                        if (!$("#search-table").is(":disabled")) {
                            $("#search-table").attr("disabled", true);
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
                if ($("#search-table").length === 0) {
                    $('.dataTables_filter').append($searchButton);
                }
            },
            drawCallback: function(settings) {
                $("#search-loader").remove();
                $("#search-table").removeAttr("disabled");
                $("#datatables button").removeAttr("disabled");
            },
            ajax: {
                url: tbl_url + plus_url,
                type: "POST",
            },
            oLanguage: {
                sProcessing: '<div class="preloader pl-size-sm">' +
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

    function loadUpdatesTable(id) {
        //employment_status = $('#hide_emp_status').val();
        plus_url = "?ref_id=" + id;
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
        /*table = $('#datatables-updates').DataTable({  
               "processing":true,  
               "serverSide":true, 
               "responsive":true, 
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "transactions/OvertimeApplicationsUpdates/fetchRows"+plus_url,  
                    type:"POST"  
               },  
               "columnDefs":[  
                    {  
                         "orderable":false,  
                    },  
               ],  
        });*/
        table = $("#datatables-updates").DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            scroller: {
                displayBuffer: 20,
            },
            columnDefs: [{
                targets: [0],
                orderable: true,
            }, ],
            initComplete: function() {
                $("#search-tableupdates").remove();
                var input = $(".dataTables_filter input").unbind(),
                    self = this.api(),
                    $searchButton = $(
                        '<button id="search-tableupdates" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                    )
                    .html('<i class="material-icons">search</i>')
                    .click(function() {
                        if (!$("#search-tableupdates").is(":disabled")) {
                            $("#search-tableupdates").attr("disabled", true);
                            self.search(input.val()).draw();
                            $("#datatables-updates_wrapper button").attr("disabled", true);
                            $("#datatables-updates_wrapper .dataTables_filter").append(
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
                $("#datatables-updates_wrapper .dataTables_filter").append(
                    $searchButton
                );
            },
            drawCallback: function(settings) {
                $("#search-loader").remove();
                $("#search-tableupdates").removeAttr("disabled");
                $("#datatables-updates button").removeAttr("disabled");
            },
            ajax: {
                url: commons.baseurl +
                    "transactions/LoanEntriesUpdates/fetchRows" + plus_url,
                type: "POST",
            },
            oLanguage: {
                sProcessing: '<div class="preloader pl-size-sm">' +
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

    function reloadTable() {
        //table.ajax.reload();
        employee_id = $(".search_entry #employee_id").val();
        plus_url = "?EmployeeId=" + employee_id;
        if (employee_id == "") {
            return false;
        }
        $("#searchEmployeeLoan").click();
    }
    console.log(commons.baseurl);

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