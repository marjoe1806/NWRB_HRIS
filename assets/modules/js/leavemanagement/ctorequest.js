$(function () {
  loadTable();
  $(document).on("show.bs.modal", "#myModal", function () {
    $(".datepicker").bootstrapMaterialDatePicker({
      format: "YYYY-MM-DD",
      minDate: new Date(),
      clearButton: true,
      weekStart: 1,
      time: false,
    });

    //  $('input:radio[name=purpose]').change(function() {
    //    if (this.value == 'expected_time_return') {
    //     $('.expected_time_return_input').show();
    //     $('.expected_return').attr("required","required");
    //    }else{
    //      $('.expected_return').removeAttr("required","required");
    //      $('.expected_time_return_input').hide();
        
    //    }
    //  });
  });
  $(document).on("click", "#print_preview_appleave", function () {
    printPrev(document.getElementById("content").innerHTML);
  });

  $(document).on("click", ".applyBtn", function(){
    var inclusivedaterange= $('.inclusive_dates').val();
    var splitDate = inclusivedaterange.split(' - ');
    var firstDate = splitDate[0];
    var secondDate = splitDate[1];
    var noWorkingDays = datediff(parseDate(firstDate), parseDate(secondDate));
    $('.number_of_days').val(noWorkingDays +1);
});

function parseDate(firstDate) {
  var mdy = firstDate.split('-');
  return new Date(mdy[0], mdy[1]-1, mdy[2]);
}
function datediff(firstDate, secondDate) {
  // Take the difference between the dates and divide by milliseconds per day.
  // Round to nearest whole number to deal with DST.
  return Math.round((secondDate-firstDate)/(1000*60*60*24));
}

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

  //Ajax non-forms
  $(document).on(
    "click",
    "#addCTORequestForm,.updateCTORequestForm,.viewCTORequestForm",
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
          page = me.attr("id");
          // console.log(me.data());
          if (result.hasOwnProperty("key")) {
            var sess_division_id = $("#sess_division_id").val();
            var sess_employee_id =
              result.key == "addCTORequest"
                ? $("#sess_employee_id").val() 
                : me.data("employee_id");
            var sess_position =
              result.key == "addCTORequest"
                ? $("#sess_position").val()
                : me.data("position_id");
            var sess_employee_number = $("#sess_employee_number").val();
            $.when(getFields.division()).done(function () {
                  $.when(
                    getFields.employee({
                      division_id: sess_division_id,
                      pay_basis: "Permanent",
                    })
                  ).done(function () {
                    $("#division_id").val(me.data("division_id")).change();
                    $("#division_id").css("pointer-events", "none");
                    $("#employee_id").val(me.data("employee_id")).change();
                    $("#employee_id").css("pointer-events", "none");

                    $("form #division_id").val(sess_division_id).change();
                    $("form .division_id").css("pointer-events", "none");
                    $("form #employee_id").val(sess_employee_id).change();
                    $("form #employee_id").css("pointer-events", "none");
                  });
                });
            $.when(getFields.position({ pay_basis: "Permanent" })).done(
              function () {
                if($.isNumeric(sess_position)){
                  $('form .position_select').show();
                }else{
                  $('form .position_input').show();
                }
                $("form #position_id").val(sess_position).change();
                $("form #position_id").css("pointer-events", "none");
                $("form #filing_date").css("pointer-events", "none");
              }
            );
            switch (result.key) {
              case "addCTORequest":
                page = "";
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                  "Add New Compensatory Time Off"
                );
                $("#myModal .modal-body").html(result.form);
                $("#myModal").modal("show");
                $(".inclusive_dates").daterangepicker({
                  timePicker: false,
                  autoApply: false,
                  drops: "up",
                  locale: { format: "YYYY-MM-DD" },
                  // minDate: moment().startOf("day"),
                  minDate: moment().add(5, "days"),
                  maxDate: moment().add(6, "months"),
                }, function(start, end, label) {
                  setTimeout(() => {
                    $('.applyBtn').click();
                  }, 100);
                });
                break;
              case "viewCTORequest":
              case "updateOCTORequest":
                $.when(getFields.division()).done(function () {
                  $(".division_id").selectpicker("val", me.data("division_id"));
                  $(".division_id").val(me.data("division_id")).change();
                  $(".remarks").selectpicker("val", me.data("remarks"));
                  $(".remarks").val(me.data("remarks")).change();
                  employee_id = me.data("employee_id");
                  $(".employee_id_2").selectpicker(
                    "val",
                    me.data("checked_by")
                  );
                  $(".type_id").selectpicker("val", me.data("type_id"));
                });
                $("#myModal .modal-dialog").attr(
                  "class",
                  "modal-dialog modal-lg"
                );
                $("#myModal .modal-title").html(
                  "Compensatory Time Off Details"
                );
                $("#myModal .modal-body").html(result.form);

                $("#myModal").modal("show");
                $("#transaction_date")
                  .attr("type", "text")
                  .val(me.data("offset_date_effectivity"));
                $("#no_of_hrs")
                  .attr("type", "text")
                  .val(me.data("offset_hrs"));
                 // $("#expected_return").removeAttr('disabled','disabled');
                $("#no_of_mins")
                  .attr("type", "text")
                  .val(me.data("offset_mins"));
                $('.number_of_days').val(me.data("number_of_days"));
                $('.inclusive_dates').val(me.data("offset_date_effectivity"));
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

  $(document).on("click", "#btnXls", function () {
    exportEXCEL("#datatables", 1, "td:eq(0),th:eq(0)");
  });
  //Ajax Forms
  $(document).on(
    "submit",
    "#addCTORequest,#updateCTORequest",
    function (e) {
      e.preventDefault();
      var form = $(this);
      let offsetBal = parseInt(($(".totalOffset").attr("data-offset-hrs") * 60)) + parseInt($(".totalOffset").attr("data-offset-mins"));
      let inputedOffsetBal = parseInt(($("#no_of_hrs").val() * 60)) + parseInt($("#no_of_mins").val());
      if(inputedOffsetBal > offsetBal){
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content:
            "Insufficient Offset Balance",
        });
          return false;
      }

      content = "Are you sure you want to proceed?";
      if (form.attr("id") == "addCTORequest") {
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

      url = form.attr("action");
      var formData = new FormData(form[0]);
      $.confirm({
        title: '<label class="text-warning">Confirm!</label>',
        content: content,
        type: "orange",
        buttons: {
          confirm: {
            btnClass: "btn-blue",
            action: function () {
              //Code here
              if (form.attr("id") == "addCTORequest") {
                if($("#sig_file")[0].files[0] == 'undefined' || $("#sig_file")[0].files[0] == null){
                  $(".esign").css("display","block")
                  return false;
                }
                formData.append("sig_file", $("#sig_file")[0].files[0]);
              }
              $.confirm({
                content: function () {
                  var self = this;
                  return $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (result) {
                      if (result.hasOwnProperty("key")) {
                        if (result.Code == "0") {
                          if (result.hasOwnProperty("key")) {
                            switch (result.key) {
                              case "addCTORequest":
                              case "updateCTORequest":
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
          "stateSave": true, // presumably saves state for reloads -- entries
          "bStateSave": true, // presumably saves state for reloads -- page number
          "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
          ],
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
              url:commons.baseurl+ "leavemanagement/CTORequest/fetchRows?status="+$("#status").val(),
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
