$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();

    $('#date_to').bootstrapMaterialDatePicker({ 
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 0,
        time: false 
    });

    $('#date_from').bootstrapMaterialDatePicker({ 
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 0,
        time: false 
    }).on('change', function(e, date) {
        $('#date_to').val('');
        $('#date_to').bootstrapMaterialDatePicker('setMinDate', date);
    });

    // $('.datepicker').bootstrapMaterialDatePicker({
    //     format: 'YYYY-MM-DD',
    //     clearButton: true,
    //     weekStart: 1,
    //     time: false
    // });
    // $('#date_to').attr('disabled',true)
    // $(document).on('change','#date_from',function(e){
    //     startDate = $(this).val()
    //     $('#date_to').val('');
    //     $('#date_to').removeAttr('disabled');
    //     $('#date_to').bootstrapMaterialDatePicker({
    //         format: 'YYYY-MM-DD',
    //         clearButton: true,
    //         weekStart: 1,
    //         time: false,
    //         minDate:startDate
    //     });
    // });

    $(document).on('click','#pcsync', function(e) {
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');

        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();

        if((date_from != '') && (date_to != '')) {
            content = 'Are you sure you want to proceed?';
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
                                        data: {date_from:date_from,date_to:date_to},
                                        dataType: "json",
                                        success: function(result){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'pcSyncing':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
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
        } else {
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Incomplete Parameters. Please fillup the necessary fields.'
            });
        }
    })
    //Confirms
    function loadTable(){
        
        // table = $('#datatables').DataTable({  
        //        "processing":true,  
        //        "serverSide":true,  
        //        "order":[],  
        //        "ajax":{  
        //             url:commons.baseurl+ "timekeeping/PCBasedSync/fetchRows",  
        //             type:"POST"  
        //        },  
        //        "columnDefs":[  
        //             {  
        //                  "orderable":false,  
        //             },  
        //        ],  
        // });
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
                url:commons.baseurl+ "timekeeping/PCBasedSync/fetchRows",  
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