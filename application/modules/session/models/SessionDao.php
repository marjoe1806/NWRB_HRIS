<?php
class SessionDao extends Helper {
	
	public function __construct() {
		$this->load->database();
		$this->load->model('HelperDao');
		$this->load->model('MessageRels');
		$this->load->model('SystemMessages');
		ModelResponse::busy();
	}
			
	public function getUser($user, $passw, $token) {
		$result = array();
		$data = array();
		$password = Helper::encrypt($passw,$user);
		$sql = " SELECT emp.*,wul.*,wu.*, emp.division_id as emp_division_id, pos.name as employee_position, dep.department_name as employee_division 
				 FROM tblwebusers wu 
				 LEFT JOIN tblwebuserslevel wul ON wu.userlevelid = wul.userlevelid 
				 LEFT JOIN tblemployees emp ON wu.employee_id = emp.id
				 LEFT JOIN tblfieldpositions pos ON emp.position_id = pos.id
				 LEFT JOIN tblfielddepartments dep ON emp.division_id = dep.id";
		$sql .=" WHERE wu.username='".$user."' AND wu.password ='".$password."'";
		$query = $this->db->query($sql);
		$user = $query->result_array();
		if(sizeof($user) > 0){
			foreach ($user as $k2 => $row) {
				$user[$k2]['employee_number'] = $this->Helper->decrypt($row['employee_number'],$row['employee_id']);
	        	$user[$k2]['employee_id_number'] = $this->Helper->decrypt($row['employee_id_number'],$row['employee_id']);
	        	$user[$k2]['first_name'] = $this->Helper->decrypt($row['first_name'],$row['employee_id']);
	        	$user[$k2]['middle_name'] = $this->Helper->decrypt($row['middle_name'],$row['employee_id']);
	        	$user[$k2]['last_name'] = $this->Helper->decrypt($row['last_name'],$row['employee_id']);
	        	$user[$k2]['extension'] = $this->Helper->decrypt($row['extension'],$row['employee_id']);
			}
			//Roles
			$userslevelconfig_sql = "SELECT * FROM tblwebuserslevelconfig WHERE userlevelid='".$user[0]['userlevelid']."'";
			$userslevelconfig_query = $this->db->query($userslevelconfig_sql);
			$roles = $userslevelconfig_query->result_array();
			$data['details'] = $user[0];
			foreach ($roles as $k => $v) {
				$data['roles'][] = $v['module'];
			}
			$response = new ModelResponse("0", "Successfully fetched user.", $data);
			$result = json_decode($response);
		}
		else{
			$response = new ModelResponse("1", "You have entered invalid username or password. Please try again!", $data);
			$result = json_decode($response);
		}
		return $result;
	}
	public function changePassword($oldpass,$newpass,$newpass2){
		$helperDao = new HelperDao();
		$code = "1";
		$message = "Failed to change password.";
		$sysmessages = new SystemMessages();

		$result = array();
		$newpassword = Helper::encrypt($newpass,Helper::get('username'));
		$oldpassword = Helper::encrypt($oldpass,Helper::get('username'));
		
		if($newpass == $newpass2){
			$checkOldPassSql = "SELECT * from tblwebusers WHERE username = ? AND password = ?";
			$params = array(Helper::get('username'), $oldpassword);
			$checkOldPassQuery = $this->db->query($checkOldPassSql, $params);
			$user = $checkOldPassQuery->result_array();
			if(sizeof($user) > 0){
				$email = Helper::decrypt($user[0]['email'],$user[0]['username']);
				$changepass_sql = "UPDATE tblwebusers SET password = ?, isfirstlogin = ? WHERE username = ?";
				$params = array($newpassword, 0,Helper::get('username'));
				$changepass_query = $this->db->query($changepass_sql, $params);
				if($this->db->affected_rows() > 0){
					// update mobile users
					$data1 = array(
						"password" => $newpassword,
						"isfirstlogin" => "0"
					);
					$this->db->where('username', Helper::get('username'));
					if ($this->db->update('tblmobileusers',$data1) === FALSE){
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
					// send email
					$sysmessages->init(MessageRels::EMAIL_USER_UPDATE);
					$message = $sysmessages->getMessage();
					$message = str_replace("<fname>","Sir/Ma'am",$message);
					$changelogs = "\n USERNAME: ". Helper::get('username');
								// . "\n PASSWORD: ". Helper::decrypt($newpassword,Helper::get('username'));
					$message = str_replace("<changelogs>",$changelogs,$message);
					$helperDao->sendMail(
						$sysmessages->getSubject(),
						$email,
						$message
					);
					$response = new ModelResponse("0", "Password successfully changed.");
					$result = json_decode($response);
				}
				else{
					$response = new ModelResponse("1", "Password change failed.");
					$result = json_decode($response);
				}
			}
			else{
				$response = new ModelResponse("2", "Old password is incorrect.");
				$result = json_decode($response);
			}
		}
		else{
			$response = new ModelResponse("2", "New password and confirm password does not match.");
			$result = json_decode($response);
		}
		
		return $result;
	}
	public function checkUser($params){
		$data = array();
		$code = "1";
		$message = "No data available.";
		$params['password'] = Helper::encrypt($params['password'],$params['username']);
		$sql = " SELECT * FROM tblwebusers WHERE username=? and password=? ";
		$query = $this->db->query($sql,$params);
		$userlevel_rows = $query->result_array();
		$data['details'] = $userlevel_rows;
		if(sizeof($userlevel_rows) > 0){
			$code = "0";
			$message = "Successfully fetched user levels.";
			$this->ModelResponse($code, $message, $data);		
			return true;		
		}	
		else {
			$this->ModelResponse($code, $message);
		}
		return false;
	}
	public function checkUsername($params){
		$data = array();
		$code = "1";
		$message = "Username does not exist or is inactive.";
		$sql = "SELECT * FROM tblwebusers WHERE status='ACTIVE' AND username=?";
		$query = $this->db->query($sql,$params);
		$userlevel_rows = $query->result_array();
		$data['details'] = $userlevel_rows;
		if(sizeof($userlevel_rows) > 0){
			$code = "0";
			$message = "Successfully fetched username.";
			$this->ModelResponse($code, $message, $data);		
			return true;		
		}	
		else {
			$this->ModelResponse($code, $message);
		}
		return false;
	}

}
?>