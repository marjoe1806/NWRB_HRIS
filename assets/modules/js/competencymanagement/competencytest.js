$(function(){
    var page = "";
    base_url = commons.base_url;
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            maxDate: new Date(),
            time: false
        });
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    // $(document).on('click','#printCompetencyTest',function(e){
    //     e.preventDefault();
    //     PrintElem("servicerecords-container");
    // })

    // Add Question button click event
    $(document).on("click", ".btnAddQuestion", function() {
        var dataType = $(this).data("type");
        var currentSequence = parseInt($("."+dataType).last().find(".order_no").val());
        cloneQuestionElement(currentSequence,dataType);
        
        // Check if the number of "Add Question" buttons is greater than one
        if ($(".btnAddQuestion").length > 1) {
            // If there are more than one "Add Question" buttons, remove the current button
            $(this).remove();
        }
    });

    // Function to clone the question element
    function cloneQuestionElement(currentSequence, dataType) {
        var clonedQuestion = $("."+dataType).last().clone(false).find(".div_choices").remove().end();
        clonedQuestion.find("input, textarea").each(function() {
            // Check if the element is visible and not the specific hidden element
            if (!$(this).is('input[type="hidden"]')) {
                // Clear the value if the element is visible and not the specific hidden element
                $(this).val("");
            }
        });

        // Get the last index from the name attribute of the last .div_multiple element
        var lastIndex = getLastIndex(dataType);
        var updatedType = dataType.replace("div_", "");
    
        // Update the name attribute of "sequence", "question", and "answer" input for uniqueness with the next index
        var newIndex = lastIndex + 1;
        clonedQuestion.find(".order_no").attr("name", "order_no["+updatedType+"][" + newIndex + "]");
        clonedQuestion.find(".sequence").attr("name", "sequence["+updatedType+"][" + newIndex + "]");
        clonedQuestion.find(".points").attr("name", "points["+updatedType+"][" + newIndex + "]");
        clonedQuestion.find(".question").attr("name", "question["+updatedType+"][" + newIndex + "]");
        clonedQuestion.find(".answer").attr("name", "answer["+updatedType+"][" + newIndex + "]");
        // Update the .sequence value for the cloned element
        clonedQuestion.find(".order_no").val(currentSequence + 1);
        if(updatedType == 'multiple') clonedQuestion.find(".sequence").val(currentSequence + 1);
        $("."+dataType).last().after(clonedQuestion);
    }

    function getLastIndex(dataType) {
        var lastIndex = 0;
        $("."+dataType).each(function () {
            var nameAttribute = $(this).find(".question").attr("name");
            if (nameAttribute) {
                var match = nameAttribute.match(/\[(\d+)\]/);
                if (match && parseInt(match[1]) > lastIndex) {
                    lastIndex = parseInt(match[1]);
                }
            }
        });
        return lastIndex;
    }

    // jQuery code to clone the choices element when the button is clicked
    $(document).on('click', '.btnAddChoices', function(e) {
        e.preventDefault();
        // Get the last index from the name attribute of the last .div_multiple element
        var lastIndex = $(this).closest(".div_multiple").find(".order_no").val();
        lastIndex -= 1;
        // This is the HTML code you want to append
        var htmlChoices = '<div class="col-md-6 div_choices"> ' +
        '<div class="form-group"> ' +
        '<div class="form-line">' +
        '<input type="text" name="choices['+ lastIndex +'][]" id="choices[]" class="choices form-control" required' +
        '</div>' +
        '</div>' +
        '</div>';

        // Append the HTML to the parent container with class "container"
        $(this).closest(".div_multiple").find(".container_choices").append(htmlChoices);
    });
    
    //Confirms
    $(document).on('click','.activateCompetencyTest,.deactivateCompetencyTest',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateCompetencyTest')){
            content = 'Are you sure you want to activate selected competency test?';
        }
        else if(me.hasClass('deactivateSubCompetencyTest')){
            content = 'Are you sure you want to deactivate selected sub competency test?';
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
                                                    case 'activateCompetencyTest':
                                                    case 'deactivateCompetencyTest':
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
    //Ajax non-forms
    $(document).on('click','#addCompetencyTestForm,.updateCompetencyTestForm',function(e){
        e.preventDefault();
        me = $(this)
        id = me.attr('data-id');
        url = me.attr('href');  
        id = me.data("id");
        exam_type = me.data("exam_type");
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = me.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'addCompetencyTest':
                        case 'updateCompetencyTest':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            if(result.key == 'addCompetencyTest'){
                                $('#myModal .modal-title').html('Duplicate Competency Test');
                            }else{
                                $('#myModal .modal-title').html('Update Competency Test');
                            }
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            $.each(me.data(),function(i,v){
                                $('.'+i).val(me.data(i)).change();
                            });
                            $.post(
                                commons.baseurl + "competencymanagement/CompetencyTest/getQuestions", {
                                    id: id
                                },
                                function(result) {
                                    result = JSON.parse(result);
                                    if (result.Code == "0") {
                                        var enumeration = result.Data.enumeration;
                                        var fill = result.Data.fill;
                                        var essay = result.Data.essay;
                                        var multiplechoice = result.Data.multiplechoice;
                                        if (enumeration.length > 0) {
                                            // Empty the container before appending new questions and choices
                                            $(".form-elements-container-enumeration").empty();
                                            // Create a new question div with the appropriate class and append it to the form container
                                            
                                            $.each(enumeration, function(k, v) {
                                                var newQuestionDiv = '<div class="row clearfix div_enumeration" style="border: 0px solid gray; padding: 5px;">';
                                                newQuestionDiv += '<input type="hidden" name="order_no[enumeration][' + k + ']" class="order_no" value="' + (v.order_no) + '" required>';
                                                // Create a new question div with the appropriate class and append it to the form container
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Sequence No. <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="sequence[enumeration][' + k + ']" class="sequence form-control" value="' + (v.sequence) + '" required>';
                                                newQuestionDiv += '</div></div></div>';

                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Points <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="number" name="points[enumeration][' + k + ']" class="points form-control" min="1" value="' + (v.points) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-6"><label class="form-label">Question <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<textarea name="question[enumeration][' + k + ']" rows="1" class="required question form-control">' + (v.question) + '</textarea>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Possible Answer <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="answer[enumeration][' + k + ']" class="answer form-control" value="' + (v.answer) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                                // Add the "Add Question" button in the last loop only
                                                if (k === enumeration.length - 1) {
                                                    newQuestionDiv += '<div><button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_enumeration" style="float: right; margin: 20px;"><i class="material-icons">add</i> Add Question</button></div>';
                                                }
                                                $(".form-elements-container-enumeration").append(newQuestionDiv);
                                            });
                                        }
                                        
                                        if (fill.length > 0) {
                                            // Empty the container before appending new questions and choices
                                            $(".form-elements-container-fill").empty();
                                            // Create a new question div with the appropriate class and append it to the form container
                                            
                                            $.each(fill, function(k, v) {
                                                var newQuestionDiv = '<div class="row clearfix div_fill" style="border: 0px solid gray; padding: 5px;">';
                                                newQuestionDiv += '<input type="hidden" name="order_no[fill][' + k + ']" class="order_no" value="' + (v.order_no) + '" required>';
                                                // Create a new question div with the appropriate class and append it to the form container
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Sequence No. <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="sequence[fill][' + k + ']" class="sequence form-control" value="' + (v.sequence) + '" required>';
                                                newQuestionDiv += '</div></div></div>';

                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Points <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="number" name="points[fill][' + k + ']" class="points form-control" min="1" value="' + (v.points) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-6"><label class="form-label">Question <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<textarea name="question[fill][' + k + ']" rows="1" class="required question form-control">' + (v.question) + '</textarea>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Possible Answer <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="answer[fill][' + k + ']" class="answer form-control" value="' + (v.answer) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                                // Add the "Add Question" button in the last loop only
                                                if (k === fill.length - 1) {
                                                    newQuestionDiv += '<div><button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_fill" style="float: right; margin: 20px;"><i class="material-icons">add</i> Add Question</button></div>';
                                                }
                                                $(".form-elements-container-fill").append(newQuestionDiv);
                                            });
                                        }

                                        if (essay.length > 0) {
                                            // Empty the container before appending new questions and choices
                                            $(".form-elements-container-essay").empty();
                                            // Create a new question div with the appropriate class and append it to the form container
                                            
                                            $.each(essay, function(k, v) {
                                                var newQuestionDiv = '<div class="row clearfix div_essay" style="border: 0px solid gray; padding: 5px;">';
                                                newQuestionDiv += '<input type="hidden" name="order_no[essay][' + k + ']" class="order_no" value="' + (v.order_no) + '" required>';
                                                // Create a new question div with the appropriate class and append it to the form container
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Sequence No. <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="sequence[essay][' + k + ']" class="sequence form-control" value="' + (v.sequence) + '" required>';
                                                newQuestionDiv += '</div></div></div>';

                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Points <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="number" name="points[essay][' + k + ']" class="points form-control" min="1" value="' + (v.points) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-8"><label class="form-label">Question <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<textarea name="question[essay][' + k + ']" rows="5" class="required question form-control">' + (v.question) + '</textarea>';
                                                newQuestionDiv += '</div></div></div>';
                                                // Add the "Add Question" button in the last loop only
                                                if (k === essay.length - 1) {
                                                    newQuestionDiv += '<div><button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_essay" style="float: right; margin: 20px;"><i class="material-icons">add</i> Add Question</button></div>';
                                                }
                                                $(".form-elements-container-essay").append(newQuestionDiv);
                                            });
                                        }

                                        if (multiplechoice.length > 0) {
                                            // Empty the container before appending new questions and choices
                                            $(".form-elements-container-multiple").empty();
                                            // Create a new question div with the appropriate class and append it to the form container
                                            $.each(multiplechoice, function(k, v) {
                                                var newQuestionDiv = '<div class="row clearfix div_multiple" style="border: 0px solid gray; padding: 5px;">';
                                                newQuestionDiv += '<input type="hidden" name="order_no[multiple][' + k + ']" class="order_no" value="' + (v.order_no) + '" required>';
                                                // Create a new question div with the appropriate class and append it to the form container
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label">Sequence No. <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="sequence[multiple][' + k + ']" class="sequence form-control" value="' + (v.sequence) + '" required readonly>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-6"><label class="form-label">Question <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<textarea name="question[multiple][' + k + ']" rows="1" class="required question form-control">' + (v.question) + '</textarea>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-4"><label class="form-label">Possible Answer <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<div class="form-group"><div class="form-line">';
                                                newQuestionDiv += '<input type="text" name="answer[multiple][' + k + ']" class="answer form-control" value="' + (v.answer) + '" required>';
                                                newQuestionDiv += '</div></div></div>';
                                
                                                newQuestionDiv += '<div class="col-md-2"><label class="form-label lbl_choices">Choices <span class="text-danger">*</span></label>';
                                                newQuestionDiv += '<button type="button" class="btn btn-primary btn-xs btnAddChoices" style="margin-left: 20px;"><i class="material-icons">add</i></button></div>';
                                                newQuestionDiv += '<div class="col-md-10 container_choices">';

                                                // Loop through choices and append each choice input field
                                                if (v.choices && v.choices.length > 0) {
                                                    $.each(v.choices, function(index, choice) {
                                                        // Assuming each choice object has a property called "choice_text"
                                                        var choiceText = choice;
                                                        newQuestionDiv += '<div class="col-md-6 div_choices">';
                                                        newQuestionDiv += '<div class="form-group">';
                                                        newQuestionDiv += '<div class="form-line">';
                                                        newQuestionDiv += '<input type="text" name="choices[' + k + '][]" class="choices form-control" value="' + choiceText + '" required>';
                                                        newQuestionDiv += '</div></div></div>';
                                                    });
                                                }

                                                newQuestionDiv += '</div>'; // Close the choices container
                                                // Add the "Add Question" button in the last loop only
                                                if (k === multiplechoice.length - 1) {
                                                    newQuestionDiv += '<div><button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_multiple" style="float: right; margin: 20px;"><i class="material-icons">add</i> Add Question</button></div>';
                                                }
                                                $(".form-elements-container-multiple").append(newQuestionDiv);
                                            });
                                        }
                                    }
                                }
                            );
                            break;
                    }
                    $("#"+result.key).validate({
                        rules:
                        {
                            ".required":
                            {
                                required: true
                            },
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
    $(document).on('submit','#addCompetencyTest,#updateCompetencyTest',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addCompetencyTest"){
            content = "Are you sure you want to add competency test?";
        }
        if(form.attr('id') == "updateCompetencyTest"){
            content = "Are you sure you want to update competency test?";
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
                                                        case 'addCompetencyTest':
                                                        case 'updateCompetencyTest':
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

    $(document).on("click", "#btnsearch", function(){
        loadTable();
    });

    function loadTable(){
        $("#datatables").DataTable().clear().destroy();
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
                url:commons.baseurl+ "competencymanagement/CompetencyTest/fetchRows?status="+$("#status").val(),
                type:"GET",
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
    // function PrintElem(elem)
    // {
    //     var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    //     mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
    //     mywindow.document.write('</head><body >');
    //     mywindow.document.write( document.getElementById(elem).innerHTML);
    //     mywindow.document.write('</body></html>');

    //     mywindow.document.close(); // necessary for IE >= 10
    //     mywindow.focus(); // necessary for IE >= 10*/

    //     mywindow.print();
    //     mywindow.close();

    //     return true;
    // }
})