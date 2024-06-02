$(document).ready(function(){
    var baseURL = commons.baseurl;
    var count = 1;
    var count_strat = 1;
    var count_core = 1;
    var count_support = 1;
    $('#ipcr_form').validate();
    $(".ipcr_date").inputmask("yyyy-mm-dd", {placeholder: "yyyy-mm-dd",});
    $.when(
        getFields.division(),
        getFields.positionCode()
    ).done(function () {
        $.AdminBSB.select.activate();
        $("#division_id").prop('required',true);
    })
    
    function addRow(thisrow){
        let tmpCount = "";
        let key = thisrow.attr('data-key');

        var append = `
                <tr class="${key}" data-count="${eval(`count_${key}`)}">
                    <td>
                    <div class="form-group">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control is_first_col_required" name="${key}_output[${ eval(`count_${key}`)}]" aria-invalid="false" required>
                            </div>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="form-group">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control is_first_col_required" name="${key}_success_ind[${ eval(`count_${key}`)}]" aria-invalid="false" required>
                            </div>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="form-group">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control is_first_col_required" name="${key}_actual_accom[${ eval(`count_${key}`)}]" aria-invalid="false" required>
                            </div>
                        </div>
                    </div>
                    </td>
                    <td>
                    <center>
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add" 
                            data-toggle="tooltip" 
                            data-placement="top" 
                            data-key="${key}"
                            title="" 
                            type="button"
                            data-original-title="Add row"> 
                        <i class = "fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove" 
                            data-toggle="tooltip" 
                            data-placement="top"
                            data-key="${key}"
                            title="" 
                            type="button"
                            data-original-title="Delete row"> 
                        <i class = "fa fa-minus"></i>
                        </button>
                    </center>
                    </td>
                </tr>
            `;

        $(append).insertAfter(thisrow.closest('tr'));
        eval(`count_${key}++`);
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
    $(document).on("click", ".add", function (e) {
        e.preventDefault();
        $(this).blur();
        $(this).hide();
        var thisrow = $(this);
        addRow(thisrow);    
    });

    $(document).on("click", ".remove", function (e) {
        e.preventDefault();
        $(this).blur();
        if($(`#ipcr_table_second tbody .${$(this).attr("data-key")}`).length == 2 ){
            $(this).closest('tr').find('input').val('');
        }
        else if(!$(this).closest('tr').next('tr').hasClass($(this).attr("data-key")) && $(`#ipcr_table_second tbody .${$(this).attr("data-key")}`).length != 2){
            console.log("here+")
            $(this).closest('tr').prev('tr').find('td:eq(3)').html(
                `
                <center>
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add" 
                            data-toggle="tooltip" 
                            data-placement="top" 
                            data-key="${$(this).attr('data-key')}"
                            title="" 
                            type="button"
                            data-original-title="Add row"> 
                        <i class = "fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove" 
                            data-toggle="tooltip" 
                            data-placement="top"
                            data-key="${$(this).attr('data-key')}"
                            title="" 
                            type="button"
                            data-original-title="Delete row"> 
                        <i class = "fa fa-minus"></i>
                        </button>
                </center>
                `
            );
            $(this).closest('tr').remove();
        }
        else{
            console.log("eksena")
            $(this).closest('tr').remove();
        }
    });
    
    $(document).on("submit", "#ipcr_form", function (e) {
        e.preventDefault();
        
        var url = baseURL + 'performancemanagement/Ipcr/add_form';
        var data = $('#ipcr_form').serializeArray();

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