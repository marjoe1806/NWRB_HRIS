<?php
	class WithHoldingTaxesCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwithholdingtax a LEFT JOIN tblwithholdingtaxtable b ON b.tax_id = a.id ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched withholding taxes.";
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
			$sql = " SELECT * FROM tblwithholdingtaxtable where is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched document types.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function hasRowsBySalary($salary){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwithholdingtaxtable where ? BETWEEN compensation_level_from AND compensation_level_to";
			$query = $this->db->query($sql,$salary);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Withholding Tax.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Withholding Tax failed to activate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblwithholdingtaxtable SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Withholding Tax.";
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
			$message = "Withholding Tax failed to deactivate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblwithholdingtaxtable SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Withholding Tax.";
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
			$message = "Withholding Tax failed to insert.";
			//var_dump($params);die();
			/*$effectivity = str_replace(' ', '', $params['effectivity']);
			$date_range = explode('-', $effectivity);
			$from 		= date('Y-m-d',strtotime($date_range[0]));
			$to 		= date('Y-m-d',strtotime($date_range[1]));
			$params1['pay_basis'] 		= $params['pay_basis'];
			$params1['effective_from'] 	= $from;
			$params1['effective_to'] 	= $to;
			
			$this->db->insert('tblwithholdingtax',$params1);*/
			$this->db->trans_begin();
			$id = 1;//$this->db->insert_id();
			foreach ($params['compensation_level_from'] as $k => $v) {
				$params2 = array();
				$params2['tax_id'] = $id;
				$params2['compensation_level_from'] = str_replace(',', '', $params['compensation_level_from'][$k]);
				$params2['compensation_level_to'] 	= str_replace(',', '', $params['compensation_level_to'][$k]);
				$params2['tax_percentage'] 			= str_replace(',', '', $params['tax_percentage'][$k]);
				$params2['tax_additional'] 			= str_replace(',', '', $params['tax_additional'][$k]);
				$this->db->insert('tblwithholdingtaxtable',$params2);
			} 
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Withholding Tax.";
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
		public function updateRows($params){
			// var_dump($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Withholding Tax failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblwithholdingtaxtable',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated Withholding Tax.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>