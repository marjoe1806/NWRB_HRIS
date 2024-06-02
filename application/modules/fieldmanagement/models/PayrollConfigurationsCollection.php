<?php
	class PayrollConfigurationsCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRowsPayrollSetup(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldpayrollconfigurations ORDER BY id DESC LIMIT 1 ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Payroll Setup.";
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

		// get_edit_service_record
		function hasRowsPayrollSetupData(){
			$this->db->order_by('id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get('tblfieldpayrollconfigurations');
			return $query->result_array();	
			
			
		}
		
		public function activeRowsPayrollSetup($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll Setup level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldpayrollconfigurations SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Payroll Setup.";
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
		public function inactiveRowsPayrollSetup($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll Setup level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldpayrollconfigurations SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Payroll Setup.";
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
		public function addRowsPayrollSetup($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Payroll Setup failed to insert.";
			$params['computation_based_on'] = isset($params['computation_based_on'])? "1" : "0" ;
			$this->db->insert('tblfieldpayrollconfigurations', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully updated Payroll Setup.";
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
		public function updateRowsPayrollSetup($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll Setup failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldpayrollconfigurations',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated Payroll Setup.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}
	}
?>