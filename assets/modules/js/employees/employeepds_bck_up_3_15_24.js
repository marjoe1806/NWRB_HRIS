$(document).ready(function() {

  $(document).on("click", ".upload", function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .find('input[type="file"]')
      .replaceWith($(this).parent().find('input[type="file"]').val(""));
    $(this).parent().find('input[type="file"]').trigger("click");
  });
  init_form_wizard();
  $.when(getFields.division()).done(function() {
    var employee_id = $("#id").val();
    if (employee_id !== "") {
      $.post(
        commons.baseurl + "employees/PDS/getEmpDetails", {
          id: employee_id
        },
        function(result) {
          $jsonData = JSON.parse(result);
          $.each($jsonData.data, function(i, v) {
            if (i != "id") {
              if (i.slice(0, 5) === "radio") {
                if (v === "Yes") {
                  $("#" + i + "_Yes")
                    .prop("checked", true)
                    .attr("checked", "checked");
                } else {
                  $("#" + i + "_No")
                    .prop("checked", true)
                    .attr("checked", "checked");
                }
              } else {
                if ($("." + i).length) $("." + i).val(v);

                $("." + i).trigger("input");
              }

              if (v == "0" || v == "1") $("." + i + v).click();
            }
          });
          $.ajax({
            url: commons.baseurl + "employees/PDS/getActivePhotoByEmployeeId",
            data: {
              employee_id: employee_id
            },
            type: "POST",
            dataType: "json",
            success: function(result) {
              photo =
                commons.baseurl + "assets/custom/images/default-avatar.jpg";
              if (result.Code == "0") {
                if (result.Data.details[0].employee_id_photo != "") {
                  photo = result.Data.details[0].employee_id_photo;
                }
              }
              $("#employeeImage").attr("src", photo);
              $(".image-tag").attr("href", photo);
              $("#employeeImage, #reset_snapshot").show();
              $("#take_snapshot, #my_camera").hide();
            },
          });

          $.post(
            commons.baseurl + "employees/Employees/getEmpTables", {
              id: employee_id
            },
            function(result) {
              result = JSON.parse(result);
              if (result.Code == "0") {
                if (result.Data.familybackgroundchildrens.length > 0) {
                  $("#tbchildres").empty();
                  $.each(
                    result.Data.familybackgroundchildrens,
                    function(k, v) {
                      $("#tbchildres").append(
                        "<tr>" +
                        "<td>" +
                        '<div class="form-group">' +
                        '<div class="form-line">' +
                        '<textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="children_name[' +
                        k +
                        ']" id="children_name[' +
                        k +
                        ']" >' +
                        v.children_name +
                        "</textarea>" +
                        "</div>" +
                        "</div>" +
                        "</td>" +
                        "<td>" +
                        '<div class="form-group">' +
                        '<div class="form-line">' +
                        '<input type="text" class="form-control is_first_col_required_birthday date_mask" value="' +
                        (v.children_birthday != null ?
                          v.children_birthday.replace(
                            " 00:00:00",
                            ""
                          ) :
                          "") +
                        '" name="children_birthday[' +
                        k +
                        ']" id="children_birthday[' +
                        k +
                        ']" >' +
                        "</div>" +
                        "</div>" +
                        "</td>" +
                        '<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                      );
                    }
                  );
                }
                if (result.Data.civilserviceeligibility.length > 0) {
                  $("#tbcse").empty();
                  $.each(result.Data.civilserviceeligibility, function(
                    k,
                    v
                  ) {
                    // k =
                    //   result.Data.civilserviceeligibility.length -
                    //   1 -
                    //   k;
                    $("#tbcse").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;"" class="form-control no-resize auto-growth  inputRequired" name="civil_service_eligibility[' +
                      k +
                      ']" id="civil_service_eligibility[' +
                      k +
                      ']" >' +
                      v.civil_service_eligibility +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.rating +
                      '" class="form-control is_first_col_required" name="rating[' +
                      k +
                      ']" id="rating[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      (v.date_conferment != null ?
                        v.date_conferment.replace(" 00:00:00", "") :
                        "") +
                      '" class="form-control is_first_col_required_examination date_picker" name="date_conferment[' +
                      k +
                      ']" id="date_conferment[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="place_examination[' +
                      k +
                      ']" id="place_examination[' +
                      k +
                      ']" >' +
                      v.place_examination +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth" name="license_number[' +
                      k +
                      ']" id="license_number[' +
                      k +
                      ']" >' +
                      v.license_number +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      (v.license_validity != null ?
                        v.license_validity.replace(" 00:00:00", "") :
                        "") +
                      '" class="form-control date_mask" name="license_validity[' +
                      k +
                      ']" id="license_validity[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.workexperience.length > 0) {
                  $("#tbworkexp").empty();
                  $.each(result.Data.workexperience, function(k, v) {
                    k = result.Data.workexperience.length - 1 - k;
                    $("#tbworkexp").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      (v.work_from != null ?
                        v.work_from.replace(" 00:00:00", "") :
                        "") +
                      '" class="form-control is_third_col_required_from date_mask" name="work_from[' +
                      k +
                      ']" id="work_from[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td>' +
                      '    <div class="form-group"><div class="form-line">' +
                      '     <input type="text" value="' + v.work_to + '" class="form-control is_third_col_required_to date_mask ' + (v.work_to == "PRESENT" ? 'remove_date_format' : '') + '" name="work_to[' + k + ']" id="work_to[' + k + ']" >' +
                      '    </div></div>' +
                      '    <div class="form-group">' +
                      '     <input type="checkbox" class="chk is_work_present" ' + (v.work_to == "PRESENT" ? 'checked' : '') + '> PRESENT' +
                      '    </div>' +
                      ' </td>' +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="position[' +
                      k +
                      ']" id="position[' +
                      k +
                      ']" >' +
                      v.position +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" name="company[' +
                      k +
                      ']" id="company[' +
                      k +
                      ']" >' +
                      v.company +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line">'+
                      '<input type="checkbox" name="day_check['+ k +']" style="position:absolute;left:70px;top:6px;opacity:1;" '+ v.per_day +'>'+
                      '<span style="position:absolute;left:86px;top:6px;opacity:1;">/day</span>'+
                      '<input type="text" value="' +
                      (v.monthly_salary != null ?
                        v.monthly_salary :
                        "") +
                      '" class="form-control is_third_col_required currency3" name="monthly_salary[' +
                      k +
                      ']" id="monthly_salary[' +
                      k +
                      ']" >'+
                      
                      '</div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" class="form-control ' +
                      (v.gov_service == "Y" ?
                        "is_third_col_required" :
                        "") +
                      ' salaryGrade" name="grade[' +
                      k +
                      ']" id="grade[' +
                      k +
                      ']" value="' +
                      v.grade +
                      '" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" class="form-control" name="status_appointment[' +
                      k +
                      ']" id="status_appointment[' +
                      k +
                      ']" >' +
                      v.status_appointment +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.gov_service +
                      '" class="form-control is_third_col_required govtservice" name="gov_service[' +
                      k +
                      ']" id="gov_service[' +
                      k +
                      ']" maxlength="1" ></div></div></td>' +
                      ' <th></button><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button><button type="button" class="btn btn-primary btn-sm addRow" style="float: right"><i class="material-icons">add</i></th>' +
                      "</tr>"
                    );
                  });
                                                
                  $(".chk").iCheck("destroy");
                  $(".chk").iCheck({
                    checkboxClass: "icheckbox_square-grey"
                  });
                }
                if (result.Data.voluntarywork.length > 0) {
                  $("#tbvoluntarywork").empty();
                  $.each(result.Data.voluntarywork, function(k, v) {
                    // k = result.Data.voluntarywork.length - 1 - k;
                    $("#tbvoluntarywork").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="organization[' +
                      k +
                      ']" id="organization[' +
                      k +
                      ']" >' +
                      v.organization +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.organization_work_from +
                      '" class="form-control is_first_col_required date_mask attendance_from" name="organization_work_from[' +
                      k +
                      ']" id="organization_work_from[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.organization_work_to +
                      '" class="form-control is_first_col_required date_mask attendance_to"  name="organization_work_to[' +
                      k +
                      ']" id="organization_work_to[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="number" value="' +
                      (v.organization_number_hours != 0 ?
                        v.organization_number_hours :
                        "") +
                      '" class="form-control  is_first_col_required" name="organization_number_hours[' +
                      k +
                      ']" id="organization_number_hours[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="organization_work_nature[' +
                      k +
                      ']" id="organization_work_nature[' +
                      k +
                      ']" >' +
                      v.organization_work_nature +
                      "</textarea></div></div></td>" +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.learningdevelopment.length > 0) {
                  $("#tblearnings").empty();
                  $.each(result.Data.learningdevelopment, function(
                    k,
                    v
                  ) {
                    k = result.Data.learningdevelopment.length - 1 - k;
                    $("#tblearnings").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="training[' +
                      k +
                      ']" id="training[' +
                      k +
                      ']" >' +
                      v.training +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      (v.traning_from != null ?
                        v.traning_from.replace(" 00:00:00", "") :
                        "") +
                      '" class="form-control is_first_col_required date_mask traningfrom" name="traning_from[' +
                      k +
                      ']" id="traning_from[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.training_to +
                      '" class="form-control is_first_col_required date_mask traningto" name="training_to[' +
                      k +
                      ']" id="training_to[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="number" value="' +
                      (v.training_number_hours != 0 ?
                        v.training_number_hours :
                        "") +
                      '" class="form-control is_first_col_required" name="training_number_hours[' +
                      k +
                      ']" id="training_number_hours[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_type[' +
                      k +
                      ']" id="training_type[' +
                      k +
                      ']" >' +
                      v.training_type +
                      "</textarea></div></div></td>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_sponsored_by[' +
                      k +
                      ']" id="training_sponsored_by[' +
                      k +
                      ']" >' +
                      v.training_sponsored_by +
                      "</textarea></div></div></td>" +
                      ' <th><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button><button type="button" class="btn btn-primary btn-sm addRow2" style="float: right"><i class="material-icons">add</i></button></th>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.specialskills.length > 0) {
                  $("#tbspecialskils").empty();
                  $.each(result.Data.specialskills, function(k, v) {
                    $("#tbspecialskils").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="special_skills[' +
                      k +
                      ']" id="special_skills[' +
                      k +
                      ']" >' +
                      v.special_skills +
                      "</textarea></div></div></td>" +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.recognitions.length > 0) {
                  $("#tbrecognitions").empty();
                  $.each(result.Data.recognitions, function(k, v) {
                    $("#tbrecognitions").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="recognitions[' +
                      k +
                      ']" id="recognitions[' +
                      k +
                      ']" >' +
                      v.recognitions +
                      "</textarea></div></div></td>" +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.organizations.length > 0) {
                  $("#tborganizations").empty();
                  $.each(result.Data.organizations, function(k, v) {
                    $("#tborganizations").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="membership[' +
                      k +
                      ']" id="membership[' +
                      k +
                      ']" >' +
                      v.organization +
                      "</textarea></div></div></td>" +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.references.length > 0) {
                  $("#tbreferences").empty();
                  $.each(result.Data.references, function(k, v) {
                    $("#tbreferences").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.reference_name +
                      '" class="form-control  inputRequired" name="reference_name[' +
                      k +
                      ']" id="reference_name[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.reference_address +
                      '" class="form-control is_first_col_required" name="reference_address[' +
                      k +
                      ']" id="reference_address[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><div class="form-group"><div class="form-line"><input type="text" value="' +
                      v.reference_tel_no +
                      '" class="form-control is_first_col_required" name="reference_tel_no[' +
                      k +
                      ']" id="reference_tel_no[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
                if (result.Data.educbgelementary.length > 0) {
                  insertEducRows(
                    result.Data.educbgelementary,
                    "elementary",
                    "btnAddELEM"
                  );
                }
                if (result.Data.educbgsecondary.length > 0) {
                  insertEducRows(
                    result.Data.educbgsecondary,
                    "secondary",
                    "btnAddSEC"
                  );
                }
                if (result.Data.educbgvocationals.length > 0) {
                  insertEducRows(
                    result.Data.educbgvocationals,
                    "vocational",
                    "btnAddVTC"
                  );
                }
                if (result.Data.educbgcolleges.length > 0) {
                  insertEducRows(
                    result.Data.educbgcolleges,
                    "college",
                    "btnAddC"
                  );
                }
                if (result.Data.educbggradstuds.length > 0) {
                  insertEducRows(
                    result.Data.educbggradstuds,
                    "grad_stud",
                    "btnAddGS"
                  );
                }

                initValidation();
                addDateMask();
              }
            }
          );

          $.post(
            commons.baseurl + "employees/Employees/getEmpAttachments", {
              id: employee_id
            },
            function(result) {
              result = JSON.parse(result);
              if (result.Code == "0") {
                if (result.Data.length > 0) {
                  $("#tbFiles").empty();
                  $.each(result.Data, function(k, v) {
                    $("#tbFiles").append(
                      "<tr>" +
                      ' <td><div class="form-group"><div class="form-line">' +
                      '<input type="hidden" class="form-control" name="cur_file_name[' +
                      k +
                      ']" value="' +
                      v.file_title +
                      '">' +
                      '<input type="hidden" class="form-control" name="index_id[' +
                      k +
                      ']" value="' +
                      v.id +
                      '">' +
                      '<input type="hidden" class="form-control" name="cur_file[' +
                      k +
                      ']" value="' +
                      v.uploaded_file +
                      '">' +
                      '<input type="hidden" class="form-control" name="uploaded_file[' +
                      k +
                      ']" value="' +
                      v.uploaded_file +
                      '">' +
                      '<textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="file_title[' +
                      k +
                      ']" id="file_title[' +
                      k +
                      ']" >' +
                      v.file_title +
                      "</textarea></div></div></td>" +
                      '   <td><a href="../uploads/employees/' +
                      employee_id +
                      "/" +
                      v.uploaded_file +
                      '" target="_blank" >View Uploaded File</a></td>' +
                      '<td><div class="form-group"><div class="form-line"><input type="file" class="form-control is_first_col_required" name="new_file[' +
                      k +
                      ']" id="new_file[' +
                      k +
                      ']" ></div></div></td>' +
                      ' <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                      "</tr>"
                    );
                  });
                }
              }
            }
          );
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({
              checkboxClass: "icheckbox_square-grey"
            });
            $(".chkradio").iCheck({
              radioClass: "iradio_square-grey"
            });
          }, 1000);
          $.AdminBSB.select.activate();
          $("#aniimated-thumbnials").lightGallery({
            thumbnail: true,
            selector: "a",
          });
          $(".form-control").css("z-index", 1);
          $(".headcol").css("z-index", 2);
          addDateMask();
          initValidation();
          $(".remove_date_format").inputmask("remove").val("PRESENT").css("pointer-events", "none");
          tmpNextBtn();
          autosize($("textarea.auto-growth"));
        }
      );
    }
  });

  $(document).on("click", ".deleteRow", function() {
    var totTableRows = $(this).closest("tbody").find("tr").length;
    if (totTableRows > 1) $(this).closest("tr").remove();
  });

  $(document).on("click", "#btnViewAddFile", function() {
    if ($("#tbFiles tr").length == 0) {
      k = 0;
      $("#tbFiles").append(
        "<tr>" +
        '<td><div class="form-group"><div class="form-line">' +
        '<input type="hidden" class="form-control" name="cur_file_name[' +
        k +
        ']">' +
        '<input type="hidden" class="form-control" name="index_id[' +
        k +
        ']">' +
        '<input type="hidden" class="form-control" name="cur_file[' +
        k +
        ']">' +
        '<input type="hidden" class="form-control" name="uploaded_file[' +
        k +
        ']">' +
        '<textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="file_title[' +
        k +
        ']" id="file_title[' +
        k +
        ']" ></textarea></div></div></td>' +
        "<td></td>" +
        '<td><div class="form-group"><div class="form-line"><input type="file" class="form-control is_first_col_required" name="new_file[' +
        k +
        ']" id="new_file[' +
        k +
        ']" ></div></div></td>' +
        '<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
        "</tr>"
      );
      initValidation();
    } else addrow($(this), 6);
  });

  $(document).on("click", "#btnAddCH, #btnAddFile", function() {
    addrow($(this), 2);
  });

  $(document).on("click", "#btnAddCSE, #btnAddLDI", function() {
    addrow($(this), 6);
  });

  $(document).on("click", "#btnAddWE", function () {
    addrow($(this), 8);
  });

  $(document).on("click", ".addRow", function() {
    if ($('.is_work_present').closest("td").find("input:first").val() == "PRESENT") {
      if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {

        var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
        var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date of work.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {
          var currentInput = $(this).closest("tr");
          var newInput = currentInput.clone();
          newInput.find(".iCheck-helper").remove();
          newInput.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position", "").css("display", "inline-table");
          
          currentInput.find("input, textarea").val("");
          // if (currentInput.find("td").length === 8) {
          //   currentInput.find("td:eq(1)").find('div.form-group:eq(1)').remove();
          //   currentInput.find("td:eq(1)").append('<div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div>');
          // }
          currentInput.find("input, checkbox").attr('checked', false);
          currentInput.before(newInput);
          setTimeout(() => {
            var currentTable = $(this).closest("tbody");
            var currentIndex = $(this).closest("tr").index();
            // alert(currentIndex);
            for (var i = 0; i < currentIndex; i++) {
              var currentRow = currentTable.find("tr:eq(" + i + ")");
              currentRow.find("td").each(function() {
                $(this).find("input, textarea").each(function() {
                    if($(this).attr("name") !== undefined){
                      var inputName = $(this).attr("name").split("[");
                      var plainName = inputName[0];
                      var inputIndex = parseInt(inputName[1].slice(0, -1)) + 1;
                      inputName = plainName + "[" + inputIndex + "]";
                      $(this).attr("name", inputName).attr("id", inputName);
                    }
                });
              });
            }
          }, 500);
          addDateMask();
          initValidation();
          autosize($("textarea.auto-growth"));
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({
              checkboxClass: "icheckbox_square-grey"
            });
            $(".chkradio").iCheck({
              radioClass: "iradio_square-grey"
            });
          }, 500);
        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }

    } else {
      if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "" && $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() != "") {

        var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
        var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date of work.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {
          var currentInput = $(this).closest("tr");
          var newInput = currentInput.clone();
          newInput.find(".iCheck-helper").remove();
          newInput.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position", "").css("display", "inline-table");

          currentInput.find("input, textarea").val("");
          // if (currentInput.find("td").length === 8) {
          //   currentInput.find("td:eq(1)").find('div.form-group:eq(1)').remove();
          //   currentInput.find("td:eq(1)").append('<div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div>');
          // }
          currentInput.find("input, checkbox").attr('checked', false);
          currentInput.before(newInput);
          setTimeout(() => {
            var currentTable = $(this).closest("tbody");
            var currentIndex = $(this).closest("tr").index();
            // alert(currentIndex);
            for (var i = 0; i < currentIndex; i++) {
              var currentRow = currentTable.find("tr:eq(" + i + ")");
              currentRow.find("td").each(function() {
                $(this).find("input, textarea").each(function() {
                    if($(this).attr("name") !== undefined){
                      var inputName = $(this).attr("name").split("[");
                      var plainName = inputName[0];
                      var inputIndex = parseInt(inputName[1].slice(0, -1)) + 1;
                      inputName = plainName + "[" + inputIndex + "]";
                      $(this).attr("name", inputName).attr("id", inputName);
                    }
                });
              });
            }
          }, 500);
          addDateMask();
          initValidation();
          autosize($("textarea.auto-growth"));
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({
              checkboxClass: "icheckbox_square-grey"
            });
            $(".chkradio").iCheck({
              radioClass: "iradio_square-grey"
            });
          }, 500);
        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }
    }
  });


  $(document).on("click", ".addRow2", function() {
    if ($('.is_work_present').closest("td").find("input:first").val() == "PRESENT") {
      if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "") {

        var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
        var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date of traning.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {
          var currentInput = $(this).closest("tr");
          var newInput = currentInput.clone();
          currentInput.find("input, textarea").val("");
          if (currentInput.find("td").length === 8) {
            currentInput.find("td:eq(1)").find('div.form-group:eq(1)').remove();
            currentInput.find("td:eq(1)").append('<div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div>');
          }
          currentInput.before(newInput);
          setTimeout(() => {
            var currentTable = $(this).closest("tbody");
            var currentIndex = $(this).closest("tr").index();
            // alert(currentIndex);
            for (var i = 0; i < currentIndex; i++) {
              var currentRow = currentTable.find("tr:eq(" + i + ")");
              currentRow.find("td").each(function() {
                var input = $(this).find("input,textarea");
                // console.log(input.attr("name"));
                var inputName = input.attr("name").split("[");
                var plainName = inputName[0];
                var inputIndex = parseInt(inputName[1].slice(0, -1)) + 1;
                inputName = plainName + "[" + inputIndex + "]";
                input.attr("name", inputName).attr("id", inputName);
              });
            }
          }, 500);
          addDateMask();
          initValidation();
          autosize($("textarea.auto-growth"));
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({
              checkboxClass: "icheckbox_square-grey"
            });
            $(".chkradio").iCheck({
              radioClass: "iradio_square-grey"
            });
          }, 500);
        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }

    } else {
      if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "" && $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() != "") {

        var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
        var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date of work.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {
          var currentInput = $(this).closest("tr");
          var newInput = currentInput.clone();
          currentInput.find("input, textarea").val("");
          if (currentInput.find("td").length === 8) {
            currentInput.find("td:eq(1)").find('div.form-group:eq(1)').remove();
            currentInput.find("td:eq(1)").append('<div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div>');
          }
          currentInput.before(newInput);
          setTimeout(() => {
            var currentTable = $(this).closest("tbody");
            var currentIndex = $(this).closest("tr").index();
            // alert(currentIndex);
            for (var i = 0; i < currentIndex; i++) {
              var currentRow = currentTable.find("tr:eq(" + i + ")");
              currentRow.find("td").each(function() {
                var input = $(this).find("input,textarea");
                // console.log(input.attr("name"));
                var inputName = input.attr("name").split("[");
                var plainName = inputName[0];
                var inputIndex = parseInt(inputName[1].slice(0, -1)) + 1;
                inputName = plainName + "[" + inputIndex + "]";
                input.attr("name", inputName).attr("id", inputName);
              });
            }
          }, 500);
          addDateMask();
          initValidation();
          autosize($("textarea.auto-growth"));
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({
              checkboxClass: "icheckbox_square-grey"
            });
            $(".chkradio").iCheck({
              radioClass: "iradio_square-grey"
            });
          }, 500);
        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }
    }
  });


  //Ajax non-forms
  $(document).on(
    "click", ".viewEmployeesForm",
    function(e) {
        e.preventDefault();
        my = $(this);
        data = my.data();

        id = my.data("id");
        f_name = my.data("first_name");
        m_name = my.data("middle_name");
        l_name = my.data("last_name");
        extension = my.data("extension");
        employee_name = l_name + ', ' + f_name + ' ' + m_name + ' ' + extension;
        employee_id = id;
        url = my.attr("href");
        if (!my.find("button").is(":disabled")) {
            getFields.reloadModal();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id
                },
                dataType: "json",
                success: function(result) {
                    page = my.attr("id");
                    if (result.hasOwnProperty("key")) {
                        status_key = result.key;
                        switch (status_key) {
                          case "viewEmployees":
                              page = "";
                              $("#myModal .modal-dialog").attr(
                                  "class",
                                  "modal-dialog modal-lg"
                              );
                              $("#myModal .modal-title").html(
                                  'Employee Personal Data Sheet Details <button type="button" id="btnPrintDetails" class="btn btn-sm btn-success"> Print <i class="material-icons">print</i></button>'
                              );
                              $("#myModal .modal-body").html(result.form);
                              $(".modal-lg").css("width", "1060px");
                          break;
                        }
                        $('.emp_name').text(employee_name);
                        $.when(
                            //getFields.position(),
                            getFields.payrollGrouping(),
                            getFields.leaveGrouping(),
                            getFields.agency(),
                            getFields.office(),
                            getFields.division(),
                            getFields.location(),
                            getFields.employmentStatus(),
                            getFields.contract(),
                            // getFields.payBasis(),
                            getFields.shift(),
                            getFields.flexibleShift(),
                            getFields.allowance()
                        ).done(function() {
                            if (my.data("mp2_contribution") != "" && my.data("with_mp2_contributions") != 0) {
                                $('#myModal .with_mp2_contributions').prop("checked", true);
                                $('#myModal .mp2_contribution').val(my.data("mp2_contribution"));
                            } else {
                                $('#myModal .with_mp2_contributions').prop("checked", false);
                                $('#myModal .mp2_contribution').val("0.00");
                            }
                            $('.account_number').val(my.data("account_number"));
                            $('#myModal #est_annual_net_tax_amnt').removeAttr("readonly");
                            $('#myModal #philhealth_contribution').val(my.data("philhealth_cos"));
                            /*if (my.data("pay_basis") == "Contract of Service") {
                                $('#myModal #philhealth_contribution').val(my.data("philhealth_cos"));
                            }*/
                            $.each(my.data(), function(i, v) {
                                if (i != "position_id") {
                                    if (i == "item_no") {
                                        item_no = my.data(i);
                                        item_flag_update = true;
                                    }
                                    if (i == "salary_grade_id") salary_grade_id = my.data(i);
                                    if (i == "salary_grade_step_id")
                                        salary_grade_step_id = my.data(i);
                                    if (i.slice(0, 5) === "radio") {
                                        if (parseInt($("#printPreview").length) > 0) {
                                            $("#myModal ." + i).append("&nbsp;&nbsp;" + my.data(i));
                                        }
                                        if (my.data(i) === "Yes") {
                                            $("#myModal #" + i + "_Yes").html("&#10004;")
                                                .prop("checked", true)
                                                .attr("checked", "checked");
                                        } else {
                                            $("#myModal #" + i + "_No").html("&#10004;")
                                                .prop("checked", true)
                                                .attr("checked", "checked");
                                        }
                                    } else if (
                                        i === "regular_shift" ||
                                        i === "with_gsis" ||
                                        i === "with_philhealth_contribution" ||
                                        i === "with_pera" ||
                                        i === "with_pagibig_contribution" ||
                                        i === "with_union_dues"
                                    ) {
                                        if (my.data(i) === 1)
                                            $("#myModal ." + i).prop("checked", true);
                                    } else {
                                        if (parseInt($("#printPreview").length) > 0) {
                                            $("#myModal ." + i).html(my.data(i));
                                            if (
                                                i === "gender" ||
                                                i === "civil_status" ||
                                                i === "nationality"
                                            ) {
                                                $("#" + i + "_" + my.data(i)).html("&#10004;");
                                                // ("checked", true)
                                                // .attr("checked", "checked");
                                            }
                                            if (i === "nationality_details") {
                                                $("#nationality_" + my.data(i)).html("&#10004;");
                                                // .prop("checked", true)
                                                // .attr("checked", "checked");
                                            }
                                        }
                                        // else if (i == "birthday") {
                                        //   birthdate = my.data('birthday').split('/');
                                        //   $('.birthday').val(""+birthdate[0]+"-"+birthdate[1]+"-"+birthdate[2]+"");
                                        // }
                                        else {
                                            $("#myModal ." + i)
                                                .val(my.data(i))
                                                .change();
                                        }
                                    }

                                    if (v == "0" || v == "1") $("#myModal ." + i + v).click();
                                }
                            });
                            if (result.key == "viewEmployees") {
                                $.ajax({
                                    url: commons.baseurl +
                                        "employees/Employees/getActivePhotoByEmployeeId",
                                    data: {
                                        employee_id: employee_id
                                    },
                                    type: "POST",
                                    dataType: "json",
                                    success: function(result_get_photo) {
                                        photo =
                                            commons.baseurl +
                                            "assets/custom/images/default-avatar.jpg";
                                        if (result_get_photo.Code == "0") {
                                            if (result_get_photo.Data.details[0].employee_id_photo != "") {
                                                photo = result_get_photo.Data.details[0].employee_id_photo;
                                            }
                                        }
                                        $("#pdf_emp_photo").attr("src", photo);
                                    },
                                });
                            }
                            if (result.key == "viewEmployees") {
                                $.post(
                                    commons.baseurl + "employees/Employees/getEmpTables", {
                                        id: employee_id
                                    },
                                    function(result) {
                                        result = JSON.parse(result);
                                        if (result.Code == "0") {
                                            var tot_page = 0;
                                            var num_per_page = 0;

                                            tot_page++;
                                            num_per_page++;
                                            $(".num_per_page:eq(0)").html(num_per_page);
                                            var childrens = result.Data.familybackgroundchildrens;
                                            if (childrens.length > 0) {
                                                $.each(childrens, function(k, v) {
                                                    if (k > 11) {
                                                        var vtc =
                                                            k === 12 ?
                                                            $(".children").clone() :
                                                            $(".children:last").clone();
                                                        if (k === 12) $(".children").remove();
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.children_name == "" ? "N/A" : v.children_name);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(1)")
                                                            .html(v.children_birthday.length > 0 ? v.children_birthday.replace(" 00:00:00", "") : "N/A");
                                                        $("#childrens tr:last").after(vtc);
                                                    } else {
                                                        $(".children_name" + k).html(v.children_name == "" ? "N/A" : v.children_name);
                                                        $(".children_birthday" + k).html(v.children_birthday.length > 0 ?
                                                            v.children_birthday.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                    }
                                                });
                                                var app = "";

                                                app += "<tr>";
                                                app +=
                                                    '<td width="35%" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td width="15%" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td width="35%" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td width="15%"></td>';
                                                app += "</tr>";
                                                $("#childrens").append(app);
                                            }
                                            if (childrens.length <= 11)
                                                $("#childrens, .pager_table:eq(1)").hide();
                                            else {
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(1)").html(num_per_page);
                                            }

                                            var educbgelem = result.Data.educbgelementary.length;
                                            var educbgsec = result.Data.educbgsecondary.length;
                                            var educbgvoc = result.Data.educbgvocationals.length;
                                            var educbgcol = result.Data.educbgcolleges.length;
                                            var educbggrad = result.Data.educbggradstuds.length;
                                            if (educbgelem > 0)
                                                insertPrintEducRows(
                                                    result.Data.educbgelementary,
                                                    "elementary",
                                                    "educs_elem"
                                                );
                                            if (educbgsec > 0)
                                                insertPrintEducRows(
                                                    result.Data.educbgsecondary,
                                                    "secondary",
                                                    "educs_sec"
                                                );
                                            if (educbgvoc > 0)
                                                insertPrintEducRows(
                                                    result.Data.educbgvocationals,
                                                    "vocational",
                                                    "educs_voc"
                                                );
                                            if (educbgcol > 0)
                                                insertPrintEducRows(
                                                    result.Data.educbgcolleges,
                                                    "college",
                                                    "educs_college"
                                                );
                                            if (educbggrad > 0)
                                                insertPrintEducRows(
                                                    result.Data.educbggradstuds,
                                                    "grad_stud",
                                                    "educs_grads"
                                                );
                                            if (educbgelem === 1) $(".educs_elem").remove();
                                            if (educbgsec === 1) $(".educs_sec").remove();
                                            if (educbgvoc === 1) $(".educs_voc").remove();
                                            if (educbgcol === 1) $(".educs_college").remove();
                                            if (educbggrad === 1) $(".educs_grads").remove();
                                            if (
                                                educbgelem <= 1 &&
                                                educbgsec <= 1 &&
                                                educbgvoc <= 1 &&
                                                educbgcol <= 1 &&
                                                educbggrad <= 1
                                            )
                                                $("#educs, .pager_table:eq(2)").hide();
                                            else {
                                                var app = "";
                                                app += "<tr>";
                                                app +=
                                                    '<td colspan="3" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td colspan="6" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td colspan="2" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td colspan="4"></td>';
                                                app += "</tr>";
                                                $("#educs").append(app);
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(2)").html(num_per_page);
                                            }
                                            tot_page++;
                                            num_per_page++;
                                            $(".num_per_page:eq(3)").html(num_per_page);
                                            var civilservice = result.Data.civilserviceeligibility;
                                            if (civilservice.length > 0) {
                                                $.each(civilservice, function(k, v) {
                                                    if (k > 6) {
                                                        var vtc =
                                                            k === 7 ?
                                                            $(".civilservice").clone() :
                                                            $(".civilservice:last").clone();
                                                        if (k === 7) $(".civilservice").remove();
                                                        var vtc = cloneRow;
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.civil_service_eligibility == "" ? "N/A" : v.civil_service_eligibility);
                                                        vtc.closest("tr").find("td:eq(1)").html(v.rating == "" ? "N/A" : v.rating);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(2)")
                                                            .html(v.date_conferment.length > 0 ?
                                                                v.date_conferment.replace(" 00:00:00", "") :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(3)")
                                                            .html(v.place_examination == "" ? "N/A" : v.place_examination);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(4)")
                                                            .html(v.license_number == "" ? "N/A" : v.license_number);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(5)")
                                                            .html(v.license_validity.length > 0 ?
                                                                v.license_validity.replace(
                                                                    " 00:00:00",
                                                                    ""
                                                                ) :
                                                                "N/A"
                                                            );
                                                        $("#civilservices tr:last").after(vtc);
                                                    } else {
                                                        $(".civilservice_eligibity" + k).html(
                                                            v.civil_service_eligibility == "" ? "N/A" : v.civil_service_eligibility
                                                        );
                                                        $(".civilservice_rating" + k).html(v.rating == "" ? "N/A" : v.rating);
                                                        $(".civilservice_dateconferment" + k).html(v.date_conferment.length > 0 ?
                                                            v.date_conferment.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                        $(".civilservice_placeconferment" + k).html(
                                                            v.place_examination == "" ? "N/A" : v.place_examination
                                                        );
                                                        $(".civilservice_licensenum" + k).html(
                                                            v.license_number == "" ? "N/A" : v.license_number
                                                        );
                                                        $(".civilservice_datevalidity" + k).html(v.license_validity.length > 0 ?
                                                            v.license_validity.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                    }
                                                });
                                            }
                                            if (civilservice.length <= 6)
                                                $("#civilservices, .pager_table:eq(4)").hide();
                                            else {
                                                var app = "";
                                                app += "<tr>";
                                                app +=
                                                    '<td colspan="5" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td colspan="4" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td colspan="2" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td colspan="4"></td>';
                                                app += "</tr>";
                                                $("#civilservices").append(app);
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(4)").html(num_per_page);
                                            }
                                            var workexps = result.Data.workexperience;
                                            if (workexps.length > 0) {
                                                $.each(workexps, function(k, v) {
                                                    if (k > 27) {
                                                        var vtc =
                                                            k === 28 ?
                                                            $(".workexp").clone() :
                                                            $(".workexp:last").clone();
                                                        $(".workexp").remove();
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.work_from.length > 0 ?
                                                                v.work_from.replace(" 00:00:00", "") :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(1)")
                                                            .html(v.work_to.length > 0 ?
                                                                v.work_to.replace(" 00:00:00", "") :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(2)")
                                                            .html(v.position == "" ? "N/A" : v.position);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(3)")
                                                            .html(v.company == "" ? "N/A" : v.company);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(4)")
                                                            .html(
                                                                v.monthly_salary.length > 0 ?
                                                                addCommas(v.monthly_salary.replace(/,/g, '')) :
                                                                "N/A"
                                                            );
                                                        vtc.closest("tr").find("td:eq(5)").html(v.grade == "" ? "N/A" : v.grade);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(6)")
                                                            .html(v.status_appointment == "" ? "N/A" : v.status_appointment);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(7)")
                                                            .html(v.gov_service == "" ? "N/A" : v.gov_service);
                                                        $("#workexps tr:last").after(vtc);
                                                    } else {
                                                        $(".workexp_workfrom" + k).html(v.work_from.length > 0 ?
                                                            v.work_from.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                        $(".workexp_workto" + k).html(v.work_to.length > 0 ?
                                                            v.work_to.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                        $(".workexp_position" + k).html(v.position == "" ? "N/A" : v.position);
                                                        $(".workexp_company" + k).html(v.company == "" ? "N/A" : v.company);
                                                        $(".workexp_salary" + k).html(
                                                            v.monthly_salary.length > 0 ? addCommas(v.monthly_salary.replace(/,/g, '')) : "N/A"
                                                        );
                                                        $(".workexp_grade" + k).html(v.grade == "" ? "N/A" : v.grade);
                                                        $(".workexp_appointment" + k).html(
                                                            v.status_appointment == "" ? "N/A" : v.status_appointment
                                                        );
                                                        $(".workexp_govservice" + k).html(v.gov_service == "" ? "N/A" : v.gov_service);
                                                    }
                                                });
                                            }
                                            if (workexps.length <= 27)
                                                $("#workexps, .pager_table:eq(5)").hide();
                                            else {
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(5)").html(num_per_page);
                                            }
                                            tot_page++;
                                            num_per_page++;
                                            $(".num_per_page:eq(6)").html(num_per_page);
                                            var voluntaryworks = result.Data.voluntarywork;
                                            if (voluntaryworks.length > 0) {
                                                $.each(voluntaryworks, function(k, v) {
                                                    if (k > 6) {
                                                        var vtc =
                                                            k === 7 ?
                                                            $(".voluntarywork").clone() :
                                                            $(".voluntarywork:last").clone();
                                                        if (k === 7) $(".voluntarywork").remove();
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.organization == "" ? "N/A" : v.organization);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(1)")
                                                            .html(
                                                                v.organization_work_from.length > 0 ?
                                                                v.organization_work_from.replace(
                                                                    " 00:00:00",
                                                                    ""
                                                                ) :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(2)")
                                                            .html(
                                                                v.organization_work_to.length > 0 ?
                                                                v.organization_work_to.replace(
                                                                    " 00:00:00",
                                                                    ""
                                                                ) :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(3)")
                                                            .html(
                                                                v.organization_number_hours != 0 ?
                                                                v.organization_number_hours :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(4)")
                                                            .html(v.organization_work_nature == "" ? "N/A" : v.organization_work_nature);
                                                        $("#voluntaryworks tr:last").after(vtc);
                                                    } else {
                                                        $(".voluntarywork_organization" + k).html(
                                                            v.organization == "" ? "N/A" : v.organization
                                                        );
                                                        $(".voluntarywork_from" + k).html(
                                                            v.organization_work_from.length > 0 ?
                                                            v.organization_work_from.replace(
                                                                " 00:00:00",
                                                                ""
                                                            ) :
                                                            "N/A"
                                                        );
                                                        $(".voluntarywork_to" + k).html(
                                                            v.organization_work_to.length > 0 ?
                                                            v.organization_work_to.replace(
                                                                " 00:00:00",
                                                                ""
                                                            ) :
                                                            "N/A"
                                                        );
                                                        $(".voluntarywork_numhours" + k).html(
                                                            v.organization_number_hours != 0 ?
                                                            v.organization_number_hours :
                                                            "N/A"
                                                        );
                                                        $(".voluntarywork_nature" + k).html(
                                                            v.organization_work_nature == "" ? "N/A" : v.organization_work_nature
                                                        );
                                                    }
                                                });
                                            }
                                            if (voluntaryworks.length <= 6)
                                                $("#voluntaryworks, .pager_table:eq(7)").hide();
                                            else {
                                                var app = "";
                                                app += "<tr>";
                                                app +=
                                                    '<td colspan="6" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td colspan="2" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td colspan="2" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td colspan="4"></td>';
                                                app += "</tr>";
                                                $("#voluntaryworks").append(app);
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(7)").html(num_per_page);
                                            }
                                            var learnings = result.Data.learningdevelopment;
                                            if (learnings.length > 0) {
                                                $.each(learnings, function(k, v) {
                                                    if (k > 20) {
                                                        var vtc =
                                                            k === 21 ?
                                                            $(".learning").clone() :
                                                            $(".learning:last").clone();
                                                        if (k === 21) $(".learning").remove();
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.training == "" ? "N/A" : v.training);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(1)")
                                                            .html(v.traning_from.length > 0 ?
                                                                v.traning_from.replace(" 00:00:00", "") :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(2)")
                                                            .html(v.training_to.length > 0 ?
                                                                v.training_to.replace(" 00:00:00", "") :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(3)")
                                                            .html(
                                                                v.training_number_hours != 0 ?
                                                                v.training_number_hours :
                                                                "N/A"
                                                            );
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(4)")
                                                            .html(v.training_type == "" ? "N/A" : v.training_type);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(5)")
                                                            .html(v.training_sponsored_by == "" ? "N/A" : v.training_sponsored_by);
                                                        $("#learnings tr:last").after(vtc);
                                                    } else {
                                                        $(".learning_training" + k).html(v.training == "" ? "N/A" : v.training);
                                                        $(".learning_from" + k).html(v.traning_from.length > 0 ?
                                                            v.traning_from.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                        $(".learning_to" + k).html(v.training_to.length > 0 ?
                                                            v.training_to.replace(" 00:00:00", "") :
                                                            "N/A"
                                                        );
                                                        $(".learning_numhours" + k).html(
                                                            v.training_number_hours != 0 ?
                                                            v.training_number_hours :
                                                            "N/A"
                                                        );
                                                        $(".learning_type" + k).html(v.training_type == "" ? "N/A" : v.training_type);
                                                        $(".learning_sponsor" + k).html(
                                                            v.training_sponsored_by == "" ? "N/A" : v.training_sponsored_by
                                                        );
                                                    }
                                                });
                                            }
                                            if (learnings.length <= 20)
                                                $("#learnings, .pager_table:eq(8)").hide();
                                            else {
                                                var app = "";
                                                app += "<tr>";
                                                app +=
                                                    '<td colspan="6" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td colspan="2" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td colspan="2" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td colspan="4"></td>';
                                                app += "</tr>";
                                                $("#learnings").append(app);
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(8)").html(num_per_page);
                                            }
                                            var specialskills = result.Data.specialskills;
                                            $(".otherinfo").remove();
                                            if (specialskills.length > 0) {
                                                var cloneRow = $(".otherinfo").clone();
                                                $.each(specialskills, function(k, v) {
                                                    if (k > 6) {
                                                        var vtc = cloneRow;
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.training);
                                                        $("#otherinfos tr:last").after(vtc);
                                                    } else {
                                                        $(".specialskills_" + k).html(v.special_skills == "" ? "N/A" : v.special_skills);
                                                    }
                                                });
                                            }
                                            var recognitions = result.Data.recognitions;
                                            if (recognitions.length > 0) {
                                                var cloneRow = $(".otherinfo").clone();
                                                $(".otherinfo").remove();
                                                $.each(recognitions, function(k, v) {
                                                    if (k > 6) {
                                                        if (
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(0")
                                                            .html()
                                                            .trim() === "" &&
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(1")
                                                            .html()
                                                            .trim() === "" &&
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(2")
                                                            .html()
                                                            .trim() === ""
                                                        ) {
                                                            var vtc = cloneRow;
                                                            vtc
                                                                .closest("tr")
                                                                .find("td:eq(1)")
                                                                .html(v.recognitions == "" ? "N/A" : v.recognitions);
                                                            $("#otherinfos tr:last").after(vtc);
                                                        } else {
                                                            var count = $("table#otherinfos tr.otherinfo")
                                                                .length;
                                                            if (count < recognitions.length) {
                                                                var cloneRow = $(
                                                                    "table#otherinfos tr.otherinfo:last"
                                                                ).clone();
                                                                cloneRow
                                                                    .closest("tr")
                                                                    .find("td:eq(1)")
                                                                    .html(v.recognitions == "" ? "N/A" : v.recognitions);
                                                                $(
                                                                    "table#otherinfos tr.otherinfo:last"
                                                                ).append(cloneRow);
                                                            } else {
                                                                $(
                                                                    "table#otherinfos tr.otherinfo:eq(" +
                                                                    k +
                                                                    ") td:eq(1)"
                                                                ).html(v.recognitions == "" ? "N/A" : v.recognitions);
                                                            }
                                                        }
                                                    } else {
                                                        $(".recognitions_" + k).html(v.recognitions == "" ? "N/A" : v.recognitions);
                                                    }
                                                });
                                            }
                                            var organizations = result.Data.organizations;
                                            if (organizations.length > 0) {
                                                $.each(organizations, function(k, v) {
                                                    if (k > 6) {
                                                        if (
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(0")
                                                            .html()
                                                            .trim() === "" &&
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(1")
                                                            .html()
                                                            .trim() === "" &&
                                                            cloneRow
                                                            .closest("tr")
                                                            .find("td:eq(2")
                                                            .html()
                                                            .trim() === ""
                                                        ) {
                                                            var vtc = cloneRow;
                                                            vtc
                                                                .closest("tr")
                                                                .find("td:eq(2)")
                                                                .html(v.organization == "" ? "N/A" : v.organization);
                                                            $("#otherinfos tr:last").after(vtc);
                                                        } else {
                                                            var count = $("table#otherinfos tr.otherinfo")
                                                                .length;
                                                            if (count < organizations.length) {
                                                                var cloneRow = $(
                                                                    "table#otherinfos tr.otherinfo:last"
                                                                ).clone();
                                                                cloneRow
                                                                    .closest("tr")
                                                                    .find("td:eq(2)")
                                                                    .html(v.organization == "" ? "N/A" : v.organization);
                                                                $(
                                                                    "table#otherinfos tr.otherinfo:last"
                                                                ).append(cloneRow);
                                                            } else {
                                                                $(
                                                                    "table#otherinfos tr.otherinfo:eq(" +
                                                                    k +
                                                                    ") td:eq(2)"
                                                                ).html(v.organization == "" ? "N/A" : v.organization);
                                                            }
                                                        }
                                                    } else {
                                                        $(".organizations_" + k).html(v.organization == "" ? "N/A" : v.organization);
                                                    }
                                                });
                                            }

                                            if (
                                                specialskills.length <= 7 &&
                                                recognitions.length <= 7 &&
                                                organizations.length <= 7
                                            )
                                                $("#otherinfos, .pager_table:eq(9)").hide();
                                            else {
                                                var app = "";
                                                app += "<tr>";
                                                app +=
                                                    '<td width="33%" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td width="33%" colspan="2" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td width="17%" colspan="2" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td width="17%"></td>';
                                                app += "</tr>";
                                                $("#otherinfos").append(app);
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(9)").html(num_per_page);
                                            }

                                            tot_page++;
                                            num_per_page++;
                                            $(".num_per_page:eq(10)").html(num_per_page);
                                            var references = result.Data.references;
                                            if (result.Data.references.length > 0) {
                                                $.each(references, function(k, v) {
                                                    if (k > 2) {
                                                        var vtc =
                                                            k === 3 ?
                                                            $(".reference").clone() :
                                                            $(".reference:last").clone();
                                                        if (k === 3) $(".reference").remove();
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(0)")
                                                            .html(v.reference_name == "" ? "N/A" : v.reference_name);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(1)")
                                                            .html(v.reference_address == "" ? "N/A" : v.reference_address);
                                                        vtc
                                                            .closest("tr")
                                                            .find("td:eq(2)")
                                                            .html(v.reference_tel_no == "" ? "N/A" : v.reference_tel_no);
                                                        $("#references tr:last").after(vtc);
                                                    } else {
                                                        $(".reference_name" + k).html(v.reference_name == "" ? "N/A" : v.reference_name);
                                                        $(".reference_address" + k).html(
                                                            v.reference_address == "" ? "N/A" : v.reference_address
                                                        );
                                                        $(".reference_tel_no" + k).html(
                                                            v.reference_tel_no == "" ? "N/A" : v.reference_tel_no
                                                        );
                                                    }
                                                });
                                                var app = "";

                                                app += "<tr>";
                                                app +=
                                                    '<td width="33%" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">SIGNATURE</td>';
                                                app +=
                                                    '    <td width="33%" style="border-right:1px solid black;"></td>';
                                                app +=
                                                    '    <td width="17%" class="cellbg2" style="font-weight: bold;font-style: italic; text-align: center;height: 30px; vertical-align: middle;border-right:1px solid black;">DATE</td>';
                                                app += '    <td width="17%"></td>';
                                                app += "</tr>";
                                                $("#references").append(app);
                                            }
                                            if (references.length <= 3)
                                                $(
                                                    "#references, #references_signature, .pager_table:eq(11)"
                                                ).hide();
                                            else {
                                                tot_page++;
                                                num_per_page++;
                                                $(".num_per_page:eq(11)").html(num_per_page);
                                            }
                                            $(".tot_page").html(num_per_page);
                                        }
                                    }
                                );
                            }
                            if (result.key == "viewEmployees") {
                                $("#myModal form")
                                    .find("input, textarea, button, select")
                                    .attr("disabled", "disabled");
                                $("#myModal form").find("#cancelUpdateForm").removeAttr("disabled");
                                $(".chk,.chkradio").iCheck("destroy");
                                $(".chk").iCheck({
                                    // checkboxClass: "icheckbox_minimal-grey"
                                    checkboxClass: "icheckbox_square-grey"
                                });
                                $(".chkradio").iCheck({
                                    // radioClass: "iradio_minimal-grey"
                                    radioClass: "iradio_square-grey"
                                });
                            } else {
                                $(".chk,.chkradio").iCheck("destroy");
                                $(".chk").iCheck({
                                    checkboxClass: "icheckbox_square-grey"
                                });
                                $(".chkradio").iCheck({
                                    radioClass: "iradio_square-grey"
                                });
                            }
                            $.AdminBSB.select.activate();
                            $("#aniimated-thumbnials").lightGallery({
                                thumbnail: true,
                                selector: "a",
                            });
                            var width = 327;
                            var height = 327;
                            if (result.key === "addEmployees") {
                                Webcam.set({
                                    width: parseInt(width, 10) - 30,
                                    height: parseInt(height, 10) - 30,
                                    image_format: "jpeg",
                                    jpeg_quality: 90,
                                });
                                // Webcam.attach("#my_camera");
                                // Webcam.on("error", function (err) {});
                            }
                            // autosize($("textarea.auto-growth"));
                            // addDateMask();
                            // $(".remove_date_format").inputmask("remove").val("PRESENT").css("pointer-events", "none");;
                            // initValidation();
                            // tmpNextBtn();
                            // $(".tin").inputmask("999-999-999-999", {
                            //   placeholder: "___-___-___-___",
                            // });
                            // $(".gsis").inputmask("999-999-999-999", {
                            //   placeholder: "___-___-___-___",
                            // });
                            // $(".sss").inputmask("99-9999999-9", {
                            //   placeholder: "__-_______-___",
                            // });
                            // $(".pagibig").inputmask("999-999-999-999", {
                            //   placeholder: "___-___-___-___",
                            // });
                            // $(".philhealth").inputmask("99-999999999-9", {
                            //   placeholder: "__-_________-_",
                            // });
                        });
                    }
                    //Advanced form with validation
                },
                error: function(result) {
                    $.alert({
                        title: '<label class="text-danger">Failed</label>',
                        content: "There was an error in the connection. Please contact the administrator for updates.",
                    });
                },
            });
        }
    }
  );
  $(document).on("click", "#btnPrintDetails", function() {
    printPrev(document.getElementById("printPreview").innerHTML);
  });

  
  $(document).on("click", "#btnAddVW", function() {
    addrow($(this), 5);
  });

  $(document).on("click", "#btnAddSSH, #btnAddNAD, #btnAddMA", function() {
    addrow($(this), 1);
  });

  $(document).on("click", "#btnAddRef", function() {
    addrow($(this), 3);
  });
  
  $(document).on("change", "#civil_status", function() {
    if ($(this).val() === "Others"){
      $(".civil_status_others").prop("required", true);
      $(".civil_status_others").closest(".row").show();
    } else {
      $(".civil_status_others").closest(".row").hide();
    }
  });

  $(document).on("change", "#nationality", function() {
    if ($(this).val() !== "Filipino") {
      $(".nationality_country").closest(".row").show();
      $(".nationality_country").prop("required", true);
    } else $(".nationality_country").closest(".row").hide();
  });

  $(document).on(
    "click",
    ".btnAddELEM, .btnAddSEC, .btnAddVTC, .btnAddC, .btnAddGS",
    function() {
      addRowAfter($(this));
    }
  );

  $(document).on("click", ".update_file_row", function() {
    var formData = new FormData();
    var files = $(this).closest("tr").find("input:file")[0].files[0];
    formData.append("employee_id", $("#id").val());
    formData.append(
      "id",
      $(this).closest("tr").find("td:eq(0)").find("input:eq(1)").val()
    );
    formData.append("file_title", $(this).closest("tr").find("textarea").val());
    formData.append(
      "current_file_title",
      $(this).closest("tr").find("td:eq(0)").find("input:eq(0)").val()
    );
    formData.append("uploaded_file", files);
    submitRequest(
      commons.baseurl + "employees/PDS/updateSpecificEmployeesAttachment",
      formData,
      updateEmployeesCallback,
      "You want to proceed?"
    );
  });

  //Webcam Feature
  $(document).on("click", "#reset_snapshot", function(e) {
    e.preventDefault();
    $("#my_camera").show();
    $("#take_snapshot").show();
    $("#reset_snapshot").hide();
    $("#employeeImage").hide();
  });

  $(document).on("click", "#take_snapshot", function(e) {
    e.preventDefault();
    take_snapshot();
  });

  $(document).on("change", "#fileupload", function() {
    readURL(this);
    $("#employeeImage").show();
    $("#reset_snapshot").show();
    $("#take_snapshot").hide();
    $("#my_camera").hide();
  });

  $(document).on("keydown", ".govtservice", function(e) {
    var key = e.keyCode | e.which;
    if (key == 78 || key == 89 || (key >= 8 && key <= 46)) return true;
    else return false;
  });

  $(document).on("change", ".govtservice", function() {
    if ($(this).val() == "Y" || $(this).val() == "y") {
      $(this)
        .closest("td")
        .prev()
        .prev()
        .find(".form-control")
        .addClass("inputRequired")
        .addClass("is_third_col_required")
        .prop("readonly", false);
    } else {
      $(this)
        .closest("td")
        .prev()
        .prev()
        .find(".form-control")
        .removeClass("inputRequired")
        .removeClass("is_third_col_required")
        .prop("readonly", true)
        .val("");
    }
    initValidation();
  });

  $.validator.addMethod(
    "lettersonly",
    function(value, element) {
      return this.optional(element) || /^[a-z Ññ]+$/i.test(value);
    },
    "Letters only please"
  );

  jQuery.validator.addMethod(
    "noSpace",
    function(value, element) {
      return value.trim().length == 0 && value.trim() == "";
    },
    "White space only not valid"
  );


  $(document).on('ifUnchecked', ".is_work_present", function(event) {
    var first_input = $(this).closest("td").find("input:first");
    first_input.inputmask("mm/dd/yyyy", {
      placeholder: "mm/dd/yyyy",
    });
    first_input.css("pointer-events", "all");
  });
  $(document).on('ifChecked', ".is_work_present", function(event) {
    var first_input = $(this).closest("td").find("input:first");
    first_input.inputmask("remove"); //.rules("remove", "required");
    first_input.val("PRESENT").css("pointer-events", "none");
    // iCheck-helper
  });

});

function readURL(input) {
  //data_uri = window.URL.createObjectURL(input.prop('files')[0])
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $("#employeeImage").attr("src", e.target.result);
      $(".image-tag").attr("href", e.target.result);
    };

    reader.onload = function(e) {
      $("#employeeImage").attr("src", e.target.result);
      $(".image-tag").attr("href", e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function addRowAfter(element) {
  // var cloneRow = element.closest("div").find("table tbody tr:last").clone();
  var cloneRow = element.closest("tr").clone();
  // var elemName = element
  //   .closest("tr")
  //   .find("td:eq(1)")
  //   .find(".form-control")
  //   .attr("name")
  //   .slice(0, -3);
  // var inc = $("textarea[name^='" + elemName + "']").length;
  var inc = element
    .closest("tr")
    .find("td:eq(1) .form-control")
    .attr("name")
    .slice(0, -3);
  lst_elem = $("textarea[name^='" + inc + "']:last");
  inc = lst_elem.attr("name");
  // var inc = element.closest("div").find("table tbody tr:last").find(".form-control").attr("name");
  $is = false;
  if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
    inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
    $is = true;
  } else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
  for (var i = 0; i < 7; i++) {
    var elementName = "";
    if ($is)
      elementName =
      cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
      "[" +
      inc +
      "]";
    else
      elementName =
      cloneRow.find(".form-control").eq(i).attr("name").slice(0, -4) +
      "[" +
      inc +
      "]";
    cloneRow
      .find(".form-control")
      .eq(i)
      .attr("name", elementName)
      .attr("id", elementName);
  }
  cloneRow.find(".form-control").val("");
  cloneRow.find("td:eq(0)").html("");
  cloneRow
    .find("td:last")
    .html(
      '<button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button>'
    );
  lst_elem.closest("tr").after(cloneRow);
  addDateMask();
  initValidation();
  autosize($("textarea.auto-growth"));
}

function addrow(element, count) {
  if (element.attr("id") === "btnAddVW") {
    if ($('.is_work_present').closest("td").find("input:first").val() == "PRESENT") {
      if ($('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() != "") {

        var date = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() + '');
        var date2 = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_to').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date attendance.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {

        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }
    } else {
      if ($('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() != "" && $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_to').val() != "") {

        var date = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() + '');
        var date2 = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_to').val() + '');
        var datenow = new Date();
        if (datenow < date || datenow < date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "No future date of attendance.",
          });
          return false;
        } else if (date > date2) {
          $.alert({
            title: '<label class="text-danger">Failed</label>',
            content: "Date to must be greater than date from.",
          });
          return false;
        } else {

        }
      } else {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "Inclusive date is required.",
        });
        return false;
      }
    }
  }

  if (element.attr("id") === "btnAddCSE") {
    if ($('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() != "") {
      var date = new Date('' + $('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() + '');
      var datenow = new Date();
      if (datenow < date) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "No future date of examination.",
        });
        return false;
      } else {

      }
    } else {
      $.alert({
        title: '<label class="text-danger">Failed</label>',
        content: "Date of examination is required.",
      });
      return false;
    }
  }
  if (element.attr("id") === "btnAddCH") {
    if ($('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() != "") {

      var date = new Date('' + $('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() + '');
      var datenow = new Date();
      if (datenow < date) {
        $.alert({
          title: '<label class="text-danger">Failed</label>',
          content: "No future birth date of children.",
        });
        return false;
      } else {

      }
    } else {
      $.alert({
        title: '<label class="text-danger">Failed</label>',
        content: "Birth date of children is required.",
      });
      return false;
    }
  }

  var cloneRow = "";
  var inc = "";
  if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI") {
    var cloneRow = element.closest("div").find("table tbody tr:first").clone();
    inc = element
      .closest("div")
      .find("table tbody tr:first")
      .find(".form-control")
      .attr("name");

  } else {
    var cloneRow = element.closest("div").find("table tbody tr:last").clone();
    inc = element
      .closest("div")
      .find("table tbody tr:last")
      .find(".form-control")
      .attr("name");
  }
  console.log('sample')

  $is = false;
  if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
    inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
    $is = true;
  } else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
  cloneRow.find(".form-group").find("label.error").remove();
  for (var i = 0; i < count; i++) {
    if(element.attr("id") === "btnViewAddFile"){
      cloneRow.find("a").remove();
    }
    if (cloneRow.closest("td").find("a").length == 0) {
      var elementName = "";

      if ($is) {
        elementName =
          cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
          "[" +
          inc +
          "]";
      } else {
        elementName =
          cloneRow.find(".form-control").eq(i).attr("name").slice(0, -4) +
          "[" +
          inc +
          "]";
      }
      cloneRow
        .find(".form-control")
        .eq(i)
        .attr("name", elementName)
        .attr("id", elementName)
        .addClass(
          element.attr("id") === "btnViewAddFile" && i == 4 ?
          "inputRequired" :
          ""
        );
    } else {
      cloneRow.find("a").remove();
    }
  }

  cloneRow.find(".iCheck-helper").remove();
  cloneRow.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position", "").css("display", "inline-table");
  cloneRow.find(".form-control").val("").end();
  if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI")
    element.closest("div").find("table tbody").prepend(cloneRow);
  else element.closest("div").find("table tbody").append(cloneRow);
  addDateMask();
  initValidation();
  autosize($("textarea.auto-growth"));
}


function initValidation() {
  $("input:not(.inputRequired)").each(function() {
    $(this).rules("remove", "required");
  });

  $("textarea:not(.inputRequired)").each(function() {
    $(this).rules("remove", "required");
  });

  $("input.inputRequired, textarea.inputRequired").each(function() {
    $(this).rules("add", {
      required: function(element) {
        return $(element).val().trim() == "";
      },
      normalizer: function(value) {
        return $.trim(value);
      },
    });
  });

  $("input.inputifyes").each(function() {
    $(this).rules("add", {
      required: function(element) {
        var num = parseInt($(element).attr("name").slice(-2));
        if (num != 0)
          return (
            $(
              "input[name='radio_input_" +
              (num < 10 ? "0" + num : num) +
              "']:checked"
            ).val() == "Yes" && $(element).val().trim() == ""
          );
      },
    });
  });

  $(".inputFile").each(function() {
    $(this).rules("add", {
      required: function(element) {
        var tdelem = $(element)
          .closest("tr")
          .find("td:first-child")
          .find(".form-control:eq(2)");
        return tdelem.val() == "" && $(element).val().trim() == "";
      },
    });
  });

  $(".date_picker").daterangepicker({
    timePicker: false,
    drops: "up",
    locale: { format: "YYYY-MM-DD" },
    autoUpdateInput: false, // Disable auto-updating the input with default values
    // minDate: moment().startOf("day"),
    maxDate: moment().add(6, "months"),
  });
  
  // Handle the apply event
  $(".date_picker").on("apply.daterangepicker", function(ev, picker) {
    // Retrieve the selected start and end dates
    var startDate = picker.startDate.format("YYYY-MM-DD");
    var endDate = picker.endDate.format("YYYY-MM-DD");

    // Set the value of the input field manually
    $(this).val(startDate + " - " + endDate);

    // Trigger the change event to ensure other scripts detect the updated value
    $(this).trigger("change");
  });
  
  $("input.is_first_col_required, textarea.is_first_col_required").each(
    function() {
      $(this).rules("add", {
        required: function(element) {
          var tdelem = $(element)
            .closest("tr")
            .find("td:first-child")
            .find(".form-control:last");
          if ($(element).attr("type") == "file") {
            var tdsecelem = $(element)
              .closest("tr")
              .find("td:first-child")
              .find(".form-control:first");
            if (tdsecelem.val() == "") {
              return (
                tdelem.val() != "N/A" &&
                tdelem.val() != "n/a" &&
                $(element).val().trim() == ""
              );
            } else {
              return (
                tdelem.val() != "N/A" &&
                tdelem.val() != "n/a" &&
                tdsecelem.val() == ""
              );
            }
          } else {
            return (
              tdelem.val() != "N/A" &&
              tdelem.val() != "n/a" &&
              $(element).val().trim() == ""
            );
          }
        },
      });
    }
  );

  $("input.is_sec_col_required, textarea.is_sec_col_required").each(
    function() {
      $(this).rules("add", {
        required: function(element) {
          var tdelem = $(element)
            .closest("tr")
            .find("td:eq(1)")
            .find(".form-control");
          return (
            tdelem.val() != "N/A" &&
            tdelem.val() != "n/a" &&
            $(element).val().trim() == ""
          );
        },
      });
    }
  );

  $("input.is_third_col_required, textarea.is_third_col_required").each(
    function() {
      $(this).rules("add", {
        required: function(element) {
          var tdelem = $(element)
            .closest("tr")
            .find("td:eq(2)")
            .find(".form-control");
          return (
            tdelem.val() != "N/A" &&
            tdelem.val() != "n/a" &&
            $(element).val().trim() == ""
          );
        },
      });
    }
  );
}

function setButtonWavesEffect(event) {
  $(event.currentTarget).find('[role="menu"] li a').removeClass("waves-effect");
  $(event.currentTarget)
    .find('[role="menu"] li:not(.disabled) a')
    .addClass("waves-effect");
}

function updateEmployeesCallback(result) {
  window.location.reload();
}

function submitRequest(url, data, callback, ask) {
  $.confirm({
    title: '<label class="text-warning">Confirm!</label>',
    content: "Are you sure you want to proceed?",
    type: "orange",
    buttons: {
      confirm: {
        btnClass: "btn-blue",
        action: function() {
          var jsonObj = [];
          $.confirm({
            content: function() {
              var self = this;
              return $.ajax({
                url: url,
                type: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                  if (result) {
                    if (result.Code == "0") {
                      self.setTitle(
                        '<label class="text-success">Success</label>'
                      );
                      self.setContent(result.Message);
                      jsonObj = result;
                    } else {
                      self.setTitle(
                        '<label class="text-danger">Failed</label>'
                      );
                      self.setContent(result.Message);
                    }
                  } else {
                    self.setTitle('<label class="text-danger">Failed</label>');
                    self.setContent("Internal Error.");
                  }
                },
                error: function(result) {
                  self.setTitle('<label class="text-danger">Failed</label>');
                  self.setContent(
                    "There was an error in the connection. Please contact the administrator for updates. "
                  );
                },
              });
            },
            buttons: {
              ok: {
                action: function() {
                  if (jsonObj.Code == "0") {
                    callback(jsonObj);
                  }
                },
              },
            },
          });
        },
      },
      cancel: function() {},
    },
  });
}

function init_form_wizard() {
  var pdsform = $("form").show();
  pdsform.steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onInit: function(event, currentIndex) {
      $.AdminBSB.input.activate();

      //Set tab width
      var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
      var tabCount = $tab.length;
      $tab.css("width", 100 / tabCount + "%");

      $("a[href='#finish']").closest("li").css("display", "");
      $("a[href='#finish']").html("Save");

      //set button waves effect
      setButtonWavesEffect(event);
      // if($("#acc").val() == 0) $("a[href='#finish']").hide();
    },
    onStepChanging: function(event, currentIndex, newIndex) {

      if (currentIndex == 0) {


        if ($('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() != "") {

          var date = new Date('' + $('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() + '');
          var datenow = new Date();
          if (datenow < date) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future birth date of children.",
            });
            return false;
          } else {

          }
        } else {

        }
      }

      if (currentIndex == 1) {
        // $('.chk').iCheck('uncheck');
        $(".chk").iCheck({
          checkboxClass: "icheckbox_square-grey"
        });
        if ($('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() != "") {
          var date = new Date('' + $('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() + '');
          var datenow = new Date();
          if (datenow < date) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future date of examination.",
            });
            return false;
          } else {
            if ($('.is_work_present').closest("td").find("input:first").val() === "PRESENT") {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {

                var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                var datenow = new Date();
                if (datenow < date || datenow < date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "No future date of work.",
                  });
                  return false;
                } else if (date > date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Date to must be greater than date from.",
                  });
                  return false;
                } else {

                  if (currentIndex > newIndex) {
                    return true;
                  }
                  if (currentIndex < newIndex) {
                    pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                    pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                  }
                  pdsform.validate().settings.ignore = ":disabled,:hidden";
                  return pdsform.valid();
                }
              } else {
                if (currentIndex > newIndex) {
                  return true;
                }
                if (currentIndex < newIndex) {
                  pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                  pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {
                if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() != "") {
                  var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                  var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                  var datenow = new Date();
                  if (datenow < date || datenow < date2) {
                    $.alert({
                      title: '<label class="text-danger">Failed</label>',
                      content: "No future date of work.",
                    });
                    return false;
                  } else if (date > date2) {
                    $.alert({
                      title: '<label class="text-danger">Failed</label>',
                      content: "Date to must be greater than date from.",
                    });
                    return false;
                  } else {

                    if (currentIndex > newIndex) {
                      return true;
                    }
                    if (currentIndex < newIndex) {
                      pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                      pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    pdsform.validate().settings.ignore = ":disabled,:hidden";
                    return pdsform.valid();
                  }
                } else {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Inclusive date is required.",
                  });
                  return false;

                }

              } else {

                if (currentIndex > newIndex) {
                  return true;
                }
                if (currentIndex < newIndex) {
                  pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                  pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();

              }
            }
          }
        } else {
          if ($('.is_work_present').closest("td").find("input:first").val() === "PRESENT") {
            if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {

              var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
              var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
              var datenow = new Date();
              if (datenow < date || datenow < date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "No future date of work.",
                });
                return false;
              } else if (date > date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Date to must be greater than date from.",
                });
                return false;
              } else {

                if (currentIndex > newIndex) {
                  return true;
                }
                if (currentIndex < newIndex) {
                  pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                  pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
              if (currentIndex > newIndex) {
                return true;
              }
              if (currentIndex < newIndex) {
                pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
              }
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }
          } else {
            if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() != "") {
                var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                var datenow = new Date();
                if (datenow < date || datenow < date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "No future date of work.",
                  });
                  return false;
                } else if (date > date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Date to must be greater than date from.",
                  });
                  return false;
                } else {

                  if (currentIndex > newIndex) {
                    return true;
                  }
                  if (currentIndex < newIndex) {
                    pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                    pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                  }
                  pdsform.validate().settings.ignore = ":disabled,:hidden";
                  return pdsform.valid();
                }
              } else {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Inclusive date is required.",
                });
                return false;

              }

            } else {

              if (currentIndex > newIndex) {
                return true;
              }
              if (currentIndex < newIndex) {
                pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
              }
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();

            }
          }

        }

      }

      if (currentIndex == 2) {
        if ($('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() != "") {

          var date = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() + '');
          var date2 = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_to').val() + '');
          var datenow = new Date();
          // console.log(date);
          // console.log(date2);
          if (datenow < date || datenow < date2) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future date of attendance.",
            });
            return false;
          } else if (date > date2) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "Date to must be greater than date from.",
            });
            return false;
          } else {

            if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "") {

              var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
              var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
              var datenow = new Date();
              if (datenow < date || datenow < date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "No future date of attendance.",
                });
                return false;
              } else if (date > date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Date to must be greater than date from.",
                });
                return false;
              } else {

                if (currentIndex > newIndex) {
                  return true;
                }
                if (currentIndex < newIndex) {
                  pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                  pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
              if (currentIndex > newIndex) {
                return true;
              }
              if (currentIndex < newIndex) {
                pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
              }
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }

          }
        } else {
          if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "") {

            var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
            var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
            var datenow = new Date();
            if (datenow < date || datenow < date2) {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "No future date of attendance.",
              });
              return false;
            } else if (date > date2) {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Date to must be greater than date from.",
              });
              return false;
            } else {

              if (currentIndex > newIndex) {
                return true;
              }
              if (currentIndex < newIndex) {
                pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
                pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
              }
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }
          } else {
            if (currentIndex > newIndex) {
              return true;
            }
            if (currentIndex < newIndex) {
              pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
              pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            pdsform.validate().settings.ignore = ":disabled,:hidden";
            return pdsform.valid();
          }
        }
      }

      if (currentIndex > newIndex) {
        return true;
      }
      if (currentIndex < newIndex) {
        pdsform.find(".body:eq(" + newIndex + ") label.error").remove();
        pdsform.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }
      pdsform.validate().settings.ignore = ":disabled,:hidden";
      return pdsform.valid();

    },
    onStepChanged: function(event, currentIndex, priorIndex) {
      tmpNextBtn();
      setButtonWavesEffect(event);
      $("a[href='#finish']").closest("li").css("display", "");
      if (currentIndex == 4) {
        $("a[href='#finish']").html("Finish");
      } else {
        $("a[href='#finish']").closest("li").css("display", "");
        $("a[href='#finish']").html("Save");
      }
      $(".govtservice").trigger("change");
    },
    onFinishing: function(event, currentIndex) {

      if (currentIndex == 0) {


        if ($('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() != "") {

          var date = new Date('' + $('#btnAddCH').closest("div").find("table tbody tr:last").find(".is_first_col_required_birthday").val() + '');
          var datenow = new Date();
          if (datenow < date) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future birth date of children.",
            });
            return false;
          } else {

          }
        } else {

        }
      }

      if (currentIndex == 1) {
        // $('.chk').iCheck('uncheck');
        $(".chk").iCheck({
          checkboxClass: "icheckbox_square-grey"
        });
        if ($('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() != "") {
          var date = new Date('' + $('#btnAddCSE').closest("div").find("table tbody tr:last").find(".is_first_col_required_examination").val() + '');
          var datenow = new Date();
          if (datenow < date) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future date of examination.",
            });
            return false;
          } else {
            if ($('.is_work_present').closest("td").find("input:first").val() === "PRESENT") {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {

                var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                var datenow = new Date();
                if (datenow < date || datenow < date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "No future date of work.",
                  });
                  return false;
                } else if (date > date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Date to must be greater than date from.",
                  });
                  return false;
                } else {

                  pdsform.validate().settings.ignore = ":disabled,:hidden";
                  return pdsform.valid();
                }
              } else {
               
                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {
                if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() != "") {
                  var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                  var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                  var datenow = new Date();
                  if (datenow < date || datenow < date2) {
                    $.alert({
                      title: '<label class="text-danger">Failed</label>',
                      content: "No future date of work.",
                    });
                    return false;
                  } else if (date > date2) {
                    $.alert({
                      title: '<label class="text-danger">Failed</label>',
                      content: "Date to must be greater than date from.",
                    });
                    return false;
                  } else {

                    pdsform.validate().settings.ignore = ":disabled,:hidden";
                    return pdsform.valid();
                  }
                } else {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Inclusive date is required.",
                  });
                  return false;

                }

              } else {

                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();

              }
            }
          }
        } else {
          if ($('.is_work_present').closest("td").find("input:first").val() === "PRESENT") {
            if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {

              var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
              var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
              var datenow = new Date();
              if (datenow < date || datenow < date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "No future date of work.",
                });
                return false;
              } else if (date > date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Date to must be greater than date from.",
                });
                return false;
              } else {

                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
             
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }
          } else {
            if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() != "") {
              if ($('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() != "") {
                var date = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_from').val() + '');
                var date2 = new Date('' + $('#tbworkexp').closest("div").find("table tbody tr:last").find('.is_third_col_required_to').val() + '');
                var datenow = new Date();
                if (datenow < date || datenow < date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "No future date of work.",
                  });
                  return false;
                } else if (date > date2) {
                  $.alert({
                    title: '<label class="text-danger">Failed</label>',
                    content: "Date to must be greater than date from.",
                  });
                  return false;
                } else {

                  pdsform.validate().settings.ignore = ":disabled,:hidden";
                  return pdsform.valid();
                }
              } else {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Inclusive date is required.",
                });
                return false;

              }

            } else {

              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();

            }
          }

        }

      }

      if (currentIndex == 2) {
        if ($('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() != "") {

          var date = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_from').val() + '');
          var date2 = new Date('' + $('#tbvoluntarywork').closest("div").find("table tbody tr:last").find('.attendance_to').val() + '');
          var datenow = new Date();
          
          if (datenow < date || datenow < date2) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "No future date of attendance.",
            });
            return false;
          } else if (date > date2) {
            $.alert({
              title: '<label class="text-danger">Failed</label>',
              content: "Date to must be greater than date from.",
            });
            return false;
          } else {

            if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "") {

              var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
              var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
              var datenow = new Date();
              if (datenow < date || datenow < date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "No future date of attendance.",
                });
                return false;
              } else if (date > date2) {
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Date to must be greater than date from.",
                });
                return false;
              } else {

                pdsform.validate().settings.ignore = ":disabled,:hidden";
                return pdsform.valid();
              }
            } else {
              
              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }

          }
        } else {
          if ($('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() != "") {

            var date = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningfrom').val() + '');
            var date2 = new Date('' + $('#tblearnings').closest("div").find("table tbody tr:last").find('.traningto').val() + '');
            var datenow = new Date();
            if (datenow < date || datenow < date2) {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "No future date of attendance.",
              });
              return false;
            } else if (date > date2) {
              $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Date to must be greater than date from.",
              });
              return false;
            } else {

              pdsform.validate().settings.ignore = ":disabled,:hidden";
              return pdsform.valid();
            }
          } else {
            pdsform.validate().settings.ignore = ":disabled,:hidden";
            return pdsform.valid();
          }
        }
      }

      pdsform.validate().settings.ignore = ":disabled,:hidden";
      return pdsform.valid();
    },
    onFinished: function(event, currentIndex) {
      var formData = new FormData($("form")[0]);
      image = $("#employeeImage").attr("src");
      formData.append("employee_id_photo", image);
      submitRequest(
        commons.baseurl + "employees/PDS/updatePDS",
        formData,
        updateEmployeesCallback,
        "You want to proceed?"
      );
    },
  });

  pdsform.validate({
    highlight: function(input) {
      $(input).parents(".form-line").addClass("error");
    },
    unhighlight: function(input) {
      $(input).parents(".form-line").removeClass("error");
    },
    errorPlacement: function(error, element) {
      $(element).parents(".form-group").append(error);
    },
    rules: {
      // ".inputFile": { extension: "jpg,jpeg,pdf,png" },
      employee_number: {
        required: true
      },
      last_name: {
        required: true,
        lastnameRegex: true
      },
      first_name: {
        required: true,
        firstnameRegex: true
      },
      middle_name: {
        middlenameRegex: true
      },
      birthday: {
        required: true,
        minDate: true
      },
      email: {
        email: function(data) {
          return $("#email").val() == "N/A" ? false : true;
        }
      },
      radio_input_01: {
        required: true
      },
      radio_input_02: {
        required: true
      },
      radio_input_03: {
        required: true
      },
      radio_input_04: {
        required: true
      },
      radio_input_05: {
        required: true
      },
      radio_input_06: {
        required: true
      },
      radio_input_07: {
        required: true
      },
      radio_input_08: {
        required: true
      },
      radio_input_09: {
        required: true
      },
      radio_input_10: {
        required: true
      },
      radio_input_11: {
        required: true
      },
      radio_input_12: {
        required: true
      },
      shift_id: {
        required: function(data) {
          return $("#md_checkbox_28").iCheck("update")[0].checked === true;
        },
      },
      if_yes_case_status_03: {
        required: function(data) {
          return $("input[name='radio_input_03']:checked").val() === "Yes";
        },
      },
      civil_status_others: {
        required: function(data) {
          return $("#civil_status").val() === "Others";
        },
      },
      nationality_country: {
        required: function(data) {
          return $("#nationality").val() !== "Filipino";
        },
      },
      cut_off_1: {
        required: function(data) {
          return parseFloat($("#cut_off_1").val()) === 0;
        },
      },
      cut_off_2: {
        required: function(data) {
          return parseFloat($("#cut_off_2").val()) === 0;
        },
      },
      cut_off_3: {
        required: function(data) {
          return (
            parseFloat($("#cut_off_3").val()) === 0 && pay_basis === "Permanent"
          );
        },
      },
      cut_off_4: {
        required: function(data) {
          return (
            parseFloat($("#cut_off_4").val()) === 0 && pay_basis === "Permanent"
          );
        },
      },
    },
  });

  $(".chk").iCheck({
    checkboxClass: "icheckbox_square-grey"
  });
  $(".chkradio").iCheck({
    radioClass: "iradio_square-grey"
  });
  $(".chkradio").iCheck({
    radioClass: "iradio_square-grey",
  });

  // $(document).on("change", ".salaryCurrency", function () {
  // var tmp = parseFloat($(this).val().replace(/,/g, ""));
  // $(this).val(addCommas2(tmp)
  // tmp.toLocaleString(undefined, {
  //   minimumFractionDigits: 2,
  //   maximumFractionDigits: 2,
  // })
  // );
  // });

  initValidation();

  $.validator.addMethod(
    "lettersonly",
    function(value, element) {
      return this.optional(element) || /^[a-z Ññ]+$/i.test(value);
    },
    "Letters only please"
  );

  $.validator.addMethod(
    "lastnameRegex",
    function(value, element) {
      return this.optional(element) || /^[a-z0-9 Ññ\-\s\,]+$/i.test(value);
    },
    "Letters, numbers, dashes and comma only"
  );

  $.validator.addMethod(
    "firstnameRegex",
    function(value, element) {
      return this.optional(element) || /^[a-z0-9 Ññ\-\s\.]+$/i.test(value);
    },
    "Letters, numbers, dashes and period only"
  );

  $.validator.addMethod(
    "middlenameRegex",
    function(value, element) {
      return this.optional(element) || /^[a-z Ññ\-\s]+$/i.test(value);
    },
    "Letters and dashes only"
  );

  $.validator.addMethod(
    "minDate",
    function(value, element) {
      var now = new Date();
      var myDate = new Date(value);
      return this.optional(element) || myDate < now;

    },
    "No future dates."
  );

  $(document).on("click", "#btntempnext", function() {
    var actions = $("#addEmployees .actions").find("ul").find("li:eq(1)");
    $.post(
      commons.baseurl + "employees/Employees/isNameExist", {
        fname: $("#first_name").val(),
        mname: $("#middle_name").val(),
        lname: $("#last_name").val(),
        no: $("#employee_number").val(),
      },
      function(result) {
        result = JSON.parse(result);
        if (result.Code == "1") {
          dialogErrorV2(result.Message);
        } else {
          actions.find("a").trigger("click");
        }
      }
    );
  });
}

