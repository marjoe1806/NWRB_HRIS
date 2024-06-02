<?php
    class DailyTimeRecordMaintenanceCollection extends Helper {
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
    var $order_column = array(null,'CAST(emp_number as INT)','first_name','tblfielddepartments.department_name','office_name','position_name');
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
                'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
                DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
                DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
                DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.*,
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

            if(isset($employment_status) && $employment_status != null)
                $this->db->where('tblemployees.employment_status != "Active"');
            else
                $this->db->where('tblemployees.employment_status = "Active"');
            if(isset($employee_id) && $employee_id != null)
                    $this->db->where('tblemployees.id="'.$employee_id.'"');

            if((isset($pay_basis) && $pay_basis != null))
                $this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
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
                'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
                DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
                DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
                DECRYPTER(tblemployees.employee_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.*,
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

            if(isset($_POST["search"]["value"])){  
				$this->db->group_start();
				foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
						$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);
					} else{
						$this->db->or_like($value, $_POST["search"]["value"]);  
					}
				}
				$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
					,$_POST["search"]["value"]);
				$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
					,$_POST["search"]["value"]);
				$this->db->group_end(); 
			}
            
			if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null){
				if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)
					$this->db->where($this->table.'.employment_status != "Active"');
				else
						$this->db->where($this->table.'.employment_status = "Active"');

					if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null) {
						// var_dump($_GET['PayBasis']); die();
						$this->db->where($this->table.'.pay_basis = "'. $_GET['PayBasis'] .'"');
					} else {
						$this->db->where('1=0');
					}
					if(isset($_GET['Division']) && $_GET['Division'] != null) {
					$this->db->where($this->table.'.division_id = "'. $_GET['Division'] .'"');
				} else {
					$this->db->where('1=0');
				}
                $this->db->group_by('tblemployees.employee_number');
                if(isset($_POST["order"])){  
                    $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
                }else{  
                    $this->db->order_by("DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id)");
                }
			}

        }

        function make_query_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id) {
            $this->db->select(
                $this->table.'.*'
            );
            $this->db->from($this->table);
            if(isset($_POST["search"]["value"]))
            {
                $this->db->group_start();
                foreach ($this->select_column as $key => $value) {
                    $this->db->or_like($value, $_POST["search"]["value"]);
                }
                $this->db->group_end();
            }
            if(isset($employee_number)){
                    $this->db->where($this->table.'.employee_number="'. $employee_number .'"');
                    $this->db->where($this->table.'.transaction_date>="'. $from .'"');
                    $this->db->where($this->table.'.transaction_date<="'. $to .'"');
            }
            else{
                $this->db->where('1=0');
            }
            if(isset($_POST["order"])){
              $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }
            else {
                    $this->db->order_by('transaction_date asc,transaction_time asc');
            }
        }

        function getAccomplishmentReports($employee_id, $from , $to) {
            $params = array($employee_id, $from , $to);
            $sql = "SELECT * FROM tbltimekeepingaccomplishmentreports WHERE employee_id = ? AND accomplishment_date >= ? AND accomplishment_date <= ? AND is_active = 1";
            $query = $this->db->query($sql, $params);
            $accomplishment_report = $query->result_array();
            return $accomplishment_report;
        }

        function getDocumentTypeById($document_type_id) {
            $params = array($document_type_id);
            $sql = "SELECT * FROM tblfielddocumenttypes WHERE type_id = ? AND is_active = 1";
            $query = $this->db->query($sql, $params);
            $document_type = $query->result_array();
            return $document_type;
        }

        function make_datatables(){
            $this->make_query_employee();
            if($_POST["length"] != -1) {
                $this->db->limit($_POST['length'], $_POST['start']);
            }
            $query = $this->db->get();
            return $query->result();
        }

        function make_datatables_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id){
            $this->make_query_dtr($id, $employee_number, $payroll_period_id, $payroll_period, $shift_id);
            if($_POST["length"] != -1) {
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
                $summary['data'][$employee['id']] = $this->make_query_summary($employee['id'], $this->Helper->decrypt($employee['employee_number'], $employee['id']), $payroll_period, $payroll_period_id, $employee['shift_id']);
            }
            $dtr['payroll_period'] = $payroll_period;
            return $summary;
        }

    function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id) {
        $dtr = array();
        $get_record = array();
        $attendance = array();
        $no_of_days = date('t',strtotime($payroll_period));
        $payroll_date = explode("-", $payroll_period);
        for ($day = 1; $day <= 31; $day++) {
            $current_day = $payroll_date[0] . '-' . $payroll_date[1] . '-' . ($day > 9 ? $day : '0'. $day);
            $dtr['records'][$current_day] = $this->getAttendanceByWorkingDays($current_day, $employee_number);
        }
        $dtr['employee'] = $this->getEmployeeById($employee_id);
        $dtr['payroll_period'] = $payroll_period;
        return $dtr;
    }

    function getDailyTimeRecordDataAdjustments($date, $employee_number) {
        $attendance = array();
        $params = array($date, $employee_number);
        $sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE transaction_date = ? AND CAST(employee_number as int) = CAST(? as int)";
        $query = $this->db->query($sql, $params);
        $attendance = $query->result_array();
        return $attendance;
    }

    function getDailyTimeRecordData($date, $employee_number) {
        $attendance = array();
        $adjustments = $this->getDailyTimeRecordDataAdjustments($date, $employee_number);
        $attendance['adjustments'] = $adjustments;
        $params = array($date, $employee_number);
        $sql = "SELECT * FROM tbltimekeepingdailytimerecord  WHERE transaction_date = ? AND CAST(employee_number as int) = CAST(? as int)";
        $query = $this->db->query($sql, $params);
        $attendance['actual_data'] = $query->result_array();
        return $attendance;
    }

    function getEmployeeById($employee_id) {
        $params = array($employee_id);
        $sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
        $query = $this->db->query($sql, $params);
        $employee = $query->result_array();
        return $employee;
    }

    function getDepartmentById($division_id) {
        $params = array($division_id);
        $sql = "SELECT * FROM tblfielddepartments WHERE id = ? AND is_active = 1";
        $query = $this->db->query($sql, $params);
        $dept = $query->result_array();
        // var_dump($dept);die();
        return $dept;
    }

    function getEmployeeSchedule($shift_id) {
        $params = array($shift_id);
        $sql = "SELECT * FROM tbltimekeepingemployeeschedules WHERE id = ? AND is_active = 1";
        $query = $this->db->query($sql, $params);
        $schedule = $query->result_array();
        return $schedule[0];
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
                "time_in"   => null,
                "break_out" => null,
                "break_in"  => null,
                "time_out"  => null,
                "overtime_in"   => null,
                "overtime_out"  => null,
                "no_type"   => null
            ),
            "adjustment" => array(
                "time_in"   => null,
                "break_out" => null,
                "break_in"  => null,
                "time_out"  => null,
                "overtime_in"   => null,
                "overtime_out"  => null,
                "no_type"   => null,
                "remarks"   => null
            ),
            "dtr" => array(
                "time_in"   => null,
                "break_out" => null,
                "break_in"  => null,
                "time_out"  => null,
                "overtime_in"   => null,
                "overtime_out"  => null,
                "no_type"   => null,
                "remarks"   => null
            ),
            "dtr_adjustments" => array(
                "time_in"   => null,
                "break_out" => null,
                "break_in"  => null,
                "time_out"  => null,
                "overtime_in"   => null,
                "overtime_out"  => null,
                "no_type"   => null,
                "remarks"   => null
            )
        );
        $actual_attendance  = $this->getActualLogs($working_day, $employee_number);
        $adjustments        = $this->getAttendanceWithAdjustments($working_day, $employee_number);
        $dtr                = $this->getDTR($working_day, $employee_number);
        $dtr_adjustments    = $this->getDTRAdjustments($working_day, $employee_number);

        if(sizeof($actual_attendance) > 0) {
            $logs["actual"] = $actual_attendance;
        }

        if(sizeof($adjustments) > 0) {
            $logs["adjustment"] = $adjustments;
        }

        if(sizeof($dtr) > 0) {
            $logs["dtr"] = $dtr;
        }

        if(sizeof($dtr_adjustments) > 0) {
            $logs["dtr_adjustments"] = $dtr_adjustments;
        }
        return $logs;
    }

    function getDTR($working_day, $employee_number){
        $params = array((int)$employee_number, $working_day);
        $sql = "SELECT * FROM tbldtr WHERE CAST(scanning_no AS INT) = ? AND DATE(transaction_date) = DATE(?)";
        $query = $this->db->query($sql,$params);
        $dtr = $query->row_array();
        if($dtr){
            return array(
                "time_in"       => [array("transaction_time" => $dtr["check_in"] == "00:00:00" ? null : $dtr["check_in"])], // AM Arrival
                "break_out"     => [array("transaction_time" => $dtr["break_out"] == "00:00:00" ? null : $dtr["break_out"])], // AM Departure
                "break_in"      => [array("transaction_time" => $dtr["break_in"] == "00:00:00" ? null : $dtr["break_in"])], // PM Arrival
                "time_out"      => [array("transaction_time" => $dtr["check_out"] == "00:00:00" ? null : $dtr["check_out"])], // AM Departure
                "overtime_in"   => [array("transaction_time" => $dtr["ot_in"] == "00:00:00" ? null : $dtr["ot_in"])], // OT Arrival
                "overtime_out"  => [array("transaction_time" => $dtr["ot_out"] == "00:00:00" ? null : $dtr["ot_out"])], // OT Departure
                "no_type"   => array(), // Else
                "remarks"   => $dtr["remarks"]
            );
        }
        return array();
    }

    function getDTRAdjustments($working_day, $employee_number){
        $params = array($employee_number, $working_day);
        $sql = "SELECT * FROM tbldtr WHERE CAST(scanning_no AS INT) = CAST(? AS INT) AND DATE(transaction_date) = DATE(?)";
        $query = $this->db->query($sql,$params);
        $dtr = $query->row_array();
        if($dtr){
            return array(
                "time_in"       => [array("transaction_time" => $dtr["adjustment_check_in"] == "00:00:00" ? null : $dtr["adjustment_check_in"])], // AM Arrival
                "break_out"     => [array("transaction_time" => $dtr["adjustment_break_out"] == "00:00:00" ? null : $dtr["adjustment_break_out"])], // AM Departure
                "break_in"      => [array("transaction_time" => $dtr["adjustment_break_in"] == "00:00:00" ? null : $dtr["adjustment_break_in"])], // PM Arrival
                "time_out"      => [array("transaction_time" => $dtr["adjustment_check_out"] == "00:00:00" ? null : $dtr["adjustment_check_out"])], // AM Departure
                "overtime_in"   => [array("transaction_time" => $dtr["adjustment_ot_in"] == "00:00:00" ? null : $dtr["adjustment_ot_in"])], // OT Arrival
                "overtime_out"  => [array("transaction_time" => $dtr["adjustment_ot_out"] == "00:00:00" ? null : $dtr["adjustment_ot_out"])], // OT Departure
                "no_type"   => array(), // Else
                "remarks"   => $dtr["adjustment_remarks"]
            );
        }
        return array();
    }

    function getAttendanceWithAdjustments($working_day, $employee_number) {
        $employee_number = $employee_number;
        $params = array($employee_number, $working_day);
        $sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 0
            ORDER BY transaction_date";
        $query = $this->db->query($sql,$params);
        $time_in = $query->result_array();

        $sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 2
            ORDER BY transaction_date";
        $query = $this->db->query($sql,$params);
        $break_out = $query->result_array();

        $sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 3
            ORDER BY transaction_date";
            $query = $this->db->query($sql,$params);
        $break_in = $query->result_array();

        $sql = "SELECT transaction_time, remarks FROM tbltimekeepingdailytimerecordadjustments
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 1
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

        if(isset($time_in[0]['remarks'])    && $time_in[0]['remarks']       != null && $time_in[0]['remarks'] != "")
            $remarks = $time_in[0]['remarks'];
        else if(isset($break_out[0]['remarks']) && $break_out[0]['remarks']     != null && $break_out[0]['remarks'] != "")
            $remarks = $break_out[0]['remarks'];
        else if(isset($break_in[0]['remarks'])  && $break_in[0]['remarks']  != null && $break_in[0]['remarks'] != "")
            $remarks = $break_in[0]['remarks'];
        else if(isset($time_out[0]['remarks'])  && $time_out[0]['remarks']  != null && $time_out[0]['remarks'] != "")
            $remarks = $time_out[0]['remarks'];
        else
            $remarks = null;

        if(sizeof($time_in) > 0 || sizeof($break_out) > 0 || sizeof($break_in) > 0 || sizeof($time_out) > 0) {
            return array(
                "time_in"   => $time_in, // AM Arrival
                "break_out" => $break_out, // AM Departure
                "break_in"  => $break_in, // PM Arrival
                "time_out"  => $time_out, // AM Departure
                "overtime_in"   => $overtime_in, // OT Arrival
                "overtime_out"  => $overtime_out, // OT Departure
                "no_type"   => array(), // Else
                "remarks"   => $remarks
            );
        } else return array();
    }

    function getActualLogs($working_day, $employee_number) {
        $employee_number = (int) $employee_number;
        $params = array($employee_number, $working_day);
        $sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 0
            ORDER BY transaction_date";
        $query = $this->db->query($sql,$params);
        $time_in = $query->result_array();

        $sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 2
            ORDER BY transaction_date";
        $query = $this->db->query($sql,$params);
        $break_out = $query->result_array();

        $sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 3
            ORDER BY transaction_date";
            $query = $this->db->query($sql,$params);
        $break_in = $query->result_array();

        $sql = "SELECT transaction_time FROM tbltimekeepingdailytimerecord
            WHERE employee_number = ?
            AND transaction_date = ? AND transaction_type = 1
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
            ORDER BY transaction_date";
            $query = $this->db->query($sql,$params);
        $no_type = $query->result_array();
        if(sizeof($time_in) > 0 || sizeof($break_out) > 0 || sizeof($break_in) > 0 || sizeof($time_out) > 0) {
            return array(
                "time_in"                   => $time_in, // AM Arrival
                "break_out"                 => $break_out, // AM Departure
                "break_in"                  => $break_in, // PM Arrival
                "time_out"                  => $time_out, // AM Departure
                "overtime_in"               => $overtime_in, // OT Arrival
                "overtime_out"              => $overtime_out, // OT Departure
                "no_type"                   => $no_type // Else
            );
        } else return array();
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

    function getWorkingDays ($payroll_period) {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = date('n', strtotime($payroll_period));
        $year = date('Y', strtotime($payroll_period));
        $day_count = cal_days_in_month($type, $month, $year);
        for ($i = 1; $i <= $day_count; $i++) {
            $date = $year.'/'.$month.'/'.$i;
            $get_name = date('l', strtotime($date));
            $day_name = substr($get_name, 0, 3);
            if($day_name != 'Sun' && $day_name != 'Sat')
            {
                    $workdays[] = date('Y-m-d', strtotime($year.'-'.$month.'-'.$i));
            }
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

    public function addRows($params){
        $helperDao = new HelperDao();
        $code = "1";
        $message = "Time Records failed to insert.";
        $employee = $this->getEmployeeById($params['employee_id']);
        $file_upload = array(
            "type_id" => $params["type_id"],
            "file_name" => $params["file_name"],
            "file_size" => $params["file_size"],
            "employee_id" => $params["employee_id"],
            "accomplishment_date" => $params["transaction_date"]
        );
        unset($params['employee_id']);
        $this->db->trans_begin();
        $this->db->insert('tbltimekeepingaccomplishmentreports', $file_upload);
        $this->db->where('transaction_date', $params['transaction_date']);
        $this->db->delete('tbltimekeepingdailytimerecord');
        foreach ($params['transaction_time'] as $k => $v) {
            $params2 = array();
            $params2['remarks'] = $params['remarks'];
            $params2['source_device'] = 'manual input';
            $params2['source_location'] = 'manual input';
            $params2['modified_by'] = Helper::get('userid');
            $params2['transaction_date'] = $params['transaction_date'];
            $params2['transaction_type'] = $params['transaction_type'][$k];
            $params2['transaction_time'] = $params['transaction_time'][$k];
            $params2['employee_number'] = $this->Helper->decrypt($employee[0]['employee_number'], $employee[0]['id']);
            $this->db->insert('tbltimekeepingdailytimerecord',$params2);
        }
        if($this->db->trans_status() === TRUE){
            $code = "0";
            $this->db->trans_commit();
            $message = "Successfully inserted Time Records.";
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
            return true;
        }
        else {
            $this->db->trans_rollback();
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
        }
        return false;
    }

    public function getEmployeeNumber($id){
        $sql = "SELECT DECRYPTER(employee_number,'sunev8clt1234567890',id) as employee_number FROM tblemployees WHERE id = '".$id."'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function updateRows($params,$newparams) {
        $helperDao = new HelperDao();
        $code = "1";
        $message = "Time Records failed to insert.";
        $data = array(
            "scanning_no" => (int)$newparams["SCANNING_NUMBER"],
            "transaction_date" => $newparams["TRANSACTION_DATE"],
            "source" => 'excel_dtr',
            "adjustment_check_in" => $newparams["ACTUAL_AM_ARRIVAL"],
            "adjustment_break_out" => $newparams["ACTUAL_AM_DEPARTURE"],
            "adjustment_break_in" => $newparams["ACTUAL_PM_ARRIVAL"],
            "adjustment_check_out" => $newparams["ACTUAL_PM_DEPARTURE"],
            "adjustment_ot_in" => $newparams["OT_ARRIVAL"],
            "adjustment_ot_out" => $newparams["OT_DEPARTURE"],
            "offset" => $newparams["OFFSET"],
            "approve_offset_hrs" => @$newparams["APPROVE_OFFSET_HRS"],
            "approve_offset_mins" => @$newparams["APPROVE_OFFSET_MINS"],
            "ot_hrs" => $newparams["OT_HRS"],
            "ot_mins" => $newparams["OT_MINS"],
            "adjustment_monetized" => $newparams["MONETIZED"],
            "tardiness_hrs" => $newparams["TARDINESS_HRS"],
            "tardiness_mins" => $newparams["TARDINESS_MINS"],
            "ut_hrs" => $newparams["UT_HRS"],
            "ut_mins" => $newparams["UT_MINS"],
            "adjustment_remarks" => $newparams["ADJUSTMENT_REMARKS"]
        );
        $sql = "SELECT * FROM tbldtr WHERE DATE(transaction_date) = DATE('".$newparams["TRANSACTION_DATE"]."') AND CAST(scanning_no as INT) = ".(int)$newparams["SCANNING_NUMBER"];
        $isTransExist = $this->db->query($sql)->row_array();
        if($isTransExist != null) {
            if($isTransExist["approve_offset_hrs"] > 0 || $isTransExist["approve_offset_mins"] > 0) $data["adjustment_remarks"] = "Offset";
            $this->db->where("id",$isTransExist["id"])->update("tbldtr",$data);
        }
        else $this->db->insert('tbldtr',$data);
        if($this->db->trans_status() === TRUE){
            $code = "0";
            $this->db->trans_commit();
            $message = "Adjustment successfully updated.";
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
            return true;
        } else {
            $this->db->trans_rollback();
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
        }
        return false;
    }

    public function activeRows($params){
        $helperDao = new HelperDao();
        $code = "1";
        $message = "Daily Time Record failed to activate.";
        $data['Id'] = isset($params['id']) ? $params['id'] : '';
        $data['Status'] = "1";
        $userlevel_sql = "UPDATE tbltimekeepingdailytimerecord SET is_active = ? WHERE id = ?";
        $userlevel_params = array($data['Status'],$data['Id']);
        $this->db->query($userlevel_sql,$userlevel_params);
        if($this->db->affected_rows() > 0){
            $code = "0";
            $message = "Successfully activated Daily Time Record.";
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
            return true;
        }
        else {
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
        }
        return false;
    }

    public function inactiveRows($params){
        $helperDao = new HelperDao();
        $code = "1";
        $message = "Daily Time Record failed to deactivate.";
        $data['Id'] = isset($params['id']) ? $params['id'] : '';
        $data['Status'] = "0";
        $userlevel_sql = "UPDATE tbltimekeepingdailytimerecord SET is_active = ? WHERE id = ?";
        $userlevel_params = array($data['Status'],$data['Id']);
        $this->db->query($userlevel_sql,$userlevel_params);
        if($this->db->affected_rows() > 0){
            $code = "0";
            $message = "Successfully deactivated Daily Time Record.";
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
            return true;
        }
        else {
            $helperDao->auditTrails(Helper::get('userid'),$message);
            $this->ModelResponse($code, $message);
        }
        return false;
    }

    }
?>

