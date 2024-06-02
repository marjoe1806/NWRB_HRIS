$(function(){
    base_url = commons.base_url;
    var tbl_url = commons.baseurl+ "transactions/SpecialPayroll/getList"
    var save_url = commons.baseurl+ "transactions/SpecialPayroll/saveBonus"

    // initialize drop downs
    $.when(
        getFields.division(),
        getFields.bonuses()
    ).done(function(){
        $.AdminBSB.select.activate();  
    })

    // get list of employees with selected bonus amount
    $(document).on('click','#searchEmployeeBonus',function(e){
        e.preventDefault();

        year = $('.search_entry #year').val()
        division_id = $('.search_entry #division_id').val()
        bonus_type = $('.search_entry #bonus_type').val()

        if(division_id == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a division.'
            });
        } else if(bonus_type == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a type of bonus.'
            });
        } else if(year == ""){
            $.alert({
                title:'<label class="text-danger">Failed</label>',
                content:'Please select a year.'
            });
        } else {
            $.ajax({
                type : "POST",
                url : tbl_url,
                data : {
                    division_id : division_id,
                    bonus_type : bonus_type,
                    year : year
                },
                dataType : "json",
                success : function(result) {
                    if(result.data.length > 0) {
                        $('#table-holder').html(result.table).show();
                        $.alert({
                            title:'<label class="text-success">System Message</label>',
                            content:'Successfully fetched data.'
                        });
                        $('#datatables').DataTable();
                    } else {
                        $('#table-holder').hide();
                        $.alert({
                            title:'<label class="text-success">System Message</label>',
                            content:'No data found on selected bonus type and year.'
                        });
                    }
                },
                error: function(result){
                    $('#table-holder').hide();
                    $.alert({
                        title:'<label class="text-danger">Failed</label>',
                        content:'There was an error in the connection. Please contact the administrator for updates.'
                    });
                }
            });
        }
    })

    // cancel edit
    $(document).on('click','#cancelSpecialPayroll',function(e){
        e.preventDefault();

        var row = $(this).data("row");
        var amount = $(this).data("amount");
        var username = $(this).data("username");
        var date_modified = $(this).data("date_modified");

        $('.disp_amount'+row).show();
        $('.amount'+row).attr("readonly",true).css("border-color","#d7d3d3").removeClass("amount[]").hide();
        $('.amount'+row).val(amount);
        $('.username'+row).val(username);
        $('.date_modified'+row).val(date_modified);

        $(this).addClass("disabled");
        $(".saveSpecialPayroll"+row).addClass("disabled");
        $(".editSpecialPayroll"+row).removeClass("disabled");
    });

    // allow edit on amount field
    $(document).on('click','#editSpecialPayroll',function(e){
        e.preventDefault();

        var row = $(this).data("row");  
        $('.disp_amount'+row).hide();      
        $('.amount'+row).removeAttr("readonly",true).css("border-color","green").addClass("amount[]").show();
        
        $(this).addClass("disabled");
        $(".cancelSpecialPayroll"+row).removeClass("disabled");
        $(".saveSpecialPayroll"+row).removeClass("disabled");
    });

    // save amount of bonus
    $(document).on('click','#saveSpecialPayroll',function(e){
        e.preventDefault();

        var row = $(this).data("row");
        var id = $(this).data("id");     
        var amount = $(".amount"+row).val();

        var division_id = $("#division_id").val();
        var bonus_type = $("#bonus_type").val();
        var year = $("#year").val();    

        $.ajax({
            type : "POST",
            url : save_url,
            data : {
                employee_id : id,
                amount : amount,
                division_id : division_id,
                bonus_type : bonus_type,
                year : year
            },
            dataType : "json",
            success: function(result){
                console.log(result);
                if(result.Code == "0"){
                    reload_table();
                    $.alert({
                        title:'<label class="text-success">System Message</label>',
                        content: result.Message
                    });
                }
                else{
                    $.alert({
                        title:'<label class="text-danger">System Message</label>',
                        content: result.Message
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
    });

    // function to reload table
    function reload_table() {
        year = $('.search_entry #year').val()
        division_id = $('.search_entry #division_id').val()
        bonus_type = $('.search_entry #bonus_type').val()

        $.ajax({
            type : "POST",
            url : tbl_url,
            data : {
                division_id : division_id,
                bonus_type : bonus_type,
                year : year
            },
            dataType : "json",
            success : function(result) {
                $('#table-holder').html(result.table).show();
                $('#datatables').DataTable();
                console.log("Successfully reloaded table")
            },
            error: function(result){
                console.log("Failed to reload table")
            }
        });
    }

    //  prevents 2 dots in amount field
    $(document).on('keypress','.amount',function(e){
        if (e.keyCode === 46 && this.value.split('.').length === 2) 
            return false;
    });
})