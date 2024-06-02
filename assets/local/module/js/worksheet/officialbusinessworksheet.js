// $(document).ready(function () {
  $(document).on("click", "#view_report", function () {

 
    var locator_id = $(this).data("locator_id");
    var checkby = $(this).data("checked_by");
    var control_no = $(this).data("control_no");
    var division_id = $(this).data("division_id");
    var employee_id = $(this).data("emp_id");
    var employee_lastname = $(this).data("last_name");
    var employee_firstname = $(this).data("first_name");
    var employee_middlename = $(this).data("middle_name");
    var status = $(this).data("status");
    var filing_date = $(this).data("filing_date");
    var position = $(this).data("position_name");
    var official = $(this).data("purpose") == "official" ? "&#10004;" : "";
    var personal = $(this).data("purpose") == "personal" ? "&#10004;" : "";
    var expected_time_return =
      $(this).data("is_return") == "1" ? "&#10004;" : "";
    var expected_not_back =
      $(this).data("is_return") == "0" ? "&#10004;" : "";
    var no_vehicle = $(this).data("is_vehicle") == "3" ? "&#10004;" : "";
    var with_vehicle = $(this).data("is_vehicle") == "2" ? "&#10004;" : "";
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
    var timein = tConvert($(this).data("transaction_time"));
    var timeout = tConvert($(this).data("transaction_time_end"));
    var datecreated = $(this).data("date_created_strto").split(" - ");
    var expected_return_time = tConvert(
      $(this).data("expected_time_return")
    );
    $(".time1").html("&emsp;&emsp;" + $(this).data("transaction_time") + "&emsp;&emsp;");
    $(".date").html("&emsp;&emsp;" + $(this).data("transaction_date_strto") + "&emsp;&emsp;");
    if($(this).data("transaction_date_end") != "0000-00-00"){
      $(".date").html("&emsp;&emsp;" + $(this).data("transaction_date_strto") + " - " + $(this).data("transaction_date_end_strto") + "&emsp;&emsp;");
    }
    $(".venue").html("&emsp;&emsp;" + location + "&emsp;&emsp;");
    $(".name_activity").html("&emsp;&emsp;" + activity_name + "&emsp;&emsp;");
    $(".official").html(official);
    $(".personal").html(personal);
    $(".expected_not_back").html(expected_not_back);
    //$(".expected_time_return").html(expected_return_time);
    $(".no_vehicle").html(no_vehicle);
    $(".with_vehicle").html(with_vehicle);
    $(".time2").html("&emsp;&emsp;" + expected_return_time + "&emsp;&emsp;");
  
    $.ajax({
      url: commons.baseurl + "timekeeping/OfficialBusinessRequest/fetchUsersByLocatorID",
      type: "POST",
      data: { locator_id: locator_id },
      dataType: "json",
      success: function (data) {
      // Display loading spinner while data is being fetched
      showLoadingSpinner();    
        // Assuming the response data contains employee information including "position"
        var employees = data.users;
  
        function generateEmployeeHtml(employee) {
          var employee_lastname = employee.decrypted_last_name;
          var employee_firstname = employee.decrypted_first_name;
          var single_full_name = employee_lastname + ", " + employee_firstname;
          var id = employee.employee_id;
          var sig = employee.sig_filename;
  
          // Check if the "position" property exists and is not null
          var position =
            employee.position_name !== null
              ? employee.position_name
              : "Position not declared";
  
          var esignHtml =
            sig == "" || sig == null
              ? ""
              : `<img src='../../assets/uploads/obattachment/signature/${id}/${sig}' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>`;
          var employeeHtml = `
            <span>${single_full_name}</span>
          `;
          return employeeHtml;
        }
  
        if (employees.length <= 1) {
          // If there is only one employee or none, don't use <li> elements
          var employee = employees[0]; // Get the single employee (if it exists)
          console.log(employee);
          if (employee) {
            var employeeHtml = generateEmployeeHtml(employee);
            $(".desig").html("&emsp;&emsp;"+position+"&emsp;&emsp;");
            // Use the single_full_name directly without <li> element
            $(".name").html("&emsp;&emsp;"+employeeHtml+"&emsp;&emsp;").show();
          } else {
            // No employee data, set the content to empty
            $(".name").html("");
          }
        } else {
          // Multiple employees, use <span> elements and align to the right
          var full_name = "";
  
          employees.forEach(function (employee) {
            hideLoadingSpinner(); // Hide loading spinner on success
            $(".desig").html('');
            var employeeHtml = generateEmployeeHtml(employee);
            full_name += employeeHtml;
          });
  
          // Align the content to the right and add bullets
          var employeeListHtml = full_name.split('<span>').join('<span style="display: block; text-align: left; margin-top: 1px;">&bull; ');
  
          $(".name").html(employeeListHtml).show();
  
          // Bind the "print_preview_modal" button click event
          $(document).on("click", "#print_preview_modal", function () {
            // Show the modal (replace the following line with the actual code to open your modal)
            // Example: $("#myModal").modal("show");
  
            // You can also show the .name element inside the modal
            $(".modal-body .name").html(employeeListHtml);
          });
  
          // Bind the modal close button click event
          // $(".modal .close").on("click", function () {
          //   // Replace the following line with the code to close your modal
          //   // Example: $("#myModal").modal("hide");
  
          //   // Hide the .name element
          //   $(".name").html("").hide();
          // });
        }
      },
      error: function (xhr, status, error) {
        hideLoadingSpinner(); // Hide loading spinner on error
        console.error("Error fetching users: " + error);
      },
    });

    // Function to show loading overlay
  function showLoadingOverlay() {
    var loadingOverlayHtml = `
      <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
          <div class="loading-emoji">⌛</div>
          <div class="loading-text">Loading... Please wait..</div>
        </div>
      </div>
    `;

  // Append the loading overlay to the body
  $("body").append(loadingOverlayHtml);

  $("#loadingOverlay").show();
}

// Function to hide loading overlay
function hideLoadingOverlay() {
  $("#loadingOverlay").remove(); // Remove the loading overlay from the DOM
}


    // Function to show loading spinner
    function showLoadingSpinner() {
      // Add code to display a loading spinner or overlay
       $("#loadingSpinner").show();
    }

    // Function to hide loading spinner
    function hideLoadingSpinner() {
      // Add code to hide the loading spinner or overlay
       $("#loadingSpinner").hide();
    }



    $(document).on("click", ".modal .modal-dialog .close", function () {
      //alert("DATA TRIGERED");
      $(".name").html('');
    });
    

    // if ($(this).data("status") == "COMPLETED" || $(this).data("status") == "APPROVED"){
    //   $(".authorized").html("&emsp;&emsp;"+checkby+"&emsp;&emsp;");
    // }else{
    //   $(".authorized").html("&emsp;&emsp;&emsp;&emsp;");
    // }
    $(".time_out").html("&emsp;&emsp;"+timein+"&emsp;&emsp;");
    $(".return_office").html("&emsp;&emsp;"+timeout+"&emsp;&emsp;");
    $(".filing_date").html(filing_date);
    $(".control_no").html(control_no);
    $(".applicantSign").html(
      ($(this).data("sig_filename") == "" || $(this).data("sig_filename") == null ? ""
      : "<img src='../../assets/uploads/obattachment/signature/" + $(this).data("employee_id") + "/" + $(this).data("sig_filename") + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>")
    );

    $(".time_out").html("&emsp;&emsp;"+timein+"&emsp;&emsp;");
    $(".return_office").html("&emsp;&emsp;"+timeout+"&emsp;&emsp;");
    $(".filing_date").html(filing_date);
    $(".control_no").html(control_no);
    

    if(!expected_time_return){
      $(".time2").html('');
    }
    $.post(
      commons.baseurl + "timekeeping/LocatorSlips/getOBApprovals",
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

            if (k["approve_type"] == "4"){
            console.log(fullname);

              $(".authorized").html(
                "<span>"+ fullname + "</span>"
              );
            }
            // if (i == 0) {
            //   if (k["approval_type"] == "3" && (status == "COMPLETED" || status == "APPROVED")){
            //       $(".authorized").html(
            //         (k["file_name"] == "" || k["file_name"] == null ? ""
            //           : "<img src='../../assets/uploads/locatorapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
            //           "<br>" +
            //           "<span>"+ k["name"] + "</span>"
            //       );
            //   }else{
            //     $(".authorized").html("&emsp;&emsp;&emsp;&emsp;");
            //   }
            // }
          });

          setTimeout(() => {
            $.each(result.Data.approved, function (i, k) {

              var fullname = k["first_name"] +" "+ k["last_name"];
              if(k["middle_name"]){
                fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"];
              }
              if(k["extension"]){
                fullname = k["first_name"] +" "+k["middle_name"].slice(0,1) +". "+ k["last_name"]+" "+ k["extension"];
              }
  
              if (k["approval_type"] == "3" && (status == "COMPLETED" || status == "APPROVED")){
                $(".authorized").html(
                  (k["file_name"] == "" || k["file_name"] == null ? ""
                    : "<img src='../../assets/uploads/locatorapproval/" + k["approved_by"]+ "/" + k["file_name"] + "' style='height:50px !important;margin-top:-20px;margin-bottom:-20px;' alt='esign'>") +
                    "<br>" +
                    "<span>"+ fullname + "</span>"
                );
              }
            });
          }, 1000);
        } else {          
          $(".authorized").html("&emsp;&emsp;&emsp;&emsp;");
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

