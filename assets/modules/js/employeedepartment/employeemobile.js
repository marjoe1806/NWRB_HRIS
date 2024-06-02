$(function(){
    var page = "";
    var table;
    loadTable();
    base_url = commons.base_url;
    var table;
    $.when(
        getFields.location()
    ).done(function(){
        // $('#location_id').prepend('<option value="all">ALL</option>')
        $('#location_id option:first').after($('<option />', { "value": 'all', text: 'ALL' }));
        $.AdminBSB.select.activate();
    });
    //Confirms
    $(document).on('click','#downloadEmployeesMobile',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var location_id = $('#location_id').val();
        var location_name = $("#location_id option:selected").text();
        content = 'Are you sure you want to download mobile employees for '+location_name+'?';
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
                                    data: {location_id:location_id},
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'downloadEmployeesMobile':
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
    function loadTable(){
        table = $('#datatables').DataTable({  
               "processing":true,  
               "serverSide":true,  
               "order":[],
               scroller: {
                    displayBuffer: 20
               },
               "ajax":{  
                    url:commons.baseurl+ "employees/EmployeesMobile/fetchRows",  
                    type:"POST",
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        // Note: You can use "textStatus" to describe the error.
                        // Custom
                        switch(jqXHR.status)
                        {
                            case 404:
                                $.alert({
                                    title:'<label class="text-danger">Failed</label>',
                                    content:'Requested page not found. [404]'
                                });
                            break;
                             
                            case 500:
                                $.alert({
                                    title:'<label class="text-danger">Failed</label>',
                                    content:'Internal Server Error [500]'
                                });
                            break;
                             
                            default:
                                $.alert({
                                    title:'<label class="text-danger">Failed</label>',
                                    content:'Unexpected Unknown Error. Please Contact the administrator.'
                                });
                            break;
                        }
                         
                        // Global
                        if (jqXHR.status != 0)
                        {
                            // $.alert('A system error has occurred (Please contact the administrator).');
                            // Or you can invoke modal bootstrap rather than a java alert.   
                        }
                    }  
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
                                    +'</div>'},
               "columnDefs":[  
                    {  
                        "targets": [0],
                        "orderable":false,  
                    },  
               ]
        });
    }
    function reloadTable(){
        
        table.ajax.reload();
    }
})