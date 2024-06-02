<?php
	class PayrollRegisterCollection extends Helper {
		
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		//Fetch
		var $table = "tbltransactionsprocesspayroll";    
		  
		function fetchPayrollRegister($pay_basis,$payroll_period_id,$division_id){
		    $this->db->select( 'tblemployees.*,'. $this->table.'.*, tblfieldpositions.name AS position_name, tblfieldoffices.name AS office_name, tblfielddepartments.department_name, tblfieldlocations.name AS location_name' );  
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left"); 
		    $this->db->where($this->table.'.payroll_period_id',$payroll_period_id);
		    $this->db->where($this->table.'.is_active',1);
		    if(isset($_POST['division_id']) && $_POST['division_id'] != "")
		    	$this->db->where($this->table.'.division_id',$division_id);
		    if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != "")
		    	$this->db->where($this->table.'.pay_basis',$pay_basis);
			$this->db->order_by("tblemployees.salary DESC");  
			$result = $this->db->get();
		    return $result->result_array();
		} 
		//End Fetch
		function fetchPayrollRegisterAll($pay_basis,$payroll_period_id){
		    $this->db->select( 'tblemployees.*,'. $this->table.'.*, tblfieldpositions.name AS position_name, tblfieldoffices.name AS office_name, tblfielddepartments.department_name, tblfieldlocations.name AS location_name' );  
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left"); 
		    $this->db->where($this->table.'.payroll_period_id',$payroll_period_id);
		    $this->db->where($this->table.'.is_active',1);
		    if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != "")
		    	$this->db->where($this->table.'.pay_basis',$pay_basis);
			$this->db->order_by("tblemployees.salary DESC");  
			$result = $this->db->get();
		    return $result->result_array();
		} 
		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
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
				DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)," ",			
				DECRYPTER(b.extension,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (1,2,3,4) ORDER BY a.id')->result_array();
			return $sign;
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

		//Fetch Computed Payrolls
		function getAmortizedAllowances($employee_id,$payroll_period_id){
			$sql = " SELECT *,c.allowance_name FROM tbltransactionspayrollprocessallowances a
					 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id
					 LEFT JOIN tblfieldallowances c ON b.allowance_id = c.id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
			return $loans;
		}
		function getAmortizedOtherEarnings($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsotherearningsamortization a
					 LEFT JOIN tbltransactionsotherearnings b ON a.other_earning_entry_id = b.id
					 LEFT JOIN tblfieldotherearnings c ON c.id = b.earning_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
			return $loans;
		}
		function getAmortizedOtherDeductions($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsotherdeductionsamortization a
					 LEFT JOIN tbltransactionsotherdeductions b ON a.other_deduction_entry_id = b.id
					 LEFT JOIN tblfieldotherdeductions c ON c.id = b.deduction_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
			$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
			return $loans;
		}
		function getAmortizedLoans($employee_id,$payroll_period_id){
			$sql = " SELECT a.*,b.*,c.code AS code_sub,c.description AS desc_sub, d.code AS code_loan,d.description AS desc_loan FROM tbltransactionsloansamortization a
					 LEFT JOIN tbltransactionsloans b ON a.loan_entry_id = b.id
					 LEFT JOIN tblfieldloanssub c ON c.id = b.sub_loans_id
					 LEFT JOIN tblfieldloans d ON d.id = c.loan_id
					 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? 
					 ORDER BY d.code ASC";
			$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
			return $loans;
		}
		
		function getTax($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tblimportprocesspayroll 
					 WHERE employee_id = ? AND payroll_period_id = ?";
			$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
			return $loans;
		}

		function getUTV2($employee_id,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionsprocesspayrolldeductionss
					 WHERE employee_id = ? AND payroll_period_id = ? ";
			$query = $this->db->query($sql,array($employee_id,$payroll_period_id));
			$loans = $query->row_array();
			return $loans;
		}

		function payrollinfo($emp_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			
			$message = "No data available.";
			$sql = "SELECT mp2_contribution FROM tblemployees where id = ? ";
			$query = $this->db->query($sql,array($emp_id));
			$datas = $query->result_array();
			$data['data'] = $datas;
			if(sizeof($datas) > 0){

				return $data;
			}
			
			return false; 
		}

		function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll register failed to update.";
			
			$id = $params['id'];
			if($params['type'] == 'col1' ){
				$params2["col1_value"] = $params['value'];
			}else if($params['type'] == 'col2' ){
				$params2["col2_value"] = $params['value'];
			}
			
			$this->db->where('id', $id);
			if ($this->db->update($this->table,$params2) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated payroll register.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;

		}
		function updateRowsHeader($params){
			$params["name"] = $params['value'];
			unset($params['value']);
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll register failed to update."; 
			$sqldtr = 'SELECT * FROM tbltransactionspayrollregisterheaders WHERE pay_basis = "'.$params['pay_basis'].'" AND period_id = "'.$params['period_id'].'"  AND division_id = "'.$params['division_id'].'" AND type = "'.$params['type'].'"';
			$querydtr = $this->db->query($sqldtr);
			if($querydtr->num_rows() > 0){
				$this->db->where('pay_basis', $params['pay_basis']);
				$this->db->where('period_id', $params['period_id']);
				$this->db->where('division_id', $params['division_id']);
				$this->db->where('type', $params['type']);
				if ($this->db->update('tbltransactionspayrollregisterheaders',$params) === FALSE){
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}else { 
					$code = "0";
					$message = "Successfully updated payroll register.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;	
				}
			}else{
				if ($this->db->insert('tbltransactionspayrollregisterheaders',$params) === FALSE){
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}else {
					$code = "0";
					$message = "Successfully updated payroll register.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;	
				}
			}
			return false;

		}
		function deleteRowsHeader($pay_basis, $period, $division , $type){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll register failed to update."; 

			$this->db->where('pay_basis', $pay_basis);
			$this->db->where('period_id', $period);
			$this->db->where('division_id', $division);
			$this->db->where('type', $type);

			if ($this->db->delete('tbltransactionspayrollregisterheaders') === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}else { 
				$code = "0";
				$message = "Successfully deleted payroll register header.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		 }

		function fetchHeaders1($pay_basis,$payroll_period_id,$division_id){
			$sql = " SELECT * FROM tbltransactionspayrollregisterheaders WHERE pay_basis = ? AND period_id = ? AND division_id = ? AND type='header_1'";
			$query = $this->db->query($sql,array($pay_basis,$payroll_period_id,$division_id));
			$data = $query->result_array();
			return $data;
		}

		function fetchHeaders2($pay_basis,$payroll_period_id,$division_id){
			$sql = " SELECT * FROM tbltransactionspayrollregisterheaders WHERE pay_basis = ? AND period_id = ? AND division_id = ? AND type='header_2'";
			$query = $this->db->query($sql,array($pay_basis,$payroll_period_id,$division_id));
			$data = $query->result_array();
			return $data;
		}
		function fetchAllHeaders1($pay_basis,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionspayrollregisterheaders WHERE pay_basis = ? AND period_id = ? AND type='header_1'";
			$query = $this->db->query($sql,array($pay_basis,$payroll_period_id));
			$data = $query->result_array();
			return $data;
		}

		function fetchAllHeaders2($pay_basis,$payroll_period_id){
			$sql = " SELECT * FROM tbltransactionspayrollregisterheaders WHERE pay_basis = ? AND period_id = ? AND type='header_2'";
			$query = $this->db->query($sql,array($pay_basis,$payroll_period_id));
			$data = $query->result_array();
			return $data;
		}

		function getpayrolltransaction($payroll_period_id, $employee_id){
			$sql = " SELECT * FROM tbltransactionsprocesspayroll a
					 WHERE a.payroll_period_id = ? AND a.employee_id = ? ";
			$query = $this->db->query($sql,array($payroll_period_id, $employee_id)); 
			$data = $query->result_array();
			return $data;
		}

		function updatePayrollCol1($id){
			$sql = "UPDATE tbltransactionsprocesspayroll SET col1_value = '0.0' WHERE id = ? ";
			$query = $this->db->query($sql,array($id));
		}
		function updatePayrollCol2($id){
			$sql = "UPDATE tbltransactionsprocesspayroll SET col2_value = '0.0' WHERE id = ? ";
			$query = $this->db->query($sql,array($id));
		}
	}
?> 