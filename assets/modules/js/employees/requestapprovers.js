$(document).ready(function() {
    base_url = commons.base_url;
    $("#addEmployeesForm").closest(".row").hide();
    $(".card .header h2").html("Manage Employees Approver");
    // loadTable();

    $(document).on("click", "#viewFilteredEmployee", function(e) {
        e.preventDefault();
        loadTable();
    });

    $.when(getFields.division(), getFields.payBasis3()).done(function() {
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

    $(document).on("change", ".dvfilter #pay_basis", function() {
        $.when(getFields.salary_grade($(this).val())).done(function() {
            $.AdminBSB.select.activate();
        });
    });

    $(document).on("change", ".dvfilter #salary_grade_id", function() {
        $.when(getFields.position({ salary_grade_id: $(this).val() })).done(
            function() {
                $.AdminBSB.select.activate();
            }
        );
    });

    $(document).on("keyup", ".dataTables_filter input", function(e) {
        if ($(this).val() != "" && e.keyCode == 13) $("#search-employee").click();
    });

    $(document).on("click", ".updateEmployeesApproverForm", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");
        if (!$(this).find("button").is(":disabled")) {
            getFields.reloadModal();
            $.ajax({
                type: "POST",
                url: url,
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    page = $(this).attr("id");
                    if (result.hasOwnProperty("key")) {
                        $("#myModal .modal-dialog").attr("class", "modal-dialog modal-lg");
                        $("#myModal .modal-title").html(
                            "Manage Employee Request Approvers"
                        );
                        $("#myModal .modal-body").html(result.form);
                        $.when(getFields.employee({ id: id })).done(function() {
                            $("input[name='id_cto'],input[name='id'], input[name='id_OB'], input[name='id_TO'], input[name='id_OT']").val(id);

                            $("#tblctoapprovers, #tblleaveapprovers, #tblobapprovers, #tbltravelorderapprover, #tblovertimeapprovers").DataTable({
                                pagingType: "full_numbers",
                                paging: true,
                                lengthMenu: [
                                    [5, 10, -1],
                                    [5, 10, "ALL"],
                                ],
                                oLanguage: {
                                    sZeroRecords: "No Records Available",
                                    sInfoEmpty: "Showing 0 to 0 of 0 records",
                                },
                                columns: [
                                    { data: "employee" },
                                    { data: "type" },
                                    { data: "approver_status" },
                                    { data: "action" },
                                ],
                            });
                            dialogGetList(
                                commons.baseurl +
                                "employees/RequestApprovers/getCTOApprovers", { id: id },
                                "tblctoapprovers"
                            );
                            dialogGetList(
                                commons.baseurl +
                                "employees/RequestApprovers/getLeaveApprovers", { id: id },
                                "tblleaveapprovers"
                            );

                            dialogGetList(
                                commons.baseurl + "employees/RequestApprovers/getOBApprovers", { id: id },
                                "tblobapprovers"
                            );

                            dialogGetList(
                                commons.baseurl + "employees/RequestApprovers/getTravelApprovers", { id: id },
                                "tbltravelorderapprover"
                            );
                            //OT Request
                            dialogGetList(
                                commons.baseurl + "employees/RequestApprovers/getOvertimeApprovers", { id: id },
                                "tblovertimeapprovers"
                            );

                            $("#approverCTOForm").validate({
                                rules: {
                                    employee_id: { required: true },
                                    approver_type_cto: { required: true },
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
                                submitHandler: function(form) {
                                    var formData = new FormData($("#approverCTOForm")[0]);
                                    submitRequest(
                                        commons.baseurl +
                                        "employees/RequestApprovers/addCTOApprover",
                                        "post",
                                        1,
                                        formData,
                                        reloadCTOApproverTable,
                                        "You want to proceed?"
                                    );
                                },
                            });

                            $("#approverLeaveForm").validate({
                                rules: {
                                    employee_id: { required: true },
                                    approver_type: { required: true },
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
                                submitHandler: function(form) {
                                    var formData = new FormData($("#approverLeaveForm")[0]);
                                    submitRequest(
                                        commons.baseurl +
                                        "employees/RequestApprovers/addLeaveApprover",
                                        "post",
                                        1,
                                        formData,
                                        reloadLeaveApproverTable,
                                        "You want to proceed?"
                                    );
                                },
                            });

                            $("#approverOBForm").validate({
                                rules: {
                                    employee_id: { required: true },
                                    approver_type_OB: { required: true },
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
                                submitHandler: function(form) {
                                    var formData = new FormData($("#approverOBForm")[0]);
                                    submitRequest(
                                        commons.baseurl +
                                        "employees/RequestApprovers/addOBApprover",
                                        "post",
                                        1,
                                        formData,
                                        reloadOBApproverTable,
                                        "You want to proceed?"
                                    );
                                },
                            });

                            $("#approverTravelForm").validate({
                                rules: {
                                    employee_id: { required: true },
                                    approver_type_TO: { required: true },
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
                                submitHandler: function(form) {
                                    var formData = new FormData($("#approverTravelForm")[0]);
                                    submitRequest(
                                        commons.baseurl +
                                        "employees/RequestApprovers/addTravelApprover",
                                        "post",
                                        1,
                                        formData,
                                        reloadTravelApproverTable,
                                        "You want to proceed?"
                                    );
                                },
                            });

                            $("#approverOvertimeForm").validate({
                                rules: {
                                    employee_id: { required: true },
                                    approver_type_OT: { required: true },
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
                                submitHandler: function(form) {
                                    var formData = new FormData($("#approverOvertimeForm")[0]);
                                    submitRequest(
                                        commons.baseurl +
                                        "employees/RequestApprovers/addOvertimeApprovers",
                                        "post",
                                        1,
                                        formData,
                                        reloadOTApproverTable,
                                        "You want to proceed?"
                                    );
                                },
                            });


                            $.AdminBSB.select.activate();
                            $(".chk").iCheck("destroy");
                            $(".chk").iCheck({ checkboxClass: "icheckbox_square-grey" });
                        });
                    }
                    //Advanced form with validation
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

    $(document).on("click", "#btnUpdate_cto", function() {
        var inputsvalidator = $("#approverCTOForm").validate();
        var i = 0;
        if (!inputsvalidator.element("#approverCTOForm #employee_id")) {
            dialogErrorV2("Select employee.");
            return false;
        } else if (!inputsvalidator.element("#approve_type_cto")) {
            dialogErrorV2("Select approve type.");
            return false;
        } else {
            var formData = new FormData($("#approverCTOForm")[0]);
            submitRequest(
                commons.baseurl + "employees/RequestApprovers/updateCTOApprover",
                "post",
                1,
                formData,
                reloadCTOApproverTable,
                "You want to proceed?"
            );
        }
    });

    $(document).on("click", "#approverLeaveForm #btnUpdate", function() {
        var inputsvalidator = $("#approverLeaveForm").validate();
        var i = 0;
        if (!inputsvalidator.element("#approverLeaveForm #employee_id")) {
            dialogErrorV2("Select employee.");
            return false;
        } else if (!inputsvalidator.element("#approve_type")) {
            dialogErrorV2("Select approve type.");
            return false;
        } else {
            var formData = new FormData($("#approverLeaveForm")[0]);
            submitRequest(
                commons.baseurl + "employees/RequestApprovers/updateLeaveApprover",
                "post",
                1,
                formData,
                reloadLeaveApproverTable,
                "You want to proceed?"
            );
        }
    });

    $(document).on("click", "#btnUpdateOB", function() {
        var inputsvalidator = $("#approverOBForm").validate();
        var i = 0;
        if (!inputsvalidator.element("#approverOBForm #employee_id")) {
            dialogErrorV2("Select employee.");
            return false;
        } else if (!inputsvalidator.element("#approve_type_OB")) {
            dialogErrorV2("Select approve type.");
            return false;
        } else {
            var formData = new FormData($("#approverOBForm")[0]);
            submitRequest(
                commons.baseurl + "employees/RequestApprovers/updateOBApprover",
                "post",
                1,
                formData,
                reloadOBApproverTable,
                "You want to proceed?"
            );
        }
    });

    $(document).on("click", "#btnUpdateTO", function() {
        var inputsvalidator = $("#approverTravelForm").validate();
        var i = 0;
        if (!inputsvalidator.element("#approverTravelForm #employee_id")) {
            dialogErrorV2("Select employee.");
            return false;
        } else if (!inputsvalidator.element("#approve_type_TO")) {
            dialogErrorV2("Select approve type.");
            return false;
        } else {
            var formData = new FormData($("#approverTravelForm")[0]);
            submitRequest(
                commons.baseurl + "employees/RequestApprovers/updateTravelApprover",
                "post",
                1,
                formData,
                reloadTravelApproverTable,
                "You want to proceed?"
            );
        }
    });

    $(document).on("click", "#btnUpdateOT", function() {
        var inputsvalidator = $("#approverOvertimeForm").validate();
        var i = 0;
        if (!inputsvalidator.element("#approverOvertimeForm #employee_id")) {
            dialogErrorV2("Select employee.");
            return false;
        } else if (!inputsvalidator.element("#approve_type_OT")) {
            dialogErrorV2("Select approve type.");
            return false;
        } else {
            var formData = new FormData($("#approverOvertimeForm")[0]);
            submitRequest(
                commons.baseurl + "employees/RequestApprovers/updateOTApprover",
                "post",
                1,
                formData,
                reloadOTApproverTable,
                "You want to proceed?"
            );
        }
    });

    $(document).on(
        "click",
        ".select_cto_approver, .activate_cto_approver, .deactivate_cto_approver, .delete_cto_approver",
        function() {
            $this = $(this);
            var id = $this.data("id");
            if ($this.hasClass("activate_cto_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/activateCTOApprover",
                    "post",
                    0, { id: id },
                    reloadCTOApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("deactivate_cto_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deactivateCTOApprover",
                    "post",
                    0, { id: id },
                    reloadCTOApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("delete_cto_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deleteCTOApprover",
                    "post",
                    0, { id: id },
                    reloadCTOApproverTable,
                    "You want to proceed?"
                );
            } else {
                $("#approverCTOForm #approver_id_cto").val($this.data("id"));
                $("#approverCTOForm #employee_id").val($this.data("approver"));
                $("#approverCTOForm #approve_type_cto").val($this.data("type"));
                $.AdminBSB.select.activate();
                $("#approverCTOForm #btnUpdate_cto").removeClass("displaynone");
                $("#approverCTOForm button[type='submit']").addClass("displaynone");
                if ($this.data("status") === "1") $("#isActive_cto").iCheck("check");
                else $("#isActive_cto").iCheck("uncheck");
            }
        }
    );

    $(document).on(
        "click",
        ".select_leave_approver, .activate_leave_approver, .deactivate_leave_approver, .delete_leave_approver",
        function() {
            $this = $(this);
            var id = $this.data("id");
            if ($this.hasClass("activate_leave_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/activateLeaveApprover",
                    "post",
                    0, { id: id },
                    reloadLeaveApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("deactivate_leave_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deactivateLeaveApprover",
                    "post",
                    0, { id: id },
                    reloadLeaveApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("delete_leave_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deleteLeaveApprover",
                    "post",
                    0, { id: id },
                    reloadLeaveApproverTable,
                    "You want to proceed?"
                );
            } else {
                $("#approverLeaveForm #approver_id").val($this.data("id"));
                $("#approverLeaveForm #employee_id").val($this.data("approver"));
                $("#approverLeaveForm #approve_type").val($this.data("type"));
                $.AdminBSB.select.activate();
                $("#approverLeaveForm #btnUpdate").removeClass("displaynone");
                $("#approverLeaveForm button[type='submit']").addClass("displaynone");
                if ($this.data("status") === "1") $("#isActive").iCheck("check");
                else $("#isActive").iCheck("uncheck");
            }
        }
    );


    $(document).on(
        "click",
        ".select_travel_approver, .activate_travel_approver, .deactivate_travel_approver, .delete_travel_approver",
        function() {
            $this = $(this);
            var id = $this.data("id");
            if ($this.hasClass("activate_travel_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/activateTravelApprover",
                    "post",
                    0, { id: id },
                    reloadTravelApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("deactivate_travel_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deactivateTravelApprover",
                    "post",
                    0, { id: id },
                    reloadTravelApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("delete_travel_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deleteTravelApprover",
                    "post",
                    0, { id: id },
                    reloadTravelApproverTable,
                    "You want to proceed?"
                );
            } else {
                $("#approverTravelForm #approver_id_TO").val($this.data("id"));
                $("#approverTravelForm #employee_id").val($this.data("approver"));
                $("#approverTravelForm #approve_type_TO").val($this.data("type"));
                $.AdminBSB.select.activate();
                $("#approverTravelForm #btnUpdateTO").removeClass("displaynone");
                $("#approverTravelForm button[type='submit']").addClass("displaynone");
                if ($this.data("status") === "1") $("#isActive_TO").iCheck("check");
                else $("#isActive_TO").iCheck("uncheck");
            }
        }
    );

    $(document).on(
        "click",
        ".select_ob_approver, .activate_ob_approver, .deactivate_ob_approver, .delete_ob_approver",
        function() {
            $this = $(this);
            var id = $this.data("id");
            if ($this.hasClass("activate_ob_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/activateOBApprover",
                    "post",
                    0, { id: id },
                    reloadOBApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("deactivate_ob_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/deactivateOBApprover",
                    "post",
                    0, { id: id },
                    reloadOBApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("delete_ob_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deleteOBApprover",
                    "post",
                    0, { id: id },
                    reloadOBApproverTable,
                    "You want to proceed?"
                );
            } else {
                $("#approver_id_OB").val($this.data("id"));
                $("#approverOBForm #employee_id").val($this.data("approver"));
                $("#approve_type_OB").val($this.data("type"));
                $.AdminBSB.select.activate();
                $("#btnUpdateOB").removeClass("displaynone");
                $("#approverOBForm button[type='submit']").addClass("displaynone");
                if ($this.data("status") === "1") $("#isActive_OB").iCheck("check");
                else $("#isActive_OB").iCheck("uncheck");
            }
        }
    );
    //OT Request
    $(document).on(
        "click",
        ".select_overtime_approver, .activate_overtime_approver, .deactivate_overtime_approver, .delete_overtime_approver",
        function() {
            $this = $(this);
            var id = $this.data("id");
            if ($this.hasClass("activate_overtime_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/activateOvertimeApprover",
                    "post",
                    0, { id: id },
                    reloadOTApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("deactivate_overtime_approver")) {
                submitRequest(
                    commons.baseurl + "employees/RequestApprovers/deactivateOvertimeApprover",
                    "post",
                    0, { id: id },
                    reloadOTApproverTable,
                    "You want to proceed?"
                );
            } else if ($this.hasClass("delete_overtime_approver")) {
                submitRequest(
                    commons.baseurl +
                    "employees/RequestApprovers/deleteOvertimeApprover",
                    "post",
                    0, { id: id },
                    reloadOTApproverTable,
                    "You want to proceed?"
                );
            } else {
                $("#approver_id_OT").val($this.data("id"));
                $("#approverOvertimeForm #employee_id").val($this.data("approver"));
                $("#approve_type_OT").val($this.data("type"));
                $.AdminBSB.select.activate();
                $("#btnUpdateOT").removeClass("displaynone");
                $("#approverOvertimeForm button[type='submit']").addClass("displaynone");
                if ($this.data("status") === "1") $("#isActive_Overtime").iCheck("check");
                else $("#isActive_Overtime").iCheck("uncheck");
            }
        }
    );
});

function reloadCTOApproverTable() {
    dialogGetList(
        commons.baseurl + "employees/RequestApprovers/getCTOApprovers", { id: $("#id_cto").val() },
        "tblctoapprovers"
    );
    $("#approverCTOForm select").prop("selectedIndex", 0);
    $.AdminBSB.select.activate();
    $("#approverCTOForm #btnUpdate_cto").addClass("displaynone");
    $("#approverCTOForm button[type='submit']").removeClass("displaynone");
    $("#isActive_cto").iCheck("uncheck");
}

function reloadLeaveApproverTable() {
    dialogGetList(
        commons.baseurl + "employees/RequestApprovers/getLeaveApprovers", { id: $("#id").val() },
        "tblleaveapprovers"
    );
    $("#approverLeaveForm select").prop("selectedIndex", 0);
    $.AdminBSB.select.activate();
    $("#approverLeaveForm #btnUpdate").addClass("displaynone");
    $("#approverLeaveForm button[type='submit']").removeClass("displaynone");
    $("#isActive").iCheck("uncheck");
}

function reloadTravelApproverTable() {
    dialogGetList(
        commons.baseurl + "employees/RequestApprovers/getTravelApprovers", { id: $("#id_TO").val() },
        "tbltravelorderapprover"
    );
    $("#approverTravelForm select").prop("selectedIndex", 0);
    $.AdminBSB.select.activate();
    $("#approverTravelForm #btnUpdateTO").addClass("displaynone");
    $("#approverTravelForm button[type='submit']").removeClass("displaynone");
    $("#isActive_TO").iCheck("uncheck");
}

function reloadOBApproverTable() {
    dialogGetList(
        commons.baseurl + "employees/RequestApprovers/getOBApprovers", { id: $("#id_OB").val() },
        "tblobapprovers"
    );
    $("#approverOBForm select").prop("selectedIndex", 0);
    $.AdminBSB.select.activate();
    $("#btnUpdateOB").addClass("displaynone");
    $("#approverOBForm button[type='submit']").removeClass("displaynone");
    $("#isActive_OB").iCheck("uncheck");
}
//OT Request
function reloadOTApproverTable() {
    dialogGetList(
        commons.baseurl + "employees/RequestApprovers/getOvertimeApprovers", { id: $("#id_OT").val() },
        "tblovertimeapprovers"
    );
    $("#approverOvertimeForm select").prop("selectedIndex", 0);
    $.AdminBSB.select.activate();
    $("#btnUpdateOT").addClass("displaynone");
    $("#approverOvertimeForm button[type='submit']").removeClass("displaynone");
    $("#isActive_Overtime").iCheck("uncheck");
}


function loadTable() {
    employment_status = $("#hide_emp_status").val();
    division_id = $(".dvfilter #division_id").val();
    pay_basis = $(".dvfilter #pay_basis").val();
    salary_grade_id = $(".dvfilter #salary_grade_id").val();
    position_id = $(".dvfilter #position_id").val();

    plus_url = "";
    if (employment_status != "")
        plus_url += "?EmploymentStatus=" + employment_status;
    if (division_id != "") plus_url += "&DivisionId=" + division_id;
    if (pay_basis != "") plus_url += "&PayBasis=" + pay_basis;
    if (salary_grade_id != "") plus_url += "&SalaryGrade=" + salary_grade_id;
    if (position_id != "") plus_url += "&PositionId=" + position_id;
    $("#datatables").DataTable().clear().destroy();
    table = $("#datatables").DataTable({
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        processing: true,
        serverSide: true,
        stateSave: true, // presumably saves state for reloads -- entries
        bStateSave: true, // presumably saves state for reloads -- page number
        order: [],
        scroller: {
            displayBuffer: 20,
        },
        columnDefs: [{
            "targets": [0],
            orderable: false,
        }, ],
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
            url: commons.baseurl + "employees/RequestApprovers/fetchRows" + plus_url,
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
    $("#datatables").DataTable().ajax.reload();
}