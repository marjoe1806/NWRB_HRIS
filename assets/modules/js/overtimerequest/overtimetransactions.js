$(function() {
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $(document).on("click", "#addBtn", function(e) {
        e.preventDefault();
        $("select").selectpicker("destroy");
        index = $("form .card").length;
        row_1 = $("#row_0").html();
        row_1 = row_1.split("[0]").join("[" + index + "]");
        // console.log(row_1);
        $(
            '<div id="row_' + index + '" class="col-md-6">' + row_1 + "</div>"
        ).insertBefore("#addBtnCotainer");
        $("#row_" + index)
            .find(".removeBtn")
            .css("visibility", "visible");
        $("select").selectpicker("refresh");
        $(".datepicker").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            maxDate: new Date(),
            time: false,
        });
        $(".timepicker").inputmask({
            mask: "h:s:s t\\m",
            placeholder: "hh:mm:s xm",
            alias: "datetime",
            hourFormat: "12",
        });
    });
    $(document).on("click", ".removeBtn", function(e) {
        $(this).closest(".card").parent().remove();
    });
    $(document).on("click", "#printOvertimeTransactions", function(e) {
        e.preventDefault();
        printPrev(document.getElementById("servicerecords-container").innerHTML);
    });

    $(document).on("click", "#changeAttachment", function(e) {
        e.preventDefault();
        $("#updateFileButtons").hide();
        $("#hiddenFileInput").show();
    });

    $(document).on("click", "#cancelChange", function(e) {
        e.preventDefault();
        $("#updateFileButtons").show();
        $("#hiddenFileInput").hide();
    });

    $(document).on("change", "#remarks", function(e) {
        e.preventDefault();
        if ($(this).val() == "Defective") {
            $("#location").parent().parent().parent().hide();
        } else {
            $("#location").parent().parent().parent().show();
        }
    });

    //Confirms
    $(document).on("click", ".approveOvertimeTransactions,.rejectOvertimeTransactions,.AssignToComplete", function(
        e
    ) {
        e.preventDefault();
        me = $(this);
        url = me.attr("href");
        var id = me.attr("data-id");
        var transaction_date = me.attr("data-transaction_date");
        var employee_id = me.attr("data-employee_id");
        content = "Are you sure you want to proceed?";
        if (me.hasClass("approveOvertimeTransactions")) {
            content = "Are you sure you want to approve selected request?";
        } else if (me.hasClass("rejectOvertimeTransactions")) {
            //content = 'Are you sure you want to reject selected request?';
            content += '<div class="form-group">';
            content += '<label class="form-label">Remarks</label>';
            content += '<div class="form-group form-float">';
            content += '<div class="form-line">';
            content += '<input type="text" class="remarks form-control" >';
            content += "</div>";
            content += "</div>";
            content += "</div>";
            content += "</form>";
        } else if (me.hasClass("AssignToComplete")) {
            content = "Are you sure you want to complete selected request?";
        }
        data = {
            id: id,
        };
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: "orange",
            buttons: {
                confirm: {
                    btnClass: "btn-blue",
                    action: function() {
                        remarks =
                            typeof this.$content.find(".remarks").val() === "undefined" ?
                            "N/A" :
                            this.$content.find(".remarks").val();

                        $.confirm({
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {
                                        id: id,
                                        remarks: remarks,
                                        transaction_date: transaction_date,
                                        employee_id: employee_id,

                                    },
                                    dataType: "json",
                                    success: function(result) {
                                        if (result.Code == "0") {
                                            if (result.hasOwnProperty("key")) {
                                                switch (result.key) {
                                                    case "approveOvertimeTransactions":
                                                    case "rejectOvertimeTransactions":
                                                    case "AssignToComplete":
                                                        self.setContent(result.Message);
                                                        self.setTitle(
                                                            '<label class="text-success">Success</label>'
                                                        );
                                                        break;
                                                }
                                            }
                                            reloadTable();
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

    var employee_id;

    //Ajax non-forms
    $(document).on(
        "click",
        "#addOvertimeTransactionsForm,.updateOvertimeTransactionsForm,.viewOvertimeTransactionsForm",
        function(e) {
            e.preventDefault();
            my = $(this);
            url = my.attr("href");
            employee_id = my.data("employee_id");
            $.ajax({
                type: "POST",
                url: url,
                data: { employee_id: employee_id },
                dataType: "json",
                success: function(result) {
                    page = my.attr("id");
                    if (result.hasOwnProperty("key")) {
                        switch (result.key) {
                            case "viewOvertimeTransactions":
                            case "updateOvertimeTransactions":

                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-title").html(
                                    "Overtime Request Permission Form"
                                );
                                $("#myModal .modal-body").html(result.form);

                                $("#myModal").modal("show");
                                $.each(my.data(), function(i, v) {
                                    $("." + i).val(v);

                                    if (i == "purpose")
                                        $("#radio_" + my.data(i))
                                        .attr("checked", true)
                                        .change()
                                        .click();

                                });

                                // $(".purpose")
                                //     .attr("checked", true)
                                //     .change()
                                //     .click()
                                //     .val("#radio_" + my.data("purpose"));

                                if (result.key == "viewOvertimeTransactions") {
                                    $("form :input").attr("disabled", true);
                                    $("form").find("#cancelUpdateForm").removeAttr("disabled");
                                }

                                $("#emp_info").attr("disabled", true);
                                $("#cancelUpdateForm").removeAttr("disabled");
                                break;
                        }
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
    $(document).on("click", "#print_preview_appleave", function() {
        printPrev(document.getElementById("content").innerHTML);
    });

    $(document).on("submit", "#addOvertimeTransactions,#updateOvertimeTransactions", function(
        e
    ) {
        e.preventDefault();
        var form = $(this);

        content = "Are you sure you want to proceed?";
        if (form.attr("id") == "addOvertimeTransactions") {
            content = "Are you sure you want to add report?";
        }
        if (form.attr("id") == "updateOvertimeTransactions") {
            content = "Are you sure you want to update report?";
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
                                    data: new FormData(form[0]),
                                    contentType: false,
                                    processData: false,
                                    dataType: "json",
                                    success: function(result) {
                                        if (result.hasOwnProperty("key")) {
                                            if (result.Code == "0") {
                                                if (result.hasOwnProperty("key")) {
                                                    switch (result.key) {
                                                        case 'addOvertimeTransactions':
                                                        case 'updateOvertimeTransactions':
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

    $(document).on("click", "#btnXls", function() {
        exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
    });

    $(document).on("click", "#btnSearch", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(result) {
                $("#table-holder").html(result.table);
                table = $("#datatables").DataTable({
                    processing: true,
                    serverSide: true,
                    columnDefs: [{
                        targets: [0],
                        orderable: false,
                    }, ],
                    order: [],
                    ajax: {
                        url: commons.baseurl + "overtimerequest/OvertimeTransactions/fetchRows",
                        // ?Status=" +$("#status").val(),
                        type: "POST",
                        data: { "status": $("#status").val() },
                    },
                });
                button =
                    '<a id="viewLeaveCreditsSummary">' +
                    '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
                    '<i class="material-icons">people</i> Balance of Leave Credits Summary' +
                    "</button>" +
                    "</a>";
                $("#table-holder .button-holder").html(button);
            },
            error: function(result) {
                errorDialog();
            },
        });
    });
});

// function loadTable() {
//     table = $("#datatables").DataTable({
//         processing: true,
//         serverSide: true,
//         order: [],
//         ajax: {
//             url: commons.baseurl +
//                 "overtimerequest/OvertimeTransactions/fetchRows?Status=" +
//                 $("#status").val(),
//             type: "POST",
//         },
//         columnDefs: [{ orderable: false }],
//     });
// }

// function reloadTable() {
//     table.ajax.reload();
// }

function loadTable() {
    table = $("#datatables").DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // presumably saves state for reloads -- entries
        bStateSave: true, // presumably saves state for reloads -- page number
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, 'All'],
        ],
        columnDefs: [{
            targets: [0],
            orderable: false,
        }, ],
        order: [],
        ajax: {
            url: commons.baseurl + "overtimerequest/OvertimeTransactions/fetchRows",
            // ?Status=" +$("#status").val(),
            type: "POST",
            data: { "status": $("#status").val() },
        },
    });
}

function reloadTable() {
    // table.ajax.reload();
    $("#datatables").DataTable().ajax.reload();
}