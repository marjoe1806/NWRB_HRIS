<?php
	class AttendanceSummaryCollection extends Helper {
      	var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		//Fetch
		var $table = "tblemployees";
    var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.contract','');
    public function getColumns(){
    	$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

    public function getEmployeeColumns(){
      $sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		function make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id)
		{
			//var_dump($this->select_column);die();
			$employee_columns = $this->getEmployeeColumns();
			foreach ($employee_columns as $kemp => $vemp) {
				if ($vemp['COLUMN_NAME'] != "id" || $vemp['COLUMN_NAME'] != "modified_by" || $vemp['COLUMN_NAME'] != "is_active" || $vemp['date_created'] != "id") {
					$this->select_column[] = 'tblemployees.'.$vemp['COLUMN_NAME'];
				}
			}
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");

		    // if(isset($_POST["search"]["value"]))
		    // {
		    // 	$this->db->group_start();
		    //  	foreach ($this->select_column as $key => $value) {
		    //  		//$this->db->like($value, $_POST["search"]["value"]);
		    //  		$this->db->or_like($value, $_POST["search"]["value"]);
		    //  	}
		    //     $this->db->group_end();
		    // }
		    if(isset($_POST["search"]["value"]))
		    {
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]);
		     		//var_dump($value == "$this->table.first_name");
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);

		     		}
		     		else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);
		     		}

		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		        $this->db->group_end();
		    }

		    if(isset($employment_status) && $employment_status != null)
		    	$this->db->where('tblemployees.employment_status != "Active"');
		    else
		    	$this->db->where('tblemployees.employment_status = "Active"');
		    //var_dump($_GET['Id']);die();
		    if(isset($employee_id) && $employee_id != null)
					$this->db->where('tblemployees.id="'.$employee_id.'"');//die('hit');

		    if((isset($pay_basis) && $pay_basis != null))
		    	$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');//die('hit');
		    // if((isset($payroll_period_id) && $payroll_period_id != null))
		    // 	$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');//die('hit');
		    else{
		    	$this->db->where('1=0');
		    }

		    if(isset($_POST["order"]))
		    {
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else
		    {
		          $this->db->order_by($this->table.'.id', 'DESC');
		    }
		}
		// function make_datatables($employment_status,$employee_id,$pay_basis,$payroll_period_id){
		//     $this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id);
		//     if($_POST["length"] != -1)
		//     {
		//         $this->db->limit($_POST['length'], $_POST['start']);
		//     }

		//     $query = $this->db->get();
		//     //echo $this->db->last_query();die();
		//     return $query->result();
		// }

		function make_query_employee()
		{
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions",$this->table.".position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies",$this->table.".agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices",$this->table.".office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments",$this->table.".division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources",$this->table.".fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans",$this->table.".loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications",$this->table.".budget_classification_id = tblfieldbudgetclassifications.id","left");

		    // if(isset($_POST["search"]["value"]))
		    // {
		    // 	$this->db->group_start();
		    //  	foreach ($this->select_column as $key => $value) {
		    //  		$this->db->or_like($value, $_POST["search"]["value"]);
		    //  	}
		    //     $this->db->group_end();
		    // }
		    if(isset($_POST["search"]["value"]))
		    {
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]);
		     		//var_dump($value == "$this->table.first_name");
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);

		     		}
		     		else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);
		     		}

		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		        $this->db->group_end();
		    }

		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)
		    	$this->db->where($this->table.'.employment_status != "Active"');
		    else
					$this->db->where($this->table.'.employment_status = "Active"');

				if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null) {
					$this->db->where($this->table.'.pay_basis = "'. $_GET['PayBasis'] .'"');
				} else {
					$this->db->where('1=0');
				}
		    if(isset($_POST["order"]))
		    {
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else
		    {
		          $this->db->order_by('id', 'DESC');
		    }
		}

		function make_datatables(){
			$this->make_query_employee();
			if($_POST["length"] != -1)
			{
					$this->db->limit($_POST['length'], $_POST['start']);
			}
			$query = $this->db->get();
			return $query->result();
		}

		function get_filtered_data(){
			$this->make_query_employee();
			$query = $this->db->get();
			return $query->num_rows();
		}

		function get_all_data()
		{
				$this->db->select($this->table."*");
				$this->db->from($this->table);
				return $this->db->count_all_results();
		}

		// function get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id){
		//      $this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id);
		//      //var_dump($this->make_query());die();

		//      $query = $this->db->get();
		//      return $query->num_rows();
		// }
		// function get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id)
		// {
		//     $this->db->select($this->table."*");
		//     $this->db->from($this->table);
		//     $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		//     $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		//     $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		//     $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		//     $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		//     $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		//     $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		//     $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		//     if(isset($employment_status) && $employment_status != null)
		//     	$this->db->where('tblemployees.employment_status != "Active"');
		//     else
		//     	$this->db->where('tblemployees.employment_status = "Active"');
		//     //var_dump($_GET['Id']);die();
		//     if(isset($employee_id) && $employee_id != null)
		//     	$this->db->where('tblemployees.id="'.$employee_id.'"');//die('hit');
		//     if((isset($pay_basis) && $pay_basis != null))
		//     	$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');//die('hit');
		//     // if((isset($payroll_period_id) && $payroll_period_id != null))
		//     // 	$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');//die('hit');
		//     else{
		//     	$this->db->where('1=0');
		//     }
		//     return $this->db->count_all_results();
		// }

		function fetchPayroll($employment_status,$employee_id,$pay_basis,$payroll_period_id){
			$this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id);
		    $query = $this->db->get();
		    return $query->result_array();
		}

		//End Fetch
		public function hasRowsAllowancesActive($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$id = $employee_id;
			$message = "No data available.";
			$sql = " SELECT * FROM tblemployeesallowances where is_active = '1' AND employee_id = ?";
			$query = $this->db->query($sql,array($id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched allowances.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($payroll_period_id));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollPeriodCutoffById($id){
			$sql = " SELECT * FROM tblfieldperiodsettingsweekly WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($id));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollAllowances($payroll_period,$employee_id){
			$sql = " SELECT * FROM tbltransactionspayrollprocessallowances a
					 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id
					 WHERE YEAR(a.date_amortization) = YEAR(?) AND MONTH(a.date_amortization) = MONTH(?) AND b.employee_id = ? ";
			$query = $this->db->query($sql,array($payroll_period,$payroll_period,$employee_id));
			$data = $query->result_array();
			return $data;
		}

		// attendance summary
		function make_datatables_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){
			$result = $this->make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id);
			return $result;
		}

		function make_datatables_summary_all($pay_basis, $payroll_period, $payroll_period_id){
			$summary = array();
			$all_employees = $this->getAllEmployeeDetails($pay_basis);
			foreach ($all_employees as $k => $employee) {
				$summary['records'][$employee['id']] = $this->make_query_summary($employee['id'], $this->Helper->decrypt($employee['employee_number'], $employee['id']), $payroll_period, $payroll_period_id, $employee['shift_id']);
			}
			$summary['employee'] = $all_employees;
			return $summary;
		}

		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){

			$dtr = array();

			// Get global settings
			$attendance_settings = $this->checkGlobalSettings();

			$working_days = $this->getWorkingDays($payroll_period);

			foreach($working_days as $k => $working_day){

				$attendance_working_day = $this->getAttendanceByWorkingDays($working_day, $employee_number);

				// Set defaults
				$dtr[$k]['transaction_date'] = isset($working_day) ? $working_day : null;
				$dtr[$k]['time_in'] = null;
				$dtr[$k]['break_out'] = null;
				$dtr[$k]['break_in'] = null;
				$dtr[$k]['time_out'] = null;
				$dtr[$k]['official_time_in'] = null;
				$dtr[$k]['official_time_out'] = null;
				$dtr[$k]['leave_hours'] = "0.00";
				$dtr[$k]['late_hours'] = '0.00';
				$dtr[$k]['undertime_hours'] = '0.00';
				$dtr[$k]['regular_hours'] = "0.00";
				$dtr[$k]['regular_nightdiff_hours'] = '0.00';
				$dtr[$k]['absent_hours'] = '0.00';
				$dtr[$k]['break_deduction'] = '0.00';
				$dtr[$k]['present_day'] = '0.00';
				$dtr[$k]['regular_overtime'] = '0.00';
				$dtr[$k]['nightdiff_overtime'] = '0.00';
				$dtr[$k]['restday_overtime'] = '0.00';
				$dtr[$k]['legal_holiday_overtime'] = '0.00';
				$dtr[$k]['legal_holiday_restday_overtime'] = '0.00';
				$dtr[$k]['special_holiday_overtime'] = '0.00';
				$dtr[$k]['special_holiday_restday_overtime'] = '0.00';
				$dtr[$k]['regular_excess_overtime'] = '0.00';
				$dtr[$k]['restday_excess_overtime'] = '0.00';
				$dtr[$k]['legal_excess_overtime'] = '0.00';
				$dtr[$k]['legal_excess_restday_overtime'] = '0.00';
				$dtr[$k]['special_excess_overtime'] = '0.00';
				$dtr[$k]['special_excess_restday_overtime'] = '0.00';

				// Get time logs
				$log_in 		= isset($attendance_working_day['time_in']) 	? $attendance_working_day['time_in'] 		: null;
				$time_in 		= isset($attendance_working_day['time_in']) 	? $attendance_working_day['time_in'] 		: null;
				$time_out 	= isset($attendance_working_day['time_out']) 	? $attendance_working_day['time_out'] 	: null;
				$break_out 	= isset($attendance_working_day['break_out']) ? $attendance_working_day['break_out'] 	: null;
				$break_in 	= isset($attendance_working_day['break_in']) 	? $attendance_working_day['break_in'] 	: null;

				// Get grace period and night diff hours
				if(sizeof($attendance_settings) > 0) {
					$night_shift_start = date("Y-m-d H:i:s", strtotime($attendance_settings['night_differential_hours_between_from'] . ' ' . $working_day));
					$night_shift_end = date("Y-m-d H:i:s", strtotime(($attendance_settings['night_differential_hours_between_to'] < $attendance_settings['night_differential_hours_between_from'] ) ? $attendance_settings['night_differential_hours_between_to']  . ' ' . date('Y-m-d', strtotime($working_day . '+1 day')) : $attendance_settings['night_differential_hours_between_to'] . ' ' . $working_day));
				}

				// Check if holiday
				$holiday_details = array();
				if(sizeof($this->checkIfHoliday($working_day)) > 0){
					$holiday_details = $this->checkIfHoliday($working_day);
				}

				// Check if leave
				$leave_details = array();
				if(sizeof($this->checkForApprovedLeave($working_day, $employee_id)) > 0) {
					$leave_details = $this->checkForApprovedLeave($working_day, $employee_id);
				}

				$employee_schedule = $this->getEmployeeSchedule($shift_id, $working_day);

				$legal_holiday_overtime = 0; $legal_excess_overtime = 0; $special_holiday_overtime = 0; $special_excess_overtime = 0; $overtime_nightdiff_hours = 0; $restday_overtime = 0; $restday_excess_overtime = 0; $regular_hours = 0; $regular_nightdiff_hours = 0; $regular_overtime = 0; $legal_holiday_restday_overtime = 0; $legal_excess_restday_overtime = 0; $special_holiday_restday_overtime = 0; $special_excess_restday_overtime = 0;

				// Employee attendance with regular schedule
				if(isset($employee_schedule) && sizeof($employee_schedule) > 0 && $employee_schedule != null) {

					$official_time_in = $working_day . ' ' . $employee_schedule['start_time'];
					$official_time_out = ($employee_schedule['end_time'] < $employee_schedule['start_time']) ? date('Y-m-d', strtotime($working_day . '+1 day')) . ' ' . $employee_schedule['end_time'] : $working_day . ' ' . $employee_schedule['end_time'];

					// Grace period
					$grace_period = date("Y-m-d H:i:s", strtotime($official_time_in . "+" . round($attendance_settings['grace_period'], 0) . " minutes"));
					$grace_period_time = sizeof($attendance_settings) > 0 ? $this->getTimeDifference($official_time_in, $grace_period) : null;

					// official break times not used yet
					$official_break_out = $employee_schedule['break_start_time'];
					$official_break_in = $employee_schedule['break_end_time'];

					// Check official schedule
					if(isset($official_time_in) && isset($official_time_out) && $official_time_in != null && $official_time_out != null) {
						if($time_in != null && $time_out != null) {
							// Check early in
							if($time_in < $official_time_in)
								$time_in = $official_time_in;
							// Check if under grace period
							elseif($time_in >= $official_time_in && $time_in <= $grace_period)
								$time_in = $official_time_in;
						} else {
							$absent_hours = sizeof($leave_details) > 0 || sizeof($holiday_details) > 0 ? "0.00" : "8.00";
						}
					}

					// Get decimal hours for each column
					if(isset($time_in) && $time_in != null && isset($time_out) && $time_out != null) {

						// Check attendance
						$present_day = '1.00'; $absent_hours = '0.00';

						// Total
						$total_hours = $time_out < $official_time_out ? $this->getTimeDifference($time_in, $time_out) : $this->getTimeDifference($time_in, $official_time_out);

						// Break
						$total_break = "0.00";
						if(($break_out != null && $break_in != null)){
							$break_result = $this->getTimeDifference($break_in, $break_out);
							if($break_result > 1)
								$total_break = $break_result - 1;
						}

						// Break
						$break_result = (isset($break_result) && $total_hours < 5) ? $break_result : 1;

						// Working Hours
						$working_hours = ($official_time_in != null && $time_in > $grace_period) ? ($total_hours - $break_result) + $grace_period_time : $total_hours - $break_result;

						// Late Hours
						$late_hours = ($official_time_in != null && $time_in > $grace_period) ? floatval($this->getTimeDifference($time_in, $official_time_in) - $grace_period_time) : null;

						// // Working Hours
						// $working_hours = $total_hours - $break_result;

						// // Late Hours
						// $late_hours = ($official_time_in != null && $time_in > $grace_period) ? $this->getTimeDifference($time_in, $official_time_in) : null;


						// Undertime hours
						$undertime_hours = ($official_time_out != null && $time_out < $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;

						if(sizeof($holiday_details) > 0) {
							// $legal_holiday_overtime; $special_holiday_overtime;
							switch ($holiday_details[0]['holiday_type']) {
								case 'Regular':
								case 'Legal':
									if($employee_schedule['is_restday'] == 1){
										// Legal Holiday Hours
										$legal_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

										// Legal Excess Overtime
										$legal_excess_restday_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
									}	else {
										// Legal Holiday Hours
										$legal_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

										// Legal EXcess Overtime
										$legal_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
									}
									break;

								default:
								if($employee_schedule['is_restday'] == 1){
									// Special Holiday Hours
									$special_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

									// Special Excess Overtime
									$special_excess_restday_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
								}	else {
									// Special Holiday Hours
									$special_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

									// Special Excess Overtime
									$special_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
								}
								break;
							}

							// Overtime Night Differential Hours
							$overtime_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

						}
						else {

							if($employee_schedule['is_restday'] == 1) {
								// Regular Hours
								$restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

								// Regular Overtime
								$restday_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
							}
							else {

								// Regular Hours
								$regular_hours = $working_hours;


								// Regular Night Differential Hours
								$regular_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

								// Regular Overtime
								$regular_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
							}

						}

					}
					else {
						// die('hit');
						$absent_hours = isset($employee_schedule['is_restday']) && $employee_schedule['is_restday'] == 1 ? "0.00" : sizeof($holiday_details) > 0 ? "0.00" : "8.00"; ;
						$present_day = sizeof($leave_details) > 0 ? "1.00" : sizeof($holiday_details) > 0 ? "1.00" : "0.00";
						$total_break = null;
						$total_hours = null;
						$regular_hours = sizeof($holiday_details) > 0 ? "8.00" : "0.00";
						$late_hours = null;
						$undertime_hours = null;
						$regular_nightdiff_hours = null;
						$regular_overtime = null;
						$restday_overtime = null;
						$restday_excess_overtime = null;
					}

				}

				// Employee attendance flexible schedule
				else {
					// Get decimal hours for each column
					if(isset($time_in) && $time_in != null && isset($time_out) && $time_out != null) {

						// Check attendance
						$present_day = '1.00'; $absent_hours = '0.00';

						// Total
						$total_hours = $this->getTimeDifference($time_in, $time_out);

						// Break
						$total_break = ($break_out != null && $break_in != null) ? $this->getTimeDifference($time_in, $time_out) : ($total_hours > 4) ? '1.00' : '0.00';

						// Working
						$working_hours = $total_hours - $total_break;

						// Undertime
						$undertime_hours = ($working_hours < 8) ? abs(8 - $working_hours) : null;

						if(sizeof($holiday_details) > 0) {
							// $legal_holiday_overtime; $special_holiday_overtime;
							switch ($holiday_details[0]['holiday_type']) {
								case 'Regular':
								case 'Legal':
									// if($employee_schedule['is_restday'] == 1){
									// 	// Legal Holiday Hours
									// 	$legal_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

									// 	// Legal Excess Overtime
									// 	$legal_excess_restday_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
									// }	else {
										// Legal Holiday Hours
										$legal_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

										// Legal EXcess Overtime
										$legal_excess_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
									// }
									break;

								default:
								// if($employee_schedule['is_restday'] == 1){
								// 	// Special Holiday Hours
								// 	$special_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

								// 	// Special Excess Overtime
								// 	$special_excess_restday_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
								// }	else {
									// Special Holiday Hours
									$special_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

									// Special Excess Overtime
									$special_excess_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
								// }
								break;
							}

							// Overtime Night Differential Hours
							$overtime_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

						}
						else {

								// Regular Hours
								$regular_hours = ($working_hours > 8) ? 8 : $working_hours;

								// Regular Night Differential Hours
								$regular_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

								// Regular Overtime
								$regular_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;

						}
					}
					else {
						$absent_hours = isset($employee_schedule['is_restday']) && $employee_schedule['is_restday'] == 1 ? "0.00" : "8.00" ;
						$present_day = sizeof($leave_details) > 0 ? "1.00" : sizeof($holiday_details) > 0 ? "1.00" : "0.00";
						$total_break = null;
						$total_hours = null;
						$regular_hours = sizeof($holiday_details) > 0 ? "8.00" : "0.00";
						$late_hours = null;
						$undertime_hours = null;
						$regular_nightdiff_hours = null;
						$regular_overtime = null;
						$restday_overtime = null;
						$restday_excess_overtime = null;
					}
				}

				// $regular_hours = ($regular_hours <= 8) ? $regular_hours - $late_hours : $regular_hours;

				// Finalize Data
				$dtr[$k]['time_in'] = isset($log_in) ? $log_in : null;
				$dtr[$k]['break_out'] = isset($break_out) ? $break_out : null;
				$dtr[$k]['break_in'] = isset($break_in) ? $break_in : null;
				$dtr[$k]['time_out'] = isset($time_out) ? $time_out : null;
				$dtr[$k]['official_time_in'] = isset($official_time_in) ? $official_time_in : null;
				$dtr[$k]['official_time_out'] = isset($official_time_out) ? $official_time_out : null;
				$dtr[$k]['leave_hours'] = sizeof($leave_details) > 0 ? "8.00" : "0.00";
				$dtr[$k]['late_hours'] = (isset($late_hours) && $late_hours != null) ? $late_hours : "0.00";
				$dtr[$k]['undertime_hours'] = (isset($undertime_hours) && $undertime_hours != null) ? $undertime_hours : "0.00";
				// $dtr[$k]['regular_hours'] = (isset($regular_hours) && $regular_hours != null) ? (isset($late_hours) && $late_hours != null) ? $regular_hours - $late_hours : $regular_hours : "0.00";
				$dtr[$k]['regular_hours'] = (isset($regular_hours) && $regular_hours != null) ? $regular_hours : "0.00";
				$dtr[$k]['regular_nightdiff_hours'] = isset($regular_nightdiff_hours) ? $regular_nightdiff_hours : "0.00";
				$dtr[$k]['absent_hours'] = (isset($absent_hours) && $absent_hours != null) ? $absent_hours : "0.00";
				$dtr[$k]['break_deduction'] = (isset($total_break) && $total_break != null) ? $total_break : "0.00";
				$dtr[$k]['present_day'] = (isset($present_day) && $present_day != null) ? $present_day : "0.00";
				$dtr[$k]['regular_overtime'] = (isset($regular_overtime) && $regular_overtime != null) ? $regular_overtime : "0.00";
				$dtr[$k]['nightdiff_overtime'] = (isset($overtime_nightdiff_hours) && $overtime_nightdiff_hours != null) ? $overtime_nightdiff_hours : "0.00";
				$dtr[$k]['restday_overtime'] = (isset($restday_overtime) && $restday_overtime != null) ? $restday_overtime : "0.00";
				$dtr[$k]['legal_holiday_overtime'] = (isset($legal_holiday_overtime) && $legal_holiday_overtime != null) ? $legal_holiday_overtime : "0.00";
				$dtr[$k]['legal_holiday_restday_overtime'] = (isset($legal_holiday_restday_overtime) && $legal_holiday_restday_overtime != null) ? $legal_holiday_restday_overtime : "0.00";
				$dtr[$k]['special_holiday_overtime'] = (isset($special_holiday_overtime) && $special_holiday_overtime != null) ? $special_holiday_overtime : "0.00";
				$dtr[$k]['special_holiday_restday_overtime'] = (isset($special_holiday_restday_overtime) && $special_holiday_restday_overtime != null) ? $special_holiday_restday_overtime : "0.00";
				// $dtr[$k]['regular_excess_overtime'] = '0.00'; // No
				$dtr[$k]['restday_excess_overtime'] = (isset($restday_excess_overtime) && $restday_excess_overtime != null) ? $restday_excess_overtime : "0.00";
				$dtr[$k]['legal_excess_overtime'] = (isset($legal_excess_overtime) && $legal_excess_overtime != null) ? $legal_excess_overtime : "0.00";
				$dtr[$k]['legal_excess_restday_overtime'] = (isset($legal_excess_restday_overtime) && $legal_excess_restday_overtime != null) ? $legal_excess_restday_overtime : "0.00";
				$dtr[$k]['special_excess_overtime'] = (isset($special_excess_overtime) && $special_excess_overtime != null) ? $special_excess_overtime : "0.00";
				$dtr[$k]['special_excess_restday_overtime'] = (isset($special_excess_restday_overtime) && $special_excess_restday_overtime != null) ? $special_excess_restday_overtime : "0.00";


					// var_dump($holiday_details);
			}
			// die();
			$current_period = explode("-", $payroll_period);
			$response['data'] = $dtr;
			$response['total_working_days'] = $this->calculateWorkingDaysInMonth($current_period[0], $current_period[1]);
			return $response;
		}

		// function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){
		// 	$dtr = array();

		// 	// Get global settings
		// 	$attendance_settings = $this->checkGlobalSettings();

		// 	$working_days = $this->getWorkingDays($payroll_period);

		// 	foreach($working_days as $k => $working_day){

		// 		$attendance_working_day = $this->getAttendanceByWorkingDays($working_day, $employee_number);

		// 		// Set defaults
		// 		$dtr[$k]['transaction_date'] = isset($working_day) ? $working_day : null;
		// 		$dtr[$k]['time_in'] = null;
		// 		$dtr[$k]['break_out'] = null;
		// 		$dtr[$k]['break_in'] = null;
		// 		$dtr[$k]['time_out'] = null;
		// 		$dtr[$k]['official_time_in'] = null;
		// 		$dtr[$k]['official_time_out'] = null;
		// 		$dtr[$k]['leave_hours'] = "0.00";
		// 		$dtr[$k]['late_hours'] = '0.00';
		// 		$dtr[$k]['undertime_hours'] = '0.00';
		// 		$dtr[$k]['regular_hours'] = "0.00";
		// 		$dtr[$k]['regular_nightdiff_hours'] = '0.00';
		// 		$dtr[$k]['absent_hours'] = '0.00';
		// 		$dtr[$k]['break_deduction'] = '0.00';
		// 		$dtr[$k]['present_day'] = '0.00';
		// 		$dtr[$k]['regular_overtime'] = '0.00';
		// 		$dtr[$k]['nightdiff_overtime'] = '0.00';
		// 		$dtr[$k]['restday_overtime'] = '0.00';
		// 		$dtr[$k]['legal_holiday_overtime'] = '0.00';
		// 		$dtr[$k]['legal_holiday_restday_overtime'] = '0.00';
		// 		$dtr[$k]['special_holiday_overtime'] = '0.00';
		// 		$dtr[$k]['special_holiday_restday_overtime'] = '0.00';
		// 		$dtr[$k]['regular_excess_overtime'] = '0.00';
		// 		$dtr[$k]['restday_excess_overtime'] = '0.00';
		// 		$dtr[$k]['legal_excess_overtime'] = '0.00';
		// 		$dtr[$k]['legal_excess_restday_overtime'] = '0.00';
		// 		$dtr[$k]['special_excess_overtime'] = '0.00';
		// 		$dtr[$k]['special_excess_restday_overtime'] = '0.00';

		// 		// Get time logs
		// 		$time_in 		= isset($attendance_working_day['time_in']) 	? $attendance_working_day['time_in'] 		: null;
		// 		$time_out 	= isset($attendance_working_day['time_out']) 	? $attendance_working_day['time_out'] 	: null;
		// 		$break_out 	= isset($attendance_working_day['break_out']) ? $attendance_working_day['break_out'] 	: null;
		// 		$break_in 	= isset($attendance_working_day['break_in']) 	? $attendance_working_day['break_in'] 	: null;

		// 		// Get grace period and night diff hours
		// 		if(sizeof($attendance_settings) > 0) {
		// 			$night_shift_start = date("Y-m-d H:i:s", strtotime($attendance_settings['night_differential_hours_between_from'] . ' ' . $working_day));
		// 			$night_shift_end = date("Y-m-d H:i:s", strtotime(($attendance_settings['night_differential_hours_between_to'] < $attendance_settings['night_differential_hours_between_from'] ) ? $attendance_settings['night_differential_hours_between_to']  . ' ' . date('Y-m-d', strtotime($working_day . '+1 day')) : $attendance_settings['night_differential_hours_between_to'] . ' ' . $working_day));
		// 		}

		// 		// Check if holiday
		// 		$holiday_details = array();
		// 		if(sizeof($this->checkIfHoliday($working_day)) > 0){
		// 			$holiday_details = $this->checkIfHoliday($working_day);
		// 		}

		// 		// Check if leave
		// 		$leave_details = array();
		// 		if(sizeof($this->checkForApprovedLeave($working_day, $employee_id)) > 0) {
		// 			$leave_details = $this->checkForApprovedLeave($working_day, $employee_id);
		// 		}

		// 		$employee_schedule = $this->getEmployeeSchedule($shift_id, $working_day);

		// 		$legal_holiday_overtime = 0; $legal_excess_overtime = 0; $special_holiday_overtime = 0; $special_excess_overtime = 0; $overtime_nightdiff_hours = 0; $restday_overtime = 0; $restday_excess_overtime = 0; $regular_hours = 0; $regular_nightdiff_hours = 0; $regular_overtime = 0; $legal_holiday_restday_overtime = 0; $legal_excess_restday_overtime = 0; $special_holiday_restday_overtime = 0; $special_excess_restday_overtime = 0;

		// 		// Employee attendance with regular schedule
		// 		if(isset($employee_schedule) && sizeof($employee_schedule) > 0 && $employee_schedule != null) {

		// 			$official_time_in = $working_day . ' ' . $employee_schedule['start_time'];
		// 			$official_time_out = ($employee_schedule['end_time'] < $employee_schedule['start_time']) ? date('Y-m-d', strtotime($working_day . '+1 day')) . ' ' . $employee_schedule['end_time'] : $working_day . ' ' . $employee_schedule['end_time'];

		// 			// Grace period
		// 			$grace_period = date("Y-m-d H:i:s", strtotime($official_time_in . "+" . round($attendance_settings['grace_period'], 0) . " minutes"));

		// 			// official break times not used yet
		// 			$official_break_out = $employee_schedule['break_start_time'];
		// 			$official_break_in = $employee_schedule['break_end_time'];

		// 			// Check official schedule
		// 			if(isset($official_time_in) && isset($official_time_out) && $official_time_in != null && $official_time_out != null) {
		// 				if($time_in != null && $time_out != null) {
		// 					// Check early in
		// 					if($time_in < $official_time_in)
		// 						$time_in = $official_time_in;
		// 					// Check if under grace period
		// 					elseif($time_in >= $official_time_in && $time_in <= $grace_period)
		// 						$time_in = $official_time_in;
		// 				} else {
		// 					$absent_hours = sizeof($leave_details) > 0 ? "0.00" : sizeof($holiday_details) > 0 ? "0.00" : "8.00";
		// 				}
		// 			}

		// 			// Get decimal hours for each column
		// 			if(isset($time_in) && $time_in != null && isset($time_out) && $time_out != null) {

		// 				// Check attendance
		// 				$present_day = '1.00'; $absent_hours = '0.00';

		// 				// Total
		// 				$total_hours = $time_out < $official_time_out ? $this->getTimeDifference($time_in, $time_out) : $this->getTimeDifference($time_in, $official_time_out);

		// 				// Break
		// 				$total_break = "0.00";
		// 				if(($break_out != null && $break_in != null)){
		// 					$break_result = $this->getTimeDifference($break_in, $break_out);
		// 					if($break_result > 1)
		// 						$total_break = $break_result - 1;
		// 				}

		// 				// Break
		// 				$break_result = (isset($break_result) && $total_hours < 5) ? $break_result : 1;

		// 				// Working Hours
		// 				$working_hours = $total_hours - $break_result;

		// 				// Late Hours
		// 				$late_hours = ($official_time_in != null && $time_in > $grace_period) ? $this->getTimeDifference($time_in, $official_time_in) : null;


		// 				// Undertime hours
		// 				$undertime_hours = ($official_time_out != null && $time_out < $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;

		// 				if(sizeof($holiday_details) > 0) {
		// 					// $legal_holiday_overtime; $special_holiday_overtime;
		// 					switch ($holiday_details[0]['holiday_type']) {
		// 						case 'Regular':
		// 						case 'Legal':
		// 							if($employee_schedule['is_restday'] == 1){
		// 								// Legal Holiday Hours
		// 								$legal_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 								// Legal Excess Overtime
		// 								$legal_excess_restday_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 							}	else {
		// 								// Legal Holiday Hours
		// 								$legal_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 								// Legal EXcess Overtime
		// 								$legal_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 							}
		// 							break;

		// 						default:
		// 						if($employee_schedule['is_restday'] == 1){
		// 							// Special Holiday Hours
		// 							$special_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 							// Special Excess Overtime
		// 							$special_excess_restday_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 						}	else {
		// 							// Special Holiday Hours
		// 							$special_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 							// Special Excess Overtime
		// 							$special_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 						}
		// 						break;
		// 					}

		// 					// Overtime Night Differential Hours
		// 					$overtime_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

		// 				}
		// 				else {

		// 					if($employee_schedule['is_restday'] == 1) {
		// 						// Regular Hours
		// 						$restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 						// Regular Overtime
		// 						$restday_excess_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 					}
		// 					else {

		// 						// Regular Hours
		// 						$regular_hours = $working_hours;


		// 						// Regular Night Differential Hours
		// 						$regular_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

		// 						// Regular Overtime
		// 						$regular_overtime = ($working_hours > 8 && $time_out > $official_time_out) ? $this->getTimeDifference($official_time_out, $time_out) : null;
		// 					}

		// 				}

		// 			}
		// 			else {
		// 				$absent_hours = isset($employee_schedule['is_restday']) && $employee_schedule['is_restday'] == 1 ? "0.00" : "8.00" ;
		// 				$present_day = sizeof($leave_details) > 0 ? "1.00" : sizeof($holiday_details) > 0 ? "1.00" : "0.00";
		// 				$total_break = null;
		// 				$total_hours = null;
		// 				$regular_hours = sizeof($holiday_details) > 0 ? "8.00" : "0.00";
		// 				$late_hours = null;
		// 				$undertime_hours = null;
		// 				$regular_nightdiff_hours = null;
		// 				$regular_overtime = null;
		// 				$restday_overtime = null;
		// 				$restday_excess_overtime = null;
		// 			}

		// 		}

		// 		// Employee attendance flexible schedule
		// 		else {
		// 			// Get decimal hours for each column
		// 			if(isset($time_in) && $time_in != null && isset($time_out) && $time_out != null) {

		// 				// Check attendance
		// 				$present_day = '1.00'; $absent_hours = '0.00';

		// 				// Total
		// 				$total_hours = $this->getTimeDifference($time_in, $time_out);

		// 				// Break
		// 				$total_break = ($break_out != null && $break_in != null) ? $this->getTimeDifference($time_in, $time_out) : ($total_hours > 4) ? '1.00' : '0.00';

		// 				// Working
		// 				$working_hours = $total_hours - $total_break;

		// 				// Undertime
		// 				$undertime_hours = ($working_hours < 8) ? abs(8 - $working_hours) : null;

		// 				if(sizeof($holiday_details) > 0) {
		// 					// $legal_holiday_overtime; $special_holiday_overtime;
		// 					switch ($holiday_details[0]['holiday_type']) {
		// 						case 'Regular':
		// 						case 'Legal':
		// 							// if($employee_schedule['is_restday'] == 1){
		// 							// 	// Legal Holiday Hours
		// 							// 	$legal_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 							// 	// Legal Excess Overtime
		// 							// 	$legal_excess_restday_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
		// 							// }	else {
		// 								// Legal Holiday Hours
		// 								$legal_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 								// Legal EXcess Overtime
		// 								$legal_excess_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
		// 							// }
		// 							break;

		// 						default:
		// 						// if($employee_schedule['is_restday'] == 1){
		// 						// 	// Special Holiday Hours
		// 						// 	$special_holiday_restday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 						// 	// Special Excess Overtime
		// 						// 	$special_excess_restday_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
		// 						// }	else {
		// 							// Special Holiday Hours
		// 							$special_holiday_overtime = ($working_hours > 8) ? 8 : $working_hours;

		// 							// Special Excess Overtime
		// 							$special_excess_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;
		// 						// }
		// 						break;
		// 					}

		// 					// Overtime Night Differential Hours
		// 					$overtime_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

		// 				}
		// 				else {

		// 						// Regular Hours
		// 						$regular_hours = ($working_hours > 8) ? 8 : $working_hours;

		// 						// Regular Night Differential Hours
		// 						$regular_nightdiff_hours = ($time_out >= $night_shift_start) ? $time_out < $night_shift_end ? $this->getTimeDifference($night_shift_start, $time_out) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

		// 						// Regular Overtime
		// 						$regular_overtime = ($working_hours > 8) ? abs($working_hours - 8) : null;

		// 				}

		// 			}
		// 			else {
		// 				$absent_hours = isset($employee_schedule['is_restday']) && $employee_schedule['is_restday'] == 1 ? "0.00" : "8.00" ;
		// 				$present_day = sizeof($leave_details) > 0 ? "1.00" : sizeof($holiday_details) > 0 ? "1.00" : "0.00";
		// 				$total_break = null;
		// 				$total_hours = null;
		// 				$regular_hours = sizeof($holiday_details) > 0 ? "8.00" : "0.00";
		// 				$late_hours = null;
		// 				$undertime_hours = null;
		// 				$regular_nightdiff_hours = null;
		// 				$regular_overtime = null;
		// 				$restday_overtime = null;
		// 				$restday_excess_overtime = null;
		// 			}
		// 		}

		// 		// $regular_hours = ($regular_hours <= 8) ? $regular_hours - $late_hours : $regular_hours;

		// 		// Finalize Data
		// 		$dtr[$k]['time_in'] = isset($time_in) ? $time_in : null;
		// 		$dtr[$k]['break_out'] = isset($break_out) ? $break_out : null;
		// 		$dtr[$k]['break_in'] = isset($break_in) ? $break_in : null;
		// 		$dtr[$k]['time_out'] = isset($time_out) ? $time_out : null;
		// 		$dtr[$k]['official_time_in'] = isset($official_time_in) ? $official_time_in : null;
		// 		$dtr[$k]['official_time_out'] = isset($official_time_out) ? $official_time_out : null;
		// 		$dtr[$k]['leave_hours'] = sizeof($leave_details) > 0 ? "8.00" : "0.00";
		// 		$dtr[$k]['late_hours'] = (isset($late_hours) && $late_hours != null) ? $late_hours : "0.00";
		// 		$dtr[$k]['undertime_hours'] = (isset($undertime_hours) && $undertime_hours != null) ? $undertime_hours : "0.00";
		// 		// $dtr[$k]['regular_hours'] = (isset($regular_hours) && $regular_hours != null) ? (isset($late_hours) && $late_hours != null) ? $regular_hours - $late_hours : $regular_hours : "0.00";
		// 		$dtr[$k]['regular_hours'] = (isset($regular_hours) && $regular_hours != null) ? $regular_hours : "0.00";
		// 		$dtr[$k]['regular_nightdiff_hours'] = isset($regular_nightdiff_hours) ? $regular_nightdiff_hours : "0.00";
		// 		$dtr[$k]['absent_hours'] = (isset($absent_hours) && $absent_hours != null) ? $absent_hours : "0.00";
		// 		$dtr[$k]['break_deduction'] = (isset($total_break) && $total_break != null) ? $total_break : "0.00";
		// 		$dtr[$k]['present_day'] = (isset($present_day) && $present_day != null) ? $present_day : "0.00";
		// 		$dtr[$k]['regular_overtime'] = (isset($regular_overtime) && $regular_overtime != null) ? $regular_overtime : "0.00";
		// 		$dtr[$k]['nightdiff_overtime'] = (isset($overtime_nightdiff_hours) && $overtime_nightdiff_hours != null) ? $overtime_nightdiff_hours : "0.00";
		// 		$dtr[$k]['restday_overtime'] = (isset($restday_overtime) && $restday_overtime != null) ? $restday_overtime : "0.00";
		// 		$dtr[$k]['legal_holiday_overtime'] = (isset($legal_holiday_overtime) && $legal_holiday_overtime != null) ? $legal_holiday_overtime : "0.00";
		// 		$dtr[$k]['legal_holiday_restday_overtime'] = (isset($legal_holiday_restday_overtime) && $legal_holiday_restday_overtime != null) ? $legal_holiday_restday_overtime : "0.00";
		// 		$dtr[$k]['special_holiday_overtime'] = (isset($special_holiday_overtime) && $special_holiday_overtime != null) ? $special_holiday_overtime : "0.00";
		// 		$dtr[$k]['special_holiday_restday_overtime'] = (isset($special_holiday_restday_overtime) && $special_holiday_restday_overtime != null) ? $special_holiday_restday_overtime : "0.00";
		// 		// $dtr[$k]['regular_excess_overtime'] = '0.00'; // No
		// 		$dtr[$k]['restday_excess_overtime'] = (isset($restday_excess_overtime) && $restday_excess_overtime != null) ? $restday_excess_overtime : "0.00";
		// 		$dtr[$k]['legal_excess_overtime'] = (isset($legal_excess_overtime) && $legal_excess_overtime != null) ? $legal_excess_overtime : "0.00";
		// 		$dtr[$k]['legal_excess_restday_overtime'] = (isset($legal_excess_restday_overtime) && $legal_excess_restday_overtime != null) ? $legal_excess_restday_overtime : "0.00";
		// 		$dtr[$k]['special_excess_overtime'] = (isset($special_excess_overtime) && $special_excess_overtime != null) ? $special_excess_overtime : "0.00";
		// 		$dtr[$k]['special_excess_restday_overtime'] = (isset($special_excess_restday_overtime) && $special_excess_restday_overtime != null) ? $special_excess_restday_overtime : "0.00";


		// 			// var_dump($holiday_details);
		// 	}
		// 	// die();
		// 	$current_period = explode("-", $payroll_period);
		// 	$response['data'] = $dtr;
		// 	$response['total_working_days'] = $this->calculateWorkingDaysInMonth($current_period[0], $current_period[1]);
		// 	return $response;
		// }

		function getEmployeeSchedule($shift_id, $working_day) {
			$week_day = date('l', strtotime($working_day));
			$schedule = array();
			$params = array($shift_id, $week_day);
			$sql = "SELECT * FROM tbltimekeepingemployeeschedules a
							LEFT JOIN tbltimekeepingemployeeschedulesweekly b
							ON a.id = b.shift_code_id WHERE b.shift_code_id = ? AND b.week_day = ? AND b.is_active = 1";
			$query = $this->db->query($sql, $params);
			$schedule = $query->result_array();
			return (sizeof($schedule) > 0) ? $schedule[0] : $schedule;
		}

		function checkGlobalSettings() {
			$settings = array();
			$sql = "SELECT * FROM tblfieldpayrollsetup WHERE is_active = 1  ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			$settings = $query->result_array();
			return (sizeof($settings) > 0) ? $settings[0] : $settings;
		}

		function getAttendanceByWorkingDays($working_day, $employee_number){
			$attendance = array();
			$params = array($employee_number, $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $working_day );
			$sql = "SELECT DISTINCT a.transaction_date, a.employee_number,
			(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) as date FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '0'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = a.transaction_date
						ORDER BY b.transaction_time ASC LIMIT 1) AS time_in,
			IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '1'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = a.transaction_date
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
						ORDER BY b.transaction_time ASC LIMIT 1),
						(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '1'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = ?
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
						ORDER BY b.transaction_time ASC LIMIT 1)) AS time_out,
			IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '2'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = a.transaction_date
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
						ORDER BY b.transaction_time ASC LIMIT 1),
						(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '2'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = ?
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
						ORDER BY b.transaction_time ASC LIMIT 1)) AS break_out,
			IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '3'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = a.transaction_date
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
						ORDER BY b.transaction_time ASC LIMIT 1),
						(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
						WHERE b.transaction_type = '3'
						AND b.employee_number = ?
						AND b.is_active = '1'
						AND b.transaction_date = ?
						AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
						ORDER BY b.transaction_time ASC LIMIT 1)) AS break_in
			FROM tbltimekeepingdailytimerecord a
			WHERE a.employee_number = ? AND transaction_date = ? ";
			$query = $this->db->query($sql,$params);
			$attendance = $query->result_array();
			return (sizeof($attendance) > 0) ? $attendance[0] : $attendance;
		}

		function checkIfHoliday($working_day){
			$params = array($working_day);
			$sql = "SELECT * FROM tblfieldholidays WHERE date = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$holiday = $query->result_array();
			return $holiday;
		}

		function checkForApprovedLeave($working_day, $employee_id){
			$params = array($working_day, $employee_id);
			$sql = "SELECT *
			FROM tblleavemanagement a
			LEFT JOIN tblleavemanagementdaysleave b ON a.id = b.id WHERE b.days_of_leave = ? AND a.employee_id = ? AND a.is_active = 1";
			$query = $this->db->query($sql, $params);
			$leave = $query->result_array();
			return $leave;
		}

	function getAllEmployeeDetails($pay_basis) {
		$params = array($pay_basis);
		$sql = "SELECT * FROM tblemployees WHERE employment_status = 'Active' AND pay_basis = ? AND is_active = 1";
		$query = $this->db->query($sql, $pay_basis);
		$employee = $query->result_array();
		return $employee;
	}

	function getWorkingDays ($payroll_period) {
		$workdays = array();
		$payroll_date = explode("-", $payroll_period);
		$no_of_days = date('t',strtotime($payroll_period));
		for ($day=1; $day <= $no_of_days; $day++) {
			$workdays[] = $payroll_date[0] . '-' . $payroll_date[1] . '-' . (($day > 9) ? $day : '0'. $day);
		}
		return $workdays;
	}

	function getTimeDifference($start, $end) {
		$start  = strtotime($start);
		$end = strtotime($end);
		$diff = ($end - $start);
		$minutes = ($diff / 60) / 60;
		return number_format((float)abs($minutes), 2, '.', '');
	}

	function calculateWorkingDaysInMonth($year, $month) {
		$startdate = strtotime($year . '-' . $month . '-01');
		$enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
		$currentdate = $startdate;
		$return = intval((date('t',$startdate)), 10);
		while ($currentdate <= $enddate) {
			if ((date('D',$currentdate) == 'Sat') || (date('D',$currentdate) == 'Sun')) {
				$return = $return - 1;
			}
			$currentdate = strtotime('+1 day', $currentdate);
		}
		return $return;
	}

}
?>