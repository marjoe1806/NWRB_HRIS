$(function(){
    var page = "";
    var table;
    loadTable();
    base_url = commons.base_url;
    var table;
    $.when(
        getFields.mobileDevices()
    ).done(function(){
        // $('#location_id').prepend('<option value="all">ALL</option>')
        $('#location_id option:first').after($('<option />', { "value": 'all', text: 'ALL' }));
        $.AdminBSB.select.activate();
    });
    $(document).on('click','#downloadText',function(e){
        // e.preventDefault()
        // alert('hello')
    })
    //Confirms
    $(document).on('click','#downloadEmployeesMobile',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var mobile_id = $('#mobile_id').val();
        var mobile_name = $("#mobile_id option:selected").text();
        content = 'Are you sure you want to download mobile employees for '+mobile_name+'?';
        if(mobile_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a mobile device.'
            });
            return false;
        }
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
                                    data: {mobile_id:mobile_id},
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'downloadEmployeesMobile':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        console.log(result)
                                                        file_name = $('#mobile_id').val()//$('#mobile_id option:selected').text().replace(/\s/g,"-")+'.txt';
                                                        $('#downloadText').attr('href','data:text/plain;charset=UTF-8,'+result.Data.content)
                                                        $('#downloadText').attr('download',file_name);
                                                        // $('#downloadText').trigger('click');
                                                        $('#downloadText').get(0).click()
                                                        reloadTable();
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
    $(document).on('keyup','.dataTables_filter input',function(e){
        if($(this).val() != '' && e.keyCode == 13) {
            $("#search-employee").click();
        }
    });
    function loadTable(){
        table = $('#datatables').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "order":[],
            scroller: {
                displayBuffer: 20
            },
            "columnDefs": [ {
                "targets": [0],
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
                url:commons.baseurl+ "employees/EmployeesMobile/fetchRows",  
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
    makeTextFile = function (text) {
        var data = new Blob([text], {type: 'text/plain'});

        // If we are replacing a previously generated file we need to
        // manually revoke the object URL to avoid memory leaks.
        if (textFile !== null) {
            window.URL.revokeObjectURL(textFile);
        }

        textFile = window.URL.createObjectURL(data);

        return textFile;
    };
})