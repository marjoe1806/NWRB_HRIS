$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    // loadTable();
    $.when(
        getFields.division(),
        getFields.bonuses()
    ).done(function(){
        $.AdminBSB.select.activate();  
    })

	$.when(getFields.payBasis3(),getFields.division()).done(function () {
		// $("#division_id option:first").text("All");
		$.AdminBSB.select.activate();
	});

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
		printPrev(document.getElementById("clearance-div").innerHTML);
    })
    $(document).on('keypress keyup keydown','form #amount',function(e){
        $('form #balance').val($(this).val())
    })
    $(document).on('change', '#pay_basis ', function (e) {
        pay_basis = $(this).val();
        $('#payroll_type').parent().parent().parent().parent().hide();
        if(pay_basis == "Permanent"){
            $('#payroll_type').parent().parent().parent().parent().show();

        } 
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
    /*$(document).on('click','#viewSpecialReportsSummary',function(e){
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
    //View Special Reports
    $(document).on('click','#viewSpecialReportsSummary',function(e){
        e.preventDefault();
        my = $(this)
        url = my.attr('href');
        year = $('.search_entry #year').val();
        pay_basis = $('.search_entry #pay_basis').val();
        bonus_type = $('.search_entry #bonus_type').val();
        select_type = $('.search_entry #type').val();
        month = $('.search_entry #month_select').val();
        division_id = $('.search_entry #division_id').val();
        payroll_grouping_id = $('.search_entry #payroll_grouping_id').val();
        uses_atm = $('.search_entry #uses_atm').val();
        is_initial_salary = $('.search_entry #is_initial_salary').val();
        payroll_type = $('.search_entry #payroll_type').val();
       // console.log(pay_basis)

       var division_name;
       if(division_id == 1){
           division_name = "EDO";
       }else if(division_id == 2){
           division_name = "DEDO";
       }
       else if(division_id == 3){
           division_name = "PPD";
       }
       else if(division_id == 4){
           division_name = "MED";
       }
       else if(division_id == 5){
           division_name = "WUD";
       }
       else if(division_id == 6){
           division_name = "WRD";
       }
       else if(division_id == 7){
           division_name = "AFD";
       }
       else if(division_id = 11){
           division_name = "CEO";
       }
       else if(division_id == 12){
           division_name = "DEO";
       }
       else if(division_id == 13){
           division_name = "BAC";
       }
       else if(division_id == 14){
           division_name = "NEWLY HIRED";
       }

        if (division_id == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Division.'
            });
        } else if (year == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Year.'
            });
        } else if (select_type == "") {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select Type.'
            });
        }else if(select_type == "Overtime" && pay_basis == ""){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select pay basis.'
            }); 
        }else if(select_type == "Overtime" && month == ""){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select month.'
            });
        
        }else if(select_type == "Bonus" && bonus_type == ""){
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: 'Please select bonus type.'
            });
        }
         else {
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

            modal_title = "Special Reports"
            $('#myModal .modal-title').html(modal_title);
            $('#myModal .modal-body').html(loader);
            $('#myModal').modal('show');
            $.ajax({
                type: "POST",
                url: commons.baseurl+ "annualreports/SpecialReports/viewSpecialReportsSummary",
                data: {
                    year: year,
                    pay_basis: pay_basis,
                    bonus_type: bonus_type,
                    division_id: division_id,
                    month: month,
                    select_type: select_type,
                    division_name: division_name
                },
                dataType: "json",
                success: function(result){
                    page = my.attr('id');
                    if(result.hasOwnProperty("key")){
                        switch(result.key){
                            case 'viewSpecialReportsSummary':
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

    $(document).on('change', '#type', function(e){
        var value = $(this).val();

        if(value == "Bonus"){
            $('#bonus').show();
            $('#month').hide();
            $('#pay_basis_select').hide();
        }else{
            $('#bonus').hide();
            $('#month').show();
            $('#pay_basis_select').show();
        }
    });
    //Ajax Forms
    /*function loadTable(){
        plus_url = ""
        
        table = $('#datatables').DataTable({  
               "processing":true,  
               "serverSide":true,  
               "order":[],  
               "ajax":{  
                    url:commons.baseurl+ "annualreports/SpecialReports/fetchRows"+plus_url,  
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