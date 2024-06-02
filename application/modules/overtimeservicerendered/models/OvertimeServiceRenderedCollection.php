<?php
	class OvertimeServiceRenderedCollection extends Helper {
		var $select_column = null;   
		//Fetch
		var $table = "tbltransactionsprocesspayroll";   
		var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.contract','');
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
			//var_dump($this->select_column);die();

		}
		  
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
		  
		function make_query($division_id) {
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	'tblemployees.*,'.
				$this->table.'.*,
				tblemployees.id as salt,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );  
		    $this->db->from("tblemployees");  
		    $this->db->join($this->table,"tblemployees.id = ".$this->table.".employee_id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		   
			$this->db->where('tblemployees.employment_status = "Active"');
			if(isset($division_id) && $division_id != NULL)
		    	$this->db->where('tblemployees.division_id="'.$division_id.'"');
		    else
				$this->db->where('1=0');
			if(isset($_POST["order"]))
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			else
				$this->db->order_by("tblfielddepartments.department_name,tblemployees.first_name ASC");
		    $this->db->group_by('tblemployees.id');
		}
		
		function make_datatables($division_id){  
		    $this->make_query($division_id);  
			if($_POST["length"] != -1)
				$this->db->limit($_POST['length'], $_POST['start']);

			$query = $this->db->get();
			// var_dump(json_encode($query->result_array()));die();
		    return $query->result();  
		}

		function get_filtered_data($division_id){  
		     $this->make_query($division_id);
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		function get_all_data() {  
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
		    $this->db->where('tblemployees.employment_status = "Active"');	
		    return $this->db->count_all_results();  
		}

		function get_service_record($id){
			$emp = $this->db->select("*")->from("tblemployees")->where("id",$id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesworkexperience")->where("employee_id",$id)->get()->result_array();
			return array("employee"=>$emp, "experience"=> $empexp);
		}
	}
?>