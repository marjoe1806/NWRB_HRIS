<?php
	class SpecialReportsCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
			//var_dump($this->select_column);die();

		}
		//Fetch
		var $table = "tbltransactionsbonus";   
      	var $order_column = array('tblfieldbudgetclassifications.code','tblemployees.first_name','tblfieldpositions.name','tblemployees.employee_number','tblemployees.salary','tbltransactionsprocesspayroll.net_pay');
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
		function fetchSpecialReports($pay_basis,$year,$bonus_type,$division_id,$payroll_grouping_id,$uses_atm,$is_initial_salary,$payroll_type){
			  
		    $this->db->select(
		    	'tblemployees.*,'.
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name'
		    );  
		    // var_dump($bonus_type);die();
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->where($this->table.'.year',$year);
		    $this->db->where($this->table.'.is_active',1);
		    if(isset($_POST['division_id']) && $_POST['division_id'] != "")
		    	$this->db->where($this->table.'.division_id',$division_id);
		    if(isset($_POST['bonus_type']) && $_POST['bonus_type'] != "")
		    	$this->db->where($this->table.'.bonus_type',$bonus_type);
		    if(isset($_POST['year']) && $_POST['year'] != "")
		    	$this->db->where($this->table.'.year',$year);
			$this->db->order_by("DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id) ASC");  
			$result = $this->db->get();
			// var_dump($this->db->last_query());die(); 
		    return $result->result_array();
		} 
		//End Fetch
		function getPayrollPeriodById($year){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($year));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollGroupingById($year){
			$sql = " SELECT * FROM tblfieldpayrollgrouping WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($year));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollPeriodCutoffByPayrollId($id){
			$sql = " SELECT * FROM tblfieldperiodsettingsweekly WHERE is_active = '1' AND year = ? ORDER BY start_date ASC";
			$query = $this->db->query($sql,array($id));
			$data = $query->result_array();
			return $data;
		}
		
		function getSignatories(){
			$sign = $this->db->query('SELECT
			a.*, 
			CONCAT(
				DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
				LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
				DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)," ",DECRYPTER(b.extension,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (8,9,14,15) ORDER BY a.id')->result_array();
			return $sign;
		}

		function getSign($division_id){
			$sign = $this->db->query("SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
				LEFT(DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id),1),'. ',
				DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.extension,'sunev8clt1234567890',b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE d.id in ($division_id) and a.signatory in ('Special Payroll - Box A')")->result_array();
			return $sign;
		}
		function getSign2($division_id){
			$sign = $this->db->query("SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
				LEFT(DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id),1),'. ',
				DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.extension,'sunev8clt1234567890',b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.signatory in ('Special Payroll - Box A DEDO')")->result_array();
			return $sign;
		}


		//Fetch Computed Payrolls
		function getAmortizedAllowances($employee_id,$payroll_period_id){
			$sql = " SELECT *,c.allowance_name FROM tbltransactionspayrollprocessallowances a
					 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id
					 LEFT JOIN tblfieldallowances c ON b.allowance_id = c.id
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
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? 
					 ORDER BY d.code ASC";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->result_array();
			return $loans;
		}

		function getOvertime($division, $month, $year, $pay_basis){
			$sql = "SELECT a.*, 
						   DECRYPTER(c.employee_number, 'sunev8clt1234567890', c.id) as emp_id, 
						   b.name as position_name,
						   d.department_name as department_name, CURRENT_TIMESTAMP as cur_timestamp,
						   CONCAT(
						DECRYPTER(c.first_name,'sunev8clt1234567890',c.id),' ',
						LEFT(DECRYPTER(c.middle_name,'sunev8clt1234567890',c.id),1),'. ',
						DECRYPTER(c.last_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.extension,'sunev8clt1234567890',c.id)) as employee_name,
						c.salary as salary
					FROM tblovertimerequest a
					LEFT JOIN tblfieldpositions b ON a.position_id = b.id
					LEFT JOIN tblemployees c ON a.employee_id = c.id
					LEFT JOIN tblfielddepartments d ON a.division_id = d.id WHERE d.id = ? and MONTH(transaction_date) = ? and YEAR(transaction_date) = ? and purpose = 'paid' and c.pay_basis = ? GROUP BY employee_id";
					$query = $this->db->query($sql,array($division, $month, $year, $pay_basis));
					$loans = $query->result_array();
					return $loans;
		}

		function getOvertimePer($employee_id, $month, $year){
			$sql = "SELECT a.*, 
					DECRYPTER(c.employee_number, 'sunev8clt1234567890', c.id) as emp_id, 
					b.name as position_name,
					d.department_name as department_name, CURRENT_TIMESTAMP as cur_timestamp,
					CONCAT(
				DECRYPTER(c.first_name,'sunev8clt1234567890',c.id),' ',
				LEFT(DECRYPTER(c.middle_name,'sunev8clt1234567890',c.id),1),'. ',
				DECRYPTER(c.last_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.extension,'sunev8clt1234567890',c.id)) as employee_name,
				c.salary as salary
			FROM tblovertimerequest a
			LEFT JOIN tblfieldpositions b ON a.position_id = b.id
			LEFT JOIN tblemployees c ON a.employee_id = c.id
			LEFT JOIN tblfielddepartments d ON a.division_id = d.id WHERE a.employee_id = ? and MONTH(transaction_date) = ? and YEAR(transaction_date) = ? and purpose = 'paid'";
			$query = $this->db->query($sql,array($employee_id, $month, $year));
			$loans = $query->result_array();
			return $loans;
		}
		function getMonthNumber($month, $year){
			$sql = "SELECT transaction_date FROM tblovertimerequest WHERE MONTH(transaction_date) = ? and YEAR(transaction_date) = ? ORDER BY transaction_date ASC";	
			
			$query = $this->db->query($sql,array($month, $year));
			$dtr = $query->result_array();
			return $dtr;
		}
		function getDtr($emp_id, $month){
			$sql = "SELECT * FROM tbldtr WHERE scanning_no = ? and transaction_date = ?";	
			
			$query = $this->db->query($sql,array($emp_id, $month));
			$dtr = $query->result_array();
			return $dtr;
		}
		
		function dtr(){

			$query = $this->db->query("SELECT * FROM tbldtr ");

			return $query;
		}
		function getSignatoriesA($division_name){
			$sql ="SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
				LEFT(DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id),1),'. ',
				DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.extension,'sunev8clt1234567890',b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.signatory = ?";
			$loans = $this->db->query($sql,array($division_name))->result_array();
			return $loans;
		}

		function divisionName($division_name){

			$sql = "SELECT * FROM tblfielddepartments WHERE code = ? ";	
			
			$query = $this->db->query($sql,array($division_name));
			$dtr = $query->result_array();
			return $dtr;
		}
		function bonusName($bonus_name){

			$sql = "SELECT * FROM tblfieldbonuses WHERE id = ? ";	
			
			$query = $this->db->query($sql,array($bonus_name));
			$dtr = $query->result_array();
			return $dtr;
		}
	}
?>
