<?php
	class PayslipCollection extends Helper {
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
		  
		function make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self){  
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
		    	tblemployees.*,'.
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
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
			if($self && ((isset($payroll_period_id) && $payroll_period_id != null))){
				$this->db->where('tblemployees.id="'.Helper::get("id").'"');
				$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
			}else{
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
				
				if(isset($employment_status) && $employment_status != null)
					$this->db->where('tblemployees.employment_status != "Active"');
				else
					$this->db->where('tblemployees.employment_status = "Active"');
				if(isset($employee_id) && $employee_id != null)
					$this->db->where('tblemployees.id="'.$employee_id.'"');
				if((isset($pay_basis) && $pay_basis != null))
					$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
				if((isset($payroll_period_id) && $payroll_period_id != null))
					$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
				if(isset($location_id) && $location_id != NULL){
					$this->db->where('tblemployees.location_id="'.$location_id.'"');
				}else if(isset($division_id) && $division_id != NULL){
					$this->db->where('tblemployees.division_id="'.$division_id.'"');
				}else{
					// $this->db->where('1=0');
				}
				$this->db->where($this->table.'.is_active="1"');
				if(isset($_POST["order"])){  
					$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
				}else{  
					$this->db->order_by("tblfielddepartments.department_name,tblemployees.first_name ASC");
				}
			}
		}  
		function make_datatables($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self){  
		    $this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self);  
		    if($_POST["length"] != -1) {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }
		    $query = $this->db->get();
		    return $query->result();  
		} 
		function get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self){  
			$this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self);
			$query = $this->db->get();  
		    return $query->num_rows();  
		}       
		function get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id, $self){  
		    $this->db->select($this->table."*");  
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
			if((isset($payroll_period_id) && $payroll_period_id != null))
				$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
			else{
				$this->db->where('1=0');
			}
			if($self){
				$this->db->where('tblemployees.id="'.Helper::get("id").'"');
			}else{
				if(isset($employment_status) && $employment_status != null)
					$this->db->where('tblemployees.employment_status != "Active"');
				else
					$this->db->where('tblemployees.employment_status = "Active"');
				if(isset($employee_id) && $employee_id != null)
					$this->db->where('tblemployees.id="'.$employee_id.'"');
				if((isset($pay_basis) && $pay_basis != null))
					$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
				if((isset($payroll_period_id) && $payroll_period_id != null))
					$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
				else{
					$this->db->where('1=0');
				}
			}
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
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$data = $this->db->query($sql,array($payroll_period_id))->result_array();
			return $data;
		}

		function getPayrollPeriodCutoffById($id){
			$sql = " SELECT * FROM tblfieldperiodsettingsweekly WHERE is_active = '1' AND id = ?";
			$data = $this->db->query($sql,array($id))->result_array();
			return $data;
		}

		function getPayrollAllowances($payroll_period,$employee_id){
			$sql = " SELECT * FROM tbltransactionspayrollprocessallowances a 
					 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id 
					 WHERE YEAR(a.date_amortization) = YEAR(?) AND MONTH(a.date_amortization) = MONTH(?) AND b.employee_id = ? ";
			$data = $this->db->query($sql,array($payroll_period,$payroll_period,$employee_id))->result_array();
			return $data;
		}

		function getPayroll($id,$employee_id,$payroll_period_id){
		    $this->db->select(
		    	'tblemployees.*,'.
		    	$this->table.'.*,
		    	tblemployees.cut_off_1 as cut1,
		    	tblemployees.cut_off_2 as cut2,
		    	tblemployees.cut_off_3 as cut3,
		    	tblemployees.cut_off_4 as cut4,
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
			if($payroll_period_id != "")
				$this->db->where($this->table.'.payroll_period_id',$payroll_period_id);
			if($employee_id != "")
				$this->db->where($this->table.'.employee_id',$employee_id);
			if($id != "")
				$this->db->where($this->table.'.id',$id);
			$this->db->order_by("tblfielddepartments.department_name,tblemployees.first_name ASC");  
			$result = $this->db->get();
		    return $result->result_array();
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
	}
	
?>