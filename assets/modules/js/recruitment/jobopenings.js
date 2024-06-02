$(function() {
    var base_url = commons.baseurl;

    // $("#datatables").DataTable();

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

    $(document).on("show.bs.modal", "#myModal", function() {
        // addDateMask();
    });

    // Ajax Forms
    $(document).on('click', '.search_btn', function(e) {
        var url = '';
        var filter1 = $('#filter1').val();
        var salary_grade = $('#salary_grade_id').val();
        var name = $('#name').val();
        var place = $('#place').val();
        var is_approve = $('#is_approve').val();
        var is_active = $('#is_active').val();

        if (filter1 == '') {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select a filter.",
            });
            return false;
        }

        switch (filter1) {
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
                        content: "Please enter a position title.",
                    });
                    return false;
                }

                var data = {
                    'name': name
                }
                break;
            case 'place':
                if (place == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please enter a place.",
                    });
                    return false;
                }

                var data = {
                    'place': place
                }
                break;
            case 'status':
                if (is_approve == '' || is_active == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please enter state/status.",
                    });
                    return false;
                }

                var data = {
                    'is_approve': is_approve,
                    'is_active': is_active
                }
                break;
        }
        loadTable();
    });

    $(document).on(
        'click',
        '#testAPI',
        function(e) {
            e.preventDefault();
            my = $(this);
            url = my.attr('href');
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                }
            });
        }
    )

    // ajax non-forms
    $(document).on(
        "click",
        ".viewJobOpeningsForm, .addApplicantsForm, .updateExpirationDateForm",
        function(e) {
            e.preventDefault();
            my = $(this);
            data = my.data();
            id = my.data("id");
            position_id = my.data("position_id");
            url = my.attr("href");
            if (!my.find("button").is(":disabled")) {
                getFields.reloadModal();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: { id: id },
                    dataType: "json",
                    success: function(result) {
                        page = my.attr("id");
                        if (result.hasOwnProperty("key")) {
                            status_key = result.key;
                            switch (status_key) {
                                case "viewJobOpening":
                                    page = "";
                                    $("#myModal .modal-dialog").attr(
                                        "class",
                                        "modal-dialog modal-lg"
                                    );
                                    $("#myModal .modal-title").html(
                                        'Job Opening Details <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success">Print</button>'
                                    );
                                    $("#myModal .modal-body").html(result.form);
                                    $(".modal-lg").css("width", "1060px");
                                    $.when(
                                        getFields.position()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (parseInt($("#printPreview").length) > 0) {
                                                $("#myModal ." + i).html(my.data(i));
                                            } else {
                                                $("#myModal ." + i)
                                                    .val(my.data(i))
                                                    .change();
                                            }
                                        });
                                        if (result.key == "viewJobOpening") {
                                            $.post(
                                                commons.baseurl + "recruitment/JobOpenings/getJobOpeningTables", { id: id },
                                                function(result) {
                                                    result = JSON.parse(result);
                                                    if (result.Code == "0") {

                                                        if (result.Data.vacancyqualifications.length > 0) {
                                                            var education = '';
                                                            var experience = '';
                                                            var training = '';
                                                            var eligibility = '';
                                                            var competency = '';
                                                            var duties = '';
                                                            $("#tbvacancyqualifications").empty();
                                                            $.each(
                                                                result.Data.vacancyqualifications,
                                                                function(k, v) {
                                                                    if (v.category == "Education")
                                                                        education += v.name + " <br>";

                                                                    if (v.category == "Experience")
                                                                        experience += v.name + " <br>";

                                                                    if (v.category == "Training")
                                                                        training += v.name + " <br>";

                                                                    if (v.category == "Eligibility")
                                                                        eligibility += v.name + " <br>";

                                                                    if (v.category == "Competency")
                                                                        competency += v.name + " <br>";

                                                                    if (v.category == "Duties")
                                                                        duties += v.name + " <br>";
                                                                }
                                                            );
                                                            $("#myModal .education").html(education);
                                                            $("#myModal .experience").html(experience);
                                                            $("#myModal .training").html(training);
                                                            $("#myModal .eligibility").html(eligibility);
                                                            $("#myModal .competency").html(competency);
                                                            $("#myModal .duties").html(duties);


                                                        }
                                                        $("#myModal .publication").html("&nbsp;&nbsp;&nbsp;&nbsp;"+my.data('publication_date')+"&nbsp;&nbsp;&nbsp;&nbsp;");
                                                        $("#myModal .posting").html("&nbsp;&nbsp;&nbsp;&nbsp;"+my.data('expiration_date')+"&nbsp;&nbsp;&nbsp;&nbsp;");

                                                        initValidation();
                                                        // addDateMask();
                                                    }
                                                }
                                            );
                                        } else if (result.key == "viewJobOpening") {
                                            $.post(
                                                commons.baseurl + "recruitment/JobOpenings/getJobOpeningTables", { id: id },
                                                function(result) {
                                                    result = JSON.parse(result);
                                                    if (result.Code == "0") {

                                                        var qualifications = result.Data.vacancyqualifications;
                                                        if (qualifications.length > 0) {
                                                            $.each(qualifications, function(k, v) {
                                                                $(".qualification_name" + k).html(v.name == "" ? "N/A" : v.name);
                                                                $(".qualification_category" + k).html(v.category == "" ? "N/A" : v.category);
                                                            });
                                                        }
                                                    }
                                                }
                                            );
                                        }

                                        if (result.key == "viewEmployees") {
                                            $("form")
                                                .find("input, textarea, button, select")
                                                .attr("disabled", "disabled");
                                            $("form").find("#cancelUpdateForm").removeAttr("disabled");
                                            $(".chk,.chkradio").iCheck("destroy");
                                            $(".chk").iCheck({ checkboxClass: "icheckbox_minimal-grey" });
                                            $(".chkradio").iCheck({ radioClass: "iradio_minimal-grey" });
                                        } else {
                                            $(".chk,.chkradio").iCheck("destroy");
                                            $(".chk").iCheck({ checkboxClass: "icheckbox_square-grey" });
                                            $(".chkradio").iCheck({ radioClass: "iradio_square-grey" });
                                        }
                                    });
                                    break;
                                case "addApplicant":
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Add New Applicant');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $.when(
                                        getFields.vacant_job()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i == "id") {
                                                $('#vacancy_id').val(my.data(i));
                                                $('#vacancy_id').attr('style', 'pointer-events: none;');
                                            }
                                        });
                                    });
                                    break;
                                case "updateExpirationDate":
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Update Applicant');
                                    $('#myModal .modal-body').html(result.form);
                                    $(".modal-md").css("width", "1060px");
                                    $('#myModal').modal('show');
                                    $(".expiration_date").daterangepicker({
                                        timePicker: false,
                                        autoApply: false,
                                        drops: "down",
                                        // minDate: moment().startOf("day"),
                                        // maxDate: moment().add(6, "months"),
                                        locale: { format: "YYYY-MM-DD", cancelLabel: 'Clear' },
                                        autoUpdateInput: false,
                                        parentEl: "#myModal .modal-body"  
                                    });
                                    $('.expiration_date').on('apply.daterangepicker', function(ev, picker) {
                                        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                                    });
                                    $('.expiration_date').on('cancel.daterangepicker', function(ev, picker) {
                                        $(this).val('');
                                    });
                                    $(".publication_date").daterangepicker({
                                      singleDatePicker: true,
                                      timePicker: false,
                                      autoApply: false,
                                      drops: "down",
                                      // minDate: moment().startOf("day"),
                                      // maxDate: moment().add(6, "months"),
                                      locale: { format: "YYYY-MM-DD",cancelLabel: 'Clear' },
                                      autoUpdateInput: false,
                                    });
                                    $('.publication_date').on('apply.daterangepicker', function(ev, picker) {
                                        $(this).val(picker.startDate.format('YYYY-MM-DD'));
                                    });
                                    $('.publication_date').on('cancel.daterangepicker', function(ev, picker) {
                                        $(this).val('');
                                    });
                                    $.when(
                                        getFields.positionWithItem()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i == "position_id") {
                                                $('#myModal #' + i).val(my.data(i));
                                                // $('#myModal #' + i).attr('style', 'pointer-events: none;');
                                            } else {
                                                $('#myModal .' + i).val(my.data(i)).change();
                                                if (i != 'expiration_date') {
                                                    // $('#myModal .' + i).attr('style', 'pointer-events: none;');
                                                }
                                            }
                                        });
                                        $('#myModal #position_id').attr('style', 'pointer-events: none;');
                                    });
                                    if (result.key == "updateExpirationDate") {
                                        $.post(
                                            commons.baseurl + "recruitment/JobOpenings/getJobOpeningTables", { id: id },
                                            function(result) {
                                                result = JSON.parse(result);

                                                if (result.Code === 0) {
                                                    if (result.Data.vacancyqualifications.length > 0) {

                                                        $("#tbvacancyqualifications").empty();
                                                        // $("#btnAddQualification").attr('style', 'pointer-events: none; float: right;')
                                                        $.each(
                                                            result.Data.vacancyqualifications,
                                                            function(k, v) {
                                                                let education = v.category === 'Education' ? 'selected' : '';
                                                                let experience = v.category === 'Experience' ? 'selected' : '';
                                                                let training = v.category === 'Training' ? 'selected' : '';
                                                                let eligibility = v.category === 'Eligibility' ? 'selected' : '';
                                                                let competency = v.category === 'Competency' ? 'selected' : '';
                                                                let duties = v.category === 'Duties' ? 'selected' : '';

                                                                $("#tbvacancyqualifications").append(
                                                                    '<tr>' +
                                                                    '<td>' +
                                                                    '<div class="form-group">' +
                                                                    '<div class="form-line">' +
                                                                    // '<input type="text" class="form-control quali_name" data-id="'+v.id+'" name="qualification_name[' + k + ']" id="qualification_name[' + k + ']" pattern="^[^\\s]+(\\s+[^\\s]+)*$" value="' + v.name + '">' +
                                                                    '<textarea class="form-control quali_name" data-id="'+v.id+'" name="qualification_name[' + k + ']" id=qualification_name[' + k + ']" pattern="^[^\\s]+(\\s+[^\\s]+)*$" cols="40" rows="4">' + v.name + '</textarea>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                    '<select name="qualification_category[' + k + ']" id="qualification_category[' + k + ']" class="form-control quali_category" data-live-search="true">' +
                                                                    '<option value="Education" ' + education + '>Education</option>' +
                                                                    '<option value="Experience" ' + experience + '>Experience</option>' +
                                                                    '<option value="Training" ' + training + '>Training</option>' +
                                                                    '<option value="Eligibility" ' + eligibility + '>Eligibility</option>' +
                                                                    '<option value="Competency" ' + competency + '>Competency</option>' +
                                                                    '<option value="Duties" ' + duties + '>Duties and Responsibilities</option>' +
                                                                    '</select>' +
                                                                    '</td>' +
                                                                    '<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right;"><i class="material-icons">remove</i></button></td>' +
                                                                    '</tr>'
                                                                );
                                                            }
                                                        );
                                                    }
                                                }
                                            }
                                        );
                                    }
                                    break;
                            }

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
        }
    );

    $(document).on('click', '.approveJobOpening, .disapprovedJobOpening, .activateJobOpening, .deactivateJobOpening', function(e) {
        e.preventDefault();
        var form = $(this);
        my = $(this);
        data = my.data();
        id = my.data('id');
        is_approve = my.data('is_approve');

        content = "Are you sure you want to proceed?";
        if (form.attr('id') == "approveJobOpening") {
            content = "Are you sure you want to approve job opening?";
            flag = 0;
        }
        if (form.attr('id') == "disapprovedJobOpening") {
            content = "Are you sure you want to disapproved job opening?";
            flag = 1;
        }
        if (form.attr('id') == "activateJobOpening") {
            content = "Are you sure you want to activate job opening?";
            flag = 1;
        }
        if (form.attr('id') == "deactivateJobOpening") {
            content = "Are you sure you want to deactivate job opening?";
            flag = 1;
        }

        url = my.attr('href');
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
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
                                    data: {
                                        id: id,
                                    },
                                    dataType: "json",
                                    success: function(result) {
                                        if (result.hasOwnProperty("key")) {
                                            if (result.Code == "0") {
                                                if (result.hasOwnProperty("key")) {
                                                    switch (result.key) {
                                                        case 'approveJobOpening':
                                                        case 'disapprovedJobOpening':
                                                        case 'activateJobOpening':
                                                        case 'deactivateJobOpening':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-default">Send Notification</label>');
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
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            },
                        });
                    }

                },
                cancel: function() {}
            }
        });
    });

    // ajax forms
    $(document).on('submit', '#addApplicant, #updateApplicant, #updateExpirationDate', function(e) {
        e.preventDefault();
        var form = $(this);

        // if (form.attr('id') == 'updateExpirationDate') {
        //     var flag = isValidDate($('#expiration_date').val());

        //     if (!flag) {
        //         return false;
        //     }
        // }

        content = "Are you sure you want to proceed?";
        if (form.attr('id') == "addApplicant") {
            content = "Are you sure you want to add job opening?";
        }
        if (form.attr('id') == 'updateApplicant') {
            content = "Are you sure you want to update job opening?";
        }
        if (form.attr('id') == 'updateExpirationDate') {
            content = "Are you sure you want to update job opening expiration date?";
        }
        url = form.attr('action');

        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function() {
                        // code here
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
                                                        case 'addApplicant':
                                                        case 'updateApplicant':
                                                        case 'updateExpirationDate':
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
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }
                },
                cancel: function() {

                }
            }
        });
    });

    $(document).on("click", ".viewJobQualification", function(e) {
        e.preventDefault();
        my = $(this);
        data = my.data();
        id = my.data("id");
        url = my.attr("href");
        $.ajax({
            type: "POST",
            url: url,
            data: { id: id },
            dataType: "json",
            success: function(result) {
                page = my.attr("id");

                $("#myModal .modal-dialog").attr(
                    "class",
                    "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                    'Qualifications'
                );
                $("#myModal .modal-body").html(result.form);
                $(".modal-lg").css("width", "1060px");
                $("#myModal").modal("show");
                $.when(

                ).done(function() {
                    $.post(
                        commons.baseurl + "recruitment/JobOpenings/getJobOpeningtables", { id: id },
                        function(result) {
                            result = JSON.parse(result);
                            if (result.Code == "0") {
                                if (result.Data.vacancyqualifications.length > 0) {

                                    $("#tbqualifications").empty();
                                    $.each(
                                        result.Data.vacancyqualifications,
                                        function(k, v) {
                                            $("#tbqualifications").append(
                                                "<tr>" +
                                                "<td>" +
                                                v.name +
                                                "</td>" +
                                                "<td>" +
                                                v.category +
                                                "</td>" +
                                                "</tr>"
                                            );
                                        }
                                    );
                                }
                            }
                        }
                    );
                });
            },
            error: function(result) {
                $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "There was an error in the connection. Please contact the administrator for updates.",
                });
            },
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

    $(document).on("click", "#btnXls", function() {
        exportEXCEL("#datatables", 1, "td:eq(9),th:eq(9)");
    });
});

