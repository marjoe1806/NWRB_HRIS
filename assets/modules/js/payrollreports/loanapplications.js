$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
    $.when(
        getFields.payBasis3()
    ).done(function(){
        $.AdminBSB.select.activate();  
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
    const addCommas = (x) => {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    //Ajax non-forms
    $(document).on('click','#viewLoanApplicationsSummary',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        payroll_period_id = $('.search_entry #payroll_period_id').val();
        pay_basis = $('.search_entry #pay_basis').val();
        //console.log(me.data())
        $.ajax({
            type: "POST",
            url: url,
            data: {payroll_period_id:payroll_period_id,pay_basis:pay_basis},
            dataType: "json",
            success: function(result){
                page = my.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'viewLoanApplicationsSummary':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            modal_title = "Loan Applications Summary"
                            $('#myModal .modal-title').html(modal_title);
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
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
    //Ajax Forms
    $(document).on('click','#computePayroll',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        pay_basis = $('.search_entry #pay_basis').val()
        payroll_period_id = $('.search_entry #payroll_period_id').val() 
        if(pay_basis == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select pay basis.'
            });
            return false;
        }
        if(payroll_period_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select payroll period.'
            });
            return false;
        }
        plus_url = '?PayBasis='+pay_basis+'&PayrollPeriod='+payroll_period_id
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
                            url:commons.baseurl+ "payrollreports/LoanApplications/fetchRows"+plus_url,  
                            type:"POST"  
                       },  
                       "columnDefs":[  
                            {  
                                "targets": [0],
                                "orderable":false,  
                            },  
                       ],  
                });
                button =    '<a id="viewLoanApplicationsSummary" href="'+commons.baseurl+'payrollreports/LoanApplications/viewLoanApplicationsSummary">'
                            +    '<button type="button" class="btn btn-block btn-lg btn-success waves-effect">'
                            +        '<i class="material-icons">description</i> Loan  Applications Summary'
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
        plus_url = ""
        /*if(employment_status != ""){
            plus_url = '?EmploymentStatus='+employment_status;
        }*/
        table = $('#datatables').DataTable({  
               "processing":true,  
               "serverSide":true,  
               "stateSave": true, // presumably saves state for reloads -- entries
               "bStateSave": true, // presumably saves state for reloads -- page number
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "payrollreports/LoanApplications/fetchRows"+plus_url,  
                    type:"POST"  
               },  
               "columnDefs":[  
                    {  
                        "targets": [0],
                        "orderable":false,  
                    },  
               ],  
        });
    }
    function reloadTable(){
        //table.ajax.reload();
        employee_id = $('.search_entry #employee_id').val()
        plus_url = '?EmployeeId='+employee_id
        if(employee_id == ""){
            return false;
        }
        $("#searchEmployeePayroll").click();
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