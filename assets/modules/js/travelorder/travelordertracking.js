$(function () {
    var page = "";
    base_url = commons.base_url;
    var table;
    loadTable();
   
    $(document).on("click", "#print_preview_appleave", function () {
      printPrev(document.getElementById("content").innerHTML);
    });
  
    $('.forApproval').click(function() {
      $.ajax({
          url: commons.baseurl + "travelOrder/TravelOrderTracking/fetchTravelID",
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
  
    var employee_id;
    var division_id;
    //Ajax non-forms
    $(document).on(
      "click",
      ".viewPendingTravelOrder",
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
                url:commons.baseurl+ "travelOrder/TravelOrderTracking/fetchRows?status="+$("#status").val(),
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
  
  
  