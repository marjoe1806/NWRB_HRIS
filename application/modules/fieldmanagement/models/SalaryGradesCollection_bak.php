<?php
	class SalaryGradesCollection extends Helper {

		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		public function hasRowsWeeklySalaryGrades($salary_grade_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			// $sql = " SELECT * FROM tblfieldsalarygradesteps ORDER BY date_created DESC LIMIT 1";
			$sql = " SELECT * FROM tblfieldsalarygradesteps WHERE grade_id = " . $salary_grade_id . " ORDER BY date_created DESC LIMIT 1";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Payroll Setup.";
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

		public function hasRowsMonthly(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygrades ORDER BY date_created ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period settings.";
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

		public function hasRowsMonthlyActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygrades WHERE is_active = 1 ORDER BY date_created ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period settings.";
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

		public function hasRowsById(){
			$id = isset($_GET["Id"]) ? $_GET["Id"] : "";
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygrades where id = " . $id;
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period setting.";
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

		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygrades where is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period setting.";
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
		public function hasRowsStepsByGradeId($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygradesteps where grade_id = ?";
			$query = $this->db->query($sql,array($id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Grade Steps.";
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
			$message = "Salary Grades level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldsalarygrades SET
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
			$message = "Salary Grades level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldsalarygrades SET
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

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Salary Grades failed to insert.";
			$this->db->insert('tblfieldsalarygrades', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted period setting.";
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
			$message = "Salary Grades failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldsalarygrades',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated period setting.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

		public function activeRowsWeekly($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldsalarygradesteps SET
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
			$message = "Salary Grades level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldsalarygradesteps SET
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
			$message = "Salary Grades failed to insert.";
			$this->db->insert('tblfieldsalarygradesteps', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted period setting.";
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
			$message = "Salary Grades failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldsalarygradesteps',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated period setting.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

		public function getSalaryGradeSteps($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades saving failed.";
			$this->db->insert('tblfieldsalarygradesteps', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Salary grade successfully saved.";
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

		public function getSalaryGrades($id){
			$helperDao = new HelperDao();
			$data = array();
			$sql = " SELECT step_1, step_2, step_3, step_4, step_5, step_6, step_7, step_8 FROM tblfieldsalarygradesteps WHERE grade_id = " . $id . " ORDER BY date_created DESC LIMIT 1";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			return $data;
		}

	}
?>