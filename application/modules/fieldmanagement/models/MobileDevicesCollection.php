<?php
	class MobileDevicesCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT a.*,b.name as location_name FROM tblfieldmobiledevices a LEFT JOIN tblfieldlocations b ON a.location_id = b.id";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched mobiledevices.";
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
		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT a.*,b.name AS location_name FROM tblfieldmobiledevices a LEFT JOIN tblfieldlocations b ON b.id = a.location_id  where a.is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
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
			$message = "Mobile Device failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldmobiledevices SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Mobile Device.";
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
			$message = "Mobile Device failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldmobiledevices SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Mobile Device.";
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
			$message = "Mobile Device failed to insert.";
			if(!$this->isExistImei($params['imei'])){
				$this->db->insert('tblfieldmobiledevices', $params);
				if($this->db->affected_rows() > 0)
				{
					$code = "0";
					$message = "Successfully inserted Mobile Device.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				}	
				else {
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}
			else{
				$code = "1";
				$message = "IMEI already exists.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Mobile Device failed to update.";
			if(!$this->isExistImei($params['imei'],$params['id'])){
				$this->db->where('id', $params['id']);
				if ($this->db->update('tblfieldmobiledevices',$params) === FALSE){
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}	
				else {
					$code = "0";
					$message = "Successfully updated Mobile Device.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;	
				}
			}
			else{
				$code = "1";
				$message = "IMEI already exists.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function isExistImei($imei,$id = ""){
			$this->db->select('*');
			$this->db->from('tblfieldmobiledevices');
			$this->db->where('imei',$imei);
			if($id!= ""){
				$this->db->where('id != '.$id);
			}
			$query = $this->db->get();
			$num = $query->num_rows();
			if($num > 0)
				return true;
			return false;
		}

	}
?>