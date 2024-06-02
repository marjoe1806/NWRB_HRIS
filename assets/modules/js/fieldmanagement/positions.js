$(function(){
    var page = "";
    var payBasis = "";
    var salaryGrade = "";
    var salaryGradeStep = "";
    base_url = commons.base_url;

    $(document).on("click", "#btnsearch", function(){
        loadTable();
    });
    
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
    $(document).on('click','#printPositions',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('change','#pay_basis',function(e){
        my = $(this);
        $('#salary_grade_id').parent().parent().parent().parent().show();
        $('#salary_grade_step_id').parent().parent().parent().parent().show();
        if(my.val() == "Permanent" || my.val() == "Permanent (Probationary)"){
            pay_basis = my.val();
            $.when(
                getFields.salary_grade(pay_basis)
            ).done(function () {
                $('#salary_grade_id').val(salaryGrade)
                $('#salary_grade_id').change();
                $.AdminBSB.select.activate();
            })
        }
        else{
            $('#salary_grade_id').parent().parent().parent().parent().hide();
            $('#salary_grade_step_id').parent().parent().parent().parent().hide();
        }
    });
    $(document).on('change','#salary_grade_id',function(e){
        my = $(this);
        id = my.val();
        $.when(
            getFields.salary_grade_step(id,$('#pay_basis').val())
        ).done(function () {
            $('#salary_grade_step_id').val(salaryGradeStep);
            $('#salary_grade_step_id').change();
            $.AdminBSB.select.activate();
        })
    });
    //Confirms
    $(document).on('click','.activatePositions,.deactivatePositions',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activatePositions')){
            content = 'Are you sure you want to activate selected position?';
        }
        else if(me.hasClass('deactivateSubPositions')){
            content = 'Are you sure you want to deactivate selected sub position?';
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
                                                    case 'activatePositions':
                                                    case 'deactivatePositions':
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
    $(document).on('click','#addPositionsForm,.updatePositionsForm',function(e){
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
                        case 'addPositions':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $("#is_leave_recom, #is_break, #is_dtr, #is_driver").iCheck("destroy");
                            $("#is_leave_recom, #is_break, #is_dtr, #is_driver").iCheck({
                              checkboxClass: "icheckbox_square-grey",
                            });
                            // $("#is_dtr").iCheck("destroy");
                            // $("#is_dtr").iCheck({
                            //   checkboxClass: "icheckbox_square-grey",
                            // });
                            $.when(
                                getFields.payBasis3(),
                                getFields.division()
                            ).done(function () {
                                $.AdminBSB.select.activate();
                            })
                            break;
                        case 'generatePositions':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updatePositions':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.when(
                                getFields.payBasis3(),
                                getFields.division()
                            ).done(function () {
                                $.AdminBSB.select.activate();
                            })
                            salaryGrade = me.data('salary_grade_id');
                            salaryGradeStep = me.data('salary_grade_step_id');
                            $("#is_leave_recom, #is_break, #is_dtr, #is_driver").iCheck("destroy");
                            $("#is_leave_recom, #is_break, #is_dtr, #is_driver").iCheck({
                              checkboxClass: "icheckbox_square-grey",
                            });
                        
                            $.each(me.data(),function(i,v){
                                if(i == "is_break"){
                                    if(me.data("is_break") == 1){
                                        $("#is_break").iCheck("check");
                                    }else $("#is_break").iCheck("uncheck");
                                }else $('.'+i).val(me.data(i)).change();
                                
                                if(i == "is_leave_recom"){
                                    if(me.data("is_leave_recom") == 1){
                                        $("#is_leave_recom").iCheck("check");
                                    }else $("#is_leave_recom").iCheck("uncheck");
                                }else $('.'+i).val(me.data(i)).change();

                                if(i == "is_dtr"){
                                    if(me.data("is_dtr") == 1){
                                        $("#is_dtr").iCheck("check");
                                    }else $("#is_dtr").iCheck("uncheck");
                                }else $('.'+i).val(me.data(i)).change();

                                if(i == "is_driver"){
                                    if(me.data("is_driver") == 1){
                                        $("#is_driver").iCheck("check");
                                    }else $("#is_driver").iCheck("uncheck");
                                }else $('.'+i).val(me.data(i)).change();
                            });
                            setTimeout(function(){
								//console.log(me.data("division_id"));
                                $("#division_id").val(me.data("division_id")).change();
                                $.AdminBSB.select.activate();
                            }, 1000);
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
    $(document).on('submit','#addPositions,#updatePositions',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addPositions"){
            content = "Are you sure you want to add position?";
        }
        if(form.attr('id') == "updatePositions"){
            content = "Are you sure you want to update position?";
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
                                                        case 'addPositions':
                                                        case 'updatePositions':
                                                            
                                                            $.when( loadTable()).done(function () {
                                                                self.setContent(result.Message);
                                                                self.setTitle('<label class="text-success">Success</label>');
                                                                $('#myModal .modal-body').html('');
                                                                $('#myModal').modal('hide');
                                                            });
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
    // function loadTable(){
    //     var url = window.location.href;
    //     $.ajax({url: url,dataType:"json", success: function(result){
    //         $("#table-holder").html(result.table);
    //         table = $('#datatables').DataTable({
    //             "pagingType": "full_numbers",
    //             "lengthMenu": [
    //                 [10, 25, 50, -1],
    //                 [10, 25, 50, "All"]
    //             ],
    //             aaSorting: [],
    //             language: {
    //                 search: "_INPUT_",
    //                 searchPlaceholder: "Search records",
    //             }

    //         });
    //     }});
    // }

    

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
                url:commons.baseurl+ "fieldmanagement/Positions/fetchRows?grade="+$("#grade").val(),
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
})