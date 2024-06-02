<?php
	class DailyTimeRecordSummaryCollection extends Helper {
    var $select_column = null;
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblemployees";
    var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.contract','');

		public function getColumns(){
    	$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}


		public function getEmployeeList($pay_basis, $location_id) {
			$sql = "SELECT a.id, a.employee_number, a.employee_id_number, a.first_name, a.middle_name, a.last_name, a.shift_id, c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name FROM tblemployees a
			LEFT JOIN tblfieldagencies c ON a.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON a.office_id = d.id
			LEFT JOIN tblfielddepartments e ON a.division_id = e.id
			LEFT JOIN tblfieldlocations f ON a.location_id = f.id
			WHERE a.pay_basis = '". $pay_basis ."' AND a.location_id = '" . $location_id . "'";
			// $sql .= isset($pay_basis) ? " WHERE a.pay_basis='" . $pay_basis . "'" : "";
			// $sql .= isset($division) ? " WHERE a.division_id='" . $division . "'" : "";

			$query = $this->db->query($sql);
			$rows = $query->result_array();
			// var_dump($sql);die();
			return $rows;
		}

    public function getEmployeeColumns(){
      $sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		function make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id) {
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

		    if(isset($_POST["search"]["value"])) {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_POST["search"]["value"]);
		     	}
		      $this->db->group_end();
		    }

		    if(isset($employment_status) && $employment_status != null)
		    	$this->db->where('tblemployees.employment_status != "Active"');
		    else
		    	$this->db->where('tblemployees.employment_status = "Active"');
		    if(isset($employee_id) && $employee_id != null)
					$this->db->where('tblemployees.id="'.$employee_id.'"');

		    if((isset($pay_basis) && $pay_basis != null))
		    	$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
		    else {
		    	$this->db->where('1=0');
		    }
		    if(isset($_POST["order"])) {
		      $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else {
		      $this->db->order_by($this->table.'.id', 'DESC');
		    }
		}

		function make_query_employee() {
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

				if(isset($_POST["search"]["value"])) {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);
		     		else
		     			$this->db->or_like($value, $_POST["search"]["value"]);
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
		    if(isset($_POST["order"])) {
		      $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else {
		      $this->db->order_by('id', 'DESC');
		    }
		}

		function make_query_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id) {
		    $this->db->select(
		    	$this->table.'.*'
		    );
		    $this->db->from($this->table);
		    if(isset($_POST["search"]["value"])) {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_POST["search"]["value"]);
		     	}
		      $this->db->group_end();
		    }
		    if(isset($employee_number)) {
					$this->db->where($this->table.'.employee_number="'. $employee_number .'"');
					$this->db->where($this->table.'.transaction_date>="'. $from .'"');
					$this->db->where($this->table.'.transaction_date<="'. $to .'"');
		    }
		    else {
		    	$this->db->where('1=0');
		    }
		    if(isset($_POST["order"])){
		      $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else {
					$this->db->order_by('transaction_date asc,transaction_time asc');
		    }
		}

		function make_datatables() {
			$this->make_query_employee();
			if($_POST["length"] != -1)
			{
					$this->db->limit($_POST['length'], $_POST['start']);
			}
			$query = $this->db->get();
			return $query->result();
		}

		function make_datatables_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id){
			$this->make_query_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id);
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

		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

		function fetchPayroll($employment_status,$employee_id,$pay_basis,$payroll_period_id){
			$this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id);
			$query = $this->db->get();
			return $query->result_array();
		}

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

		function make_datatables_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){
			$result = $this->make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id);
			return $result;
		}

		function make_datatables_summary_all($pay_basis, $payroll_period, $payroll_period_id){
			$summary = array();
			$all_employees = $this->getAllEmployeeDetails($pay_basis);
			foreach ($all_employees as $k => $employee) {
				$summary['data'][$employee['id']] = $this->make_query_summary($employee['id'], $this->Helper->decrypt($employee['employee_number'], $employee['id']), $payroll_period, $payroll_period_id, $employee['shift_id']);
			}
			$dtr['payroll_period'] = $payroll_period;
			return $summary;
		}


		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id) {
			$dtr = array();
			$payroll_date = explode("-", $payroll_period);
			$no_of_days = date('t',strtotime($payroll_period));
			$payroll_dates = $this->DailyTimeRecordSummaryCollection->getPayrollPeriodById($payroll_period_id);
			if(isset($payroll_dates) && sizeof($payroll_dates) > 0){
				$start = explode("-", $payroll_dates[0]['start_date']);
				$end = explode("-", $payroll_dates[0]['end_date']);
			}
			for ($day = 1; $day <= 31; $day++) {
				$current_day = $payroll_date[0] . '-' . $payroll_date[1] . '-' . ($day > 9 ? $day : '0'. $day);
				if($day >= $start[2] && $day <= $end[2]) {
					$dtr['records'][$current_day] = $this->getAttendanceByWorkingDays($current_day,$employee_number);
				} else {
					$dtr['records'][$current_day] = null;
				}
			}
			$dtr['employee'] = $this->getEmployeeById($employee_id);
			$dtr['details'] = $this->getOfficeById($dtr['employee'][0]['office_id']);
			$dtr['payroll_period'][0] = $payroll_period;
			$dtr['payroll_period'][1] = isset($payroll_dates[0]['start_date']) ? $payroll_dates[0]['start_date'] : null;
			$dtr['payroll_period'][2] = isset($payroll_dates[0]['end_date']) ? $payroll_dates[0]['end_date'] : null;
			return $dtr;

	}

	function getFlagCeremonyDay($day) {
		$params = array($day);
		$sql = "SELECT IF(EXISTS(SELECT 1 FROM tbltimekeepingflagceremonyschedules WHERE YEAR(flagdateceremony) = YEAR('".$day."') AND MONTH(flagdateceremony) = MONTH('".$day."')) = 1, (SELECT flagdateceremony FROM tbltimekeepingflagceremonyschedules WHERE YEAR(flagdateceremony) = YEAR('".$day."') AND MONTH(flagdateceremony) = MONTH('".$day."')),(SELECT ADDDATE( '".$day."' , MOD((9-DAYOFWEEK('".$day."')),7)))) AS flagday";
		$query = $this->db->query($sql, $params);
		$flagday = $query->result_array();
		return $flagday;
	}

	function getHolidays($day) {
		$sql = "SELECT * FROM tblfieldholidays WHERE YEAR(date) = YEAR('".$day."') AND MONTH(date) = MONTH('".$day."') AND is_active = 1";
		$query = $this->db->query($sql);
		$holidays = $query->result_array();
		return $holidays;
	}

	function getEmployeeById($employee_id) {
		$params = array($employee_id);
		$sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
		$query = $this->db->query($sql, $params);
		$employee = $query->result_array();
		return $employee;
	}

	function getOfficeById($office_id) {
		$params = array($office_id);
		$sql = "SELECT * FROM tblfieldoffices WHERE id = ? AND is_active = 1";
		$query = $this->db->query($sql, $params);
		$dept = $query->result_array();
		return $dept;
	}

	function getDepartmentById($division_id) {
		$params = array($division_id);
		$sql = "SELECT * FROM tblfielddepartments WHERE id = ? AND is_active = 1";
		$query = $this->db->query($sql, $params);
		$dept = $query->result_array();
		return $dept;
	}

	function getAllEmployeeDetails($pay_basis) {
		$params = array($pay_basis);
		$sql = "SELECT * FROM tblemployees WHERE employment_status = 'Active' AND pay_basis = ? AND is_active = 1";
		$query = $this->db->query($sql, $pay_basis);
		$employee = $query->result_array();
		return $employee;
	}

	function getAttendanceByWorkingDays($working_day,$employee_number){
		$logs = array(
			"actual" => array(
				"time_in" 	=> null,
				"break_out" => null,
				"break_in" 	=> null,
				"time_out" 	=> null,
				"overtime_in" 	=> null,
				"overtime_out" 	=> null,
				"no_type"	=> null
			),
			"adjustment" => array(
				"time_in" 	=> null,
				"break_out" => null,
				"break_in" 	=> null,
				"time_out" 	=> null,
				"overtime_in" 	=> null,
				"overtime_out" 	=> null,
				"no_type"	=> null,
				"remarks"	=> null
			)
		);
		$actual_attendance 	= $this->getActualLogs($working_day, $employee_number);
		$adjustments 		= $this->getAttendanceWithAdjustments($working_day, $employee_number);

		if(sizeof($actual_attendance) > 0) {
			$logs["actual"] = $actual_attendance;
		}

		if(sizeof($adjustments) > 0) {
			$logs["adjustment"] = $adjustments;
		}
		return $logs;
	}

	function getAttendanceWithAdjustments($working_day, $employee_number) {
		$employee_number = (int) $employee_number;
		$params = array($employee_number, $working_day);
		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 0 AND is_active = 1
			ORDER BY transaction_date";
		$query = $this->db->query($sql,$params);
		$time_in = $query->result_array();

		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 2 AND is_active = 1
			ORDER BY transaction_date";
		$query = $this->db->query($sql,$params);
		$break_out = $query->result_array();

		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 3 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$break_in = $query->result_array();

		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 1 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$time_out = $query->result_array();

		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 4 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$overtime_in = $query->result_array();

		$sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 5 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$overtime_out = $query->result_array();


		if(isset($time_in[0]['remarks']) 	&& $time_in[0]['remarks'] 		!= null && $time_in[0]['remarks'] != "")
			$remarks = $time_in[0]['remarks'];
		else if(isset($break_out[0]['remarks']) && $break_out[0]['remarks'] 	!= null && $break_out[0]['remarks'] != "")
			$remarks = $break_out[0]['remarks'];
		else if(isset($break_in[0]['remarks']) 	&& $break_in[0]['remarks'] 	!= null && $break_in[0]['remarks'] != "")
			$remarks = $break_in[0]['remarks'];
		else if(isset($time_out[0]['remarks']) 	&& $time_out[0]['remarks'] 	!= null && $time_out[0]['remarks'] != "")
			$remarks = $time_out[0]['remarks'];
		else
			$remarks = null;


		if(sizeof($time_in) > 0 || sizeof($break_out) > 0 || sizeof($break_in) > 0 || sizeof($time_out) > 0) {
			// var_dump($time_in); die();
			return array(
				"time_in" 	=> $time_in, // AM Arrival
				"break_out" => $break_out, // AM Departure
				"break_in" 	=> $break_in, // PM Arrival
				"time_out" 	=> $time_out, // AM Departure
				"overtime_in" 	=> $overtime_in, // OT Arrival
				"overtime_out" 	=> $overtime_out, // OT Departure
				"no_type"	=> array(), // Else
				"remarks"	=> $remarks
			);
		} else return array();
	}

	function getActualLogs($working_day, $employee_number) {
		$employee_number = (int) $employee_number;
		$params = array($employee_number, $working_day);
		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 0 AND is_active = 1
			ORDER BY transaction_date";
		$query = $this->db->query($sql,$params);
		$time_in = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 2 AND is_active = 1
			ORDER BY transaction_date";
		$query = $this->db->query($sql,$params);
		$break_out = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 3 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$break_in = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 1 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$time_out = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 4 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$overtime_in = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type = 5 AND is_active = 1
			ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$overtime_out = $query->result_array();

		$sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
			WHERE employee_number = ?
			AND transaction_date = ? AND transaction_type NOT IN(0,1,2,3) 
			AND is_active = 1 ORDER BY transaction_date";
			$query = $this->db->query($sql,$params);
		$no_type = $query->result_array();
		// var_dump($no_type);die();
		if(sizeof($time_in) > 0 || sizeof($break_out) > 0 || sizeof($break_in) > 0 || sizeof($time_out) > 0) {
			return array(
				"time_in" 					=> $time_in, // AM Arrival
				"break_out" 				=> $break_out, // AM Departure
				"break_in" 					=> $break_in, // PM Arrival
				"time_out" 					=> $time_out, // AM Departure
				"overtime_in" 				=> $overtime_in, // OT Arrival
				"overtime_out" 				=> $overtime_out, // OT Departure
				"no_type"					=> $no_type // Else
			);
		} else return array();
	}

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

	function getEmployeeRegularSchedule($shift_id) {
		// $schedule = array();
		$params = array($shift_id);
		$sql = "SELECT *, '1' AS shift_type FROM tbltimekeepingemployeeschedulesweekly WHERE shift_code_id = ?";
		$query = $this->db->query($sql, $params);
		// $schedule = $query->result_array();
		return $query->result_array();
	}

	function getEmployeeShiftHistory($employee_number, $day) {
		// $schedule = array();
		$sql = "(SELECT employee_id, shift_type, shift_id, DAY(STR_TO_DATE(previous_date_effectivity,'%m/%d/%Y')) AS shift_date_effectivity FROM tblemployeesshifthistory WHERE employee_id = '".$employee_number."' AND MONTH(STR_TO_DATE(previous_date_effectivity,'%m/%d/%Y')) = MONTH('".$day."')) UNION ALL (SELECT id, regular_shift, shift_id, DAY(STR_TO_DATE(shift_date_effectivity,'%m/%d/%Y')) FROM tblemployees WHERE id = '".$employee_number."' AND MONTH(STR_TO_DATE(shift_date_effectivity,'%m/%d/%Y')) = MONTH('".$day."'))";
		$query = $this->db->query($sql);
		// $schedule = $query->result_array();
		// var_export($this->db->last_query());die();
		return $query->result_array();
	}

	function getEmployeeFlexibleSchedule($shift_id) {
		// $schedule = array();
		$params = array($shift_id);
		$sql = "SELECT *, '0' AS shift_type FROM tbltimekeepingemployeeflexibleschedulesweekly WHERE shift_code_id = ?";
		$query = $this->db->query($sql, $params);
		// $schedule = $query->result_array();
		return $query->result_array();
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
		$sql = "SELECT a.id, b.id
		FROM tblleavemanagement a
		LEFT JOIN tblleavemanagementdaysleave b ON a.id = b.id WHERE b.days_of_leave = ? AND a.employee_id = ? AND a.is_active = 1";
		$query = $this->db->query($sql, $params);
		$leave = $query->result_array();
		return $leave;
	}

	function checkGlobalSettings() {
		$settings = array();
		$sql = "SELECT * FROM tblfieldpayrollsetup WHERE is_active = 1  ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		$settings = $query->result_array();
		return (sizeof($settings) > 0) ? $settings[0] : $settings;
	}

	function getLocation($location_id) {
		$settings = array();
		$sql = "SELECT * FROM tblfieldlocations WHERE id = ? AND is_active = 1 ";
		$query = $this->db->query($sql, $location_id);
		$settings = $query->result_array();
		return (sizeof($settings) > 0) ? $settings[0] : $settings;
	}

	function getWorkingDays($payroll_period) {
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

	function checkAnte($time, $type){
		$ante = $time != null && $time != '' ? date('a', strtotime($time)) : '';
		// echo $ante;
		if($type != null && $type != '') {
			switch ($type) {
				case 'am':
					return $ante == 'am' ? true : false;
					break;
				case 'pm':
					return $ante == 'pm' ? true : false;
					break;
				default:
					return false;
					break;
			}
		} else {
			return false;
		}
	}

	}
?>