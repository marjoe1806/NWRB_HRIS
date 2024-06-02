<?php
	class DepartmentsCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblfielddepartments";
      	var $order_column = array('code','department_name','is_active');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($status){
		    $this->db->select(
		    	$this->table.'.*'
		    );
		    $this->db->from($this->table);
			if($status != "ALL") $this->db->where($this->table.".is_active",$status);
    	    if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_GET["search"]["value"]);
		     	}
		        $this->db->group_end();
		    }
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by($this->table.'.id', 'DESC');
		    }
		}
		
		function make_datatables($status){
		    $this->make_query($status);
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
			
		    $query = $this->db->get();
		    return $query->result();
		}
		function get_filtered_data($status){
			$this->make_query($status);
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data($status){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($status != "ALL") $this->db->where($this->table.".is_active",$status);
		    return $this->db->count_all_results();
		}
		
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfielddepartments";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched departments.";
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfielddepartments where is_active = '1' ORDER BY department_name ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched departments.";
				$this->ModelResponse($code, $message, $data);	
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Department failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfielddepartments SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated department.";
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
			$message = "Department failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfielddepartments SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated department.";
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
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Document type failed to insert.";
			$this->db->insert('tblfielddepartments', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted department.";
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
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Document type failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfielddepartments',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated department.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>