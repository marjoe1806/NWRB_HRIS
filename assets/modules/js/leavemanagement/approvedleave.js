$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    table = $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        aaSorting: [],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }

    });
    $(document).on('change','#leave_kind_search',function(e){
        url = commons.baseurl + "leavemanagement/ApprovedLeave/";
        url += "?Kind="+$(this).val();
        window.location = url;
    })
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            maxDate: new Date(),
            time: false
        });
        $('.daysleave').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: false,
            weekStart: 1,
            minDate: new Date(),
            time: false
        });
        $.AdminBSB.dropdownMenu.activate();
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
        $.AdminBSB.search.activate();
    })
    
    //Ajax non-forms
    $(document).on('click','.viewApprovedLeaveRegularDetails,.viewApprovedLeaveSpecialDetails',function(e){
        e.preventDefault();
        me = $(this)
        id = me.attr('data-id');
        url = me.attr('href');  
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = me.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'viewApprovedLeaveSpecialDetails':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Approved Leave Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $("#special_leave_dates").hide();
                            $("#sick_spent_content").hide();
                            $("#milestone_content").hide();
                            $("#domestic_content").hide();
                            $("#personal_content").hide();
                            $("#calamity_content").hide();
                            $("#parental_content").hide();
                            $("#filial_content").hide();
                            $.each(me.data(),function(i,v){
                                console.log(i +' : ' + v);
                                if(i == "leave_type"){
                                    console.log(me.data(i));
                                    $('#radio_'+me.data(i)).change().click();
                                }
                                el = $('.'+i)
                                el_type = $('.'+i).attr('type');
                                if(el.is("select") || el.is("textarea") || el_type == "text" || el_type == "hidden" ){
                                    $('.'+i).val(me.data(i)).change();
                                }
                            });

                            var getLeaveDates = function() {
                                var temp = null;
                                url2 = commons.baseurl + "leavemanagement/ApprovedLeave/getLeaveDates";
                                data2 = {id:id}
                                $.ajax({
                                    async: false,
                                    url: url2,
                                    data: data2,
                                    type:'POST',
                                    dataType:'JSON',
                                    success: function(res) {
                                        temp = res;
                                        //console.log("meron "+res);
                                    }
                                });  
                                
                                return temp;
                            }();
                            if(getLeaveDates.Code == 0){
                                $('.days_of_leave_container').html('')
                                $.each(getLeaveDates.Data.details,function(i,v){
                                    button = "";
                                    /*if(i == 0){
                                        button = "";
                                    }
                                    else{
                                        button = '<button type="button" class="removeLeaveDays btn btn-danger btn-circle waves-effect waves-circle waves-float">'
                                               +    '<i class="material-icons">remove</i>'
                                               + '</button>'
                                    }*/
                                    leaveform = ''
                                    +    '<div class="row days_row clearfix">'
                                    +        '<div class="col-md-12">'
                                    +            '<div class="row">'
                                    +                '<div class="col-md-10">'
                                    +                    '<label class="form-label" style="font-size: 1.25rem">'
                                    +                        '<!-- <small>From</small> -->'
                                    +                    '</label>'
                                    +                    '<div class="form-group form-float">'
                                    +                        '<div class="form-line">'
                                    +                            '<input type="text" class="days_of_leave daysleave form-control" value="'+v.days_of_leave+'" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>'
                                    +                        '</div>'
                                    +                    '</div>'
                                    +                '</div>'
                                    +                '<div class="col-md-2 text-right">'
                                    +                   button                  
                                    +                '</div>'
                                    +            '</div>'
                                    +        '</div>'
                                    +    '</div>'
                                    $('.days_of_leave_container').append(leaveform);
                                })
                            }
                            $("form :input").attr("disabled", true);
                            $("#cancelUpdateForm").removeAttr('disabled');
                            break;
                        case 'viewApprovedLeaveRegularDetails':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Approved Leave Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $("#other_vacation_type_content").hide();
                            $("#vacation_sick_leave_header").hide();
                            $("#other_leave_type_content").hide();
                            $("#abroad_location_content").hide();
                            $("#vacation_spent_content").hide();
                            $("#additional_info_header").hide();
                            $("#hospital_name_content").hide();
                            $("#vacation_type_content").hide();

                            $.each(me.data(),function(i,v){
                                console.log(i +' : ' + v);
                                if(i == "leave_type" || i == "leave_type_vacation"){
                                    console.log(me.data(i));
                                    $('#radio_'+me.data(i)).change().click();
                                    $('#radio_vacation_'+me.data(i)).change().click();
                                }
                                if(i == "leave_type_vacation_location" && v == "Abroad"){
                                    $('.leave_type_vacation_location').change().click();
                                }
                                if(i == "leave_commutation" && v == "Requested"){
                                    $('.leave_commutation').attr('checked', true).change();
                                }
                                if(i == "leave_type_sick_location" && v == "Hospital"){
                                    $('#leave_type_sick_location').change().click();
                                }
                                el = $('.'+i)
                                el_type = $('.'+i).attr('type');
                                if(el.is("select") || el.is("textarea") || el_type == "text" || el_type == "hidden" ){
                                    $('.'+i).val(me.data(i)).change();
                                }
                            });

                            var getLeaveDates = function() {
                                var temp = null;
                                url2 = commons.baseurl + "leavemanagement/ApprovedLeave/getLeaveDates";
                                data2 = {id:id}
                                $.ajax({
                                    async: false,
                                    url: url2,
                                    data: data2,
                                    type:'POST',
                                    dataType:'JSON',
                                    success: function(res) {
                                        temp = res;
                                        //console.log("meron "+res);
                                    }
                                });  
                                
                                return temp;
                            }();
                            if(getLeaveDates.Code == 0){
                                $('.days_of_leave_container').html('')
                                $.each(getLeaveDates.Data.details,function(i,v){
                                    button = "";
                                    /*if(i == 0){
                                        button = "";
                                    }
                                    else{
                                        button = '<button type="button" class="removeLeaveDays btn btn-danger btn-circle waves-effect waves-circle waves-float">'
                                               +    '<i class="material-icons">remove</i>'
                                               + '</button>'
                                    }*/
                                    leaveform = ''
                                    +    '<div class="row days_row clearfix">'
                                    +        '<div class="col-md-12">'
                                    +            '<div class="row">'
                                    +                '<div class="col-md-10">'
                                    +                    '<label class="form-label" style="font-size: 1.25rem">'
                                    +                        '<!-- <small>From</small> -->'
                                    +                    '</label>'
                                    +                    '<div class="form-group form-float">'
                                    +                        '<div class="form-line">'
                                    +                            '<input type="text" class="days_of_leave daysleave form-control" value="'+v.days_of_leave+'" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>'
                                    +                        '</div>'
                                    +                    '</div>'
                                    +                '</div>'
                                    +                '<div class="col-md-2 text-right">'
                                    +                   button                  
                                    +                '</div>'
                                    +            '</div>'
                                    +        '</div>'
                                    +    '</div>'
                                    $('.days_of_leave_container').append(leaveform);
                                })
                            }
                            $("form :input").attr("disabled", true);
                            $("#cancelUpdateForm").removeAttr('disabled');
                            break;
                    }
                    $("#"+result.key).validate({
                        rules:
                        {
                            ".required":
                            {
                                required: true
                            },
                            ".email":
                            {
                                required: true,
                                email: true
                            }
                        },
                        highlight: function (input) {
                            $(input).parents('.form-line').addClass('error');
                        },
                        unhighlight: function (input) {
                            $(input).parents('.form-line').removeClass('error');
                        },
                        errorPlacement: function (error, element) {
                            $(element).parents('.form-group').append(error);
                        }
                    });
                }
            },
            error: function(result){
                $.alert({
                    title:'<label class="text-danger">Failed</label>',
                    content:'There was an error in the connection. Please contact the administrator for updates.'
                });
            }
        });
    })
    //Owen Codes
    //Regular Owen Codes
    $(document).on('change','.leave_type',function () {
        if ($('#radio_vacation').is(':checked')) {
            $("#vacation_sick_leave_header").show();
            $("#other_leave_type_content").hide();
            $("#vacation_spent_content").show();
            $("#vacation_type_content").show();
            $("#sick_spent_content").hide();
        }
        if ($('#radio_sick').is(':checked')) {
            $("#vacation_sick_leave_header").show();
            $("#other_leave_type_content").hide();
            $("#vacation_spent_content").hide();
            $("#vacation_type_content").hide();
            $("#sick_spent_content").show();
        }
        if ($('#radio_maternity').is(':checked')) {
            $("#vacation_sick_leave_header").hide();
            $("#other_leave_type_content").hide();
            $("#vacation_spent_content").hide();
            $("#vacation_type_content").hide();
            $("#sick_spent_content").hide();
        }
        if ($('#radio_others').is(':checked')) {
            $("#vacation_sick_leave_header").hide();
            $("#other_leave_type_content").show();
            $("#vacation_spent_content").hide();
            $("#vacation_type_content").hide();
            $("#sick_spent_content").hide();
        }
    });

    $(document).on("click", ".leave_type_vacation", function () {
        if ($('#radio_vacation_seek').is(':checked')) {
            $("#other_vacation_type_content").hide();
        }
        if ($('#radio_vacation_others').is(':checked')) {
            $("#other_vacation_type_content").show();
        }
    });

    $(document).on("click", ".leave_type_vacation_location", function () {
        $("#abroad_location_content").toggle();
    });

    $(document).on("click", ".leave_type_sick_location", function () {
        $("#hospital_name_content").toggle();
    });
    //Special Owen Codes
    $(document).on("change", ".leave_type", function () {
    //$("input[name='special_leave_radio_group']").change(function () {

        if ($('#radio_personal_milestone').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").show();
            $("#parental_content").hide();
            $("#domestic_content").hide();
            $("#personal_content").hide();
            $("#calamity_content").hide();
            $("#filial_content").hide();

        }
        if ($('#radio_parental_obligation').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").hide();
            $("#parental_content").show();
            $("#domestic_content").hide();
            $("#personal_content").hide();
            $("#calamity_content").hide();
            $("#filial_content").hide();
        }
        if ($('#radio_filial_obligations').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").hide();
            $("#parental_content").hide();
            $("#domestic_content").hide();
            $("#personal_content").hide();
            $("#calamity_content").hide();
            $("#filial_content").show();
        }
        if ($('#radio_domestic_emergencies').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").hide();
            $("#parental_content").hide();
            $("#domestic_content").show();
            $("#personal_content").hide();
            $("#calamity_content").hide();
            $("#filial_content").hide();
        }
        if ($('#radio_personal_transactions').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").hide();
            $("#parental_content").hide();
            $("#domestic_content").hide();
            $("#personal_content").show();
            $("#calamity_content").hide();
            $("#filial_content").hide();
        }
        if ($('#radio_accident').is(':checked')) {
            $("#additional_info_header").show();
            $("#special_leave_dates").show();
            $("#milestone_content").hide();
            $("#parental_content").hide();
            $("#domestic_content").hide();
            $("#personal_content").hide();
            $("#calamity_content").show();
            $("#filial_content").hide();
// }
        }
    });
    //End Owen Codes
    function loadTable(){
        var url = window.location.href;
        $.ajax({url: url,dataType:"json", success: function(result){
            $("#table-holder").html(result.table);
            table = $('#datatables').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                responsive: true,
                aaSorting: [],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }

            });
        }});
    }
    console.log(commons.baseurl)
    function PrintElem(elem)
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
        mywindow.document.write('</head><body >');
        mywindow.document.write( document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
})