$(function() {
    // loadTable();
    $(document).on("show.bs.modal", "#myModal", function() {
        $(".date").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            time: false,
        });

        $('input:radio[name=purpose]').change(function() {
            if (this.value == 'expected_time_return') {
                $('.expected_time_return_input').show();
                $('.expected_return').attr("required", "required");
            } else {
                $('.expected_return').removeAttr("required", "required");
                $('.expected_time_return_input').hide();

            }
        });
    });
    $(document).on("click", "#print_preview_appleave", function() {
        printPrev(document.getElementById("content").innerHTML);
    });

    //Confirms
    $(document).on('click', '.cancelOBRequestForm', function(e) {
        e.preventDefault();
        var me = $(this);
        var url = me.attr('href');
        var id = me.attr('data-id');
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: 'Are you sure you want to proceed?',
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
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
                                            self.setContent(result.Message);
                                            self.setTitle('<label class="text-success">Success</label>');
                                            loadTable();
                                        } else {
                                            self.setContent(result.Message);
                                            self.setTitle('<label class="text-danger">Failed</label>');
                                        }
                                    },
                                    error: function(result) {
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function() {}
            }
        });
    });

    //Ajax non-forms
    $(document).on(
        "click",
        "#addOvertimeRequestForm,.updateOvertimeRequestForm,.viewOvertimeRequestForm",
        function(e) {
            e.preventDefault();
            me = $(this);
            id = me.attr("data-id");
            url = me.attr("href");
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                },
                dataType: "json",
                success: function(result) {
                    page = me.attr("id");
                    // console.log(me.data());
                    if (result.hasOwnProperty("key")) {
                        var sess_division_id = $("#sess_division_id").val();
                        var sess_employee_id =
                            result.key == "addOvertimeRequest" ?
                            $("#sess_employee_id").val() :
                            me.data("employee_id");
                        var sess_position =
                            result.key == "addOvertimeRequest" ?
                            $("#sess_position").val() :
                            me.data("position_id");
                        var sess_employee_number = $("#sess_employee_number").val();
                        $.when(getFields.division()).done(function() {
                            $.when(
                                getFields.employee({
                                    division_id: sess_division_id,
                                    pay_basis: "Permanent",
                                })
                            ).done(function() {
                                $("#division_id").val(me.data("division_id")).change();
                                $("#division_id").css("pointer-events", "none");
                                $("#employee_id").val(me.data("employee_id")).change();
                                $("#employee_id").css("pointer-events", "none");

                                $("form #division_id").val(sess_division_id).change();
                                $("form .division_id").css("pointer-events", "none");
                                $("form #employee_id").val(sess_employee_id).change();
                                $("form #employee_id").css("pointer-events", "none");
                            });
                        });
                        $.when(getFields.position({ pay_basis: "Permanent" })).done(
                            function() {
                                $("form #position_id").val(sess_position).change();
                                $("form #position_id").css("pointer-events", "none");
                                $("form #filing_date").css("pointer-events", "none");
                            }
                        );
                        switch (result.key) {
                            case "addOvertimeRequest":
                                page = "";
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-title").html(
                                    "Add New Overtime Request"
                                );
                                $("#myModal .modal-body").html(result.form);
                                $("#myModal").modal("show");
                                $("#transaction_date").daterangepicker({
                                    timePicker: false,
                                    autoApply: true,
                                    locale: { format: "YYYY-MM-DD" },
                                    // maxDate: moment().startOf("day"),
                                    singleDatePicker: true,
                                });
                                break;
                            case "viewOvertimeRequest":
                            case "updateOvertimeRequest":
                                $.when(getFields.division()).done(function() {
                                    $(".division_id").selectpicker("val", me.data("division_id"));
                                    $(".division_id").val(me.data("division_id")).change();
                                    $(".remarks").selectpicker("val", me.data("remarks"));
                                    $(".remarks").val(me.data("remarks")).change();
                                    employee_id = me.data("employee_id");
                                    $(".employee_id_2").selectpicker(
                                        "val",
                                        me.data("checked_by")
                                    );
                                    $(".type_id").selectpicker("val", me.data("type_id"));
                                    // $('.location_id').selectpicker('val', me.data('location_id'));
                                    // $('.employee_id').selectpicker('refresh');
                                    $("#transaction_date").change();
                                    $.AdminBSB.select.activate();
                                });
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-lg"
                                );
                                $("#myModal .modal-title").html(
                                    "Personnel Overtime Request Details"
                                );
                                $("#myModal .modal-body").html(result.form);

                                $("#myModal").modal("show");
                                $.each(me.data(), function(i, v) {
                                    if (i == "transaction_date") {
                                        $(".transaction_date").val(
                                            me.data("transaction_date_end") != "0000-00-00" ?
                                            v + " - " + me.data("transaction_date_end") :
                                            v
                                        );
                                    } else {
                                        $("." + i)
                                            .val(me.data(i))
                                            .change();
                                    }

                                    if (i == "purpose")
                                        $("#radio_" + me.data(i))
                                        .attr("checked", true)
                                        .change()
                                        .click();
                                });

                                $("#overtime_time_out")
                                    .attr("type", "text")
                                    .val(me.data("transaction_time_end"));
                                $("#overtime_time_in")
                                    .attr("type", "text")
                                    .val(me.data("transaction_time"));
                                // $("#expected_return").removeAttr('disabled','disabled');
                                $("#expected_return")
                                    .attr("type", "text")
                                    .val(me.data("expected_time_return"));

                                if (me.data("filename") == "") {
                                    $("#downloadAttachment, #viewAttachment").css("pointer-events", "none");
                                }

                                // $("#viewAttachment").attr(
                                //   "href",
                                //   commons.baseurl +
                                //     "assets/uploads/locatorslips/" +
                                //     me.data("employee_id") +
                                //     "/" +
                                //     me.data("filename")
                                // );
                                // $("#downloadAttachment").attr(
                                //   "href",
                                //   commons.baseurl +
                                //     "assets/uploads/locatorslips/" +
                                //     me.data("employee_id") +
                                //     "/" +
                                //     me.data("filename")
                                // );
                                if (result.key == "viewOvertimeSlips") {
                                    $("#changeAttachment").hide();
                                    $("form")
                                        .find("input, textarea, select")
                                        .attr("disabled", "disabled");
                                    $("form").find("#cancelUpdateForm").removeAttr("disabled");
                                }

                                $("form :input").attr("disabled", true);
                                $("#cancelUpdateForm").removeAttr("disabled");
                                break;
                        }

                        $("#" + result.key).validate({
                            rules: {
                                activity_name: {
                                    required: true,
                                    normalizer: function(value) {
                                        return $.trim(value);
                                    },
                                },
                                location: {
                                    required: true,
                                    normalizer: function(value) {
                                        return $.trim(value);
                                    },
                                },
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

    $(document).on("click", "#btnXls", function() {
        exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
    });
    //Ajax Forms
    $(document).on(
        "submit",
        "#addOvertimeRequest,#updateOvertimeRequest",
        function(e) {
            e.preventDefault();
            var form = $(this);

            if (!$(".purpose").is(":checked")) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Select Type of Overtime.",
                });
                return false;
            }

            if (
                $("#overtime_time_in").val() == "" ||
                $("#overtime_time_out").val() == ""
            ) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Update time of activity with complete details.",
                });
                return false;
            }

            // if ($("#expected_return").val() == "") {
            //    $.alert({
            //      title: '<label class="text-danger">Failed</label>',
            //      content: "Update expected time of return",
            //    });
            //    return false;
            // }


            content = "Are you sure you want to proceed?";
            if (form.attr("id") == "addOvertimeRequest") {
                content =
                    "Are you sure you want to request for this overtime?";
            }
            if (form.attr("id") == "updateOvertimeRequest") {
                content = "Are you sure you want to update this overtime?";
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
                                                            case "addOvertimeRequest":
                                                            case "updateOvertimeRequest":
                                                                self.setContent(result.Message);
                                                                self.setTitle(
                                                                    '<label class="text-success">Success</label>'
                                                                );
                                                                $("#myModal .modal-body").html("");
                                                                $("#myModal").modal("hide");
                                                                loadTable();
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

    $(document).on("click", "#btnsearch", function() {
        loadTable();
    });

    function loadTable() {
        $("#datatables").DataTable().clear().destroy();
        table = $('#datatables').DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true, // presumably saves state for reloads -- entries
            "bStateSave": true, // presumably saves state for reloads -- page number
            "lengthMenu": [
              [10, 25, 50, -1],
              [10, 25, 50, 'All'],
            ],
            "order": [],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [{
                "targets": [0],
                "orderable": false
            }],
            initComplete: function() {

                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
                    .html('<i class="material-icons">search</i>')
                    .click(function() {

                        if (!$('#search-employee').is(':disabled')) {
                            $('#search-employee').attr('disabled', true);
                            self.search(input.val()).draw();
                            $('#datatables button').attr('disabled', true);
                            $('.dataTables_filter').append('<div id="search-loader"><br>' +
                                '<div class="preloader pl-size-xs">' +
                                '<div class="spinner-layer pl-red-grey">' +
                                '<div class="circle-clipper left">' +
                                '<div class="circle"></div>' +
                                '</div>' +
                                '<div class="circle-clipper right">' +
                                '<div class="circle"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '&emsp;Please Wait..</div>');
                        }

                    })
                if ($("#search-employee").length === 0) {
                    $('.dataTables_filter').append($searchButton);
                }

            },
            "drawCallback": function(settings) {
                $('#search-loader').remove();
                $('#search-employee').removeAttr('disabled');
                $('#datatables button').removeAttr('disabled');
            },
            "ajax": {
                url: commons.baseurl + "overtimerequest/OvertimeRequest/fetchRows?status=" + $("#status").val(),
                type: "GET",
            },
            oLanguage: {
                sProcessing: '<div class="preloader pl-size-sm">' +
                    '<div class="spinner-layer pl-red-grey">' +
                    '<div class="circle-clipper left">' +
                    '<div class="circle"></div>' +
                    '</div>' +
                    '<div class="circle-clipper right">' +
                    '<div class="circle"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
            }
        });
    }
});