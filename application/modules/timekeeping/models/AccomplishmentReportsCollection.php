<?php
	class AccomplishmentReportsCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT * FROM tbltimekeepingaccomplishmentreports";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			foreach ($data['details'] as $k => $v) {
				$employee = $this->getEmployeeById($v['employee_id']);
				$document_type = $this->getDocumentTypeById($v['type_id']);
				$data['details'][$k]['employee_firstname'] = $this->Helper->decrypt($employee[0]['first_name'], $employee[0]['id']);
				$data['details'][$k]['employee_middlename'] = $this->Helper->decrypt($employee[0]['middle_name'], $employee[0]['id']);
				$data['details'][$k]['employee_lastname'] = $this->Helper->decrypt($employee[0]['last_name'], $employee[0]['id']);
				$data['details'][$k]['document_name'] = $document_type[0]['type_name'];
				$data['details'][$k]['document_desc'] = $document_type[0]['type_description'];
			}
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched accomplishment reports.";
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
			$sql = " SELECT * FROM tbltimekeepingaccomplishmentreports where is_active = '1'";
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
			$message = "Accomplishment Report failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tbltimekeepingaccomplishmentreports SET
									is_active = ?
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Accomplishment Report.";
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
			$message = "Accomplishment Report failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tbltimekeepingaccomplishmentreports SET
									is_active = ?
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Accomplishment Report.";
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

		function getEmployeeById($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function getDocumentTypeById($document_type_id) {
			$params = array($document_type_id);
			$sql = "SELECT * FROM tblfielddocumenttypes WHERE type_id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$document_type = $query->result_array();
			return $document_type;
		}

		public function addRows($params){

			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Document type failed to insert.";
			$this->db->insert('tbltimekeepingaccomplishmentreports', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted Accomplishment Report.";
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
			if ($this->db->update('tbltimekeepingaccomplishmentreports',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated Accomplishment Report.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

	}
?>