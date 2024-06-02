<?php
	class UserLevelConfigCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwebuserslevel";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched user levels.";
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
		public function getUserLevel($id){
			// die("hit");
			//$data['SessionId'] 		= UserAccount::getSessionId();
			$helperDao = new HelperDao;
			$data['UserLevelId'] 	= $id;
			// var_dump($data);die();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwebuserslevel WHERE userlevelid = ?";
			$sql2 = " SELECT module FROM tblwebuserslevelconfig WHERE userlevelid = ?";
			$params = array($data['UserLevelId']);
			$query = $this->db->query($sql,$params);
			$query2 = $this->db->query($sql2,$params);
			$userlevel_rows = $query->result_array();
			$role_rows = $query2->result_array();
			$data['details'] = $userlevel_rows[0];
			foreach ($role_rows as $k => $v) {
				$data['details']['modules'][] = $v['module'];
			}
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched user level and roles.";
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
		public function getModules(){
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwebuserslevelmodule WHERE status = 'ACTIVE' ORDER BY description ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched user level module.";
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
			$message = "User account level failed to activate.";
			$data['UserLevelId'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "ACTIVE";
			$userlevel_sql = "	UPDATE tblwebuserslevel SET
									status = ? 
								WHERE userlevelid = ? ";
			$userlevel_params = array($data['Status'],$data['UserLevelId']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated user level.";
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User account level failed to deactivate.";
			$data['UserLevelId'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "INACTIVE";
			$userlevel_sql = "	UPDATE tblwebuserslevel SET
									status = ? 
								WHERE userlevelid = ? ";
			$userlevel_params = array($data['Status'],$data['UserLevelId']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated user level.";
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
			$message = "User account level failed to insert.";
			$data['UserLevelName'] 	= strtoupper(isset($params['UserLevelName'])? $params['UserLevelName'] : "");
			$data['Description'] 	= isset($params['Description'])? $params['Description'] : "";
			$data['Status'] 		= isset($params['Status'])? $params['Status'] : "";
			$data['Roles'] 			= isset($params['Roles'])? $params['Roles'] : "";
			$this->db->trans_begin();
			$userlevel_sql = "INSERT INTO tblwebuserslevel(userlevelname,description,status)VALUES(?,?,?)";
			$userlevel_params = array($data['UserLevelName'],$data['Description'],$data['Status']);
			$this->db->query($userlevel_sql,$userlevel_params);
			$data['UserLevelId'] = $this->db->insert_id();
			foreach ($data['Roles'] as $k => $v) {
				$userlevelconfig_sql = "INSERT INTO tblwebuserslevelconfig(userlevelid,module)VALUES(?,?)";
				$userlevel_params = array($data['UserLevelId'],$v);
				$this->db->query($userlevelconfig_sql,$userlevel_params);
			} 
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted user level.";
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
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User account level failed to update.";
			$data['UserLevelId'] 	= isset($params['UserLevelId'])? $params['UserLevelId'] : "";
			$data['UserLevelName'] 	= strtoupper(isset($params['UserLevelName'])? $params['UserLevelName'] : "");
			$data['Description'] 	= isset($params['Description'])? $params['Description'] : "";
			$data['Roles'] 			= isset($params['Roles'])? $params['Roles'] : "";
			$data['Status'] 		= isset($params['Status'])? $params['Status'] : "";
			//$params['DoctypeId'] = isset($params['id'])?$params['id']:'';
			// var_dump($data);die();
			$this->db->trans_begin();
			$removeconfig_sql = "DELETE FROM tblwebuserslevelconfig WHERE userlevelid = ?";
			$removeconfig_params = array($data['UserLevelId']);
			$this->db->query($removeconfig_sql,$removeconfig_params);
			$userlevel_sql = "	UPDATE tblwebuserslevel SET 
									userlevelname = ?, 
									description = ?, 
									status = ? 
								WHERE userlevelid = ? ";
			$userlevel_params = array($data['UserLevelName'],$data['Description'],$data['Status'],$data['UserLevelId']);
			$this->db->query($userlevel_sql,$userlevel_params);
			foreach ($data['Roles'] as $k => $v) {
				$userlevelconfig_sql = "INSERT INTO tblwebuserslevelconfig(userlevelid,module)VALUES(?,?)";
				$userlevel_params = array($data['UserLevelId'],$v);
				$this->db->query($userlevelconfig_sql,$userlevel_params);
			}
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully updated user level.";
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

	}
?>