function field_reset() {
    $('#name').val("").change();
    $('#location').val("").change();
    $('#salary_grade').val("").change();
}

function loadTable() {
    let today = new Date().toLocaleDateString();
    plus_url = "";

    var filter1 = $('#filter1').val();
    var salary_grade_id = $('#salary_grade_id').val();
    var name = $('#name').val();
    var place = $('#place').val();
    var is_approve = $('#is_approve').val();
    var is_active = $('#is_active').val();

    switch (filter1) {
        case 'name':
            plus_url = "?Name=" + name;
            break;
        case 'place':
            plus_url = "?Place=" + place;
            break;
        case 'salary_grade':
            plus_url = "?SalaryGradeId=" + salary_grade_id;
            break;
        case 'status':
            plus_url = "?IsApprove=" + is_approve + "&IsActive=" + is_active;
            break;
    }

    $("#datatables").DataTable().clear().destroy();
    table = $("#datatables").DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        processing: true,
        serverSide: true,
        order: [],
        scroller: {
            displayBuffer: 20,
        },
        columnDefs: [{
            targets: [0, 9],
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
                        $("#datatables button").attr("disabled", true);
                        self.search(input.val()).draw();
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
                            "&emsp;Please Wait..</div>",
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
            url: commons.baseurl + "recruitment/JobOpenings/fetchRows" + plus_url,
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
    //     fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
    //         if (Date.parse(aData[6]) < Date.parse(today)) {
    //             $('td', nRow).css('background-color', '#D3D3D3');

    //             if (!aData[8].includes('INACTIVE')) {
    //                 $.ajax({
    //                     type: 'POST',
    //                     url: commons.baseurl + 'recruitment/JobOpenings/deactivateJobOpening',
    //                     data: { id: aData[0] },
    //                     dataType: "json",
    //                     success: function(result) {
    //                         loadTable();
    //                     },
    //                     error: function(result) {
    //                         console.log(result);
    //                     }
    //                 });
    //             }
    //         }
    //     },
    //     initComplete: function() {
    //         var input = $('.dataTables_filter input').unbind(),
    //             self = this.api(),
    //             $searchButton = $(
    //                 '<button id="search-job-opening" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
    //             )
    //             .html('<i class="material-icons">search</i>')
    //             .click(function() {
    //                 if (!$("#search-job-opening").is(":disabled")) {
    //                     $("#search-job-opening").attr("disabled", true);
    //                     self.search(input.val()).draw();
    //                     $("#datatables button").attr("disabled", true);
    //                     $(".dataTables_filter").append(
    //                         '<div id="search-loader"><br>' +
    //                         '<div class="preloader pl-size-xs">' +
    //                         '<div class="spinner-layer pl-red-grey">' +
    //                         '<div class="circle-clipper left">' +
    //                         '<div class="circle"></div>' +
    //                         "</div>" +
    //                         '<div class="circle-clipper right">' +
    //                         '<div class="circle"></div>' +
    //                         "</div>" +
    //                         "</div>" +
    //                         "</div>" +
    //                         "&emsp;Please Wait..</div>"
    //                     );
    //                 }
    //             });

    //         if ($("#search-job-opening").length === 0) {
    //             $('.dataTables_filter').append($searchButton);
    //         }

    //     },
    //     drawCallback: function(settings) {
    //         $('#search-loader').remove();
    //         $('#search-job-opening').removeAttr('disabled');
    //         $('#datatables button').removeAttr('disabled');
    //     },
    //     ajax: {
    //         url: commons.baseurl + "recruitment/JobOpenings/fetchRows" + plus_url,
    //         type: 'POST',
    //     },
    //     oLanguage: {
    //         sProcessing: '<div class="preloader pl-size-sm">' +
    //             '<div class="spinner-layer pl-red-grey">' +
    //             '<div class="circle-clipper left">' +
    //             '<div class="circle"></div>' +
    //             "</div>" +
    //             '<div class="circle-clipper right">' +
    //             '<div class="circle"></div>' +
    //             "</div>" +
    //             "</div>" +
    //             "</div>",
    //     },
    // });


}

function addDateMask() {
    $(".date_mask, .datepicker").each(function() {
        if ($(this).hasClass("date_mask") && $(this).closest("td").find("input:last").hasClass("chk")) {
            if (!$(this).closest("td").find("input:last").iCheck("update")[0].checked) {
                $(this).inputmask("mm/dd/yyyy", { placeholder: "mm/dd/yyyy", });
            }
        } else {
            $(this).inputmask("mm/dd/yyyy", { placeholder: "mm/dd/yyyy", });
        }
    });

    $(".id").inputmask("99-9", { placeholder: "__-_" });
}

function isValidDate(value) {
    var flag = 0;
    var message = "Invalid date format!";
    var timestamp = Date.parse(value);
    var schedule_date = false;
    var now = new Date();
    now.setHours(0, 0, 0, 0);

    // if (isNaN(timestamp) === false) {
    //     schedule_date = new Date(timestamp);
    //     flag = 1;
    //     if (schedule_date < now) {
    //         flag = 0;
    //         message = "Selected date is in the past";
    //     }
    // }

    if (!flag) {
        $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: message,
            buttons: {
                ok: function() {
                    $(this).focus();
                },
            }
        });
    }

    return flag;
}

