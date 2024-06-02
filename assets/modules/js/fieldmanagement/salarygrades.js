$(function(){
    var page = "";
    base_url = commons.base_url;
    var table;
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    $.when(
        getFields.payBasis3()
    ).done(function () {
        $.AdminBSB.select.activate();
    })
    $(document).on('click','#addNewGradeRow',function(e){
        e.preventDefault()
        first_row = $('#formTable').find('.first_row').html();
        console.log(first_row)
        index = $('#formTable tbody tr').length;
        $('#formTable tbody').append('<tr class="grade_'+index+'">'+first_row+'</tr>');
        $('.grade_'+index).find('#removeGradeRow').css('visibility','visible');
        $('.grade_'+index).find('.grade').attr('name','salary['+index+'][grade]');
        $('.grade_'+index).find('.grade').attr('id','grade'+index);
        $('.grade_'+index).find('.grade').val(index+1);
        $('.grade_'+index).find('.grade').attr('value',index+1);
        $('.grade_'+index +' .step').each(function(e){
            stepInd = $(this).attr('id').split('_');
            stepIndex = parseInt(stepInd[1])-1;
            $(this).attr('name','salary['+index+'][step]['+stepIndex+']');
            $(this).val("0.00")
        });
        
    })
    $(document).on('click','.removeGradeRow',function(e){
        e.preventDefault()
        $(this).closest('tr').remove();
    })
    $(document).on('click','#addNewStep',function(e){
        e.preventDefault();
        $('.removeStep').css('visibility','hidden');
        index = $("#formTable > thead > tr:first > th").length;
        $('#addNewGradeRow').parent().attr('colspan',index+1);
        n = index - 2;
        head = '<th class="step_added" nowrap valign="bottom" id="step_'+(n+1)+'">Step <span value="" class="step_value">'+(n+1)+'</span>'
                +   ' &emsp;&nbsp;<a class="removeStep" href="#"><i class="fa fa-times text-danger"></i></a>'
                +'</th>';
        $(this).closest('tr').find("th:eq("+n+")").after(head);
        count = 0;
        $('#formTable tbody').find('tr').each(function(){
            body    ='<td>'
                    +    '<div class="form-group">'
                    +        '<div class="form-line">'
                    +            '<input type="text" name="salary['+count+'][step]['+(n)+']" id="step_'+(n+1)+'" value="" class="step currency form-control" required>'
                    +        '</div>'
                    +    '</div>  '
                    +'</td>'
            $(this).find('td').eq(n).after(body);
            count+=1;
       });
    })
    $(document).on('click','.removeStep',function(e){
        e.preventDefault();
        my = $(this);
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: 'Are you sure you want to remove selected step?',
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        index = parseInt(my.parent().find('.step_value').html());
                        my.closest('tr').find("th:eq("+index+")").remove();
                        // alert($('#step_'+(index-1)).find('.removeStep').length)
                        if(index != 9){
                            $('#step_'+(index-1)).find('.removeStep').css('visibility','visible');
                        }
                        $("#formTable tbody tr").each(function() {
                            $(this).find("td:eq("+index+")").remove();

                        });
                    }

                },
                cancel: function () {
                }
            }
        });
        
    })
    //Confirms
    $(document).on('click','.activateSalaryGrades,.deactivateSalaryGrades',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateSalaryGrades')){
            content = 'Are you sure you want to activate selected salary grades??';
        }
        else if(me.hasClass('deactivateSubSalaryGrades')){
            content = 'Are you sure you want to deactivate selected sub salary grades??';
        }
        data = {id: id};
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
                                    data: {id:id},
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'activateSalaryGrades':
                                                    case 'deactivateSalaryGrades':
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
    //Ajax Forms
    $(document).on('submit','#addSalaryGrades,#updateSalaryGrades',function(e){
        e.preventDefault();
        var form = $(this)
        $('<input>').attr({
            type: 'hidden',
            id: 'pay_basis',
            name: 'pay_basis',
            class: 'pay_basis',
            value: $('#pay_basis').val()
        }).appendTo(form);
        $('<input>').attr({
            type: 'hidden',
            id: 'effectivity',
            name: 'effectivity',
            class: 'effectivity',
            value: $('#effectivity').val()
        }).appendTo(form);
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addSalaryGrades"){
            content = "Are you sure you want to save salary grades??";
        }
        if(form.attr('id') == "updateSalaryGrades"){
            content = "Are you sure you want to update salary grades??";
        }
        url = form.attr('action');
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
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'addSalaryGrades':
                                                        case 'updateSalaryGrades':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal .modal-body').html('');
                                                            $('#myModal').modal('hide');
                                                            loadTable();
                                                            break;
                                                    }
                                                }  
                                            }
                                            else{
                                                self.setContent(result.Message);
                                                self.setTitle('<label class="text-danger">Failed</label>');
                                            }
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
    $(document).on('change','#pay_basis',function(e){
        loadTable();
    })
    loadTable();
    function loadTable(){
        var url = commons.baseurl+"fieldmanagement/SalaryGrades/getActiveSalaryGradesSteps";
        pay_basis = "Permanent";//$('#pay_basis').val();
        effectivity = $('#effectivity').val();
        $.ajax({url: url,
            dataType:"json",
            data:{effectivity:effectivity,pay_basis:pay_basis}, 
            success: function(result){
                var grade = "";
                var step = 0;
                if(result.Code == "0"){
                    $('#formTable tbody').html('');
                    $('#datatables tbody').html('');
                    $('#datatables .step_head').remove();
                    $('#formTable .step_added').remove();
                    var steps = $('.step').length;

                    $.each(result.Data.details,function(i,v){
                        if(v.grade != grade){
                            
                            //Table
                            body =  '<tr class="grade_'+v.grade+'">'
                                 +  '<td class="text-info">'+v.grade+'</td>'
                                 +  '<td>'+v.salary+'</td>'
                                 +  '</tr>'  
                            $('#datatables tbody').append(body); 
                            if(v.grade == "1"){
                                head =  '<th class="step_head">Step '+v.step+'</th>';
                                $('#datatables thead tr').append(head); 
                            }
                            
                            //End Table
                            //Form
                            first_row = "";
                            if(v.grade == "1"){
                                first_row = "first_row";
                            }
                            body    =  '<tr class="grade_'+(parseInt(v.grade)-1)+' '+first_row+'">'
                                    +  '<td class="bg-info">'
                                    +       '<div class="form-group">'
                                    +           '<div class="form-line">'
                                    +               '<input type="text" name="salary['+(parseInt(v.grade)-1)+'][grade]" id="grade'+v.grade+'" class="grade currency2 form-control" value="'+v.grade+'" required readonly="">'
                                    +           '</div>'
                                    +       '</div>'  
                                    +  '</td>'
                                    +  '<td>'
                                    +       '<div class="form-group">'
                                    +           '<div class="form-line">'
                                    +               '<input type="text" name="salary['+(parseInt(v.grade)-1)+'][step]['+(parseInt(v.step)-1)+']" id="step_'+v.step+'" value="'+v.salary+'" class="step currency2 form-control" required>'
                                    +           '</div>'
                                    +       '</div>'
                                    +  '</td>'
                                    +  '</tr>'  
                            $('#formTable tbody').append(body);
                            //End Form
                            
                        }
                        else{
                            body = '<td>'+v.salary+'</td>'
                            $('#datatables tbody .grade_'+v.grade).append(body);
                            if(v.grade == "1"){
                                head =  '<th class="step_head">Step '+v.step+'</th>';
                                $('#datatables thead tr').append(head); 
                            }
                            //Form
                            body    =   '<td>'
                                    +       '<div class="form-group">'
                                    +           '<div class="form-line">'
                                    +               '<input type="text" name="salary['+(parseInt(v.grade)-1)+'][step]['+(parseInt(v.step)-1)+']" id="step_'+v.step+'" value="'+v.salary+'" class="step currency2 form-control" required>'
                                    +           '</div>'
                                    +       '</div>'
                                    +   '</td>'
                            $('#formTable tbody .grade_'+(parseInt(v.grade)-1)).append(body);
                            //End Form
                        }
                        grade = parseInt(v.grade)
                        step = parseInt(v.step)
                    })
                    for(i=9;i<=step;i++){
                        style = ""
                        if(i != step)
                            style = "visibility:hidden;"
                        head = '<th class="step_added" nowrap valign="bottom" id="step_'+(i)+'">Step <span value="" class="step_value">'+(i)+'</span>'
                            +   ' &emsp;&nbsp;<a class="removeStep" href="#" style="'+style+'"><i class="fa fa-times text-danger"></i></a>'
                            +'</th>';
                        $(head).insertBefore('.addStep');


                    }
                    for(i=1;i<=grade;i++){
                        style="";
                        if(i == 1)
                            style = "visibility:hidden;";
                        graderemover    =   '<td class="text-right remove-container">'
                                        +       '<button type="button" id="removeGradeRow" style="'+style+'" class="removeGradeRow btn btn-danger btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button>'
                                        +   '</td>'
                        $('#formTable .grade_'+(i-1)).append(graderemover);
                    }
                    $('.addGradeCon').attr('colspan',step+2)
                }
                else{
                    first_row = '<tr class="grade_0 first_row"> <td class="bg-info"> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][grade]" id="grade1" class="grade currency form-control" value="1" required readonly=""> </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][0]" id="step_1" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][1]" id="step_2" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][2]" id="step_3" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][3]" id="step_4" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][4]" id="step_5" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][5]" id="step_6" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][6]" id="step_7" value="" class="step currency form-control" required > </div> </div> </td> <td> <div class="form-group"> <div class="form-line"> <input type="text" name="salary[0][step][7]" id="step_8" value="" class="step currency form-control" required > </div> </div> </td> <td class="text-right remove-container"> <button type="button" id="removeGradeRow" style="visibility:hidden;" class="removeGradeRow btn btn-danger btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button> </td> </tr>';
                    $('#formTable tbody').html(first_row);
                    $('#datatables tbody').html('<td>&emsp;<label class="text-danger">No data available.</label></td>');
                    $('#datatables .step_head').remove();
                    $('#formTable .step_added').remove();

                }
            }
        });
    }
    
})