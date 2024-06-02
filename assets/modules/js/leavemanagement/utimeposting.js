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
        // getFields.location()
        getFields.division()
    ).done(function(){
        if($('#leave_tracking_all_access').val() == 0){
            $('.division_id').val($('#division_id_hide').val()).change();
            $('.division_id').attr('disabled',true);
        }
        else {
          $.AdminBSB.select.activate(); 
        }       
    })
    var employee_id = null;
    // $(document).on('change','#division_id ',function(e){
    //     division_id = $(this).val();
    //     location_id = $('#location_id').val();
    //     $.when(
    //         getFields.employee({location_id:location_id,division_id:division_id})
    //     ).done(function(){
    //         // alert(employee_id)
    //         $('.employee_id').val(employee_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })
    $(document).on('change','#division_id ',function(e){
        division_id = $('#division_id').val();
        $.when(
            getFields.employee({division_id:division_id})
        ).done(function(){
            // alert(employee_id)
            $('.employee_id').val(employee_id).change();
            $.AdminBSB.select.activate();
        })
    })
    // var division_id = null;
    // $(document).on('change','#location_id ',function(e){
    //     location_id = $(this).val();
    //     $.when(
    //         getFields.division()
    //     ).done(function(){
    //         // alert(employee_id)
    //         if(division_id != null)
    //             $('.division_id').val(division_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })
    $(document).on('show.bs.modal','#myModal', function () {
        $.AdminBSB.dropdownMenu.activate();
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
        $.AdminBSB.search.activate();
        $('.daystimepicker').inputmask('s:h:s',{placeholder:'dd:hh:mm'});
    });
    $(document).on('click','#addUTimeRow',function(e){
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
        $('.row_'+index).find('#removeUTimeRow').css('visibility','visible');
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
                    $('.daystimepicker').inputmask('s:h:s',{placeholder:'dd:hh:mm'});
                }
            }
        });
    });
    $(document).on('click','.removeUTimeRow',function(e){
        $(this).closest('tr').remove();
    })
    //Sick
    
    $(document).on('click','#searchEmployeeUTime',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        division_id = $('.search_row #division_id').val()
        employee_id = $('.search_row #employee_id').val()
        leave_year = $('.search_row #search_year').val()
        plus_url = '?DivisionId='+division_id+'&EmployeeId='+employee_id+'&LeaveYear='+leave_year
        
        if(division_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a department.'
            });
        }
        else if(employee_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select an employee.'
            });
        }
        else if(leave_year == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a year.'
            });
        }
        else{
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function(result){
                    $('#table-holder').html(result.table);
                    //employment_status = $('#hide_emp_status').val();
                    //plus_url = ""
                    /*if(employment_status != ""){
                        plus_url = '?EmploymentStatus='+employment_status;
                    }*/
                    table = $('#datatables').DataTable({  
                           "processing":true,  
                           "serverSide":true,  
                           "order":[],  
                           "ajax":{  
                                url:commons.baseurl+ "leavemanagement/UTimePosting/fetchRows"+plus_url,  
                                type:"POST"  
                           },  
                           "columnDefs":[  
                                {  
                                    "targets": [0],
                                    "orderable":false,  
                                },  
                           ],  
                    });
                },
                error: function(result){
                    $.alert({
                        title:'<label class="text-danger">Failed</label>',
                        content:'There was an error in the connection. Please contact the administrator for updates.'
                    });
                }
            });
        }
    })
    //Confirms
    $(document).on('click','.activateUTimePosting,.deactivateUTimePosting',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateUTimePosting')){
            content = 'Are you sure you want to activate selected UTime?';
        }
        else if(me.hasClass('deactivateUTimePosting')){
            content = 'Are you sure you want to deactivate selected UTime?';
        }
        data = {id: id};
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
                                    data: {id:id},
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'activateUTimePosting':
                                                    case 'deactivateUTimePosting':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        $('#searchEmployeeUTime').click();
                                                        break;
                                                }
                                            }  
                                        }
                                        else{
                                            self.setContent(result.Message);
                                            self.setTitle('<label class="text-danger">Failed</label>');
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
    //Ajax non-forms
    $(document).on('click','#addEmployeeUTimeForm,.updateEmployeeUTimeForm',function(e){
        location_id = $('.search_row #location_id').val();
        division_id = $('.search_row #division_id').val();
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
                        case 'addEmployeeUTime':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Leave Ledger Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateEmployeeUTime':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Leave Ledger Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                    }
                    if(result.key != "employeeUTimePosting"){
                        $.when(
                            // getFields.location(),
                            getFields.division(),
                            getFields.month()
                        ).done(function(){
                            $('.month').attr('name','month_code[]').attr('id','month_code').addClass('month_code');
                            $.each(my.data(),function(i,v){
                                if(i != "location_id")
                                    $('.'+i).val(my.data(i)).change();
                            })
                            
                            if(result.key == "updateEmployeeUTime")
                                $('.division_id').val(my.data('division_id')).change()
                            else
                                $('.division_id').val(division_id).change()
                            employee_id = employee_id;
                            if($('#leave_tracking_all_access').val() == 0){
                                $('.division_id').val($('#division_id_hide').val()).change();
                                $('.division_id').attr('disabled',true);
                            }
                            else{
                                $(".division_id").selectpicker("render");
                            }
                            $(".month").selectpicker("render");
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
    $(document).on('submit','#addEmployeeUTime,#updateEmployeeUTime,#generateEmployeeUTime',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addEmployeeUTime"){
            content = "Are you sure you want to add employee UTime?";
        }
        if(form.attr('id') == "updateEmployeeUTime"){
            content = "Are you sure you want to update employee UTime?";
        }
        if(form.attr('id') == "generateEmployeeUTime"){
            content = "Are you sure you want to generate employee employee UTime?";
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
                                                        case 'addEmployeeUTime':
                                                        case 'updateEmployeeUTime':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            division_id = $('#myModal #division_id').val();
                                                            employee_id = $('#myModal #employee_id').val();
                                                            leave_year = $('#myModal #leave_year').val();
                                                            $('.leave_year').val(leave_year).change();

                                                            $('.division_id').selectpicker('val',division_id);
                                                            $('.employee_id').selectpicker('val',employee_id);
                                                            $('.leave_year').selectpicker('val',leave_year);

                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            $('#searchEmployeeUTime').click();
                                                            break;
                                                        case 'generateEmployeeUTime':
                                                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                                            $('#myModal .modal-title').html('Employee UTime Posting');
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
    // console.log(commons.baseurl)
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