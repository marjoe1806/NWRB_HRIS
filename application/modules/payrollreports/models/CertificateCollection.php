<?php
	class CertificateCollection extends Helper {
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
		var $table = "tblgovernmentcertificates";   
		  var $order_column = array(null,'CAST(emp_number as INT)','last_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.is_active');
		  
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
		  
		function make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id,$certificate_type, $self){  
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
			
		    $this->db->select(
			    'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
                DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
                DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
                DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.*,'.
		    	'tbltransactionsprocesspayroll.*,'.
		    	$this->table.'.*,
		    	DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) AS last_name,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tbltransactionsprocesspayroll",$this->table.".payroll_period_id = tbltransactionsprocesspayroll.payroll_period_id","left");
		    $this->db->join("tblemployees","tbltransactionsprocesspayroll.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
			// $this->db->order_by('tbltransactionsprocesspayroll.rate', 'ASC');
			
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
				$this->db->where($this->table.'.pay_basis="'.$pay_basis.'"');
			if((isset($loans_id) && $loans_id != null))
				$this->db->where($this->table.'.type="'.$loans_id.'"');
			if((isset($sub_loans_id) && $sub_loans_id != null))
				$this->db->where($this->table.'.type="'.$sub_loans_id.'"');
			if(isset($division_id) && $division_id != NULL){
				$this->db->where('tblemployees.division_id="'.$division_id.'"');
			}else{
				// $this->db->where('1=0');
			}
			$this->db->group_by('tblemployees.employee_number');
			if(isset($_POST["order"])){  
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
			}else{  
				$this->db->order_by("DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)");
			}
		}  
		function make_datatables($employee_id,$self,$location_id,$payroll_period_id,$division_id,$employment_status,$loans_id,$sub_loans_id,$pay_basis){  
		    $this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id,$loans_id,$sub_loans_id, $self);  
		    if($_POST["length"] != -1) {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }
		    $query = $this->db->get();
		    return $query->result();  
		} 
		function get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id,$loans_id,$sub_loans_id, $self){  
			$this->make_query($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id,$loans_id,$sub_loans_id, $self);
			$query = $this->db->get();  
		    return $query->num_rows();  
		}       
		function get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$loans_id,$sub_loans_id, $self){  

		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    $this->db->join("tbltransactionsprocesspayroll",$this->table.".payroll_period_id = tbltransactionsprocesspayroll.payroll_period_id","left");
		    $this->db->join("tblemployees","tbltransactionsprocesspayroll.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
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
				// else{
				// 	$this->db->where('1=0');
				// }
			}
		    return $this->db->count_all_results();  
		} 

		function getCertificate($loans_id,$sub_loans_id,$pay_basis,$employee_id){
		    $this->db->select(
		    	'tblemployees.*,'.
		    	'tblemployees.id as emp_id,'.
		    	'tbltransactionsprocesspayroll.*,'.
		    	'tbltransactionsloans.*,'.
		    	'tbltransactionsloansamortization.*,'.
		    	$this->table.'.*,
				tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.period_id,
		    	tblfieldpositions.name AS position_name,
		    	tblfielddepartments.department_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tbltransactionsprocesspayroll",$this->table.".payroll_period_id = tbltransactionsprocesspayroll.payroll_period_id","left");
		    $this->db->join("tbltransactionsloans","tbltransactionsprocesspayroll.employee_id = tbltransactionsloans.employee_id","left");
		    $this->db->join("tbltransactionsloansamortization","tbltransactionsloans.id = tbltransactionsloansamortization.loan_entry_id","left");
		    $this->db->join("tblfieldperiodsettings",$this->table.".payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblemployees","tbltransactionsprocesspayroll.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
			if($employee_id != "")
				$this->db->where('tblemployees.id',$employee_id);
			if((isset($pay_basis) && $pay_basis != null))
				$this->db->where($this->table.'.pay_basis="'.$pay_basis.'"');
			if($loans_id != "")
				$this->db->where($this->table.'.loans_id',$loans_id);
				$this->db->where('tbltransactionsloans.loans_id',$loans_id);
			if($sub_loans_id != "")
				$this->db->where($this->table.'.sub_loans_id',$sub_loans_id);				
				$this->db->where('tbltransactionsloans.sub_loans_id',$sub_loans_id);
			$this->db->group_by($this->table.'.official_receipt_no');
			$this->db->distinct($this->table.'.official_receipt_no', $this->table.'.payroll_period_id');			
			$this->db->order_by($this->table.'.date_posted', 'DESC');  
			if($loans_id == 0)
				$this->db->limit(12);  
			$result = $this->db->get();
		    return $result->result_array();
		}

		//PhilHealth_Certificate
		function getCertificatePhil($loans_id,$sub_loans_id,$pay_basis,$employee_id){
		    $this->db->select(
		    	'tblemployees.*,'.
		    	'tblemployees.id as emp_id,'.
		    	'tbltransactionsprocesspayroll.*,'.
		    	$this->table.'.*,
				tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.period_id,
		    	tblfieldpositions.name AS position_name,
		    	tblfielddepartments.department_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tbltransactionsprocesspayroll",$this->table.".payroll_period_id = tbltransactionsprocesspayroll.payroll_period_id","left");
		    $this->db->join("tblfieldperiodsettings",$this->table.".payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblemployees","tbltransactionsprocesspayroll.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
			if($employee_id != "")
				$this->db->where('tblemployees.id',$employee_id);
			if((isset($pay_basis) && $pay_basis != null))
				$this->db->where($this->table.'.pay_basis="'.$pay_basis.'"');
			if($loans_id != "")
				$this->db->where($this->table.'.loans_id',$loans_id);
			if($sub_loans_id != "")
				$this->db->where($this->table.'.sub_loans_id',$sub_loans_id);
			$this->db->order_by($this->table.'.date_posted DESC');  			
			if($loans_id == 0)
				$this->db->limit(12);  
			$result = $this->db->get();
		    return $result->result_array();
		}
		function getCertificateMP2($loans_id,$sub_loans_id,$pay_basis,$employee_id){
		    $this->db->select(
		    	'tblemployees.*,'.
		    	'tblemployees.id as emp_id,'.
		    	'tbltransactionsprocesspayroll.*,'.
		    	$this->table.'.*,
				tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.period_id,
		    	tblfieldpositions.name AS position_name,
		    	tblfielddepartments.department_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tbltransactionsprocesspayroll",$this->table.".payroll_period_id = tbltransactionsprocesspayroll.payroll_period_id","left");
		    $this->db->join("tblfieldperiodsettings",$this->table.".payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblemployees","tbltransactionsprocesspayroll.employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
			if($employee_id != "")
				$this->db->where('tblemployees.id',$employee_id);
			if((isset($pay_basis) && $pay_basis != null))
				$this->db->where($this->table.'.pay_basis="'.$pay_basis.'"');
			if($loans_id != "")
				$this->db->where($this->table.'.loans_id',$loans_id);
			if($sub_loans_id != "")
				$this->db->where($this->table.'.sub_loans_id',$sub_loans_id);
			$this->db->order_by($this->table.'.date_posted DESC');  			
			if($loans_id == 0)
				$this->db->limit(12);  
			$result = $this->db->get();
		    return $result->result_array();
		}
	}
?>