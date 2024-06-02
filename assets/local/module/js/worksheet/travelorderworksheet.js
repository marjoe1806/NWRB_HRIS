$(document).ready(function () {
  $(document).on("click", "#view_report", function () {
    $(".travel_order_no").html("");
    $(".for").html("");
    $(".vehicle_no").html("");
    $(".destination").html("");
    $(".driver").html("");
    $(".duration").html("");
    $(".no_days").html("");
    $(".location").html("");
    $(".official_purpose").html("");
    $(".not_return").html("");
    $(".return").html("");
    $(".date").html("");
    $(".approver").html("");
    $(".last_recommend").html("");
    $(".div_head").html("");
    $(".div_head_date").html("");
    
    var travel_id = $(this).data("travel_id");
    var status = $(this).data("status");
    var status_before_reject = $(this).data("status_before_reject");
    var division_head_sign_date = $(this).data("division_head_sign_date");
    var last_approve_name = $(this).data("last_approve_name");
    var prev_recomm_name = $(this).data("prev_recomm_name");
    var sec_recomm_name = $(this).data("sec_recomm_name");
    var driver = $(this).data("driver");
    var destination = $(this).data("destination");
    var vehicle_no = $(this).data("vehicle_no");
    var duration = $(this).data("duration");
    var no_days = $(this).data("no_days");
    var reason = $(this).data("reason");
    var location = $(this).data("location");
    var travel_order_no = $(this).data("travel_order_no");
    var official_purpose = $(this).data("officialpurpose");
    var employee_lastname = $(this).data("last_name");
    var employee_firstname = $(this).data("first_name");
    var employee_middlename = $(this).data("middle_name");
    var division_head_sign = $(this).data("division_head_sign");
    var deputy_sign = $(this).data("deputy_sign");
    var director_sign = $(this).data("director_sign");
    var full_name = employee_lastname+", "+employee_firstname+" "+employee_middlename;
    var not_return = $(this).data("purpose") == "not_return" ? "&#10004;" : "";
    var returns = $(this).data("purpose") == "return" ? "&#10004;" : "";
    var objDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

    $date = duration.split(' - ');
    start_date = $date[0];
    $month_name = monthNames[objDate.getMonth()];
    $date = objDate.getDate();
    $year = objDate.getFullYear();
    full_date = $month_name+" "+$date+", "+$year;
    
    if ($(this).data("purpose") === "not_return"){
      $(".reason").html(reason);
    }else{
      $(".reason").html("");
    }
    
    if (travel_order_no == 0) {
      $(".travel_order_no").html();
    }else{
      $(".travel_order_no").html(travel_order_no);
    }

    $.ajax({
      url: commons.baseurl + "travelOrder/TravelOrderApproval/fetchUsersByTravelID",
      type: "POST",
      data: { travel_id: travel_id },
      dataType: "json",
      success: function(data) {
        // Assuming the response data contains employee_lastname, employee_firstname, and employee_middlename
        var employees = data.users;
    
        // Check if the number of employees is less than or equal to 1
        if (employees.length <= 1) {
          // If there is only one employee or none, don't use <li> elements
          var employee = employees[0]; // Get the single employee (if it exists)
    
          if (employee) {
            // Assuming the data is available and contains the necessary properties
            var employee_lastname = employee.decrypted_last_name;
            var employee_firstname = employee.decrypted_first_name;
            var single_full_name = employee_lastname + ", " + employee_firstname;
    
            // Use the single_full_name directly without <li> element
            $(".for").html("&emsp;&emsp;" + single_full_name + "&emsp;&emsp;");
          } else {
            // No employee data, set the content to empty
            $(".for").html("");
          }
        } else {
          // Multiple employees, use <li> elements
          // Loop through the employees and update the full_name variable
          var full_name = '';
          employees.forEach(function(employee) {
            var employee_lastname = employee.decrypted_last_name;
            var employee_firstname = employee.decrypted_first_name;
            var single_full_name = employee_lastname + ", " + employee_firstname;
            full_name += "<li style='text-align: left;'>" + single_full_name + "</li>";
          });
    
          // Use the full_name variable as needed
          $(".for").html("&emsp;&emsp;" + full_name + "&emsp;&emsp;");
        }
        
        // Set the height of elements with class "for_height" to 100px
        $(".for_height").css("height", "100px !important");
      },
      error: function(xhr, status, error) {
        console.error('Error fetching users: ' + error);
      }
    });
    

 
    $(".vehicle_no").html("&emsp;&emsp;"+vehicle_no+"&emsp;&emsp;");
    $(".destination").html("&emsp;&emsp;"+destination+"&emsp;&emsp;");
    $(".driver").html("&emsp;&emsp;"+driver+"&emsp;&emsp;");
    $(".duration").html("&emsp;&emsp;"+duration+"&emsp;&emsp;");
    $(".no_days").html("&emsp;&emsp;"+no_days+"&emsp;&emsp;");
    // $(".location").html("&emsp;&emsp;&emsp;"+location+"&emsp;&emsp;&emsp;");
    $(".location").html(location);
    // $(".official_purpose").html("&emsp;&emsp;&emsp;"+official_purpose+"&emsp;&emsp;&emsp;");
    $(".official_purpose").html(official_purpose);
    $(".not_return").html(not_return);
    $(".return").html(returns);
    $(".date").html("&emsp;&emsp;"+duration+"&emsp;&emsp;");

    $.post(
      commons.baseurl + "travelOrder/TravelOrderRequest/getTOApprovals",
      { id: $(this).data("id"), employee_id: $(this).data("employee_id") },
      function (result) {
        result = JSON.parse(result);
        if (result.Code == "0") {
          $.each(result.Data.approvers, function (i, k) {
            var fullname = k["first_name"] +" "+ k["last_name"];
            if(k["middle_name"]){
              fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"];
            }
            if(k["extension"]){
              fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"]+" "+ k["extension"];
            }
            if(k['approve_type'] == "1"){

              $(".last_recommend").html(
                "<span>"+ fullname + "</span>"
              );

              $(".div_head").html(
                "<span>"+ fullname + "</span>"
              );

            }else if (k['approve_type'] == "3") {
              $(".approver").html(
                "<span>"+ fullname + "</span>"
              );
            }
          });
        }

        setTimeout(() => {
          if(director_sign != "" && director_sign != null){
            $(".approver").html(
              "<img src='../../assets/uploads/travelorderapproval/" + travel_id + "/" + director_sign + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>" +
              "<br>" +
              "<span>"+ last_approve_name + "</span>");
          }
          if(division_head_sign != "" && division_head_sign != null){
            $(".last_recommend").html(
              "<img src='../../assets/uploads/travelorderapproval/" + travel_id + "/" + division_head_sign + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>" +
              "<br>" +
            "<span>"+ prev_recomm_name + "</span>"); 
          }
          
          if(division_head_sign != "" &&  division_head_sign != null){
            $(".div_head").html(
              "<img src='../../assets/uploads/travelorderapproval/" + travel_id + "/" +  division_head_sign + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>" +
              "<br>" +
              "<span>"+ prev_recomm_name + "</span>");
          }
      
        }, 1000);
      }
    );
      
    if(status >= 2){
      $(".div_head_date").html("&emsp;&emsp;&emsp;" +division_head_sign_date + "&emsp;&emsp;&emsp;");
    }else{
      $('.div_head_date').html("");
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

