<?php
	class SignatoriesCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblfieldsignatories";
      	var $order_column = array('signatory','employee_name','is_active');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($status){
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.last_name';
		    $this->db->select(
				$this->table.'.*,
				CONCAT(DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ", DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)," ", DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id)) as employee_name,
				tblemployees.division_id as division_id
				'
			);
			$this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
						$this->db->from($this->table);
									if($status != "ALL") $this->db->where($this->table.".is_active",$status);
    	    if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
						$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_GET["search"]["value"]);
					}else{
						$this->db->or_like($value, $_GET["search"]["value"]);
					}
		     	}
		        $this->db->group_end();
		    }
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by($this->table.'.id', 'ASC');
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
			$sql = 'SELECT a.*, CONCAT(DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ", DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id)," ", DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)) as employee_name, b.division_id as division_id FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id';
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched signatories.";
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
		public function hasHeadRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT *, (SELECT code FROM tblfieldpayrollgrouping WHERE id = tblfieldheadsignatories.payroll_grouping_id) AS payroll_code  FROM tblfieldheadsignatories";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched signatories.";
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
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Document type level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldsignatories SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated signatory.";
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
		public function activeHeadRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Signatory failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldheadsignatories SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated signatory.";
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
			$message = "Document type level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldsignatories SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated signatory.";
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

		public function inactiveHeadRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Signatory failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldheadsignatories SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated signatory.";
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
			unset($params["division_id"]);
			$params['modified_by'] = Helper::get('userid');
			$message = "Signatory failed to insert.";
			$this->db->insert('tblfieldsignatories', $params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully inserted signatory.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function addHeadRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Signatory failed to insert.";
			$this->db->insert('tblfieldheadsignatories', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted signatory.";
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
			unset($params["division_id"]);
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldsignatories',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated signatory.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}
		public function updateHeadRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Signatory failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldheadsignatories',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated signatory.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>