$(document).on("click", "#btnAddQualification, #btnAddFile", function () {
        addrow($(this), 2);
    });

$(document).on("click", ".deleteRow", function () {
    id = $(this).closest("tr").find('.quali_name').data('id');
    name = $(this).closest("tr").find('.quali_name').val();
    category = $(this).closest("tr").find('.quali_category').val();

        $.post(
            commons.baseurl + "recruitment/JobOpenings/deleteQualifications", {id: id, name: name, category: category},
            function(result) {
                result = JSON.parse(result);
                if (result.Code == "0") {
                    
                }
            }
        );

    var totTableRows = $(this).closest("tbody").find("tr").length;
    if (totTableRows > 1) $(this).closest("tr").remove();
});

function addrow(element, count) {
    var cloneRow = "";
    var inc = "";
    if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI") {
        var cloneRow = element.closest("div").find("table tbody tr:first").clone();
        inc = element
            .closest("div")
            .find("table tbody tr:first")
            .find(".form-control")
            .attr("name");
    } else {
        var cloneRow = element.closest("div").find("table tbody tr:last").clone();
        inc = element
            .closest("div")
            .find("table tbody tr:last")
            .find(".form-control")
            .attr("name");
    }

    $is = false;
    if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
        inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
        $is = true;
    } else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
    cloneRow.find(".form-group").find("label.error").remove();
    for (var i = 0; i < count; i++) {
        if (cloneRow.closest("td").find("a").length == 0) {
            var elementName = "";

            if ($is)
                elementName =
                cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
                "[" +
                inc +
                "]";
            else {
                elementName =
                    cloneRow.find(".form-control").eq(i).attr("name").slice(0, -4) +
                    "[" +
                    inc +
                    "]";
            }
            cloneRow
                .find(".form-control")
                .eq(i)
                .attr("name", elementName)
                .attr("id", elementName)
                .addClass(
                    element.attr("id") === "btnViewAddFile" && i == 4 ?
                    "inputRequired" :
                    ""
                );
        } else {
            cloneRow.find("a").remove();
        }
    }

    cloneRow.find(".iCheck-helper").remove();
    cloneRow.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position", "").css("display", "inline-table");
    cloneRow.find(".form-control").val("").end();
    if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI")
        element.closest("div").find("table tbody").prepend(cloneRow);
    else element.closest("div").find("table tbody").append(cloneRow);
    // addDateMask();
    // initValidation();
    autosize($("textarea.auto-growth"));
}

