$(document).ready(function () {
    $(document).on("click", "#view_report", function () {
      var control_no = $(this).data("control_no");
      var division_id = $(this).data("division_id");
      var employee_id = $(this).data("emp_id");
      var employee_lastname = $(this).data("last_name");
      var employee_firstname = $(this).data("first_name");
      var employee_middlename = $(this).data("middle_name");
      var full_name = employee_lastname+", "+employee_firstname+" "+employee_middlename;
      var filing_date = $(this).data("filing_date");
      var position = $(this).data("position_name");
      var paid = $(this).data("purpose") == "paid" ? "&#10004;" : "";
      var cto = $(this).data("purpose") == "cto" ? "&#10004;" : "";
      var expected_time_return =
        $(this).data("purpose") == "expected_time_return" ? "&#10004;" : "";
      var expected_not_back =
        $(this).data("purpose") == "expected_not_back" ? "&#10004;" : "";
        // console.log($(this).data("purpose"));
      var activity_name = $(this).data("activity_name");
      var department_name = $(this).data("department_name");
      var transaction_date = $(this).data("transaction_date");
      var transaction_date =
        $(this).data("transaction_date_end") != "0000-00-00"
          ? $(this).data("transaction_date_strto") +
            " - " +
            $(this).data("transaction_date_end_strto")
          : $(this).data("transaction_date_strto");
      var location = $(this).data("location");
      // var timein = tConvert($(this).data("transaction_date"));
      // var timeout = tConvert($(this).data("locator_time_out"));
      var timein = tConvert($(this).data("transaction_time"));
      var timeout = tConvert($(this).data("transaction_time_end"));
      // console.log($(this).data("date_created_strto"));
      var datecreated = $(this).data("date_created_strto").split(" - ");
      // console.log(datecreated[0]);
      var expected_return_time = tConvert($(this).data("expected_time_return"));
  
      $(".time1").html("&emsp;&emsp;"+$(this).data("transaction_time")+"&emsp;&emsp;");
      $(".date").html("&emsp;&emsp;"+$(this).data("transaction_date_strto")+"&emsp;&emsp;");
      $(".venue").html("&emsp;&emsp;"+location+"&emsp;&emsp;");
      $(".venue").html("&emsp;&emsp;"+location+"&emsp;&emsp;");
      $(".name_activity").html("&emsp;&emsp;"+activity_name+"&emsp;&emsp;");
      $("#paid").html(paid);
      $("#cto").html(cto);
      $("#expected_not_back").html(expected_not_back);
      $("#expected_time_return").html(expected_time_return);
      $(".time2").html("&emsp;&emsp;"+expected_return_time+"&emsp;&emsp;");
      $(".name").html("&emsp;&emsp;"+full_name+"&emsp;&emsp;");
      $(".desig").html("&emsp;&emsp;"+position+"&emsp;&emsp;");
      $(".time_out").html("&emsp;&emsp;"+timein+"&emsp;&emsp;");
      $(".return_office").html("&emsp;&emsp;"+timeout+"&emsp;&emsp;");
      $(".filing_date").html(filing_date);
  
      if(!expected_time_return){
        $(".time2").html("");
      }
  
     });
   });
  
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
  
  