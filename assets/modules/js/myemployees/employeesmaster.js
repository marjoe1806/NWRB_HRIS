
var getEmployees = { 
    activate : function() {
        var temp = null;
        url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
        $.ajax({
            async: false,
            url: url2,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    options = '<option value=""></option>'
                    $.each(temp.Data.details, function(i,v){
                        middle_name = ""
                        if(v.middle_name != null && v.middle_name != "null" && v.middle_name != "NULL"){
                            middle_name1 = v.middle_name
                            middle_name = middle_name1.charAt(0)+'.'
                        }
                        options += '<option value="'+v.id+'">'+v.first_name+' '+middle_name+' '+v.last_name+'</option>'      
                    }) 
                    $('.employee_select').html(options)
                }
            }
        });  
        //return temp;
    },  
    activateModal : function() {
        var temp = null;
        url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
        $.ajax({
            async: false,
            url: url2,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    options = '<option value=""></option>'
                    $.each(temp.Data.details, function(i,v){
                        middle_name = ""
                        if(v.middle_name != null && v.middle_name != "null" && v.middle_name != "NULL"){
                            middle_name1 = v.middle_name
                            middle_name = middle_name1.charAt(0)+'.'
                        }
                        options += '<option value="'+v.id+'">'+v.first_name+' '+middle_name+' '+v.last_name+'</option>'      
                    }) 
                    $('#myModal .employee_select').html(options)
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });  
        //return temp;
    },
    activateModalPositions : function() {
        var temp = null;
        url2 = commons.baseurl + "employees/Employees/getPositions";
        $.ajax({
            async: false,
            url: url2,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    options = '<option value=""></option>'
                    $.each(temp.Data.details, function(i,v){
                        options += '<option value="'+v.name+'">'+v.name+'</option>'      
                    }) 
                    $('#myModal .position_select').html(options)
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });  
        //return temp;
    }

};