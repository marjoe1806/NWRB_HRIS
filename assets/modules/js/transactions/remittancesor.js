$(function(){
    var page = "";
    base_url = commons.base_url;
    $.when(
        getFields.payBasis3()
    ).done(function(){
        $.AdminBSB.select.activate();  
    })
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        // maxDate: new Date(),
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            // maxDate: new Date(),
            time: false
        });
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    
    $(document).on('change','#pay_basis',function(e){
        pay_basis = $(this).val();
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $('.payroll_period_id').val(payroll_period_id);
            $("#payroll_period_id option:first").text("All");
            $.AdminBSB.select.activate();  
        })
    });

    // $(document).on('change','form #pay_basis',function(e){
    //     pay_basis = $(this).val();
    //     $('.search_entry #pay_basis').val(pay_basis).change();
    //     $.when(
    //         getFields.payrollperiodcutoff(pay_basis)
    //     ).done(function(){
    //         $('.payroll_period_id').val(payroll_period_id);
    //         $.AdminBSB.select.activate();
    //     })
    // });


    $(document).on('click','#printRemittancesOR',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    //Confirms
    $(document).on('click','.activateRemittancesOR,.deactivateRemittancesOR',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateRemittancesOR')){
            content = 'Are you sure you want to activate selected official receipt?';
        }
        else if(me.hasClass('deactivateSubRemittancesOR')){
            content = 'Are you sure you want to deactivate selected official receipt?';
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
                                                    case 'activateRemittancesOR':
                                                    case 'deactivateRemittancesOR':
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
    $(document).on('click','#addRemittancesORForm,.updateRemittancesORForm',function(e){
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
                        case 'addRemittancesOR':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Official Receipt');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        // case 'generateRemittancesOR':
                        //     page="";
                        //     $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                        //     $('#myModal .modal-title').html('Employee Department');
                        //     $('#myModal .modal-body').html(result.form);
                        //     $('#myModal').modal('show');
                        //     break;
                        case 'updateRemittancesOR':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Official Receipt');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(me.data(i)).change();
                            });
                            break;
                    }
                    $.when(
                        getFields.loan(),
                        // getFields.month(),
                        getFields.payBasis3()
                    ).done(function(){
                        $('#loans_id').append(new Option('PhilHealth', '3'));
                        $("#loans_id option[value='4']").remove();
                        $("#loans_id option[value='5']").remove();
                        // $("#loans_id option[value='5']").remove();
                        $('.selectpicker').selectpicker("destroy");
                        $.each(me.data(),function(i,v){
                            $('.'+i).val(me.data(i)).change();
                        });

                        if(result.key =="viewRemittancesOR"){
                            $('form').find('input, textarea, button, select').attr('disabled','disabled');
                            cancelButton = $('#cancelUpdateForm').get(0).outerHTML;
                            $('#myModal .modal-body').append('<div class="text-right" style="width:100%;">'+cancelButton+'</div>')
                            $('form').find('#cancelUpdateForm').remove();
                            $('#cancelUpdateForm').removeAttr('disabled');
                            $('form').css('pointer-events','none');
                        }
                        if(result.key == "addRemittancesOR"){
                            $('#pay_basis').val(pay_basis).change();
                        }
                        sub_loans_id = me.data('sub_loans_id');
                        payroll_period_id = me.data('payroll_period_id');
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
    $(document).on('submit','#addRemittancesOR,#updateRemittancesOR',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addRemittancesOR"){
            content = "Are you sure you want to add official receipt?";
        }
        if(form.attr('id') == "updateRemittancesOR"){
            content = "Are you sure you want to update official receipt?";
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
                                                        case 'addRemittancesOR':
                                                        case 'updateRemittancesOR':
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

    $(document).on("click", "#btnsearch", function(){
        loadTable();
    });

    function loadTable(){
        if($("#pay_basis").val()==""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Pay Basis.'
            });
        }else if($("#payroll_period_id").val()==null){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a Period.'
            });
        }else{
            $("#datatables").DataTable().clear().destroy();
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
                    if	($("#search-employee").length === 0) {
                        $('.dataTables_filter').append($searchButton);
                    }
                    
                },
                "drawCallback": function( settings ) {
                    $('#search-loader').remove();
                    $('#search-employee').removeAttr('disabled');
                    $('#datatables button').removeAttr('disabled');
                },
                "ajax":{  
                    url:commons.baseurl+ "transactions/RemittancesOR/fetchRows?status="+$("#status").val()+"&pay_basis="+$("#pay_basis").val()+"&payroll_period_id="+$("#payroll_period_id").val(),
                    type:"GET",
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
    }
    $(document).on("change", "#loans_id", function () {
		id = $(this).val();
		pay_basis = $("form #pay_basis").val();
		$.when(getFields.subloan(id)).done(function () {
			$("#sub_loans_id").val(sub_loans_id).change();
            if(id == "3"){
                $(".sub_loan").css("display", "none");
            }
            if(id == "1"){
                $(".sub_loan").css("display", "block");
                $("#sub_loans_id option[value='3']").remove();
                $("#sub_loans_id option[value='4']").remove();
                $("#sub_loans_id option[value='5']").remove();
                $("#sub_loans_id option[value='6']").remove();
                $("#sub_loans_id option[value='7']").remove();
                $("#sub_loans_id option[value='8']").remove();
                $("#sub_loans_id option[value='14']").remove();
                $("#sub_loans_id option[value='15']").remove();
                $("#sub_loans_id option[value='16']").remove();
                $("#sub_loans_id option[value='17']").remove();
                $("#sub_loans_id option[value='18']").remove();
                $("#sub_loans_id option[value='35']").remove();
                $("#sub_loans_id option[value='36']").remove();
            }
            if(id == "2"){
                $(".sub_loan").css("display", "block");
                $("#sub_loans_id option[value='34']").remove();
                $("#sub_loans_id option[value='37']").remove();
                $("#sub_loans_id option[value='20']").remove();
                $("#sub_loans_id option[value='22']").remove();
                $('#sub_loans_id').append(new Option('MP2 Contribution', '0'));
            }
			$.AdminBSB.select.activate();
		});
	});
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