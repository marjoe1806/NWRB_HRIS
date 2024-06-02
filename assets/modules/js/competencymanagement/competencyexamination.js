$(function(){
    if(validation.Code == 1){
      $.alert({
        title:'<label class="text-danger">Message</label>',
        content: validation.Message
      });
  
      $('.examinationFormBtn').hide();
      $('.examinationForm').hide();
    }else{
      $('.examinationFormBtn').show();
      $('.examinationForm').show();
    }

    
    $(document).on('submit','#saveExaminationCompetency',function(e){
      e.preventDefault();
      var form = $(this)
      content = "Are you sure you want to proceed?";
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
                                                      case 'saveExaminationCompetency':
                                                            console.log(result.Data)
                                                          self.setContent(result.Data);
                                                          self.setTitle(`<label class="text-success">Exam Result</label> <p>Multiple Choice : ${result.Data.multiplication_res}</p> 
                                                            <p>Enumeration : ${result.Data.enumeration_res}</p> <p>Fill in the blanks: ${result.Data.fill_res}</p> <p>Essay result pending result</p>
                                                          `);
                                                          $('#myModal .modal-body').html('');
                                                          $('#myModal').modal('hide');
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
  });
});
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  