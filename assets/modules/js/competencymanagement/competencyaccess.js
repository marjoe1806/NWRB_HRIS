$(function(){
    var page = "";
    base_url = commons.base_url;
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
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    $(document).on('click','#printCompetencyAccess',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
   
    //Ajax non-forms
    $(document).on('click','#addCompetencyAccessForm,.updateCompetencyAccessForm,.viewCompetencyAccessDetails',function(e){
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
                        case 'addCompetencyAccess':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Add New Competency Access');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateCompetencyAccess':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Update Competency Access');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            min_date = me.attr('data-date');
                            $('.date').attr("min",min_date);
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(v).change();
                            });
                            break;
                        case 'viewCompetencyAccessDetails':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('View Competency Access Detials');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                console.log(i,v)
                                $('.'+i).val(v).change();
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
    });
    //Ajax Forms
    $(document).on('submit','#addCompetencyAccess,#updateCompetencyAccess',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addCompetencyAccess"){
            content = "Are you sure you want to add competency access?";
        }
        if(form.attr('id') == "updateCompetencyAccess"){
            content = "Are you sure you want to update competency access?";
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
                                                        case 'addCompetencyAccess':
                                                        case 'updateCompetencyAccess':
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
    });

    $(document).on("click", ".removeEmailaddress", function(){
        me = $(this)
        id = me.attr('data-id')

        const row_id = '.email_row_'+id;
        $(row_id).remove();
    });

    var row_add_num = 100;
    $(document).on("click", ".addEmailaddress", function(){
        // this.closest('.formButton').html(
        //     '<button data-id="'+row_add_num+'" class="btn btn-danger btn-sm waves-effect removeEmailaddress" type="button" style="min-width: 100%">'
        //         +'<i class="material-icons">remove</i><span> Remove</span>'
        //     +'</button>'
        // );
        var btn_id = $(this).attr('data-id');
        $('.button_div_'+btn_id).html(
            '<button data-id="'+btn_id+'" class="btn btn-danger btn-sm waves-effect removeEmailaddress" type="button" style="min-width: 100%">'
                +'<i class="material-icons">remove</i><span> Remove</span>'
            +'</button>'
        );

        row_add_num += 1;

        var options = '';
        global_employee_list.forEach(el => {
            options += '<option value="'+el.email+'">'+el.email+'</option>';
    });

        $('#emailDisplayList').append(
            '<div class="row clearfix email_row_'+row_add_num+'">'
                +'<div class="col-md-6">'
                    +'<div class="form-group">'
                        +'<div class="form-line">'
                            +'<select name="emailaddress[]" id="emailaddress" class="form-control emailaddress custom-select" '
                            +'<option value=""></option>'
                            +'<option value=""></option>'
                            +options
                            +'</select>'
                        +'</div>'
                    +'</div>'
                +'</div>'
                +'<div class="col-md-2" style="text-align: middle">'
                    +'<div class="button_div_'+row_add_num+'">'
                        +'<button data-id="'+row_add_num+'" class="btn btn-primary btn-sm waves-effect addEmailaddress" type="button" style="min-width: 100%">'
                            +'<i class="material-icons">add</i><span> Add</span>'
                        +'</button>'
                    +'</div>'
                +'</div>'
                +'<div class="col-md-4">'
                +'</div>'
            +'</div>'
        );

        $('.custom-select').selectpicker('refresh'); 
    });

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
                url:commons.baseurl+ "competencymanagement/CompetencyAccess/fetchRows?status="+$("#status").val(),
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

    loadTable();
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