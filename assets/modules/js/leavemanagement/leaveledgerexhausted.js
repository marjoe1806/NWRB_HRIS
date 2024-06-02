$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $.when(
        getFields.division(),
        getFields.month()
    ).done(function(){
        if($('#leave_tracking_all_access').val() == 0){
            $('.division_id').val($('#division_id_hide').val()).change();
            $('.division_id').attr('disabled',true);
        }
        else{
            $(".division_id").selectpicker("render");
        }
        $(".month").selectpicker("render");
    })
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
        $('[data-toggle="popover"]').popover(); 
        //$.AdminBSB.input.activate();
    })
    $(document).on('click', function (e) {
        $('[data-toggle="popover"],[data-original-title]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
                (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
            }

        });
    });
    $(document).on('click','#printClearance',function(e){
        e.preventDefault();
        PrintElem("clearance-div");
    })
    $(document).on('keypress keyup keydown','form #amount',function(e){
        $('form #balance').val($(this).val())
    })
    $(document).on('change','#pay_basis ',function(e){
        pay_basis = $(this).val();
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function(){
            $.AdminBSB.select.activate();  
        })
    })
    /*$(document).on('change','#division_id ',function(e){
        division_id = $(this).val();
        $.when(
            getFields.periodweeklycutoff(division_id)
        ).done(function(){
            $.AdminBSB.select.activate();  
        })
    });*/
    const addCommas = (x) => {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    //Ajax non-forms
    $(document).on('click','.viewLeaveLedgerForm',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        employee_id = my.data('employee_id')
        year = $('.search_entry #search_year').val()
        month = $('.search_entry #month').val()
        division_id = $('.search_entry #division_id').val();
        location_id = $('.search_entry #location_id').val();
        pay_basis = "Permanent-Casual";
        month = $('.search_entry #month').val();
        year = $('.search_entry #search_year').val();
        //console.log(me.data())
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id,location_id:location_id,division_id:division_id,employee_id:employee_id, month:month, year:year, pay_basis:pay_basis},
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'viewLeaveLedger':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            modal_title = my.data("employee_id_number")+" - "+my.data("first_name")+ " "+ my.data("middle_name") +" " + my.data("last_name")
                            $('#myModal .modal-title').html(modal_title);
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(my.data(),function(i,v){
                                $('.'+i).val(addCommas(v));

                            })
                            console.log(addCommas(my.data("salary")));
                            $("form input:not(:button),form input:not(:submit)").attr("disabled", true);
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
    $(document).on('click','#viewLeaveLedgerSummary',function(e){
        e.preventDefault();
        division_id = $('.search_entry #division_id').val();
        pay_basis = "Permanent-Casual";
        month = $('.search_entry #month').val();
        year = $('.search_entry #search_year').val();
        //console.log(me.data())
        if (month == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select a month.'
            });
        } else if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Department.'
            });
        } else if (year == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select a year.'
            });
        } else {
            // getFields.employeeModal(pay_basis,location_id,division_id,null,null,leave_grouping_id)
            content = 0;
            location_id = null;
            division_id = null;
            payroll_grouping_id = null;
            specific = null;
            getFields.reloadModal();
            lengthmenu = [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ];
            if(content == 1){
                lengthmenu = [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ];
            }
            // alert(division_id);
            $.ajax({
                type: "POST",
                url: commons.baseurl + "leavemanagement/LeaveLedger/getEmployeeListExhausted",
                data: {
                    pay_basis: pay_basis,
                    location_id:location_id,
                    division_id: division_id,
                    specific: specific,
                    year: year,
                    month: month
                },
                dataType: "json",
                success: function (result) {
                    if (result.hasOwnProperty("key")) {
                        switch (result.key) {
                            case 'viewEmployees':
                                $('#myModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
                                $('#myModal .modal-title').html("Please Select Employee/s");
                                $('#myModal .modal-body').html(result.table);
                                // alert('yo')
                                $('#module').DataTable({
                                    searching: true,
                                    responsive: true,
                                    destroy: true,
                                    aaSorting: [],
                                    "columnDefs": [ {
                                      "targets"  : [0],
                                      "orderable": false
                                    }],
                                    "lengthMenu": lengthmenu,
                                    /*"info": false,
                                    "paging": true,
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ],
                                    
                                    dom: 'tp',
                                    language: {
                                        search: "_INPUT_",
                                        searchPlaceholder: "Search records",
                                    }*/
                                });
                                
                                break;
                        }
                        setTimeout(function () {
                            $('#print-all-preloader').hide()
                        }, 1000);
                    }
                },
                error: function (result) {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: 'There was an error in the connection. Please contact the administrator for updates.'
                    });
                }
            });
        }
        
    })
    
    //View Payroll Register
    $(document).on('click','#submitUserCheckList',function(e){
        e.preventDefault();
        division_id = $('.search_entry #division_id').val();
        pay_basis = "Permanent-Casual";
        month = $('.search_entry #month').val();
        year = $('.search_entry #search_year').val();
        myBtn = $(this)
        $(this).attr('disabled','disabled')
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: 'Are you sure you want to process leave ledger?',
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        var progressbar =   '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-addedval="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
                                            '0% Complete (success)' +
                                            '</div></div>';
                        total_data = $(".checkbox_table:checked").length;
                        if(total_data > 0){
                            $('#myModal #progress-container').html(progressbar);
                            $('.progress-bar').focus();
                            $.ajax({
                                type: "POST",
                                url: commons.baseurl+ "leavemanagement/LeaveLedger/leaveledgerContainer",
                                dataType: "json",
                                success: function(result){
                                    if(result.hasOwnProperty("key")){
                                        switch(result.key){
                                            case 'leaveledgerContainer':
                                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                                modal_title = "Leave Ledger Summary"
                                                $('#myModal .modal-title').html(modal_title);
                                                $('#myModal .modal-body').append(result.form);
                                                $.each($(".checkbox_table:checked"), function (k, v) {
                                                    employee_id = $(this).attr('data-id');
                                                    queue = getEmployeeLeaveLedger(location_id,division_id,employee_id,month,year,k+1,total_data);
                                                    // console.log(queue)
                                                    if(total_data == (k+1)){
                                                        // alert(total_data+" : "+ (k+1))
                                                        // Complete Loading
                                                        $("#employee-checklists").remove();
                                                    }
                                                });
                                                break;
                                        }
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
                        else{
                            $.alert({
                                title: '<label class="text-danger">Failed</label>',
                                content: 'Please select Employees.'
                            });
                            myBtn.removeAttr('disabled')
                        }
                        
                    }

                },
                cancel: function () {
                    myBtn.removeAttr('disabled')
                }
            }
        });
    })
    function getEmployeeLeaveLedger(location_id,division_id,employee_id,month,year,queue,total_data){
        url2 = commons.baseurl+ "leavemanagement/LeaveLedger/viewLeaveLedgerSummary/";
        $.ajax({
            type: "POST",
            url: url2,
            data: {location_id:location_id,division_id:division_id,employee_id:employee_id,month:month,year:year},
            dataType: "json",
            success: function(result){
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'viewLeaveLedgerSummary':
                            $('#myModal #certificate-container').append(result.form);
                            queue += 1;
                            progress = parseInt($('.progress-bar').attr("aria-addedval")) + 1;
                            percentage = ((total_data - (total_data -  progress)) / total_data) * 100 
                            /*console.log(percentage);
                            console.log(progress);*/
                            $('.progress-bar').attr("aria-valuenow",percentage)
                            $('.progress-bar').attr("aria-addedval",progress);
                            $('.progress-bar').css("width",percentage+"%");
                            $('.progress-bar').html(""+percentage.toFixed() + "% Complete (success)");
                            break;
                    }
                }
            },
            error: function(result){
                // $.alert({
                //     title:'<label class="text-danger">Failed</label>',
                //     content:'There was an error in the connection. Please contact the administrator for updates.'
                // });
                queue = false;
            }
        });
        return queue;
    }
    //Ajax Forms
    $(document).on('click','#computePayroll',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        month = $('.search_entry #month').val()
        year = $('.search_entry #search_year').val()
        // division_id = $('.search_entry #division_id').val() 
        // location_id = $('.search_entry #location_id').val() 
        division_id = $('.search_entry #division_id').val() 
        if(division_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select Department.'
            });
            return false;
        }
        if(month == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a month.'
            });
            return false;
        }
        if(year == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a year.'
            });
            return false;
        }
        // if(division_id == ""){
        //     $.alert({
        //         title:'<label class="text-danger">Failed</label>',
        //         content:'Please select payroll period.'
        //     });
        //     return false;
        // }
        // if(location_id == ""){
        //     $.alert({
        //         title:'<label class="text-danger">Failed</label>',
        //         content:'Please select location.'
        //     });
        //     return false;
        // }
        // plus_url = '?PayBasis='+pay_basis+'&Division='+division_id+'&Location='+location_id
        plus_url = '?Division='+division_id+'&Month='+month+'&Year='+year
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(result){
                $('#table-holder').html(result.table);
                table = $('#datatables').DataTable({  
                       "processing":true,  
                       "serverSide":true,  
                       "order":[],  
                       "ajax":{  
                            url:commons.baseurl+ "leavemanagement/LeaveLedger/fetchRowsExhausted"+plus_url,  
                            type:"POST"  
                       },  
                       "columnDefs":[  
                            {  
                                "targets": [0],
                                "orderable":false,  
                            },  
                       ],  
                });
                button =    '<a id="viewLeaveLedgerSummary" href="'+commons.baseurl+'leavemanagement/LeaveLedger/viewLeaveLedgerSummary">'
                            +    '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">'
                            +        '<i class="material-icons">people</i> Leave Ledger Summary'
                            +    '</button>'
                            +'</a>'
                 $('#table-holder .button-holder').html(button)
            },
            error: function(result){
                $.alert({
                    title:'<label class="text-danger">Failed</label>',
                    content:'There was an error in the connection. Please contact the administrator for updates.'
                });
            }
        });
    })
    function loadTable(){
        //employment_status = $('#hide_emp_status').val();
        plus_url = "";
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
        table = $('#datatables').DataTable({  
               "processing":true,  
               "serverSide":true,  
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "leavemanagement/LeaveLedger/fetchRowsExhausted"+plus_url,  
                    type:"POST"  
               },  
               "columnDefs":[  
                    {  
                         "orderable":false,  
                    },  
               ],  
        });
    }
    function reloadTable(){
        //table.ajax.reload();
        employee_id = $('.search_entry #employee_id').val()
        month = $('.search_entry #month').val()
        year = $('.search_entry #search_year').val()
        plus_url = '?EmployeeId='+employee_id+'&Month='+month+'&Year='+year
        if(employee_id == ""){
            return false;
        }
        $("#searchEmployeePayroll").click();
    }
    // console.log(commons.baseurl)
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