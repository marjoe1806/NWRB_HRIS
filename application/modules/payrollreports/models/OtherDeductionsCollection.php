<?php
	class OtherDeductionsCollection extends Helper {
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
		var $table = "tbltransactionsotherdeductionsamortization";   
      	var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfielddepartments.department_name','tbltransactionsotherdeductionsamortization.amount','tbltransactionsotherdeductionsamortization.balance');
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
		function make_query($pay_basis,$payroll_period_id){
			$employee_columns = $this->getEmployeeColumns();
			foreach ($employee_columns as $kemp => $vemp) {
				if ($vemp['COLUMN_NAME'] != "id" || $vemp['COLUMN_NAME'] != "modified_by" || $vemp['COLUMN_NAME'] != "is_active" || $vemp['date_created'] != "id") {
					$this->select_column[] = 'tblemployees.'.$vemp['COLUMN_NAME'];
				}
			}
			$this->select_column[] = 'tblfieldotherdeductions.deduct_code';
			$this->select_column[] = 'tblfieldotherdeductions.description';
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	'tblemployees.*,
		    	tbltransactionsotherdeductions.*,'.
		    	$this->table.'.*,
		    	tblfieldotherdeductions.deduct_code AS deduct_code,
		    	tblfieldotherdeductions.description AS deduct_description,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldperiodsettings.period_id,
		    	tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.start_date,
		    	tblfieldperiodsettings.end_date,
		    	tblfieldfundsources.fund_source,
		    	tblfieldbudgetclassifications.code AS budget_classification_code,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tbltransactionsotherdeductions",$this->table.".other_deduction_entry_id = tbltransactionsotherdeductions.id","left");
		    $this->db->join("tblemployees","tbltransactionsotherdeductions.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldotherdeductions","tbltransactionsotherdeductions.deduction_id = tblfieldotherdeductions.id","left");
		    $this->db->join("tblfieldperiodsettings","tbltransactionsotherdeductions.payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		   
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
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
		    if(isset($employee_id) && $employee_id != null)
		    	$this->db->where('tblemployees.id="'.$employee_id.'"');
		    if((isset($pay_basis) && $pay_basis != null))
		    	$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
		    if((isset($payroll_period_id) && $payroll_period_id != null))
		    	$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
		    else{
		    	$this->db->where('1=0');
		    }
		    $this->db->where($this->table.'.is_active="1"');
		    if(isset($_POST["order"]))  
		    {  
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
		    }  
		    else  
		    {  
		          $this->db->order_by('tblfieldbudgetclassifications.code', 'ASC');  
		    }  
		}  
		function make_datatables($pay_basis,$payroll_period_id){  
		    $this->make_query($pay_basis,$payroll_period_id);  
		    if($_POST["length"] != -1)  
		    {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }  

		    $query = $this->db->get();
		    return $query->result();  
		} 
		function get_filtered_data($pay_basis,$payroll_period_id){  
		     $this->make_query($pay_basis,$payroll_period_id); 
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}       
		function get_all_data($pay_basis,$payroll_period_id)  
		{  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    $this->db->join("tbltransactionsotherdeductions",$this->table.".other_deduction_entry_id = tbltransactionsotherdeductions.id","left");
		    $this->db->join("tblemployees","tbltransactionsotherdeductions.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldotherdeductions","tbltransactionsotherdeductions.deduction_id = tblfieldotherdeductions.id","left");
		    $this->db->join("tblfieldperiodsettings","tbltransactionsotherdeductions.payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		    if(isset($employment_status) && $employment_status != null)
		    	$this->db->where('tblemployees.employment_status != "Active"');
		    else
		    	$this->db->where('tblemployees.employment_status = "Active"');
		    if((isset($pay_basis) && $pay_basis != null))
		    	$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
		    if((isset($payroll_period_id) && $payroll_period_id != null))
		    	$this->db->where($this->table.'.payroll_period_id="'.$payroll_period_id.'"');
		    else{
		    	$this->db->where('1=0');
		    }
		    $this->db->where($this->table.'.is_active="1"');
		    return $this->db->count_all_results();  
		} 
		function fetchOtherDeductions($pay_basis,$payroll_period_id){
			$this->make_query($pay_basis,$payroll_period_id);
		    $query = $this->db->get();
		    return $query->result_array();
		} 
		//End Fetch
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
	}
?>