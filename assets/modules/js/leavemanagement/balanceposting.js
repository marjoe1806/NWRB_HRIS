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
        // getFields.location(),
        // getFields.division()
        getFields.leaveGrouping()
    ).done(function(){
        $.AdminBSB.select.activate();
    })
    
    // var division_id = null;
    // $(document).on('change','#myModal #location_id',function(e){
    //     $.when(
    //         getFields.division(),
    //     ).done(function(){
    //         // alert(employee_id)
    //         if(division_id != "")
    //             $('.division_id').val(division_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })
    var employee_id = null;
    // $(document).on('change','#myModal #division_id',function(e){
    //     location_id = $('#myModal #location_id').val();
    //     division_id = $('#myModal #division_id').val();
    //     $.when(
    //         getFields.employee({location_id:location_id, division_id:division_id})
    //     ).done(function(){
    //         // alert(employe e_id)
    //         $('.employee_id').attr('name','employee_id[]');
    //         $('.employee_id').val(employee_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })

    $(document).on('change','#myModal #leave_grouping_id',function(e){
        leave_grouping_id = $('#myModal #leave_grouping_id').val();
        $.when(
            getFields.employee({leave_grouping_id:leave_grouping_id})
        ).done(function(){
            // alert(employe e_id)
            $('.employee_id').attr('name','employee_id[]');
            $('.employee_id').val(employee_id).change();
            $.AdminBSB.select.activate();
        })
    })
    $(document).on('show.bs.modal','#myModal', function () {
        $.AdminBSB.dropdownMenu.activate();
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
        $.AdminBSB.search.activate();
        $('.daystimepicker').inputmask('s:h:s',{placeholder:'dd:hh:mm'});
    });
    $(document).on('click','#addBalanceRow',function(e){
        e.preventDefault()
        first_row = $('#leaveapplicationtable').find('.row_0').html();
        index = $('#leaveapplicationtable tbody tr').length;
        $('#leaveapplicationtable tbody').append('<tr class="row_'+index+'">'+first_row+'</tr>');
        $('.row_'+index).find('#removeBalanceRow').css('visibility','visible');
        $.each($('.row_'+index+' .form-control'),function(){
            name = $(this).attr('name');
            id = $(this).attr('id');
            indexed_name = name.replace('[]','['+index+']');
            $(this).attr('name',indexed_name);
            $(this).attr('id',id+index);
        })
        $('.row_'+index).find('.year_select').html('')
        years = getYearRange(1914);
        year_select = '<select data-container="body" class="leave_year form-control" name="leave_year['+index+']" id="leave_year" data-live-search="true" required>'                              
        $.each(years,function(i,v){
            year_select+='<option value="'+v+'">'+v+'</option>'
        });
        year_select += '</select>'
        $('.row_'+index).find('.year_select').html(year_select)

        //Fetch Employees
        var temp = null;
        employee_select = '<select data-container="body" class="employee_id form-control " name="employee_id['+index+']" id="employee_id" data-live-search="true" required><option value="">Loading...</option></select>'
        $('.row_'+index).find('.employee_select').html(employee_select)
        url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
        location_id = $('#myModal #location_id').val();
        division_id = $('#myModal #division_id').val();
        data = {leave_grouping_id:leave_grouping_id}
        return $.ajax({
            url: url2,
            data: data,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                select = '<select data-container="body" class="employee_id form-control " name="employee_id['+index+']" id="employee_id" data-live-search="true" required>'
                    options = '<option value=""></option>'
                if(temp.Code == "0"){
                    $.each(temp.Data.details, function(i,v){
                        options += '<option value="'+v.id+'">'+v.employee_id_number+' - '+v.first_name+' '+v.middle_name+' '+v.last_name+'</option>'
                    })
                    //$('.selectpicker').selectpicker('refresh')
                }
                select += options
                select += '</select>'
                $('.row_'+index).find('.employee_select').html(select)
                $.AdminBSB.select.activate();
            }
        });
        
        
    });
    $(document).on('click','.removeBalanceRow',function(e){
        $(this).closest('tr').remove();
    })
    //Sick
    
    $(document).on('click','#searchEmployeeBalance',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        // location_id = $('.search_row #location_id').val()
        // division_id = $('.search_row #division_id').val()
        // plus_url = '?LocationId='+location_id+'&DivisionId='+division_id

        leave_grouping_id = $('.search_row #leave_grouping_id').val()
        plus_url = '?LeaveGroupingId='+leave_grouping_id
        // if(location_id == ""){
        //     $.alert({
        //         title:'<label class="text-danger">Failed</label>',
        //         content:'Please select a location.'
        //     });
        // }
        // else if(division_id == ""){
        //     $.alert({
        //         title:'<label class="text-danger">Failed</label>',
        //         content:'Please select a department.'
        //     });
        // }
        if(leave_grouping_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Leave Group.'
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
                                url:commons.baseurl+ "leavemanagement/BalancePosting/fetchRows"+plus_url,  
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
    $(document).on('click','.activateBalancePosting,.deactivateBalancePosting',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateBalancePosting')){
            content = 'Are you sure you want to activate selected Balance?';
        }
        else if(me.hasClass('deactivateBalancePosting')){
            content = 'Are you sure you want to deactivate selected Balance?';
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
                                                    case 'activateBalancePosting':
                                                    case 'deactivateBalancePosting':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        $('#searchEmployeeBalance').click();
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
    $(document).on('click','#addEmployeeBalanceForm,.updateEmployeeBalanceForm',function(e){
        var location_id = $('.search_row #location_id').val();
        division_id = $('.search_row #division_id').val();
        data = {location_id:location_id, division_id:division_id}
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
                        case 'addEmployeeBalance':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Balance Posting Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateEmployeeBalance':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Balance Form');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                    }
                    if(result.key != "employeeBalancePosting"){
                        $.when(
                            // getFields.location(),
                            // getFields.division()
                            getFields.leaveGrouping()
                        ).done(function(){
                    
                            $.each(my.data(),function(i,v){
                                if(i != 'location_id'){
                                    $('.'+i).val(my.data(i)).change();
                                }
                            })
                            $('.location_id').val(location_id).change()
                            division_id = division_id
                            employee_id = my.data('employee_id');
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
    $(document).on('submit','#addEmployeeBalance,#updateEmployeeBalance,#generateEmployeeBalance',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addEmployeeBalance"){
            content = "Are you sure you want to add employee Balance?";
        }
        if(form.attr('id') == "updateEmployeeBalance"){
            content = "Are you sure you want to update employee Balance?";
        }
        if(form.attr('id') == "generateEmployeeBalance"){
            content = "Are you sure you want to generate employee Balance?";
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
                                                        case 'addEmployeeBalance':
                                                        case 'updateEmployeeBalance':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            // location_id = $('#myModal #location_id').val();
                                                            // division_id = $('#myModal #division_id').val();
                                                            leave_grouping_id = $('#myModal #leave_grouping_id').val();
                                                            
                                                            // $('.location_id').val(location_id).change();
                                                            // $('.division_id').val(division_id).change();

                                                            // $('.location_id').selectpicker('val',location_id);
                                                            // $('.division_id').selectpicker('val',division_id);
                                                            $('.leave_grouping_id').selectpicker('val',leave_grouping_id);

                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            $('#searchEmployeeBalance').click();
                                                            break;
                                                        case 'generateEmployeeBalance':
                                                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                                            $('#myModal .modal-title').html('Employee Balance Posting');
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
    function getYearRange(startYear){
        var years=[];
        currentYear = new Date().getFullYear();
        while(currentYear >= startYear){
            years.push(currentYear)
            currentYear--
        }
        return years;
    }
})