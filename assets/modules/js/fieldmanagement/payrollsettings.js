$(function(){
    var page = "";
    base_url = commons.base_url;
    var table_gsis;
    var table_philhealth;
    loadPayrollSetup();
    table_gsis = $('#datatables-deductionoptions').DataTable({
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
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    $('.timepicker').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        clearButton: true,
        date: false,
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            maxDate: new Date(),
            time: false
        });
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    $("#addPayrollSettingsPayrollSetup").validate({
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
    $(document).on('click','#printPayrollSettingsPayrollSetup',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    //Confirms
    $(document).on('click','.activatePayrollSettingsPayrollSetup,.deactivatePayrollSettingsPayrollSetup,.activatePayrollSettingsDeductionOptions,.deactivatePayrollSettingsDeductionOptions',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activatePayrollSettingsPayrollSetup')){
            content = 'Are you sure you want to activate selected payroll setup?';
        }
        else if(me.hasClass('deactivateSubPayrollSettingsPayrollSetup')){
            content = 'Are you sure you want to deactivate selected payroll setup?';
        }
        else if(me.hasClass('activatePayrollSettingsDeductionOptions')){
            content = 'Are you sure you want to deactivate selected deduction option?';
        }
        else if(me.hasClass('deactivateSubPayrollSettingsDeductionOptions')){
            content = 'Are you sure you want to deactivate selected deduction option?';
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
                                                    case 'activatePayrollSettingsDeductionOptions':
                                                    case 'deactivatePayrollSettingsDeductionOptions':
                                                    case 'activatePayrollSettingsPayrollSetup':
                                                    case 'deactivatePayrollSettingsPayrollSetup':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        loadTable();
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
    $(document).on('click','#addPayrollSettingsPayrollSetupForm,.updatePayrollSettingsPayrollSetupForm,#addPayrollSettingsDeductionOptionsForm,.updatePayrollSettingsDeductionOptionsForm',function(e){
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
                        case 'addPayrollSettingsPayrollSetup':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New GSIS Contribution');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updatePayrollSettingsPayrollSetup':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update GSIS Contribution');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(me.data(i)).change();
                            });
                            break;
                        case 'addPayrollSettingsDeductionOptions':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New PhilHealth Contribution');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updatePayrollSettingsDeductionOptions':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update PhilHealth Contribution');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(me.data(i)).change();
                            });
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
    //Ajax Forms
    $(document).on('submit','#addPayrollSettingsPayrollSetup,#updatePayrollSettingsPayrollSetup,#addPayrollSettingsDeductionOptions,#updatePayrollSettingsDeductionOptions',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addPayrollSettingsPayrollSetup"){
            content = "Are you sure you want to update payroll setup?";
        }
        if(form.attr('id') == "updatePayrollSettingsPayrollSetup"){
            content = "Are you sure you want to update payroll setup?";
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
                                                        case 'addPayrollSettingsDeductionOptions':
                                                        case 'updatePayrollSettingsDeductionOptions':
                                                        case 'addPayrollSettingsPayrollSetup':
                                                        case 'updatePayrollSettingsPayrollSetup':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            loadTable();
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
        var url = window.location.href;
        $.ajax({url: url,dataType:"json", success: function(result){
            $("#table-holder-deductionoptions").html(result.table_deduction_options);
            table_philhealth = $('#datatables-deductionoptions').DataTable({
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
            loadPayrollSetup();
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
    function loadPayrollSetup(){
        url = commons.baseurl + 'fieldmanagement/PayrollSettings/loadPayrollSetup'; 
        $.ajax({url: url,dataType:"json", success: function(result){
            console.log(result);
            if(result.Code == "0"){
                $.each(result.Data.details[0],function(i,v){
                    if(i != "overtime")
                        $('.'+i).val(v).change();
                    else if(i == "overtime")
                        $('.'+v).click();
                })
            }
        }}) 
    }
})