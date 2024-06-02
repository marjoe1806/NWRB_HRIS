$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
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
        
        var startDate = '1/31/'+ new Date().getFullYear();
        var endDate = '12/31/'+ new Date().getFullYear();
        $(".daterangepicker").daterangepicker({
            format: "mm/dd/yyyy",
            startDate: startDate,
            endDate: endDate
        })
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    $(document).on('keyup','.currency',function(e){
        val = $(this).val().replace(/,/g, '')
        console.log(val)
        $(this).val(addCommas(val));
    })
    $(document).on('click','#addNewTaxRow',function(e){
        e.preventDefault()
        first_row = $('#formTable').find('.first_row').html();
        console.log(first_row)
        index = $('#formTable tbody tr').length + 1;
        $('#formTable tbody').append('<tr class="row_'+index+'">'+first_row+'</tr>');
        $('.row_'+index).find('#removeTaxRow').css('visibility','visible');
        $('.row_'+index).find('.compensation_level_from').attr('name','compensation_level_from['+index+']');
        $('.row_'+index).find('.compensation_level_to').attr('name','compensation_level_to['+index+']');
        $('.row_'+index).find('.tax_percentage').attr('name','tax_percentage['+index+']');
        $('.row_'+index).find('.tax_additional').attr('name','tax_additional['+index+']');
    })
    $(document).on('click','.removeTaxRow',function(e){
        e.preventDefault()
        $(this).closest('tr').remove();
    })
    //Confirms
    $(document).on('click','.activateWithHoldingTaxes,.deactivateWithHoldingTaxes',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateWithHoldingTaxes')){
            content = 'Are you sure you want to activate selected withholding tax?';
        }
        else if(me.hasClass('deactivateSubWithHoldingTaxes')){
            content = 'Are you sure you want to deactivate selected sub withholding tax?';
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
                                                    case 'activateWithHoldingTaxes':
                                                    case 'deactivateWithHoldingTaxes':
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
    $(document).on('click','#addWithHoldingTaxesForm,.updateWithHoldingTaxesForm',function(e){
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
                        case 'addWithHoldingTaxes':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Revised Witholding Tax Table');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'generateWithHoldingTaxes':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Revised Witholding Tax Table');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateWithHoldingTaxes':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Revised Witholding Tax Table');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(v);
                            })
                            
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
    $(document).on('submit','#addWithHoldingTaxes,#updateWithHoldingTaxes',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addWithHoldingTaxes"){
            content = "Are you sure you want to add withholding tax?";
        }
        if(form.attr('id') == "updateWithHoldingTaxes"){
            content = "Are you sure you want to update withholding tax?";
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
                                                        case 'addWithHoldingTaxes':
                                                        case 'updateWithHoldingTaxes':
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
            $("#table-holder").html(result.table);
        }});
    }
    function addCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
})