function tmpNextBtn() {
  var steps = $("#addEmployees .steps").find("ul");
  var actions = $("#addEmployees .actions").find("ul").find("li:eq(1)");
  if (steps.find("li:eq(0)").hasClass("current")) {
    if (actions.find("button").length == 0) {
      actions.append(
        "<button type='button' id='btntempnext' class='btn btn-lg waves-effect' style='background-color: #009688; color: #fff;padding: 0.5em 1em;font-size: 14px;'>Next</button>"
      );
    } else {
      actions.find("button").css("display", "");
    }
    actions.find("a").css("display", "none");
  } else {
    actions.find("a").css("display", "");
    if (actions.find("button").length > 0) {
      actions.find("button").css("display", "none");
    }
  }
}

function addDateMask() {
  $(".date_mask, .datepicker").each(function() {
    if ($(this).hasClass("date_mask") && $(this).closest("td").find("input:last").hasClass("chk")) {
      if ($(this).closest("td").find("input:last").iCheck("update")[0].checked) {

        // do nothing
      } else {
        $(this).inputmask("mm/dd/yyyy", {
          placeholder: "mm/dd/yyyy",
        });
      }
    } else {
      $(this).inputmask("mm/dd/yyyy", {
        placeholder: "mm/dd/yyyy",
      });
    }
  });
  // $(".attendancePeriod").inputmask("9999", { placeholder: "____" });
  // $(".salaryGrade").inputmask("99-9", { placeholder: "__-_" });
}


