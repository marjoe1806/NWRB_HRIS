<?php
	class ProcessPayrollCollection extends Helper {
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
		var $table = "tbltransactionsprocesspayroll";
      	var $order_column = array('','CAST(emp_number as INT)','first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.contract','');
      	
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

		function make_query()
		{
			$employee_columns = $this->getEmployeeColumns();
			foreach ($employee_columns as $kemp => $vemp) {
				if ($vemp['COLUMN_NAME'] != "id" || $vemp['COLUMN_NAME'] != "modified_by" || $vemp['COLUMN_NAME'] != "is_active" || $vemp['date_created'] != "id") {
					$this->select_column[] = 'tblemployees.'.$vemp['COLUMN_NAME'];
				}
			}
			$this->select_column[] = "DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id)";
			$this->select_column[] = "DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id)";
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
			$this->db->distinct();
		    $this->db->select(
		    	'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
                DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
                DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
                DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.*,'.
		    	$this->table.'.*,
		    	tblemployees.id,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");

		    if(isset($_POST["search"]["value"]))
		    {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);
		     			$this->db->group_by("tbltransactionsprocesspayroll.employee_id");
		     		}
		     		else{	
		     			$this->db->or_like($value, $_POST["search"]["value"]);
		     			$this->db->group_by("tbltransactionsprocesspayroll.employee_id");
		     		}
		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		        $this->db->group_end();
		    }
		    
		    $this->db->where($this->table.'.is_active="1"');
			if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != null)
		    	$this->db->where($this->table.'.pay_basis',$_POST['pay_basis']);
		    if(isset($_POST['division_id']) && $_POST['division_id'] != null)
		    	$this->db->where($this->table.'.division_id',$_POST['division_id']);
		    if(isset($_POST['payroll_period_id']) && $_POST['payroll_period_id'] != null)
		    	$this->db->where($this->table.'.payroll_period_id',$_POST['payroll_period_id']);
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

		function make_datatables(){
		    $this->make_query();
		    if($_POST["length"] != -1) {
		        $this->db->limit($_POST['length'], $_POST['start']);
		    }
		    $query = $this->db->get();
		    return $query->result();
		}

		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->get();

		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched Overtime.";
				$data['details'] = $query->result();
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function get_filtered_data(){
		     $this->make_query();
		     $query = $this->db->get();
		     return $query->num_rows();
		}

		function get_all_data()
		{
		    $this->db->select($this->table."*");
		    $this->db->from($this->table);
		    return $this->db->count_all_results();
		}
		//End Fetch
		function hasRowsAllowancesActive($employee_id){
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

		function deductions($emp_id, $payroll_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";			
			$message = "No data available.";
			$sql = "SELECT * FROM tbltransactionsprocesspayroll where employee_id = ? AND payroll_period_id = ?";
			$query = $this->db->query($sql,array($emp_id,$payroll_id));
			$datas = $query->result_array();
			$data['data'] = $datas;
			if(sizeof($datas) > 0){
				return $data;
			}			
			return false;
		}

		public function get_witHoldingTax($emp_id, $payroll_id) {
			$sql = "SELECT * FROM tblimportprocesspayroll WHERE employee_id = ? AND payroll_period_id = ?";
			$query = $this->db->query($sql, array($emp_id, $payroll_id));
			$data = $query->row_array();
		
			return $data;
		}

		//Fetch Computed Payrolls
		function getUndertimeAndOvertime($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsprocesspayrolldeductions
					 WHERE employee_id = ? AND payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getUTV2($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsprocesspayrolldeductionss
					 WHERE employee_id = ? AND payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->row_array();
			return $loans;
		}

		function getAmortizedAllowances($employee_id,$payroll_period_id){
			$sql = " SELECT *,c.allowance_name FROM tbltransactionspayrollprocessallowances a
					 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id
					 LEFT JOIN tblfieldallowances c ON b.allowance_id = c.id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getAmortizedOtherBenefits($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsotherbenefitsamortization a
					 LEFT JOIN tbltransactionsotherbenefits b ON a.other_benefit_entry_id = b.id
					 LEFT JOIN tblfieldotherbenefits c ON c.id = b.benefit_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getAmortizedOtherEarnings($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsotherearningsamortization a
					 LEFT JOIN tbltransactionsotherearnings b ON a.other_earning_entry_id = b.id
					 LEFT JOIN tblfieldotherearnings c ON c.id = b.earning_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getAmortizedOtherDeductions($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsotherdeductionsamortization a
					 LEFT JOIN tbltransactionsotherdeductions b ON a.other_deduction_entry_id = b.id
					 LEFT JOIN tblfieldotherdeductions c ON c.id = b.deduction_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getAmortizedLoans($employee_id,$payroll_period_id){
			$sql = " SELECT a.*,b.*,c.code AS code_sub,c.description AS desc_sub, d.code AS code_loan,d.description AS desc_loan FROM tbltransactionsloansamortization a
					 LEFT JOIN tbltransactionsloans b ON a.loan_entry_id = b.id
					 LEFT JOIN tblfieldloanssub c ON c.id = b.sub_loans_id
					 LEFT JOIN tblfieldloans d ON d.id = c.loan_id
					 WHERE b.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? 
					 ORDER BY d.code ASC";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getEmployeeAllowances($employee_id){
			$sql = " SELECT * FROM tblemployeesallowances WHERE is_active = '1' AND employee_id = ?";
			$query = $this->db->query($sql,array($employee_id));
			$allowances = $query->result_array();
			return $allowances;
		}

		function getPayrollPeriodCutoff($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettingsweekly WHERE is_active = '1' AND payroll_period_id = ?";
			$query = $this->db->query($sql,array($payroll_period_id));
			$allowances = $query->result_array();
			return $allowances;
		}

		function getLoanEntries($employee_id,$payroll_period_id,$period_id,$date_amortization){
			$sql = "SELECT * FROM tbltransactionsloans WHERE employee_id = ? AND is_active = 1";
			$query = $this->db->query($sql, array($employee_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getOtherDeductionEntries($employee_id,$payroll_period_id,$period_id,$date_amortization){
			$sql = "SELECT a.*,(IF(a.amount - (SELECT SUM(c.amount) FROM tbltransactionsotherdeductionsamortization c 
					WHERE a.id = c.other_deduction_entry_id 
					AND ? > c.date_amortization) < a.amortization_per_month,a.amount - (SELECT SUM(c.amount) FROM tbltransactionsotherdeductionsamortization c 
					WHERE a.id = c.other_deduction_entry_id 
					AND ? > c.date_amortization),a.amortization_per_month)) AS amortization_per_month FROM tbltransactionsotherdeductions a
					LEFT JOIN tblfieldperiodsettings b ON a.payroll_period_id = b.id 
					WHERE a.is_active = '1' AND a.employee_id = ? AND b.period_id = ?
					AND ((a.rmtp - (SELECT COUNT(*) FROM tbltransactionsotherdeductionsamortization c 
					WHERE a.id = c.other_deduction_entry_id 						
					AND ? > c.date_amortization) > 0
					AND a.balance - (SELECT SUM(c.amount) FROM tbltransactionsotherdeductionsamortization c 
					WHERE a.id = c.other_deduction_entry_id 
					AND ? > c.date_amortization) > 0)
					|| a.payroll_period_id = ?)
					ORDER BY a.payroll_period_id DESC";
			$query = $this->db->query($sql,array($date_amortization,$date_amortization,$employee_id,$period_id,$date_amortization,$date_amortization,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getOtherBenefitEntries($employee_id,$payroll_period_id){
			$sql = " SELECT a.* FROM tbltransactionsotherbenefits a LEFT JOIN tblfieldotherbenefits b ON a.benefit_id = b.id WHERE a.is_active = '1' AND a.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getOtherEarningEntries($employee_id,$payroll_period_id){
			$sql = " SELECT a.*, b.is_taxable FROM tbltransactionsotherearnings a LEFT JOIN tblfieldotherearnings b ON a.earning_id = b.id WHERE a.is_active = '1' AND a.employee_id = ? AND a.payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getPayrollPeriodById2($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$data = $this->db->query($sql,array($payroll_period_id))->result_array();
			return $data;
		}

		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE id = ?";
			$query = $this->db->query($sql,array($payroll_period_id));
			$data = $query->result_array();
			return $data;
		}

		function getPreviousPayrollPeriod($month,$year){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE MONTH(payroll_period) = ? AND YEAR(payroll_period) = ? AND period_id = 'Whole Period' AND is_active = 1 ";
			$query = $this->db->query($sql,array($month,$year));
			$data = $query->result_array();
			return $data;
		}

		function getPreviousPayrollTransaction($payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsprocesspayroll WHERE payroll_period_id = ? AND is_active = 1 ";
			$query = $this->db->query($sql,array($payroll_period_id));
			$data = $query->result_array();
			return $data;
		}

		function getEmployeesByPayBasisAndId($pay_basis,$id){
			$sql = " SELECT * FROM tblemployees WHERE pay_basis = ? AND id = ?";
			$query = $this->db->query($sql,array($pay_basis,$id));
			$loans = $query->result_array();
			return $loans;
		}

		function getPayrollConfiguration(){
			$sql = " SELECT * FROM tblfieldpayrollconfigurations WHERE is_active = '1' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			return $data;
		}

		function getWithHoldingTaxes($income){
			$sql = " SELECT * FROM tblwithholdingtaxtable  WHERE compensation_level_from <= ? AND compensation_level_to >= ? ";
			$query = $this->db->query($sql,array($income,$income));
			$data = $query->result_array();
			return $data;
		}

		function getEmployeeMonthlySalary($id, $payroll_period){
			$sql = " SELECT *, MONTH(date_start) AS month FROM tblemployeessalaryhistory WHERE YEAR(date_start) = (SELECT YEAR(payroll_period) FROM tblfieldperiodsettings WHERE id = ?) AND employee_id = ?";
			$query = $this->db->query($sql,array($payroll_period,$id));
			$loans = $query->result_array();
			return $loans;
		}

		function hasRowsEmployeesByPayBasis($pay_basis){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT id,employee_number FROM tblemployees WHERE is_active = '1' AND employment_status='Active' AND pay_basis = ?";
			$query = $this->db->query($sql,array($pay_basis));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched employees.";
				$this->ModelResponse($code, $message, $data);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function getPayrollPostingByEmployeeAndPayrollPeriod($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionspayrollposting WHERE is_active = 1 AND employee_id = ? AND payroll_period_id = ?";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$result = $query->result_array();
			return $result;
		}

		public function computePayrollProcess($data){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to process Payroll.";
			$pay_basis = $data['pay_basis'];
			if($pay_basis == "Permanent"|| $pay_basis == "Contractual" || $pay_basis == "Casual" || $pay_basis == "Permanent (Probationary)"){
				$this->computePermanent($data);
			} else {
				$this->computeJobOrder($data);
			}
			return false;
		}

		function checkIfHoliday($working_day){
			$params = array($working_day);
			$sql = "SELECT * FROM tblfieldholidays WHERE date = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$holiday = $query->result_array();
			return $holiday;
		}

		function getWorkingDays($payroll_period) {
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
					$workday = date('Y-m-d', strtotime($year.'-'.$month.'-'.$i));
					if(sizeof($this->checkIfHoliday($workday)) == 0)
						$workdays[] = $workday;
				}
			}
			return $workdays;
		}

		public function computePermanent($data){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$payroll_period_id = $data['payroll_period_id'];
			$division_id = $data['division_id'];
			$pay_basis = $data['pay_basis'];
			if(!isset($data['employee_ids']) && !sizeof($data['employee_ids'] > 0)) return false;
			//Insert Variables
			$month_name = array("January","February","March","April","May","June","July","August","September","October","November","December"); 
			$allowance_insert = $other_benefits_insert = $other_earnings_insert = $other_deductions_insert = $loans_insert = $payroll_insert = $trans_process_payroll_deductions = $driver_monetized_insert = array();
			//Payroll Configuration
			$payrollconfig = $this->getPayrollConfiguration();
			//Payroll Period
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			foreach ($data['employee_ids'] as $k => $employee_id) {
				// var_dump($data["wout_pay"][$k]);
				// die();
				$isDtrRequired = $this->db->query("SELECT * FROM tblfieldpositions a LEFT JOIN tblemployees b ON a.id = b.position_id WHERE b.id = '".$employee_id."'")->row_array();
				if($isDtrRequired){
					if($isDtrRequired["is_dtr"]==0){
						$data["wout_pay"][$k] = 0;
						$data["process_absent"][$k] = 0;
						$data["process_tardiness_hrs"][$k] = 0;
						$data["process_tardiness_mins"][$k] = 0;
						$data["process_ut_hrs"][$k] = 0;
						$data["process_ut_mins"][$k] = 0;
						$data["wout_pay"][$k] = 0;
						$data["process_leave_credits_used"][$k] = 0;
						$data["process_ut_conversion"][$k] = 0;
						$data["process_offset_days"][$k] = 0;
						$data["process_offset_hrs"][$k] = 0;
						$data["process_offset_mins"][$k] = 0;
					}
				}
				
				$employee = $this->getEmployeesByPayBasisAndId($pay_basis,$employee_id);
				$salary = $employee[0]['salary'];
				$employee_monthly_salary = $this->getEmployeeMonthlySalary($employee_id, $payroll_period_id);
				if(!sizeof($employee) > 0) return false;
				$annual_salary_arr = $this->getAnnualSalary($employee_id,$payroll_period_id);
				if(isset($annual_salary_arr) && sizeof($annual_salary_arr) > 0){
					$current_month = 
					$monthly_salary_amt = 0;
					if(sizeof($annual_salary_arr) == 1){
						$salary = $annual_salary_arr[0]["salary"];
					}else{
						foreach ($annual_salary_arr as $k3 => $v3) {
							if((int)date("m",strtotime($data['period_year']."-".$data['period_month']."-01")) == $v3['month']){
								if(date("Y-m-d",strtotime($data['period_year']."-".$data['period_month']."-01")) == $v3['date_start']){
									$monthly_salary_amt = $v3["salary"];
								}else{
									$monthly_salary_amt = $annual_salary_arr[$k3-1]["salary"];
								}
								break;
							}else $monthly_salary_amt = end($annual_salary_arr)["salary"];
						}
						$salary = $monthly_salary_amt;
					}
				}				
				$day_rate = $salary / 22;
				$hr_rate = $day_rate / 8;
				$min_rate = $hr_rate / 60;
				$data["wout_pay"][$k] = $data["wout_pay"][$k] == "" ? 0 : $data["wout_pay"][$k];
					$woutpay_days = $woutpay_hrs = $woutpay_mins = 0;
					$woutpay_days = floor($data["wout_pay"][$k]);
					$woutpay_frct = $data["wout_pay"][$k] - $woutpay_days;
					$woutpay_hrs = ($woutpay_days * 8);
					$getconversiontable = $this->db->select("*")->from("tblleaveconversionfractions")->order_by("id","DESC")->get()->result_array();
					foreach($getconversiontable as $kc => $vc){
						if($woutpay_frct < end($getconversiontable)["equiv_day"]) break;
						if($woutpay_frct >= $vc["equiv_day"]){
							$woutpay_frct -= $vc["equiv_day"];
							if($vc["time_type"] == "hr") $woutpay_hrs += $vc["time_amount"];
							else $woutpay_mins += $vc["time_amount"];
						}
					}
				
					// $data["wout_pay"][$k] = 20;
				//END Taxable Gross
				// if($salary > 0 && $no_of_days > 0){
					$no_of_days = 22;
					$earned_for_period = $day_rate * $no_of_days;
					//LATES UNDERTIMES
					$absent_amt = $day_rate * $data["process_absent"][$k];
					$undertime_amt = 0;
					$late_amt = 0;					
					
					$total_tardiness_amt = round($salary/22*$data["wout_pay"][$k],2);
					$net_earned_for_period = $earned_for_period - $total_tardiness_amt;
					//Gross Income : salary - tardiness
					$gross_income = $salary - $total_tardiness_amt;
					//Added gross income = gross income + ot pays + holidays pay
					//GET PAGIBIG
					$PAGIBIGAmount = 0;
					$pagibig_amt_employer = 0;
					if($employee[0]['with_pagibig_contribution'] == "1"){
						$PAGIBIGAmount = $employee[0]['pagibig_contribution'];
						$pagibig_amt_employer = $payrollconfig[0]['employer_pagibig_amount'];
					}
					//END PAGIBIG
					//Get GSIS
					$GSISAmount = 0;
					$GSISAmountEmployer = 0;
					if($employee[0]['with_gsis'] == "1"){
						// $GSISFormula = $gross_income * ($payrollconfig[0]['employee_gsis_rate'] / 100);//$salary;
						$GSISFormula = $salary * ($payrollconfig[0]['employee_gsis_rate'] / 100);//$salary;
						$GSISAmount = $GSISFormula;
						$GSISAmountEmployer = $salary * ($payrollconfig[0]['employer_gsis_rate'] / 100); //$salary;
					}
					//End GSIS
					//Get PhilHealth
					$PhilHealthAmount = 0;
					$PhilHealthAmountEmployer = 0;
					if($employee[0]['with_philhealth_contribution'] == "1"){
						if($salary <= 10000){
							$PhilHealthAmount 			= $payrollconfig[0]['philhealth_rate_1'] / 2;
							$PhilHealthAmountEmployer 	= $PhilHealthAmount;
						}
						else if($salary >= 10000.01 && $salary <= 79999.99){
							$PhilHealthFormula = $salary * ($payrollconfig[0]['philhealth_rate_2'] / 100);
							$formula1 = $PhilHealthFormula / 2;
							$PhilHealthAmount = (floor($formula1 * 100) / 100);
							$PhilHealthAmountEmployer = round($formula1,2);
							//var_dump($PhilHealthAmount);die();
						}else{
							$PhilHealthAmount = $payrollconfig[0]['philhealth_rate_3']/2;
							$PhilHealthAmountEmployer = $PhilHealthAmount;
						}						
					}
					//End PhilHealth
					//Get E-COla
					$EColaAmount = $employee[0]['with_e_cola'] == "1" ? $payrollconfig[0]['monthly_e_cola_amount'] : 0;
					//GET ACPCEA
					$ACPCEAAmount = $employee[0]['with_acpcea'] == "1" ? $payrollconfig[0]['acpcea_amount'] : 0;
					//GET PERA
					$PERAAmount = 0;
					$PERA = 0;
					if($employee[0]['with_pera'] == "1"){
						$PERA = $payrollconfig[0]['pera_amount'];
						$PERAAmount = $PERA;
					}
					//GET PERA WOP
					$pera_wop_amt = $PERAAmount / 22 * $data["wout_pay"][$k];
					//RATA
					$rep_allowance = 0;
					$transpo_allowance = 0;
					$mp2_contribution = 0;
					$rep_allowance = isset($employee[0]['rep_allowance'])?$employee[0]['rep_allowance']:0;
					$transpo_allowance = isset($employee[0]['trans_allowance'])?$employee[0]['trans_allowance']:0;
					//DAMAYAN
					$damayan_amt = $employee[0]['with_damayan'] == "1" ? $payrollconfig[0]['damayan_amt'] : 0;
					$union_dues_amt = $employee[0]['with_union_dues'] == "1" ? $payrollconfig[0]['union_dues_amount'] : 0;
					$mp2_contribution = isset($employee[0]['mp2_contribution'])?$employee[0]['mp2_contribution']:0;
					// --------Earnings Ammortization------------
					//Taxable Gross
					// $gross_pay = $salary + $PERAAmount;
					$gross_pay = $salary;
					$taxable_gross_amount = $gross_pay - ($GSISAmount + $PAGIBIGAmount + $PhilHealthAmount);


					//Allowances
					$allowances = $this->getEmployeeAllowances($employee_id);
					$total_allowances = 0;
					if(sizeof($allowances) > 0){
						foreach ($allowances as $k1 => $v1) {
							$allowance_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"allowance_id"=>$v1['id'],
								"amount"=>$v1['amount'],
								"date_amortization"=>$payroll_period[0]["payroll_period"]
							);
							$total_allowances += floatval($v1['amount']);
						}
					}
					//Newly Added
					
					// --------Deductions Ammortization------------
					//With Holding Tax
					$withHoldingTaxAmount = 0;
					// $taxable_bonus_res = $this->getTaxableBonus($employee_id,$data["period_year"]);
					$employee_bonuses = $this->db->query("SELECT SUM(a.amount) as amount, (SELECT SUM(max) FROM tblfieldbonuses c WHERE c.group = b.group) as max, b.with_tax as tax, b.name as bonus FROM tbltransactionsbonus AS a INNER JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$employee_id."' AND a.year = '".date("Y")."' GROUP BY b.group")->result_array();
					$total_bonus = $labis = 0;
					if(sizeof($employee_bonuses) > 1){
						foreach($employee_bonuses as $bk => $bv){
							if($bv["tax"] == 1) $total_bonus += $bv["amount"];
							else{
								if($bv["max"] == 0) $total_bonus += $bv["amount"];
								else{
									$labis += $bv["amount"] > $bv["max"] ? ($bv["max"] == 0 ? 0 : $bv["amount"] - $bv["max"]) : 0;
									// $total_bonus += $bv["amount"] > $bv["max"] ? $bv["max"] : $bv["amount"];
								}
							}
						}
					}
					$total_bonus += $labis;
					$taxable_bonus = $total_bonus - $payrollconfig[0]['allowable_compensation'];
					$taxable_bonus = $taxable_bonus < 0 ? 0 : $taxable_bonus;
					// $taxable_bonus += $labis;
					

					// $annual_salary = $salary * 12;
					// $annual_salary_arr = $this->getAnnualSalary($employee_id,$payroll_period_id);
					if(isset($annual_salary_arr) && sizeof($annual_salary_arr) > 0){
	                    $prev_salary_amt = null; 
	                    $diff = 0;
	                    $str = "";
	                    $annual_salary_amt = 0;
	                    $annual_salary = 0;
	                    foreach($month_name AS $key => $value) {
							if(sizeof($annual_salary_arr) == 1){
								$annual_salary_amt = $annual_salary_arr[0]["salary"];
							}else{
								foreach ($annual_salary_arr as $k3 => $v3) {
									if((int)date("m",strtotime($value." 1, ".$data['period_year'])) == $v3['month']){
										if(date("Y-m-d",strtotime($value." 1, ".$data['period_year'])) == $v3['date_start']){
											$annual_salary_amt = $v3["salary"];
										}else{
											$annual_salary_amt = $annual_salary_arr[$k3-1]["salary"];
										}
										break;
									}else if((int)date("m",strtotime($value." 1, ".$data['period_year'])) < $v3['month']){
										$annual_salary_amt = end($annual_salary_arr);
										$annual_salary_amt = prev($annual_salary_arr)["salary"];
									}else{
										$annual_salary_amt = end($annual_salary_arr)["salary"];
									}	
								}
							}
							$annual_salary += $annual_salary_amt;
	                    }    
					} else $annual_salary = $salary * 12;

					// $annual_pagibig = $PAGIBIGAmount >= 100 ? 100 * 12 : $PAGIBIGAmount * 12;
					$annual_pagibig = $annual_philhealth = $annual_gsis = $annual_uniondues = 0;
					$processed_monthly_tax = $processed_monthly_deduction = 0;
					$monthly_tax = $this->getProcessMonthlyTax($employee_id,$payroll_period_id);
					if(sizeof($monthly_tax) > 0){
						$processed_monthly_tax = array_sum(array_column($monthly_tax, 'wh_tax_amt'));
						$processed_monthly_deduction = array_sum(array_column($monthly_tax, 'total_tardiness_amt'));
					}

					if($employee[0]['with_pagibig_contribution'] == 1){
						$annual_pagibig_arr = $this->getAnnualPagibig($employee_id,$payroll_period_id);
						$annual_pagibig = $annual_pagibig_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_pagibig += $PhilHealthAmountEmployer * $rem_months;
					}else $PAGIBIGAmount = 0;
					
					// $annual_philhealth = $PhilHealthAmount * 12;
					if($employee[0]['with_philhealth_contribution'] == 1){
						$annual_philhealth_arr = $this->getAnnualPhilhealth($employee_id,$payroll_period_id);
						$annual_philhealth = $annual_philhealth_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_philhealth += $PhilHealthAmount * $rem_months;
					}else $PhilHealthAmount = 0;

					// $annual_gsis = $GSISAmount * 12;
					if($employee[0]['with_gsis'] == 1){
						$annual_gsis_arr = $this->getAnnualGSIS($employee_id,$payroll_period_id);
						$annual_gsis = $annual_gsis_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_gsis += $GSISAmount * $rem_months;
					}else $GSISAmount = 0;

					// $annual_uniondues = $union_dues_amt * 12;
					if($employee[0]['with_union_dues'] == 1){
						$annual_uniondues_arr = $this->getAnnualUnionDues($employee_id,$payroll_period_id);
						$annual_uniondues = $annual_uniondues_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_uniondues += $union_dues_amt * $rem_months;
					}else $union_dues_amt = 0;

					// Other Earnings
					$other_earnings = $this->getOtherEarningEntries($employee_id,$payroll_period_id);
					$total_other_benefits = $total_other_earnings = $total_other_earnings_w_tax = $driver_ot = $tot_driver_ot = $tot_available_driver_ot = 0;
					if(sizeof($other_earnings) > 0){
						foreach ($other_earnings as $k1 => $v1) {
							$other_earnings_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_earning_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							if($v1["is_taxable"] == 1) $total_other_earnings_w_tax += floatval($v1['amount']);
							// else $total_other_earnings += floatval($v1['amount']);
						}
					}

					// Other benefits
					$other_benefits = $this->getOtherBenefitEntries($employee_id,$payroll_period_id);
					if(sizeof($other_benefits) > 0){
						foreach ($other_benefits as $k1 => $v1) {
							$other_benefits_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_benefit_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							$total_other_benefits += floatval($v1['amount']);
						}
					}

					$isDriver = $this->db->query("SELECT IFNULL(SUM(a.adjustment_monetized),IFNULL(SUM(a.monetized),0)) as monetized FROM tbldtr a LEFT JOIN tblemployees b ON a.scanning_no = DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) LEFT JOIN tblfieldpositions c ON b.position_id = c.id WHERE c.is_driver = 1 AND b.id = '".$employee_id."' AND MONTH(a.transaction_date) = '".$data['period_month']."' AND YEAR(a.transaction_date) = '".$data['period_year']."'")->row_array();
					$driver_ot = 0;
					// if($isDriver){
					// 	$tot_available_driver_ot = $annual_salary * .50;
					// 	$driver_monetized = $this->db->query("SELECT IFNULL(SUM(amount),0) as annual_monetized, IFNULL((SELECT amount FROM tblannualdriverovertime WHERE payroll_period_id = 27 AND MONTH(transaction_date) = CAST('02' AS INT) AND employee_id = '5d9b738199c13'),0) as monthly_monetized FROM tblannualdriverovertime WHERE payroll_period_id = ".$data["payroll_period_id"]." AND YEAR(transaction_date) = '".$data["period_year"]."' AND employee_id = '".$employee_id."'")->row_array();
					// 	$driver_ot = round($day_rate * $isDriver['monetized'], 2);
					// 	$tot_driver_ot = $driver_ot + ($driver_monetized ? $driver_monetized["annual_monetized"] - $driver_monetized["monthly_monetized"] : 0);
					// 	$driver_ot -= $tot_driver_ot > $tot_available_driver_ot ? $tot_driver_ot - $tot_available_driver_ot : 0;
					// 	$driver_monetized_insert[] = array(
					// 		"employee_id"=>$employee_id,
					// 		"payroll_period_id"=>$payroll_period_id,
					// 		"amount"=>$driver_ot,
					// 		"transaction_date"=>date("Y-m-d",strtotime($data['period_year']."-".$data['period_month']."-01"))
					// 	);
					// }

					//Other Deductions
					$total_other_deductions = $total_other_deduc_gsis = $total_other_deduc_philhealth = $total_other_deduc_tax = 0;
					$get_other_deductions_entries = $this->db->query("SELECT * FROM tbltransactionsotherdeductions  WHERE payroll_period_id = '".$payroll_period_id."' AND employee_id ='".$employee_id."' AND is_active = 1")->result_array();
					if(sizeof($get_other_deductions_entries) > 0){
						foreach($get_other_deductions_entries as $k_ode => $v_ode){
							if($v_ode['deduction_id'] == 1) $total_other_deduc_gsis += $v_ode['amount'];
							else if($v_ode['deduction_id'] == 2) $total_other_deduc_philhealth += $v_ode['amount'];
							else if($v_ode['deduction_id'] == 3) $total_other_deduc_tax += $v_ode['amount'];
							$total_other_deductions += $v_ode['amount'];
						}
					}
					// var_dump( $salary ." - salary");
					// var_dump($annual_salary." - annual_salary");
					// var_dump($taxable_bonus." - taxable_bonus");
					// var_dump($pagibig_amt_employer . " - pagibig_amt_employer");
					// var_dump($total_other_earnings_w_tax." - total_other_ear.nings_w_tax");
					// var_dump($processed_monthly_deduction . " - processed_monthly_deduction");

					// var_dump($driver_ot." - if driver = driver_monetary");
					// var_dump($annual_pagibig." - annual_pagibig");
					// var_dump($annual_philhealth." - annual_philhealth : " .$total_other_deduc_philhealth." - total_other_deduc_philhealth" );
					// var_dump($annual_gsis." - annual_gsis : " .$total_other_deduc_gsis." - total_other_deduc_gsis" );
					// var_dump($annual_uniondues." - annual_uniondues");
					$running_bal = $this->db->query("SELECT a.* FROM tblemployeesmonthlypayrollbalance a LEFT JOIN tblemployees b ON CAST(a.scanning_no as INT) = CAST(DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) as INT) WHERE a.month = '".((int)$data['period_month']-1)."' AND a.year = '".$data['period_year']."' AND b.id = '".$employee_id."' AND a.is_active = 1")->row_array();
					$rem_months = 12 - ((int)$data['period_month']-1);
					if($running_bal){
						$annual_pagibig = $running_bal["employer_pagibig"] + ($pagibig_amt_employer * $rem_months);
						$annual_philhealth = $running_bal["philhealth"] + ($PhilHealthAmount * $rem_months);
						$annual_gsis = $running_bal["gsis"] + ($GSISAmount * $rem_months);
						$annual_uniondues = $running_bal["union_dues"] + ($union_dues_amt * $rem_months);
						$processed_monthly_tax = $running_bal["wh_tax"];
						$processed_monthly_deduction = $running_bal["tardiness_amt"];
						// var_dump($running_bal["pagibig"] . " - running balance imported pagibig");
						// var_dump($PAGIBIGAmount . " - monthly pag ibig");
						// var_dump($annual_pagibig . " - total annual_pagibig");
						// var_dump($running_bal["philhealth"] . " - running balance imported philhealth");
						// var_dump($PhilHealthAmount . " - monthly philhealth");
						// var_dump($annual_philhealth . " - total annual_philhealth");
						// var_dump($running_bal["gsis"] . " - running balance imported gsis");
						// var_dump($GSISAmount . " - monthly gsis");
						// var_dump($annual_gsis . " - total annual_gsis");
						// var_dump($annual_uniondues . " - total annual uniondues");
					}
					// else{
					// 	$run_bal = $this->db->query("SELECT a.* FROM tblemployeesmonthlypayrollbalance a LEFT JOIN tblemployees b ON CAST(a.scanning_no as INT) = CAST(DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) as INT) WHERE scanning_no = '0083' AND month <= MONTH(NOW()) AND b.id = '".$employee_id."' AND a.is_active = 1
					// 			ORDER BY date_created DESC
					// 			LIMIT 1")->row_array();
					// 	if($run_bal){
					// 		$annual_pagibig = $running_bal["employer_pagibig"] + ($pagibig_amt_employer * $rem_months);
					// 		$annual_philhealth = $running_bal["philhealth"] + ($PhilHealthAmount * $rem_months);
					// 		$annual_gsis = $running_bal["gsis"] + ($GSISAmount * $rem_months);
					// 		$annual_uniondues = $running_bal["union_dues"] + ($union_dues_amt * $rem_months);
					// 		$processed_monthly_tax = $running_bal["wh_tax"];
					// 		$processed_monthly_deduction = $running_bal["tardiness_amt"];
					// 		$_bal = $this->db->query("SELECT
					// 								SUM(a.pagibig_amt_employer) as pagibig_amt_employer,
					// 								SUM(a.philhealth_amt) as philhealth_amt,
					// 								SUM(a.sss_gsis_amt) as sss_gsis_amt,
					// 								SUM(a.union_dues_amt) as union_dues_amt,
					// 								SUM(a.wh_tax_amt) as wh_tax_amt,
					// 								SUM(a.total_tardiness_amt) as total_tardiness_amt
					// 								FROM tbltransactionsprocesspayroll AS a
					// 								LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id
					// 								WHERE a.employee_id = '".$employee_id."'
					// 								AND YEAR(b.payroll_period) = YEAR('".$payroll_period."')
					// 								AND (MONTH(b.payroll_period) > 1 AND MONTH(b.payroll_period) < MONTH('".$payroll_period."'))")->row_array();
					// 		if($_bal){
					// 			$annual_pagibig += $_bal['pagibig_amt_employer'];
					// 			$annual_philhealth += $_bal['philhealth_amt'];
					// 			$annual_gsis += $_bal['sss_gsis_amt'];
					// 			$annual_uniondues += $_bal['union_dues_amt'];
					// 			$processed_monthly_tax += $_bal['wh_tax_amt'];
					// 			$processed_monthly_deduction += $_bal['total_tardiness_amt'];
					// 		}
					// 	}
					// }
					// die();
					
					$annual_taxable_salary =  (($annual_salary - $processed_monthly_deduction) + $taxable_bonus + $total_other_earnings_w_tax + $driver_ot) - ($annual_pagibig + ($annual_philhealth + $total_other_deduc_philhealth) + ($annual_gsis + $total_other_deduc_gsis) + $annual_uniondues);
					$tax_table = $this->getWithHoldingTaxes($annual_taxable_salary);
					$comp_from = 0.00;
					$tax_percentage = 0.00;
					$tax_additional = 0.00;
					if(sizeof($tax_table) > 0){
						$comp_from = $tax_table[0]['compensation_level_from'];
						$tax_percentage = $tax_table[0]['tax_percentage'];
						$tax_additional = $tax_table[0]['tax_additional'];
					}
					if($annual_taxable_salary > $comp_from){
						// var_dump($annual_taxable_salary." - annual_taxable_salary");
						// var_dump($comp_from." - comp_from");
						// var_dump($tax_percentage." - tax_percentage");
						// var_dump($tax_additional." - tax_additional");
						$withHoldingTaxFormula = round(($annual_taxable_salary - $comp_from) * ($tax_percentage / 100) + $tax_additional, 2);
						// var_dump((($annual_taxable_salary - $comp_from) * ($tax_percentage / 100) + $tax_additional)." - real value");
						// var_dump($withHoldingTaxFormula." - withHoldingTaxFormula");
						$cr_m = (int)$data['period_month'];

						$num1 = (13 - $cr_m);
						$num2 = ($processed_monthly_tax + $total_other_deduc_tax);
						$num3 = $num2/$num1;

						// $withHoldingTaxAmount  = round(($withHoldingTaxFormula - ($processed_monthly_tax + $total_other_deduc_tax)) / (13 - $cr_m), 2);
						$imported_wh_tax = $this->db->select("*")->from("tblimportprocesspayroll")->where("payroll_period_id",$payroll_period_id)->where("employee_id",$employee_id)->get()->row_array();
						$withHoldingTaxAmount = isset($imported_wh_tax['wh_tax_amt']) ? $imported_wh_tax['wh_tax_amt'] : 0.00;
						// var_dump($processed_monthly_tax." - processed_monthly_tax");
						// var_dump((($withHoldingTaxFormula - ($processed_monthly_tax + $total_other_deduc_tax)) / (13 - $cr_m)). " real value");
						// var_dump($withHoldingTaxAmount." - withHoldingTaxAmount");
						// var_dump($total_other_deduc_tax." - total_other_deduc_tax");
						// var_dump((13 - $cr_m)." -  remaining month");

						// print_r($withHoldingTaxAmount); die();
					}
					// die();

					//Other Loans Amortization
					$other_loans = $this->getOtherDeductionEntries($employee_id,$payroll_period_id,$payroll_period[0]["period_id"],$payroll_period[0]["payroll_period"]);
					$total_other_loans = 0;
					if(sizeof($other_loans) > 0){
						foreach ($other_loans as $k1 => $v1) {
							$other_deductions_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_deduction_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							$total_other_loans += floatval($v1['amount']);
						}
					}
										
					//Loans Amortization
					$loans = $this->getLoanEntries($employee_id,$payroll_period_id,$payroll_period[0]["period_id"],$payroll_period[0]["payroll_period"]);
					$total_loans = 0;
					if(sizeof($loans) > 0){
						foreach ($loans as $k1 => $v1) {
							$loans_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"loan_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amortization_per_month']
							);
							$total_loans += floatval($v1['amortization_per_month']);
						}
					}
					
					// $union_dues_amt di kasama sa payroll.. sinisingil lang ng association sa employee
					$total_deductions = 0;
					// var_dump($withHoldingTaxAmount . " - withHoldingTaxAmount");
					// var_dump($GSISAmount . " - GSISAmount");
					// var_dump($PAGIBIGAmount . " - PAGIBIGAmount");
					// var_dump($PhilHealthAmount . " - PhilHealthAmount");
					// die();
					$premium_deductions = $withHoldingTaxAmount + $GSISAmount + $PAGIBIGAmount + $PhilHealthAmount;
					$regular_deductions = $total_loans + $total_other_loans + $union_dues_amt + $mp2_contribution; //added other loans
					$PERAAmount -= $pera_wop_amt;
					// var_dump($gross_income ."gross a");
					
					$tmp_total_other_earnings_w_tax = $total_other_earnings_w_tax - round($total_other_earnings_w_tax/22*$data["wout_pay"][$k],2);
					$gross_income += $driver_ot + $total_other_earnings_w_tax + $total_other_benefits;
					// var_dump($gross_income ."gross b");
					// var_dump($rep_allowance . " - rep_allowance");
					// var_dump($transpo_allowance . " - transpo_allowance");
					// var_dump($PERAAmount . " - PERAAmount");
					// var_dump($driver_ot . " - driver_ot");
					// var_dump($total_other_earnings_w_tax . " - total_other_earnings_w_tax");
					// var_dump($total_other_benefits . " - total_other_benefits");

					$tmp_taxable_gross = $gross_income - ($GSISAmount + $PAGIBIGAmount + $PhilHealthAmount);
					$tmp_gross_pay = $gross_income + $PERAAmount;
					// var_dump($withHoldingTaxAmount . " - withHoldingTaxAmount");
					// var_dump($GSISAmount . " - GSISAmount");
					// var_dump($PAGIBIGAmount . " - PAGIBIGAmount");
					// var_dump($PhilHealthAmount . " - PhilHealthAmount");
					// var_dump($premium_deductions . " - premium_deductions");
					// var_dump($regular_deductions . " - regular_deductions");
					// var_dump($gross_income >= $premium_deductions);
					// var_dump($gross_income ."gross - premium". $premium_deductions);
					// var_dump($total_tardiness_amt . " - total_tardiness_amt");
					// var_dump($tmp_total_other_earnings_w_tax . " - tmp_total_other_earnings_w_tax");
					if($gross_income >= $premium_deductions){
						$gross_income -= $premium_deductions;
						$total_deductions += $premium_deductions;
						if($gross_income >= $regular_deductions){
							$gross_income -= $regular_deductions;
							$total_deductions += $regular_deductions;
						}else{
							$total_loans = 0;
							$loans_insert = array();
							$other_deductions_insert = array();
							$total_other_deductions = 0;
						}
					}else{
						$total_loans = 0;
						$withHoldingTaxAmount = 0;
						$GSISAmount = 0;
						$PAGIBIGAmount = 0;
						$PhilHealthAmount = 0;
						$loans_insert = array();
						$other_deductions_insert = array();

						$withHoldingTaxAmount = 0;
						$GSISAmount = 0;
						$GSISAmountEmployer = 0;
						$EColaAmount = 0;
						$PhilHealthAmount = 0;
						$PhilHealthAmountEmployer = 0;
						$ACPCEAAmount = 0;

						// $pera_wop_amt = $PERAAmount;
						$pera_wop_amt = 0;
						$taxable_gross_amount = 0;
						$gross_pay = 0;

						$PAGIBIGAmount = 0;
						$pagibig_amt_employer = 0;
						$damayan_amt = 0;
						// $total_other_earnings = 0;
						$total_other_deductions = 0;
						$total_loans = 0;
						$total_deductions = 0;
						$earned_for_period = 0;
						$net_amount_total = 0;

						$cutoff[1] = 0;
						$cutoff[2] = 0;
						$cutoff[3] = 0;
						$cutoff[4] = 0;
						$cutoff[5] = 0;
					}
					$total_deductions = round($total_deductions,2);
					// removed allowance and pera in net computation
					// $net_amount_total = $tmp_gross_pay - ($total_tardiness_amt + $total_deductions);
					// $net_amount_total += $rep_allowance + $transpo_allowance + $PERAAmount;
					$per = $this->db->select("MONTH(payroll_period) AS month, YEAR(payroll_period) AS year, period_id")->from("tblfieldperiodsettings")->where("id",$data["payroll_period_id"])->get()->row_array();
					$ispayroll = $this->db->select("*")->from("tbltransactionspayrollhistory")->where("employee_id",$employee_id)->where("month",$per["month"])->where("year",$per["year"])->get();

					// if($pay_basis == "Permanent") {
						// removed allowance and pera in net computation
						// var_dump($employee[0]['cut_off_1'] . " - cut_off_1");
						// var_dump($rep_allowance . " - rep_allowance");
						// var_dump($transpo_allowance . " - transpo_allowance");
						// var_dump($PERAAmount . " - PERAAmount");
						// var_dump($tmp_total_other_earnings_w_tax . " - tmp_total_other_earnings_w_tax");
						// var_dump($total_other_benefits . " - total_other_benefits");
						// var_dump($total_other_benefits . " - total_deductions");
						// var_dump($total_deductions . " - total_deductions");
						// var_dump($total_tardiness_amt . " - total_tardiness_amt");
						// die();
						// $cutoff[1] = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits);
						// $cutoff[1] += $rep_allowance + $transpo_allowance + $PERAAmount;
						$cutoff[1] = floor(($salary - ($total_deductions + $total_tardiness_amt) + $PERAAmount)/2);
						// var_dump($employee[0]['cut_off_1'] ." : ". $rep_allowance ." : ". $transpo_allowance ." : ". $PERAAmount ." : ". $driver_ot ." : ". $tmp_total_other_earnings_w_tax ." : ". $total_other_benefits);
						// var_dump($total_deductions ." : ". $total_tardiness_amt);
						// var_dump($cutoff[1] . " - cutoff 1");
						// var_dump($cutoff[2] . " - cutoff 2");
						// var_dump($cutoff[3] . " - cutoff 3");
						// var_dump($cutoff[4] . " - cutoff 4");
						$cutoff[2] = ($salary - ($total_deductions + $total_tardiness_amt) + $PERAAmount ) - $cutoff[1];
						// $cutoff[2] = $employee[0]['cut_off_2'] - ($cutoff[1] < 0 ? abs($cutoff[1]) : 0);
						$cutoff[3] = $employee[0]['cut_off_3'] - ($cutoff[2] < 0 ? abs($cutoff[2]) : 0);
						$cutoff[4] = $employee[0]['cut_off_4'] - ($cutoff[3] < 0 ? abs($cutoff[3]) : 0);
						if($cutoff[1] < 0) $cutoff[1] = 0;
						if($cutoff[2] < 0) $cutoff[2] = 0;
						if($cutoff[3] < 0) $cutoff[3] = 0;
						if($cutoff[4] < 0) $cutoff[4] = 0;
						$cutoff[5] = 0;
						$net_amount_total = $cutoff[1] + $cutoff[2] + $cutoff[3] + $cutoff[4] + $cutoff[5];
						if($ispayroll->num_rows() > 0){
							$historyparams = array( "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "cutoff_3"=>$cutoff[3], "cutoff_4"=>$cutoff[4], "cutoff_5"=>$cutoff[5], "modified_by"=>Helper::get('userid'));
							$this->db->where("employee_id", $employee_id)->where("month", $per["month"])->where("year", $per["year"])->update("tbltransactionspayrollhistory", $historyparams);
						}else{
							$historyparams = array("employee_id"=>$employee_id, "month"=>$per["month"], "year"=>$per["year"], "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "cutoff_3"=>$cutoff[3], "cutoff_4"=>$cutoff[4], "cutoff_5"=>$cutoff[5], "created_by"=>Helper::get('userid'));
							$this->db->insert("tbltransactionspayrollhistory", $historyparams);
						}
					// } else {
					// 	$cutoff_amount1 = $cutoff_amount2 = 0;
					// 	$cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
					// 	$cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
					// 	if($cutoff_amount1 < 0) $cutoff_amount1 = 0;
					// 	if($per["period_id"]=="1st Period"){
					// 		$cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
					// 		$cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
					// 		$cutoff_amount1 = $cutoff_amount1 < 0 ? 0 : $cutoff_amount1;
					// 		$cutoff_amount2 = $cutoff_amount2 < 0 ? 0 : $cutoff_amount2;
					// 	}else{
					// 		$rem = 0;
					// 		$perqy1 = $this->db->select("*")->from("tblfieldperiodsettings")->where("MONTH(payroll_period)",$per["month"])->where("YEAR(payroll_period)",$per["year"])->where("period_id","1st Period")->get();
					// 		$fstper = $perqy1->row_array();
					// 		if($perqy1->num_rows() > 0){
					// 			$perqy2 = $this->db->select("*")->from("tbltransactionsprocesspayroll")->where("payroll_period_id",$fstper["id"])->where("employee_id",$employee_id)->get();
					// 			$fstprocesspayroll = $perqy2->row_array();
					// 			$rem = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - $fstprocesspayroll["total_deduct_amt"];
					// 			if($perqy2->num_rows() > 0) $cutoff_amount1 = $fstprocesspayroll["cutoff_1"];
					// 			else $cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
					// 			// var_dump($cutoff_amount1." cutoff_amount1 a");
					// 		}else $cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
					// 		if($cutoff_amount1 < 0) $cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
					// 		else {
					// 			// var_dump($rem . " - rem");
					// 			$cutoff_amount2 = ($employee[0]['cut_off_2']) - ($rem < 0 ? abs($rem) : 0);
					// 			// var_dump($employee[0]['cut_off_2'] . " - cut_off_2");
					// 			// var_dump($cutoff_amount2 . " - cutoff_amount2");
					// 		}
					// 		$cutoff_amount1 = $cutoff_amount1 < 0 ? 0 : $cutoff_amount1;
					// 		$cutoff_amount2 = $cutoff_amount2 < 0 ? 0 : $cutoff_amount2;
					// 	}
					// 	// var_dump($cutoff_amount1." cutoff_amount1 last");
					// 	// var_dump($cutoff_amount2." cutoff_amount2");
					// 	// var_dump($employee[0]['cut_off_1'] . " - curoff");
					// 	// var_dump($employee[0]['cut_off_2'] . " - curoff 2");
					// 	// var_dump(($employee[0]['cut_off_1'] + $total_allowances + $PERAAmount + $driver_ot + $total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt) . " - remaining");
					// 	// var_dump(($employee[0]['cut_off_1'] + $total_allowances + $PERAAmount + $driver_ot + $total_other_earnings_w_tax + $total_other_benefits));
					// 	// var_dump(($total_deductions + $total_tardiness_amt)." tot deduc");
					// 	// var_dump($total_allowances . " - total_allowances");
					// 	// var_dump($PERAAmount . " - PERAAmount");
					// 	// var_dump($driver_ot . " - driver_ot");
					// 	// var_dump($total_other_earnings_w_tax . " - total_other_earnings_w_tax");
					// 	// var_dump($total_other_benefits . " - total_other_benefits");
					// 	// var_dump(($total_deductions + $total_tardiness_amt));
					// 	// var_dump($cutoff_amount1 . " - cutoff_amount1");
					// 	// var_dump($cutoff_amount2 . " - cutoff_amount2");
					// 	$cutoff[1] = $cutoff_amount1;
					// 	$cutoff[2] = $cutoff_amount2;
					// 	$cutoff[3] = 0;
					// 	$cutoff[4] = 0;
					// 	$cutoff[5] = 0;
					// 	if($ispayroll->num_rows() > 0){
					// 		$historyparams = array( "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "modified_by"=>Helper::get('userid'));
					// 		$this->db->where("employee_id", $employee_id)->where("month", $per["month"])->where("year", $per["year"])->update("tbltransactionspayrollhistory", $historyparams);
					// 	}else{
					// 		$historyparams = array("employee_id"=>$employee_id, "month"=>$per["month"], "year"=>$per["year"], "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "created_by"=>Helper::get('userid'));
					// 		$this->db->insert("tbltransactionspayrollhistory", $historyparams);
					// 	}
					// }
					
					// if($data["process_absent"][$k] >= 22) {
					// 	$withHoldingTaxAmount = 0;
					// 	$GSISAmount = 0;
					// 	$GSISAmountEmployer = 0;
					// 	$EColaAmount = 0;
					// 	$PhilHealthAmount = 0;
					// 	$PhilHealthAmountEmployer = 0;
					// 	$ACPCEAAmount = 0;

					// 	// $pera_wop_amt = $PERAAmount;
					// 	$pera_wop_amt = 0;
					// 	$PERAAmount = 0;

					// 	$rep_allowance = 0;
					// 	$transpo_allowance = 0;
					// 	$taxable_gross_amount = 0;
					// 	$gross_pay = 0;

					// 	$PAGIBIGAmount = 0;
					// 	$pagibig_amt_employer = 0;
					// 	$damayan_amt = 0;
					// 	$total_other_earnings = 0;
					// 	$total_other_deductions = 0;
					// 	$total_loans = 0;
					// 	$total_deductions = 0;
					// 	$earned_for_period = 0;
					// 	$net_amount_total = 0;

					// 	$cutoff[1] = 0;
					// 	$cutoff[2] = 0;
					// 	$cutoff[3] = 0;
					// 	$cutoff[4] = 0;
					// 	$cutoff[5] = 0;
					// }

					$net_amount_total = $net_amount_total < 0 ? 0 : $net_amount_total;
					
					$payroll_insert[] = array(
						"pay_basis"=>$pay_basis,
						"division_id"=>$division_id,
						"payroll_period_id"=>$payroll_period_id,
						"employee_id"=>$employee_id,
						"rate"=>$salary,
						"basic_pay"=>$salary,
						"day_rate"=>$day_rate,
						"hr_rate"=>$hr_rate,
						"min_rate"=>$min_rate,
						// "no_of_days"=>$no_of_days,
						"no_of_days"=> $data['no_days'],
						"present_day"=>$no_of_days,
						"present_day"=>$no_of_days,
						"late"=>str_replace(":", ".", @$lates_total),
						"late_amt"=>$late_amt,
						"utime"=>str_replace(":", ".", @$undertime_total),
						"utime_amt"=>$undertime_amt,
						"abst"=>$data["process_absent"][$k],
						"abst_amt"=>$absent_amt,
						"total_tardiness_amt"=>$total_tardiness_amt,
						// "wh_tax_amt"=>isset($imported_wh_tax['wh_tax_amt']) ? $imported_wh_tax['wh_tax_amt'] : 0.00,
						"wh_tax_amt"=>$withHoldingTaxAmount,
						// "wh_tax_amt"=>(floor($withHoldingTaxAmount * 100) / 100),
						//Newly Added
						"sss_gsis_amt" => $GSISAmount < 0 ? 0 : $GSISAmount,
						"sss_gsis_amt_employer" => $GSISAmountEmployer < 0 ? 0 : $GSISAmountEmployer,
						"e_cola_amt" => $EColaAmount < 0 ? 0 : $EColaAmount,
						"philhealth_amt" => $PhilHealthAmount < 0 ? 0 : $PhilHealthAmount,
						"philhealth_amt_employer" => $PhilHealthAmountEmployer < 0 ? 0 : $PhilHealthAmountEmployer,
						"acpcea_amt" => $ACPCEAAmount < 0 ? 0 : $ACPCEAAmount,
						"union_dues_amt" => $union_dues_amt < 0 ? 0 : $union_dues_amt,
						"mp2_contribution_amt" => $mp2_contribution < 0 ? 0 : $mp2_contribution,
						"pera_amt" => $PERAAmount < 0 ? 0 : $PERAAmount,
						"pera_wop_amt" => $pera_wop_amt,
						"rep_allowance"=>$rep_allowance,
						"transpo_allowance"=>$transpo_allowance,
						"taxable_gross" => $taxable_gross_amount < 0 ? 0 : $taxable_gross_amount,
						"gross_pay" => $tmp_gross_pay < 0 ? 0 : $tmp_gross_pay,//$gross_pay < 0 ? 0 : $gross_pay,
						//Newaly added
						"sss_amt"=> 0,//No Computations yet
						"pagibig_amt"=>$PAGIBIGAmount,
						"pagibig_amt_employer"=>$pagibig_amt_employer,
						"damayan_amt"=>$damayan_amt,
						"total_other_benefit_amt"=>$total_other_benefits,
						"total_other_earning_amt"=>$total_other_earnings + $total_other_earnings_w_tax + $driver_ot,
						"total_other_deduct_amt"=>$total_other_deductions,//$total_other_deductions,
						"total_loan_deduct_amt"=>$total_loans,
						"total_deduct_amt"=>$total_deductions + $total_tardiness_amt,
						"earned_for_period"=>$earned_for_period,
						"net_earned"=>$net_earned_for_period,
						"net_pay"=>$net_amount_total,
						"cutoff_1"=>$cutoff[1],
						"cutoff_2"=>$cutoff[2],
						"cutoff_3"=>$cutoff[3],
						"cutoff_4"=>$cutoff[4],
						"cutoff_5"=>$cutoff[5],
						"modified_by"=>Helper::get('userid')
					);
					// print_r($payroll_insert); die();
				// }

				$trans_process_payroll_deductions[] = array(
					"employee_id" =>$employee_id,
					"payroll_period_id" =>$data["payroll_period_id"],
					"absent" => $data["process_absent"][$k],
					"tardiness_hrs" =>$data["process_tardiness_hrs"][$k],
					"tardiness_min" =>$data["process_tardiness_mins"][$k],
					"ut_hrs" =>$data["process_ut_hrs"][$k],
					"ut_min" =>$data["process_ut_mins"][$k],
					"fraction" =>$data["wout_pay"][$k],
					"leave_credits_used" =>$data["process_leave_credits_used"][$k],
					"ut_conversion" =>$data["process_ut_conversion"][$k],
					"offset_days" =>$data["process_offset_days"][$k],
					"offset_hrs" =>$data["process_offset_hrs"][$k],
					"offset_min" =>$data["process_offset_mins"][$k]
				);

			}

			if(sizeof($trans_process_payroll_deductions) > 0){
				foreach ($trans_process_payroll_deductions as $k => $v) {
					$sql = "DELETE FROM tbltransactionsprocesspayrolldeductionss WHERE employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['employee_id'],$v['payroll_period_id']));
				}
				$this->db->insert_batch("tbltransactionsprocesspayrolldeductionss", $trans_process_payroll_deductions);
			}

			if(sizeof($driver_monetized_insert) > 0){
				foreach ($driver_monetized_insert as $k => $v) {
					$sql = "DELETE FROM tblannualdriverovertime WHERE employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['employee_id'],$v['payroll_period_id']));
				}
				$this->db->insert_batch('tblannualdriverovertime', $driver_monetized_insert);
			}

			if(sizeof($allowance_insert) > 0){
				foreach ($allowance_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionspayrollprocessallowances 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND allowance_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['allowance_id']
										));
				}
				$this->db->insert_batch('tbltransactionspayrollprocessallowances', $allowance_insert);
			}
			if(sizeof($other_earnings_insert) > 0){
				foreach ($other_earnings_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherearningsamortization 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_earning_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_earning_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherearningsamortization', $other_earnings_insert);
			}
			if(sizeof($other_benefits_insert) > 0){
				foreach ($other_benefits_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherbenefitsamortization 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_benefit_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_benefit_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherbenefitsamortization', $other_benefits_insert);
			}
			if(sizeof($other_deductions_insert) > 0){
				foreach ($other_deductions_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherdeductionsamortization
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_deduction_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_deduction_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherdeductionsamortization', $other_deductions_insert);
			}
			if(sizeof($loans_insert) > 0){
				foreach ($loans_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsloansamortization
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND loan_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['loan_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsloansamortization', $loans_insert);
			}
			if(sizeof($payroll_insert) > 0){
				foreach ($payroll_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsprocesspayroll
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id']
										));
				}
				$this->db->insert_batch('tbltransactionsprocesspayroll', $payroll_insert);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully processed periodic payroll.";
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

		public function computeJobOrder($data){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$payroll_period_id = $data['payroll_period_id'];
			$division_id = $data['division_id'];
			$pay_basis = $data['pay_basis'];
			if(!isset($data['employee_ids']) && !sizeof($data['employee_ids'] > 0)) return false;
			//Insert Variables
			$month_name = array("January","February","March","April","May","June","July","August","September","October","November","December"); 
			$allowance_insert = $other_benefits_insert = $other_earnings_insert = $other_deductions_insert = $loans_insert = $payroll_insert = $trans_process_payroll_deductions = $driver_monetized_insert = array();
			//Payroll Configuration
			$payrollconfig = $this->getPayrollConfiguration();
			//Payroll Period
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);

			foreach ($data['employee_ids'] as $k => $employee_id) {
				// var_dump($data["wout_pay"][$k]);
				// die();
				$isDtrRequired = $this->db->query("SELECT * FROM tblfieldpositions a LEFT JOIN tblemployees b ON a.id = b.position_id WHERE b.id = '".$employee_id."'")->row_array();
				if($isDtrRequired){
					if($isDtrRequired["is_dtr"]==0){
						$data["wout_pay"][$k] = 0;
						$data["process_absent"][$k] = 0;
						$data["process_tardiness_hrs"][$k] = 0;
						$data["process_tardiness_mins"][$k] = 0;
						$data["process_ut_hrs"][$k] = 0;
						$data["process_ut_mins"][$k] = 0;
						$data["wout_pay"][$k] = 0;
						$data["process_leave_credits_used"][$k] = 0;
						$data["process_ut_conversion"][$k] = 0;
						$data["process_offset_days"][$k] = 0;
						$data["process_offset_hrs"][$k] = 0;
						$data["process_offset_mins"][$k] = 0;
					}
				}
				
				$employee = $this->getEmployeesByPayBasisAndId($pay_basis,$employee_id);
				$salary = $employee[0]['salary'];
				$employee_monthly_salary = $this->getEmployeeMonthlySalary($employee_id, $payroll_period_id);
				if(!sizeof($employee) > 0) return false;
				$annual_salary_arr = $this->getAnnualSalary($employee_id,$payroll_period_id);
				if(isset($annual_salary_arr) && sizeof($annual_salary_arr) > 0){
					$current_month = 
					$monthly_salary_amt = 0;
					if(sizeof($annual_salary_arr) == 1){
						$salary = $annual_salary_arr[0]["salary"];
					}else{
						foreach ($annual_salary_arr as $k3 => $v3) {
							if((int)date("m",strtotime($data['period_year']."-".$data['period_month']."-01")) == $v3['month']){
								if(date("Y-m-d",strtotime($data['period_year']."-".$data['period_month']."-01")) == $v3['date_start']){
									$monthly_salary_amt = $v3["salary"];
								}else{
									$monthly_salary_amt = $annual_salary_arr[$k3-1]["salary"];
								}
								break;
							}else $monthly_salary_amt = end($annual_salary_arr)["salary"];
						}
						$salary = $monthly_salary_amt;
					}
				}				
				$day_rate = $salary / 22;
				$hr_rate = $day_rate / 8;
				$min_rate = $hr_rate / 60;
				$data["wout_pay"][$k] = $data["wout_pay"][$k] == "" ? 0 : $data["wout_pay"][$k];
					$woutpay_days = $woutpay_hrs = $woutpay_mins = 0;
					$woutpay_days = floor($data["wout_pay"][$k]);
					$woutpay_frct = $data["wout_pay"][$k] - $woutpay_days;
					$woutpay_hrs = ($woutpay_days * 8);
					$getconversiontable = $this->db->select("*")->from("tblleaveconversionfractions")->order_by("id","DESC")->get()->result_array();
					foreach($getconversiontable as $kc => $vc){
						if($woutpay_frct < end($getconversiontable)["equiv_day"]) break;
						if($woutpay_frct >= $vc["equiv_day"]){
							$woutpay_frct -= $vc["equiv_day"];
							if($vc["time_type"] == "hr") $woutpay_hrs += $vc["time_amount"];
							else $woutpay_mins += $vc["time_amount"];
						}
					}
				
					// $data["wout_pay"][$k] = 20;
				//END Taxable Gross
				// if($salary > 0 && $no_of_days > 0){
					$no_of_days = 22;
					$earned_for_period = $day_rate * $no_of_days;
					//LATES UNDERTIMES
					$absent_amt = $day_rate * $data["process_absent"][$k];
					$undertime_amt = 0;
					$late_amt = 0;					
					
					$total_tardiness_amt = round($salary/22*$data["wout_pay"][$k],2);
					$net_earned_for_period = $earned_for_period - $total_tardiness_amt;
					//Gross Income : salary - tardiness
					$gross_income = $salary - $total_tardiness_amt;
					//Added gross income = gross income + ot pays + holidays pay
					//GET PAGIBIG
					$PAGIBIGAmount = 0;
					$pagibig_amt_employer = 0;
					if($employee[0]['with_pagibig_contribution'] == "1"){
						$PAGIBIGAmount = $employee[0]['pagibig_contribution'];
						$pagibig_amt_employer = $payrollconfig[0]['employer_pagibig_amount'];
					}
					//END PAGIBIG
					//Get GSIS
					$GSISAmount = 0;
					$GSISAmountEmployer = 0;
					if($employee[0]['with_gsis'] == "1"){
						// $GSISFormula = $gross_income * ($payrollconfig[0]['employee_gsis_rate'] / 100);//$salary;
						$GSISFormula = $salary * ($payrollconfig[0]['employee_gsis_rate'] / 100);//$salary;
						$GSISAmount = $GSISFormula;
						$GSISAmountEmployer = $salary * ($payrollconfig[0]['employer_gsis_rate'] / 100); //$salary;
					}
					//End GSIS
					//Get PhilHealth
					$PhilHealthAmount = 0;
					$PhilHealthAmountEmployer = 0;
					if($employee[0]['with_philhealth_contribution'] == "1"){
							$PhilHealthAmount 			= $employee[0]['philhealth_cos'];
							$PhilHealthAmountEmployer 	= 0;				
					}
					//End PhilHealth
					//Get E-COla
					$EColaAmount = $employee[0]['with_e_cola'] == "1" ? $payrollconfig[0]['monthly_e_cola_amount'] : 0;
					//GET ACPCEA
					$ACPCEAAmount = $employee[0]['with_acpcea'] == "1" ? $payrollconfig[0]['acpcea_amount'] : 0;
					//GET PERA
					$PERAAmount = 0;
					$PERA = 0;
					if($employee[0]['with_pera'] == "1"){
						$PERA = $payrollconfig[0]['pera_amount'];
						$PERAAmount = $PERA;
					}
					//GET PERA WOP
					$pera_wop_amt = $PERAAmount / 22 * $data["wout_pay"][$k];
					//RATA
					$rep_allowance = 0;
					$transpo_allowance = 0;
					$mp2_contribution = 0;
					$rep_allowance = isset($employee[0]['rep_allowance'])?$employee[0]['rep_allowance']:0;
					$transpo_allowance = isset($employee[0]['trans_allowance'])?$employee[0]['trans_allowance']:0;
					//DAMAYAN
					$damayan_amt = $employee[0]['with_damayan'] == "1" ? $payrollconfig[0]['damayan_amt'] : 0;
					$union_dues_amt = $employee[0]['with_union_dues'] == "1" ? $payrollconfig[0]['union_dues_amount'] : 0;
					$mp2_contribution = isset($employee[0]['mp2_contribution'])?$employee[0]['mp2_contribution']:0;
					// --------Earnings Ammortization------------
					//Taxable Gross
					// $gross_pay = $salary + $PERAAmount;
					$gross_pay = $salary;
					$taxable_gross_amount = $gross_pay - ($GSISAmount + $PAGIBIGAmount + $PhilHealthAmount);


					//Allowances
					$allowances = $this->getEmployeeAllowances($employee_id);
					$total_allowances = 0;
					if(sizeof($allowances) > 0){
						foreach ($allowances as $k1 => $v1) {
							$allowance_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"allowance_id"=>$v1['id'],
								"amount"=>$v1['amount'],
								"date_amortization"=>$payroll_period[0]["payroll_period"]
							);
							$total_allowances += floatval($v1['amount']);
						}
					}
					//Newly Added
					
					// --------Deductions Ammortization------------
					//With Holding Tax
					$withHoldingTaxAmount = 0;
					// $taxable_bonus_res = $this->getTaxableBonus($employee_id,$data["period_year"]);
					$employee_bonuses = $this->db->query("SELECT SUM(a.amount) as amount, (SELECT SUM(max) FROM tblfieldbonuses c WHERE c.group = b.group) as max, b.with_tax as tax, b.name as bonus FROM tbltransactionsbonus AS a INNER JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$employee_id."' AND a.year = '".date("Y")."' GROUP BY b.group")->result_array();
					$total_bonus = $labis = 0;
					if(sizeof($employee_bonuses) > 1){
						foreach($employee_bonuses as $bk => $bv){
							if($bv["tax"] == 1) $total_bonus += $bv["amount"];
							else{
								if($bv["max"] == 0) $total_bonus += $bv["amount"];
								else{
									$labis += $bv["amount"] > $bv["max"] ? ($bv["max"] == 0 ? 0 : $bv["amount"] - $bv["max"]) : 0;
									// $total_bonus += $bv["amount"] > $bv["max"] ? $bv["max"] : $bv["amount"];
								}
							}
						}
					}
					$total_bonus += $labis;
					$taxable_bonus = $total_bonus - $payrollconfig[0]['allowable_compensation'];
					$taxable_bonus = $taxable_bonus < 0 ? 0 : $taxable_bonus;
					// $taxable_bonus += $labis;
					

					// $annual_salary = $salary * 12;
					// $annual_salary_arr = $this->getAnnualSalary($employee_id,$payroll_period_id);
					if(isset($annual_salary_arr) && sizeof($annual_salary_arr) > 0){
	                    $prev_salary_amt = null; 
	                    $diff = 0;
	                    $str = "";
	                    $annual_salary_amt = 0;
	                    $annual_salary = 0;
	                    foreach($month_name AS $key => $value) {
							if(sizeof($annual_salary_arr) == 1){
								$annual_salary_amt = $annual_salary_arr[0]["salary"];
							}else{
								foreach ($annual_salary_arr as $k3 => $v3) {
									if((int)date("m",strtotime($value." 1, ".$data['period_year'])) == $v3['month']){
										if(date("Y-m-d",strtotime($value." 1, ".$data['period_year'])) == $v3['date_start']){
											$annual_salary_amt = $v3["salary"];
										}else{
											$annual_salary_amt = $annual_salary_arr[$k3-1]["salary"];
										}
										break;
									}else if((int)date("m",strtotime($value." 1, ".$data['period_year'])) < $v3['month']){
										$annual_salary_amt = end($annual_salary_arr);
										$annual_salary_amt = prev($annual_salary_arr)["salary"];
									}else{
										$annual_salary_amt = end($annual_salary_arr)["salary"];
									}	
								}
							}
							$annual_salary += $annual_salary_amt;
	                    }    
					} else $annual_salary = $salary * 12;

					// $annual_pagibig = $PAGIBIGAmount >= 100 ? 100 * 12 : $PAGIBIGAmount * 12;
					$annual_pagibig = $annual_philhealth = $annual_gsis = $annual_uniondues = 0;
					$processed_monthly_tax = $processed_monthly_deduction = 0;
					$monthly_tax = $this->getProcessMonthlyTax($employee_id,$payroll_period_id);
					if(sizeof($monthly_tax) > 0){
						$processed_monthly_tax = array_sum(array_column($monthly_tax, 'wh_tax_amt'));
						$processed_monthly_deduction = array_sum(array_column($monthly_tax, 'total_tardiness_amt'));
					}

					if($employee[0]['with_pagibig_contribution'] == 1){
						$annual_pagibig_arr = $this->getAnnualPagibig($employee_id,$payroll_period_id);
						$annual_pagibig = $annual_pagibig_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_pagibig += $PhilHealthAmountEmployer * $rem_months;
					}else $PAGIBIGAmount = 0;
					
					// $annual_philhealth = $PhilHealthAmount * 12;
					if($employee[0]['with_philhealth_contribution'] == 1){
						$annual_philhealth_arr = $this->getAnnualPhilhealth($employee_id,$payroll_period_id);
						$annual_philhealth = $annual_philhealth_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_philhealth += $PhilHealthAmount * $rem_months;
					}else $PhilHealthAmount = 0;

					// $annual_gsis = $GSISAmount * 12;
					if($employee[0]['with_gsis'] == 1){
						$annual_gsis_arr = $this->getAnnualGSIS($employee_id,$payroll_period_id);
						$annual_gsis = $annual_gsis_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_gsis += $GSISAmount * $rem_months;
					}else $GSISAmount = 0;

					// $annual_uniondues = $union_dues_amt * 12;
					if($employee[0]['with_union_dues'] == 1){
						$annual_uniondues_arr = $this->getAnnualUnionDues($employee_id,$payroll_period_id);
						$annual_uniondues = $annual_uniondues_arr["total_amt"];
						$rem_months = 12 - (int)$data['period_month'];
						$annual_uniondues += $union_dues_amt * $rem_months;
					}else $union_dues_amt = 0;

					// Other Earnings
					$other_earnings = $this->getOtherEarningEntries($employee_id,$payroll_period_id);
					$total_other_benefits = $total_other_earnings = $total_other_earnings_w_tax = $driver_ot = $tot_driver_ot = $tot_available_driver_ot = 0;
					if(sizeof($other_earnings) > 0){
						foreach ($other_earnings as $k1 => $v1) {
							$other_earnings_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_earning_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							if($v1["is_taxable"] == 1) $total_other_earnings_w_tax += floatval($v1['amount']);
							// else $total_other_earnings += floatval($v1['amount']);
						}
					}

					// Other benefits
					$other_benefits = $this->getOtherBenefitEntries($employee_id,$payroll_period_id);
					if(sizeof($other_benefits) > 0){
						foreach ($other_benefits as $k1 => $v1) {
							$other_benefits_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_benefit_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							$total_other_benefits += floatval($v1['amount']);
						}
					}

					$isDriver = $this->db->query("SELECT IFNULL(SUM(a.adjustment_monetized),IFNULL(SUM(a.monetized),0)) as monetized FROM tbldtr a LEFT JOIN tblemployees b ON a.scanning_no = DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) LEFT JOIN tblfieldpositions c ON b.position_id = c.id WHERE c.is_driver = 1 AND b.id = '".$employee_id."' AND MONTH(a.transaction_date) = '".$data['period_month']."' AND YEAR(a.transaction_date) = '".$data['period_year']."'")->row_array();
					$driver_ot = 0;
					// if($isDriver){
					// 	$tot_available_driver_ot = $annual_salary * .50;
					// 	$driver_monetized = $this->db->query("SELECT IFNULL(SUM(amount),0) as annual_monetized, IFNULL((SELECT amount FROM tblannualdriverovertime WHERE payroll_period_id = 27 AND MONTH(transaction_date) = CAST('02' AS INT) AND employee_id = '5d9b738199c13'),0) as monthly_monetized FROM tblannualdriverovertime WHERE payroll_period_id = ".$data["payroll_period_id"]." AND YEAR(transaction_date) = '".$data["period_year"]."' AND employee_id = '".$employee_id."'")->row_array();
					// 	$driver_ot = round($day_rate * $isDriver['monetized'], 2);
					// 	$tot_driver_ot = $driver_ot + ($driver_monetized ? $driver_monetized["annual_monetized"] - $driver_monetized["monthly_monetized"] : 0);
					// 	$driver_ot -= $tot_driver_ot > $tot_available_driver_ot ? $tot_driver_ot - $tot_available_driver_ot : 0;
					// 	$driver_monetized_insert[] = array(
					// 		"employee_id"=>$employee_id,
					// 		"payroll_period_id"=>$payroll_period_id,
					// 		"amount"=>$driver_ot,
					// 		"transaction_date"=>date("Y-m-d",strtotime($data['period_year']."-".$data['period_month']."-01"))
					// 	);
					// }

					//Other Deductions
					$total_other_deductions = $total_other_deduc_gsis = $total_other_deduc_philhealth = $total_other_deduc_tax = 0;
					$get_other_deductions_entries = $this->db->query("SELECT * FROM tbltransactionsotherdeductions  WHERE payroll_period_id = '".$payroll_period_id."' AND employee_id ='".$employee_id."' AND is_active = 1")->result_array();
					if(sizeof($get_other_deductions_entries) > 0){
						foreach($get_other_deductions_entries as $k_ode => $v_ode){
							if($v_ode['deduction_id'] == 1) $total_other_deduc_gsis += $v_ode['amount'];
							else if($v_ode['deduction_id'] == 2) $total_other_deduc_philhealth += $v_ode['amount'];
							else if($v_ode['deduction_id'] == 3) $total_other_deduc_tax += $v_ode['amount'];
							$total_other_deductions += $v_ode['amount'];
						}
					}
					// var_dump( $salary ." - salary");
					// var_dump($annual_salary." - annual_salary");
					// var_dump($taxable_bonus." - taxable_bonus");
					// var_dump($pagibig_amt_employer . " - pagibig_amt_employer");
					// var_dump($total_other_earnings_w_tax." - total_other_ear.nings_w_tax");
					// var_dump($processed_monthly_deduction . " - processed_monthly_deduction");

					// var_dump($driver_ot." - if driver = driver_monetary");
					// var_dump($annual_pagibig." - annual_pagibig");
					// var_dump($annual_philhealth." - annual_philhealth : " .$total_other_deduc_philhealth." - total_other_deduc_philhealth" );
					// var_dump($annual_gsis." - annual_gsis : " .$total_other_deduc_gsis." - total_other_deduc_gsis" );
					// var_dump($annual_uniondues." - annual_uniondues");
					$running_bal = $this->db->query("SELECT a.* FROM tblemployeesmonthlypayrollbalance a LEFT JOIN tblemployees b ON CAST(a.scanning_no as INT) = CAST(DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) as INT) WHERE a.month = '".((int)$data['period_month']-1)."' AND a.year = '".$data['period_year']."' AND b.id = '".$employee_id."' AND a.is_active = 1")->row_array();
					$rem_months = 12 - ((int)$data['period_month']-1);
					if($running_bal){
						$annual_pagibig = $running_bal["employer_pagibig"] + ($pagibig_amt_employer * $rem_months);
						$annual_philhealth = $running_bal["philhealth"] + ($PhilHealthAmount * $rem_months);
						$annual_gsis = $running_bal["gsis"] + ($GSISAmount * $rem_months);
						$annual_uniondues = $running_bal["union_dues"] + ($union_dues_amt * $rem_months);
						$processed_monthly_tax = $running_bal["wh_tax"];
						$processed_monthly_deduction = $running_bal["tardiness_amt"];
						// var_dump($running_bal["pagibig"] . " - running balance imported pagibig");
						// var_dump($PAGIBIGAmount . " - monthly pag ibig");
						// var_dump($annual_pagibig . " - total annual_pagibig");
						// var_dump($running_bal["philhealth"] . " - running balance imported philhealth");
						// var_dump($PhilHealthAmount . " - monthly philhealth");
						// var_dump($annual_philhealth . " - total annual_philhealth");
						// var_dump($running_bal["gsis"] . " - running balance imported gsis");
						// var_dump($GSISAmount . " - monthly gsis");
						// var_dump($annual_gsis . " - total annual_gsis");
						// var_dump($annual_uniondues . " - total annual uniondues");
					}
					// else{
					// 	$run_bal = $this->db->query("SELECT a.* FROM tblemployeesmonthlypayrollbalance a LEFT JOIN tblemployees b ON CAST(a.scanning_no as INT) = CAST(DECRYPTER(b.employee_number, 'sunev8clt1234567890', b.id) as INT) WHERE scanning_no = '0083' AND month <= MONTH(NOW()) AND b.id = '".$employee_id."' AND a.is_active = 1
					// 			ORDER BY date_created DESC
					// 			LIMIT 1")->row_array();
					// 	if($run_bal){
					// 		$annual_pagibig = $running_bal["employer_pagibig"] + ($pagibig_amt_employer * $rem_months);
					// 		$annual_philhealth = $running_bal["philhealth"] + ($PhilHealthAmount * $rem_months);
					// 		$annual_gsis = $running_bal["gsis"] + ($GSISAmount * $rem_months);
					// 		$annual_uniondues = $running_bal["union_dues"] + ($union_dues_amt * $rem_months);
					// 		$processed_monthly_tax = $running_bal["wh_tax"];
					// 		$processed_monthly_deduction = $running_bal["tardiness_amt"];
					// 		$_bal = $this->db->query("SELECT
					// 								SUM(a.pagibig_amt_employer) as pagibig_amt_employer,
					// 								SUM(a.philhealth_amt) as philhealth_amt,
					// 								SUM(a.sss_gsis_amt) as sss_gsis_amt,
					// 								SUM(a.union_dues_amt) as union_dues_amt,
					// 								SUM(a.wh_tax_amt) as wh_tax_amt,
					// 								SUM(a.total_tardiness_amt) as total_tardiness_amt
					// 								FROM tbltransactionsprocesspayroll AS a
					// 								LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id
					// 								WHERE a.employee_id = '".$employee_id."'
					// 								AND YEAR(b.payroll_period) = YEAR('".$payroll_period."')
					// 								AND (MONTH(b.payroll_period) > 1 AND MONTH(b.payroll_period) < MONTH('".$payroll_period."'))")->row_array();
					// 		if($_bal){
					// 			$annual_pagibig += $_bal['pagibig_amt_employer'];
					// 			$annual_philhealth += $_bal['philhealth_amt'];
					// 			$annual_gsis += $_bal['sss_gsis_amt'];
					// 			$annual_uniondues += $_bal['union_dues_amt'];
					// 			$processed_monthly_tax += $_bal['wh_tax_amt'];
					// 			$processed_monthly_deduction += $_bal['total_tardiness_amt'];
					// 		}
					// 	}
					// }
					// die();
					
					$annual_taxable_salary =  (($annual_salary - $processed_monthly_deduction) + $taxable_bonus + $total_other_earnings_w_tax + $driver_ot) - ($annual_pagibig + ($annual_philhealth + $total_other_deduc_philhealth) + ($annual_gsis + $total_other_deduc_gsis) + $annual_uniondues);
					$tax_table = $this->getWithHoldingTaxes($annual_taxable_salary);
					$comp_from = 0.00;
					$tax_percentage = 0.00;
					$tax_additional = 0.00;
					if(sizeof($tax_table) > 0){
						$comp_from = $tax_table[0]['compensation_level_from'];
						$tax_percentage = $tax_table[0]['tax_percentage'];
						$tax_additional = $tax_table[0]['tax_additional'];
					}
					if($annual_taxable_salary > $comp_from){
						// var_dump($annual_taxable_salary." - annual_taxable_salary");
						// var_dump($comp_from." - comp_from");
						// var_dump($tax_percentage." - tax_percentage");
						// var_dump($tax_additional." - tax_additional");
						$withHoldingTaxFormula = round(($annual_taxable_salary - $comp_from) * ($tax_percentage / 100) + $tax_additional, 2);
						// var_dump((($annual_taxable_salary - $comp_from) * ($tax_percentage / 100) + $tax_additional)." - real value");
						// var_dump($withHoldingTaxFormula." - withHoldingTaxFormula");
						$cr_m = (int)$data['period_month'];

						$num1 = (13 - $cr_m);
						$num2 = ($processed_monthly_tax + $total_other_deduc_tax);
						$num3 = $num2/$num1;

						// $withHoldingTaxAmount  = round(($withHoldingTaxFormula - ($processed_monthly_tax + $total_other_deduc_tax)) / (13 - $cr_m), 2);
						$imported_wh_tax = $this->db->select("*")->from("tblimportprocesspayroll")->where("payroll_period_id",$payroll_period_id)->where("employee_id",$employee_id)->get()->row_array();
						$withHoldingTaxAmount = isset($imported_wh_tax['wh_tax_amt']) ? $imported_wh_tax['wh_tax_amt'] : 0.00;
						// var_dump($processed_monthly_tax." - processed_monthly_tax");
						// var_dump((($withHoldingTaxFormula - ($processed_monthly_tax + $total_other_deduc_tax)) / (13 - $cr_m)). " real value");
						// var_dump($withHoldingTaxAmount." - withHoldingTaxAmount");
						// var_dump($total_other_deduc_tax." - total_other_deduc_tax");
						// var_dump((13 - $cr_m)." -  remaining month");

						// print_r($withHoldingTaxAmount); die();
					}
					// die();

					//Other Loans Amortization
					$other_loans = $this->getOtherDeductionEntries($employee_id,$payroll_period_id,$payroll_period[0]["period_id"],$payroll_period[0]["payroll_period"]);
					$total_other_loans = 0;
					if(sizeof($other_loans) > 0){
						foreach ($other_loans as $k1 => $v1) {
							$other_deductions_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"other_deduction_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amount']
							);
							$total_other_loans += floatval($v1['amount']);
						}
					}
					
					//Loans Amortization
					$loans = $this->getLoanEntries($employee_id,$payroll_period_id,$payroll_period[0]["period_id"],$payroll_period[0]["payroll_period"]);
					$total_loans = 0;
					if(sizeof($loans) > 0){
						foreach ($loans as $k1 => $v1) {
							$loans_insert[] = array(
								"pay_basis"=>$pay_basis,
								"division_id"=>$division_id,
								"payroll_period_id"=>$payroll_period_id,
								"employee_id"=>$employee_id,
								"loan_entry_id"=>$v1['id'],
								"date_amortization"=>$payroll_period[0]["payroll_period"],
								"amount"=>$v1['amortization_per_month']
							);
							$total_loans += floatval($v1['amortization_per_month']);
						}
					}
					
					// $union_dues_amt di kasama sa payroll.. sinisingil lang ng association sa employee
					$total_deductions = 0;
					// var_dump($withHoldingTaxAmount . " - withHoldingTaxAmount");
					// var_dump($GSISAmount . " - GSISAmount");
					// var_dump($PAGIBIGAmount . " - PAGIBIGAmount");
					// var_dump($PhilHealthAmount . " - PhilHealthAmount");
					// die();
					$premium_deductions = $withHoldingTaxAmount + $GSISAmount + $PAGIBIGAmount + $PhilHealthAmount;
					$regular_deductions = $total_loans + $total_other_loans + $union_dues_amt + $mp2_contribution; //added other loans
					$PERAAmount -= $pera_wop_amt;
					// var_dump($gross_income ."gross a");
					
					$tmp_total_other_earnings_w_tax = $total_other_earnings_w_tax - round($total_other_earnings_w_tax/22*$data["wout_pay"][$k],2);
					$gross_income += $driver_ot + $total_other_earnings_w_tax + $total_other_benefits;
					// var_dump($gross_income ."gross b");
					// var_dump($rep_allowance . " - rep_allowance");
					// var_dump($transpo_allowance . " - transpo_allowance");
					// var_dump($PERAAmount . " - PERAAmount");
					// var_dump($driver_ot . " - driver_ot");
					// var_dump($total_other_earnings_w_tax . " - total_other_earnings_w_tax");
					// var_dump($total_other_benefits . " - total_other_benefits");

					$tmp_taxable_gross = $gross_income - ($GSISAmount + $PAGIBIGAmount + $PhilHealthAmount);
					$tmp_gross_pay = $gross_income + $PERAAmount;
					// var_dump($withHoldingTaxAmount . " - withHoldingTaxAmount");
					// var_dump($GSISAmount . " - GSISAmount");
					// var_dump($PAGIBIGAmount . " - PAGIBIGAmount");
					// var_dump($PhilHealthAmount . " - PhilHealthAmount");
					// var_dump($premium_deductions . " - premium_deductions");
					// var_dump($regular_deductions . " - regular_deductions");
					// var_dump($gross_income >= $premium_deductions);
					// var_dump($gross_income ."gross - premium". $premium_deductions);
					// var_dump($total_tardiness_amt . " - total_tardiness_amt");
					// var_dump($tmp_total_other_earnings_w_tax . " - tmp_total_other_earnings_w_tax");
					if($gross_income >= $premium_deductions){
						$gross_income -= $premium_deductions;
						$total_deductions += $premium_deductions;
						if($gross_income >= $regular_deductions){
							$gross_income -= $regular_deductions;
							$total_deductions += $regular_deductions;
						}else{
							$total_loans = 0;
							$loans_insert = array();
							$other_deductions_insert = array();
							$total_other_deductions = 0;
						}
					}else{
						$total_loans = 0;
						$withHoldingTaxAmount = 0;
						$GSISAmount = 0;
						$PAGIBIGAmount = 0;
						$PhilHealthAmount = 0;
						$loans_insert = array();
						$other_deductions_insert = array();

						$withHoldingTaxAmount = 0;
						$GSISAmount = 0;
						$GSISAmountEmployer = 0;
						$EColaAmount = 0;
						$PhilHealthAmount = 0;
						$PhilHealthAmountEmployer = 0;
						$ACPCEAAmount = 0;

						// $pera_wop_amt = $PERAAmount;
						$pera_wop_amt = 0;
						$taxable_gross_amount = 0;
						$gross_pay = 0;

						$PAGIBIGAmount = 0;
						$pagibig_amt_employer = 0;
						$damayan_amt = 0;
						// $total_other_earnings = 0;
						$total_other_deductions = 0;
						$total_loans = 0;
						$total_deductions = 0;
						$earned_for_period = 0;
						$net_amount_total = 0;

						$cutoff[1] = 0;
						$cutoff[2] = 0;
						$cutoff[3] = 0;
						$cutoff[4] = 0;
						$cutoff[5] = 0;
					}
					$total_deductions = round($total_deductions,2);
					// removed allowance and pera in net computation
					// $net_amount_total = $tmp_gross_pay - ($total_tardiness_amt + $total_deductions);
					// $net_amount_total += $rep_allowance + $transpo_allowance + $PERAAmount;
					$per = $this->db->select("MONTH(payroll_period) AS month, YEAR(payroll_period) AS year, period_id")->from("tblfieldperiodsettings")->where("id",$data["payroll_period_id"])->get()->row_array();
					$ispayroll = $this->db->select("*")->from("tbltransactionspayrollhistory")->where("employee_id",$employee_id)->where("month",$per["month"])->where("year",$per["year"])->get();

					// if($pay_basis == "Permanent") {
					// 	// removed allowance and pera in net computation
					// 	// var_dump($employee[0]['cut_off_1'] . " - cut_off_1");
					// 	// var_dump($rep_allowance . " - rep_allowance");
					// 	// var_dump($transpo_allowance . " - transpo_allowance");
					// 	// var_dump($PERAAmount . " - PERAAmount");
					// 	// var_dump($tmp_total_other_earnings_w_tax . " - tmp_total_other_earnings_w_tax");
					// 	// var_dump($total_other_benefits . " - total_other_benefits");
					// 	// var_dump($total_other_benefits . " - total_deductions");
					// 	// var_dump($total_deductions . " - total_deductions");
					// 	// var_dump($total_tardiness_amt . " - total_tardiness_amt");
					// 	// die();
					// 	// $cutoff[1] = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits);
					// 	// $cutoff[1] += $rep_allowance + $transpo_allowance + $PERAAmount;
					// 	$cutoff[1] = floor(($salary - ($total_deductions + $total_tardiness_amt) + $PERA)/2);
					// 	// var_dump($employee[0]['cut_off_1'] ." : ". $rep_allowance ." : ". $transpo_allowance ." : ". $PERAAmount ." : ". $driver_ot ." : ". $tmp_total_other_earnings_w_tax ." : ". $total_other_benefits);
					// 	// var_dump($total_deductions ." : ". $total_tardiness_amt);
					// 	// var_dump($cutoff[1] . " - cutoff 1");
					// 	// var_dump($cutoff[2] . " - cutoff 2");
					// 	// var_dump($cutoff[3] . " - cutoff 3");
					// 	// var_dump($cutoff[4] . " - cutoff 4");
					// 	$cutoff[2] = ($salary - ($total_deductions + $total_tardiness_amt) + $PERA ) - $cutoff[1];
					// 	// $cutoff[2] = $employee[0]['cut_off_2'] - ($cutoff[1] < 0 ? abs($cutoff[1]) : 0);
					// 	$cutoff[3] = $employee[0]['cut_off_3'] - ($cutoff[2] < 0 ? abs($cutoff[2]) : 0);
					// 	$cutoff[4] = $employee[0]['cut_off_4'] - ($cutoff[3] < 0 ? abs($cutoff[3]) : 0);
					// 	if($cutoff[1] < 0) $cutoff[1] = 0;
					// 	if($cutoff[2] < 0) $cutoff[2] = 0;
					// 	if($cutoff[3] < 0) $cutoff[3] = 0;
					// 	if($cutoff[4] < 0) $cutoff[4] = 0;
					// 	$cutoff[5] = 0;
					// 	$net_amount_total = $cutoff[1] + $cutoff[2] + $cutoff[3] + $cutoff[4] + $cutoff[5];
					// 	if($ispayroll->num_rows() > 0){
					// 		$historyparams = array( "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "cutoff_3"=>$cutoff[3], "cutoff_4"=>$cutoff[4], "cutoff_5"=>$cutoff[5], "modified_by"=>Helper::get('userid'));
					// 		$this->db->where("employee_id", $employee_id)->where("month", $per["month"])->where("year", $per["year"])->update("tbltransactionspayrollhistory", $historyparams);
					// 	}else{
					// 		$historyparams = array("employee_id"=>$employee_id, "month"=>$per["month"], "year"=>$per["year"], "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "cutoff_3"=>$cutoff[3], "cutoff_4"=>$cutoff[4], "cutoff_5"=>$cutoff[5], "created_by"=>Helper::get('userid'));
					// 		$this->db->insert("tbltransactionspayrollhistory", $historyparams);
					// 	}
					// } else {
						$cutoff_amount1 = $cutoff_amount2 = 0;
						$cutoff_amount1 = floor(($salary - ($total_deductions + $total_tardiness_amt) + $PERAAmount)/2);
						$cutoff_amount2 = ($salary - ($total_deductions + $total_tardiness_amt) + $PERAAmount ) - $cutoff_amount1;
						// $cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
						// $cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
						if($cutoff_amount1 < 0) $cutoff_amount1 = 0;
						// if($per["period_id"]=="1st Period"){
						// 	$cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
						// 	$cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
						// 	$cutoff_amount1 = $cutoff_amount1 < 0 ? 0 : $cutoff_amount1;
						// 	$cutoff_amount2 = $cutoff_amount2 < 0 ? 0 : $cutoff_amount2;
						// }else{
						// 	$rem = 0;
						// 	$perqy1 = $this->db->select("*")->from("tblfieldperiodsettings")->where("MONTH(payroll_period)",$per["month"])->where("YEAR(payroll_period)",$per["year"])->where("period_id","1st Period")->get();
						// 	$fstper = $perqy1->row_array();
						// 	if($perqy1->num_rows() > 0){
						// 		$perqy2 = $this->db->select("*")->from("tbltransactionsprocesspayroll")->where("payroll_period_id",$fstper["id"])->where("employee_id",$employee_id)->get();
						// 		$fstprocesspayroll = $perqy2->row_array();
						// 		$rem = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - $fstprocesspayroll["total_deduct_amt"];
						// 		if($perqy2->num_rows() > 0) $cutoff_amount1 = $fstprocesspayroll["cutoff_1"];
						// 		else $cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
						// 		// var_dump($cutoff_amount1." cutoff_amount1 a");
						// 	}else $cutoff_amount1 = ($employee[0]['cut_off_1'] + $driver_ot + $tmp_total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt);
						// 	if($cutoff_amount1 < 0) $cutoff_amount2 = ($employee[0]['cut_off_2']) - ($cutoff_amount1 < 0 ? abs($cutoff_amount1) : 0);
						// 	else {
						// 		// var_dump($rem . " - rem");
						// 		$cutoff_amount2 = ($employee[0]['cut_off_2']) - ($rem < 0 ? abs($rem) : 0);
						// 		// var_dump($employee[0]['cut_off_2'] . " - cut_off_2");
						// 		// var_dump($cutoff_amount2 . " - cutoff_amount2");
						// 	}
						// 	$cutoff_amount1 = $cutoff_amount1 < 0 ? 0 : $cutoff_amount1;
						// 	$cutoff_amount2 = $cutoff_amount2 < 0 ? 0 : $cutoff_amount2;
						// 	$net_amount_total = $cutoff_amount1 + $cutoff_amount2;
						// }
						// var_dump($cutoff_amount1." cutoff_amount1 last");
						// var_dump($cutoff_amount2." cutoff_amount2");
						// var_dump($employee[0]['cut_off_1'] . " - curoff");
						// var_dump($employee[0]['cut_off_2'] . " - curoff 2");
						// var_dump(($employee[0]['cut_off_1'] + $total_allowances + $PERAAmount + $driver_ot + $total_other_earnings_w_tax + $total_other_benefits) - ($total_deductions + $total_tardiness_amt) . " - remaining");
						// var_dump(($employee[0]['cut_off_1'] + $total_allowances + $PERAAmount + $driver_ot + $total_other_earnings_w_tax + $total_other_benefits));
						// var_dump(($total_deductions + $total_tardiness_amt)." tot deduc");
						// var_dump($total_allowances . " - total_allowances");
						// var_dump($PERAAmount . " - PERAAmount");
						// var_dump($driver_ot . " - driver_ot");
						// var_dump($total_other_earnings_w_tax . " - total_other_earnings_w_tax");
						// var_dump($total_other_benefits . " - total_other_benefits");
						// var_dump(($total_deductions + $total_tardiness_amt));
						// var_dump($cutoff_amount1 . " - cutoff_amount1");
						// var_dump($cutoff_amount2 . " - cutoff_amount2");
						$cutoff_amount1 = $cutoff_amount1 < 0 ? 0 : $cutoff_amount1;
						$cutoff_amount2 = $cutoff_amount2 < 0 ? 0 : $cutoff_amount2;
						$net_amount_total = $cutoff_amount1 + $cutoff_amount2;
						$net_amount_total = $cutoff_amount1 + $cutoff_amount2;
						$cutoff[1] = $cutoff_amount1;
						$cutoff[2] = $cutoff_amount2;
						$cutoff[3] = 0;
						$cutoff[4] = 0;
						$cutoff[5] = 0;
						if($ispayroll->num_rows() > 0){
							$historyparams = array( "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "modified_by"=>Helper::get('userid'));
							$this->db->where("employee_id", $employee_id)->where("month", $per["month"])->where("year", $per["year"])->update("tbltransactionspayrollhistory", $historyparams);
						}else{
							$historyparams = array("employee_id"=>$employee_id, "month"=>$per["month"], "year"=>$per["year"], "cutoff_1"=>$cutoff[1], "cutoff_2"=>$cutoff[2], "created_by"=>Helper::get('userid'));
							$this->db->insert("tbltransactionspayrollhistory", $historyparams);
						}
					// }
					
					// if($data["process_absent"][$k] >= 22) {
					// 	$withHoldingTaxAmount = 0;
					// 	$GSISAmount = 0;
					// 	$GSISAmountEmployer = 0;
					// 	$EColaAmount = 0;
					// 	$PhilHealthAmount = 0;
					// 	$PhilHealthAmountEmployer = 0;
					// 	$ACPCEAAmount = 0;

					// 	// $pera_wop_amt = $PERAAmount;
					// 	$pera_wop_amt = 0;
					// 	$PERAAmount = 0;

					// 	$rep_allowance = 0;
					// 	$transpo_allowance = 0;
					// 	$taxable_gross_amount = 0;
					// 	$gross_pay = 0;

					// 	$PAGIBIGAmount = 0;
					// 	$pagibig_amt_employer = 0;
					// 	$damayan_amt = 0;
					// 	$total_other_earnings = 0;
					// 	$total_other_deductions = 0;
					// 	$total_loans = 0;
					// 	$total_deductions = 0;
					// 	$earned_for_period = 0;
					// 	$net_amount_total = 0;

					// 	$cutoff[1] = 0;
					// 	$cutoff[2] = 0;
					// 	$cutoff[3] = 0;
					// 	$cutoff[4] = 0;
					// 	$cutoff[5] = 0;
					// }

					$net_amount_total = $net_amount_total < 0 ? 0 : $net_amount_total;
					
					$payroll_insert[] = array(
						"pay_basis"=>$pay_basis,
						"division_id"=>$division_id,
						"payroll_period_id"=>$payroll_period_id,
						"employee_id"=>$employee_id,
						"rate"=>$salary,
						"basic_pay"=>$salary,
						"day_rate"=>$day_rate,
						"hr_rate"=>$hr_rate,
						"min_rate"=>$min_rate,
						// "no_of_days"=>$no_of_days,
						"no_of_days"=> $data['no_days'],
						"present_day"=>$no_of_days,
						"present_day"=>$no_of_days,
						"late"=>str_replace(":", ".", @$lates_total),
						"late_amt"=>$late_amt,
						"utime"=>str_replace(":", ".", @$undertime_total),
						"utime_amt"=>$undertime_amt,
						"abst"=>$data["process_absent"][$k],
						"abst_amt"=>$absent_amt,
						"total_tardiness_amt"=>$total_tardiness_amt,
						// "wh_tax_amt"=>isset($imported_wh_tax['wh_tax_amt']) ? $imported_wh_tax['wh_tax_amt'] : 0.00,
						"wh_tax_amt"=>$withHoldingTaxAmount,
						// "wh_tax_amt"=>(floor($withHoldingTaxAmount * 100) / 100),
						//Newly Added
						"sss_gsis_amt" => $GSISAmount < 0 ? 0 : $GSISAmount,
						"sss_gsis_amt_employer" => $GSISAmountEmployer < 0 ? 0 : $GSISAmountEmployer,
						"e_cola_amt" => $EColaAmount < 0 ? 0 : $EColaAmount,
						"philhealth_amt" => $PhilHealthAmount < 0 ? 0 : $PhilHealthAmount,
						"philhealth_amt_employer" => $PhilHealthAmountEmployer < 0 ? 0 : $PhilHealthAmountEmployer,
						"acpcea_amt" => $ACPCEAAmount < 0 ? 0 : $ACPCEAAmount,
						"union_dues_amt" => $union_dues_amt < 0 ? 0 : $union_dues_amt,
						"mp2_contribution_amt" => $mp2_contribution < 0 ? 0 : $mp2_contribution,
						"pera_amt" => $PERAAmount < 0 ? 0 : $PERAAmount,
						"pera_wop_amt" => $pera_wop_amt,
						"rep_allowance"=>$rep_allowance,
						"transpo_allowance"=>$transpo_allowance,
						"taxable_gross" => $taxable_gross_amount < 0 ? 0 : $taxable_gross_amount,
						"gross_pay" => $tmp_gross_pay < 0 ? 0 : $tmp_gross_pay,//$gross_pay < 0 ? 0 : $gross_pay,
						//Newaly added
						"sss_amt"=> 0,//No Computations yet
						"pagibig_amt"=>$PAGIBIGAmount,
						"pagibig_amt_employer"=>$pagibig_amt_employer,
						"damayan_amt"=>$damayan_amt,
						"total_other_benefit_amt"=>$total_other_benefits,
						"total_other_earning_amt"=>$total_other_earnings + $total_other_earnings_w_tax + $driver_ot,
						"total_other_deduct_amt"=>$total_other_deductions,//$total_other_deductions,
						"total_loan_deduct_amt"=>$total_loans,
						"total_deduct_amt"=>$total_deductions + $total_tardiness_amt,
						"earned_for_period"=>$earned_for_period,
						"net_earned"=>$net_earned_for_period,
						"net_pay"=>$net_amount_total,
						"cutoff_1"=>$cutoff[1],
						"cutoff_2"=>$cutoff[2],
						"cutoff_3"=>$cutoff[3],
						"cutoff_4"=>$cutoff[4],
						"cutoff_5"=>$cutoff[5],
						"modified_by"=>Helper::get('userid')
					);
					// print_r($payroll_insert); die();
				// }

				$trans_process_payroll_deductions[] = array(
					"employee_id" =>$employee_id,
					"payroll_period_id" =>$data["payroll_period_id"],
					"absent" => $data["process_absent"][$k],
					"tardiness_hrs" =>$data["process_tardiness_hrs"][$k],
					"tardiness_min" =>$data["process_tardiness_mins"][$k],
					"ut_hrs" =>$data["process_ut_hrs"][$k],
					"ut_min" =>$data["process_ut_mins"][$k],
					"fraction" =>$data["wout_pay"][$k],
					"leave_credits_used" =>$data["process_leave_credits_used"][$k],
					"ut_conversion" =>$data["process_ut_conversion"][$k],
					"offset_days" =>$data["process_offset_days"][$k],
					"offset_hrs" =>$data["process_offset_hrs"][$k],
					"offset_min" =>$data["process_offset_mins"][$k]
				);

			}

			if(sizeof($trans_process_payroll_deductions) > 0){
				foreach ($trans_process_payroll_deductions as $k => $v) {
					$sql = "DELETE FROM tbltransactionsprocesspayrolldeductionss WHERE employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['employee_id'],$v['payroll_period_id']));
				}
				$this->db->insert_batch("tbltransactionsprocesspayrolldeductionss", $trans_process_payroll_deductions);
			}

			if(sizeof($driver_monetized_insert) > 0){
				foreach ($driver_monetized_insert as $k => $v) {
					$sql = "DELETE FROM tblannualdriverovertime WHERE employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['employee_id'],$v['payroll_period_id']));
				}
				$this->db->insert_batch('tblannualdriverovertime', $driver_monetized_insert);
			}

			if(sizeof($allowance_insert) > 0){
				foreach ($allowance_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionspayrollprocessallowances 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND allowance_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['allowance_id']
										));
				}
				$this->db->insert_batch('tbltransactionspayrollprocessallowances', $allowance_insert);
			}
			if(sizeof($other_earnings_insert) > 0){
				foreach ($other_earnings_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherearningsamortization 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_earning_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_earning_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherearningsamortization', $other_earnings_insert);
			}
			if(sizeof($other_benefits_insert) > 0){
				foreach ($other_benefits_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherbenefitsamortization 
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_benefit_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_benefit_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherbenefitsamortization', $other_benefits_insert);
			}
			if(sizeof($other_deductions_insert) > 0){
				foreach ($other_deductions_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsotherdeductionsamortization
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND other_deduction_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['other_deduction_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsotherdeductionsamortization', $other_deductions_insert);
			}
			if(sizeof($loans_insert) > 0){
				foreach ($loans_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsloansamortization
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ? AND loan_entry_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id'],$v['loan_entry_id']
										));
				}
				$this->db->insert_batch('tbltransactionsloansamortization', $loans_insert);
			}
			if(sizeof($payroll_insert) > 0){
				foreach ($payroll_insert as $k => $v) {
					$sql = "DELETE FROM tbltransactionsprocesspayroll
							WHERE pay_basis = ? AND division_id = ? AND employee_id = ? AND payroll_period_id = ?";
					$this->db->query($sql,array($v['pay_basis'],$v['division_id'],
												$v['employee_id'],$v['payroll_period_id']
										));
				}
				$this->db->insert_batch('tbltransactionsprocesspayroll', $payroll_insert);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully processed periodic payroll.";
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

		//End Loans
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

		function checkGlobalSettings() {
			$settings = array();
			$sql = "SELECT * FROM tblfieldpayrollsetup WHERE is_active = 1  ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			$settings = $query->result_array();
			return (sizeof($settings) > 0) ? $settings[0] : $settings;
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

		function getAttendanceAdjustments($working_day, $employee_number){
			$params = array($employee_number, $working_day);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = ? AND transaction_date = ? ";
			$query = $this->db->query($sql, $params);
			$adjustments = $query->result_array();
			return $adjustments;
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

		function getTimeDifference($start, $end) {
			$start  = strtotime($start);
			$end = strtotime($end);
			$diff = ($end - $start);
			$minutes = ($diff / 60) / 60;
			return number_format((float)abs($minutes), 2, '.', '');
		}

		function getEmployeeRegularSchedule($shift_id) {
			$params = array($shift_id);
			$sql = "SELECT *, '1' AS shift_type FROM tbltimekeepingemployeeschedulesweekly WHERE shift_code_id = ?";
			$query = $this->db->query($sql, $params);
			return $query->result_array();
		}

		function getEmployeeShiftHistory($employee_number, $payroll_period_id) {
			$sql = "(SELECT employee_id, shift_type, shift_id, DAY(STR_TO_DATE(previous_date_effectivity,'%m/%d/%Y')) AS shift_date_effectivity FROM tblemployeesshifthistory WHERE employee_id = '".$employee_number."' AND MONTH(STR_TO_DATE(previous_date_effectivity,'%m/%d/%Y')) = (SELECT MONTH(payroll_period) FROM tblfieldperiodsettings WHERE id = '".$payroll_period_id."') AND YEAR(STR_TO_DATE(previous_date_effectivity,'%m/%d/%Y')) = (SELECT YEAR(payroll_period) FROM tblfieldperiodsettings WHERE id = '".$payroll_period_id."')) UNION ALL (SELECT id, regular_shift, shift_id, DAY(STR_TO_DATE(shift_date_effectivity,'%m/%d/%Y')) FROM tblemployees WHERE id = '".$employee_number."'  AND MONTH(STR_TO_DATE(shift_date_effectivity,'%m/%d/%Y')) = (SELECT MONTH(payroll_period) FROM tblfieldperiodsettings WHERE id = '".$payroll_period_id."') AND YEAR(STR_TO_DATE(shift_date_effectivity,'%m/%d/%Y')) = (SELECT YEAR(payroll_period) FROM tblfieldperiodsettings WHERE id = '".$payroll_period_id."'))";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getCurrentEmployeeShiftHistory($employee_number, $day) {
			$sql = "SELECT id AS employee_id, regular_shift AS shift_type, shift_id, 1 AS shift_date_effectivity FROM tblemployees WHERE id = '".$employee_number."'";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getEmployeeFlexibleSchedule($shift_id) {
			$params = array($shift_id);
			$sql = "SELECT *, '0' AS shift_type FROM tbltimekeepingemployeeflexibleschedulesweekly WHERE shift_code_id = ?";
			$query = $this->db->query($sql, $params);
			return $query->result_array();
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

		function make_datatables_summary($employee_id, $employee_number, $payroll_period_id, $shift_id){
			$result = $this->make_query_summary($employee_id, $employee_number, $payroll_period_id, $shift_id);
			return $result;
		}

		function make_query_summary($employee_id, $employee_number, $payroll_period_id, $shift_id) {
			$dtr = array();

			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$payroll_date = explode("-", $payroll_period);
			$no_of_days = date('t',strtotime($payroll_period));
			$payroll_dates = $this->ProcessPayrollCollection->getPayrollPeriodById($payroll_period_id);
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

		public function addPayrollDeductions($params){
			$helperDao = new HelperDao();
			//Delete adjustments
			$this->db->where('employee_id', $params['employee_id']);
			$this->db->where('payroll_period_id', $params['payroll_period_id']);
			$this->db->delete('tbltransactionsprocesspayrolldeductions');
			$this->db->insert('tbltransactionsprocesspayrolldeductions', $params);
			$currentBal = $this->db->select("num_hrs")->from("tblovertimebalance")->where("employee_id",$params["employee_id"])->limit(1)->get()->row_array();
			if($currentBal===null) $this->db->insert("tblovertimebalance",array("employee_id"=>$params["employee_id"],"modified_by"=>Helper::get("userid"),"num_hrs"=>(0 + (int)$params["total_ot_hrs"])));
			else $this->db->where("employee_id",$params["employee_id"])->update("tblovertimebalance",array("modified_by"=>Helper::get("userid"),"num_hrs"=>((int)$currentBal["num_hrs"] + (int)$params["total_ot_hrs"])));
		}

		function getFlagCeremonyDay($day) {
			$params = array($day);
			$sql = "SELECT IF(EXISTS(SELECT 1 FROM tbltimekeepingflagceremonyschedules WHERE YEAR(flagdateceremony) = YEAR('".$day."') AND MONTH(flagdateceremony) = MONTH('".$day."')) = 1, (SELECT flagdateceremony FROM tbltimekeepingflagceremonyschedules WHERE YEAR(flagdateceremony) = YEAR('".$day."') AND MONTH(flagdateceremony) = MONTH('".$day."')),(SELECT ADDDATE( '".$day."' , MOD((9-DAYOFWEEK('".$day."')),7)))) AS flagday";
			$query = $this->db->query($sql, $params);
			$flagday = $query->result_array();
			return $flagday;
		}

		function getlwp($month,$employee_id,$year){
			// if($month == 1) {
			// 	$month = 12;
			// 	$year = $year - 1;
			// } else
			// 	$month = $month - 1;
				
			$sql = '
				SELECT 
				CASE
					WHEN (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt) >= 22 THEN "0"
					WHEN (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt) IS NULL THEN "22"
					ELSE 22 - (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt)
				END AS lwp_amt, (SELECT COUNT(1) FROM tblleavemanagementdaysleave WHERE id IN (SELECT id FROM tblleavemanagement WHERE employee_id = "'.$employee_id.'") AND MONTH(days_of_leave) = "'.$month.'" AND YEAR(days_of_leave) = "'.$year.'") AS leave_cnt FROM tbltransactionsprocesspayrolldeductions AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id WHERE employee_id = "'.$employee_id.'" AND YEAR(b.payroll_period) = "'.$year.'" AND MONTH(b.payroll_period) = "'.$month.'"
				';
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getTaxableBonus ($employee_id,$year){
			$sql = "SELECT IFNULL(SUM(amount),'0.00') AS taxable_bonus FROM tbltransactionsbonus AS a LEFT JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$employee_id."' AND a.year = '".$year."' AND b.with_tax = 1";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getAnnualSalary($employee_id,$payroll_period_id){
			$employee_number_res = $this->getEmployeeNumber($employee_id);
			$employee_number = $employee_number_res[0]['employee_number'];

			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];
			$sql = "SELECT 
							date_start, 
							salary, 
							MONTH(date_start) AS month, 
							DAY(date_start) AS day
						FROM tblemployeessalaryhistory 
						WHERE employee_id = '".$employee_id."' 
							AND YEAR(date_start) = YEAR('".$payroll_period."') AND MONTH(date_start) <= MONTH('".$payroll_period."')  ORDER BY date_start ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		// function getActualSalary($employee_id,$payroll_period_id){
		// 	$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
		// 	$payroll_period = $payroll_period[0]['payroll_period'];

		// 	$sql = "SELECT 
		// 				MONTH(b.payroll_period) AS month,
		// 				a.basic_pay
		// 			FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
		// 			WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."') GROUP BY MONTH(b.payroll_period) ORDER BY MONTH(b.payroll_period) ";
		// 	$query = $this->db->query($sql);
		// 	return $query->result_array();
		// }

		function getProcessMonthlyTax($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT 
						MONTH(b.payroll_period) AS month,
						a.wh_tax_amt,a.total_tardiness_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) < MONTH('".$payroll_period."') GROUP BY MONTH(b.payroll_period) ORDER BY MONTH(b.payroll_period) ";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getAnnualPagibig($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT 
						SUM(a.pagibig_amt_employer) as total_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		function getAnnualPhilhealth($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT 
						SUM(a.philhealth_amt) as total_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		function getAnnualGSIS($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT 
						SUM(a.sss_gsis_amt) as total_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		function getAnnualUnionDues($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT
						SUM(a.union_dues_amt) as total_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		function getAnnualTaxDue($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT 
						MONTH(b.payroll_period) AS month,
						a.wh_tax_amt
					FROM tbltransactionsprocesspayroll AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id  
					WHERE a.employee_id = '".$employee_id."' AND YEAR(b.payroll_period) = YEAR('".$payroll_period."') AND MONTH(b.payroll_period) <= MONTH('".$payroll_period."') GROUP BY MONTH(b.payroll_period) ORDER BY MONTH(b.payroll_period) ";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getReleasedBonus ($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT b.name AS bonus_type, a.amount FROM tbltransactionsbonus AS a LEFT JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$employee_id."' AND a.is_active = 1 AND b.with_tax = 1 AND a.year = YEAR('".$payroll_period."') ORDER BY a.date_created";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getNonTaxableReleasedBonus ($employee_id,$payroll_period_id){
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			$payroll_period = $payroll_period[0]['payroll_period'];

			$sql = "SELECT b.name AS bonus_type, a.amount FROM tbltransactionsbonus AS a LEFT JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$employee_id."' AND a.is_active = 1 AND b.with_tax = 0 AND a.year = YEAR('".$payroll_period."') ORDER BY a.date_created";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getEmployeeNumber ($employee_id){
			$sql = "SELECT DECRYPTER(employee_number,'sunev8clt1234567890',id) AS employee_number FROM tblemployees WHERE id = '".$employee_id."'";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getLeaveThisDay($employee_id, $trans_date){
			$sql = "SELECT * FROM tblleavemanagement a
			LEFT JOIN tblleavemanagementdaysleave b ON a.id = b.id
			WHERE 
			(
				(date(b.days_of_leave) = date('".$trans_date."') OR date(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = date('".$trans_date."'))
				OR
				(date(b.days_of_leave) <= date('".$trans_date."') AND date(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) >= date('".$trans_date."'))
				OR
				((a.date_filed <= date('".$trans_date."') AND DATE_ADD(a.date_filed, INTERVAL a.number_of_days DAY) >= date('".$trans_date."')) AND a.type = 'monetization')
			) AND a.employee_id = '".$employee_id."' AND a.status = 5 LIMIT 1";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		// for leave credits
		function getLeaveBalance($employee_id, $year){
			$sql = "SELECT * FROM tblleavebalance WHERE id = '".$employee_id."' AND year = '".$year."'";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
        function getTerminal($year,$employee_id){
			$sql = "SELECT * FROM tblleavemanagement WHERE employee_id = '".$employee_id."' AND type = 'terminal' AND status = 5";// AND YEAR(is_terminal) = ".$year;
			$query = $this->db->query($sql);
			return $query->row_array();
		}

        function getEarnedConversion($present_day){
			$this->db->select("*");
			$this->db->from("tblleavecreditsearnedconversion");
			$this->db->where("present_days = $present_day"); 
			$query = $this->db->get();  
		    return $query->result_array();  
		}
		
		function countDays($year, $month, $ignore, $terminal, $emp_shift_days) {

			$count = 0;
			$counter = mktime(0, 0, 0, $month, 1, $year);
			while (date("n", $counter) == $month) {
				if(in_array(date("l", $counter),$emp_shift_days)){
					if($terminal) if(date("Y-m-d", $counter) == $terminal["is_terminal"]) break;
					if (in_array(date("w", $counter), $ignore) == false) $count++;
				}
				$counter = strtotime("+1 day", $counter);
			}
			// $emp_shift_days = implode(", ",$emp_shift_days);
			$emp_shift_days = implode("','",$emp_shift_days);
			$query = $this->db->query("SELECT COUNT(*) as no_days FROM tblfieldholidays WHERE DAYNAME(date) IN ('".$emp_shift_days."') AND MONTH(date) = '".$month."' AND YEAR(date) = '".$year."'")->row_array();
			$count -= $query["no_days"];
			return $count;
		}
		
        function getUndertTime($month,$employee_id,$year, $isTerminal, $dtfiled, $terminal_date){
			$sql = "SELECT b.id, a.scanning_no, COUNT(*) as no_days, SUM(a.tardiness_hrs) as tardiness_hrs, SUM(a.tardiness_mins) as tardiness_mins, SUM(a.ut_hrs) as ut_hrs, SUM(a.ut_mins) as ut_mins, (SELECT COUNT(*) FROM tbldtr WHERE CAST(scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) AND approve_offset_hrs = 8 AND adjustment_remarks = 'offset' AND MONTH(transaction_date) = ".$month." AND YEAR(transaction_date) = ".$year.") as offset_days, SUM(a.approve_offset_hrs) as offset_hrs, SUM(a.approve_offset_mins) as offset_mins
			FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND (IFNULL(CAST(a.approve_offset_hrs AS INT),0) < 8)
			AND (remarks != 'Absent' OR adjustment_remarks != 'Absent')
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')";
			$query = $this->db->query($sql)->row_array();
			// var_dump("1".json_encode($query));
			$sql2 = "SELECT b.id, a.scanning_no, COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND (IFNULL(CAST(a.approve_offset_hrs AS INT),0) < 8)
			AND (remarks != 'Absent' OR adjustment_remarks != 'Absent')
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')
			AND source = 'excel_dtr'";
			$query_upload = $this->db->query($sql2)->row_array();

			$sql_no_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'";
			$sql_no_dtr .= " AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')";
			// $sql_no_dtr .= " AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')";
			$sql_no_dtr .= " AND (remarks != 'Holiday' OR adjustment_remarks != 'Holiday')";
			// if($isTerminal == true && $dtfiled == $month)
			// 	$sql_no_dtr .= " AND DATE(transaction_date) < DATE('".$terminal_date."')";
			$query_no_dtr = $this->db->query($sql_no_dtr)->row_array();
			// var_dump("2".json_encode($query_no_dtr));
			

			$sql_terminated_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')
			AND (remarks != 'Holiday' OR adjustment_remarks != 'Holiday')";
			if($isTerminal == true && $dtfiled == $month)
				$sql_terminated_dtr .= " AND DATE(transaction_date) > DATE('".$terminal_date."')";
			$query_terminated_dtr = $this->db->query($sql_terminated_dtr)->row_array();
			

			$sql_holiday_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'
			AND (remarks != 'Holiday' OR adjustment_remarks = 'Holiday')";
			if($isTerminal == true && $dtfiled == $month)
				$sql_holiday_dtr .= " AND DATE(transaction_date) > DATE('".$terminal_date."')";
			$query_holiday_dtr = $this->db->query($sql_holiday_dtr)->row_array();
			
			// $query["no_days"] = $query_no_dtr["no_days"] > 0 ? (int)$query["no_days"] - (int)$query_no_dtr["no_days"] : 0;
			$query["no_days_absent"] = $query_no_dtr["no_days"];
			$query["no_days_upload_dtr"] = $query_upload["no_days"];
			$query["no_days_holiday"] = $query_holiday_dtr["no_days"];
			$query["no_days_terminated"] = ($isTerminal == true && $dtfiled == $month) ? $query_terminated_dtr["no_days"] + 1 : 0;
			// $query["ut_hrs"] = $query["ut_hrs"] - (($isTerminal == true && $dtfiled == $month) ? $sql_terminated_dtr["no_days"] * 8 : 0);
			// var_dump("3".json_encode($query));
			return $query;
		}
		
        function getBalanceAsOf($month,$employee_id,$year){
			$sql = "SELECT * FROM tblleavebalancemonthlyasof WHERE id = '".$employee_id."' AND month = ".(int)$month." AND year = ".$year." LIMIT 1";
			$query = $this->db->query($sql)->row_array();
			return $query;
		}

        function getUTConvertsions($hr, $min){
			$hr_sql = "SELECT equiv_day FROM tblleaveconversionfractions WHERE time_amount = ".$hr." AND time_type = 'hr'";
			$min_sql = "SELECT equiv_day FROM tblleaveconversionfractions WHERE time_amount = ".$min." AND time_type = 'min'";
			$hr_result = $this->db->query($hr_sql)->row_array();
			$min_result = $this->db->query($min_sql)->row_array();
			$total = ($hr_result != null ? $hr_result["equiv_day"] : 0.000) + ($min_result != null ? $min_result["equiv_day"] : 0.000);
			return $total;
		}
        function getHolidays($month, $employee_id, $year) {
			$sql = 'SELECT COUNT(1) AS addtl_days FROM tblfieldholidays WHERE MONTH(date) = "'.$month.'" AND YEAR(date) = "'.$year.'" AND date NOT IN (SELECT transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = CAST((SELECT DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) FROM tblemployees WHERE id = "'.$employee_id.'") AS SIGNED)) AND date NOT IN (SELECT transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = CAST((SELECT DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) FROM tblemployees WHERE id = "'.$employee_id.'") AS SIGNED))';
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		public function getWorkSuspension($month,$year){
			$sql = "SELECT * FROM tblfieldholidays a WHERE is_active = 1 AND holiday_type='Suspension' AND MONTH(a.date) = ".$month." AND YEAR(a.date)= ".$year."";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
        function getLeaveIsMonth($month,$employee_id,$year){
			$sql = "	SELECT a.*,SUM(number_of_days) AS number_of_days FROM tblleavemanagement a
						WHERE	(
							((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
									OR
									((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
								) AND (SELECT YEAR(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id LIMIT 0,1) = '".$year."' AND employee_id = '".$employee_id."' AND a.is_active = 1 AND status = 5 GROUP BY type";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

        function getLeave($month,$employee_id,$year,$type){
			$sql = "	SELECT a.*,SUM(number_of_days) -
						(
							select COUNT(*) FROM tbldtr dtr
							left join tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
							WHERE DATE(dtr.transaction_date) IN
								(
									select lvd.days_of_leave from tblleavemanagement lv right join tblleavemanagementdaysleave lvd on lv.id = lvd.id
									where (MONTH(lvd.days_of_leave) = '".$month."'
									AND YEAR(lvd.days_of_leave) = '".$year."')
									AND employee_id = '".$employee_id."'
									AND lv.status = 5
									AND lv.type = '".$type."'
								)
							AND emp.id = '".$employee_id."'
							AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
							AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
							AND (
								(check_in  IS NOT NULL OR break_out  IS NOT NULL OR break_in  IS NOT NULL OR check_out  IS NOT NULL)
								OR
								(adjustment_check_in  IS NOT NULL OR adjustment_break_out  IS NOT NULL OR adjustment_break_in  IS NOT NULL OR adjustment_check_out  IS NOT NULL)
								)
						) as number_of_days
						FROM tblleavemanagement a
						WHERE	(
									((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
									OR
									((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
								) AND employee_id = '".$employee_id."' AND type='".$type."' AND a.is_active = 1 AND status = 5 GROUP BY type";
			$query = $this->db->query($sql)->result_array();
			if(sizeof($query) > 0){
				foreach($query as $k => $v){
					if($v["number_of_days"] == 0){
						unset($query[$k]);
					}
				}
			}
			return $query;
		}

        function getMonetization($month,$year,$employee_id){
			$sql = "SELECT * FROM tblleavemanagement WHERE employee_id = '".$employee_id."' AND type = 'monetization' AND status = 5 AND MONTH(date_filed) = '".$month."' AND YEAR(date_filed) = ".$year;
			$query = $this->db->query($sql);
			return $query->result_array();
		}

        function getLeaveDays($month,$year,$employee_id,$type){
			$sql = "	SELECT
							a.*,
							(SELECT dtr.transaction_date FROM tbldtr dtr
							LEFT JOIN tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
							WHERE emp.id = '".$employee_id."' AND dtr.transaction_date = a.days_of_leave
							AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
							AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
							) as match_date
						FROM tblleavemanagementdaysleave a 
						WHERE	(
									(MONTH(days_of_leave) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
									OR
									(MONTH(SUBSTRING_INDEX(days_of_leave,' - ',-1)) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
								)  
						AND (SELECT employee_id FROM tblleavemanagement b WHERE b.id=a.id) = '".$employee_id."'
						AND (SELECT type FROM tblleavemanagement b WHERE b.id=a.id) = '".$type."'
						AND a.is_active = 1 AND (SELECT b.status FROM tblleavemanagement b WHERE b.id=a.id ) = 5";
			$query = $this->db->query($sql)->result_array();
			if(sizeof($query) > 0){
				foreach($query as $k => $v){
					if($v["match_date"] != "" || $v["match_date"] != null){
						unset($query[$k]);
					}
				}
			}
			return $query;
		}

        function getConversionFractions($time_amount,$time_type){
			$query = $this->db->select('*')->from('tblleaveconversionfractions')->where(array('time_amount'=>$time_amount,'time_type'=>$time_type))->get();
			$data = $query->result_array();
			return $data;
		}

		public function getShiftDetails($id){
			$sql = "SELECT DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as scan_no,tblemployees.shift_date_effectivity as shiftDate,tblemployees.* FROM tblemployees WHERE id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function isDTRRequired($id){
			$sql = "SELECT * FROM tblfieldpositions a LEFT JOIN tblemployees b ON a.id = b.position_id WHERE b.id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getShiftHistory($id){
			$query = $this->db->select("*")->from("tblemployeesshifthistory")->where("employee_id", $id)->order_by("previous_date_effectivity","DESC")->get();
			return $query->result_array();
		}

		public function getRegularShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeschedulesweekly a")->join("tbltimekeepingemployeeschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->where("a.is_restday", 0)->get();
			return $query->result_array();
		}

		public function getFlexibleShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeflexibleschedulesweekly a")->join("tbltimekeepingemployeeflexibleschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->where("a.is_restday", 0)->get();
			return $query->result_array();
		}

		
		function countHollidayAttended($year, $month, $emp_shift_days, $employee_id) {
			$emp_shift_days = implode("','",$emp_shift_days);
			$query = $this->db->query("SELECT COUNT(*) as holiday_attended
				FROM tblfieldholidays AS h
				LEFT JOIN tbldtr AS a ON a.transaction_date = h.date
				LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
 				WHERE DAYNAME(date) IN ('".$emp_shift_days."') AND MONTH(date) = '".$month."' AND YEAR(date) = '".$year."' AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM'))
				AND b.id = '".$employee_id."'
				")->row_array();
			
			return $query["holiday_attended"];
		}

	}
?>
