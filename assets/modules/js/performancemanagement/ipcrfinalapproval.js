$(document).ready(function(){
    var baseURL = commons.baseurl;

    function ipcr_reviews(){
        $('#ipcr_reviews').DataTable({
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
                url:  baseURL + "performancemanagement/IpcrFinalApproval/get_ipcr",
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
                    data: 'period_of'
                },
                {
                    data: 'higher_approval',
                    render: function(data, type, row){
                        if(data == 0){
                            return 'PENDING';
                        }

                        else if(data == 1){
                            return 'APPROVED'; 
                        }

                        else{
                            return 'DISAPPROVED'; 
                        }
                    }
                },
                {
                    data: 'posted_date'
                },
                {
                    "data": function (data) {
                        return `
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float pull-right view" 
                                data-id = "${data.form_id}"
                                data-approved = "${data.higher_approval}"
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
                $(row).find('td:contains(DISAPPROVED)').closest('tr').find('.view').prop('disabled',true);
            }
        });
    }

    ipcr_reviews();

    $(document).on("click", ".view", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var approved = $(this).data('approved');
        var url = baseURL + 'performancemanagement/IpcrFinalApproval/view_ipcr';
        $.post(url, {id:id}).done(function(result) {
            result = JSON.parse(result);
            form = 'approve';
            title = 'Final Approval';
            body = result.form;
            if(approved != 1){
                footer = 
                    '<br><br><button type = "button" class = "btn btn-success btn-fill pull-right approve" data-val = "1">Approve</button>'
                    +'<button type = "button" class = "btn btn-warning btn-fill pull-right approve" data-val = "-1">Disapprove</button><br><br>'
                ;
            }
            else{
                footer = '<br><br><button type = "button" class = "btn btn-default btn-fill pull-right" data-dismiss = "modal">Close</button><br><br>';
            }
            size = 'lg';
            modal(form,title,body,footer,size);
            $('#myModal .modal-dialog').css('width','90%')
        });

    });

    $(document).on("click", ".approve", function (e) {
        e.preventDefault();
        var val = $(this).data('val')
        var data = {
            data: $('#approve').serializeArray(),
            val: val
        };
        var url = baseURL + 'performancemanagement/IpcrFinalApproval/finalapprove';

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
                                                ipcr_reviews();
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