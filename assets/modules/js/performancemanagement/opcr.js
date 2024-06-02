$(document).ready(function(){
    var baseURL = commons.baseurl;
    var count_strat = 1;
    var count_core = 1;
    var count_support = 1;

    $(".opcr_date").inputmask("yyyy-mm-dd", {placeholder: "yyyy-mm-dd",});
    $.when(
        getFields.division(),
        getFields.positionCode()
    ).done(function () {
        $.AdminBSB.select.activate();
        $("#division_id").prop('required',true);
    })
    $('#opcr_form').validate();

    function addCellStrat(thisrow){
        var append = `
            <tr>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="strat_major[${count_strat}]" aria-invalid="false" required>
                        </div>
                    </div>
                </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="strat_success[${count_strat}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="strat_alloted[${count_strat}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="strat_office[${count_strat}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="strat_actual[${count_strat}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <center>

                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_strat" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Add row"> 
                            <i class = "fa fa-plus"></i>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_strat" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Delete row"> 
                            <i class = "fa fa-minus"></i>
                        </button>   
                    </center>    
                </td>
            </tr>
        `;

        $(append).insertAfter(thisrow.closest('tr'));
        
        count_strat++;
    }

    function addCellCore(thisrow){
        var append = 
        `
            <tr>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="core_major[${count_core}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="core_success[${count_core}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="core_alloted[${count_core}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="core_office[${count_core}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="core_actual[${count_core}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <center>
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_core" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Add row"> 
                            <i class = "fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_core" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Delete row"> 
                            <i class = "fa fa-minus"></i>
                        </button>   
                    </center>    
                </td>
            </tr>
        `;
        $(append).insertAfter(thisrow.closest('tr'));

        count_core++;
    }

    function addCellSupport(thisrow){
        var append = 
        `
            <tr>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="support_major[${count_support}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="support_success[${count_support}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="support_alloted[${count_support}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="support_office[${count_support}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control is_first_col_required " name="support_actual[${count_support}]" aria-invalid="false" required>
                        </div>
                    </div>
                </td>
                <td>
                    <center>
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_support" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Add row"> 
                            <i class = "fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_support" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Delete row"> 
                            <i class = "fa fa-minus"></i>
                        </button>    
                    </center>    
                </td>
            </tr>
        `;

        $(append).insertAfter(thisrow.closest('tr'));

        count_support++;
    }

    $(document).on('change','#division_id',function(e){
        var division_id = $(this).val();
        var data = {
            division_id:division_id
        };

        $.when(
            getFields.employee(data)
        ).done(function () {
            $.AdminBSB.select.activate();
        })
    })

    $(document).on("click", ".add_strat", function (e) {
        e.preventDefault();
        $(this).hide();
        var thisrow = $(this);
        addCellStrat(thisrow);    
    });

    $(document).on("click", ".remove_strat", function (e) {
        e.preventDefault();
        $(this).blur()
        var table = $('.table-style tbody tr');
        var start;
        var end; 
        var total = 0;
        $.each(table,function(k,v){
            if($(v).hasClass('strat')){
                start = k;
            }
            if($(v).hasClass('core')){
                end = k;
            }
            if(k == end){                
                return false;
            }
            if(k != start){
                total++;
            }
        })

        if(total == 1){
            $(this).closest('tr').find('input').val('');
        }
        else if($(this).closest("tr").is(":last-child")){
            $(this).closest('tr').prev('tr').find('td:eq(5)').html(
                `
                <center>
                    <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_strat" 
                            data-toggle="tooltip" 
                            data-placement="top" 
                            title="" 
                            data-original-title="Add row"> 
                        <i class = "fa fa-plus"></i>
                    </button>
                    <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_strat" 
                            data-toggle="tooltip" 
                            data-placement="top" 
                            title="" 
                            data-original-title="Delete row"> 
                        <i class = "fa fa-minus"></i>
                    </button>  
                </center> 
                `
            );
            $(this).closest('tr').remove();
        }
        else{
            $(this).closest('tr').remove();
        }
    });

    $(document).on("click", ".add_core", function (e) {
        e.preventDefault();
        $(this).blur()
        $(this).hide();
        var thisrow = $(this);
        addCellCore(thisrow);  
    });

    $(document).on("click", ".remove_core", function (e) {
        e.preventDefault();
        $(this).blur()
        var table = $('.table-style tbody tr');
        var start;
        var end; 
        var total = 0;
        $.each(table,function(k,v){
            if($(v).hasClass('core')){
                start = k;
            }
            if($(v).hasClass('support')){
                end = k;
            }
            if(k == end){                
                return false;
            }
            if(k != start && start < k){
                total++;
            }
        })

        if(total == 1){
            $(this).closest('tr').find('input').val('');
        }
        else if($(this).closest("tr").is(":last-child")){
            $(this).closest('tr').prev('tr').find('td:eq(5)').html(
               `
               <center>
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_core" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Add row"> 
                            <i class = "fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_core" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="" 
                                data-original-title="Delete row"> 
                            <i class = "fa fa-minus"></i>
                        </button>   
                    </center>
               `
            );
            $(this).closest('tr').remove();
        }else{
            $(this).closest('tr').remove();
        }
    });

    $(document).on("click", ".add_support", function (e) {
        e.preventDefault();
        $(this).blur()
        $(this).hide();  
        var thisrow = $(this);
        addCellSupport(thisrow);    
    });

    $(document).on("click", ".remove_support", function (e) {
        e.preventDefault();
        $(this).blur()
        var table = $('.table-style tbody tr');
        var start;
        var end = $('.table-style tbody tr').length -1; 
        var total = 0;
        $.each(table,function(k,v){
            /*if($(v).hasClass('support')){
                start = k;
            }
            if(k != start && start < k){
                total++;
            }*/
            if($(v).hasClass('support')){
                start = k;
            }
            if($(v).hasClass('subtotal_support')){
                end = k;
            }
            if(k == end){                
                return false;
            }
            if(k != start && start < k){
                total++;
            }
        })

        if(total == 1){
            $(this).closest('tr').find('input').val('');
        }
        else if($(this).closest("tr").is(":last-child")){
            $(this).closest('tr').prev('tr').find('td:eq(5)').html(
               `
               <center>
                <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_support" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="" 
                        data-original-title="Add row"> 
                    <i class = "fa fa-plus"></i>
                </button>
                <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_support" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="" 
                        data-original-title="Delete row"> 
                    <i class = "fa fa-minus"></i>
                </button>    
                </center>
               `
            );
            $(this).closest('tr').remove();
        }
        else{
            $(this).closest('tr').remove();
        }
    });

    $(document).on("submit", "#opcr_form", function (e) {
        e.preventDefault();
        $(this).blur();
        var url = baseURL + 'performancemanagement/Opcr/add_form';
        var data = $('#opcr_form').serializeArray();
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
                                                location.reload()
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
});