<?php
	class RemittanceSummaryCollection extends Helper {
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
    	var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblemployees.salary','tblemployees.contract','');

		public function getColumns(){
    	$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		public function getEmployeeList($remittance_type,$pay_basis,$division_id,$location,$per,$from,$to,$payroll_period_id,$payroll_grouping_id) {
			$rem = explode(",", $remittance_type);
			$loanid = $rem[0];
			$subloanid = $rem[1];
			//Per payroll_grouping_id
			if($loanid==1 || $loanid==2 || $loanid==3 || $loanid==4){
				if($per == 0){
					if($division_id == "*"){
						$select = ",c.code";
						$params = array($subloanid,$payroll_period_id,$pay_basis);
						$where = "";
						$group_by = "c.code";
						$order_by = "c.code";
					}else{
						$select = ",c.code,d.employee_number,d.employee_id_number,d.first_name,d.middle_name,d.last_name,g.name AS position_name,d.id ";
						if(isset($division_id) && $division_id != "") {
							$params = array($subloanid,$payroll_period_id,$pay_basis,$division_id);
							$where = " AND b.division_id = ? ";
						}else{
							$params = array($subloanid,$payroll_period_id,$pay_basis);
						}							
						$group_by = "d.id";
						$order_by = "d.id";
					}
				}
				$sql = "SELECT SUM(a.amount) AS amount, e.code AS loan, f.code AS subloan $select  FROM tbltransactionsloansamortization a 
						LEFT JOIN tbltransactionsloans b ON b.id = a.loan_entry_id 
						LEFT JOIN tblfielddepartments c ON c.id = b.division_id
						LEFT JOIN tblemployees d ON d.id = b.employee_id
						LEFT JOIN tblfieldloans e ON e.id = b.loans_id
						LEFT JOIN tblfieldloanssub f ON f.id = b.sub_loans_id
						LEFT JOIN tblfieldpositions g ON g.id = d.position_id
						WHERE a.is_active = 1 AND b.sub_loans_id = ? AND a.payroll_period_id = ? AND d.pay_basis = ? $where
						GROUP BY $group_by ORDER BY $order_by";
				$query = $this->db->query($sql,$params);
				// print_r($this->db->last_query());
				return $query->result_array();	
			}
			if($loanid == 9998 || $loanid == 9999){
				if($loanid == 9998){
					if($subloanid == 1){
						$amounts = "'DAMAYAN' AS loan,SUM(a.damayan_amt) AS amount";
					}
					else if($subloanid == 2){
						$amounts = "'E.C.C' AS loan,SUM(a.acpcea_amt) AS amount";
					}
					else if($subloanid == 3){
						$amounts = "'GSIS' AS loan,IFNULL(SUM(a.sss_gsis_amt),0) as amountPShare,IFNULL(SUM(a.sss_gsis_amt_employer),0) as amountGShare, (IFNULL(SUM(a.sss_gsis_amt),0) + IFNULL(SUM(a.sss_gsis_amt_employer),0)) as amount";
					}
					else if($subloanid == 4){
						$amounts = "'PAGIBIG' AS loan,IFNULL(SUM(a.pagibig_amt),0) as amountPShare,IFNULL(SUM(a.pagibig_amt_employer),0) as amountGShare, (IFNULL(SUM(a.pagibig_amt),0) + IFNULL(SUM(a.pagibig_amt_employer),0)) as amount";
					}
					else if($subloanid == 5){
						$amounts = "'PERA' AS loan,SUM(a.pera_amt) AS amount";
					}
					else if($subloanid == 6){
						$amounts = "'PHILHEALTH' AS loan,IFNULL(SUM(a.philhealth_amt),0) as amountPShare,IFNULL(SUM(a.philhealth_amt_employer),0) as amountGShare, (IFNULL(SUM(a.philhealth_amt),0) + IFNULL(SUM(a.philhealth_amt_employer),0)) as amount";
					}
					else if($subloanid == 39){
						$amounts = "'LANDBANK' AS loan,SUM(a.damayan_amt) AS amount";
					}
				}else if($loanid == 9999){
					if($subloanid == 2){
						$amounts = "'GROSS PAY' AS loan,SUM(a.gross_pay) AS amount";
					}else if($subloanid == 4){
						$amounts = "'Net Pay 50%' AS loan,SUM(a.net_pay) AS amount";
					}else if($subloanid == 5){
						$amounts = "'OVERPAYMENT' AS loan,SUM(a.total_other_deduct_amt) AS amount";
					}else if($subloanid == 7){
						$amounts = "'TOTAL DEDUCTION' AS loan,SUM(a.total_deduct_amt) AS amount";
					}else if($subloanid == 8){
						$amounts = "'UNDER PAYMENT' AS loan,SUM(a.total_other_earning_amt) AS amount";
					}else if($subloanid == 9){
						$amounts = "'WITHHOLDING TAX' AS loan, SUM(a.wh_tax_amt) AS amount, SUM(a.total_other_deduct_amt) AS otherAmount";
					}
				}
				if($per == 0){
					if($payroll_grouping_id == "*"){
						$select = ",b.code";
						if(isset($division_id) && $division_id != "") {
							$params = array($payroll_period_id,$pay_basis,$division_id);
							$where = " AND d.division_id = ? ";
						}else{
							$params = array($payroll_period_id,$pay_basis);
							$where = "";
						}
						// $group_by = "b.code";
						// $order_by = "b.code";
						$group_by = "d.id";
						$order_by = "d.id";
					}
					else{
						$select = ",d.employee_number,d.employee_id_number,d.first_name,d.middle_name,d.last_name,g.name AS position_name,d.id ";
						if(isset($division_id) && $division_id != "") {
							$params = array($payroll_period_id,$pay_basis,$division_id);
							$where = " AND d.division_id = ? ";
						}else{
							$params = array($payroll_period_id,$pay_basis);
						}
						$group_by = "d.id";
						$order_by = "d.id";
					}
				}
				$sql = "SELECT $amounts $select FROM tbltransactionsprocesspayroll a
						LEFT JOIN tblemployees d ON d.id = a.employee_id
						LEFT JOIN tblfieldlocations c ON c.id = d.location_id
						LEFT JOIN tblfieldpositions g ON g.id = d.position_id
						WHERE a.is_active = 1 AND a.payroll_period_id = ? AND d.pay_basis = ? $where
						GROUP BY $group_by
						ORDER BY $order_by";
				$query = $this->db->query($sql,$params);
				// print_r($this->db->last_query()); die();
				return $query->result_array();	
			}
		}

		public function getEmployeeColumns(){
			$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		public function getpayroll_grouping_ids(){
			$res = array();
			try{
				$this->db->select("*")->from("tblfieldpayrollgrouping");
				$query = $this->db->get();
				if($query -> num_rows() > 0){
					$res = array("Code"=>0,"Message"=>"Successful data fetch.","Data"=>$query->result_array());
				}else {
					$res = array("Code"=>1,"Message"=>"No available data.","Data"=>[]);
				}
				return $res;
			} catch(Exception $e){
				$res = array("Code"=>2,"Message"=>"System is Busy.","Data"=>[]);
				return $res;
			}
		}
	
		public function getLocations(){
			$res = array();
			try{
				$this->db->select("*")->from("tblfieldlocations");
				$query = $this->db->get();
				if($query -> num_rows() > 0){
					$res = array("Code"=>0,"Message"=>"Successful data fetch.","Data"=>$query->result_array());
				}else {
					$res = array("Code"=>1,"Message"=>"No available data.","Data"=>[]);
				}
				return $res;
			} catch(Exception $e){
				$res = array("Code"=>2,"Message"=>"System is Busy.","Data"=>[]);
				return $res;
			}
		}
	
		public function getPayrollGrouping(){
			$res = array();
			try{
				$this->db->select("*")->from("tblfieldpayrollgrouping");
				$query = $this->db->get();
				if($query -> num_rows() > 0){
					$res = array("Code"=>0,"Message"=>"Successful data fetch.","Data"=>$query->result_array());
				}else {
					$res = array("Code"=>1,"Message"=>"No available data.","Data"=>[]);
				}
				return $res;
			} catch(Exception $e){
				$res = array("Code"=>2,"Message"=>"System is Busy.","Data"=>[]);
				return $res;
			}
		}

	public function getLoans(){
        $res = array();
        try{
            $this->db->select("a.id as loanId, a.code as loanCode, b.id as subloanId, b.code as subloanCode")->from("tblfieldloans a");
            $this->db->join("tblfieldloanssub b","a.id = b.loan_id");
            $this->db->where("a.is_active",1);
            $this->db->where("b.is_active",1);
            $this->db->order_by("a.id","asc");
            $query = $this->db->get();
            if($query -> num_rows() > 0){
                $res = array("Code"=>0,"Message"=>"Successful data fetch.","Data"=>$query->result_array());
            }else {
                $res = array("Code"=>1,"Message"=>"No available data.","Data"=>[]);
            }
            return $res;
        } catch(Exception $e){
            $res = array("Code"=>2,"Message"=>"System is Busy.","Data"=>[]);
            return $res;
        }
	}

	public function getDivisions(){
        $res = array();
        try{
            $this->db->select("*")->from("tblfielddepartments");
            $query = $this->db->get();
            if($query -> num_rows() > 0){
                $res = array("Code"=>0,"Message"=>"Successful data fetch.","Data"=>$query->result_array());
            }else {
                $res = array("Code"=>1,"Message"=>"No available data.","Data"=>[]);
            }
            return $res;
        } catch(Exception $e){
            $res = array("Code"=>2,"Message"=>"System is Busy.","Data"=>[]);
            return $res;
        }
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
			$this->select_column[] = 'tblfieldpayrollgrouping.payroll_grouping_id_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfieldpayrollgrouping.payroll_grouping_id_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfieldpayrollgrouping","tblemployees.payroll_grouping_id = tblfieldpayrollgrouping.id","left");
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
			$this->select_column[] = 'tblfieldpayrollgrouping.payroll_grouping_id_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfieldpayrollgrouping.payroll_grouping_id_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions",$this->table.".position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies",$this->table.".agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices",$this->table.".office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfieldpayrollgrouping",$this->table.".payroll_grouping_id = tblfieldpayrollgrouping.id","left");
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

		function make_datatables_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){
			$result = $this->make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id);
			return $result;
		}


		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id) {
			$dtr = array();
			$payroll_date = explode("-", $payroll_period);
			$no_of_days = date('t',strtotime($payroll_period));
			$payroll_dates = $this->RemittanceSummaryCollection->getPayrollPeriodById($payroll_period_id);
			if(isset($payroll_dates) && sizeof($payroll_dates) > 0){
				$start = explode("-", $payroll_dates[0]['start_date']);
				$end = explode("-", $payroll_dates[0]['end_date']);
			}

			for ($day = $start[2]; $day <= $end[2]; $day++) {
				$current_day = $payroll_date[0] . '-' . $payroll_date[1] . '-' . (($day > 9) ? $day : '0'. $day);
				$dtr['records'][$current_day] = $this->getAttendanceByWorkingDays($current_day,$employee_number);
			}
			$dtr['employee'] = $this->getEmployeeById($employee_id);
			$dtr['details'] = $this->getOfficeById($dtr['employee'][0]['office_id']);
			$dtr['payroll_period'][0] = $payroll_period;
			$dtr['payroll_period'][1] = isset($payroll_dates[0]['start_date']) ? $payroll_dates[0]['start_date'] : null;
			$dtr['payroll_period'][2] = isset($payroll_dates[0]['end_date']) ? $payroll_dates[0]['end_date'] : null;
			return $dtr;

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

	function getpayroll_grouping_idById($payroll_grouping_id) {
		$params = array($payroll_grouping_id);
		$sql = "SELECT * FROM tblfieldpayrollgrouping WHERE id = ? AND is_active = 1";
		$query = $this->db->query($sql, $params);
		$dept = $query->result_array();
		return $dept;
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

	function make_datatables_summary_all($pay_basis, $payroll_period, $payroll_period_id){
		// var_dump($pay_basis, $payroll_period, $payroll_period_id); die();
		// $this->getEmployeeById($employee_id)
		// $all_employees = array();
		$all_employees = $this->getAllEmployeeDetails($pay_basis);
		foreach ($all_employees as $k => $employee) {
			if(isset($employee) && sizeof($employee) > 0) {
				$all_employees[$k]['first_name'] = $this->Helper->decrypt($all_employees[$k]['first_name'], $employee['employee_id']);
				$all_employees[$k]['middle_name'] = $this->Helper->decrypt($employee['middle_name'], $employee['employee_id']);
				$all_employees[$k]['last_name'] = $this->Helper->decrypt($employee['last_name'], $employee['employee_id']);
				$all_employees[$k]['employee_number'] = $this->Helper->decrypt($employee['employee_number'], $employee['employee_id']);
				$all_employees[$k]['employee_id_number'] = $this->Helper->decrypt($employee['employee_id_number'], $employee['employee_id']);
			}
		}
		return $all_employees;
	}

	function getAllEmployeeDetails($params) {
		$sql = "SELECT * FROM tblemployees LEFT JOIN tbltransactionsprocesspayroll ON tblemployees.id = tbltransactionsprocesspayroll.employee_id WHERE  tblemployees.pay_basis = ? AND tblemployees.is_active = 1 AND tbltransactionsprocesspayroll.is_active = 1";
		$query = $this->db->query($sql, $params);
		$employee = $query->result_array();
		return $employee;
	}
	function getSignatoriesHead($payroll_grouping_id){
		$sql = " SELECT * FROM tblfieldheadsignatories WHERE is_active = 1 AND payroll_grouping_id = ?";
		$query = $this->db->query($sql,array($payroll_grouping_id));
		$data = $query->result_array();
		return $data;
	}
	function getPayrollGroupingById($payroll_period_id){
		$sql = " SELECT * FROM tblfieldpayrollgrouping WHERE is_active = '1' AND id = ?";
		$query = $this->db->query($sql,array($payroll_period_id));
		$data = $query->result_array();
		return $data;
	}
		
	function getSignatories(){
		$sign = $this->db->query('SELECT
		a.*,
		CONCAT(
			DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
			LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
			DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
		FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
		LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (7,11) ORDER BY a.id')->result_array();
		return $sign;
	}

}
?>