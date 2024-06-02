$(document).ready(function () {
  $(document).on("click", "#view_report", function () {
    console.log($(this).data());
    $("span.boxes").html("");
    $(".firstApprover").html("");
    $(".secondApprover").html("");
    $(".thirdApprover").html("");
    $(".secondApproval").html("");
    $(".secondDisapproval").html("");
    $(".secondRemarks").html("");
    $(".thirdApproval").html("");
    $(".thirdDisapproval").html("");
    $(".thirdRemarks").html("");
    $(".dayWithPay").html("");
    $(".daysWithoutPay").html("");
    $(".otherSpecify").html("");
    
    var division_id = $(this).data("division_id");
    var department = $(this).data("department_name");
    var nodaysapplied = $(this).data("number_of_days");
    var lname = $(this).data("lname");
    var fname = $(this).data("fname");
    var mname = $(this).data("mname");
    var filing_date = $(this).data("filing_date");
    var position = $(this).data("position_name");
    var salary = $(this).data("salary");
    var type = $(this).data("type") != "" ? "&#10004;" : "";
    var type_vacation = $(this).data("type_vacation") != "" ? "&#10004;" : "";
    var dir_filename = $(this).data("dir_filename");
    var dir_employee_id = $(this).data("dir_employee_id");
    var status = $(this).data("status");
    var spl_other_details = $(this).data("spl_other_details");
    var commutation = $(this).data("commutation");
    var inclusive_dates = $(this).data("type") == "offset" ? $(this).data("offset_date_effectivity") : $(this).data("inclusive_dates");
    var vacation_locphilippines =
      $(this).data("vacation_loc") == "philippines" ? "&#10004;" : "";
    var vacation_locabroad =
      $(this).data("vacation_loc") == "abroad" ? "&#10004;" : "";
    var vacation_loc_details_philippines =
      $(this).data("vacation_loc") == "philippines"
        ? $(this).data("vacation_loc_details")
        : "";
    var vacation_loc_details_abroad =
      $(this).data("vacation_loc") == "abroad"
        ? $(this).data("vacation_loc_details")
        : "";

    var sick_lochospital =
      $(this).data("sick_loc") == "hospital" ? "&#10004;" : "";
    var sick_locpatient = $(this).data("sick_loc") == "out" ? "&#10004;" : "";
    var sick_loc_details_hosp =
      $(this).data("sick_loc") == "hospital"
        ? $(this).data("sick_loc_details")
        : "";
    var sick_loc_details_pt =
      $(this).data("sick_loc") == "out" ? $(this).data("sick_loc_details") : "";

    var type_study_master =
      $(this).data("type_study") == "master" ? "&#10004;" : "";
    var type_study_bar = $(this).data("type_study") == "bar" ? "&#10004;" : "";
    // var vacation_loc = vacation_loc
    $(".applicantSign").html(
      ($(this).data("sig_filename") == "" || $(this).data("sig_filename") == null ? ""
      : "<img src='../../assets/uploads/leaveattachment/signature/" + $(this).data("employee_id") + "/" + $(this).data("sig_filename") + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>")
    );
    $(".vl").html($(this).data("vl"));
    $(".sl").html($(this).data("sl"));
    $(".totvlsl").html($(this).data("totvlsl"));

    const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
    var formattedDate = new Date(filing_date);
    var m =  formattedDate.getMonth();
    var y = formattedDate.getFullYear();
    var as_of_date = monthNames[m] + " " + y;

    $("#servicedivisionunit").html(department);
    $("#lname").html(lname);
    $("#fname").html(fname);
    $("#mname").html(mname);
    $("#datefiling").html(filing_date);
    $(".as_of_date").html(as_of_date);
    $("#position").html(position);
    $("#salary").html(salary);
    var type_indicator = $(this).data("type").toLowerCase();
    $("." + type_indicator).html("&#10004;");
    if ($(this).data("type") == "offset") {
      $("#othersoffset").val("Offset Leave");
    }
    $("type").html(type);
    $("#type_study_master").html(type_study_master);
    $("#type_study_bar").html(type_study_bar);
    $(".incdates").html(inclusive_dates);

    $("#vacation_loc_php").html(vacation_locphilippines);
    $("#vacation_loc_abr").html(vacation_locabroad);
    $("#vacation_loc_details_php").replaceWith(
      '<input type="text" value = "' +
        vacation_loc_details_philippines +
        '" name="activity_name" id = "vacation_loc_details_php" style="font-size:9pt;position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly="">'
    );
    $("#vacation_loc_details_abr").replaceWith(
      '<input type="text" value = "' +
        vacation_loc_details_abroad +
        '" name="activity_name" id = "vacation_loc_details_abr" style="font-size:9pt;position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly="">'
    );      
    $("#spl_other_details").replaceWith(
      '<input type="text" value = "' +
      spl_other_details +
        '" name="activity_name" id = "spl_other_details" style="font-size:9pt;position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly="">'
    );
    $("#sick_loc_hosp").html(sick_lochospital);
    $("#sick_loc_pt").html(sick_locpatient);
    $("#sick_loc_details_hosp").replaceWith(
      '<input type="text" value = "' +
        sick_loc_details_hosp +
        '" name="activity_name" id = "sick_loc_details_hosp" style="font-size:9pt;position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly="">'
    );
    $("#sick_loc_details_pt").replaceWith(
      '<input type="text" value = "' +
        sick_loc_details_pt +
        '" name="activity_name" id = "sick_loc_details_pt" style="font-size:9pt;position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly="">'
    );

    var reset = 0;
    $(".nodays").html(nodaysapplied);
    if(status == 5) $(".dayWithPay").html(nodaysapplied);
    if($(this).data("type").toLowerCase() == 'vacation' || $(this).data("type").toLowerCase() == 'force'){
      $(".vl_less").html(nodaysapplied);
      $(".sl_less").html(reset);
      var vl_bal = $(this).data("vl") - nodaysapplied;
      $(".vl_bal").html(vl_bal.toFixed(3));
      
      var sl_bal = $(this).data("sl");
      $(".sl_bal").html(parseFloat(sl_bal).toFixed(3));

      if($(this).data("vl") == ""){
        $(".vl_bal").html("");
      }
      if($(this).data("sl") == ""){
        $(".sl_bal").html("");
      }
    }else if($(this).data("type").toLowerCase() == 'sick'){
      $(".vl_less").html(reset);
      $(".sl_less").html(nodaysapplied);
      var sl_bal = $(this).data("sl") - nodaysapplied;
      $(".sl_bal").html(sl_bal.toFixed(3));
      
      var vl_bal = $(this).data("vl");
      $(".vl_bal").html(parseFloat(vl_bal).toFixed(3));

      if($(this).data("vl") == ""){
        $(".vl_bal").html("");
      }
      if($(this).data("sl") == ""){
        $(".sl_bal").html("");
      }
    }else{
      $(".vl_less").html(reset);
      var vl_bal = $(this).data("vl");
      $(".vl_bal").html(parseFloat(vl_bal).toFixed(3));

      $(".sl_less").html(reset);
      var sl_bal = $(this).data("sl");
      $(".sl_bal").html(parseFloat(sl_bal).toFixed(3));

      if($(this).data("vl") == ""){
        $(".vl_bal").html("");
      }
      if($(this).data("sl") == ""){
        $(".sl_bal").html("");
      }
    }
    if (commutation == "Requested" || commutation == "REQUESTED")
      $(".commutation_requested").html("&#10004;");
    else $(".commutation_not_requested").html("&#10004;");
    v_location = $(this).data("vacation_loc");
    $.post(
      commons.baseurl + "leavemanagement/LeaveRequest/getLeaveApprovals",
      { id: $(this).data("id"), employee_id: $(this).data("employee_id") },
      function (result) {
        result = JSON.parse(result);
        if (result.Code == "0") {
          $.each(result.Data.approvers, function (i, k) {
            if(k["remarks"]=='N/A') k["remarks"] = "";

            var fullname = k["first_name"] +" "+ k["last_name"];
            if(k["middle_name"]){
              fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"];
            }
            if(k["extension"]){
              fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"]+" "+ k["extension"];
            }

            var pos = "";
            if(k['approver'] == "650bf745e83a4a" || k['approver'] == "650bf745e83a4aa" ){
              pos = "ATTY. ";
            }

            if(k['approve_type'] == "1"){
              $(".firstApprover").html(
                "<span>"+ fullname + "</span>"
              );
              $(".firstApproverPosition").html(
                "<span>"+ (jQuery.isNumeric(k["position_id"])
                ? k["position_title"]
                : k["position_id"])
                + "</span>"
              );
            }else if (k['approve_type'] == "3" || k['approve_type'] == "8") {
              $(".secondApprover").html(
                "<span>"+ fullname + "</span>"
              );
              $(".secondApproverPosition").html(
                "<span>"+ (jQuery.isNumeric(k["position_id"])
                ? k["position_title"]
                : k["position_id"])
                + "</span>"
              );
            }else if (k['approve_type'] == "4") {
              console.log(v_location);
              console.log(k['approver']);
              if(v_location == "abroad" && k['approver'] == "650bf745e83a4a"){
                $(".thirdApprover").html(
                  "<span>"+ pos + ""+ fullname + "</span>"
                );
                $(".thirdApproverPosition").html(
                  "<span>"+ (jQuery.isNumeric(k["position_id"])
                  ? k["position_title"]
                  : k["position_id"])
                  + "</span>"
                );
              }else{
                $(".thirdApprover").html(
                  "<span>"+ pos + ""+ fullname + "</span>"
                );
                $(".thirdApproverPosition").html(
                  "<span>"+ (jQuery.isNumeric(k["position_id"])
                  ? k["position_title"]
                  : k["position_id"])
                  + "</span>"
                );
              }
            }
            // if (i == 0) {
            //   $(".firstApprover").html(
                // (k["file_name"] == "" || k["file_name"] == null ? ""
                //   : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                //   // k["file_name"] +
                //   "<br>" +
                //   "<span>"+ k["name"] + "</span>" +
                //   "<br>" +
                //   (k["position_title"] == "" ||
                //   k["position_title"] == null
                //     ? ""
                //     : k["position_title"])
            //   );
            // // } else if (i == 1) {
            // //   if (k["approval_type"] == "2")
            // //     $(".secondApproval").html(k["employee_id"] == null || k["employee_id"] == "" ? "" : "&#10004;");
            // //   else {
            // //     $(".secondDisapproval").html("&#10004;");
            // //     $(".secondRemarks").html(k["remarks"]);
            // //   }
            // //   $(".secondApprover").html(
            // //     (k["file_name"] == "" || k["file_name"] == null ? ""
            // //       : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
            // //       // k["file_name"] +
            // //       "<br>" +
            // //       "<span>"+ k["name"] + "</span>" +
            // //       "<br>" +
            // //       (k["position_designation"] == "" ||
            // //       k["position_designation"] == null
            // //         ? k["position_title"]
            // //         : k["position_designation"])
            // //   );
            // } else if (i == 2) {
            //   if (k["approval_type"] == "3")
            //     $(".secondApproval").html("&#10004;");
            //   else {
            //     $(".secondDisapproval").html("&#10004;");
            //     $(".secondRemarks").html(k["remarks"]);
            //   }
            //   $(".secondApprover").html(
            //     (k["file_name"] == "" || k["file_name"] == null ? ""
            //       : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
            //       // k["file_name"] +
            //       "<br>" +
            //       "<span>"+ k["name"] + "</span>" +
            //       "<br>" +
            //       (k["position_title"] == "" ||
            //       k["position_title"] == null
            //         ? ""
            //         : k["position_title"])
            //   );
            // } else if (i == 3) {
            //   if (k["approval_type"] == "4")
            //     $(".thirdApproval").html("&#10004;");
            //   else {
            //     $(".thirdDisapproval").html("&#10004;");
            //     $(".thirdRemarks").html(k["remarks"]);
            //   }
            //   $(".thirdApprover").html(
            //     (k["file_name"] == "" || k["file_name"] == null ? ""
            //       : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
            //       // k["file_name"] +
            //       "<br>" +
            //       "<span>"+ k["name"] + "</span>"
            //       // (k["position_designation"] == "" ||
            //       // k["position_designation"] == null
            //       //   ? k["position_title"]
            //       //   : k["position_designation"])
            //   );
            // }
          });

          setTimeout(() => {
            $.each(result.Data.approved, function (i, k) {
              if(k["remarks"]=='N/A') k["remarks"] = "";
              
              var fullname = k["first_name"] +" "+ k["last_name"];
              if(k["middle_name"]){
                fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"];
              }
              if(k["extension"]){
                fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"]+" "+ k["extension"];
              }

              var pos = "";
              if(k['approver'] == "650bf745e83a4a" || k['approver'] == "650bfc7c045d7" ){
                pos = "ATTY. ";
              }
              
              if(k['approval_type'] == "1"){
                $(".firstApprover").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                  "<span>"+ fullname + "</span>"
                );

                $(".firstApproverPosition").html(
                  "<span>"+ (jQuery.isNumeric(k["position_id"])
                  ? k["position_title"]
                  : k["position_id"])
                  + "</span>"
                );
              }else if (k['approval_type'] == "3") {
                $(".secondApprover").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                  "<span>"+ fullname + "</span>"
                );
                $(".secondApproverPosition").html(
                  "<span>"+ (jQuery.isNumeric(k["position_id"])
                  ? k["position_title"]
                  : k["position_id"])
                  + "</span>"
                );
              }else if (k['approval_type'] == "4") {
                if(v_location == "abroad" && k['employee_id'] == "650bf745e83a4a"){
                  $(".thirdApprover").html(
                    (k["file_name"] == "" || k["file_name"] == null ? ""
                      : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                      "<br>" +
                    "<span>"+ pos + ""+ fullname + "</span>"
                  );
                  $(".thirdApproverPosition").html(
                    "<span>"+ (jQuery.isNumeric(k["position_id"])
                    ? k["position_title"]
                    : k["position_id"])
                    + "</span>"
                  );
                }else{
                  $(".thirdApprover").html(
                    (k["file_name"] == "" || k["file_name"] == null ? ""
                      : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                      "<br>" +
                    "<span>"+ pos + ""+ fullname + "</span>"
                  );
                  $(".thirdApproverPosition").html(
                    "<span>"+ (jQuery.isNumeric(k["position_id"])
                    ? k["position_title"]
                    : k["position_id"])
                    + "</span>"
                  );
                }
                //   if(v_location == "abroad" && k['employee_id'] == "650bf745e83a4a"){
              //     $(".thirdApprover").html(
              //       (k["file_name"] == "" || k["file_name"] == null ? ""
              //         : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
              //         "<br>" +
              //       "<span>"+ fullname + "</span>"
              //     );
              //     $(".thirdApproverPosition").html(
              //       "<span>"+ (jQuery.isNumeric(k["position_id"])
              //       ? k["position_title"]
              //       : k["position_id"])
              //       + "</span>"
              //     );
              // }else if(v_location != "abroad" && k['employee_id'] != "650bf745e83a4a"){
              //     $(".thirdApprover").html(
              //       (k["file_name"] == "" || k["file_name"] == null ? ""
              //         : "<img src='../../assets/uploads/leaveapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
              //         "<br>" +
              //       "<span>"+ fullname + "</span>"
              //     );
              //     $(".thirdApproverPosition").html(
              //       "<span>"+ (jQuery.isNumeric(k["position_id"])
              //       ? k["position_title"]
              //       : k["position_id"])
              //       + "</span>"
              //     );
              }
            });
          }, 1000);

          
          
          if((status == 5 || status == 6) && dir_filename != "" && dir_employee_id != ""){
            if(status == 5) $(".thirdRemarks").html("");
            $(".thirdApprover").html(
              (dir_filename == "" || dir_filename == null ? ""
                : "<img src='../../assets/uploads/leaveapproval/" + dir_employee_id + "/" + dir_filename + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                "<br>" +
                "<span>"+ "DAVID, SEVILLO D. JR." + "</span>" +
                "<br>" +
                "EXECUTIVE DIRECTOR III"
            );
            $(".thirdDesignation").hide();
          }
        } else {
          $(".firstApprover").html("&emsp;&emsp;")
          $(".secondApprover").html("&emsp;&emsp;")
          $(".thirdApprover").html("&emsp;&emsp;")
          $(".secondApproval").html("&emsp;&emsp;")
        }
      }
    );

    // $.post(
    //   commons.baseurl + "leavemanagement/LeaveRequest/getLeaveCredits",
    //   { employee_id: $(this).data("employee_id") },
    //   function (result) {
    //     result = JSON.parse(result);
    //     $(".vacationdays").html(result.data.vl);
    //     $(".sickdays").html(result.data.sl);
    //     $(".totaldays").html(result.data.leaveCreditsTotal);
    //   }
    // );

    // $.post(
    //   commons.baseurl + "leavemanagement/LeaveRequest/getLeaveDates",
    //   { id: $(this).data("id") },
    //   function (result) {
    //     result = JSON.parse(result);
    //     if (result.Code == "0") {
    //       console.log(nodaysapplied);
    //       var vl = (sl = 0);
    //       if (
    //         $(this).data("type") !== "rehab" &&
    //         $(this).data("type") !== "maternity" &&
    //         $(this).data("type") !== "study"
    //       ) {
    //         $.each(result.Data.details, function (i, k) {
    //           if (i == 0) {
    //             $(".incdates").html(k["days_of_leave"]);
    //           } else {
    //             $(".incdates").append(", " + k["days_of_leave"]);
    //           }
    //           if (k["status"] == "1") {
    //             if (k["leave_type"] == "SL") sl++;
    //             else vl++;
    //           }
    //         });
    //         // $(".vacationdays").html(vl);
    //         // $(".sickdays").html(sl);
    //       } else {
    //         $(".incdates").html(result.Data.details[0]["days_of_leave"]);
    //       }
    //     }
    //   }
    // );
  });
});
