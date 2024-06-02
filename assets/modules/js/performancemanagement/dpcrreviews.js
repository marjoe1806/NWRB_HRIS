$(document).ready(function(){
    
    var baseURL = commons.baseurl;
    var data;
    $.when(
        getFields.division(),
        getFields.positionCode()
    ).done(function () {
        $.AdminBSB.select.activate();
    })
    function dpcr_reviews(params){
        $('#dpcr_reviews').DataTable({
            "destroy": true,
            "pagingType": "full_numbers",
            "ordering": false,
            "lengthMenu": [[10, 25, 50, -1], 
            [10, 25, 50, "All"]],
            responsive: true,
            language: {
            search: "_INPUT_",
                searchPlaceholder: "Search records",
            },
            ajax:
            {
                url:  baseURL + "performancemanagement/DpcrReviews/get_dpcr",
                type: "POST",
                data: params,
            },
            error: function (xhr, error, code)
            {
                console.log(xhr);
                console.log(code);
            },
            columns: [
                {
                    data: 'empl_name'
                },
                {
                    data: 'position'
                },
                {
                    data: 'period_of'
                },
                {
                    data: 'posted_date'
                },
                {
                    "data": function (data) {
                        return `
                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float pull-right view" 
                                    data-id = "${data.form_id}"
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="" 
                                    data-original-title="View"> 
                                <i class="material-icons">list</i>  
                            </button>
                            <button class="btn btn-info btn-circle waves-effect waves-circle waves-float pull-right viewprint" 
                                    data-id = "${data.form_id}"
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="" 
                                    data-original-title="Print"> 
                                <i class="material-icons">remove_red_eye</i>   
                            </button>
                        `;
                    },
                }
            ]
        });
    }

    // ipcr_reviews();

    function step1(){
        // $('.step1').parent().addClass('done');
        // $('.step1').parent().removeClass('todo');
        // $('.step1').removeClass('btn-default').addClass('btn-success');
        $('#update_form').validate();
    }   

    function step2(){
        // $('.step2').parent().addClass('done');
        // $('.step2').parent().removeClass('todo');
        // $('.step2').removeClass('btn-default').addClass('btn-success');
        $('#rating_ans').show();
        $('.form_table').find('th:contains(REMARKS)').show();
        $('.form_table').find('th:contains(Q1)').show();
        $('.form_table').find('th:contains(E2)').show();
        $('.form_table').find('th:contains(T3)').show();
        $('.form_table').find('th:contains(A4)').show();
        $('.form_table td').find('input[type=text]').parent().parent().show();
        $('.form_table td').find('input[type=checkbox]').parent().parent().show();
    }

    function step3(){
        $('#reviewed_date').text(data.data[0].date_review);
        $('.form_table').find('th:contains(APPROVED DATE:)').hide();
        $('#approved_date').hide();
        $('.form_table').find('input[type=checkbox]').hide();
        $('.form_table td').find('input[type=text]').hide();
        $('.form_table td').find('input[type=number]').hide();
        $('.form_table').find('th:contains(OUTPUT)').closest('table').find('tbody').find('tr:eq(0)');

        $('.strategic tbody tr').remove();
        $('.core-support tbody tr').remove();

        $('.strategic tbody').append(
            `<tr>
                <th style="text-align:left;">Strategic Functions</th>
                <th>
                    <label></label>
                </th>
                <th>
                    <label></label>
                </th>
                <th colspan="4">
                    <label></label>
                </th>
                <th>
                    <label></label>
                </th>
            </tr>`
        );
        $('.core-support tbody').append(
            `<tr>
                <th style="text-align:left;">Core Functions</th>
                <th>
                    <label></label>
                </th>
                <th>
                    <label></label>
                </th>
                <th colspan="4">
                    <label></label>
                </th>
                <th>
                    <label></label>
                </th>
            </tr>`
        );
        $.each(data.data,function(k,v){
            let classKey = v.weight_of_output == "Strategic"? "strategic" : "core-support";
            if(v.weight_of_output == "Support" && data.data[k-1].weight_of_output != "Support"){
                    $('.core-support tbody').append(
                        `<tr>
                            <th style="text-align:left;">Support Functions</th>
                            <th>
                                <label></label>
                            </th>
                            <th>
                                <label></label>
                            </th>
                            <th colspan="4">
                                <label></label>
                            </th>
                            <th>
                                <label></label>
                            </th>
                        </tr>`
                    );
            } 
            
            if(v.weight_of_output == "Final-Rating"){
               $(`.${classKey} tbody`).append(
                '<tr>'
                +   '<th colspan = "3" style="text-align:left;" >'
                +      'Final Average Ratings'
                +   '</th>'
                +   '<td>'
                +       v.q1
                +   '</td>'
                +   '<td>'
                +       v.e2
                +   '</td>'
                +   '<td>'
                +     v.t3
                +   '</td>'
                +   '<td>'
                +       v.a4
                +   '</td>'
                +   '<td>'
                +       v.remarks
                +   '</td>'
                +'</tr>'
                ); 
            }else{
                $(`.${classKey} tbody`).append(
                    '<tr>'
                    +   '<td>'
                    +       v.output
                    +   '</td>'
                    +   '<td>'
                    +       v.success_target
                    +   '</td>'
                    +   '<td>'
                    +       v.actual_accomplishments
                    +   '</td>'
                    +   '<td>'
                    +       v.q1
                    +   '</td>'
                    +   '<td>'
                    +       v.e2
                    +   '</td>'
                    +   '<td>'
                    +     v.t3
                    +   '</td>'
                    +   '<td>'
                    +       v.a4
                    +   '</td>'
                    +   '<td>'
                    +       v.remarks
                    +   '</td>'
                    +'</tr>'
                );
            }
            
        });

        $('#strat_mfo').text(data.data[0].strat_mfo);
        $('#strat_rating').text(data.data[0].strat_rating);
        $('#core_mfo').text(data.data[0].core_mfo);
        $('#core_rating').text(data.data[0].core_rating);
        $('#support_mfo').text(data.data[0].support_mfo);
        $('#support_rating').text(data.data[0].support_rating);
        $('#overall_rating_mfo').text(data.data[0].overall_rating_mfo);
        $('#overall_rating').text(data.data[0].overall_rating);
        $('#avg_rating_mfo').text(data.data[0].avg_rating_mfo);
        $('#avg_rating').text(data.data[0].avg_rating);
        $('#adj_rating_mfo').text(data.data[0].adj_rating_mfo);
        $('#adj_rating').text(data.data[0].adj_rating);

        $('#avg_rating_mfo').text(data.data[0].avg_rating_mfo);
        $('#comments').text(data.data[0].comments);
       
        $('#myModal #footer').html('<button type = "button" class = "btn btn-success btn-fill pull-right print_preview">Print</button>');
    }

    // function step4(){
    //     $('.step3').parent().addClass('done');
    //     $('.step3').parent().removeClass('todo');
    //     $('.step3').removeClass('btn-default').addClass('btn-success');
    //     $('.form_table').find('th:contains(DISSCUSSED DATE:)').show();
    //     $('#date_discussed').show().text(data.data[0].date_discussed);
    // }

    // function step5(){
    //     $('.step4').parent().addClass('done');
    //     $('.step4').parent().removeClass('todo');
    //     $('.step4').removeClass('btn-default').addClass('btn-success');
    //     $('.form_table').find('th:contains(ASSESSED DATE:)').show();
    //     $('#date_assesed').show();
    //     $('#date_assesed').text(data.data[0].date_assesed == null ? "" : data.data[0].date_assesed);
    // }

    // function step6(){
    //     $('.step5').parent().addClass('done');
    //     $('.step5').parent().removeClass('todo');
    //     $('.step5').removeClass('btn-default').addClass('btn-success');
    //     $('.form_table').find('th:contains(FINAL RATE DATE:)').show();
    //     $('.form_table').find('th:contains(APPROVED DATE:)').show();
    //     $('#date_assesed').show();
    //     $('#date_assesed').text(data.data[0].date_assesed == null ? "" : data.data[0].date_assesed);
    //     $('#date_final_rating').show().text(data.data[0].date_final_rating == null ? "" : data.data[0].date_final_rating);
    //     $('#approved_date').show().text(data.data[0].date_approve == null ? "" : data.data[0].date_approve);
    //     $('#myModal #footer').html('<button type = "button" class = "btn btn-success btn-fill pull-right print_preview">Print</button>');
    // }

    // function isRatingValid(){
    //     const ratingRow = ($('input[name^="rating"]').length / 4)
    //     for(let a = 0; a < ratingRow ; a++){
    //         let checked = $(`input[name^="rating[${a}]"]:checked`).length;
    //         if(checked < 1){
    //             return false;
    //         }
    //     }
    //     return true;
    // }

    $("#update_form").validate({
        ignore: [],
        rules: {
        },
        errorClass: "jv-error",
        errorPlacement: function (error, element) {
          $(element).parent("div").addClass("has-error");
          $(element).parents(".form-group").append(error);
        },
        unhighlight: (element, errorClass) => {
          $(element).parent("div").removeClass("has-error");
          $(element).removeClass("jv-error");
        },
    });
    
    
    $(document).on("click", "#getDpcrReview", function (e) {
        e.preventDefault();
        my = $(this);
        url = my.attr("href");
        division_id = $("#division_id").val();
        employee_id = $("#employee_id").val();
        if (division_id == "") {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Please select Department.",
          });
          return false;
        }
        dpcr_reviews({division_id, employee_id})
    });
    
    $(document).on('change','#division_id',function(e){
        var division_id = $(this).val();
        var data = {
            division_id:division_id
        };

        $.when(
            getFields.employee(data)
        ).done(function () {
            $.AdminBSB.select.activate();
        })
    })

    $(document).on("click", ".view", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = baseURL + 'performancemanagement/DpcrReviews/view_dpcr';
        $.post(url, {id:id}).done(function(result) {
            result = JSON.parse(result);
            data = result;
            form = 'update_form';
            title = 'View Form';
            body = result.form;
            footer = '<div id="footer"><button type = "button" class = "btn btn-warning btn-fill pull-right update">Update</button></div><br><br>';
            size = 'lg';
            modal(form,title,body,footer,size);
            $('#myModal .modal-dialog').css('width','90%');
            $('#content_print').hide();
            var fillup = result.data[0].fill_up;
            var ratee = result.data[0].ratee;
            var validate = result.data[0].validate;
            var approval = result.data[0].approval;
            var higher_approval = result.data[0].higher_approval;
            var answered = result.data[0].answered;
            if(fillup == 1){
                step1();
            }
            if(answered == 1){
                step2();
                step3();
            }
            // if(validate == 1){
            //     step4();
            // }
            // if(approval == 1){
            //     step5();
            // }
            // if(higher_approval == 1){
            //     step6();
            // }
        });
    });

    $(document).on("click", ".viewprint", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = baseURL + 'performancemanagement/DpcrReviews/print_dpcr';
        $.post(url, {id:id}).done(function(result) {
            result = JSON.parse(result);
            data = result;
            form = 'update_form';
            title = 'View Form';
            body = result.form;
            footer = '<div id="footer"><button type = "button" class = "btn btn-success btn-fill pull-right print_preview">Print</button></div><br><br>';
            size = 'lg';
            modal(form,title,body,footer,size);
            $('#myModal .modal-dialog').css('width','70%');
            // $('#content_print').hide();
            var fillup = result.data[0].fill_up;
            var ratee = result.data[0].ratee;
            var validate = result.data[0].validate;
            var approval = result.data[0].approval;
            var higher_approval = result.data[0].higher_approval;
            var answered = result.data[0].answered;
            // if(fillup == 1){
            //     step1();
            // }
            // if(answered == 1){
            //     step2();
            //     step3();
            // }
            // if(validate == 1){
            //     step4();
            // }
            // if(approval == 1){
            //     step5();
            // }
            // if(higher_approval == 1){
            //     step6();
            // }
        });
    });

    $(document).on("click", ".update", function (e) {
        var url = baseURL + 'performancemanagement/DpcrReviews/update';
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
                cancel:{
                    text: 'Cancel',
                    btnClass: 'btn-warning',
                    keys: ['enter', 'shift'],
                    action: function(){
                        // $('#myModal').modal('hide');
                    }
                },
                confirm:{
                    text: 'Submit',
                    btnClass: 'btn-success',
                    action: function(){
                        $.post(url, data).done(function(result) {
                            result = JSON.parse(result);
                            if(result.Code == 0){
                                $.confirm({
                                    title: 'Success!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-success',
                                            keys: ['enter', 'shift'],
                                            action: function(){
                                                dpcr_reviews();
                                                $('#myModal').modal('hide');
                                            }
                                        }
                                    }
                                });
                            }
                            else{
                                $.confirm({
                                    title: 'Warning!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-warning',
                                            keys: ['enter', 'shift'],
                                            action: function(){
                                                
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

    $(document).on('click', '.print_preview', function(){
        printPrev(document.getElementById("content_print").innerHTML)
    }); 












    function modal(form,title,body,footer,size){
        $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-'+size+'');
        $('#myModal .modal-title').html(title);
        $('#myModal .modal-body').html(`<form id="${form}">`+body+footer+`</form>`);
        $('#myModal').modal('show');
    }


  
});