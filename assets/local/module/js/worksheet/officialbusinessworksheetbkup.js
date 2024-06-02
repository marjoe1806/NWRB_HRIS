$(document).ready(function () {
  $(document).on("click", "#view_report", function () {
    var control_no = $(this).data("control_no");
    var division_id = $(this).data("division_id");
    var employee_id = $(this).data("emp_id");
    var employee_lastname = $(this).data("last_name");
    var employee_firstname = $(this).data("first_name");
    var employee_middlename = $(this).data("middle_name");
    var filing_date = $(this).data("filing_date");
    var position = $(this).data("position_name");
    var meeting = $(this).data("purpose") == "meeting" ? "&#10004;" : "";
    var training_program =
      $(this).data("purpose") == "training_program" ? "&#10004;" : "";
    var others = $(this).data("purpose") == "others" ? "&#10004;" : "";
    var seminar_conference =
      $(this).data("purpose") == "seminar_conference" ? "&#10004;" : "";
    var government_transactions =
      $(this).data("purpose") == "gov_transaction" ? "&#10004;" : "";
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

    $("#department_name").html(department_name);
    $("#controlnumber").html(control_no);
    $("#division_id").html(division_id);
    $("#employeeno").html(employee_id);
    $("#lname").html(employee_lastname);
    $("#fname").html(employee_firstname);
    $("#mname").html(employee_middlename);
    $("#datefiling").html(filing_date);
    $("#position").html(position); //=========================
    $("#meeting").html(meeting);
    $("#training_program").html(training_program);
    $("#others").html(others);
    $("#seminars_conference").html(seminar_conference);
    $("#government_transaction").html(government_transactions);

    $("#activity_name").replaceWith(
      '<input type="text" value = "' +
        activity_name +
        '" id = "activity_name" style="position: relative; left: 10px; top: -3px; border: 0px;  width:100%;" readonly>'
    );
    $("#activity_date").replaceWith(
      '<input type="text" value = "' +
        transaction_date +
        '" name="activity_date" id = "activity_date" style="position: relative; left: 10px; top: -3px; border: 0px;  width:50%;" readonly="">'
    );
    $("#activity_venue").replaceWith(
      '<input type="text" value = "' +
        location +
        '" name="activity_venue" id = "activity_venue" style="position: relative; left: 10px; top: -3px; border: 0px;  width:50%;" readonly="">'
    );
    $("#activity_time").replaceWith(
      '<input type="text" value = "' +
        timein +
        " - " +
        timeout +
        '" name="activity_time" id = "activity_time" style="position: relative; left: 10px; top: -3px; border: 0px;  width:50%;" readonly="">'
    );

    if ($(this).data("status") == "APPROVED") {
      $("#date_created_date").html(datecreated[0]);
      // $("#date_created_date").replaceWith(
      //   '<input type="text" value = ' +
      //     datecreated[0].trim('"') +
      //     ' name="date_created_date" id = "date_created_date" style="position: relative; left: 10px; top: -3px; border: 0px;  width:30%;" readonly="">'
      // );
      $("#date_created_time").html(tConvert(datecreated[1]));
      // $("#date_created_time").replaceWith(
      //   '<input type="text" value = ' +
      //     tConvert(datecreated[1]) +
      //     ' name="date_created_time" id = "date_created_time" style="position: relative; left: 10px; top: -3px; border: 0px;  width:30%;" readonly="">'
      // );
      $.post(
      commons.baseurl + "timekeeping/LocatorSlips/getOBApprovals",
      { id: $(this).data("id") },
      function (result) {
        result = JSON.parse(result);
        if (result.Code == "0") {
          $.each(result.Data.details, function (i, k) {
            $(".obapprover").html(
              k["name"] +
                "<br>" +
                (k["position_designation"] == "" ||
                k["position_designation"] == null
                  ? k["position_title"]
                  : k["position_designation"])
            );
          });
        }
      }
    );
    } else {
      $("#date_created_date").html("");
      $("#date_created_time").html("");
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
      time[5] = +time[0] < 12 ? "AM" : "PM"; // Set AM/PM
      time[0] = +time[0] % 12 || 12; // Adjust hours
    }
    return time.join(""); // return adjusted time or original string
  }
  return "N/A";
}
