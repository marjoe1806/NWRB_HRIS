$(function() {
    var page = "";
    base_url = commons.base_url;
    var table;
    // loadTable();
    $.when(
        getFields.payBasis3(), 
        getFields.division()
        // getFields.location() 
    ).done(function() {
        $("#pay_basis").val("Permanent-Casual").change();
        $.AdminBSB.select.activate();
        $("#pay_basis option").eq(2).remove();
        $("#pay_basis").selectpicker("refresh");
    });

    $(document).on("show.bs.modal", "#myModal", function() {
        $(".datepicker").bootstrapMaterialDatePicker({
            format: "YYYY-MM-DD",
            clearButton: true,
            weekStart: 1,
            time: false,
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
                    ($(this).popover("hide").data("bs.popover") || {}).inState || {}
                ).click = false; // fix for BS 3.3.6
            }
        });
    });

    const addCommas = (x) => {
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
                            $("#myModal .modal-body").css("position", "relative");
                            $("#myModal .modal-body").css("overflow-y", "auto");
                            $("#myModal .modal-body").css("overflow-x", "auto");
                            // $("#myModal .modal-body").css("max-height","400px");
                            $("#myModal .modal-body").css("padding", "15px");
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
                            ".email": { required: true, email: true },
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
    // $(document).on("click", ".addServiceRecord", function(e) {
    //     e.preventDefault();
    //     my = $(this);
    //     url = my.attr("href");
    //     employee_id = my.data("employee_id");
    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: { employee_id: employee_id },
    //         dataType: "json",
    //         success: function(result) {
    //             page = my.attr("id");
    //             if (result.hasOwnProperty("key")) {
    //                 switch (result.key) {
    //                     case "addServiceRecord":
    //                         page = "";
    //                         $("#myModal .modal-dialog").attr(
    //                             "class",
    //                             "modal-dialog modal-lg"
    //                         );
    //                         modal_title =
    //                             "Service Record: " +
    //                             result.data.employee_id_number +
    //                             " - " +
    //                             result.data.first_name +
    //                             " " +
    //                             result.data.middle_name +
    //                             " " +
    //                             result.data.last_name;
    //                         $("#myModal .modal-title").html(modal_title);
    //                         $("#myModal .modal-body").html(result.form);
    //                         $("#myModal").modal("show");
    //                         $.each(my.data(), function(i, v) {
    //                             $("." + i).val(addCommas(v));
    //                         });
    //                         $("form input:not(:button),form input:not(:submit)").attr(
    //                             "disabled",
    //                             false
    //                         );
    //                         break;
    //                 }
    //                 $("#" + result.key).validate({
    //                     rules: {
    //                         ".required": { required: true },
    //                         ".email": { required: true, email: true },
    //                     },
    //                     highlight: function(input) {
    //                         $(input).parents(".form-line").addClass("error");
    //                     },
    //                     unhighlight: function(input) {
    //                         $(input).parents(".form-line").removeClass("error");
    //                     },
    //                     errorPlacement: function(error, element) {
    //                         $(element).parents(".form-group").append(error);
    //                     },
    //                 });
    //             }
    //         },
    //         error: function(result) {
    //             $.alert({
    //                 title: '<label class="text-danger">Failed</label>',
    //                 content: "There was an error in the connection. Please contact the administrator for updates.",
    //             });
    //         },
    //     });
    // });

    // edit service records
    // end of edit
    //Ajax non-forms
    $(document).on("click", ".editServiceRecord, .addServiceRecord", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        employee_id = my.data("employee_id");
        // console.log(employee_id);
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
                            $("#myModal .modal-title").html(modal_title);
                            $("#myModal .modal-body").html(result.form);
                            $("#myModal").modal("show");
                            $.each(my.data(), function(i, v) {
                                $("." + i).val(addCommas(v));
                            });
                            $("form input:not(:button),form input:not(:submit)").attr(
                                "disabled",
                                false
                            );
                            reloadTable();
                            break;
                        case "addServiceRecord":
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
                            $("#myModal .modal-title").html(modal_title);
                            $("#myModal .modal-body").html(result.form);
                            $("#myModal").modal("show");
                            $.each(my.data(), function(i, v) {
                                $("." + i).val(addCommas(v));
                            });
                           
                            $("form input:not(:button),form input:not(:submit)").attr(
                                "disabled",
                                false
                            );
                            reloadTable();
                            break;
                    }
                    $("#" + result.key).validate({
                        rules: {
                            ".required": { required: true },
                            ".email": { required: true, email: true },
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

    $(document).on('click', '#add_fields', function() {
        var num = $(this).data('num');
        var count = $('.removeFields').size()
        var employee_id = $('#employee_id').val();
        var numRemove = count + 1;
        var salary = Number($('#salary').val()) * 12;
        var work_to = $('#work_to').val();
        var position = $('#position').val();
        var status_appointment = $('#status_appointment').val();
        var company =  $('#company').val();
        var branch =  $('#branch').val();
        var lv_abs_wo_pay =  $('#lv_abs_wo_pay').val();
        var seperation_date =  $('#seperation_date').val();
        var sc = $('#seperation_cause:last').html();
        var seperation_cause =  sc;
       //  alert(status_appointment);



        //console.log(employee_id);
        // for (var i = 0; i < 1; i++) {
        //     numRemove = numRemove + count + 1;
        //     $(this).data('num', numRemove);
        // }
        var tbody = $('#serviceOfrecord').children('tbody');

        //Then if no tbody just select your table 
        var table = tbody.length ? tbody : $('#serviceOfrecord');
        var rowContainingButton = $(this).closest('tr');
        $(
            '<tr id="fields_line' + numRemove + '">' +
            '<input type="hidden" name="employee_id[]" id="employee_id" value="' + employee_id + '">' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired add work_to"   name="work_from[]" id="work_from' + num + '" required ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group ">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired work_from"  name="work_to[]" id="work_to' + num + '"  required></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 160px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"   name="position[]" id="position' + num + '"  required>-do-</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 70px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"  name="status_appointment[]" id="status_appoinment' + num + '"  required >P</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="monthly_salary[]" id="monthly_salary' + num + '"   required>'+salary+'/a</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td class="aligncenter" style="min-width: 100px">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="company[]" id="company' + num + '"  required >'+company+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="branch[]" id="branch' + num + '" required>'+branch+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="lv_abs_wo_pay[]" id="lv_abs_wo_pay' + num + '" required>'+lv_abs_wo_pay+'</textarea>' +
            '</div>' +
            '</div> ' +
            '	</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth"   name="seperation_date[]" id="seperation_date' + num + '">'+seperation_date+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired seperation_cause" name="seperation_cause[]" id="seperation_cause' + num + '"></textarea>' +
            '</div>' +
            '</div> ' +
            '</td>' +
            '<td><button type="button" id="remove_fields" class="removeFields btn-danger" data-num="0" data-remove="fields_line' + numRemove + '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove">' +
            '<i class="material-icons">remove</i>' +
            '</button>'+
            '<button type="button" id="add_field" data-num="'+ num +'" data-add="fields_line' + numRemove + '" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Add">'+
			'<i class="material-icons">add</i>'+
            '</button>'+
            '</td>'+
            '</tr>'
            ).insertAfter(rowContainingButton);

    });

    $(document).on('click', '#add_field', function() {
        var num = $(this).data('num');
        var count = $('.removeFields').size()
        var employee_id = $('#employee_id').val();
        var numRemove = count + 1;
        var salary = Number($('#salary').val()) * 12;
        var work_to = $('.work_to').val();
        var work_from = $('.work_from').val();
        var position = $('#position').val();
        var status_appointment = $('#status_appointment').val();
        var company =  $('#company').val();
        var branch =  $('#branch').val();
        var lv_abs_wo_pay =  $('#lv_abs_wo_pay').val();
        var seperation_date =  $('#seperation_date').val();
        var seperation_cause =  $('.seperation_cause').val();
       //  alert(status_appointment);



        //console.log(employee_id);
        // for (var i = 0; i < 1; i++) {
        //     numRemove = numRemove + count + 1;
        //     $(this).data('num', numRemove);
        // }
        var tbody = $('#serviceOfrecord').children('tbody');

        //Then if no tbody just select your table 
        var table = tbody.length ? tbody : $('#serviceOfrecord');

          // Find the nearest row containing the clicked button using closest()
        var rowContainingButton = $(this).closest('tr');

        $(
            '<tr id="fields_line' + numRemove + '">' +
            '<input type="hidden" name="employee_id[]" id="employee_id" value="' + employee_id + '">' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired add work_to "   name="work_from[]" id="work_from' + num + '" required >'+work_to +'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group ">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired work_from "  name="work_to[]" id="work_to' + num + '"  required>'+work_from+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 160px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"   name="position[]" id="position' + num + '"  required>-do-</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 70px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"  name="status_appointment[]" id="status_appoinment' + num + '"  required >P</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="monthly_salary[]" id="monthly_salary' + num + '"   required>'+salary+'/a</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td class="aligncenter" style="min-width: 100px">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="company[]" id="company' + num + '"  required >'+company+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="branch[]" id="branch' + num + '" required>'+branch+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="lv_abs_wo_pay[]" id="lv_abs_wo_pay' + num + '" required>'+lv_abs_wo_pay+'</textarea>' +
            '</div>' +
            '</div> ' +
            '	</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth"   name="seperation_date[]" id="seperation_date' + num + '">'+seperation_date+'</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="seperation_cause[]" id="seperation_cause' + num + '">'+seperation_cause+'</textarea>' +
            '</div>' +
            '</div> ' +
            '</td>' +
            '<td><button type="button" id="remove_fields" class="removeFields btn-danger" data-num="0" data-remove="fields_line' + numRemove + '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove">' +
            '<i class="material-icons">remove</i>' +
            '</button>'+
            '<button type="button" id="add_field" data-num="'+ num +'" data-add="fields_line' + numRemove + '" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm as" data-toggle="tooltip" data-placement="top" title="Add">'+
			'<i class="material-icons">add</i>'+
            '</button>'+
            '</td>'+
            '</tr>'
       ).insertAfter(rowContainingButton);

    });

    $(document).on('click', '#add_fieldss', function() {
        var num = $(this).data('num');
        var count = $('.removeFields').size()
        var employee_id = $('#employee_id').val();
        var numRemove = count + 1;
        var salary = Number($('#salary').val()) * 12;
        //console.log(Number($('#salary').val()));
        // for (var i = 0; i < 1; i++) {
        //     numRemove = numRemove + count + 1;
        //     $(this).data('num', numRemove);
        // }
        var tbody = $('#serviceOfrecord').children('tbody');

        //Then if no tbody just select your table 
        var table = tbody.length ? tbody : $('#serviceOfrecord');

        $('#append_fields').append(
            '<tr id="fields_line' + numRemove + '">' +
            '<input type="hidden" name="employee_id[]" id="employee_id" value="' + employee_id + '">' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired add"   name="work_from[]" id="work_from' + num + '" required ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 90px" class="aligncenter">' +
            '<div class="form-group ">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"  name="work_to[]" id="work_to' + num + '"  required  ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 160px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"   name="position[]" id="position' + num + '"  required  ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 70px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"  name="status_appointment[]" id="status_appoinment' + num + '"  required ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="monthly_salary[]" id="monthly_salary' + num + '"   required>'+salary+'/a</textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td class="aligncenter" style="min-width: 100px">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="company[]" id="company' + num + '"  required ></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="branch[]" id="branch' + num + '" required></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="lv_abs_wo_pay[]" id="lv_abs_wo_pay' + num + '" ></textarea>' +
            '</div>' +
            '</div> ' +
            '	</td>' +
            '<td style="min-width: 100px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth"   name="seperation_date[]" id="seperation_date' + num + '"></textarea>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="min-width: 120px" class="aligncenter">' +
            '<div class="form-group">' +
            '<div class="form-line">' +
            '<textarea rows="3"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="seperation_cause[]" id="seperation_cause' + num + '"></textarea>' +
            '</div>' +
            '</div> ' +
            '</td>' +
            '<td><button type="button" id="remove_fields" class="removeFields btn-danger" data-num="0" data-remove="fields_line' + numRemove + '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove">' +
            '<i class="material-icons">remove</i>' +
            '</button>'+
            '<button type="button" id="add_fields" data-num="'+ num +'" data-add="fields_line' + numRemove + '" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Add">'+
			'<i class="material-icons">add</i>'+
            '</button>'+
            '</td>'+
            '</tr>'
        )

    });

    $(document).on('click', '#remove_fields', function() {
        var remove = $(this).data('remove');
        //console.log(remove);
        if (remove != null) {
            $('#' + remove).remove();
        }
        var removeadd = $(this).data('add');
        if (removeadd != null) {
            $('#' + add_fields).remove();
        }
    });


    $(document).on("click", "#computePayroll", function(e) {
        e.preventDefault();
    
        // Cache the jQuery object
        var my = $(this);
        var url = my.attr("href");
        var division_id = $(".search_entry #division_id").val().trim();
        var employment_status = $(".search_entry #employment_status").val().trim();
    
        // Validate inputs
        if (division_id == "") {
            division_id = "ALL";
            // showErrorAlert("Please select Department.");
            // return false;
        }
    
        if (employment_status == "") {
            showErrorAlert("Please select employment status.");
            return false;
        }
    
        // Destroy existing DataTable
        var existingDataTable = $("#datatables").DataTable();
        if (existingDataTable) {
            existingDataTable.destroy();
        }
    
        // Make the AJAX request
        $.ajax({
            type: "POST",
            url: commons.baseurl + "employees/ServiceRecords/fetchRows?",
            dataType: "json",
            data: {
                DivisionId: division_id,
                EmploymentStatus: employment_status,
            },
            success: function(result) {
                // Display the result in the table-holder
                $("#table-holder").html(result.table);
    
                // Initialize DataTable
                var table = $("#datatables").DataTable({
                    processing: true,
                    serverSide: true,
                    order: [
                        [1, 'asc'] // Set default sorting order, change '1' to the column index you want to sort by
                    ],
                    ajax: {
                        url: commons.baseurl + "employees/ServiceRecords/fetchRows?",
                        type: "POST",
                        data: {
                            DivisionId: division_id,
                            EmploymentStatus: employment_status,
                        },
                    },
                    columnDefs: [{
                        "targets": [0],
                        orderable: false,
                    }],
                });
    
                // // Search all data
                // table.search("").draw();
            },
            error: function() {
                showErrorAlert("There was an error in the connection. Please contact the administrator for updates.");
            },
        });
    });
    
    
    // Function to show error alerts
    function showErrorAlert(message) {
        $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: message,
        });
    }
    

    $(document).on("click", "#btnPrintDetails", function() {
        var mywindow = window.open("", "PRINT", "height=1000,width=1000");
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

    // function loadTable() {
    //   table = $("#datatables").DataTable({
    //     processing: true,
    //     serverSide: true,
    //     stateSave: true, // presumably saves state for reloads -- entries
    //     bStateSave: true, // presumably saves state for reloads -- page number
    //     order: [],
    //     ajax: {
    //       url: commons.baseurl + "employees/ServiceRecords/fetchRows",
    //       type: "POST",
    //     },
    //     columnDefs: [{ orderable: false }],
    //   });
    // }


    function reloadTable() {
        //table.ajax.reload();
        employee_id = $(".search_entry #employee_id").val();
        plus_url = "?EmployeeId=" + employee_id;
        if (employee_id == "") {
            return false;
        }
    }


    function reloadTable2(id, url2) {
        employee_id = id;
        url = url2
        $.ajax({
            type: "POST",
            url: url,
            data: { employee_id: employee_id },
            dataType: "json",
            success: function(result) {
                page = my.attr("id");
                if (result.hasOwnProperty("key")) {
                    switch (result.key) {
                        case "addServiceRecord":
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
                            $("#myModal .modal-title").html(modal_title);
                            $("#myModal .modal-body").html(result.form);
                            $("#myModal").modal("show");
                            $.each(my.data(), function(i, v) {
                                $("." + i).val(addCommas(v));
                            });
                            $("form input:not(:button),form input:not(:submit)").attr(
                                "disabled",
                                false
                            );
                            break;
                        case "addServiceRecord":
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
                            $("#myModal .modal-title").html(modal_title);
                            $("#myModal .modal-body").html(result.form);
                            $("#myModal").modal("show");
                            $.each(my.data(), function(i, v) {
                                $("." + i).val(addCommas(v));
                            });
                            $("form input:not(:button),form input:not(:submit)").attr(
                                "disabled",
                                false
                            );
                            break;
                    }
                    $("#" + result.key).validate({
                        rules: {
                            ".required": { required: true },
                            ".email": { required: true, email: true },
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

    $(document).on('click', '#deleteRecord', function(e) {
        e.preventDefault();
    
        const recordId = $(this).data('id');
        const employeeId = $(this).data('emp');
        const content = "You are about to delete the entire row of data.";
        const url = $(this).data('url');
        const url2 = $(this).data('url2');
    
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: "red",
            buttons: {
                ok: {
                    btnClass: "btn-blue",
                    action: function() {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                id: recordId
                            },
                            dataType: "json",
                            success: function(result) {
                                if (result.hasOwnProperty("key")) {
                                    if (result.Code == "0" && result.key === "deleteServiceRecord") {
                                        // Show the success modal here
                                        $.confirm({
                                            title: '<label class="text-success">Success</label>',
                                            content: result.Message,
                                            type: "green",
                                            buttons: {
                                                ok: {
                                                    btnClass: "btn-blue",
                                                    action: function() {
                                                        // Reload the table after the record is deleted
                                                        reloadTable2(employeeId, url2);
                                                    }
                                                }
                                            }
                                        });
                                    } else {
                                        // Show the failure modal here
                                        $.confirm({
                                            title: '<label class="text-danger">Failed</label>',
                                            content: result.Message,
                                            type: "red",
                                            buttons: {
                                                ok: {
                                                    btnClass: "btn-blue"
                                                }
                                            }
                                        });
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                // Show the error modal here
                                $.confirm({
                                    title: '<label class="text-danger">Failed</label>',
                                    content: status === "error" ? "There was an error in the connection. Please try again later." : "An unexpected error occurred. Please contact the administrator for support.",
                                    type: "red",
                                    buttons: {
                                        ok: {
                                            btnClass: "btn-blue"
                                        }
                                    }
                                });
                            }
                        });
                    }
                },
                cancel: function() {
                    // Add any specific action for the cancel button here, if needed.
                },
            }
        });
    });
    
    
    // EMPLOYEE EDITT SERVICE RECORD BUTTON ALERT
    $(document).on("submit", "form", "#update", function(e) {
        e.preventDefault();
        var form = $(this);
        //content = "Are you sure you want to proceed?";

        if (form.attr("id") == "editServiceRecord") {
            content = "Are you sure you want to update service record?";
        }
        if (form.attr("id") == "addServiceRecord") {
            var input = $('.add').val();
              if(input != null){
                if (input !== "-") {
                    input = input.replace('/', '-');
                  }
                    var a = input.split('-');
                    var b = a[0];
    
                    if (b >= 13) {
                        $.alert({
                            title: '<label class="text-danger">Failed</label>',
                            content: "There was an error in the connection. Date "+'<b>"From"</b>' +" must be m-d-Y (00-00-0000) format!",
                        });
                        return false;
                    }
              }
                content = "Are you sure you want to submit service record?";
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
                                                        case "editServiceRecord":
                                                        case "updateServiceRecord":
                                                        case "addServiceRecord":
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

    $(document).on("click", "#cancel", function(e) {
        e.preventDefault();
        var form = $(this);
        content = "Are you sure you want to proceed?";

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
                        $("#myModal").modal("hide");
                    },
                },
                cancel: function() {},
            },
        });
    });

    //END EMPLOYEE EDITT SERVICE RECORD


});