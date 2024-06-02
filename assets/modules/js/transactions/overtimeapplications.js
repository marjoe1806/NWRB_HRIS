$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $.when(
        // getFields.location()
        getFields.payrollGrouping(),
        getFields.payBasis3()
    ).done(function(){
        $(".pay_basis option[value='Consultant']").remove();
        $(".pay_basis option[value='Congress']").remove();
        $('#pay_basis').selectpicker('refresh')
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
        //$.AdminBSB.input.activate();
    })
    $(document).on('click','#printOvertimeApplications',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('keypress keyup keydown','form #amount',function(e){
        $('form #balance').val($(this).val())
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
    // $(document).on('change','form #employee_id ',function(e){
    //     id = $(this).val();
    //     $('.search_entry #employee_id').val(id).change();
    // })
    var payroll_period_id;
    $(document).on('change','#pay_basis ',function(e){
        pay_basis = $(this).val();
        $('#permanent_ot').show();
        $('#other_ot').hide();
        if(pay_basis != "Permanent" && pay_basis != "Casual" && pay_basis != ""){
            $('#permanent_ot').hide();
            $('#other_ot').show(); 
        }
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('form #payroll_period_id').val(payroll_period_id)
            $.AdminBSB.select.activate();  
        })
    })
    $(document).on('change','form #employee_id',function(e){
        id = $(this).val();
        $('.employee_id').val(id);
        if(id == null){
            return false;
        }
        plus_url = '?Id=' + id;
        url = commons.baseurl + 'employees/Employees/getEmployeesById' + plus_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                if(result.Code == "0"){
                    pay_basis = $('#pay_basis').val();
                    salary = result.Data.details[0].salary;
                    position_name = result.Data.details[0].position_name
                    $('form #salary').val(addCommas(salary)).change();
                    day_rate = salary;
                    if(pay_basis == "Permanent" || pay_basis == "Casual"){
                        no_of_days = 22;
                        day_rate = (parseFloat(salary) / no_of_days).toFixed(2); 
                    }
                    hr_rate  = (day_rate / 8).toFixed(2);
                    min_rate = (hr_rate / 60).toFixed(2);
                    $('form #day_rate').val(addCommas(day_rate)).change();
                    $('form #hr_rate').val(addCommas(hr_rate)).change();
                    $('form #min_rate').val(addCommas(min_rate)).change();
                    $('form #position_name').val(position_name)
                    /*$.when(
                        getFields.payrollperiodcutoff(pay_basis)
                    ).done(function(){
                        $('form #payroll_period_id').val(payroll_period_id)
                        $.AdminBSB.select.activate();  
                    })*/
                }
                
            }
        });
    })
    
    $(document).on('click change keypress keyup keydown','#salary,#hrs_15,#mins_15,#hrs_125,#mins_125,#hrs_1,#mins_1,#hrs_1625,#mins_1625,#tax,input[name="ot_percent"]',function(e){
        pay_basis   = $('#pay_basis').val();
        salary      = $('#salary').val().replace(/,/g, '');
        day_rate    = $('form #day_rate').val().replace(/,/g, '');
        hr_rate     = $('form #hr_rate').val().replace(/,/g, '');
        min_rate    = $('form #min_rate').val().replace(/,/g, '');
        tax         = $('form #tax').val();
        //For Permanent and Casuals
        hrs_15      = $('form #hrs_15').val();
        mins_15     = $('form #mins_15').val();
        hrs_125     = $('form #hrs_125').val();
        mins_125    = $('form #mins_125').val();
        hr_15_amt   = hr_rate * hrs_15 * 1.5;
        min_15_amt  = min_rate * mins_15 * 1.5;
        hr_125_amt  = hr_rate * hrs_125 * 1.25;
        min_125_amt = min_rate * mins_125 * 1.25;
        //For Job Orders
        hrs_1      = $('form #hrs_1').val();
        mins_1     = $('form #mins_1').val();
        hrs_1625     = $('form #hrs_1625').val();
        mins_1625    = $('form #mins_1625').val();
        hr_1_amt   = hr_rate * hrs_1 * 1;
        min_1_amt  = min_rate * mins_1 * 1;
        hr_1625_amt  = hr_rate * hrs_1625 * 1.625;
        min_1625_amt = min_rate * mins_1625 * 1.625;
        if(pay_basis == "Permanent" || pay_basis == "Casual"){
            period_earned = (hr_15_amt + min_15_amt + hr_125_amt + min_125_amt).toFixed(2)   
        }
        else{
            period_earned = (hr_1_amt + min_1_amt + hr_1625_amt + min_1625_amt).toFixed(2)
        }
        ot_percent = $('input[name=ot_percent]:checked').val();
        ot_percent = $('input[name=ot_percent]:checked').val();
        //Period Earned
        if(ot_percent != 100){
            available_ot = salary * (ot_percent/100);
            if(period_earned > available_ot){
                period_earned = available_ot.toFixed(2);
            }  
        }
        $('#period_earned').val(addCommas(period_earned));
        tax_amt = (period_earned * (tax/100)).toFixed(2);
        $('#tax_amt').val(tax_amt);
        //Net Pay
        net_pay = (period_earned - tax_amt).toFixed(2)
        $('#net_pay').val(addCommas(net_pay));
    })
    var employee_id = null;
    // $(document).on('change','#division_id',function(e){
    //     location_id = $('#location_id').val();
    //     division_id = $('#division_id').val();
    //     $('.division_id').val(division_id)
    //     $.when(
    //         getFields.employee({location_id:location_id,division_id:division_id})
    //     ).done(function(){
    //         // alert(employee_id)
    //         $('.employee_id').val(employee_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })
    // var division_id = null;
    // $(document).on('change','#location_id',function(e){
    //     $('.location_id').val($(this).val());
    //     $.when(
    //         getFields.division()
    //     ).done(function(){
    //         /*$('#division_id option:first').text("All");
    //         $('#division_id option:first').val("");*/
    //         console.log(division_id)
    //         if(division_id != null)
    //             $('#division_id').val(division_id).change();
    //         $.AdminBSB.select.activate();
    //     })
    // })

    $(document).on('change','.payroll_grouping_id,.pay_basis',function(e){
        $('.selectpicker').selectpicker('destroy');
        payroll_grouping_id = $('#payroll_grouping_id').val();
        pay_basis = $('#pay_basis').val();
        if(payroll_grouping_id != "" && pay_basis != "") {
            $.when(
                getFields.employee(
                    {
                        payroll_grouping_id : payroll_grouping_id,
                        pay_basis : pay_basis
                    }
                )
            ).done(function(){
                // alert(employee_id)
                
                $('.employee_id').val(employee_id).change();
                $.AdminBSB.select.activate();
            })
        }       
    })

    //Confirms
    $(document).on('click','.activateOvertimeApplications,.deactivateOvertimeApplications',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateOvertimeApplications')){
            content = 'Are you sure you want to activate selected Overtime?';
        }
        else if(me.hasClass('deactivateSubOvertimeApplications')){
            content = 'Are you sure you want to deactivate selected Overtime?';
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
                                                    case 'activateOvertimeApplications':
                                                    case 'deactivateOvertimeApplications':
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
    $(document).on('click','#searchEmployeeOvertime',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        employee_id = $('.search_entry #employee_id').val()
        payroll_grouping_id = $('.search_entry #payroll_grouping_id').val()
        payroll_period_id = $('.search_entry #payroll_period_id').val()
        pay_basis = $('.search_entry #pay_basis').val()
        plus_url = '?pay_basis='+pay_basis+'&payroll_grouping_id='+payroll_grouping_id+'&payroll_period_id='+payroll_period_id
        if(pay_basis == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Pay Basis.'
            });
        } else if(payroll_grouping_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Payroll Grouping.'
            });
        } 
        else if(payroll_period_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Payroll Period.'
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
                            url:commons.baseurl+ "transactions/OvertimeApplications/fetchRows"+plus_url,  
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
    $(document).on('click','#addOvertimeApplicationsForm,.updateOvertimeApplicationsForm,.viewOvertimeApplicationsForm,.viewSubOvertimeApplicationsForm,.viewOvertimeApplicationsUpdates',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        employee_id = $('.search_entry #employee_id').val()
        payroll_grouping_id = $('.search_entry #payroll_grouping_id').val()
        pay_basis = $('.search_entry #pay_basis').val()
        year = $('.search_entry #search_year').val()
        month = $('.search_entry #month').val()
        employee_id = my.data('employee_id')
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
                        case 'addOvertimeApplications':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Overtime');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'viewOvertimeApplications':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Overtime Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateOvertimeApplications':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Overtime');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            
                            break;
                        case 'viewOvertimeApplicationsUpdates':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-dialog').addClass('full-screen');
                            $('#myModal .modal-title').html('Overtime History');
                            $('#myModal .modal-body').html(result.table);
                            loadUpdatesTable(id);
                            $('#myModal').modal('show');
                            
                            break;
                    }
                    $.when(
                        // getFields.location(),
                        getFields.payrollGrouping(),
                        getFields.payBasis3(),
                        getFields.otType()
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
                        if(result.key =="viewOvertimeApplications"){

                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            cancelButton = $('#cancelUpdateForm').get(0).outerHTML;
                            $('#myModal .modal-body').append('<div class="text-right" style="width:100%;">'+cancelButton+'</div>')
                            $('form').find('#cancelUpdateForm').remove();
                            $('#cancelUpdateForm').removeAttr('disabled');
                            $('form').css('pointer-events','none');
                        }
                        if(result.key == "addOvertimeApplications"){
                            $('.search_entry #pay_basis').val(pay_basis).change();
                            $('.search_entry #payroll_grouping_id').val(payroll_grouping_id).change();
                            $('.search_entry #employee_id').val(employee_id).change();
                            $('#myModal #employee_id').val(employee_id).change();
                            $('#radio_100').click();
                            //alert('heelooo')
                        }
                        payroll_period_id = my.data('payroll_period_id');
                        $(".pay_basis option[value='Consultant']").remove();
                        $(".pay_basis option[value='Congress']").remove();
                        $('#pay_basis').selectpicker('refresh')
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
    $(document).on('submit','#addOvertimeApplications,#updateOvertimeApplications',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addOvertimeApplications"){
            content = "Are you sure you want to add Overtime?";
        }
        if(form.attr('id') == "updateOvertimeApplications"){
            content = "Are you sure you want to update Overtime?";
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
                                                        case 'addOvertimeApplications':
                                                        case 'updateOvertimeApplications':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            $('#searchEmployeeOvertime').click();
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
                url:commons.baseurl+ "transactions/OvertimeApplications/fetchRows"+plus_url,  
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
        plus_url = "?ref_id="+id;
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
                url:commons.baseurl+ "transactions/OvertimeApplicationsUpdates/fetchRows"+plus_url,   
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
        $("#searchEmployeeOvertime").click();
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