$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    var tbl_url = commons.baseurl+ "transactions/OtherBenefitEntries/fetchRows"
    // loadTable();
    $.when(
        getFields.month(),
        // getFields.location()
        getFields.division(),
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
        //$.AdminBSB.input.activate();
    })
    $(document).on('click','#printOtherBenefitEntries',function(e){
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

    
    var pay_basis;
    var division_id;
    var payroll_period_id;
    var employee_id;

    $(document).on('change','form #pay_basis',function(e){
        pay_basis = $(this).val();
        division_id = $('form #division_id').val();

        $('.search_entry #pay_basis').val(pay_basis).change();

        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('.payroll_period_id').val(payroll_period_id);
            $.AdminBSB.select.activate();     

            getEmployeeList(pay_basis,division_id);  
        })
    });

    $(document).on('change','form #division_id',function(e){
        division_id = $(this).val();
        pay_basis = $('form #pay_basis').val();
        getEmployeeList(pay_basis,division_id);

        $('.search_entry #division_id').val(division_id).change();
    });

    $(document).on('change','form #payroll_period_id',function(e){
        payroll_period_id = $(this).val();

        $('.search_entry #payroll_period_id').val(payroll_period_id).change();
    });
    

    $(document).on('change','.search_entry #pay_basis',function(e){
        pay_basis = $(this).val();

        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('.payroll_period_id').val(payroll_period_id);
            $.AdminBSB.select.activate();  
        })
    });
    
    function getEmployeeList(pay_basis,division_id) {
        if(division_id != "" && pay_basis != "") {
            $.when(
                getFields.employee(
                    {
                        division_id : division_id,
                        pay_basis : pay_basis
                    }
                )
            ).done(function(){
                $('.employee_id').val(employee_id).change();
                $.AdminBSB.select.activate();
            })
        }       
    }

    //Confirms
    $(document).on('click','.activateOtherBenefitEntries,.deactivateOtherBenefitEntries',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateOtherBenefitEntries')){
            content = 'Are you sure you want to activate selected Other Benefit?';
        }
        else if(me.hasClass('deactivateSubOtherBenefitEntries')){
            content = 'Are you sure you want to deactivate selected Other Benefit?';
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
                                                    case 'activateOtherBenefitEntries':
                                                    case 'deactivateOtherBenefitEntries':
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
    $(document).on('click','#searchEmployeeBenefit',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        employee_id = $('.search_entry #employee_id').val()
        division_id = $('.search_entry #division_id').val()
        payroll_period_id = $('.search_entry #payroll_period_id').val()
        pay_basis = $('.search_entry #pay_basis').val()
        // plus_url = '?EmployeeId='+employee_id
        plus_url = '?PayBasis='+pay_basis+'&DivisionId='+division_id+'&PayrollPeriodId='+payroll_period_id
        if(pay_basis == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Pay Basis.'
            });
        } else if(division_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Department.'
            });
        } else if(employee_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select an Employee.'
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
                            url:tbl_url+plus_url,  
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
    $(document).on('click','#addOtherBenefitEntriesForm,.updateOtherBenefitEntriesForm,.viewOtherBenefitEntriesForm,.viewOtherBenefitEntriesUpdates',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        // employee_id = $('.search_entry #employee_id').val()
        employee_id = my.data('employee_id')
        $('#myModal .modal-dialog').removeClass('full-screen')
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'addOtherBenefitEntries':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Other Benefit');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'viewOtherBenefitEntries':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Other Benefit Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateOtherBenefitEntries':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Other Benefit');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'viewOtherBenefitEntriesUpdates':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-dialog').addClass('full-screen');
                            $('#myModal .modal-title').html('Other Benefit History');
                            $('#myModal .modal-body').html(result.table);
                            loadUpdatesTable(id);
                            $('#myModal').modal('show');
                            break;
                    }
                    $.when(
                        getFields.otherBenefits(),
                        getFields.month(),
                        // getFields.location()
                        getFields.division(),
                        getFields.payBasis3()
                    ).done(function(){
                        $('.selectpicker').selectpicker("destroy");
                        $.each(my.data(),function(i,v){
                            $('.'+i).val(my.data(i)).change();
                        });

                        if(result.key =="viewOtherBenefitEntries"){
                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            cancelButton = $('#cancelUpdateForm').get(0).outerHTML;
                            $('#myModal .modal-body').append('<div class="text-right" style="width:100%;">'+cancelButton+'</div>')
                            $('form').find('#cancelUpdateForm').remove();
                            $('#cancelUpdateForm').removeAttr('disabled');
                            $('form').css('pointer-events','none');
                        }
                        if(result.key == "addOtherBenefitEntries"){
                            $('.search_entry #pay_basis').val(pay_basis).change();
                            $('.search_entry #division_id').val(division_id).change();
                            $('.search_entry #employee_id').val(employee_id).change();
                            $('#myModal #employee_id').val(employee_id).change();
                            //alert('heelooo')
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
    $(document).on('submit','#addOtherBenefitEntries,#updateOtherBenefitEntries',function(e){
        e.preventDefault();
        var form = $(this)
        benefit_id = $('#myModal #benefit_id').val()

        content = "Are you sure you want to proceed?";
        if(benefit_id == "" || benefit_id == "undefined"){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Type of Benefit.",
            });
            return false;
        }
        if(form.attr('id') == "addOtherBenefitEntries"){
            content = "Are you sure you want to add Other Benefit?";
        }
        if(form.attr('id') == "updateOtherBenefitEntries"){
            content = "Are you sure you want to update Other Benefit?";
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
                                                        case 'addOtherBenefitEntries':
                                                        case 'updateOtherBenefitEntries':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            // alert(employee_id)
                                                            // $('.search_entry .employee_id').val($('form #employee_id').val()).change();
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
    function loadTable(){
        //employment_status = $('#hide_emp_status').val();
        plus_url = ""
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
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
                "targets": [0],
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
                url:tbl_url+plus_url,  
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
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "transactions/OvertimeApplicationsUpdates/fetchRows"+plus_url,  
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
                url:commons.baseurl+ "transactions/OtherBenefitEntriesUpdates/fetchRows"+plus_url,   
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
        employee_id = $('.search_entry #employee_id').val()
        plus_url = '?EmployeeId='+employee_id
        if(employee_id == ""){
            return false;
        }
        $("#searchEmployeeBenefit").click();
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