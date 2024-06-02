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
    $.when(
        getFields.location()
    ).done(function(){
        $.AdminBSB.select.activate();
    })
    var employee_id = null;
    $(document).on('change','#location_id ',function(e){
        location_id = $(this).val();
        $.when(
            getFields.employee({location_id:location_id})
        ).done(function(){
            // alert(employee_id)
            $('.employee_id').val(employee_id).change();
            $.AdminBSB.select.activate();
        })
    })
    $(document).on('show.bs.modal','#myModal', function () {
        $.AdminBSB.dropdownMenu.activate();
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
        $.AdminBSB.search.activate();
    });
    $(document).on('click','#addLedgerRow',function(e){
        e.preventDefault()
        first_row = $('#leaveapplicationtable').find('.row_0').html();
        index = $('#leaveapplicationtable tbody tr').length;
        $('#leaveapplicationtable tbody').append('<tr class="row_'+index+'">'+first_row+'</tr>');
        $.each($('.row_'+index+' .form-control'),function(){
            name = $(this).attr('name');
            id = $(this).attr('id');
            indexed_name = name.replace('[]','['+index+']');
            $(this).attr('name',indexed_name);
            $(this).attr('id',id+index);
        })
        $('.row_'+index).find('.month_select').html('')
        $('.row_'+index).find('#removeLedgerRow').css('visibility','visible');
        var temp = null;
        url2 = commons.baseurl + "months/Months/getActiveMonths";
        $.ajax({
            url: url2,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    before_month = $('.row_'+(index-1)).find('select.month_code').val();
                    select = '<select data-container="body" class="month_code form-control required" name="month_code['+index+']" id="month_code" data-live-search="true">'
                    options = '<option value=""></option>'
                    $.each(temp.Data.details, function(i,v){
                        options += '<option value="'+v.code+'">'+v.name+'</option>'      
                    }) 
                    select += options
                    select += '</select>'
                    $('.row_'+index).find('.month_select').html(select);
                    new_val = Number(before_month) + 1
                    if(new_val.toString().length == 1){
                        new_val = "0" + new_val.toString(); 
                    }
                    $('.row_'+index).find('select.month_code').val(new_val).change();
                    $.AdminBSB.select.activate();
                    $('.row_0').find('.leave_vacation_balance').change();
                    $('.row_0').find('.leave_sick_balance').change();
                }
            }
        });
    });
    $(document).on('click','.removeLedgerRow',function(e){
        $(this).closest('tr').remove();
    })
    //Print
    $(document).on('click','#printClearance',function(e){
        e.preventDefault();
        PrintElem("clearance-div");
    })
    //Vacation
    $(document).on('change keypress keyup keydown','.leave_vacation_balance',function(e){
        myClass = $(this).closest('tr').attr('class');
        split_class = myClass.split('_');
        index = split_class[1];
        tbody_length = $('#leaveapplicationtable tbody tr').length;
        for(i = Number(index) + 1; i<=tbody_length; i++ ){
            prev_val = $('.row_'+(i-1)).find('.leave_vacation_balance').val();
            earned = $('.row_'+i).find('.leave_vacation_earned').val();
            deduct = $('.row_'+i).find('.leave_vacation_undertime_w_pay').val();
            new_val = Number(prev_val) + Number(earned) - Number(deduct);
            $('.row_'+i).find('.leave_vacation_balance').val(new_val);
        }
    })
    
    $(document).on('keypress keyup keydown change','.leave_vacation_earned',function(e){
        myClass = $(this).closest('tr').attr('class');
        split_class = myClass.split('_');
        index = split_class[1];
        if(index != "0"){
            brought = $('.row_'+(Number(index) - 1)).find('.leave_vacation_balance').val();
            earned  = $(this).val();
            deduct = $('.row_'+index).find('.leave_vacation_undertime_w_pay').val();
            // console.log((deduct))
            new_val = Number(brought) + Number(earned) - Number(deduct);
            $(this).closest('tr').find('.leave_vacation_balance').val(new_val);
            console.log(new_val)
        }
        $(this).closest('tr').find('.leave_vacation_balance').change(); 
        
    })
    $(document).on('keypress keyup keydown change','.leave_vacation_undertime_w_pay',function(e){
        myClass = $(this).closest('tr').attr('class');
        split_class = myClass.split('_');
        deduct = $(this).val();
        index = split_class[1];
        if(index != "0"){
            brought = $('.row_'+(Number(index) - 1)).find('.leave_vacation_balance').val();
            earned  = $(this).closest('tr').find('.leave_vacation_earned').val();
            new_val = Number(brought) - Number(deduct) + Number(earned);
            $(this).closest('tr').find('.leave_vacation_balance').val(new_val);
        }
        $(this).closest('tr').find('.leave_vacation_balance').change(); 
        
    })
    //Sick
    $(document).on('change keypress keyup keydown','.leave_sick_balance',function(e){
        myClass = $(this).closest('tr').attr('class');
        split_class = myClass.split('_');
        index = split_class[1];
        tbody_length = $('#leaveapplicationtable tbody tr').length;
        for(i = Number(index) + 1; i<=tbody_length; i++ ){
            prev_val = $('.row_'+(i-1)).find('.leave_sick_balance').val();
            earned = $('.row_'+i).find('.leave_sick_earned').val();
            deduct = $('.row_'+i).find('.leave_sick_undertime_w_pay').val();
            new_val = Number(prev_val) + Number(earned) - Number(deduct);
            $('.row_'+i).find('.leave_sick_balance').val(new_val);
        }
    })
    $(document).on('keypress keyup keydown change','.leave_sick_undertime_w_pay',function(e){
        myClass = $(this).closest('tr').attr('class');
        split_class = myClass.split('_');
        deduct = $(this).val();
        index = split_class[1];
        if(index != "0"){
            brought = $('.row_'+(Number(index) - 1)).find('.leave_sick_balance').val();
            earned  = $(this).closest('tr').find('.leave_sick_earned').val();
            new_val = Number(brought) - Number(deduct) + Number(earned);
            $(this).closest('tr').find('.leave_sick_balance').val(new_val);
        }
        $(this).closest('tr').find('.leave_sick_balance').change(); 
        
    })
    //Change Employee
    $(document).on('change','#employee_id',function(e){

        id = $(this).val();
        employee_name = $(this).find("option:selected").text();
       
        position ="N/A"
        
        status ="N/A"
        
        civil_status ="N/A"
        
        entrance_to_duty ="N/A"
        
        unit ="N/A"
        
        gsis ="N/A"
        
        tin ="N/A"
        
        nrcn ="N/A"
        
        url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    status = temp.Data.details[0].employment_status;
                    //civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].gsis;
                    tin = temp.Data.details[0].tin;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('.employee_name').val(employee_name);
        $('.employee_name_text').html(employee_name);

        $('.employee_position').val(position);
        $('.employee_position_text').html(position);

        $('.employee_status').val(status);
        $('.employee_status_text').html(status);

        $('.employee_civil_status').val(civil_status);
        $('.employee_civil_status_text').html(civil_status);

        $('.employee_unit').val(unit);
        $('.employee_unit_text').html(unit);

        $('.employee_entrance_to_duty').val(entrance_to_duty);
        $('.employee_entrance_to_duty_text').html(entrance_to_duty);

        $('.employee_gsis').val(gsis);
        $('.employee_gsis_text').html(gsis);

        $('.employee_tin').val(tin);
        $('.employee_tin_text').html(tin);

        $('.employee_nrcn').val(nrcn);
        $('.employee_nrcn_text').html(nrcn);
    })
    $(document).on('click','#searchEmployeeLedger',function(e){
        employee_id = $('.search_row #employee_id').val();
        year = $('#search_year').val();
        data = {employee_id:employee_id, year:year}
        loader =    '<center><div class="preloader pl-size-xl">'
                    +    '<div class="spinner-layer">'
                    +        '<div class="circle-clipper left">'
                    +            '<div class="circle"></div>'
                    +        '</div>'
                    +        '<div class="circle-clipper right">'
                    +            '<div class="circle"></div>'
                    +        '</div>'
                    +    '</div>'
                    +'</div></center>';
        $('#table-holder').html(loader)
        e.preventDefault();
        my = $(this)
        url = my.attr('href');  
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'employeeLeaveLedger':
                            $('#table-holder').html(result.table);
                            break;
                        
                    }
                
                        
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
    //Ajax non-forms
    $(document).on('click','#addEmployeeLedgerForm,.updateEmployeeLedgerForm',function(e){
        location_id = $('.search_row #location_id').val();
        employee_id = $('.search_row #employee_id').val();
        year = $('#search_year').val();
        data = {employee_id:employee_id, year:year}
        e.preventDefault();
        my = $(this)
        url = my.attr('href');  
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'addEmployeeLedger':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg').css('width','1200px');
                            $('#myModal .modal-title').html('Leave Ledger Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateEmployeeLedger':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg').css('width','1200px');
                            $('#myModal .modal-title').html('Leave Ledger Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                    }
                    if(result.key != "employeeLeaveLedger"){
                        $.when(
                            getFields.location(),
                            getFields.month()
                        ).done(function(){
                            $('.month').attr('name','month_code[]').attr('id','month_code').addClass('month_code');
                            $.each(my.data(),function(i,v){
                                $('.'+i).val(my.data(i)).change();
                            })
                            employee_id = employee_id;
                            $('.location_id').val(location_id).change()
                            $.AdminBSB.select.activate();  
                        })
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
    //Ajax Forms
    $(document).on('submit','#addEmployeeLedger,#updateEmployeeLedger,#generateEmployeeLedger',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addEmployeeLedger"){
            content = "Are you sure you want to add employee ledger?";
        }
        if(form.attr('id') == "updateEmployeeLedger"){
            content = "Are you sure you want to update employee ledger?";
        }
        if(form.attr('id') == "generateEmployeeLedger"){
            content = "Are you sure you want to generate employee employee ledger?";
        }
        url = form.attr('action');
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $.confirm({
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'addEmployeeLedger':
                                                        case 'updateEmployeeLedger':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            // $('#searchEmployeeLedger').click();
                                                            break;
                                                        case 'generateEmployeeLedger':
                                                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                                            $('#myModal .modal-title').html('Employee Service Record');
                                                            $('#myModal .modal-body').html(result.table);
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            break;
                                                    }
                                                }  
                                            }
                                            else{
                                                self.setContent(result.Message);
                                                self.setTitle('<label class="text-danger">Failed</label>');
                                            }
                                        }
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function () {
                }
            }
        });
    })
    //Owen Codes
    //Regular Owen Codes
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