$(function () {
  loadTable();
  globalCount = 0; 
  $(document).on("show.bs.modal", "#myModal", function () {
    $(".date").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      clearButton: true,
      weekStart: 1,
      time: false,
    });
 
     $('input:radio[name=is_return]').change(function() {
       if (this.value == '1') {
        $('.expected_time_return_input').show();
        $('.expected_return').attr("required","required");
       }else{
         $('.expected_return').removeAttr("required","required");
         $('.expected_time_return_input').hide();
        
       }
     });
  });
  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  //Confirms
  $(document).on('click', '.cancelOBRequestForm', function (e) {
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

  $(document).on('change','#division_id', function () {
    //console.log(count);
    if(globalCount > 1){
      $.when(getFields.employee({
        division_id: $(this).val()
      })
     ).done(function () {
      $('#employee_id').attr('multiple','multiple');
      $("#employee_id option[value='']").remove();
       $('#employee_id').removeAttr('required','required');
      $.AdminBSB.select.activate();
    });
    }
    globalCount += 1;
  });

  //Ajax non-forms
  $(document).on(
    "click",
    "#addOfficialBusinessRequestForm,.updateOfficialBusinessRequestForm,.viewOfficialBusinessRequestForm",
    function (e) {
      
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
          
          globalCount = 0;
          valuetest = true;
          //console.log(count);
            var sess_division_id = $("#sess_division_id").val();
            var sess_employee_id =
              result.key == "addOfficialBusinessRequest"
                ? $("#sess_employee_id").val()
                : me.data("employee_id");
                //console.log(sess_employee_id);
            // var sess_position =
            //   result.key == "addOfficialBusinessRequest"
            //     ? $("#sess_position").val()
            //     : me.data("position_id");
            // var sess_employee_number = $("#sess_employee_number").val();
            //OLD CODE
            // $.when(getFields.division()).done(function () {
            //       $.when(
            //         getFields.employee({
            //           division_id: sess_division_id,
            //           pay_basis: "Permanent",
            //         })
            //       ).done(function () {
            //         $("#division_id").val(me.data("division_id")).change();
            //         // $("#division_id").css("pointer-events", "none");
            //         $("#employee_id").val(me.data("employee_id")).change();
            //         // $("#employee_id").css("pointer-events", "none");

            //         $("form #division_id").val(sess_division_id).change();
            //         // $("form .division_id").css("pointer-events", "none");
            //         $("form #employee_id").val(sess_employee_id).change();
            //         // $("form #employee_id").css("pointer-events", "none");
            //       });
            //     });
            //END
            //MY NEW CODE
          
            // END
            // $.when(getFields.position({ pay_basis: "Permanent" })).done(
            //   function () {
            //     $("form #position_id").val(sess_position).change();
            //     // $("form #position_id").css("pointer-events", "none");
            //     $("form #filing_date").css("pointer-events", "none");
            //   }
            // );
            switch (result.key) {
              case "addOfficialBusinessRequest":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                  "Add New Personnel Locator Slip Request"
                );
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $('#myModal').on('hidden.bs.modal', function () {
                  $('.cancelBtn').click();
                })
                $(".emplabel").html('');
                $(".emplabel").html('Employee Name');

                employee_id = $('.user_employee_id').val();
                division_id = $('.user_division_id').val();
                canselectmultiple = $('.canselectmultiple').val();
               

                $('.control_no_div').hide();
                $('.filing_date_div').hide();
                $('.driver_div').hide();
                $('.vehicle_div').hide();
                $('.filing_date_div').hide();
                $('.activity_name_div').hide();
                $('.location_div').hide();
                $('.expected_return_div').hide();
                $('.transaction_date_div').hide();
                $('.expected_return_div').hide();
                $('.purpose_div').hide();

                $('.control_no').removeAttr('required','required');
                $('.filing_date').removeAttr('required','required');
                $('.driver').removeAttr('required','required');
                $('.vehicle').removeAttr('required','required');
                $('.filing_date').removeAttr('required','required');
                $('.activity_name').removeAttr('required','required');
                $('.location').removeAttr('required','required');
                $('.expected_return').removeAttr('required','required');
                $('.transaction_date').removeAttr('required','required');
                $('.expected_return').removeAttr('required','required');
                $('.purpose').removeAttr('required','required');

                $("#transaction_date").daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  locale: { format: "YYYY-MM-DD" },
                  minDate: moment().startOf("day"),
                });

                

                $("form #filing_date").css("pointer-events", "none");
                $.when(getFields.division()).done(function (){
                  $.AdminBSB.select.activate();
                $.when(
                    getFields.employee({
                      division_id: sess_division_id,
                      pay_basis: "Permanent",
                    })
                  ).done(function () {
                    $("#division_id").val(me.data("division_id")).change();
                    $("#employee_id").val(me.data("employee_id")).change();
                    $('#employee_id').attr('multiple','multiple');
                    $("form #division_id").val(sess_division_id).change();
                    $('#employee_id').selectpicker("val", sess_employee_id);
                    valuetest = false;
                  });

                });

                  if($('#division_id').val() == ""){
                    $('.division_id').removeAttr('required','required');
                  
                  }

                  if($('#employee_id').val() == ""){
                    $('.employee_id').removeAttr('required','required');
                  
                  }

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
                  // $.when(getFields.division()).done(function (){
                  //   $.AdminBSB.select.activate();
                  // });

                  $(document).on('click', '#cancel', function(){
                    count = 0;
                  });
                }

                break;
              case "viewOfficialBusinessRequest":
              case "updateOfficialBusinessRequest":
                $.when(getFields.division()).done(function () {
                  $(".division_id").selectpicker("val", me.data("division_id"));
                  $(".division_id").val(me.data("division_id")).change();
                  $(".remarks").selectpicker("val", me.data("remarks"));
                  $(".remarks").val(me.data("remarks")).change();
                 
                  employee_id = me.data("employee_id");
                  $.when(getFields.employee({division_id: division_id})
                  ).done(function () {
                   $('.employee_id').selectpicker("val", employee_id);
                   $(".employee_id").val(me.data("employee_id")).change();
                 });
                  $(".employee_id_2").selectpicker(
                    "val",
                    me.data("checked_by")
                  );
                  $(".type_id").selectpicker("val", me.data("type_id"));
                  // $('.location_id').selectpicker('val', me.data('location_id'));
                  // $('.employee_id').selectpicker('refresh');
                  $("#transaction_date").change();
                  $.AdminBSB.select.activate();
                });
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                  "Personnel Locator Slip Details"
                );
                $("#myModal .modal-body").html(result.form);

                $("#myModal").modal("show");
                $.each(me.data(), function (i, v) {
                  if (i == "transaction_date") {
                    $(".transaction_date").val(
                      me.data("transaction_date_end") != "0000-00-00"
                        ? v + " - " + me.data("transaction_date_end")
                        : v + " - " + v
                    );
                  } else {
                    $("." + i)
                      .val(me.data(i))
                      .change();
                  }

                //  For Vehicle
                if (i == "is_vehicle")
                $("#radio_" + me.data(i))
                  .attr("checked", true)
                  .change()
                  .click();
                // end

                  if (i == "purpose")
                    $("#radio_" + me.data(i))
                      .attr("checked", true)
                      .change()
                      .click();

                  if (i == "is_return")
                    $("#radio_" + me.data(i))
                      .attr("checked", true)
                      .change()
                      .click();

              
                      
                });

              
                


                $("#locator_time_out")
                  .attr("type", "text")
                  .val(me.data("transaction_time_end"));
                $("#locator_time_in")
                  .attr("type", "text")
                  .val(me.data("transaction_time"));
                 // $("#expected_return").removeAttr('disabled','disabled');
                $("#expected_return")
                  .attr("type", "text")
                  .val(me.data("expected_time_return"));
                  
                if(me.data("filename") == ""){
                  $("#downloadAttachment, #viewAttachment").css("pointer-events","none");
                }

                $("#viewAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/locatorslips/" +
                    me.data("employee_id") +
                    "/" +
                    me.data("filename")
                );
                $("#downloadAttachment").attr(
                  "href",
                  commons.baseurl +
                    "assets/uploads/locatorslips/" +
                    me.data("employee_id") +
                    "/" +
                    me.data("filename")
                );
                if (result.key == "viewLocatorSlips") {
                  $("#changeAttachment").hide();
                  $("form")
                    .find("input, textarea, select")
                    .attr("disabled", "disabled");
                  $("form").find("#cancelUpdateForm").removeAttr("disabled");
                }

                $("form :input").attr("disabled", true);
                $("#cancelUpdateForm").removeAttr("disabled");
                break;
            }

            $("#" + result.key).validate({
              rules: {
                activity_name: {
                  required: true,
                  normalizer: function (value) {
                    return $.trim(value);
                  },
                },
                location: {
                  required: true,
                  normalizer: function (value) {
                    return $.trim(value);
                  },
                },
                ".required": {
                  required: true,
                },
                ".email": {
                  required: true,
                  email: true,
                },
              },
              highlight: function (input) {
                $(input).parents(".form-line").addClass("error");
              },
              unhighlight: function (input) {
                $(input).parents(".form-line").removeClass("error");
              },
              errorPlacement: function (error, element) {
                $(element).parents(".form-group").append(error);
              },
            });
          
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

  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
  });


  var employee_id;
  var division_id;
  //Ajax Forms
  $(document).on(
    "submit",
    "#addOfficialBusinessRequest,#updateOfficialBusinessRequest",
    function (e) {
      //console.log('test');
      e.preventDefault();
      var form = $(this);
      // console.log(form);
      var datas = new FormData(form[0]);
    
     //console.log($("#employee_id").val());
      // alert(datas);

      if (
        $("#locator_time_in").val() == "" ||
        $("#locator_time_out").val() == ""
      ) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Enter Time Out.",
        });
        return false;
      }

      if (
        $("#employee_id").val() == "" || $("#employee_id").val() == "undefined" || $("#employee_id").val() == null) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please Select Employee.",
        });
        return false;
      }

      if (
        $("#division_id").val() == "" || $("#division_id").val() == "undefined") {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Please Select Division / Unit.",
        });
        return false;
      }



      if (!$(".purpose").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Select Purpose of Official Business.",
        });
        return false;
      }

      if (!$(".is_vehicle").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Select No Vehicle or With Vehicle.",
        });
        return false;
      }

      if (!$(".is_return").is(":checked")) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Select Expected time of return/Expected not to be back.",
        });
        return false;
      }

     // if ($("#expected_return").val() == "") {
     //    $.alert({
     //      title: '<label class="text-danger">Failed</label>',
     //      content: "Update expected time of return",
     //    });
     //    return false;
     // }
     
    //  alert(form.attr("id"));

      content = "Are you sure you want to proceed?";
      if (form.attr("id") == "addOfficialBusinessRequest") {

        datas.append('employee_ids',$('#employee_id').val());
      //  console.log(data);
        content = "Are you sure you want to request for this personnel locator slip?";
        content += '<div class="form-group">';
        content += '<label class="form-label">E-Signature<small class="text-danger esign" style="display:none;font-style: italic;"> * Please upload signature.</small></label>';
        content += '<div class="form-group form-float">';
        content += '<div class="form-line">';
        content +=
          '<input type="file" id="sig_file" name="sig_file" class="sig_file form-control">';
        content += "</div>";
        content += "</div>";
        content += "</div>";
        content += "</form>";
      }
      if (form.attr("id") == "updateOfficialBusinessRequest") {
        content = "Are you sure you want to update this personnel locator slip?";
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
              if (form.attr("id") == "addOfficialBusinessRequest") {
                if($("#sig_file")[0].files[0] == 'undefined' || $("#sig_file")[0].files[0] == null){
                  $(".esign").css("display","block")
                  return false;
                }
                datas.append("sig_file", $("#sig_file")[0].files[0]);
              }
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
                      if (result.hasOwnProperty("key")) {
                        if (result.Code == "0") {
                          if (result.hasOwnProperty("key")) {
                            switch (result.key) {
                              case "addOfficialBusinessRequest":
                              case "updateOfficialBusinessRequest":
                                self.setContent(result.Message);
                                self.setTitle(
                                  '<label class="text-success">Success</label>'
                                );
                                $("#myModal .modal-body").html("");
                                $("#myModal").modal("hide");
                                loadTable();
                                break;
                            }
                          }
                        } else {
                          self.setContent(result.Message);
                          self.setTitle(
                            '<label class="text-danger">Failed</label>'
                          );
                        }
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
          "order": [],
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
          ],
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
              url:commons.baseurl+ "timekeeping/OfficialBusinessRequest/fetchRows?status="+$("#status").val(),
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
