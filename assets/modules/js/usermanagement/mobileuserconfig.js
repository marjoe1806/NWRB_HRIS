$(function(){
    var base_url = commons.baseurl;
    loadTable();

    //Ajax non-forms
    //event triggered upon clicking of add record, view mobile user, or update mobile user
    //display mobile user form, mobile user details will be displayed for viewing and update 
    $(document).on('click','#addMobileUserConfigForm,.viewMobileUserConfigForm',function(e){
        e.preventDefault();
        my = $(this)
        data = my.data();
        id = my.data('id');
        employee_id = id
        url = my.attr('href'); 
        if(!my.find('button').is(':disabled')){
            $.ajax({
                type: "POST",
                url: url,
                data: {id:id},
                dataType: "json",
                success: function(result){
                    page = my.attr('id');
                    if(result.hasOwnProperty("key")){
                        switch(result.key){
                            case 'addMobileUserConfig':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                                $('#myModal .modal-title').html('Add New Mobile User');
                                $('#myModal .modal-body').html(result.form);        
                                $('#myModal').modal('show');                      
                                break;
                            case 'viewMobileUserConfig':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                                $('#myModal .modal-title').html('Mobile User Details');
                                $('#myModal .modal-body').html(result.form);
                                break;
                        }

                        if(result.key != 'addMobileUserConfig') {
                            $.each(my.data(),function(i,v){
                                if(i == 'employee_id') {
                                    employee_id = my.data(i);
                                } else {
                                    $('#myModal .'+i).val(my.data(i)).change();
                                    if(v == "0" || v == "1"){
                                        $('#myModal .'+i+v).click();
                                    } 
                                }                  
                            });
                            $('#myModal').modal('show'); 
                        } else {
                            employee_id = null;
                        }

                        if(result.key =="viewMobileUserConfig"){
                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            $('form').find('#cancelUpdateForm').removeAttr('disabled');
                        }

                        $.AdminBSB.select.activate();
                    }
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

    //Ajax Forms
    //event triggered upon submitting mobile user form for adding mobile users
    $(document).on('submit','#addMobileUserConfig',function(e){
        var formData = new FormData($(this)[0]);
        
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addMobileUserConfig"){
            content = "Credentials will be sent to employee's email. Do you want to proceed?";
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
                                    dataType: "json",
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'addMobileUserConfig':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            reloadTable();
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

    //event triggered when clicking activate/deactivate button
    //update mobile user status (active/inactive)
    $(document).on('click','.activateMobileUserConfig,.deactivateMobileUserConfig',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        var employee_id = me.attr('data-employee-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateMobileUserConfig')){
            content = 'Are you sure you want to activate this mobile user?';
        } else if(me.hasClass('deactivateMobileUserConfig')){
            content = 'Are you sure you want to deactivate this mobile user?';
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
                                    data: {
                                        id:id,
                                        employee_id
                                    },
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'activateMobileUserConfig':
                                                    case 'deactivateMobileUserConfig':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        reloadTable();
                                                        break;
                                                }
                                            }  
                                        } else{
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

    //event triggered when clicking reset password button
    //reset mobile user password to default
    $(document).on('click','.resetPassword',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        var employee_id = me.attr('data-employee-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('resetPassword')){
            content = 'Are you sure you want to reset the password of this mobile user?';
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
                                    data: {
                                        id:id,
                                        employee_id
                                    },
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'resetPassword':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        reloadTable();
                                                        break;
                                                }
                                            }  
                                        } else{
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

    //event triggered when clicking load filtered mobile user button
    //update list based on the filters selected (location, department, status)
    $(document).on('click','#viewFilteredMobileUsers',function(e){
        e.preventDefault();
        loadTable();
        $.AdminBSB.select.activate();
    })

    //initialize employee_id value
    var employee_id;

    //event triggered when changing location dropdown
    //update list of employee dropdown
    $(document).on('change','#location_id ',function(e){
        location_id = $(this).val();
        $.when(
            loadEmployees(location_id)
        ).done(function(){
            $('#employee_id').val(employee_id).change();
            $.AdminBSB.select.activate();        
        }) 
    });

    //event triggered when changing employee dropdown
    //update data for position and department
    $(document).on('change','#employee_id',function(e){
        me = $(this)
        id = me.val();

        employee_name = $(this).find("option:selected").text();
        url = commons.baseurl + "employees/Employees/getEmployeesById?Id="+id;

        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url,
            data:{id: id},
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                }
            }
        });
        $('form .position_name').val(position);
        $('form .department_name').val(dept_id);

    })
})

//initialize table to be displayed
function loadTable(){
    location_id = $('.listTable #location_id').val();
    department_id = $('.listTable #department_id').val();
    status = $('.listTable #status').val();

    plus_url = ""
    if(status != ""){
        plus_url += '?Status='+status;
    }
    if(location_id != ""){
        plus_url += '&LocationId='+location_id;
    }
    if(department_id != ""){
        plus_url += '&DepartmentId='+department_id;
    }
    
    $('#datatables').DataTable().clear().destroy();
    table = $('#datatables').DataTable({  
        "processing":true,  
        "serverSide":true,  
        "stateSave": true, // presumably saves state for reloads -- entries
        "bStateSave": true, // presumably saves state for reloads -- page number
        "order":[],
        scroller: {
            displayBuffer: 20
        },
        "columnDefs": [ {
          "targets"  : [0],
          "orderable": false
        }],
        initComplete : function() {
            
            var input = $('.dataTables_filter input').unbind(),
            self = this.api(),
            $searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
            .html('<i class="material-icons">search</i>')
            .click(function() {
                
                if(!$('#search-employee').is(':disabled')){
                    $('#search-employee').attr('disabled',true);
                    self.search(input.val()).draw();
                    $('#datatables button').attr('disabled',true);
                    $('.dataTables_filter').append('<div id="search-loader"><br>' 
                        +'<div class="preloader pl-size-xs">'
                        +    '<div class="spinner-layer pl-red-grey">'
                        +        '<div class="circle-clipper left">'
                        +            '<div class="circle"></div>'
                        +        '</div>'
                        +        '<div class="circle-clipper right">'
                        +            '<div class="circle"></div>'
                        +        '</div>'
                        +    '</div>'
                        +'</div>'
                        +'&emsp;Please Wait..</div>');
                }

            })
            if ($("#search-employee").length === 0) {
                $('.dataTables_filter').append($searchButton);
            }
            
        },
        "drawCallback": function( settings ) {
            $('#search-loader').remove();
            $('#search-employee').removeAttr('disabled');
            $('#datatables button').removeAttr('disabled');
        },
        "ajax":{  
            url:commons.baseurl+ "usermanagement/MobileUserConfig/fetchRows"+plus_url,  
            type:"POST",
        },  
        oLanguage: {sProcessing: '<div class="preloader pl-size-sm">'
                                +'<div class="spinner-layer pl-red-grey">'
                                +    '<div class="circle-clipper left">'
                                +        '<div class="circle"></div>'
                                +    '</div>'
                                +    '<div class="circle-clipper right">'
                                +        '<div class="circle"></div>'
                                +    '</div>'
                                +'</div>'
                                +'</div>'}
    });
}

//function to reload table
function reloadTable(){       
    table.ajax.reload();
}

//function to load list of employees
function loadEmployees(location_id) {
    var temp = null;
    select = '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required><option value="">Loading...</option></select>'
    $('.employee_select').html(select);
    plus_url = '?LocationId=' + location_id;
    url = commons.baseurl + "employees/Employees/getActiveEmployees"+plus_url;
    return $.ajax({
        url: url,
        type:'POST',
        dataType:'JSON',
        success: function(res) {
            temp = res;
            select = '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required>'
            options = '<option value=""></option>'
            if(temp.Code == "0"){
                $.each(temp.Data.details, function(i,v){
                    options += '<option value="'+v.id+'">'+v.employee_id_number+' - '+v.last_name+', '+v.first_name+' '+v.middle_name+'</option>'
                })
            }
            select += options
            select += '</select>'
            $('.employee_select').html(select)
        }
    });
}