$(function() {
    var base_url = commons.baseurl;
    addDateMask();
    $(document).on('change', '#filter1', function(e) {
        var filter = $(this).val();

        $('.filter2').hide();
        field_reset();
        switch (filter) {
            case 'vacancy':
                $('.vacancy_id_div').show();
                $.when(
                    getFields.vacant_job()
                ).done(function() {
                    $.AdminBSB.select.activate();
                });
                break;
            case 'status':
                $('.status_div').show();
                $.when(
                    getFields.applicantStatus()
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
        addDateMask();
    });

    // Ajax Forms
    $(document).on('click', '.search_btn', function(e) {
        var url = '';
        var filter1 = $('#filter1').val();
        var vacancy_id = $('#position_id').val();
        var name = $('#name').val();
        var application_status = $('#application_status').val();

        if (filter1 == '') {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select a filter.",
            });
            return false;
        }

        switch (filter1) {
            case 'vacancy_id':
                if (vacancy_id == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please select a position title.",
                    });
                    return false;
                }

                var data = {
                    'vacancy_id': vacancy_id
                };

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
                };

                break;
            case 'status':
                if (application_status == '') {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "Please enter a status",
                    });
                    return false;
                }

                var data = {
                    'status': application_status
                };

                break;
        }
        loadTable();
    });

    // ajax non-forms
    $(document).on(
        "click",
        "#addApplicantsForm, .updateApplicantsForm, .viewApplicantsForm, .addExaminationSchedulesForm, .viewExaminationSchedulesForm, .addInterviewSchedulesForm, .viewInterviewSchedulesForm, .recommendJobForm, .addApplicantChecklistsForm",
        function(e) {
            e.preventDefault();
            my = $(this);
            data = my.data();
            id = my.attr("data-id");
            url = my.attr("href");
            schedule_id = my.attr("data-schedule_id");

            if (!my.find("button").is(":disabled")) {
                getFields.reloadModal();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: { id: id, last_name: my.data('last_name'), first_name: my.data('first_name') },
                    dataType: "json",
                    success: function(result) {
                        page = my.attr("id");
                        if (result.hasOwnProperty("key")) {
                            status_key = result.key;
                            switch (status_key) {
                                case 'addApplicant':
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Add New Applicant');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $("#myModal .add").show();
                                    $("#myModal .update").hide();
                                    $.when(
                                        getFields.vacant_job()
                                    ).done(function() {
                                        $.AdminBSB.select.activate();
                                    });
                                    break;
                                case 'updateApplicant':
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Update Applicant');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $("#myModal .add").hide();
                                    $("#myModal .update").removeAttr('style');
                                    $.when(
                                        getFields.vacant_job()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i === "vacancy_id") {
                                                $('.' + i).val(my.data(i));
                                            } else {
                                                $('#' + i).val(my.data(i)).change();
                                            }
                                        });
                                        $.AdminBSB.select.activate();
                                        // var applicant_folder = my.data("last_name") + "_" + my.data("first_name");
                                        // $('#viewAttachment').attr('href', commons.baseurl + "assets/uploads/applicants/" + applicant_folder + "/" + my.data("filename"));
                                        // $('#downloadAttachment').attr('href', commons.baseurl + "assets/uploads/applicants/" + applicant_folder + "/" + my.data("filename"));

                                    });
                                    break;
                                case "viewApplicant":
                                    page = "";
                                    $("#myModal .modal-dialog").attr(
                                        "class",
                                        "modal-dialog modal-lg"
                                    );
                                    $("#myModal .modal-title").html(
                                        'Applicant Details'
                                    );
                                    $("#myModal .modal-body").html(result.form);
                                    $(".modal-lg").css("width", "1060px");
                                    $("#myModal .add").hide();
                                    $.when(
                                        getFields.vacant_job()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i === "vacancy_id") {
                                                $('.' + i).val(my.data(i));
                                                $('.' + i).attr('style', 'pointer-events: none;');
                                            } else {
                                                $('#' + i).val(my.data(i)).change();
                                                $('#' + i).attr('style', 'pointer-events: none;');
                                            }
                                        });
                                        // var applicant_folder = my.data("last_name") + "_" + my.data("first_name");
                                        // $('#viewAttachment').attr('href', commons.baseurl + "assets/uploads/applicants/" + applicant_folder + "/" + my.data("filename"));
                                        // $('#downloadAttachment').attr('href', commons.baseurl + "assets/uploads/applicants/" + applicant_folder + "/" + my.data("filename"));

                                    });
                                    break;
                                case "addExaminationSchedule":
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Add Examination Schedule');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $.when(
                                        getFields.positionWithItem()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i !== 'examination_schedule_date' && i !== 'examination_schedule_time' && i !== 'examination_remarks') {
                                                $('.' + i).val(my.data(i)).change();
                                                $('.' + i).attr('style', 'pointer-events: none;');
                                            }
                                        });
                                        $.AdminBSB.select.activate();
                                    });
                                    break;
                                case "viewExaminationSchedule":
                                    page = "";
                                    $("#myModal .modal-dialog").attr(
                                        "class",
                                        "modal-dialog modal-lg"
                                    );
                                    $("#myModal .modal-title").html(
                                        'Examination Schedule Detail'
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
                                    });
                                    break;
                                case "addInterviewSchedule":
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Add Interview Schedule');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $.when(
                                        getFields.position()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i !== 'schedule_date' && i !== 'schedule_time' && i !== 'remarks') {
                                                $('.' + i).val(my.data(i)).change();
                                                $('.' + i).attr('style', 'pointer-events: none;');
                                            }
                                        });
                                        $.AdminBSB.select.activate();
                                    });
                                    break;
                                case "viewInterviewSchedule":
                                    page = "";
                                    $("#myModal .modal-dialog").attr(
                                        "class",
                                        "modal-dialog modal-lg"
                                    );
                                    $("#myModal .modal-title").html(
                                        'Interview Schedule Detail'
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
                                    });
                                    break;
                                case "recommendJob":
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Recommend Job Opening');
                                    $('#myModal .modal-body').html(result.form);
                                    $('#myModal').modal('show');
                                    $.when(
                                        getFields.vacant_job()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            if (i == 'vacancy_id') {
                                                $('#vacancy_id option[value="' + my.data(i) + '"]').remove();
                                            } else {
                                                $('#myModal #' + i).val(my.data(i));
                                            }
                                        });
                                        $.AdminBSB.select.activate();
                                    });
                                    break;
                                case 'addApplicantChecklist':
                                    page = "";
                                    $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
                                    $('#myModal .modal-title').html('Add New Applicant Checklist');
                                    $('#myModal .modal-body').html(result.form);
                                    $(".modal-lg").css("width", "1060px");
                                    $('#myModal').modal('show');
                                    $.when(
                                        getFields.position(),
                                        getFields.employee()
                                    ).done(function() {
                                        $.each(my.data(), function(i, v) {
                                            $('.modal-body #' + i).val(my.data(i)).change();
                                        });
                                        $.AdminBSB.select.activate();
                                        if (result.key == "addApplicantChecklist") {
                                            $.post(
                                                commons.baseurl + "fieldmanagement/Guidelines/getActiveGuidelines",
                                                function(result) {
                                                    result = JSON.parse(result);
                                                    if (result.Code == "0") {
                                                        if (result.Data.details.length > 0) {
                                                            var guidelines = result.Data.details;
                                                            var items = result.Data.items;
                                                            var first_day = '';
                                                            var policies = '';
                                                            var administrative_procedure = '';
                                                            var general_orientation = '';
                                                            var position_information = '';
                                                            var first_day_item = '';
                                                            var policies_item = '';
                                                            var administrative_procedure_item = '';
                                                            var general_orientation_item = '';
                                                            var position_information_item = '';

                                                            // Get employee guidelines by category
                                                            // static category: FIRST DAY, POLICIES, ADMINISTRATIVE PROCEDURES, POSITION INFORMATION
                                                            $.each(guidelines, function(k, v) {
                                                                if (v.category === 'FIRST DAY') {
                                                                    first_day += '<input type="hidden" value="0" name="first_day[' + v.id + ']">';
                                                                    first_day += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="first_day[' + v.id + ']" id="first_day[' + v.id + ']">';
                                                                    first_day += '<label for="first_day[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
                                                                    first_day_item += '<div class="row"><div class="col-md-12" id="guideline_id_' + v.id + '"></div></div>';
                                                                }
                                                                if (v.category === 'POLICIES') {
                                                                    policies += '<input type="hidden" value="0" name="policies[' + v.id + ']">';
                                                                    policies += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="policies[' + v.id + ']" id="policies[' + v.id + ']">';
                                                                    policies += '<label for="policies[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
                                                                    policies_item += '<div class="row"><div class="col-md-12" id="guideline_id_' + v.id + '"></div></div>';
                                                                }
                                                                if (v.category === 'ADMINISTRATIVE PROCEDURES') {
                                                                    administrative_procedure += '<input type="hidden" value="0" name="administrative_procedure[' + v.id + ']">';
                                                                    administrative_procedure += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="administrative_procedure[' + v.id + ']" id="administrative_procedure[' + v.id + ']">';
                                                                    administrative_procedure += '<label for="administrative_procedure[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
                                                                    administrative_procedure_item += '<div class="row"><div class="col-md-12" id="guideline_id_' + v.id + '"></div></div>';
                                                                }
                                                                if (v.category === 'GENERAL ORIENTATION') {
                                                                    general_orientation += '<input type="hidden" value="0" name="general_orientation[' + v.id + ']">';
                                                                    general_orientation += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="general_orientation[' + v.id + ']" id="general_orientation[' + v.id + ']">';
                                                                    general_orientation += '<label for="general_orientation[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
                                                                    general_orientation_item += '<div class="row"><div class="col-md-12" id="guideline_id_' + v.id + '"></div></div>';
                                                                }
                                                                if (v.category === 'POSITION INFORMATION') {
                                                                    position_information += '<input type="hidden" value="0" name="position_information[' + v.id + ']">';
                                                                    position_information += '<input type="checkbox" class="filled-in chk-col-green" value="1" name="position_information[' + v.id + ']" id="position_information[' + v.id + ']">';
                                                                    position_information += '<label for="position_information[' + v.id + ']"><strong>' + v.name + '</strong></label><br>';
                                                                    position_information_item += '<div class="row"><div class="col-md-12" id="guideline_id_' + v.id + '"></div></div>';
                                                                }
                                                            });

                                                            first_day += '<br>';
                                                            policies += '<br>';
                                                            administrative_procedure += '<br>';
                                                            general_orientation += '<br>';
                                                            position_information += '<br>';

                                                            $("#myModal #first_day").html(first_day);
                                                            $("#myModal #policies").html(policies);
                                                            $("#myModal #administrative_procedure").html(administrative_procedure);
                                                            $("#myModal #general_orientation").html(general_orientation);
                                                            $("#myModal #position_information").html(position_information);
                                                            $("#myModal #first_day_item").html(first_day_item);
                                                            $("#myModal #policies_item").html(policies_item);
                                                            $("#myModal #administrative_procedure_item").html(administrative_procedure_item);
                                                            $("#myModal #general_orientation_item").html(general_orientation_item);
                                                            $("#myModal #position_information_item").html(position_information_item);

                                                            // display employee guideline items.
                                                            // displays from left to right
                                                            $.each(items, function(k, v) {
                                                                $.each(guidelines, function(pk, pv) {
                                                                    if (pv.id === v.guideline_id) {
                                                                        var elem = "#guideline_id_" + pv.id;
                                                                        $(elem).append('<div class="col-md-6">* ' + v.name + '</div>');
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    }
                                                }
                                            );
                                        }
                                    });
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

    $(document).on('click', '.acceptApplicant, .disapproveApplicant, .disapproveAfterDeliberation, .withdrawApplicant, .blockApplicant', function(e) {
        e.preventDefault();
        var form = $(this);
        my = $(this);
        data = my.data();
        id = my.data('id');
        content = "Are you sure you want to proceed?";

        if (form.attr('id') == "acceptApplicant") {
            content = "Are you sure you want to approve applicant?";
        }
        if (form.attr('id') == "disapproveApplicant" || form.attr('id') == "disapproveAfterDeliberation") {
            content = "Are you sure you want to disapproved applicant?";
        }
        if (form.attr('id') == "blockApplicant") {
            content = "Are you sure you want to block applicant?";
        }
        if (form.attr('id') == "withdrawApplicant") {
            content = "Are you sure you want to withdraw applicant?";
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
                                        if (result.Code == "0") {
                                            if (result.hasOwnProperty("key")) {
                                                switch (result.key) {
                                                    case 'acceptApplicant':
                                                    case 'disapproveApplicant':
                                                    case 'disapproveAfterDeliberation':
                                                    case 'withdrawApplicant':
                                                    case 'blockApplicant':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-default">Success</label>');
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
                                    },
                                    error: function(result) {
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            },
                            buttons: {
                                ok: function() {}
                            }
                        });
                    }
                },
                cancel: function() {}
            }
        });
    });

    $(document).on('click', '#cancelApplicant, .close', function() {
        $('.filter-option-inner-inner').text('ALL');
        $('#filter1').prop('selectedIndex', 0);
        $('.filter2').hide();
    });

    // ajax forms
    $(document).on('submit', '#addApplicant, #updateApplicant, #addExaminationSchedule, #addInterviewSchedule, #recommendJob, #addApplicantChecklist', function(e) {
        e.preventDefault();
        var form = $(this);

        if (form.attr('id') == 'addInterviewSchedule') {
            var flag = isValidDate($('#schedule_date').val());

            if (!flag) {
                return false;
            }

            flag = isValidTime($('#schedule_time').val(), $('#schedule_date').val());

            if (!flag) {
                return false;
            }
        }

        if (form.attr('id') == 'addExaminationSchedule') {
            var flag = isValidDate($('#examination_schedule_date').val());

            if (!flag) {
                return false;
            }

            flag = isValidTime($('#examination_schedule_time').val(), $('#examination_schedule_date').val());

            if (!flag) {
                return false;
            }
        }

        content = "Are you sure you want to proceed?";
        if (form.attr('id') == "addApplicant") {
            content = "Are you sure you want to add applicant?";
        }
        if (form.attr('id') == 'updateApplicant') {
            content = "Are you sure you want to update applicant?";
        }
        if (form.attr('id') == 'addExaminationSchedule') {
            content = "Are you sure you want to schedule examination?";
        }
        if (form.attr('id') == 'addInterviewSchedule') {
            content = "Are you sure you want to schedule interview?";
        }
        if (form.attr('id') == 'recommendJob') {
            content = "Are you sure you want to send job opening recommendation?";
        }
        if (form.attr('id') == "addApplicantChecklist") {
            content = "Are you sure you want to add applicant checklist?";
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
                                                        case 'addExaminationSchedule':
                                                        case 'addInterviewSchedule':
                                                        case 'recommendJob':
                                                        case 'addApplicantChecklist':
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

    $(document).on("blur", "#schedule_date", function() {
        isValidDate($(this).val());
    });

    $(document).on("blur", "#schedule_time", function() {
        isValidTime($(this).val(), $('#schedule_date').val());
    });
});

function field_reset() {
    $('#vacancy_id').val("").change();
    $('#name').val("").change();
}

function loadTable() {
    plus_url = "";

    var filter1 = $('#filter1').val();
    var vacancy_id = $('#vacancy_id').val();
    var name = $('#name').val();
    var application_status = $('#application_status').val();

    switch (filter1) {
        case 'vacancy':
            plus_url = "?VacancyID=" + vacancy_id;
            break;
        case 'name':
            plus_url = "?Name=" + name;
            break;
        case 'status':
            plus_url = "?Status=" + application_status;
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
            targets: [0],
            orderable: false,
        }, ],
        initComplete: function() {
            var input = $('.dataTables_filter input').unbind(),
                self = this.api(),
                $searchButton = $(
                    '<button id="search-applicant" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">'
                )
                .html('<i class="material-icons">search</i>')
                .click(function() {
                    if (!$("#search-applicant").is(":disabled")) {
                        $("#search-applicant").attr("disabled", true);
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

            if ($("#search-applicant").length === 0) {
                $('.dataTables_filter').append($searchButton);
            }

        },
        drawCallback: function(settings) {
            $('#search-loader').remove();
            $('#search-applicant').removeAttr('disabled');
            $('#datatables button').removeAttr('disabled');
        },
        ajax: {
            url: commons.baseurl + "recruitment/Applicants/fetchRows" + plus_url,
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
}

function isValidDate(value) {
    var flag = 0;
    var message = "Invalid date format!";
    var timestamp = Date.parse(value);
    var schedule_date = false;
    var now = new Date();
    now.setHours(0, 0, 0, 0);

    if (isNaN(timestamp) === false) {
        schedule_date = new Date(timestamp);
        flag = 1;
        if (schedule_date < now) {
            flag = 0;
            message = "Selected date is in the past";
        }
    }

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

function isValidTime(value, date) {
    var flag = 0;
    var message = "Invalid time format!";
    var timestamp = Date.parse(date);
    var now = new Date();
    var current_time = new Date().getHours();
    var hour = value.substring(0, 2);
    now.setHours(0, 0, 0, 0);

    if (isNaN(timestamp) === false) {
        var schedule_date = new Date(timestamp);

        if (schedule_date.getTime() === now.getTime()) {
            if (hour < current_time) {
                message = "Selected time is in the past";
            } else if (hour >= 8 && hour <= 16) {
                flag = 1;
            } else {
                message = "Interview schedule must be during work hours. Between 8AM to 5PM";
            }
        } else {
            if (hour >= 8 && hour <= 16) {
                flag = 1;
            } else {
                message = "Interview schedule must be during work hours. Between 8AM to 5PM";
            }
        }
    }

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
    addDateMask();
    initValidation();
    autosize($("textarea.auto-growth"));
}

function initValidation() {
    $("textarea:not(.inputRequired)").each(function() {
        $(this).rules("remove", "required");
    });

    $(".chkradio").each(function() {
        $(this).rules("add", {
            required: true
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

    // $(".chk").iCheck("destroy");
    // $(".chk").iCheck({
    // 	checkboxClass: "icheckbox_square-grey",
    // });
}

$(document).on("click", "#addrow", function (e) {
    e.preventDefault();
    $('.newfile').append(
        '<div class="form-line">'+
        '<input type="file" class="file form-control" name="file[]" id="file">'+
        '<button id="removerow" class="btn btn-danger btn-sm waves-effect" style="float: right;">' +
        '<i class="material-icons">remove</i>'+
        '</button>'+
        '</div>'
    );
});

$(document).on("click", "#removerow", function (e) {
    e.preventDefault();
    $(this).closest(".form-line").remove();
});


$(document).on("click", "#changeAttachment", function (e) {
    e.preventDefault();
    me = $(this);
    var id = me.attr("data-id");
    $("#hiddenFileInput"+id+"").show();
});

$(document).on("click", "#cancelChange", function (e) {
    e.preventDefault();
    me = $(this);
    var id = me.attr("data-id");
    $("#hiddenFileInput"+id+"").hide();
    $(".file_upload_"+id+"").val('');
});

$(document).on("click", "#deleteAttachment", function (e) {
    e.preventDefault();
    me = $(this);
    var id = me.attr("data-id");
    $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: "Are you sure you want to delete this file?",
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
                                url: commons.baseurl + "recruitment/Applicants/deletefile",
                                data: {id: id},
                                // contentType: false,
                                // processData: false,
                                dataType: "json",
                                success: function(result) {
                                    if (result.hasOwnProperty("key")) {
                                        if (result.Code == "0") {
                                            self.setTitle('<label class="text-success">Success</label>');
                                            self.setContent(result.Message);
                                            $(".view_"+id+"").remove();
                                            $(".download_"+id+"").remove();
                                            $(".change_"+id+"").remove();
                                            $(".delete_"+id+"").remove();
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