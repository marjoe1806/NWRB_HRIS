$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    // loadTable();
    $.when(
        getFields.payBasis3(),
        getFields.location(),
        getFields.division(),
        getFields.payrollGrouping()
    ).done(function(){
    	$(".pay_basis option[value='Consultant']").remove();
        $(".pay_basis option[value='Congress']").remove();
        $('#pay_basis').selectpicker('refresh')
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
    $(document).on('change', '#pay_basis ', function (e) {
        pay_basis = $(this).val();
        $.when(
            getFields.payrollperiodcutoff(pay_basis)
        ).done(function () {
            $.AdminBSB.select.activate();
        })
    })
    const addCommas = (x) => {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    //Ajax non-forms
    /*$(document).on('click','#viewOvertimeRegisterSummary',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        payroll_period_id = $('.search_entry #payroll_period_id').val();
        pay_basis = $('.search_entry #pay_basis').val();
        //console.log(me.data())
        if (pay_basis == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Pay Basis.'
            });
        } else if (payroll_period_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Period.'
            });
        } else {
            getFields.employeeModal(pay_basis)
        }
        
    })*/
    //View Payroll Register
    $(document).on('click','#viewOvertimeRegisterSummary',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        payroll_period_id = $('.search_entry #payroll_period_id').val();
        pay_basis = $('.search_entry #pay_basis').val();
        payroll_grouping_id = $('.search_entry #payroll_grouping_id').val();
        //console.log(me.data())
        if (pay_basis == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Pay Basis.'
            });
        }  else if (payroll_grouping_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Payroll Group.'
            });
        } else if (payroll_period_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Period.'
            });
        } else {
            loader = '<div id="btn-loader" class="preloader pl-size-xl">'
            +    '<div class="spinner-layer pl-blue">'
            +        '<div class="circle-clipper left">'
            +            '<div class="circle"></div>'
            +        '</div>'
            +        '<div class="circle-clipper right">'
            +            '<div class="circle"></div>'
            +        '</div>'
            +    '</div>'
            +'</div>'

            modal_title = "Overtime Summary"
            $('#myModal .modal-title').html(modal_title);
            $('#myModal .modal-body').html(loader);
            $('#myModal').modal('show');
            $.ajax({
                type: "POST",
                url: commons.baseurl+ "payrollreports/OvertimeRegister/viewOvertimeRegisterSummary",
                data: {payroll_period_id:payroll_period_id,pay_basis:pay_basis,payroll_grouping_id:payroll_grouping_id},
                dataType: "json",
                success: function(result){
                    page = my.attr('id');
                    if(result.hasOwnProperty("key")){
                        switch(result.key){
                            case 'viewOvertimeRegisterSummary':
                                page="";
                                $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                                $('#myModal .modal-dialog').css('width','98%')
                                $('#myModal .modal-body').html(result.form);
                                $("form input:not(:button),form input:not(:submit)").attr("disabled", true);
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
    })
    //Ajax Forms
    /*function loadTable(){
        plus_url = ""
        
        table = $('#datatables').DataTable({  
               "processing":true,  
               "serverSide":true,  
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "payrollreports/OvertimeRegister/fetchRows"+plus_url,  
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
        employee_id = $('.search_entry #employee_id').val()
        plus_url = '?EmployeeId='+employee_id
        if(employee_id == ""){
            return false;
        }
        $("#searchEmployeePayroll").click();
    }*/
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