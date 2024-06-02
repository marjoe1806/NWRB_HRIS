$(function() {
    var base_url = commons.baseurl;

    $(document).on('change', '#filter1', function(e) {
        var filter = $(this).val();

        $('.filter2').hide();
        field_reset();
        switch (filter) {
            case 'salary_grade':
                $('.salary_grade_div').show();
                $.when(
                    getFields.salary_grade('Permanent')
                ).done(function() {
                    $.AdminBSB.select.activate();
                });
                break;
            default:
                $('.' + filter + '_div').show();
                break;
        }
    });

    function field_reset() {
        $('#salary_grade_id').val("").change();
        $('#name').val("").change();
    }
    // ajax Forms
    $(document).on('click', '.search_btn', function(e) {
        var url = '';
        var filter1 = $('#filter1').val();
        var salary_grade = $('#salary_grade').val();
        var name = $('#name').val();

        if (filter1 == '') {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select a filter.",
            });
            return false;
        }

        switch (filter1) {
            case 'all':
                var data = {
                    'all': filter1
                };
                break;
            case 'salary_grade':
                if (salary_grade == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please select a salary grade.",
                    });
                    return false;
                }

                var data = {
                    'salary_grade': salary_grade
                }

                break;
            case 'name':
                if (name == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please enter a name.",
                    });
                    return false;
                }

                var data = {
                    'name': name
                }
                break;
        }
        loadTable();
    });

    // ajax non-forms
    $(document).on("click", "#viewPositionsReportsForm",
        function(e) {
            e.preventDefault();
            my = $(this);
            id = my.data('id');
            url = my.attr("href");
            $.ajax({
                type: "POST",
                url: url,
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    page = my.attr("id");
                    if (result.hasOwnProperty("key")) {
                        switch (result.key) {
                            case "viewPositionsReports":
                                page = "";
                                $("#myModal .modal-dialog").attr(
                                    "class",
                                    "modal-dialog modal-md"
                                );
                                $("#myModal .modal-title").html("Preview Position Reports");
                                $("#myModal .modal-body").html(result.form);
                                $("#myModal").modal("show");
                                $.each(my.data(), function(i, v) {
                                    if (i == "is_break") {
                                        if (my.data("is_break") == 1) {
                                            $("#is_break").iCheck("check");
                                        } else $("#is_break").iCheck("uncheck");
                                    } else $('.' + i).val(my.data(i)).change();

                                    if (i == "is_leave_recom") {
                                        if (my.data("is_leave_recom") == 1) {
                                            $("#is_leave_recom").iCheck("check");
                                        } else $("#is_leave_recom").iCheck("uncheck");
                                    } else $('.' + i).val(my.data(i)).change();

                                    if (i == "is_dtr") {
                                        if (my.data("is_dtr") == 1) {
                                            $("#is_dtr").iCheck("check");
                                        } else $("#is_dtr").iCheck("uncheck");
                                    } else $('.' + i).val(my.data(i)).change();

                                    if (i == "is_driver") {
                                        if (my.data("is_driver") == 1) {
                                            $("#is_driver").iCheck("check");
                                        } else $("#is_driver").iCheck("uncheck");
                                    } else $('.' + i).val(my.data(i)).change();
                                });
                                break;

                        }
                        $.when(

                        ).done(function() {
                            $(".selectpicker").selectpicker("destroy");
                            $.each(my.data(), function(i, v) {
                                $("." + i)
                                    .val(my.data(i))
                                    .change();
                            });
                            if (result.key == "viewPositionsReports") {
                                cancelButton = $("#cancelUpdateForm").get(0).outerHTML;
                                $("#myModal .modal-body").append(
                                    '<div class="text-right" style="width:100%;">' +
                                    cancelButton +
                                    "</div>"
                                );
                                $("textarea").attr("disabled", "disable");
                                $("form").find("#cancelUpdateForm").remove();
                                $("#cancelUpdateForm").removeAttr("disabled");
                            }

                            $.AdminBSB.select.activate();

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
        });


    $(document).on("blur", "#expiration_date", function() {
        isValidDate($(this).val());
    });

    $(document).on("click", "#btnAddQualification, #btnAddFile", function() {
        addrow($(this), 2);
    });

    $(document).on("click", ".deleteRow", function() {
        var totTableRows = $(this).closest("tbody").find("tr").length;
        if (totTableRows > 1) $(this).closest("tr").remove();
    });
});


function loadTable() {
    plus_url = "";

    var filter1 = $('#filter1').val();
    var salary_grade_id = $('#salary_grade_id').val();
    var name = $('#name').val();

    switch (filter1) {
        case 'salary_grade':
            plus_url = "?SalaryGradeId=" + salary_grade_id;
            break;
        case 'name':
            plus_url = "?Name=" + name;
            break;
    }

    $("#datatables").DataTable().clear().destroy();
    table = $("#datatables").DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // presumably saves state for reloads -- entries
        bStateSave: true, // presumably saves state for reloads -- page number
        order: [],
        scroller: {
            displayBuffer: 20,
        },
        columnDefs: [{
            targets: [0],
            orderable: false,
        }, ],
        initComplete: function() {
            var input = $('.dataTables_filter input').unbind(),
                self = this.api(),
                $searchButton = $(
                    '<button id="search-vacant-position" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                )
                .html('<i class="material-icons">search</i>')
                .click(function() {
                    if (!$("#search-vacant-position").is(":disabled")) {
                        $("#search-vacant-position").attr("disabled", true);
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

            if ($("#search-vacant-position").length === 0) {
                $('.dataTables_filter').append($searchButton);
            }

        },
        drawCallback: function(settings) {
            $('#search-loader').remove();
            $('#search-vacant-position').removeAttr('disabled');
            $('#datatables button').removeAttr('disabled');
        },
        ajax: {
            url: commons.baseurl + "recruitment/PositionsReports/fetchRows" + plus_url,
            type: 'POST',
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
    $(document).on("click", "#btnXls", function() {
        exportEXCEL("#datatables", 1, "td:eq(6),th:eq(5)");

    });
}