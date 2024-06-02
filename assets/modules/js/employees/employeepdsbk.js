$(document).ready(function () {

  $(document).on("click", ".upload", function (e) {
    e.preventDefault();
    $(this)
      .parent()
      .find('input[type="file"]')
      .replaceWith($(this).parent().find('input[type="file"]').val(""));
    $(this).parent().find('input[type="file"]').trigger("click");
  });
  init_form_wizard();
  $.when(getFields.division()).done(function () {
    var employee_id = $("#id").val();
    if (employee_id !== "") {
      $.post(
        commons.baseurl + "employees/PDS/getEmpDetails",
        { id: employee_id },
        function (result) {
          $jsonData = JSON.parse(result);
          $.each($jsonData.data, function (i, v) {
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
            data: { employee_id: employee_id },
            type: "POST",
            dataType: "json",
            success: function (result) {
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
            commons.baseurl + "employees/Employees/getEmpTables",
            { id: employee_id },
            function (result) {
              result = JSON.parse(result);
              if (result.Code == "0") {
                if (result.Data.familybackgroundchildrens.length > 0) {
                  $("#tbchildres").empty();
                  $.each(
                    result.Data.familybackgroundchildrens,
                    function (k, v) {
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
                          '<input type="text" class="form-control is_first_col_required date_mask" value="' +
                          (v.children_birthday != null
                            ? v.children_birthday.replace(
                                " 00:00:00",
                                ""
                              )
                            : "") +
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
                  $.each(result.Data.civilserviceeligibility, function (
                    k,
                    v
                  ) {
                    // k =
                    //   result.Data.civilserviceeligibility.length -
                    //   1 -
                    //   k;
                    $("#tbcse").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;"" class="form-control no-resize auto-growth  inputRequired" name="civil_service_eligibility[' +
                        k +
                        ']" id="civil_service_eligibility[' +
                        k +
                        ']" >' +
                        v.civil_service_eligibility +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.rating +
                        '" class="form-control is_first_col_required" name="rating[' +
                        k +
                        ']" id="rating[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        (v.date_conferment != null
                          ? v.date_conferment.replace(" 00:00:00", "")
                          : "") +
                        '" class="form-control is_first_col_required date_mask" name="date_conferment[' +
                        k +
                        ']" id="date_conferment[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="place_examination[' +
                        k +
                        ']" id="place_examination[' +
                        k +
                        ']" >' +
                        v.place_examination +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth" name="license_number[' +
                        k +
                        ']" id="license_number[' +
                        k +
                        ']" >' +
                        v.license_number +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        (v.license_validity != null
                          ? v.license_validity.replace(" 00:00:00", "")
                          : "") +
                        '" class="form-control date_mask" name="license_validity[' +
                        k +
                        ']" id="license_validity[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.workexperience.length > 0) {
                  $("#tbworkexp").empty();
                  $.each(result.Data.workexperience, function (k, v) {
                    k = result.Data.workexperience.length - 1 - k;
                    $("#tbworkexp").append(
                      "<tr>" +
                                '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                                (v.work_from != null
                                  ? v.work_from.replace(" 00:00:00", "")
                                  : "") +
                                '" class="form-control is_third_col_required date_mask" name="work_from[' +
                                k +
                                ']" id="work_from[' +
                                k +
                                ']" ></div></div></td>' +
                                '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                                v.work_to +
                                '" class="form-control is_third_col_required date_mask '+(v.work_to == "PRESENT" ? 'remove_date_format':'')+'" name="work_to[' +
                        k +
                        ']" id="work_to[' +
                        k +
                        ']" ></div><div class="form-group"><input type="checkbox" class="chk is_work_present" '+(v.work_to == "PRESENT" ? 'checked':'')+'> PRESENT</div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="position[' +
                        k +
                        ']" id="position[' +
                        k +
                        ']" >' +
                        v.position +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" name="company[' +
                        k +
                        ']" id="company[' +
                        k +
                        ']" >' +
                        v.company +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        (v.monthly_salary != null
                          ? v.monthly_salary
                          : "0") +
                        '" class="form-control is_third_col_required currency2" name="monthly_salary[' +
                        k +
                        ']" id="monthly_salary[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" class="form-control ' +
                        (v.gov_service == "Y"
                          ? "is_third_col_required"
                          : "") +
                        ' salaryGrade" name="grade[' +
                        k +
                        ']" id="grade[' +
                        k +
                        ']" value="' +
                        v.grade +
                        '" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" class="form-control" name="status_appointment[' +
                        k +
                        ']" id="status_appointment[' +
                        k +
                        ']" >' +
                        v.status_appointment +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.gov_service +
                        '" class="form-control is_third_col_required govtservice" name="gov_service[' +
                        k +
                        ']" id="gov_service[' +
                        k +
                        ']" maxlength="1" ></div></div></td>' +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.voluntarywork.length > 0) {
                  $("#tbvoluntarywork").empty();
                  $.each(result.Data.voluntarywork, function (k, v) {
                    // k = result.Data.voluntarywork.length - 1 - k;
                    $("#tbvoluntarywork").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="organization[' +
                        k +
                        ']" id="organization[' +
                        k +
                        ']" >' +
                        v.organization +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.organization_work_from +
                        '" class="form-control is_first_col_required date_mask" name="organization_work_from[' +
                        k +
                        ']" id="organization_work_from[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.organization_work_to +
                        '" class="form-control is_first_col_required date_mask" name="organization_work_to[' +
                        k +
                        ']" id="organization_work_to[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="number" value="' +
                        (v.organization_number_hours != 0
                          ? v.organization_number_hours
                          : "") +
                        '" class="form-control  is_first_col_required" name="organization_number_hours[' +
                        k +
                        ']" id="organization_number_hours[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="organization_work_nature[' +
                        k +
                        ']" id="organization_work_nature[' +
                        k +
                        ']" >' +
                        v.organization_work_nature +
                        "</textarea></div></div></td>" +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.learningdevelopment.length > 0) {
                  $("#tblearnings").empty();
                  $.each(result.Data.learningdevelopment, function (
                    k,
                    v
                  ) {
                    k = result.Data.learningdevelopment.length - 1 - k;
                    $("#tblearnings").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="training[' +
                        k +
                        ']" id="training[' +
                        k +
                        ']" >' +
                        v.training +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        (v.traning_from != null
                          ? v.traning_from.replace(" 00:00:00", "")
                          : "") +
                        '" class="form-control is_first_col_required date_mask" name="traning_from[' +
                        k +
                        ']" id="traning_from[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.training_to +
                        '" class="form-control is_first_col_required date_mask" name="training_to[' +
                        k +
                        ']" id="training_to[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="number" value="' +
                        (v.training_number_hours != 0
                          ? v.training_number_hours
                          : "") +
                        '" class="form-control is_first_col_required" name="training_number_hours[' +
                        k +
                        ']" id="training_number_hours[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_type[' +
                        k +
                        ']" id="training_type[' +
                        k +
                        ']" >' +
                        v.training_type +
                        "</textarea></div></div></td>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_sponsored_by[' +
                        k +
                        ']" id="training_sponsored_by[' +
                        k +
                        ']" >' +
                        v.training_sponsored_by +
                        "</textarea></div></div></td>" +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.specialskills.length > 0) {
                  $("#tbspecialskils").empty();
                  $.each(result.Data.specialskills, function (k, v) {
                    $("#tbspecialskils").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="special_skills[' +
                        k +
                        ']" id="special_skills[' +
                        k +
                        ']" >' +
                        v.special_skills +
                        "</textarea></div></div></td>" +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.recognitions.length > 0) {
                  $("#tbrecognitions").empty();
                  $.each(result.Data.recognitions, function (k, v) {
                    $("#tbrecognitions").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="recognitions[' +
                        k +
                        ']" id="recognitions[' +
                        k +
                        ']" >' +
                        v.recognitions +
                        "</textarea></div></div></td>" +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.organizations.length > 0) {
                  $("#tborganizations").empty();
                  $.each(result.Data.organizations, function (k, v) {
                    $("#tborganizations").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="membership[' +
                        k +
                        ']" id="membership[' +
                        k +
                        ']" >' +
                        v.organization +
                        "</textarea></div></div></td>" +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
                if (result.Data.references.length > 0) {
                  $("#tbreferences").empty();
                  $.each(result.Data.references, function (k, v) {
                    $("#tbreferences").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.reference_name +
                        '" class="form-control  inputRequired" name="reference_name[' +
                        k +
                        ']" id="reference_name[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.reference_address +
                        '" class="form-control is_first_col_required" name="reference_address[' +
                        k +
                        ']" id="reference_address[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><div class="form-group"><div class="form-line"><input type="text" value="' +
                        v.reference_tel_no +
                        '" class="form-control is_first_col_required" name="reference_tel_no[' +
                        k +
                        ']" id="reference_tel_no[' +
                        k +
                        ']" ></div></div></td>' +
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
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
            commons.baseurl + "employees/Employees/getEmpAttachments",
            { id: employee_id },
            function (result) {
              result = JSON.parse(result);
              if (result.Code == "0") {
                if (result.Data.length > 0) {
                  $("#tbFiles").empty();
                  $.each(result.Data, function (k, v) {
                    $("#tbFiles").append(
                      "<tr>" +
                        '	<td><div class="form-group"><div class="form-line">' +
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
                        v.file_name +
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
                        '	<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>' +
                        "</tr>"
                    );
                  });
                }
              }
            }
          );
          setTimeout(() => {
            $(".chk").iCheck("destroy");
            $(".chk").iCheck({ checkboxClass: "icheckbox_square-grey" });
            $(".chkradio").iCheck({ radioClass: "iradio_square-grey" });
          }, 1000);
          $.AdminBSB.select.activate();
          $("#aniimated-thumbnials").lightGallery({
            thumbnail: true,
            selector: "a",
          });
          initValidation();
          $(".form-control").css("z-index", 1);
          $(".headcol").css("z-index", 2);
          addDateMask();
          $(".remove_date_format").inputmask("remove").val("PRESENT").css("pointer-events", "none");
          tmpNextBtn();
          autosize($("textarea.auto-growth"));
        }
      );
    }
  });

  $(document).on("click", ".deleteRow", function () {
    var totTableRows = $(this).closest("tbody").find("tr").length;
    if (totTableRows > 1) $(this).closest("tr").remove();
  });

  $(document).on("click", "#btnViewAddFile", function () {
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

  $(document).on("click", "#btnAddCH, #btnAddFile", function () {
    addrow($(this), 2);
  });

  $(document).on("click", "#btnAddCSE, #btnAddLDI", function () {
    addrow($(this), 6);
  });

  $(document).on("click", "#btnAddWE", function () {
    addrow($(this), 8);
  });

  $(document).on("click", "#btnAddVW", function () {
    addrow($(this), 5);
  });

  $(document).on("click", "#btnAddSSH, #btnAddNAD, #btnAddMA", function () {
    addrow($(this), 1);
  });

  $(document).on("click", "#btnAddRef", function () {
    addrow($(this), 3);
  });

  $(document).on("change", "#civil_status", function () {
    if ($(this).val() === "Others")
      $(".civil_status_others").closest(".row").show();
    else $(".civil_status_others").closest(".row").hide();
  });

  $(document).on("change", "#nationality", function () {
    if ($(this).val() !== "Filipino")
      $(".nationality_country").closest(".row").show();
    else $(".nationality_country").closest(".row").hide();
  });

  $(document).on(
    "click",
    ".btnAddELEM, .btnAddSEC, .btnAddVTC, .btnAddC, .btnAddGS",
    function () {
      addRowAfter($(this));
    }
  );

  $(document).on("click", ".update_file_row", function () {
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
  $(document).on("click", "#reset_snapshot", function (e) {
    e.preventDefault();
    $("#my_camera").show();
    $("#take_snapshot").show();
    $("#reset_snapshot").hide();
    $("#employeeImage").hide();
  });

  $(document).on("click", "#take_snapshot", function (e) {
    e.preventDefault();
    take_snapshot();
  });

  $(document).on("change", "#fileupload", function () {
    readURL(this);
    $("#employeeImage").show();
    $("#reset_snapshot").show();
    $("#take_snapshot").hide();
    $("#my_camera").hide();
  });

  $(document).on("keydown", ".govtservice", function (e) {
    var key = e.keyCode | e.which;
    if (key == 78 || key == 89 || (key >= 8 && key <= 46)) return true;
    else return false;
  });

  $(document).on("change", ".govtservice", function () {
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
    function (value, element) {
      return this.optional(element) || /^[a-z Ññ]+$/i.test(value);
    },
    "Letters only please"
  );

  jQuery.validator.addMethod(
    "noSpace",
    function (value, element) {
      return value.trim().length == 0 && value.trim() == "";
    },
    "White space only not valid"
  );

  
  $(document).on('ifUnchecked',".is_work_present", function(event){
    var first_input = $(this).closest("td").find("input:first");
    first_input.inputmask("mm/dd/yyyy", {placeholder: "mm/dd/yyyy",});
    first_input.css("pointer-events", "all");
  });
  $(document).on('ifChecked',".is_work_present", function(event){
    var first_input = $(this).closest("td").find("input:first");
    first_input.inputmask("remove");//.rules("remove", "required");
    first_input.val("PRESENT").css("pointer-events", "none");
    // iCheck-helper
  });
  
});

function readURL(input) {
  //data_uri = window.URL.createObjectURL(input.prop('files')[0])
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#employeeImage").attr("src", e.target.result);
      $(".image-tag").attr("href", e.target.result);
    };

    reader.onload = function (e) {
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

  $is = false;
  if (inc.substr(inc.length - 3).slice(0, -2) == "[") {
    inc = parseInt(inc.substr(inc.length - 2).slice(0, -1)) + 1;
    $is = true;
  } else inc = parseInt(inc.substr(inc.length - 3).slice(0, -1)) + 1;
  cloneRow.find(".form-group").find("label.error").remove();
  for (var i = 0; i < count; i++) {
    if (cloneRow.closest("td").find("a").length == 0) {
      var elementName = "";

      if ($is)
        elementName =
          cloneRow.find(".form-control").eq(i).attr("name").slice(0, -3) +
          "[" +
          inc +
          "]";
      else {
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
          element.attr("id") === "btnViewAddFile" && i == 4
            ? "inputRequired"
            : ""
        );
    } else {
      cloneRow.find("a").remove();
    }
  }
  cloneRow.find(".iCheck-helper").remove();
  cloneRow.find(".icheckbox_square-grey").removeClass("icheckbox_square-grey").removeClass("checked").removeClass("hover").css("position","").css("display","inline-table");
  cloneRow.find(".form-control").val("").end();
  if (element.attr("id") === "btnAddWE" || element.attr("id") === "btnAddLDI")
    element.closest("div").find("table tbody").prepend(cloneRow);
  else element.closest("div").find("table tbody").append(cloneRow);
  addDateMask();
  initValidation();
  autosize($("textarea.auto-growth"));
  setTimeout(() => {
    $(".chk").iCheck("destroy");
    $(".chk").iCheck({ checkboxClass: "icheckbox_square-grey" });
    $(".chkradio").iCheck({ radioClass: "iradio_square-grey" });
  }, 500);
}

function initValidation() {
  $("input:not(.inputRequired)").each(function () {
    $(this).rules("remove", "required");
  });

  $("textarea:not(.inputRequired)").each(function () {
    $(this).rules("remove", "required");
  });

  $("input.inputRequired, textarea.inputRequired").each(function () {
    $(this).rules("add", {
      required: function (element) {
        return $(element).val().trim() == "";
      },
      normalizer: function (value) {
        return $.trim(value);
      },
    });
  });

  $("input.inputifyes").each(function () {
    $(this).rules("add", {
      required: function (element) {
        var num = parseInt($(element).attr("name").slice(-2));
        if (num != 1)
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

  $(".inputFile").each(function () {
    $(this).rules("add", {
      required: function (element) {
        var tdelem = $(element)
          .closest("tr")
          .find("td:first-child")
          .find(".form-control:eq(2)");
        return tdelem.val() == "" && $(element).val().trim() == "";
      },
    });
  });

  $("input.is_first_col_required, textarea.is_first_col_required").each(
    function () {
      $(this).rules("add", {
        required: function (element) {
          var tdelem = $(element)
            .closest("tr")
            .find("td:first-child")
            .find(".form-control:last");
          if($(element).attr("type") == "file"){
            var tdsecelem = $(element)
            .closest("tr")
            .find("td:first-child")
            .find(".form-control:first");
            if(tdsecelem.val() == ""){
              return (
                tdelem.val() != "N/A" &&
                tdelem.val() != "n/a" &&
                $(element).val().trim() == ""
              );
            }else{
              return (
                tdelem.val() != "N/A" &&
                tdelem.val() != "n/a" &&
                tdsecelem.val() == ""
              );
            }
          }else{
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
    function () {
      $(this).rules("add", {
        required: function (element) {
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
    function () {
      $(this).rules("add", {
        required: function (element) {
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
    content:
      "<center><i class='material-icons' style='font-size:70pt' align='center'>warning</i><br>Are you sure?<br>" +
      ask +
      "</center>",
    type: "orange",
    buttons: {
      confirm: {
        btnClass: "btn-blue",
        action: function () {
          var jsonObj = [];
          $.confirm({
            content: function () {
              var self = this;
              return $.ajax({
                url: url,
                type: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (result) {
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
                error: function (result) {
                  self.setTitle('<label class="text-danger">Failed</label>');
                  self.setContent(
                    "There was an error in the connection. Please contact the administrator for updates. "
                  );
                },
              });
            },
            buttons: {
              ok: {
                action: function () {
                  if (jsonObj.Code == "0") {
                    callback(jsonObj);
                  }
                },
              },
            },
          });
        },
      },
      cancel: function () {},
    },
  });
}

function init_form_wizard() {
  var pdsform = $("form").show();
  pdsform.steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onInit: function (event, currentIndex) {
      $.AdminBSB.input.activate();

      //Set tab width
      var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
      var tabCount = $tab.length;
      $tab.css("width", 100 / tabCount + "%");
      
      $("a[href='#finish']").closest("li").css("display","");
      $("a[href='#finish']").html("Save");

      //set button waves effect
      setButtonWavesEffect(event);
      // if($("#acc").val() == 0) $("a[href='#finish']").hide();
    },
    onStepChanging: function (event, currentIndex, newIndex) {
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
    onStepChanged: function (event, currentIndex, priorIndex) {
      tmpNextBtn();
      setButtonWavesEffect(event);
      $("a[href='#finish']").closest("li").css("display","");
      if(currentIndex == 4){
        $("a[href='#finish']").html("Finish");
      }else{
        $("a[href='#finish']").closest("li").css("display","");
        $("a[href='#finish']").html("Save");
      }
    },
    onFinishing: function (event, currentIndex) {
      pdsform.validate().settings.ignore = ":disabled";
      return pdsform.valid();
    },
    onFinished: function (event, currentIndex) {
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
    highlight: function (input) {
      $(input).parents(".form-line").addClass("error");
    },
    unhighlight: function (input) {
      $(input).parents(".form-line").removeClass("error");
    },
    errorPlacement: function (error, element) {
      $(element).parents(".form-group").append(error);
    },
    rules: {
      // ".inputFile": { extension: "jpg,jpeg,pdf,png" },
      employee_number: { required: true },
      last_name: { required: true, lettersonly: true },
      first_name: { required: true, lettersonly: true },
      middle_name: { lettersonly: true },
      birthday: { required: true },
      email: {
        email : function(data){
          return $("#email").val() == "N/A" ? false : true;
        }
      },
      radio_input_01: { required: true },
      radio_input_02: { required: true },
      radio_input_03: { required: true },
      radio_input_04: { required: true },
      radio_input_05: { required: true },
      radio_input_06: { required: true },
      radio_input_07: { required: true },
      radio_input_08: { required: true },
      radio_input_09: { required: true },
      radio_input_10: { required: true },
      radio_input_11: { required: true },
      radio_input_12: { required: true },
      shift_id: {
        required: function (data) {
          return $("#md_checkbox_28").iCheck("update")[0].checked === true;
        },
      },
      if_yes_case_status_03: {
        required: function (data) {
          return $("input[name='radio_input_03']:checked").val() === "Yes";
        },
      },
      civil_status_others: {
        required: function (data) {
          return $("#civil_status").val() === "Others";
        },
      },
      nationality_country: {
        required: function (data) {
          return $("#nationality").val() !== "Filipino";
        },
      },
      cut_off_1: {
        required: function (data) {
          return parseFloat($("#cut_off_1").val()) === 0;
        },
      },
      cut_off_2: {
        required: function (data) {
          return parseFloat($("#cut_off_2").val()) === 0;
        },
      },
      cut_off_3: {
        required: function (data) {
          return (
            parseFloat($("#cut_off_3").val()) === 0 && pay_basis === "Permanent"
          );
        },
      },
      cut_off_4: {
        required: function (data) {
          return (
            parseFloat($("#cut_off_4").val()) === 0 && pay_basis === "Permanent"
          );
        },
      },
    },
  });

  $(".chk").iCheck({ checkboxClass: "icheckbox_square-grey" });
  $(".chkradio").iCheck({ radioClass: "iradio_square-grey" });
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
    function (value, element) {
      return this.optional(element) || /^[a-z Ññ]+$/i.test(value);
    },
    "Letters only please"
  );

  $(document).on("click", "#btntempnext", function () {
    var actions = $("#addEmployees .actions").find("ul").find("li:eq(1)");
    $.post(
      commons.baseurl + "employees/Employees/isNameExist",
      {
        fname: $("#first_name").val(),
        mname: $("#middle_name").val(),
        lname: $("#last_name").val(),
        no: $("#employee_number").val(),
      },
      function (result) {
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
  $(".date_mask, .datepicker").each( function(){
    if($(this).hasClass("date_mask") && $(this).closest("td").find("input:last").hasClass("chk")){
      if($(this).closest("td").find("input:last").iCheck("update")[0].checked){
        
      // do nothing
      }else{
        $(this).inputmask("mm/dd/yyyy", {placeholder: "mm/dd/yyyy",});
      }
    }else{
      $(this).inputmask("mm/dd/yyyy", {placeholder: "mm/dd/yyyy",});
    }
  });
  // $(".attendancePeriod").inputmask("9999", { placeholder: "____" });
  // $(".salaryGrade").inputmask("99-9", { placeholder: "__-_" });
}


function insertEducRows(arrData, educType, buttonName) {
  $.each(arrData, function (k, v) {
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
  $.each(arrData, function (k, v) {
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
      // 	$("." + educClass + ":last")
      // 		.closest("tr")
      // 		.after(row);
    }
  });
}
