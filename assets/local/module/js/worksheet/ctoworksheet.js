// $(document).ready(function () {      
    $(document).on("click", "#view_report", function () {
      $(".firstApprover").empty();
      $(".secondApprover").empty();
      $(".thirdApprover").empty();
      $(".thirdApproval").html("");
      $(".thirdDisapproval").html("");
      $(".thirdRemarks_a").html("");
      $(".thirdRemarks_b").html("");
      var lname = $(this).data("lname");
      var fname = $(this).data("fname");
      var mname = $(this).data("mname");
      var department = $(this).data("department_name");
      var position = $(this).data("position_name");
      var filing_date = $(this).data("filing_date");
      var number_of_days = $(this).data("number_of_days");
      var offset_hrs = $(this).data("offset_hrs");
      var offset_mins = $(this).data("offset_mins");
      var offsetHrs = $(this).data("offsetHrs");
      var offsetMins = $(this).data("offsetMins");
      var r_offsetHrs = 0;
      var r_offsetMins = 0;

      // offset_hrs = offset_hrs * number_of_days;
      if(offset_hrs > 0 && offset_mins == 0){
        r_offsetHrs = offsetHrs - offset_hrs;
        r_offsetMins = offsetMins;
      }

      if(offsetMins > 0 || offset_mins > 0){
        if(offset_hrs > 0 && offset_mins == 0){
          r_offsetHrs = offsetHrs - offset_hrs;
          r_offsetMins = offsetMins;
        }
        if(offset_mins > 0){
          if( offset_mins > offsetMins){
            offsetHrs = offsetHrs - 1;
            r_offsetMins = (60 + offsetMins) - offset_mins;            
            r_offsetHrs = (offsetHrs - offset_hrs)
          }else{   
            r_offsetHrs = (offsetHrs - offset_hrs)
            r_offsetMins = offsetMins - offset_mins;    
          }

          // if(offset_hrs > 0){
          //   r_offsetHrs = (offsetHrs - offset_hrs)
          //   if(r_offsetMins >= 60){
          //     r_offsetMins = r_offsetMins - 60;
          //     r_offsetHrs += 1;
          //   }
          // }
        }  
      }

      $(".lname").html(lname);
      $(".fname").html(fname);
      $(".mname").html(mname);
      $(".servicedivisionunit").html("&emsp;&emsp;"+department+"&emsp;&emsp;");
      $(".position").html("&emsp;&emsp;"+position+"&emsp;&emsp;");
      $(".datefiling").html("&emsp;&emsp;"+filing_date+"&emsp;&emsp;");
      // $(".no_of_hrs").html("&emsp;&emsp;"+pad(offset_hrs,2)+" hrs & "+pad(offset_mins,2)+" mins &emsp;&emsp;");
      $(".no_of_hrs").html("&emsp;&emsp;"+pad(offset_hrs,2)+" hrs &emsp;&emsp;");
      $(".transaction_date").html("&emsp;&emsp;"+$(this).data("transaction_date")+"&emsp;&emsp;");
      $(".transaction_date").html("&emsp;&emsp;"+$(this).data("offset_date_effectivity")+"&emsp;&emsp;");
      $(".hours_earned").html("&emsp;&emsp;"+pad(offsetHrs,2)+":"+pad(offsetMins,2)+"&emsp;&emsp;");
      $(".less").html(pad(offset_hrs,2)+":"+pad(offset_mins,2));
      $(".remaining").html(pad(r_offsetHrs,2)+":"+pad(r_offsetMins,2));
      $(".applicantSign").html(
        ($(this).data("sig_filename") == "" || $(this).data("sig_filename") == null ? ""
        : "<img src='../../assets/uploads/ctoattachment/signature/" + $(this).data("employee_id") + "/" + $(this).data("sig_filename") + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>")
      );

      
      $.post(
        commons.baseurl + "leavemanagement/CTOApproval/getCTOApprovals",
        { id: $(this).data("id") },
        function (result) {
          result = JSON.parse(result);
          if (result.Code == "0") {
            $.each(result.Data.details, function (i, k) {
              if (i == 0) {
                $(".firstApprover").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/ctoapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                    "<span>"+ k["name"] + "</span>" +
                    "<br>" +
                    (k["position_title"] == "" ||
                    k["position_title"] == null
                      ? ""
                      : k["position_title"])
                );
                if(k["approval_type"] == "6"){                  
                  $(".thirdDisapproval").html("&#10004;");
                  remarks = k["remarks"];
                  remarks = "asduihmox38038fofoxejlaidjeixj2398xmfoxj3if038m2jxfp";
                  remarks_a = remarks.substr(0, 20);
                  remarks_b = remarks.replace(remarks_a,'');
                  $(".thirdRemarks_a").html(remarks_a + ' ...');
                  $(".thirdRemarks_b").html(remarks_b);
                  $(".thirdApprover").html(
                    (k["file_name"] == "" || k["file_name"] == null ? ""
                      : "<img src='../../assets/uploads/ctoapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                      "<br>" +
                      "<span>"+ k["name"] + "</span>" +
                      "<br>" +
                      (k["position_title"] == "" ||
                      k["position_title"] == null
                        ? ""
                        : k["position_title"])
                  );
                }
              // } else if (i == 1) {
              //   if (k["approval_type"] == "1")
              //     $(".secondApproval").html(k["employee_id"] == null || k["employee_id"] == "" ? "" : "&#10004;");
              //   else {
              //     $(".secondDisapproval").html("&#10004;");
              //     $(".secondRemarks").html(k["remarks"]);
              //   }
              //   $(".secondApprover").html(
              //     (k["file_name"] == "" || k["file_name"] == null ? ""
              //       : "<img src='../../assets/uploads/ctoapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
              //       "<br>" +
              //       "<span>"+ k["name"] + "</span>"
              //   );
              } else if (i == 2) {
                if (k["approval_type"] == "3")
                  $(".secondApproval").html("&#10004;");
                else {
                  $(".secondDisapproval").html("&#10004;");
                  $(".secondRemarks").html(k["remarks"]);
                }
                $(".secondApprover").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/ctoapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                    "<span>"+ k["name"] + "</span>" +
                    "<br>" +
                    (k["position_title"] == "" ||
                    k["position_title"] == null
                      ? ""
                      : k["position_title"])
                );
              } else if (i == 3) {
                if (k["approval_type"] == "4")
                  $(".thirdApproval").html("&#10004;");
                else {
                  $(".thirdDisapproval").html("&#10004;");
                  $(".thirdRemarks").html(k["remarks"]);
                }
                $(".thirdApprover").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/ctoapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                    "<span>"+ k["name"] + "</span>"
                );
              }
            });
          } else {
            $(".firstApprover").html("&emsp;&emsp;")
            $(".secondApprover").html("&emsp;&emsp;")
            $(".thirdApprover").html("&emsp;&emsp;")
          }
        }
      );
      
    });
  //  });

  
  function tConvert(time) {
    // Check correct time format and split into components
    if (typeof time != "undefined") {
      time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  
      if (time.length > 1) {
        // If time format correct
        time = time.slice(1); // Remove full string match value
        time[5] = +time[0] < 12 ? " AM" : " PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
      }
      return time.join(""); // return adjusted time or original string
    }
    return "N/A";
  }
function pad(num, size) {
    num = num.toString();
    while (num.length < size) num = "0" + num;
    return num;
}
  
  