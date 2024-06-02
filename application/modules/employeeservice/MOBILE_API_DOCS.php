/////////////////////////////////////////////////////////////////////
LOGIN
/////////////////////////////////////////////////////////////////////
url (test env): https://www.tlcpay.ph/NWRB_HRIS/employeeservice/EmployeeService/checkUser
url (production): https://www.tlcpay.ph/NWRBCHRIS/employeeservice/EmployeeService/checkUser
request : {
	"username": "eugene.padernal@mobilemoney.ph",
	"password": "999991"
}
response : {
	"Code":"0",
  "Message":"Successfully fetched data.",
  "Data":[{
    "username":"eugene.padernal@mobilemoney.ph",
    "isfirstlogin":"1",
    "full_name":"PADERNAL, EUGENE PALAY",
    "scanning_no":"999991",
    "location":"SM Bacolod",
    "latitude":"10.670766452422530000",
    "longitude":"122.942660596412900000",
    "radius":"10.448350541744773000",
    "lat1":"0.000000000000000000",
    "lat2":"0.000000000000000000",
    "long1":"0.000000000000000000",
    "long2":"0.000000000000000000"
  }]
}



/////////////////////////////////////////////////////////////////////
CHANGE PASSWORD
/////////////////////////////////////////////////////////////////////
url (test env): https://www.tlcpay.ph/NWRB_HRIS/employeeservice/EmployeeService/changePass
url (production): https://www.tlcpay.ph/NWRBCHRIS/employeeservice/EmployeeService/changePass
request : {
	"username": "eugene.padernal@mobilemoney.ph",
	"password": "1234",
	"oldpassword": "999991"
}
response : {
	"Code": "0",
	"Message": "Password successfully updated."
}



/////////////////////////////////////////////////////////////////////
INSERT TIMELOGS
/////////////////////////////////////////////////////////////////////
url (test env): https://www.tlcpay.ph/NWRB_HRIS/employeeservice/EmployeeService/addEmployeeDTR
url (production): https://www.tlcpay.ph/NWRBCHRIS/employeeservice/EmployeeService/addEmployeeDTR
request : {
	"scanning_no": "999991",
	"transaction_date": "2022-01-01",
	"transaction_time": "05:37:45",
	"transaction_type": "0",
	"source_location": "14.688891666666667,120.974485",
	"source_device": "mobile"
}
response : {
	"Code": "0",
	"Message": "Successfully recorded employee timelog."
}

/////////////////////////////////////////////////////////////////////
INSERT MULTIPLE TIMELOGS
/////////////////////////////////////////////////////////////////////
url (test env): https://www.tlcpay.ph/NWRB_HRIS/employeeservice/EmployeeService/addMultipleEmployeeDTR
url (production): https://www.tlcpay.ph/NWRBCHRIS/employeeservice/EmployeeService/addMultipleEmployeeDTR
request :  [
  {
    "scanning_no": "999991",
    "transaction_date": "2022-01-01",
    "transaction_time": "05:37:45",
    "transaction_type": "0",
    "source_location": "14.688891666666667,120.974485",
    "source_device": "mobile"
  },
  {
    "scanning_no": "999991",
    "transaction_date": "2022-01-01",
    "transaction_time": "05:37:45",
    "transaction_type": "0",
    "source_location": "14.688891666666667,120.974485",
    "source_device": "mobile"
  },
  {
    "scanning_no": "999991",
    "transaction_date": "2022-01-01",
    "transaction_time": "05:37:45",
    "transaction_type": "0",
    "source_location": "14.688891666666667,120.974485",
    "source_device": "mobile"
  },
  {
    "scanning_no": "999991",
    "transaction_date": "2022-01-01",
    "transaction_time": "05:37:45",
    "transaction_type": "0",
    "source_location": "14.688891666666667,120.974485",
    "source_device": "mobile"
  }
]
response : {
	"Code": "0",
	"Message": "Successfully recorded employee timelog."
}