function insertEducRows(arrData, educType, buttonName) {
  $.each(arrData, function(k, v) {
    if (k === 0) {
      $("textarea[name='" + educType + "_school[0]']").val(v.school);
      $("textarea[name='" + educType + "_degree[0]']").val(v.degree);
      $("input[name='" + educType + "_period_from[0]']").val(v.period_from);
      $("input[name='" + educType + "_period_to[0]']").val(v.period_to);
      $("textarea[name='" + educType + "_highest_level[0]']").val(
        v.highest_level
      );
      $("input[name='" + educType + "_year_graduated[0]']").val(
        v.year_graduated
      );
      $("textarea[name='" + educType + "_received[0]']").val(v.received);
    } else {
      var vtc = $("." + buttonName + ":last")
        .closest("tr")
        .clone();
      var intp = $("." + buttonName + ":last")
        .closest("tr")
        .find("td:eq(1) .form-control")
        .attr("name");
      var inc = $("textarea[name^='" + intp.slice(0, -3) + "']:last").attr(
        "name"
      );

      $is = false;
      if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
        inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
        $is = true;
      } else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;

      vtc.closest("tr").find("td:eq(0)").html("");
      elementName1 =
        vtc
        .closest("tr")
        .find("td:eq(1)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName2 =
        vtc
        .closest("tr")
        .find("td:eq(2)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName3 =
        vtc
        .closest("tr")
        .find("td:eq(3)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName4 =
        vtc
        .closest("tr")
        .find("td:eq(4)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName5 =
        vtc
        .closest("tr")
        .find("td:eq(5)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName6 =
        vtc
        .closest("tr")
        .find("td:eq(6)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      elementName7 =
        vtc
        .closest("tr")
        .find("td:eq(7)")
        .find(".form-control")
        .attr("name")
        .slice(0, $is ? -3 : -4) +
        "[" +
        inc +
        "]";
      vtc
        .closest("tr")
        .find("td:eq(1)")
        .find(".form-control")
        .attr("name", elementName1)
        .val(v.school);
      vtc
        .closest("tr")
        .find("td:eq(2)")
        .find(".form-control")
        .attr("name", elementName2)
        .val(v.degree);
      vtc
        .closest("tr")
        .find("td:eq(3)")
        .find(".form-control")
        .attr("name", elementName3)
        .val(v.period_from);
      vtc
        .closest("tr")
        .find("td:eq(4)")
        .find(".form-control")
        .attr("name", elementName4)
        .val(v.period_to);
      vtc
        .closest("tr")
        .find("td:eq(5)")
        .find(".form-control")
        .attr("name", elementName5)
        .val(v.highest_level);
      vtc
        .closest("tr")
        .find("td:eq(6)")
        .find(".form-control")
        .attr("name", elementName6)
        .val(v.year_graduated);
      vtc
        .closest("tr")
        .find("td:eq(7)")
        .find(".form-control")
        .attr("name", elementName7)
        .val(v.received);
      vtc
        .closest("tr")
        .find("td:eq(8)")
        .html(
          '<button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button>'
        );
      // <button type="button" class="btn btn-primary btn-sm ' + buttonName + ' style="float: right;"><i class="material-icons">add</i></button>
      lst_elem = $("textarea[name^='" + intp.slice(0, -3) + "']:last");
      lst_elem.closest("tr").after(vtc);
      inc = lst_elem.attr("name");
    }
  });
}

function insertPrintEducRows(arrData, educType, educClass) {
  $.each(arrData, function(k, v) {
    if (k === 0) {
      $("." + educType + "_school").html(v.school);
      $("." + educType + "_degree").html(v.degree);
      $("." + educType + "_period_from").html(v.period_from);
      $("." + educType + "_period_to").html(v.period_to);
      $("." + educType + "_highest_level").html(v.highest_level);
      $("." + educType + "_year_graduated").html(v.year_graduated);
      $("." + educType + "_received").html(v.received);
    } else {
      var row =
        k === 1 ? $("." + educClass) : $("." + educClass + ":last").clone();
      row.closest("tr").find("td:eq(1)").html(v.school);
      row.closest("tr").find("td:eq(2)").html(v.degree);
      row.closest("tr").find("td:eq(3)").html(v.period_from);
      row.closest("tr").find("td:eq(4)").html(v.period_to);
      row.closest("tr").find("td:eq(5)").html(v.highest_level);
      row.closest("tr").find("td:eq(6)").html(v.year_graduated);
      row.closest("tr").find("td:eq(7)").html(v.received);
      $("." + educClass + ":last").after(row);
      // if (educType == "grad_stud")
      //  $("." + educClass + ":last")
      //    .closest("tr")
      //    .after(row);
    }
  });
}