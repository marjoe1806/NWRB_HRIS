<?php
	class UTimePostingCollection extends Helper {
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
		var $table = "tblleaveutimeposting";   
      	var $order_column = array("DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)","tblmonths.name","utime_amt","date_created","is_active","");
      	public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		function make_query()  
		{  
			//var_dump($this->select_column);die();
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.last_name';
			// $this->select_column[] = 'tblfieldlocations.name';
		    // $this->db->select(
		    // 	$this->table.'.*,
		    // 	tblemployees.first_name,
		    // 	tblemployees.middle_name,
		    // 	tblemployees.last_name,
		    // 	tblfieldlocations.name AS location_name,
		    // 	tblmonths.name AS month_name'
		    // );
		    $this->db->select(
		    	$this->table.'.*,
		    	tblemployees.first_name,
		    	tblemployees.middle_name,
		    	tblemployees.last_name,
		    	tblemployees.last_name,
		    	tblmonths.name AS month_name'
		    );   
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    // $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");
		    $this->db->join("tblmonths",$this->table.".month_code = tblmonths.code","left");
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]); 
		     		//var_dump($value == "$this->table.first_name");
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  

		     		}
		     		else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		}
		     		
		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		        $this->db->group_end(); 
		    }

		    if(isset($_GET['Id']) && $_GET['Id'] != null)
		    	$this->db->where($this->table.'.id="'.$_GET['Id'].'"');//die('hit');
		    // if(isset($_GET['LocationId']))
		    // 	$this->db->where($this->table.'.location_id="'.$_GET['LocationId'].'"');
		    if(isset($_GET['LeaveYear']))
		    	$this->db->where($this->table.'.leave_year ="'.$_GET['LeaveYear'].'"');
		    if(isset($_GET['DivisionId']))
		    	$this->db->where('tblemployees.division_id ="'.$_GET['DivisionId'].'"');
		    if(isset($_GET['EmployeeId'])){
		    	$this->db->where($this->table.'.employee_id ="'.$_GET['EmployeeId'].'"');
		    }
		    else
		    {
		    	$this->db->where('1=0');
		    }	
		    if(isset($_POST["order"]))  
		    {  
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
		    }  
		    else  
		    {  
		          $this->db->order_by($this->table.'.utime_id', 'DESC');  
		    }  
		}  
		function make_datatables(){  
		    $this->make_query();  
		    if($_POST["length"] != -1)  
		    {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }  

		    $query = $this->db->get();  
		    //echo $this->db->last_query();die();
		    return $query->result();  
		}  
		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->get();
		    
		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched Loan.";
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
		     //var_dump($this->make_query());die();

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
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "UTime failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$sql = "	UPDATE $this->table SET
									is_active = ? 
								WHERE utime_id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated UTime.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "UTime failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$sql = "	UPDATE $this->table SET
									is_active = ? 
								WHERE utime_id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated UTime.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function fetchMonths(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblmonths";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched months.";
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
		public function addUTimePosting($params){
			//var_dump($params);die();
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "UTime failed to insert.";
			$this->db->trans_begin();
			$utimes = array();
			foreach ($params['month_code'] as $k => $v) {
				$utimes[] = array(
					// "location_id"  	=> $params['location_id'],
					// "division_id"  	=> $params['division_id'],
					"division_id"  	=> $params['division_id'],
					"employee_id"  	=> $params['employee_id'],
					"leave_year"  	=> $params['leave_year'],
					"month_code"  	=> $params['month_code'][$k],
					"utime_amt"  	=> $params['utime_amt'][$k],
					"remarks" 	 	=> $params['remarks'][$k],
					"modified_by"	=> Helper::get('userid')
				);
				$this->db->query("DELETE FROM tblleaveutimeposting WHERE month_code = '".$params['month_code'][$k]."' AND leave_year = ".$params['leave_year']." AND employee_id = '".$params['employee_id']."' ");
				//var_dump($participants);die();
				//$this->db->insert('tblleavemanagementdaysleave', $days);
			}
			$this->db->insert_batch('tblleaveutimeposting', $utimes);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully added UTime.";
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
		public function updateUTimePosting($params){
			//var_dump($params);die();
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "UTime failed to update.";
			$this->db->trans_begin();
			// var_dump($params);die();
			$utimes = array();
			foreach ($params['month_code'] as $k => $v) {
				$utimes[] = array(
					// "location_id"  	=> $params['location_id'],
					// "division_id"  	=> $params['division_id'],
					"division_id"  	=> $params['division_id'],
					"employee_id"  	=> $params['employee_id'],
					"leave_year"  	=> $params['leave_year'],
					"month_code"  	=> $params['month_code'][$k],
					"utime_amt"  	=> $params['utime_amt'][$k],
					"remarks" 	 	=> $params['remarks'][$k],
					"modified_by"	=> Helper::get('userid')
				);
				$this->db->query("DELETE FROM tblleaveutimeposting WHERE month_code = '".$params['month_code'][$k]."' AND leave_year = ".$params['leave_year']." AND employee_id = '".$params['employee_id']."' ");
				//var_dump($participants);die();
				//$this->db->insert('tblleavemanagementdaysleave', $days);
			}
			$this->db->insert_batch('tblleaveutimeposting', $utimes);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated UTime.";
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
		public function fetchBalanceBrought($year,$employee_id){
			//var_dump($year);die();
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblleaveledger WHERE leave_year = ? AND employee_id = ?";

			$params = array($year,$employee_id);
			$query = $this->db->query($sql,$params);
			$rows = $query->result_array();
			$data['details'] = $rows;
			if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched leave ledger.";
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

	}
?>