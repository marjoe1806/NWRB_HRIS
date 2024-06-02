$(function() {
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $.when(
        getFields.payBasis3(),
        getFields.division()
        // getFields.location()
    ).done(function() {
        $("#pay_basis")
            .val("Permanent-Casual")
            .change();
        $.AdminBSB.select.activate();
        $("#pay_basis option")
            .eq(2)
            .remove();
        $("#pay_basis").selectpicker("refresh");
    });

    $(document).on("show.bs.modal", "#myModal", function() {
        $(".datepicker").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            time: false
        });
        $('[data-toggle="popover"]').popover();
        //$.AdminBSB.input.activate();
    });

    $(document).on("click", function(e) {
        $('[data-toggle="popover"],[data-original-title]').each(function() {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
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
                ).click = false; // fix for BS 3.3.6
            }
        });
    });

    const addCommas = x => {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    };

    //Ajax non-forms
    $(document).on("click", ".viewServiceRecord", function(e) {
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
                        case "viewServiceRecord":
                            page = "";
                            $("#myModal .modal-dialog").attr(
                                "class",
                                "modal-dialog modal-lg"
                            );
                            modal_title =
                                "Service Record: " +
                                result.data.employee_id_number +
                                " - " +
                                result.data.first_name +
                                " " +
                                result.data.middle_name +
                                " " +
                                result.data.last_name +
                                ' <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success">Print Preview</button>';
                            $("#myModal .modal-title").html(modal_title);
                            $("#myModal .modal-body").html(result.form);
                            $("#myModal").modal("show");
                            $.each(my.data(), function(i, v) {
                                $("." + i).val(addCommas(v));
                            });
                            $("form input:not(:button),form input:not(:submit)").attr(
                                "disabled",
                                true
                            );
                            break;
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
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates."
                });
            }
        });
    });

        // edit service records
    //Ajax non-forms
    $(document).on("click", ".editServiceRecord", function(e) {
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
                        case "editServiceRecord":
                            page = "";
                            $("#myModal .modal-dialog").attr(
                                "class",
                                "modal-dialog modal-lg"
                            );
                            modal_title =
                                "Service Record: " +
                                result.data.employee_id_number +
                                " - " +
                                result.data.first_name +
                                " " +
                                result.data.middle_name +
                                " " +
                                result.data.last_name;
                            // $("#myModal .modal-title").html(modal_title);
                            // $("#myModal .modal-body").html(result.form);
                            // $("#myModal").modal("show");
                            // $.each(my.data(), function(i, v) {
                            //     $("." + i).val(addCommas(v));
                            // });
                            // $("form input:not(:button),form input:not(:submit)").attr(
                            //     "disabled",
                            //     true
                            // );
                            break;
                    }
                    // $("#" + result.key).validate({
                    //     rules: {
                    //         ".required": { required: true },
                    //         ".email": { required: true, email: true },
                    //     },
                    //     highlight: function(input) {
                    //         $(input).parents(".form-line").addClass("error");
                    //     },
                    //     unhighlight: function(input) {
                    //         $(input).parents(".form-line").removeClass("error");
                    //     },
                    //     errorPlacement: function(error, element) {
                    //         $(element).parents(".form-group").append(error);
                    //     },
                    // });
                }
            },
            error: function(result) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates.",
                });
            },
        });
    });
    // end of edit

    $(document).on("click", "#computePayroll", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        division_id = $(".search_entry #division_id").val();
        if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Department."
            });
            return false;
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
                    order: [],
                    ajax: {
                        url: commons.baseurl +
                            "ServiceRecords/fetchRows?DivisionId=" +
                            division_id,
                        type: "POST"
                    },
                    columnDefs: [{
                        "targets": [0],
                        orderable: false
                    }]
                });
            },
            error: function(result) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates."
                });
            }
        });
    });

    $(document).on("click", "#btnPrintDetails", function() {
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

    function loadTable() {
        table = $("#datatables").DataTable({
            processing: true,
            serverSide: true,
            stateSave: true, // presumably saves state for reloads -- entries
            bStateSave: true, // presumably saves state for reloads -- page number
            order: [],
            ajax: {
                url: "ServiceRecords/fetchRows",
                type: "POST"
            },
            columnDefs: [{ orderable: false }]
        });
    }

   

    $(document).on("click", "#computePayroll", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        division_id = $(".search_entry #division_id").val();
        if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Department."
            });
            return false;
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
                    order: [],
                    ajax: {
                        url: commons.baseurl +
                            "ServiceRecords/fetchRows?DivisionId=" +
                            division_id,
                        type: "POST"
                    },
                    columnDefs: [{
                        orderable: false
                    }]
                });
            },
            error: function(result) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates."
                });
            }
        });
    });

    $(document).on("click", "#btnPrintDetails", function() {
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

    function loadTable() {
        table = $("#datatables").DataTable({
            processing: true,
            serverSide: true,
            stateSave: true, // presumably saves state for reloads -- entries
            bStateSave: true, // presumably saves state for reloads -- page number
            order: [],
            ajax: {
                url: "ServiceRecords/fetchRows",
                type: "POST"
            },
            columnDefs: [{ orderable: false }]
        });
    }


    function reloadTable() {
        //table.ajax.reload();
        employee_id = $(".search_entry #employee_id").val();
        plus_url = "?EmployeeId=" + employee_id;
        if (employee_id == "") {
            return false;
        }
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