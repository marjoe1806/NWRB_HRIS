$(document).ready(function() {

    var baseURL = commons.baseurl;
    var data;
    var opcrFilter = { division_id: "", employee_id: "" }
    $.when(
        getFields.division(),
        getFields.positionCode()
    ).done(function() {
        $.AdminBSB.select.activate();
    })

    function opcr_reviews() {
        $('#opcr_reviews').DataTable({
            "destroy": true,
            "pagingType": "full_numbers",
            "ordering": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records",
            },
            ajax: {
                url: baseURL + "performancemanagement/OpcrReviews/get_opcr",
                type: "POST",
                data: opcrFilter
            },
            columns: [{
                    data: 'empl_name'
                },
                {
                    data: 'position'
                },
                {
                    data: 'submitted_date'
                },
                {
                    "data": function(data) {
                        return `
                            <button class="btn btn-info btn-circle waves-effect waves-circle waves-float pull-right view" 
                                    data-id = "${data.form_id}"
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="" 
                                    data-original-title="View"> 
                                <i class="material-icons">remove_red_eye</i>  
                            </button>
                            
                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float pull-right print"
                                    data-id = "${data.form_id}" 
                                    data-toggle="tooltip" 
                                    data-placement="top" title="" 
                                    data-original-title="Print"> 
                                <i class="material-icons">list</i>  
                            </button>
                        `;
                    },
                }
            ]
        });
    }

    // opcr_reviews();

    function step1() {
        // $('.step1').parent().addClass('done');
        // $('.step1').parent().removeClass('todo');
        // $('.step1').removeClass('btn-default').addClass('btn-success');
        $('#update_form').validate();
        $('#total').hide();
    }

    function step2() {
        // $('.step2').parent().addClass('done');
        // $('.step2').parent().removeClass('todo');
        // $('.step2').removeClass('btn-default').addClass('btn-success');
        $('#myModal #footer').html(
            '<br><br><button type="submit" class="btn btn-default btn-fill pull-right" data-dismiss="modal">Close</button><br><br>'
        );
        $('#tbl-ratings tbody tr').remove();
        var count_strat = 0;
        var count_core = 0;
        var count_support = 0;
        var loop_core = 0;
        var loop_support = 0;
        var loop_strat = 0;
        $('#tbl-ratings tbody').append(
            '<tr>' +
            '<th colspan = "11">A. Strategic Priorities: (30%)</th>' +
            '</tr>'
        );
        $.each(data, function(k, v) {

            if (v.weight_of_output == "Strategic") {
                count_strat++;
            } else {
                count_support++;
            }
        });
        $.each(data, function(k, v) {
            if (v.weight_of_output != "Core") {
                if (v.weight_of_output == "Final-Rating") {
                    $('#tbl-ratings tbody').append(
                        '<tr>' +
                        '<th colspan="5" style="text-align:left;">' +
                        'Final Avaerage Rating' +
                        '</th>' +
                        '<td>' +
                        v.q1 +
                        '</td>' +
                        '<td>' +
                        v.e2 +
                        '</td>' +
                        '<td>' +
                        v.t3 +
                        '</td>' +
                        '<td>' +
                        v.a4 +
                        '</td>' +
                        '<td>' +
                        '</td>' +
                        '</tr>'
                    );
                } else {
                    $('#tbl-ratings tbody').append(
                        '<tr>' +
                        '<td>' +
                        v.mfo_pap +
                        '</td>' +
                        '<td>' +
                        v.success_target +
                        '</td>' +
                        '<td>' +
                        v.allotted_budget +
                        '</td>' +
                        '<td>' +
                        v.office_individual +
                        '</td>' +
                        '<td>' +
                        v.actual_accomplishments +
                        '</td>' +
                        '<td>' +
                        v.q1 +
                        '</td>' +
                        '<td>' +
                        v.e2 +
                        '</td>' +
                        '<td>' +
                        v.t3 +
                        '</td>' +
                        '<td>' +
                        v.a4 +
                        '</td>' +
                        '<td>' +
                        v.remarks +
                        '</td>' +
                        '</tr>'
                    );
                }
                if (v.weight_of_output == "Strategic") {
                    loop_strat++;
                    if (count_strat == loop_strat) {
                        // $('.table-style tbody').append(
                        //     '<tr>'
                        //     +   '<th colspan = "11">B. Core Functions: (50%)</th>'
                        //     +'</tr>'
                        // );  
                        $('#tbl-ratings tbody').append(
                            '<tr>' +
                            '<th colspan = "11">C. Support Functions: (20%)</th>' +
                            '</tr>'
                        );
                    }
                }
                // else{
                //     loop_core++;
                //     if(count_core == loop_core){
                //         $('.table-style tbody').append(
                //             '<tr>'
                //             +   '<th colspan = "11">C. Support Functions: (20%)</th>'
                //             +'</tr>'
                //         );  
                //     }
                // }
            }


        });
        $('#total').show();
        $('#sub_strat').text(data[0].subtotal_strat);
        $('#sub_core').text(data[0].subtotal_core);
        $('#sub_support').text(data[0].subtotal_support);
        $('#grand_total').text(data[0].grand_total);

        $('#strat_mfo').text(data[0].strat_mfo);
        $('#strat_rating').text(data[0].strat_rating);
        $('#core_mfo').text(data[0].core_mfo);
        $('#core_rating').text(data[0].core_rating);
        $('#support_mfo').text(data[0].support_mfo);
        $('#support_rating').text(data[0].support_rating);
        $('#overall_rating_mfo').text(data[0].overall_rating_mfo);
        $('#overall_rating').text(data[0].overall_rating);
        $('#avg_rating_mfo').text(data[0].avg_rating_mfo);
        $('#avg_rating').text(data[0].avg_rating);
        $('#adj_rating_mfo').text(data[0].adj_rating_mfo);
        $('#adj_rating').text(data[0].adj_rating);
    }


    $("#update_form").validate({
        ignore: [],
        rules: {},
        errorClass: "jv-error",
        errorPlacement: function(error, element) {
            $(element).parent("div").addClass("has-error");
            $(element).parents(".form-group").append(error);
        },
        unhighlight: (element, errorClass) => {
            $(element).parent("div").removeClass("has-error");
            $(element).removeClass("jv-error");
        },
    });

    $(document).on("click", "#getOpcrReview", function(e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        // division_id = $("#division_id").val();
        // employee_id = $("#employee_id").val();
        opcrFilter.division_id = $("#division_id").val();
        opcrFilter.employee_id = $("#employee_id").val();
        if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Department.",
            });
            return false;
        }

        opcr_reviews()
    });

    $(document).on('change', '#division_id', function(e) {
        var division_id = $(this).val();
        var data = {
            division_id: division_id
        };

        $.when(
            getFields.employee(data)
        ).done(function() {
            $.AdminBSB.select.activate();
        })
    })

    $(document).on("click", ".view", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = baseURL + 'performancemanagement/OpcrReviews/view_opcr';
        $.post(url, { id: id }).done(function(result) {
            result = JSON.parse(result);
            data = result.data;
            form = 'update_form';
            title = 'View Form';
            body = result.form;
            footer = '<div id="footer"><br><br><button type = "button" class = "btn btn-warning btn-fill pull-right update">Update</button><br><br></div>';
            size = 'lg';
            modal(form, title, body, footer, size);
            $('#content_print').hide();
            step1();
            if (result.data[0].assessed_reviewed == 1) {
                step2();
            }
            if (result.data[0].final_rating == 1) {
                $('#myModal #footer').html('<br><br><button type="submit" class="btn btn-default btn-fill pull-right" data-dismiss="modal">Close</button><br><br>');
            }
            $('#myModal .modal-dialog').css('width', '90%');

        });

    });

    $(document).on("click", ".print", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = baseURL + 'performancemanagement/OpcrReviews/print_opcr';
        $.post(url, { id: id }).done(function(result) {
            result = JSON.parse(result);
            data = result.data;
            form = 'update_form';
            title = 'View Form';
            body = result.form;
            footer = '<div id="footer"><br><br><button type = "button" class = "btn btn-success btn-fill pull-right print_preview">Print</button><br><br></div>';
            size = 'lg';
            modal(form, title, body, footer, size);
            // $('#content_print').hide();
            // step1();
            // if(result.data[0].assessed_reviewed == 1){
            //     step2();
            // }
            // if(result.data[0].final_rating == 1){
            //     $('#myModal #footer').html('<br><br><button type = "button" class = "btn btn-success btn-fill pull-right print_preview">Print</button><br><br>');
            // }
            $('#myModal .modal-dialog').css('width', '70%');
        });

    });

    $(document).on("click", ".update", function(e) {
        var url = baseURL + 'performancemanagement/OpcrReviews/update';
        var data = $('#update_form').serializeArray();
        const valid = $("#update_form").valid();
        if (!valid) {
            return;
        }

        $("#ratings-required").text("")
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure want to proceed?',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-warning',
                    keys: ['enter', 'shift'],
                    action: function() {
                        // $('#myModal').modal('hide');
                    }
                },
                confirm: {
                    text: 'Submit',
                    btnClass: 'btn-success',
                    action: function() {
                        $.post(url, data).done(function(result) {
                            result = JSON.parse(result);
                            if (result.Code == 0) {
                                $.confirm({
                                    title: 'Success!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-success',
                                            keys: ['enter', 'shift'],
                                            action: function() {
                                                opcr_reviews();
                                                $('#myModal').modal('hide');
                                            }
                                        }
                                    }
                                });
                            } else {
                                $.confirm({
                                    title: 'Warning!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-warning',
                                            keys: ['enter', 'shift'],
                                            action: function() {

                                            }
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            }
        });
    });

    $(document).on('click', '.print_preview', function() {
        printPrev(document.getElementById("content_print").innerHTML)
    });

    function modal(form, title, body, footer, size) {
        $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-' + size + '');
        $('#myModal .modal-title').html(title);
        $('#myModal .modal-body').html(`<form id="${form}">` + body + footer + `</form>`);
        $('#myModal').modal('show');
    }

});