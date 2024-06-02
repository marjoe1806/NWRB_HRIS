$(function(){
    var page = "";
    var payBasis = "";
    var salaryGrade = "";
    var salaryGradeStep = "";
    base_url = commons.base_url;
    var table;
    table = $('#datatables').DataTable({
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
    
    //Confirms
    $(document).on('click','.activateRATA,.deactivateRATA',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateRATA')){
            content = 'Are you sure you want to activate selected position?';
        }
        else if(me.hasClass('deactivateSubRATA')){
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
                                                    case 'activateRATA':
                                                    case 'deactivateRATA':
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
    $(document).on('click','#addRATAForm,.updateRATAForm',function(e){
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
                        case 'addRATA':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Add New Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.when(
                                getFields.position()
                            ).done(function () {
                                $.AdminBSB.select.activate();
                            })
                            break;
                        case 'generateRATA':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            break;
                        case 'updateRATA':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Update Position Record');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.when(
                                getFields.position()
                            ).done(function () {
                                $.each(me.data(),function(i,v){
                                    $('.'+i).val(me.data(i)).change();
                                });
                                $.AdminBSB.select.activate();
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
    $(document).on('submit','#addRATA,#updateRATA',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addRATA"){
            content = "Are you sure you want to add position?";
        }
        if(form.attr('id') == "updateRATA"){
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
                                                        case 'addRATA':
                                                        case 'updateRATA':
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
            table = $('#datatables').DataTable({
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
})