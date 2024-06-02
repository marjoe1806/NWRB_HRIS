$(document).ready(function(){
    
    var baseURL = commons.baseurl;

    function opcr_reviews(){
        $('#opcr_reviews').DataTable({
            "destroy": true,
            "pagingType": "full_numbers",
            "ordering": false,
            "lengthMenu": [[10, 25, 50, -1], 
            [10, 25, 50, "All"]],
            responsive: true,
            language: {
            search: "_INPUT_",
                searchPlaceholder: "Search records",
            },
            ajax:
            {
                url:  baseURL + "performancemanagement/OpcrValidate/get_opcr",
                type: "POST"
            },
            columns: [
                {
                    data: 'empl_name'
                },
                {
                    data: 'position'
                },
                {
                    data: 'validation',
                    render: function(data, type, row){
                        if(data == 0){
                            return 'PENDING';
                        }

                        else if(data == 1){
                            return 'VALIDATED'; 
                        }

                        else{
                            return 'INVALID'; 
                        }
                    }
                },
                {
                    data: 'submitted_date'
                },
                {
                    "data": function (data) {
                        // return `
                        //     <a class="btn btn-simple btn-primary btn-icon view pull-right"
                        //         data-id = "`+data.form_id+`" data-validated = "`+data.validation+`"
                        //     >
                        //         <i class="fa fa-list"></i>
                        //     </a>
                        // `;
                        return `
                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float pull-right view" 
                                    data-id = "${data.form_id}"
                                    data-validated = "${data.validation}"
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="" 
                                    data-original-title="View details"> 
                                <i class="material-icons">list</i>  
                            </button>
                        `;
                    },
                }
            ],
            createdRow: function (row, data, indice) {
                $(row).find('td:contains(INVALID)').closest('tr').find('.view').prop('disabled',true);
            }
        });
    }

    opcr_reviews();

    $(document).on("click", ".view", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var validate = $(this).data('validated');
        var url = baseURL + 'performancemanagement/OpcrValidate/view_ipcr';
        $.post(url, {id:id}).done(function(result) {
            result = JSON.parse(result);
            form = 'validate';
            title = 'Validate';
            body = result.form;
            if(validate != 1){
                footer = 
                    '<br><br><button type = "submit" class = "btn btn-warning btn-fill pull-right validate" data-val = "1">Validate</button>'
                    +'<button type = "submit" class = "btn btn-danger btn-fill pull-right validate" data-val = "-1">Invalid</button><br><br>'
                ;
            }
            else{
                footer = '<br><br><button type = "button" class = "btn btn-default pull-right btn-fill" data-dismiss = "modal">Close</button><br><br>';
            }
            size = 'lg';
            modal(form,title,body,footer,size);
            $('#myModal .modal-dialog').css('width','90%')
        });

    });

    $(document).on("click", ".validate", function (e) {
        e.preventDefault();
        var val = $(this).data('val')
        var url = baseURL + 'performancemanagement/OpcrValidate/validate';
        var data = {
            data: $('#validate').serializeArray(),
            val: val
        };
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure want to proceed?',
            buttons: {
                cancel:{
                    text: 'Cancel',
                    btnClass: 'btn-warning',
                    keys: ['enter', 'shift'],
                    action: function(){
                        $('#myModal').modal('hide');
                    }
                },
                confirm:{
                    text: 'Submit',
                    btnClass: 'btn-success',
                    action: function(){
                        $.post(url, data).done(function(result) {
                            result = JSON.parse(result);
                            if(result.Code == 0){
                                $.confirm({
                                    title: 'Success!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-success',
                                            keys: ['enter', 'shift'],
                                            action: function(){
                                                opcr_reviews();
                                                $('#myModal').modal('hide');
                                            }
                                        }
                                    }
                                });
                            }
                            else{
                                $.confirm({
                                    title: 'Warning!',
                                    content: result.Message,
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-warning',
                                            keys: ['enter', 'shift'],
                                            action: function(){
                                                
                                            }
                                        }
                                    }
                                });
                            }      
                        });
                    }
                }
            }
        });
    });


    function modal(form,title,body,footer,size){
        $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-'+size+'');
        $('#myModal .modal-title').html(title);
        $('#myModal .modal-body').html(`<form id="${form}">`+body+footer+`</form>`);
        $('#myModal').modal('show');
    }


});