function initValidation() {
    $("input:not(.inputRequired)").each(function() {
        $(this).rules("remove", "required");
    });

    $("textarea:not(.inputRequired)").each(function() {
        $(this).rules("remove", "required");
    });

    $(".chkradio").each(function() {
        $(this).rules("add", {
            required: true
        });
    });

    $("input.inputRequired, textarea.inputRequired").each(function() {
        $(this).rules("add", {
            required: function(element) {
                return $(element).val().trim() == "";
            },
            normalizer: function(value) {
                return $.trim(value);
            },
        });
    });

    $("input.inputifyes").each(function() {
        $(this).rules("add", {
            required: function(element) {
                var num = parseInt($(element).attr("name").slice(-2));
                return (
                    $(
                        "input[name='radio_input_" +
                        (num < 10 ? "0" + num : num) +
                        "']:checked"
                    ).val() == "Yes" && $(element).val().trim() == ""
                );
            },
        });
    });

    $(".inputFile").each(function() {
        $(this).rules("add", {
            required: function(element) {
                var tdelem = $(element)
                    .closest("tr")
                    .find("td:first-child")
                    .find(".form-control:eq(2)");
                return tdelem.val() == "" && $(element).val().trim() == "";
            },
        });
    });

    $("input.is_first_col_required, textarea.is_first_col_required").each(
        function() {
            $(this).rules("add", {
                required: function(element) {
                    var tdelem = $(element)
                        .closest("tr")
                        .find("td:first-child")
                        .find(".form-control:last");
                    return (
                        tdelem.val() != "N/A" &&
                        tdelem.val() != "n/a" &&
                        $(element).val().trim() == ""
                    );
                },
            });
        }
    );

    $("input.is_sec_col_required, textarea.is_sec_col_required").each(
        function() {
            $(this).rules("add", {
                required: function(element) {
                    var tdelem = $(element)
                        .closest("tr")
                        .find("td:eq(1)")
                        .find(".form-control");
                    return (
                        tdelem.val() != "N/A" &&
                        tdelem.val() != "n/a" &&
                        $(element).val().trim() == ""
                    );
                },
            });
        }
    );

    $("input.is_third_col_required, textarea.is_third_col_required").each(
        function() {
            $(this).rules("add", {
                required: function(element) {
                    var tdelem = $(element)
                        .closest("tr")
                        .find("td:eq(2)")
                        .find(".form-control");
                    return (
                        tdelem.val() != "N/A" &&
                        tdelem.val() != "n/a" &&
                        $(element).val().trim() == ""
                    );
                },
            });
        }
    );

    $(".chk").iCheck("destroy");
    $(".chk").iCheck({
        checkboxClass: "icheckbox_square-grey",
    });
}

function tmpNextBtn() {
    var steps = $("#addEmployees .steps").find("ul");
    var actions = $("#addEmployees .actions").find("ul").find("li:eq(1)");
    if (steps.find("li:eq(0)").hasClass("current")) {
        if (actions.find("button").length == 0) {
            actions.append(
                "<button type='button' id='btntempnext' class='btn btn-lg waves-effect' style='background-color: #009688; color: #fff;padding: 0.5em 1em;font-size: 14px;'>Next</button>"
            );
        } else {
            actions.find("button").css("display", "");
        }
        actions.find("a").css("display", "none");
    } else {
        actions.find("a").css("display", "");
        if (actions.find("button").length > 0) {
            actions.find("button").css("display", "none");
        }
    }
}