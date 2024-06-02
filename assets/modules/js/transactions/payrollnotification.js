$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $.when(
        getFields.payBasis3()
    ).done(function(){
        $.AdminBSB.select.activate();  
    })
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
        $('.daystimepicker').inputmask('h:s',{placeholder:'hh:mm'});
        //$.AdminBSB.input.activate();
    })
    $(document).on('click','#printPayrollNotification',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('change','#leave_grouping_id,#pay_basis',function(e){
        leave_grouping_id = $('#leave_grouping_id').val();
        pay_basis = $('#pay_basis').val();
        if(leave_grouping_id != "" && pay_basis != ""){
            $.ajax({
                type: "GET",
                url: commons.baseurl + "transactions/PayrollNotification/fetchEmployees",
                data: {leave_grouping_id:leave_grouping_id,pay_basis:pay_basis},
                dataType: "json",
                success: function(result){
                    if(result.Code == "0"){
                        if(result.hasOwnProperty("key")){
                            switch(result.key){
                                case 'fetchEmployees':
                                    list = '<ol>';
                                    $.each(result.Data.details,function(i,v){
                                        list+= '<li>'+v.last_name+', '+v.first_name+v.middle_name+'</li>'
                                    })
                                    list+= '<ol>';
                                    $('#employee-container').html(list);
                                    break;
                            }
                        }  
                    }
                    else{
                        no_employees = '<div class="alert alert-danger">'
                            +'No employees available. Please select leave grouping.'
                        +'</div>'
                        $('#employee-container').html(no_employees)
                    } 
                },
                error: function(result){
                    self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                    self.setTitle('<label class="text-danger">Failed</label>');
                }
            });
        }
    })
    $(document).on('change','#is_active',function(e){
        status = $(this).val();
        if(status == 1){
            $('.status_icon').removeClass("text-danger");
            $('.status_icon').addClass("text-success");
            $('.status_icon i').html("check_circle")
        }
        else{
            $('.status_icon').removeClass("text-success");
            $('.status_icon').addClass("text-danger");
            $('.status_icon i').html("remove_circle")
        }
    })
    $(document).on('change','form #pay_basis',function(e){
        id = $(this).val();
        $('.search_entry #pay_basis').val(id).change();
    })
    
    $(document).on('change','form #payroll_grouping_id',function(e){
        id = $(this).val();
        $('.search_entry #payroll_grouping_id').val(id).change();
    })
    var payroll_period_id;
    $(document).on('change','#pay_basis ',function(e){
        pay_basis = $(this).val();
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('form #payroll_period_id').val(payroll_period_id)
            $.AdminBSB.select.activate();  
        })
    })
    //Confirms
    $(document).on('click','.activatePayrollNotification,.deactivatePayrollNotification',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activatePayrollNotification')){
            content = 'Are you sure you want to activate selected Payroll Notification?';
        }
        else if(me.hasClass('deactivateSubPayrollNotification')){
            content = 'Are you sure you want to deactivate selected Payroll Notification?';
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
                                                    case 'activatePayrollNotification':
                                                    case 'deactivatePayrollNotification':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        reloadTable();
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
    $(document).on('click','#searchEmployeePayrollNotification',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        leave_grouping_id = $('.search_entry #leave_grouping_id').val()
        payroll_period_id = $('.search_entry #payroll_period_id').val()
        pay_basis = $('.search_entry #pay_basis').val()
        plus_url = '?pay_basis='+pay_basis+'&payroll_period_id='+payroll_period_id
        if(pay_basis == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Pay Basis.'
            });
        }
        else if(payroll_period_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Payroll Period.'
            });
        } 
        // else if(employee_id == ""){
        //     $.alert({
        //         title:'<label class="text-danger">Failed</label>',
        //         content:'Please select an Employee.'
        //     });
        // }
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
                        "responsive":true,
                        "order":[],
                        scroller: {
                            displayBuffer: 20
                        },
                        "columnDefs": [ {
                          "targets"  : [0],
                          "orderable": true
                        }],
                        initComplete : function() {
                            $('#search-table').remove();
                            var input = $('.dataTables_filter input').unbind(),
                            self = this.api(),
                            $searchButton = $('<button id="search-table" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
                            .html('<i class="material-icons">search</i>')
                            .click(function() {
                                
                                if(!$('#search-table').is(':disabled')){
                                    $('#search-table').attr('disabled',true);
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
                            $('.dataTables_filter').append($searchButton);
                            
                        },
                        "drawCallback": function( settings ) {
                            $('#search-loader').remove();
                            $('#search-table').removeAttr('disabled');
                            $('#datatables button').removeAttr('disabled');
                        },
                        "ajax":{  
                            url:commons.baseurl+ "transactions/PayrollNotification/fetchRows"+plus_url,  
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
    //Ajax non-forms
    $(document).on('click','#addPayrollNotificationForm,.updatePayrollNotificationForm,.viewPayrollNotificationForm,.viewSubPayrollNotificationForm,.viewPayrollNotificationUpdates',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        employee_id = $('.search_entry #employee_id').val()
        payroll_grouping_id = $('.search_entry #payroll_grouping_id').val()
        pay_basis = $('.search_entry #pay_basis').val()
        year = $('.search_entry #search_year').val()
        month = $('.search_entry #month').val()
        sub_loan_id = my.data('sub_loans_id')
        employee_id = my.data('employee_id')
        division_id = my.data('division_id')
        $('#myModal .modal-dialog').removeClass('full-screen')
        //console.log(me.data())
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'addPayrollNotification':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Payroll Notification');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'viewPayrollNotification':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Payroll Notification Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updatePayrollNotification':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Payroll Notification');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            
                            break;
                        case 'viewPayrollNotificationUpdates':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-dialog').addClass('full-screen');
                            $('#myModal .modal-title').html('Payroll Notification History');
                            $('#myModal .modal-body').html(result.table);
                            loadUpdatesTable(id);
                            $('#myModal').modal('show');
                            
                            break;
                    }
                    $.when(
                        // getFields.location(),
                        getFields.leaveGrouping(),
                        getFields.payBasis3()
                    ).done(function(){
                        $('.selectpicker').selectpicker("destroy");
                        // console.log(division_id)
                        // console.log(my.data());
                        $.each(my.data(),function(i,v){
                            if(i == "is_approved")
                                $('.is_approved'+v).click();
                            else{
                                if(i != "division_id" || i != "employee_id")
                                    $('.'+i).val(my.data(i)).change(); 
                                if(i == "ot_percent")
                                    $("input[name='"+i+"'][value='"+my.data(i)+"']").prop('checked', true);
                            }
                        });
                        // $('#location_id').val(my.data('location_id')).change();
                        employee_id = my.data('employee_id')
                        division_id = my.data('division_id')
                        if(result.key =="viewPayrollNotification"){

                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            cancelButton = $('#cancelUpdateForm').get(0).outerHTML;
                            $('#myModal .modal-body').append('<div class="text-right" style="width:100%;">'+cancelButton+'</div>')
                            $('form').find('#cancelUpdateForm').remove();
                            $('#cancelUpdateForm').removeAttr('disabled');
                            $('form').css('pointer-events','none');
                        }
                        if(result.key == "addPayrollNotification"){
                            $('.search_entry #pay_basis').val(pay_basis).change();
                        }
                        payroll_period_id = my.data('payroll_period_id');
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
    $(document).on('submit','#addPayrollNotification,#updatePayrollNotification',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addPayrollNotification"){
            content = "Are you sure you want to add Payroll Notification?";
        }
        if(form.attr('id') == "updatePayrollNotification"){
            content = "Are you sure you want to update Payroll Notification?";
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
                                                        case 'addPayrollNotification':
                                                        case 'updatePayrollNotification':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            $('#searchEmployeePayrollNotification').click();
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
    function loadTable(){
        //employment_status = $('#hide_emp_status').val();
        plus_url = ""
        table = $('#datatables').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "stateSave": true, // presumably saves state for reloads -- entries
            "bStateSave": true, // presumably saves state for reloads -- page number
            "responsive":true,  
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
              "targets"  : [0],
              "orderable": true
            }],
            initComplete : function() {
                $('#search-table').remove();
                var input = $('.dataTables_filter input').unbind(),
                self = this.api(),
                $searchButton = $('<button id="search-table" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
                .html('<i class="material-icons">search</i>')
                .click(function() {
                    
                    if(!$('#search-table').is(':disabled')){
                        $('#search-table').attr('disabled',true);
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
                if	($("#search-table").length === 0) {
                  $('.dataTables_filter').append($searchButton);
                }
                
            },
            "drawCallback": function( settings ) {
                $('#search-loader').remove();
                $('#search-table').removeAttr('disabled');
                $('#datatables button').removeAttr('disabled');
            },
            "ajax":{  
                url:commons.baseurl+ "transactions/PayrollNotification/fetchRows"+plus_url,  
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
    function loadUpdatesTable(id){
        //employment_status = $('#hide_emp_status').val();
        plus_url = "?ref_id="+id;
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
        /*table = $('#datatables-updates').DataTable({  
               "processing":true,  
               "serverSide":true, 
               "responsive":true, 
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "transactions/PayrollNotificationUpdates/fetchRows"+plus_url,  
                    type:"POST"  
               },  
               "columnDefs":[  
                    {  
                         "orderable":false,  
                    },  
               ],  
        });*/
        table = $('#datatables-updates').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "responsive":true,  
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
              "targets"  : [0],
              "orderable": true
            }],
            initComplete : function() {
                $('#search-tableupdates').remove();
                var input = $('.dataTables_filter input').unbind(),
                self = this.api(),
                $searchButton = $('<button id="search-tableupdates" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
                .html('<i class="material-icons">search</i>')
                .click(function() {
                    
                    if(!$('#search-tableupdates').is(':disabled')){
                        $('#search-tableupdates').attr('disabled',true);
                        self.search(input.val()).draw();
                        $('#datatables-updates_wrapper button').attr('disabled',true);
                        $('#datatables-updates_wrapper .dataTables_filter').append('<div id="search-loader"><br>' 
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
                $('#datatables-updates_wrapper .dataTables_filter').append($searchButton);
                
            },
            "drawCallback": function( settings ) {
                $('#search-loader').remove();
                $('#search-tableupdates').removeAttr('disabled');
                $('#datatables-updates button').removeAttr('disabled');
            },
            "ajax":{  
                url:commons.baseurl+ "transactions/PayrollNotificationUpdates/fetchRows"+plus_url,   
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
    function reloadTable(){
        //table.ajax.reload();
        $("#searchEmployeePayrollNotification").click();
    }
    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
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