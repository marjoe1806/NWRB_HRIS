$(function(){
    var page = "";
    base_url = commons.baseurl;
    var table;
    var emp_id = "";
    // loadTable();
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    var payroll_grouping_id = null;
    $(document).on('show.bs.modal','#myModal', function () {
        $.when(
            getFields.division(),
        ).done(function(){
            emp_id = "";
            $.AdminBSB.select.activate();
        });
    });

    $(document).on('change','#division_id',function(){
        getEmployeeList($(this).val());
    });

    $(document).on('change','.file',function(e){
        readURL(this);
    })

    $(document).on('click','#printSignatories',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    //Confirms
    $(document).on('click','.activateSignatories,.deactivateSignatories,.activateHeadSignatories,.deactivateHeadSignatories',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateSignatories')){
            content = 'Are you sure you want to activate selected signatory?';
        }
        else if(me.hasClass('deactivateSubSignatories')){
            content = 'Are you sure you want to deactivate selected sub signatory?';
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
                                                    case 'activateSignatories':
                                                    case 'deactivateSignatories':
                                                    case 'activateHeadSignatories':
                                                    case 'deactivateHeadSignatories':
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
    $(document).on('click','#addSignatoriesForm,.updateSignatoriesForm,#addHeadSignatoriesForm,#updateHeadSignatoriesForm',function(e){
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
                        case 'addSignatories':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Signatory Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'addHeadSignatories':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Head Signatory Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'generateSignatories':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Signatory Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateSignatories':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Signatory Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');

                            $.each(me.data(),function(i,v){
                                //console.log(me.data(i) + " : " + i);
                                if(i != "division_id"){
                                    $('.'+i).val(me.data(i)).change();
                                }
                            });
                            setTimeout(function(){
                                emp_id = me.data("employee_id");
								//console.log(me.data("division_id"));
                                $("#division_id").val(me.data("division_id")).change();
                                $.AdminBSB.select.activate();
                            }, 1000);

                            // $('.file').val(me.attr('data-file_name'));
                            // var sig_img = me.attr('data-file_dir').replace("./", base_url)+me.attr('data-file_name');
                            // $('.signature_img').attr('src',sig_img);
                            // $('.signature_img').parent().show();
                            
                            break;
                        case 'updateHeadSignatories':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Head Signatory Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(me.data(i)).change();
                            });
                            // payroll_grouping_id = me.data().payroll_grouping_id;
                            // $('.file').val(me.attr('data-file_name'));
                            // var sig_img = me.attr('data-file_dir').replace("./", base_url)+me.attr('data-file_name');
                            // $('.signature_img').attr('src',sig_img);
                            // $('.signature_img').parent().show();
                            
                            break;
                    }
                    $("#"+result.key).validate({
                        rules:{
                            ".required":{
                                required: true
                            }, ".email":{
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
    $(document).on('submit','#addSignatories,#updateSignatories,#addHeadSignatories,#updateHeadSignatories',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addSignatories"){
            content = "Are you sure you want to add signatory?";
        }
        if(form.attr('id') == "updateSignatories"){
            content = "Are you sure you want to update signatory?";
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
                                    // data: form.serialize(),
                                    data: new FormData(form[0]),
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'addSignatories':
                                                        case 'updateSignatories':
                                                        case 'addHeadSignatories':
                                                        case 'updateHeadSignatories':
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
                url:commons.baseurl+ "fieldmanagement/Signatories/fetchRows?status="+$("#status").val(),
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
    
    function getEmployeeList(division_id) {
        if(division_id != "") {
            $.when(getFields.employee({division_id : division_id})
            ).done(function(){
                $('.employee_id').val(emp_id).change();
                $.AdminBSB.select.activate();
            });
        }
    }

    function PrintElem(elem){
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

    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('.signature_img').parent().show();
            $('.signature_img').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
    }   
})