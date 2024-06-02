$(function () {
  var divisions = [];
  var emps = [];
  loadTable();

  $(document).on("click", ".applyBtn", function(){
     var inclusivedaterange = $('#myModal .duration').val();
    //  var splitDate = inclusivedaterange.split(' - '); 
    //  var firstDate = splitDate[0];
    //  var secondDate = splitDate[1];
    //  var noDays = datediff(parseDate(firstDate), parseDate(secondDate));
    //  $('.no_days').val(noDays+1);
     
    $('#saveTravelOrder').prop("disabled", true);
    $.ajax({
     type: "POST",
     url: commons.baseurl + "leavemanagement/LeaveRequest/validateDaterange",
     data: {inclusivedaterange: inclusivedaterange},
     dataType: "json",
     success: function (result) {
       $('#saveTravelOrder').prop("disabled", false);
       if (result.Code == "0") {
         $('.no_days').val(result.Data['number_of_days']);
       } else {
         $('.no_days').val("");
       }
     },
     error: function (result) {
       self.setContent("There was an error in the connection. Please contact the administrator for updates.");
       self.setTitle('<label class="text-danger">Failed</label>');
     }
   });

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

 //  $('#myModal').on('shown.bs.modal', function (e) {
 //    $('.addItem').click(function(){
 //      var emp_id = $('#employee_id').val();
 //      var emp_name = $('#employee_id option:selected').text();
 //      var divisionID = $('#division_id').val();
 //      var division_name = $('#division_id option:selected').text();
 //      if (division_name !== "" && emp_name !== "" && emp_name !== "Loading..." && division_name !== "Loading..."){
 //        if(jQuery.inArray(emp_id, emps) !== -1){
 //           $.alert({
 //            title: '<label class="text-danger">Failed</label>',
 //            content:
 //              "Employee already added.",
 //          });
 //        }else{
 //        var concat_value = divisionID+"-"+emp_id;
 //        emps.push(emp_id);
 //        divisions.push(concat_value);
 //        $('.emp_list').append('<li class="list-group-item">'+
 //          ''+division_name+' &emsp;&emsp;&emsp;-&emsp;&emsp;&emsp; '+emp_name+''+
 //          '<button type="button" id="remove-'+emp_id+'" class="btn btn-danger glyphicon glyphicon-remove" style="float: right;padding: 2px;">'+
 //          '</button></li>');
 //        }
 //      }else{
 //        $.alert({
 //          title: '<label class="text-danger">Failed</label>',
 //          content:
 //            "Please select division and passenger/driver.",
 //        });
 //      } 

 //      $("#remove-"+emp_id+"").on('click',function () {
 //        $(this).closest("li").remove();
 //        divisions = jQuery.grep(divisions, function(value) {
 //          return value != concat_value;
 //        });
 //        emps = jQuery.grep(emps, function(value) {
 //          return value != emp_id;
 //        });
 //      });

 //    });
 // });


  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  //Ajax non-forms
  $(document).on("click","#addTravelOrderForm", function (e) {
      e.preventDefault();
      me = $(this);
      id = me.attr("data-id");
      url = me.attr("href");
      $.ajax({
        type: "POST",
        url: url,
        data: {
          id: id,
        },
        dataType: "json",
        success: function (result) {
           switch (result.key) {
              case "addTravelOrder":
                $("#myModal .modal-dialog").attr("class","modal-dialog modal-lg");
                $("#myModal .modal-title").html("Add New Travel Order Request");
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $("#officialpurpose").val('');
                $(".emplabel").html('');
                $(".emplabel").html('Passenger / Driver<span class="text-danger">*</span>');

                employee_id = $('.user_employee_id').val();
                division_id = $('.user_division_id').val();
                canselectmultiple = $('.canselectmultiple').val();

                $('.travel_order_no_div').hide();
                $('.vehicle_no_div').hide();
                $('.driver_div').hide();
                $('.travel_order_no').removeAttr('required','required');
                $('.vehicle_no').removeAttr('required','required');
                $('.driver').removeAttr('required','required');

                $('input:radio[name=purpose]').change(function() {
                   if (this.value == 'not_return') {
                    $('.reason_hide').show();
                    // $('.reason').attr("required","required");
                    $('.reason').val('');
                   }else{
                     $('.reason').removeAttr("required","required");
                      $('.reason').val('');
                     $('.reason_hide').hide();
                   }
                });

                if (canselectmultiple == "no"){
                  $.when(getFields.division()).done(function (){
                    $('#division_id').val(division_id);
                    $("#division_id").css("pointer-events", "none");
                  });
                  $.when(getFields.employee({division_id: division_id})
                   ).done(function () {
                    $('#employee_id').val(employee_id);
                    $("#employee_id").css("pointer-events", "none");
                  });
                }else{
                  $(document).on('change','#division_id', function () {
                    $.when(getFields.employee({
                        division_id: $(this).val()
                      }) 
                     ).done(function () {
                      $('#employee_id').attr('multiple','multiple');
                      $("#employee_id option[value='']").remove();
                      $('#employee_id').removeAttr('required','required');
                      
                      $('#employee_id').val(employee_id);
                      $.AdminBSB.select.activate();
                    });
                  });

                  $.when(getFields.division()).done(function (){
                    $('#division_id').val(division_id).change();
                    $.AdminBSB.select.activate();
                  });
                  
                }
                setTimeout(function(){
                  $('#radio_not_return_office').click();
                }, 100);
                
                 $("#duration").daterangepicker({
                    timePicker: false,
                    autoApply: false,
                    drops: "up",
                    locale: { format: "MM/DD/YYYY"  },
                    minDate: moment().startOf("day"),
                    parentEl: "#myModal .modal-body" 
                  });
                $('.no_days').val("1");
                break;
              
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
                if (status == "4" || status == "5"){
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

                $(".button_add").hide();
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
                    // $('.reason').attr("required","required");
                   }else{
                     $('.reason').removeAttr("required","required");
                     $('.reason').val('');
                     $('.reason_hide').hide();
                   }
                });
                // console.log(division_id);
                 $.when(getFields.division()).done(function (){
                   $('.division_id').val(division_id);
                   $.AdminBSB.select.activate();
                  
                });
               
                $.when(getFields.employee({division_id: division_id})).done(function () {
                   // $('#employee_id').attr('multiple','multiple');
                   // $("#employee_id option[value='']").remove();
                   $('.employee_id').val(employee_id);
                   $.AdminBSB.select.activate();
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

  //Confirms
  $(document).on('click', '.cancelRequestForm', function (e) {
    e.preventDefault();
    var me = $(this);
    var url = me.attr('href');
    var id = me.attr('data-id');
    $.confirm({
      title: '<label class="text-warning">Confirm!</label>',
      content: 'Are you sure you want to proceed?',
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
                  data: {id: id},
                  dataType: "json",
                  success: function (result) {
                    if (result.Code == "0") {
                            self.setContent(result.Message);
                            self.setTitle('<label class="text-success">Success</label>');
                            loadTable();
                    } else {
                      self.setContent(result.Message);
                      self.setTitle('<label class="text-danger">Failed</label>');
                    }
                  },
                  error: function (result) {
                    self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                    self.setTitle('<label class="text-danger">Failed</label>');
                  }
                });
              }
            });
          }

        },
        cancel: function () {}
      }
    });
  });

  $(document).on("submit","#addTravelOrder,#updateTravelOrder",function (e) {
      e.preventDefault();
      var form = $(this); 
      var datas = new FormData(form[0]);
      employee_id = $('#myModal #employee_id').val()
      division_id = $("#myModal #division_id").val();
      console.log(employee_id);
     if (!$(".purpose").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please select return to office or not.",
        });
        return false;
      }
      if(division_id == "" || division_id == "undefined"){
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Please select Division.",
        });
        return false;
      }else if(employee_id == "" || employee_id == "undefined" || employee_id == null){
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Please select Passenger/Driver.",
        });
        return false;
      }else{
        if (form.attr("id") == "addTravelOrder") {
       
          // if (emps.length === 0) {
          //   $.alert({
          //     title: '<label class="text-danger">Failed</label>',
          //     content: "Please add employee.",
          //   });
          //   return false;
          // }
  
          // var arr_division = [];
          // for (var i = 0; i < divisions.length; i++) {
          //   var divi_id = divisions[i].split('-');
          //   arr_division. push(divi_id[0]);
          // }
          // datas.append('division_ids',arr_division);
          
          unique_travel_id = makeid(8);
          datas.append('travel_id',unique_travel_id);
          datas.append('employee_ids',$('#employee_id').val());
  
          content = "Are you sure you want to request for this travel order?";
        }
  
         if (form.attr("id") === "updateTravelOrder") {
          content =
            "Are you sure you want to update this travel order?";
            datas.append('travel_id',$('.travel_id').val());
            datas.append('travel_order_no',$('.travel_order_no').val());
            datas.append('driver',$('.driver').val());
            datas.append('vehicle_no',$('.vehicle_no').val());
        }
      }

      content = "Are you sure you want to proceed?";


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
                         if (form.attr("id") === "updateTravelOrder" || form.attr("id") == "addTravelOrder"){
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
              url:commons.baseurl+ "travelOrder/TravelOrderRequest/fetchRows?status="+$("#status").val(),
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

  function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
   return result;
}
});
