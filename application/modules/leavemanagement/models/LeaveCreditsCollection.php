<?php
	class LeaveCreditsCollection extends Helper {
		var $select_column = null;   
		//Fetch
		var $table = "tbltransactionsprocesspayroll";   
		var $order_column = array(
			'',
			"CAST(DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id) as INT)",
			"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
			'tblfieldpositions.name',
			'tblfielddepartments.department_name',
			'tblemployees.salary'
		);
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				// $this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}
		  
      	public function getColumns(){
      		// $sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
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
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.last_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.extension';
			$this->select_column[] = 'tblemployees.employee_number';
			$this->select_column[] = 'tblemployees.employee_id_number';
			$this->select_column[] = 'tblemployees.salary';
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->db->select();
			// '.$this->table.'.*,
		    $this->db->select(
		    	'tblemployees.*,
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
		    // $this->db->join($this->table,"tblemployees.id = ".$this->table.".employee_id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		   
			$this->db->where('tblemployees.employment_status = "Active"');
		   
		    if(isset($_POST["search"]["value"])) {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					
					// if($value == "created_by")  {
		     			// $this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
						$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  
					}
					else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		}
		     		
		     	}
		        $this->db->group_end(); 
		    }

		    // if(isset($_GET['Status']) && $_GET['Status'] != null)
		    // 	$this->db->where($this->table.'.status = "'.$_GET['Status'].'"');

			//FILTERING BY DIVISION_ID
			if(isset($division_id) && $division_id != NULL)
		    	$this->db->where('tblemployees.division_id="'.$division_id.'"');

		    if(isset($_POST["order"])) {  	
		    	$this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		    } else { 
		    	  // $this->db->limit(1,0);  
		          $this->db->order_by("tblemployees".".start_date","DESC");

		    }
		}
		
		function make_datatables($division_id){  
		    $this->make_query($division_id);  
			if($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);

			$query = $this->db->get();
		    return $query->result();  
		}

		function get_filtered_data($division_id){  
		     $this->make_query($division_id);
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		function get_all_data() {  
		    // $this->db->select($this->table."*");  
		    // $this->db->from($this->table);
		    // $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");

		    $this->db->select("tblemployees.*");  
		    $this->db->from("tblemployees");  

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
			$emp = $this->db->select("a.*,b.name AS position_name, b.id,c.vl,c.sl,c.total as leaveCreditsTotal,d.department_name as dpt_name,d.code as dpt_code")->from("tblemployees a")
			->join("tblfieldpositions b","a.position_id = b.id","left")->join("tblleavebalance c","a.id = c.id","left")->join("tblfielddepartments d","a.division_id = d.id","left")
			->where("a.id",$id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesworkexperience")->where("employee_id",$id)->get()->result_array();
			return array("employee"=>$emp, "experience"=> $empexp);
		}
		
		function getSignatories(){
			$sign = $this->db->query('SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
				LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
				DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (16, 17, 18) ORDER BY a.id')->result_array();
			return $sign;
		}
	}
?>