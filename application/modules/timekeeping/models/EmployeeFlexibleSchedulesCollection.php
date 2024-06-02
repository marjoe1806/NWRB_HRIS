<?php
	class EmployeeFlexibleSchedulesCollection extends Helper {
		
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		public function hasRowsRegular(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepingemployeeflexibleschedules";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Employee Schedules.";
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

		public function hasRowsShifting(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepingemployeeflexibleschedules WHERE shift_type = 1";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Employee Schedules.";
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
			$sql = " SELECT * FROM tbltimekeepingemployeeflexibleschedules where is_active = '1' ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched shifting schedules.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}	
		public function hasRowsWeeklyEmployeeSchedules($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepingemployeeflexibleschedulesweekly WHERE shift_code_id = " . $id . " ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Employee Schedules.";
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

		public function addRows($params){
			// $helperDao = new HelperDao();
			// $code = "1";
			// $params['modified_by'] = Helper::get('userid');
			// $message = "Employee Schedule failed to insert.";

			$helperDao = new HelperDao();
			$isDescriotionExist = $this->db->select("*")->from("tbltimekeepingemployeeflexibleschedules")
			->where("description",$params['description'])
			->where("shift_code",$params['shift_code'])
			->get()->num_rows();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = $isDescriotionExist < 1 ? "Employee Schedule failed to insert." : "Shift description/shift code already exist.";
			$this->db->insert('tbltimekeepingemployeeflexibleschedules', $params);




			if($isDescriotionExist < 1){
				if($this->db->affected_rows() > 0)
				{
					$code = "0";
					$message = "Successfully inserted Employee Schedule.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				}	
				else {
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}
			$this->ModelResponse($code, $message);
			return false;
		}

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee Schedule failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tbltimekeepingemployeeflexibleschedules',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated Employee Schedule.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee Schedules failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tbltimekeepingemployeeflexibleschedules SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated period setting.";
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
			$message = "Employee Schedules failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tbltimekeepingemployeeflexibleschedules SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated period setting.";
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

		public function activeRowsWeekly($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee Schedules failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tbltimekeepingemployeeflexibleschedulesweekly SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated period setting.";
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

		public function inactiveRowsWeekly($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee Schedules failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tbltimekeepingemployeeflexibleschedulesweekly SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated period setting.";
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

		public function addRowsWeekly($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$params['is_restday'] = isset($params['is_restday']) ? "1" : "0" ;
			$message = "Employee Schedules failed to insert.";
			$this->db->insert('tbltimekeepingemployeeflexibleschedulesweekly', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted employee schedules.";
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

		public function updateRowsWeekly($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee Schedules failed to update.";
			$params['is_restday'] = isset($params['is_restday']) ? "1" : "0" ;
			$this->db->where('id', $params['id']);
			if ($this->db->update('tbltimekeepingemployeeflexibleschedulesweekly',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated employee schedules.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>