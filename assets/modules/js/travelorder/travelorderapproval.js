$(function () {
  var page = "";
  base_url = commons.base_url;
  var table;
  loadTable();
  $(document).on("click", ".applyBtn", function(){
      var inclusivedaterange = $('#myModal .duration').val();
      var splitDate = inclusivedaterange.split(' - ');
      var firstDate = splitDate[0];
      var secondDate = splitDate[1];
      var noDays = datediff(parseDate(firstDate), parseDate(secondDate));
      $('.no_days').val(noDays+1);
  });

  function parseDate(firstDate) {
      var mdy = firstDate.split('/');
      return new Date(mdy[2], mdy[0]-1, mdy[1]);
  }

  function datediff(firstDate, secondDate) {
      // Take the difference between the dates and divide by milliseconds per day.
      // Round to nearest whole number to deal with DST.
      return Math.round((secondDate-firstDate)/(1000*60*60*24));
  }

  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  $('.forApproval').click(function() {
    $.ajax({
        url: commons.baseurl + "travelOrder/TravelOrderApproval/fetchTravelID",
        type: "GET",
        data: { travel_id: $("#travel_id").val() },
        dataType: "json",
        success: function(data) {
            // Process the retrieved data here
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error: " + textStatus, errorThrown);
        }
    });
});

  //Confirms
 $(document).on(
    "click",
    ".firstRecommendation, .secondRecommendation, .forCertification, .forApproval, .forDriverVehicle, .rejectTravelOrder",
    function (e) {

      e.preventDefault();
      var formData = new FormData();
      me = $(this);
      url = me.attr("href");
      var id = me.attr("data-id");
      var employee_id = me.attr("data-employee_id");
      var travel_id = me.attr("data-travel_id");
      var status = me.attr("data-status");
      var duration = me.attr("data-duration");
      var no_days = me.attr("data-no_days");
      var travel_order_no = me.attr("data-travel_order_no");
      var content = "Are you sure you want to proceed?";
      var remarks = "";
      // var formData = {
      //   id: id,
      //   employee_id: employee_id,
      //   travel_id : travel_id,
      //   status : status,
      //   duration : duration,
      //   no_days : no_days,
      //   travel_order_no : travel_order_no
      // }
      


      formData.append("id", id);
      formData.append("employee_id", employee_id);
      formData.append("travel_id", travel_id);
      formData.append("status", status);
      formData.append("duration", duration);
      formData.append("no_days", no_days);
      formData.append("travel_order_no", travel_order_no);

  
      
      console.log(travel_id);
      if (me.hasClass("firstRecommendation")) {
        content = "Are you sure you want to recommend the travel order request?";
      } 
      else if (me.hasClass("secondRecommendation")) {
         content = "Are you sure you want to recommend the travel order request?";
         content += '<div class="form-group">';
         content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
         content += '<div class="form-group form-float">';
         content += '<div class="form-line">';
         content +=
           '<input type="file" id="file" name="file" class="file form-control">';
         content += "</div>";
         content += "</div>";
         content += "</div>";
         content += "</form>";
      } 
      else if (me.hasClass("forCertification")) {
        content = "Are you sure you want to certify the travel order request?";
        // content = "Are you sure you want to certify the travel order request?";
        // content += '<div class="form-group">';
        // content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        // content += '<div class="form-group form-float">';
        // content += '<div class="form-line">';
        // content +=
        //   '<input type="file" id="file" name="file" class="file form-control">';
        // content += "</div>";
        // content += "</div>";
        // content += "</div>";
        // content += "</form>";
      }
      else // Assuming you have a variable 'me' representing the element with the class "forApproval"
      if (me.hasClass("forApproval")) {

        
          
          // Add the content to the DOM
          var content = ""; // Initialize the 'content' variable
          
          content += "Are you sure you want to approve the travel order request?";
          content += '<div class="form-group">';
          //content += '<div id="contentContainer">'; // Placeholder for the user list using a container with the ID 'contentContainer'
          //content += '</div>';
          // Continue with the existing content
          content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
          content += '<div class="form-group form-float">';
          content += '<div class="form-line">';
          content += '<input type="file" id="file" name="file" class="file form-control">';
          content += "</div>";
          content += "</div>";
          content += "</div>";
          content += "</form>";

            // // Function to fetch users by travel ID     
            // $.ajax({
            //   url: commons.baseurl + "travelOrder/TravelOrderApproval/fetchUsersByTravelID",
            //   type: "POST",
            //   data: { travel_id: travel_id },
            //   dataType: "json",
            //   success: function(data) {
            //     // On success, iterate through the users and store their names in an array
            //     var userNames = [];
            //     $.each(data.users, function(index, user) {
            //         var fullName = user.decrypted_first_name + ' ' + user.decrypted_last_name;
            //         userNames.push(fullName);
            //     });
        
            //     // Log the entire 'data' object to the console
            //     console.log("User data:", data);
        
            //     // Display the array list in the content section
            //     var userListContainer = $("<ul></ul>");
        
            //     $.each(userNames, function(index, name) {
            //         var listItem = $("<li></li>").text(name);
            //         userListContainer.append(listItem);
            //     });
        
            //     // Add the user list to the content section
            //     var contentContainer = $("#contentContainer");
            //     contentContainer.empty().append('<div class="user-list"><b>Employee List:</b></div>', userListContainer);
            //   },
            //   error: function(xhr, status, error) {
            //     // Handle errors if needed
            //     console.error("Error fetching users: " + error);
            //   }
            // });
           
        }      
        else if (me.hasClass("forDriverVehicle")) {
          content = "Are you sure you want to approve the travel order request?";
        }
        else if (me.hasClass("rejectTravelOrder")) {
          content += '<div class="form-group">';
          content += '<label class="form-label">Remarks</label>';
          content += '<div class="form-group form-float">';
          content += '<div class="form-line">';
          content += '<input type="text" name="remarks" id="remarks" class="remarks form-control">';
          content += "</div>";
          content += "</div>";
          content += "</div>";
          content += "</form>";
      }      
      else if (me.hasClass("forDriverVehicle")) {
        content = "Are you sure you want to approve the travel order request?";
      }
      else if (me.hasClass("rejectTravelOrder")) {
        content += '<div class="form-group">';
        content += '<label class="form-label">Remarks</label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content += '<input type="text" name="remarks" id="remarks" class="remarks form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      }

      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              remarks =
                typeof this.$content.find(".remarks").val() === "undefined"
                  ? "N/A"
                  : this.$content.find(".remarks").val();
                  if(remarks != "") formData.append('remarks', remarks);
                  //console.log(formData);

              if (me.hasClass("secondRecommendation") || me.hasClass("forApproval")) {
                if($("#file")[0].files[0] == 'undefined' || $("#file")[0].files[0] == null){
                  $(".esign").css("display","block")
                  return false;
                }
                formData.append("file", $("#file")[0].files[0]);
              }
              $.confirm({
                content: function () {
                  var self = this;
                  var jsondata = {
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (result) {
                      if (result.Code == "0") {
                        if (result.hasOwnProperty("key")) {
                          switch (result.key) {
                            case "firstRecommendation":
                            case "secondRecommendation":
                            case "forCertification": 
                            case "forApproval":
                            case "forDriverVehicle":
                            case "rejectTravelOrder":
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              loadTable();
                              break;
                          }
                        }
                      } else {
                        self.setContent(result.Message);
                        self.setTitle(
                          '<label class="text-danger">Failed</label>'
                        );
                        loadTable();
                      }
                    },
                    error: function (result) {
                      self.setContent(
                        "There was an error in the connection. Please contact the administrator for updates."
                      );
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                    },
                  };
                  return $.ajax(jsondata);
                },
              });
            },
          },
          cancel: function () {},
        },
        onContentReady: function () {
          $(".chkDates").iCheck("destroy");
          $(".chkDates").iCheck({
            checkboxClass: "icheckbox_square-blue",
          });
        },
      });
    }
  );
  var employee_id;
  var division_id;
  //Ajax non-forms
  $(document).on(
    "click",
    ".viewPendingTravelOrder,.updateTravelOrderForm",
    function (e) {
      e.preventDefault();
      me = $(this);
      id = me.attr("data-id");
      status = me.attr("data-status");
      division_id = me.attr("data-division_id");
      employee_id = me.attr("data-employee_id");
      vehicle_no = me.attr("data-vehicle_no");
      locations = me.attr("data-location");
      reason = me.attr("data-reason");
      duration = me.attr("data-duration");
      no_days = me.attr("data-no_days");
      driver = me.attr("data-driver");
      purpose = me.attr("data-purpose");
      officialpurpose = me.attr("data-officialpurpose");
      travel_order_no = me.attr("data-travel_order_no");
      travel_id = me.attr("data-travel_id");
      is_vehicle = me.attr("data-is_vehicle");
      url = me.attr("href");
      $.ajax({
        type: "POST",
        url: url,
        data: {
          id: id,
        },
        dataType: "json",
        success: function (result) {
          page = me.attr("id");
          if (result.hasOwnProperty("key")) {
            switch (result.key) {
              case "viewPendingTravelOrder":
                
                $("#myModal .modal-dialog").attr("class","modal-dialog modal-lg");
                $("#myModal .modal-title").html("Travel Order Form");
                $("#myModal .modal-body").html(result.form);
                $(".form-elements-container").css("pointer-events", "none");
                $("#myModal").modal("show");
                if (status == "4" || status == "5" || status == "6"){
                  $('.vehicle_no').attr('disabled','disabled');
                  $('.driver').attr('disabled','disabled');
                  $('.travel_order_no').attr('disabled','disabled');
                }else if (status == "5"){
                  $('.travel_order_no_div').show();
                  $('.vehicle_no_div').show();
                  $('.driver_div').show();
                }else {
                  $('.travel_order_no_div').hide();
                  $('.vehicle_no_div').hide();
                  $('.driver_div').hide();
                }
                $('input:radio[name=purpose]').change(function() {
                   if (this.value == 'not_return') {
                    $('.reason_hide').show();
                    $('#radio_return_office').attr("disabled","disabled");
                   }else{
                      $('#radio_not_return_office').attr("disabled","disabled");
                     $('.reason_hide').hide();
                   }
                });
                // console.log(division_id);
                 $.when(getFields.division()).done(function (){
                   $('.division_id').val(division_id);
                  $("#division_id").css("pointer-events", "none");
                });
               
                $.when(getFields.employee({division_id: division_id})).done(function () {
                   $('.employee_id').val(employee_id);
                   $("#employee_id").css("pointer-events", "none");
                });
                

                $("#duration").val(duration);
                $("#vehicle_no").val(vehicle_no);
                $("#location").val(locations);   
                $("#reason").val(reason);
                $("#no_days").val(no_days);
                $("#driver").val(driver);
                $(".travel_order_no").val(travel_order_no);
                $("#officialpurpose").val(officialpurpose);

                if (purpose == "return"){
                  $("#radio_return_office").attr("checked", true).change().click();
                }else if (purpose == "not_return"){
                  $("#radio_not_return_office").attr("checked", true).change().click();
                }
                else{
                  $('#radio_return_office').attr("disabled","disabled");
                  $('#radio_not_return_office').attr("disabled","disabled");
                }

                if (is_vehicle == 2){
                  $("#radio_2").attr("checked", true).change().click();
                }else if (is_vehicle == 3){
                  $("#radio_3").attr("checked", true).change().click();
                }

               $("#vehicle_content").css("pointer-events", "none");

              break;

              case "updateTravelOrder":
        
                $("#myModal .modal-dialog").attr("class","modal-dialog modal-lg");
                $("#myModal .modal-title").html("Travel Order Form");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                if (status == "4"){
                    $('.travel_order_no').attr('disabled','disabled');
                    $('.vehicle_no').attr('required','required');
                    $('.driver').attr('required','required');
                    $('.travel_order_no').removeAttr('required','required');

                    $("#location").attr('readonly','readonly');
                    $("#reason").attr('readonly','readonly');
                    $("#no_days").attr('readonly','readonly');
                    $("#duration").attr('disabled','disabled')
                    
                    $("#officialpurpose").attr('readonly','readonly');
                
                    $('input:radio[name=purpose]').change(function() {
                    if (this.value == 'not_return') {
                        $('.reason_hide').show();
                        $('#radio_return_office').attr("disabled","disabled");
                    }else{
                        $('.reason_hide').hide();
                        $('#radio_not_return_office').attr("disabled","disabled");
                    }

                    if (is_vehicle == 2){
                        $('#radio_3').attr("disabled","disabled");
                    }else if (is_vehicle == 3){
                        $('#radio_2').attr("disabled","disabled");
                    }
                });
                }else if(status == "5") {
                  $('.vehicle_no').attr('disabled','disabled');
                  $('.driver').attr('disabled','disabled');
                  $('.travel_order_no').attr('disabled','disabled');
                  $('.vehicle_no').removeAttr('required','required');
                  $('.driver').removeAttr('required','required');
                  $('.travel_order_no').removeAttr('required','required');
                }else {
                  $('.travel_order_no_div').hide();
                  $('.vehicle_no_div').hide();
                  $('.driver_div').hide();
                  $('.travel_order_no').removeAttr('required','required');
                  $('.vehicle_no').removeAttr('required','required');
                  $('.driver').removeAttr('required','required');
                }

                // $(".button_add").hide();
                date = duration.split('-');
                $("#duration").daterangepicker({
                    timePicker: false,
                    autoApply: false,
                    drops: "up",
                    locale: { format: "MM/DD/YYYY"  },
                    startDate: new Date(date[0]),
                    endDate: new Date(date[1]),
                  });

                $('input:radio[name=purpose]').change(function() {
                   if (this.value == 'not_return') {
                    $('.reason_hide').show();
                    $('.reason').attr("required","required");
                   }else{
                     $('.reason').removeAttr("required","required");
                     $('.reason').val('');
                     $('.reason_hide').hide();
                   }
                });
                // console.log(division_id);
                 $.when(getFields.division()).done(function (){
                   $('.division_id').val(division_id);
                   if (status == "4") {
                    $("#division_id").css("pointer-events", "none");
                  } else{

                   $.AdminBSB.select.activate();
                  }
                  
                });
               
                $.when(getFields.employee({division_id: division_id})).done(function () {
                   // $('#employee_id').attr('multiple','multiple');
                   // $("#employee_id option[value='']").remove();
                   $('.employee_id').val(employee_id);
                  if (status == "4") {
                    $("#employee_id").css("pointer-events", "none");
                  }else{
                    $.AdminBSB.select.activate();
                  } 
                   
                });
                
                $(".id").val(id);
                $(".travel_id").val(travel_id);
                $(".travel_order_no").val(travel_order_no);
                $("#vehicle_no").val(vehicle_no);
                $("#location").val(locations);   
                $("#reason").val(reason);
                $("#no_days").val(no_days);
                $("#driver").val(driver);
                $("#officialpurpose").val(officialpurpose);

                if (is_vehicle == 2){
                  $("#radio_2").attr("checked", true).change().click();
                }else if (is_vehicleyapp == 3){
                  $("#radio_3").attr("checked", true).change().click();
                }

                if (purpose == "return"){
                  $("#radio_return_office").attr("checked", true).change().click();
                }else if (purpose == "not_return"){
                  $("#radio_not_return_office").attr("checked", true).change().click();
                }
                else{
                  
                }
                break;
               
            }
          }
        },
        error: function (result) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content:
              "There was an error in the connection. Please contact the administrator for updates.",
          });
        },
      });
    }
  );


  $(document).on("submit" ,"#updateTravelOrder",function (e) {
      e.preventDefault();
      var form = $(this);
      var datas = new FormData(form[0]);

      if ($("#division_id").val() === "") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select division.",
        });
        return false;
      }

      else if (!$(".purpose").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select return to office or not.",
        });
        return false;
      }

      if (form.attr("id") === "updateTravelOrder") {
        content =
          "Are you sure you want to update this travel order?";
          datas.append('travel_id',$('.travel_id').val());
          datas.append('travel_order_no',$('#travel_order_no').val());
          datas.append('driver',$('#driver').val());
          datas.append('vehicle_no',$('#vehicle_no').val());
          datas.append('duration',$('#duration').val());
      }

      url = form.attr("action");
      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              //Code here
              $.confirm({
                content: function () {
                  var self = this;
                  return $.ajax({
                    type: "POST",
                    url: url,
                    data: datas,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (result) {
                        if (result.Code == "0") {
                          if (form.attr("id") === "updateTravelOrder"){
                              self.setContent(result.Message);
                              self.setTitle(
                                '<label class="text-success">Success</label>'
                              );
                              $("#myModal .modal-body").html("");
                              $("#myModal").modal("hide");
                              loadTable();
                          }
                       
                        } else {
                          self.setContent(result.Message);
                          self.setTitle(
                            '<label class="text-danger">Failed</label>'
                          );
                        }
                      
                    },
                    error: function (result) {
                      self.setContent(
                        "There was an error in the connection. Please contact the administrator for updates."
                      );
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                    },
                  });
                },
              });
            },
          },
          cancel: function () {},
        },
      });
    }
  );
  

  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
  });

  $(document).on("click", "#btnsearch", function(){
      loadTable();
  });


  function loadTable(){
      $("#datatables").DataTable().clear().destroy();
      table = $('#datatables').DataTable({  
          "processing":true,  
          "serverSide":true,  
          // "stateSave": true, // presumably saves state for reloads -- entries
          // "bStateSave": true, // presumably saves state for reloads -- page number
          "order":[],
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
          ],
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
              if  ($("#search-employee").length === 0) {
                $('.dataTables_filter').append($searchButton);
              }
              
          },
          "drawCallback": function( settings ) {
              $('#search-loader').remove();
              $('#search-employee').removeAttr('disabled');
              $('#datatables button').removeAttr('disabled');
          },
          "ajax":{  
              url:commons.baseurl+ "travelOrder/TravelOrderApproval/fetchRows?status="+$("#status").val(),
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
  
});


