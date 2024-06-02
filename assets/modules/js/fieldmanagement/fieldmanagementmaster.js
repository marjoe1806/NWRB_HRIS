var getFields = {
  document: function () {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/DocumentTypes/getActiveDocumentTypes";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="type_id form-control " name="type_id" id="type_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.type_id + '">' + v.type_name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".type_id_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  allowance: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Allowances/getActiveAllowances";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="allowance_id form-control " name="allowance_id" id="allowance_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.allowance_name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".allowance_id_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  positionCode: function (data) {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Positions/getActivePositions";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        // console.log(res);
        temp = res;
        select =
          '<select class="item_no form-control " name="item_no" id="item_no" data-live-search="true">';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.code + "</option>";
          });

          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".item_no_select").html(select);
      },
    });
    //return temp;
  },
  position: function (data) {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Positions/getActivePositions";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select class="position_id form-control " name="position_id" id="position_id" data-live-search="true">';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.name + "</option>";
          });

          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".position_select").html(select);
      },
    });
    //return temp;
  },

  positionWithItem: function (data) {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Positions/getActivePositions";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select class="position_id form-control " name="position_id" id="position_id" data-live-search="true">';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.name + " ("+v.code+")</option>";
          });

          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".position_select").html(select);
      },
    });
    //return temp;
  },
  positionName: function (data) {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Positions/getActivePositionsByName";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select class="position_name form-control " name="position_name" id="position_name" data-live-search="true">';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.name + '">' + v.name + "</option>";
          });

          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".position_name_select").html(select);
      },
    });
    //return temp;
  },
  agency: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Agencies/getActiveAgencies";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="agency_id form-control " name="agency_id" id="agency_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.agency_name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".agency_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  office: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Offices/getActiveOffices";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="office_id form-control " name="office_id" id="office_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".office_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  division: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Departments/getActiveDepartments";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select = '<select class="division_id form-control " name="division_id" id="division_id" data-live-search="true">';
            options = '<option value=""></option>';
            // options += '<option value="1">OFFICE OF THE CHAIRPERSON AND COMMISSIONERS</option>';
            // options += '<option value="2">FINANCE, MANAGEMENT AND ADMINISTRATIVE SERVICE - OFFICE OF THE DIRECTOR</option>';
            // options += '<option value="3">FINANCIAL MANAGEMENT DIVISION</option>';
            // options += '<option value="4">ADMINISTRATIVE DIVISION</option>';
            // options += '<option value="5">PLANNING, MANAGEMENT AND INFORMATION SYSTEMS DIVISION</option>';
            // options += '<option value="6">RESEARCH, INVESTIGATION AND INTERNATIONAL TRADE ANALYSIS SERVICE - OFFICE OF THE DIRECTOR</option>';
            // options += '<option value="7">ECONOMICS, TRADE AND INDUSTRY STUDIES DIVISION</option>';
            // options += '<option value="8">FINANCIAL STUDIES DIVISION</option>';
            // options += '<option value="9">COMMODITIES STUDIES DIVISION</option>';
            // options += '<option value="10">INTERNATIONAL TRADE STUDIES DIVISION</option>';
          
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.department_name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".division_select").html(select);
          // $('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  fundSource: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/FundSources/getActiveFundSources";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="fund_source_id form-control " name="fund_source_id" id="fund_source_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.fund_source + "</option>";
          });
          select += options;
          select += "</select>";
          $(".fund_source_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  otherDeductions: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "fieldmanagement/OtherDeductions/getActiveOtherDeductions";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="deduction_id form-control " name="deduction_id" id="deduction_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">(' +
              v.deduct_code +
              ") " +
              v.description +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".deduction_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  otherEarnings: function () {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/OtherEarnings/getActiveOtherEarnings";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="earning_id form-control " name="earning_id" id="earning_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">(' +
              v.earning_code +
              ") " +
              v.description +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".earning_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  otherBenefits: function () {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/OtherBenefits/getActiveOtherBenefits";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="benefit_id form-control " name="benefit_id" id="benefit_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">(' +
              v.name +
              ") " +
              v.description +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".benefit_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  loan: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Loans/getActiveLoans";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="loans_id form-control " name="loans_id" id="loans_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.description + "</option>";
          });
          select += options;
          select += "</select>";
          $(".loans_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  subloan: function (id) {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/Loans/getActiveSubLoans?LoanId=" + id;
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select class="sub_loans_id form-control" name="sub_loans_id" id="sub_loans_id" data-live-search="true">';
        options = "<option>Please Select Category</option>";
        if (temp.Code == "0") {
          options = "<option></option>";
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.description + "</option>";
          });
        }
        select += options;
        select += "</select>";
        $(".sub_loans_select").html(select);
      },
    });
    //return temp;
  },
  payrollperiod: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "fieldmanagement/PeriodSettings/getActivePeriodSettings";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">' +
              v.period_id +
              " - " +
              v.start_date +
              " to " +
              v.end_date +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".payroll_period_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  payrollperiodcutoff: function (cutoff) {
    // alert(cutoff);
    var temp = null;
    select =
      '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required><option value="">Loading...</option></select>';
    $(".payroll_period_select").html(select);
    plus_url = "?PayBasis=" + cutoff;
    url2 =
      commons.baseurl +
      "fieldmanagement/PeriodSettings/getActivePeriodSettingsCutOff" +
      plus_url;
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        // console.log(res.Data)
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required>';
          options = '<option value=""></option>';
          first = true;
          $.each(temp.Data.details, function (i, v) {
            if (first) {
              selected1 = "selected";
              first = false;
            }
            options +=
              '<option value="' +
              v.id +
              '" ' +
              "data-is-posted ="+
              v.is_posted
              +">" +
              v.period_id +
              " - " +
              v.start_date +
              " to " +
              v.end_date +
              "</option>";
            selected1 = "";
          });
          select += options;
          select += "</select>";
          $(".payroll_period_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
          $("#payroll_period_id").change();
        }
      },
    });
    //return temp;
  },
  payrollperiods: function (cutoff) {
    // alert(cutoff);
    var temp = null;
    select =
      '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required><option value="">Loading...</option></select>';
    $(".payroll_period_select").html(select);
    plus_url = "?PayBasis=" + cutoff;
    url2 = commons.baseurl + "fieldmanagement/PeriodSettings/getActivePeriods";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true" required>';
          options = '<option value=""></option>';
          first = true;
          $.each(temp.Data.details, function (i, v) {
            if (first) selected1 = "selected";
            first = false;
            options +=
              '<option value="' +
              v.id +
              '" ' +
              selected1 +
              ">" +
              v.period_id +
              " - " +
              v.start_date +
              " to " +
              v.end_date +
              "</option>";
            selected1 = "";
          });
          select += options;
          select += "</select>";
          $(".payroll_period_select").html(select);
          $("#payroll_period_id").change();
        }
      },
    });
  },
  periodweeklycutoff: function (payroll_period_id) {
    var temp = null;
    plus_url = "?CutOff=" + payroll_period_id;
    url2 =
      commons.baseurl +
      "fieldmanagement/PeriodSettings/getWeeklyPeriodByPeriodId";
    return $.ajax({
      url: url2,
      data: { id: payroll_period_id },
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="cutoff_id form-control " name="cutoff_id" id="cutoff_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">' +
              v.week_number +
              " - " +
              v.start_date +
              " to " +
              v.end_date +
              "</option>";
          });
          options += '<option value="all">All</option>';
          select += options;
          select += "</select>";
          $(".cutoff_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  payrollGrouping: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "fieldmanagement/PayrollGrouping/getActivePayrollGrouping";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="payroll_grouping_id form-control " name="payroll_grouping_id" id="payroll_grouping_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.code + "</option>";
          });
          select += options;
          select += "</select>";
          $(".payroll_grouping_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  leaveGrouping: function () {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/LeaveGrouping/getActiveLeaveGrouping";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="leave_grouping_id form-control " name="leave_grouping_id" id="leave_grouping_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.code + "</option>";
          });
          select += options;
          select += "</select>";
          $(".leave_grouping_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  bonuses: function () {
    var temp = null;
    url2 = commons.baseurl + "fieldmanagement/Bonuses/getActiveBonuses";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="bonus_type form-control " name="bonus_type" id="bonus_type" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.code + "</option>";
          });
          select += options;
          select += "</select>";
          $(".bonuses_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  location: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "fieldmanagement/SatelliteLocations/getActiveSatelliteLocations";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" required>';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".location_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  mobileDevices: function () {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/MobileDevices/getActiveMobileDevices";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="mobile_id form-control " name="mobile_id" id="mobile_id" data-live-search="true" required>';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.imei +
              '">' +
              v.imei +
              " - " +
              v.description +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".mobile_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  employee: function (data) {
    var temp = null;
    select =
      '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required><option value="">Loading...</option></select>';
    $(".employee_select").html(select);
    url2 = commons.baseurl + "employees/Employees/getActiveEmployees";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select data-container="body" class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true" required>';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">' +
              v.employee_id_number +
              " - " +
              v.first_name +
              " " +
              v.middle_name +
              " " +
              v.last_name +
              "</option>";
          });
          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".employee_select").html(select);
      },
    });
    //return temp;
  },
  employeeByPayBasis: function (pay_basis) {
    var temp = null;
    url2 =
      commons.baseurl +
      "employees/Employees/getActiveEmployeesByPayBasis?PayBasis=" +
      pay_basis;
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.id +
              '">' +
              v.employee_id_number +
              " - " +
              v.first_name +
              " " +
              v.middle_name +
              " " +
              v.last_name +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".employee_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  shift: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "timekeeping/EmployeeSchedules/getActiveEmployeeSchedules";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="shift_id form-control " name="shift_id" id="shift_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.shift_code + "</option>";
          });
          select += options;
          select += "</select>";
          $(".shift_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  flexibleShift: function () {
    var temp = null;
    url2 =
      commons.baseurl +
      "timekeeping/EmployeeFlexibleSchedules/getActiveEmployeeSchedules";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="flex_shift_id form-control " name="flex_shift_id" id="flex_shift_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.id + '">' + v.shift_code + "</option>";
          });
          select += options;
          select += "</select>";
          $(".flex_shift_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  holidays: function () {
    var temp = null;
    url2 = commons.baseurl + "timekeeping/Offsetting/getActiveHolidays";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        if (res.Code == "0") {
          if (res.Data.details.length > 0) {
            vholidays = res.Data.details;
            $(".datepicker").bootstrapMaterialDatePicker({
              format: "YYYY-MM-DD",
              clearButton: true,
              time: false,
              disabledDays: [7, 6],
              disabledDates: vholidays,
            });
            $(".datepicker").on("change", function (e) {
              if (vholidays.indexOf($(this).val()) !== -1) {
                $(this).val("");
                $.alert({
                  title: '<label class="text-danger">Failed</label>',
                  content: "Invalid date.",
                });
                return false;
              } else return true;
            });
          }
        }
      },
    });
  },
  salary_grade: function (pay_basis) {
    var temp = null;
    url2 =
      commons.baseurl + "fieldmanagement/SalaryGrades/getActiveSalaryGrades";
    return $.ajax({
      url: url2,
      data: { pay_basis: pay_basis },
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="salary_grade_id form-control " name="salary_grade_id" id="salary_grade_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.grade + '">' + v.grade + "</option>";
          });
          select += options;
          select += "</select>";
          $(".salary_grade_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  salary_grade_step: function (id, pay_basis) {
    var temp = null;
    url2 =
      commons.baseurl +
      "fieldmanagement/SalaryGrades/getSalaryGradesStepsById?id=" +
      id +
      "&pay_basis=" +
      pay_basis;
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="salary_grade_step_id form-control " name="salary_grade_step_id" id="salary_grade_step_id" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' +
              v.step +
              '">Step ' +
              v.step +
              " (Php. " +
              v.salary +
              ")" +
              "</option>";
          });
          select += options;
          select += "</select>";
          $(".salary_grade_step_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  month: function () {
    var temp = null;
    url2 = commons.baseurl + "months/Months/getActiveMonths";
    return $.ajax({
      url: url2,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select data-container="body" class="month form-control " name="month" id="month" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.code + '">' + v.name + "</option>";
          });
          select += options;
          select += "</select>";
          $(".month_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  employmentStatus: function () {
    var data = [
      "Active",
      "Transferred",
      "Retired",
      "Resigned",
      "Dropped",
      "Terminated",
      "Dismissed",
    ];
    select =
      '<select class="employment_status form-control " name="employment_status" id="employment_status" data-live-search="true" required>';
    options = "";
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".employment_status_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  period: function () {
    var data = ["1st Period", "2nd Period", "3rd Period"];
    select =
      '<select class="period form-control " name="period" id="period" data-live-search="true">';
    options = "";
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".period_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  contract: function () {
    var data = ["PERMANENT", "CASUAL", "JOB ORDER"];
    select =
      '<select class="contract form-control " name="contract" id="contract" data-live-search="true">';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".contract_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  payBasis: function () {
    var data = ["Permanent", "Permanent (Probationary)"];
    var label = ["Weekly", "Bi-Monthly"];
    select =
      '<select class="pay_basis form-control " name="pay_basis" id="pay_basis" data-live-search="true" required>';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + label[i] + "</option>";
    });
    select += options;
    select += "</select>";
    $(".pay_basis_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  payBasis2: function () {
    var data = ["Permanent", "Permanent (Probationary)"];
    var label = ["Monthly", "Bi-Monthly"];
    select =
      '<select class="pay_basis form-control " name="pay_basis" id="pay_basis" data-live-search="true" required>';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + label[i] + "</option>";
    });
    select += options;
    select += "</select>";
    $(".pay_basis_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },  
  payBasis3: function () {
    var data = ["Permanent", "Contractual", "Temporary", "Contract of Service", "Appointed Official"];
    // var label = ["Permanent", "Contractual", "Temporary", "Contract of Service"];
    select =
      '<select class="pay_basis form-control " name="pay_basis" id="pay_basis" data-live-search="true" required>';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".pay_basis_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  payBasisWithAll: function () {
    var data = ["Permanent-Casual", "Job Order"];
    select =
      '<select class="pay_basis form-control " name="pay_basis" id="pay_basis" data-live-search="true">';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".pay_basis_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  otType: function () {
    var data = ["REGOT (OT1)", "WKNDOT(OT2)", "NDIFF (OT3)"];
    select =
      '<select class="ot_type form-control " name="ot_type" id="ot_type" data-live-search="true">';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".ot_type_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  payrollProcess: function () {
    var data = ["Regular Payroll", "Supplementary Payroll"];
    select =
      '<select class="payroll_process form-control " name="payroll_process" id="payroll_process" data-live-search="true">';
    options = "";
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + v + "</option>";
    });
    select += options;
    select += "</select>";
    $(".payroll_process_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  employeeModal: function (pay_basis, location_id = null, division_id = null, specific = null, content = 0, leave_grouping_id = null, payroll_grouping_id = null
  ) {
    getFields.reloadModal();
    lengthmenu = [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ];
    return $.ajax({
      type: "POST",
      url: commons.baseurl + "employees/Employees/getEmployeeList",
      data: {
        pay_basis: pay_basis,
        location_id: location_id,
        division_id: division_id,
        leave_grouping_id: leave_grouping_id,
        payroll_grouping_id: payroll_grouping_id,
        specific: specific,
      },
      dataType: "json",
      success: function (result) {
        if (result.hasOwnProperty("key")) {
          switch (result.key) {
            case "viewEmployees":
              $("#myModal .modal-dialog").attr(
                "class",
                "modal-dialog modal-lg"
              );
              $("#myModal .modal-title").html("Please Select Employee/s");
              $("#myModal .modal-body").html(result.table);
              // alert('yo')
              $("#module").DataTable({
                searching: true,
                destroy: true,
                aaSorting: [],
                columnDefs: [
                  {
                    targets: [0],
                    orderable: false,
                  },
                ],
                lengthMenu: lengthmenu,
              });

              break;
          }
          setTimeout(function () {
            $("#print-all-preloader").hide();
          }, 1000);
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
  },
  regularEmployeeModal: function (
    pay_basis,
    location_id = null,
    division_id = null,
    specific = null,
    content = 0,
    leave_grouping_id = null,
    payroll_grouping_id = null
  ) {
    getFields.reloadModal();
    lengthmenu = [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ];
    return $.ajax({
      type: "POST",
      url: commons.baseurl + "employees/Employees/getRegularEmployeeList",
      data: {
        pay_basis: pay_basis,
        location_id: location_id,
        division_id: division_id,
        leave_grouping_id: leave_grouping_id,
        payroll_grouping_id: payroll_grouping_id,
        specific: specific,
      },
      dataType: "json",
      success: function (result) {
        if (result.hasOwnProperty("key")) {
          switch (result.key) {
            case "viewEmployees":
              $("#myModal .modal-dialog").attr(
                "class",
                "modal-dialog modal-lg"
              );
              $("#myModal .modal-title").html("Please Select Employee/s");
              $("#myModal .modal-body").html(result.table);
              // alert('yo')
              $("#module").DataTable({
                searching: true,
                destroy: true,
                aaSorting: [],
                columnDefs: [
                  {
                    targets: [0],
                    orderable: false,
                  },
                ],
                lengthMenu: lengthmenu,
                /*"info": false,
                                "paging": true,
                                "lengthMenu": [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, "All"]
                                ],
                                
                                dom: 'tp',
                                language: {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search records",
                                }*/
              });

              break;
          }
          setTimeout(function () {
            $("#print-all-preloader").hide();
          }, 1000);
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
  },
  reloadModal: function () {
    $("#myModal .modal-dialog").attr("class", "modal-dialog modal-md");
    $("#myModal .modal-title").html("Loading.....");
    loader =
      '<center><div class="preloader pl-size-xl">' +
      '<div class="spinner-layer">' +
      '<div class="circle-clipper left">' +
      '<div class="circle"></div>' +
      "</div>" +
      '<div class="circle-clipper right">' +
      '<div class="circle"></div>' +
      "</div>" +
      "</div>" +
      "</div></center>";
    $("#myModal .modal-body").html(loader);
    $("#myModal").modal("show");
  },
  places: function () {
    var temp = null;
    url2 = commons.baseurl + "trainingmonitoring/AllRecord/getPlaces";
    return $.ajax({
      url: url2,

      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        if (temp.Code == "0") {
          select =
            '<select class="place form-control " name="place" id="place" data-live-search="true">';
          options = '<option value=""></option>';
          $.each(temp.Data.details, function (i, v) {
            options +=
              '<option value="' + v.place + '">' + v.place + "</option>";
          });
          select += options;
          select += "</select>";
          $(".place_select").html(select);
          //$('.selectpicker').selectpicker('refresh')
        }
      },
    });
    //return temp;
  },
  applicantStatus: function () {
    var data = ["PENDING", "REJECTED","REJECTED AFTER DELIBERATION", "ACCEPTED", "WITHDRAWN", "BLOCKED"];
    var label = ["PENDING", "REJECTED","REJECTED AFTER DELIBERATION", "ACCEPTED", "WITHDRAWN", "BLOCKED"];
    select =
      '<select class="application_status form-control " name="application_status" id="application_status" data-live-search="true" required>';
    options = '<option value=""></option>';
    $.each(data, function (i, v) {
      options += '<option value="' + v + '">' + label[i] + "</option>";
    });
    select += options;
    select += "</select>";
    $(".application_status_select").html(select);
    //$('.selectpicker').selectpicker('refresh')
    //return temp;
  },
  vacant_job: function (data) {
    var temp = null;
    url2 = commons.baseurl + "recruitment/JobOpenings/getActiveJobOpenings";
    return $.ajax({
      url: url2,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function (res) {
        temp = res;
        select =
          '<select class="vacancy_id form-control " name="vacancy_id" id="vacancy_id" required data-live-search="true">';
        options = '<option value=""></option>';
        if (temp.Code == "0") {
          $.each(temp.Data.details, function (i, v) {
            options += '<option value="' + v.id + '">' + v.name + " ("+v.code+")</option>";
          });
  
          //$('.selectpicker').selectpicker('refresh')
        }
        select += options;
        select += "</select>";
        $(".vacancy_select").html(select);
      },
    });
    //return temp;
  },
};
