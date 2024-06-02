<?php
	class LeaveTrackingCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value)
				$this->select_column[] = $this->table.'.'.$value->COLUMN_NAME;
		}

		//Fetch
		var $table = "tblleavemanagement";   
		var $order_column = array('','last_name','date_filed','type','offset_date_effectivity','status_name','remarks');
		var $order_column2 = array('','last_name','filing_date','type','inclusive_dates','description','remarks','');
		public function getColumns(){		
			$this->sql = "CALL sp_get_leave_mngt_columns()";
			$query = $this->db->query($this->sql);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			return $result; 
		}

		function make_query(){
			$this->selectRequestParams = array(
				(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
				(isset($_POST["Status"]))?@$_POST["Status"]:"",
				@$_POST["search"]["value"],
				"",
				0,
				(isset($_POST['order']))?1:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				@$_POST['length'],
				@$_POST['start'],
				(!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?Helper::get('leave_grouping_id'):"",
				(!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?1:0,
				(isset($_POST['Id']) && $_POST['Id'] != null)?$_POST['Id']:"",
				(isset($_POST['Id']) && $_POST['Id'] != null)?1:0,
				(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS)) ? 1:0,
				$_SESSION["division_id"],
				$_SESSION["id"],
				Helper::get('userlevelid') == 43 ? 1 : 0,
				in_array(Helper::get('userlevelid'), array(38,45)) ? 1 : 0,
				Helper::get('userlevelid') == 46 ? 1 : 0

			);
			
            $this->sql = "CALL sp_get_all_employee_leave_requests(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		}

		function make_datatables(){
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectRequestParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    return $result;
		}

		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectRequestParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched Other Deduction.";
				$data['details'] = $result;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else $this->ModelResponse($code, $message);
			return false;
		}

		function get_filtered_data(){  
		     $this->make_query();
			 $this->selectRequestParams = array(
				(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
				(isset($_POST["Status"]))?@$_POST["Status"]:"",
				 @$_POST["search"]["value"],
				 "",
				 0,
				 (isset($_POST['order']))?1:"",
				 (isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				 (isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				 "",
				 "",
				 (!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?Helper::get('leave_grouping_id'):"",
				 (!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?1:0,
				 (isset($_POST['Id']) && $_POST['Id'] != null)?$_POST['Id']:"",
				 (isset($_POST['Id']) && $_POST['Id'] != null)?1:0,
				 (Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS)) ? 1:0,
				 $_SESSION["division_id"],
				 $_SESSION["id"],
				 Helper::get('userlevelid') == 43 ? 1 : 0,
				 in_array(Helper::get('userlevelid'), array(38,45)) ? 1 : 0,
				 Helper::get('userlevelid') == 46 ? 1 : 0
			 );
			 $query = $this->db->query($this->sql,$this->selectRequestParams);
			 $result = $query->result();
			 mysqli_next_result($this->db->conn_id);
		     return sizeof($result);  
		}   

		function get_all_data(){

			$this->db->select('*');
			$query = $this->db->get($this->table);
			$total = $this->db->count_all($this->table);
			// $this->db->select($this->table."*");  
			// $this->db->from($this->table);
			// var_dump();
		    return $total;

		}  
		//End Fetch


	}
?>