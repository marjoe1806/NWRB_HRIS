$(function(){
    
    var page = "";
    base_url = commons.base_url;
    var table;
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    /*button = '<a id="viewFilteredEmployee" href="#">' +
                    '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">' +
                    '<i class="material-icons">people</i> Load Filtered Employees' +
                    '</button>' +
                    '</a>'
    filters = '<div class="row">'
                +   '<div class="col-md-6">'
                +       '<div>Location'
                +           '<div class="form-group">'
                +               '<div class="form-line location_select">'
                +                   '<select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">'
                +                       '<option value="" selected></option>'
                +                   '</select>'
                +               '</div>'
                +           '</div>'
                +       '</div>'
                +   '</div>'
                +   '<div class="col-md-6">'
                +       '<div>Department'
                +           '<div class="form-group">'
                +               '<div class="form-line division_select">'
                +                   '<select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">'
                +                       '<option value="" selected></option>'
                +                   '</select>'
                +               '</div>'
                +           '</div>'
                +       '</div>'
                +   '</div>'
                +   '<div class="col-md-12">'
                +      button 
                +   '</div>'
                +'</div>';
    $('.listTable').prepend(filters);*/
    loadTable();
    /*$(document).on('click','#viewFilteredEmployee',function(e){
        e.preventDefault();
        loadTable();
    })
    $.when(
        getFields.location(),
        getFields.division()
    ).done(function () {
        $('#division_id option:first').text("All");
        $('#location_id option:first').text("All");
        $('#division_id option:first').val("");
        $('#location_id option:first').val("");
        $.AdminBSB.select.activate();
    })*/
    $(document).on('hide.bs.modal','#myModal', function () {
        Webcam.reset();
        //$.AdminBSB.input.activate();
    })

    $(document).on('click','.upload',function(e){
        e.preventDefault();
        $(this).parent().find('input[type="file"]').replaceWith($(this).parent().find('input[type="file"]').val(''));
        $(this).parent().find('input[type="file"]').trigger('click');
    })

    $(document).on('keyup','.dataTables_filter input',function(e){
        if($(this).val() != '' && e.keyCode == 13) {
            $("#search-employee").click();
        }
    });

    $(document).on('change','.position_id',function(e){
        my = $(this)
        id = my.val()
        url2 = commons.baseurl + "fieldmanagement/Positions/getPositionsById";
        $.ajax({
            url: url2,
            data: {id:id},
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                if(res.Code == "0"){
                    if(res.Data.details[0].pay_basis == "Permanent-Casual"){
                        $('#monthly_tax_container').show();
                        $('#jo_tax_container').hide();
                    }
                    else{
                        $('#monthly_tax_container').hide();
                        $('#jo_tax_container').show();
                    }
                    //$('.selectpicker').selectpicker('refresh')
                    $('#myModal .salary').val(res.Data.details[0].salary);
                    $('#myModal .salary_grade_step').val(res.Data.details[0].step);
                    $('#myModal .salary_grade').val(res.Data.details[0].grade);
                    $('#myModal .salary_grade_id').val(res.Data.details[0].grade_id);
                    $('#myModal .salary_grade_step_id').val(res.Data.details[0].grade_step_id);
                    loadTax(res.Data.details[0].salary);
                }
            }
        });
    })
    $(document).on('keyup','#salary',function(e){
        $('#monthly_tax_container').show();
        $('#jo_tax_container').hide();
        salary = $(this).val();
        loadTax(salary);
    })
    $(document).on('click','#take_snapshot',function(e){
        e.preventDefault();
        take_snapshot();
    })
    $(document).on('change','#fileupload',function(){
        readURL(this);
        $("#employeeImage").show();
        $("#reset_snapshot").show();
        $("#take_snapshot").hide();
        $("#my_camera").hide();
    })
    $(document).on('change','#budget_classification_id',function(e){
        me = $(this)
        id = me.val();
        data = {id:id}
        url2 = commons.baseurl + "fieldmanagement/BudgetClassifications/getBudgetClassificationsById";
        $.ajax({
            url: url2,
            type:'POST',
            data: data,
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){   
                    $('#services').val(temp.Data.details[0].budget_classification_name);
                }
            }
        });  
    })
    $(document).on('click','.updateEmployeeAllowancesForm',function(e){
        e.preventDefault();
        $(this).closest('tr').find(':input,:button').show().removeAttr('disabled');
        $(this).closest('tr').find('.deactivateEmployeeAllowances,.activateEmployeeAllowances,.td-text').hide();
        $(this).hide();
    })
    $(document).on('click','.cancelUpdateForm',function(e){
        e.preventDefault();
        $(this).closest('tr').find(':input,:button').hide().attr('disabled','disabled');
        $(this).closest('tr').find('.deactivateEmployeeAllowances,.activateEmployeeAllowances,.td-text,.updateEmployeeAllowancesForm').show();
        $(this).hide();
    })
    $(document).on('click','.regular_shift1',function(e){
        if($(this).is(':checked')){
            $('form .shift_container').show(); 
        }
        else{
            
            $('form .shift_container').hide(); 
        }
        
    })
    $(document).on('click','#printEmployees',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('keypress keydown keyup','#employee_id_number',function(e){
        emp_num = $(this).val();
        numberNoHyphens = emp_num.replace(/-/g,"");
        $("#employee_number").val(numberNoHyphens);
    })
    //Confirms
    $(document).on('click','.activateEmployees,.deactivateEmployees,.activateEmployeeAllowances,.deactivateEmployeeAllowances',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateEmployees')){
            content = 'Are you sure you want to activate selected employee?';
        }
        else if(me.hasClass('deactivateSubEmployees')){
            content = 'Are you sure you want to deactivate selected sub employee?';
        }
        else if(me.hasClass('activateEmployeeAllowances')){
            content = 'Are you sure you want to activate selected allowance?';
        }
        else if(me.hasClass('deactivateEmployeeAllowances')){
            content = 'Are you sure you want to deactivate selected allowance?';
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
                                                    case 'activateEmployees':
                                                    case 'deactivateEmployees':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        reloadTable();
                                                        break;
                                                    case 'activateEmployeeAllowances':
                                                    case 'deactivateEmployeeAllowances':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        loadAllowanceTable(me.data('employee_id'));

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
    $(document).on('click','#addEmployeesForm,.updateEmployeesForm,.viewEmployeesForm,.viewEmployeeAllowancesForm',function(e){
        e.preventDefault();
        my = $(this)
        data = my.data();
        id = my.data('id');
        employee_id = id
        url = my.attr('href'); 
        if(!my.find('button').is(':disabled')){
            getFields.reloadModal();
            $.ajax({
                type: "POST",
                url: url,
                data: {id:id},
                dataType: "json",
                success: function(result){
                    page = my.attr('id');
                    if(result.hasOwnProperty("key")){
                        switch(result.key){
                            case 'addEmployees':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                $('#myModal .modal-title').html('Add New Employee');
                                $('#myModal .modal-body').html(result.form);
                                break;
                            case 'viewEmployees':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                $('#myModal .modal-title').html('Employee Details');
                                $('#myModal .modal-body').html(result.form);
                                break;
                            case 'viewEmployeeAllowances':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                                $('#myModal .modal-title').html('Add New Allowance Record');
                                $('#myModal .modal-body').html(result.form);
                                $('#myModal .employee_name').html(my.data('first_name')+" "+my.data("last_name"))
                                $('#myModal #employee_id').val(id);
                                break;
                            case 'updateEmployees':
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                $('#myModal .modal-title').html('Update Employee');
                                $('#myModal .modal-body').html(result.form);
                                break;
                        }
                        $.when(
                            getFields.position(),
                            getFields.agency(),
                            getFields.office(),
                            getFields.division(),
                            getFields.location(),
                            getFields.employmentStatus(),
                            getFields.contract(),
                            getFields.payBasis3(),
                            getFields.shift(),
                            getFields.allowance()
                        ).done(function(){
                            $.each(my.data(),function(i,v){
                                if(i != "position_id"){
                                    $('#myModal .'+i).val(my.data(i)).change();
                                    if(v == "0" || v == "1"){
                                        $('#myModal .'+i+v).click();
                                    } 
                                }
                            });
                            $('#myModal #position_id').attr('disabled',true);
                            $('#myModal #pay_basis').attr('disabled',true);
                            $('#myModal #employment_status').attr('disabled',true);
                            $('#myModal .position_id').val(my.data('position_id')).change();
                            if(result.key != "addEmployees"){
                                alert
                                $.ajax({
                                    url: commons.baseurl+"employeedepartment/Employees/getActivePhotoByEmployeeId",
                                    data:{employee_id: employee_id},
                                    type:"POST",
                                    dataType:"json",
                                    success: function(result){
                                        // console.log(result);
                                        photo = commons.baseurl+"assets/custom/images/default-avatar.jpg"
                                        if(result.Code == "0"){
                                            if(result.Data.details[0].employee_id_photo != ""){
                                                photo = result.Data.details[0].employee_id_photo
                                            }
                                        }
                                        console.log(employee_id);
                                        $('#employeeImage').attr('src', photo)
                                        $(".image-tag").attr("href",photo);
                                        $("#employeeImage").show();
                                        $("#reset_snapshot").show();
                                        $("#take_snapshot").hide();
                                        $("#my_camera").hide(); 
                                        
                                    }
                                });
                            } 
                            if(result.key =="viewEmployees"){
                                $('form').find('input, textarea, button, select').attr('disabled','disabled');
                                $('form').find('#cancelUpdateForm').removeAttr('disabled');
                            }
                            $.AdminBSB.select.activate(); 
                            $('form').find('.date').inputmask('mm/dd/yyyy', { placeholder: '__/__/____' });
                            $('#aniimated-thumbnials').lightGallery({
                                thumbnail: true,
                                selector: 'a'
                            }); 
                            var width = 327;
                            var height = 327;
                            if(result.key != "viewEmployees" && result.key != "viewEmployeeAllowances"){
                                Webcam.set({
                                    width: parseInt(width,10) - 30,
                                    height: parseInt(height,10) -30,
                                    image_format: 'jpeg',
                                    jpeg_quality: 90
                                });
                                Webcam.attach( '#my_camera' );
                                Webcam.on( 'error', function(err) {
                                    console.log(err);
                                } );
                            }
                            $('.datepicker').bootstrapMaterialDatePicker({
                                format: 'YYYY-MM-DD',
                                clearButton: true,
                                time: false
                            });
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
        }
    })
    //Ajax Forms
    $(document).on('submit','#addEmployees,#updateEmployees,#addEmployeeAllowances,#updateEmployeeAllowances',function(e){
        var formData = new FormData($(this)[0]);
        
        e.preventDefault();
        var form = $(this)
        image = $("#employeeImage").attr("src");
        if(form.attr('id') == "addEmployees" || form.attr('id') == "updateEmployees"){
            formData.append('employee_id_photo',image); 
        }
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addEmployees"){
            content = "Are you sure you want to add employee?";
        }
        if(form.attr('id') == "updateEmployees"){
            content = "Are you sure you want to update employee?";
        }
        if(form.attr('id') == "addEmployeeAllowances"){
            content = "Are you sure you want to add allowance?";
        }
        if(form.attr('id') == "updateEmployeeAllowances"){
            content = "Are you sure you want to update allowance?";
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
                                                        case 'addEmployees':
                                                        case 'updateEmployees':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            reloadTable();
                                                            break;
                                                        case 'updateEmployeeAllowances':
                                                        case 'addEmployeeAllowances':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            //$('#myModal .modal-body').html('');
                                                            loadAllowanceTable(form.find('.employee_id').val());
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
        employment_status = $('#hide_emp_status').val();
        location_id = $('#location_id').val();
        division_id = $('#division_id').val();
        plus_url = ""
        /*if(employment_status != ""){
            plus_url += '?EmploymentStatus='+employment_status;
        }
        if(location_id != ""){
            plus_url += '&LocationId='+location_id;
        }
        if(division_id != ""){
            plus_url += '&DivisionId='+division_id;
        }*/
        $('#datatables').DataTable().clear().destroy();
        table = $('#datatables').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
              "targets"  : [0,1],
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
                url:commons.baseurl+ "employeedepartment/Employees/fetchRows"+plus_url,  
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
        
        table.ajax.reload();
    }
    function loadAllowanceTable(data_id){
        //console.log(data_id)
        
        var url = window.location.href+'viewEmployeeAllowancesForm';
        $.ajax({url: url,data:{id:data_id},type: "POST",dataType:"json", success: function(result){
            $("#myModal .modal-body").html(result.form);
            $('#myModal #employee_id').val(data_id);
            $.when(getFields.allowance()).done(function(){
                $.AdminBSB.select.activate();
            })
            
        }});
    }
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
    //Webcam Feature
    $(document).on('click','#reset_snapshot',function(e){
        e.preventDefault();
        $("#my_camera").show();
        $("#take_snapshot").show();
        $("#reset_snapshot").hide();
        $("#employeeImage").hide();

    })
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap( function(data_uri) {
            // display results in page
            
            $("#employeeImage").attr("src",data_uri);
            $(".image-tag").attr("href",data_uri);
            $("#employeeImage").show();
            $("#reset_snapshot").show();
            $("#take_snapshot").hide();
            $("#my_camera").hide();
        } );
    }
    function readURL(input) {
        //console.log(input.files[0])
        //data_uri = window.URL.createObjectURL(input.prop('files')[0])
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#employeeImage').attr('src', e.target.result)
                $(".image-tag").attr("href",e.target.result);
            }

            reader.onload = function (e) {
                $('#employeeImage').attr('src', e.target.result)
                $(".image-tag").attr("href",e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function loadTax(salary){
        $('#tax_percentage').val(0);
        $('#tax_additional').val(0);
        data = {salary:salary}
        url2 = commons.baseurl + "fieldmanagement/WithHoldingTaxes/getWithHoldingTaxesBySalary";
        $.ajax({
            url: url2,
            type:'POST',
            data: data,
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    tax_percentage = temp.Data.details[0].tax_percentage;
                    tax_additional = temp.Data.details[0].tax_additional;
                    $('#tax_percentage').val(tax_percentage);
                    $('#tax_additional').val(tax_additional);
                }
            }
        });
    }

})