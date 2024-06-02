$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    var tbl_url = commons.baseurl+ "transactions/OtherDeductionEntries/fetchRows";
    // loadTable();
    $.when(
        getFields.month(),
        getFields.division(),
        getFields.payBasis3()
        // getFields.location()
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
    $(document).on('click','#printOtherDeductionEntries',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('keypress keyup keydown','form #amount',function(e){
        $('form #balance').val($(this).val())
        $('form #amortization_per_month').val($(this).val())
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
    $(document).on('change','form #pay_basis ',function(e){
        id = $(this).val();
        $('.search_entry #pay_basis').val(id).change();
    })
    $(document).on('change','form #division_id ',function(e){
        id = $(this).val();
        $('.search_entry #division_id').val(id).change();
    })
    $(document).on('change','form #payroll_period_id ',function(e){ 
        id = $(this).val();
        $('.search_entry #payroll_period_id').val(id).change();
    })
    var payroll_period_id;
    $(document).on('change','#pay_basis ',function(e){
        pay_basis = $(this).val();
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('.payroll_period_id').val(payroll_period_id).change();
            $.AdminBSB.select.activate();  
        })
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

    // $(document).on('change','form .division_id,form .pay_basis',function(e){
    //     division_id = $('form #division_id').val();
    //     pay_basis = $('form #pay_basis').val();
    //     if(division_id != "" && pay_basis != "") {
    //         $.when(
    //             getFields.employee(
    //                 {
    //                     division_id : division_id,
    //                     pay_basis : pay_basis
    //                 }
    //             )
    //         ).done(function(){
    //             // alert(employee_id)
                
    //             $('.employee_id').val(employee_id).change();
    //             $.AdminBSB.select.activate();
    //         })
    //     }       
    // })

    $(document).on('change','.division_id,.pay_basis',function(e){
        division_id = $('#division_id').val();
        pay_basis = $('#pay_basis').val();
        if(division_id != "" && pay_basis != "") {
            $.when(
                getFields.employee(
                    {
                        division_id : division_id,
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
    $(document).on('click','.activateOtherDeductionEntries,.deactivateOtherDeductionEntries',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateOtherDeductionEntries')){
            content = 'Are you sure you want to activate selected Other Deduction?';
        }
        else if(me.hasClass('deactivateSubOtherDeductionEntries')){
            content = 'Are you sure you want to deactivate selected sub Other Deduction?';
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
                                                    case 'activateOtherDeductionEntries':
                                                    case 'deactivateOtherDeductionEntries':
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
    $(document).on('click','#searchEmployeeDeduction',function(e){
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
        } else if(payroll_period_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select an Payroll Period.'
            });
        }
        else{
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function(result){
                    $('#table-holder').html(result.table);
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
    $(document).on('click','#addOtherDeductionEntriesForm,.updateOtherDeductionEntriesForm,.viewOtherDeductionEntriesForm,.viewOtherDeductionEntriesUpdates',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        // employee_id = $('.search_entry #employee_id').val()
        year = $('.search_entry #search_year').val()
        month = $('.search_entry #month').val()

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
                        case 'addOtherDeductionEntries':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Other Deduction');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'viewOtherDeductionEntries':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Other Deduction Details');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateOtherDeductionEntries':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Other Deduction');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            
                            break;
                        case 'viewOtherDeductionEntriesUpdates':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-dialog').addClass('full-screen');
                            $('#myModal .modal-title').html('Other Deduction History');
                            $('#myModal .modal-body').html(result.table);
                            loadUpdatesTable(id);
                            $('#myModal').modal('show');
                            
                            break;
                    }
                    $.when(
                        getFields.otherDeductions(),
                        getFields.month(),
                        getFields.division(),
                        getFields.payBasis3()
                    ).done(function(){
                        $('.selectpicker').selectpicker("destroy");
                        $.each(my.data(),function(i,v){
                            $('.'+i).val(my.data(i)).change();
                        });
                        if(result.key =="viewOtherDeductionEntries"){
                            // $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            // $('form').find('#cancelUpdateForm').removeAttr('disabled');

                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            cancelButton = $('#cancelUpdateForm').get(0).outerHTML;
                            $('#myModal .modal-body').append('<div class="text-right" style="width:100%;">'+cancelButton+'</div>')
                            $('form').find('#cancelUpdateForm').remove();
                            $('#cancelUpdateForm').removeAttr('disabled');
                            $('form').css('pointer-events','none');
                        }
                        if(result.key == "addOtherDeductionEntries"){
                            $('.search_entry #pay_basis').val(pay_basis).change();
                            $('.search_entry #division_id').val(division_id).change();
                            $('.search_entry #employee_id').val(employee_id).change();
                            $('#myModal #employee_id').val(employee_id).change();
                            //alert('heelooo')
                        }
                        employee_id = employee_id
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
    $(document).on('submit','#addOtherDeductionEntries,#updateOtherDeductionEntries',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        division_id = $("#myModal #division_id").val();
        deduction_id = $("#myModal #deduction_id").val();

        if(division_id == "" || division_id == "undefined"){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Division.",
            });
            return false;
        } else if (deduction_id == "" || deduction_id == "undefined") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Please select Type of Deduction.",
            });
            return false;
        }
        if(form.attr('id') == "addOtherDeductionEntries"){
            content = "Are you sure you want to add Other Deduction?";
        }
        if(form.attr('id') == "updateOtherDeductionEntries"){
            content = "Are you sure you want to update Other Deduction?";
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
                                                        case 'addOtherDeductionEntries':
                                                        case 'updateOtherDeductionEntries':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            //$('.search_entry #employee_id').val($('#myModal #employee_id').val());
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
               "responsive":true, 
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
            "responsive":true,  
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
              "targets"  : [-1],
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
                url:commons.baseurl+ "transactions/OtherDeductionEntriesUpdates/fetchRows"+plus_url,   
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
        $("#searchEmployeeDeduction").click();
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