<?php
	class UserConfigCollection extends Helper {
		public function __construct() {
			ModelResponse::busy();
			$this->load->model('HelperDao');
			$this->load->model('MessageRels');
			$this->load->model('SystemMessages');
				
		}
		/*public function hasRows($data,$jsonfile) {
			$str = file_get_contents(base_url()."assets/local/jsonlib/".$jsonfile.".json");
			$json = json_decode($str,true);
			return $json;
		}*/
		/*public function hasRows($data,$jsonfile){
			$url = base_url()."assets/local/jsonlib/".$jsonfile.".json";
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL,$url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		    $return = curl_exec($ch);
		    curl_close ($ch);
		    $json = json_decode($return);
		    return $json;
		}*/
		public function hasRowsCount($params){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$data['Status']			= isset($params['Status'])?$params['Status']:'ACTIVE';
			$data['specific'] 		= isset($params['specific'])?$params['specific']:'';
			//var_dump($data);die();
			$params = array($data['Status']);
			$sql = " SELECT count(*) AS 'count' FROM tblwebusers WHERE status = ? ";
			if($data['specific'] != null){
				$sql .= " AND CONCAT_WS('',userid,username,userlevelid,email,status,datecreated,createdby) LIKE ?";
				//$params[1] = $data['specific'];
				array_splice($params, 1, 0, array('%'.$data['specific'].'%'));
			}
			//var_dump($params);die();
			$query = $this->db->query($sql,$params);
			$user_rows = $query->result_array();
			$data['details'] = $user_rows;
			if(sizeof($user_rows) > 0){
				$code = "0";
				$message = "Successfully fetched users.";
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
		public function hasRows($params){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$data['Status']			= isset($params['Status'])?$params['Status']:'ACTIVE';
			$data['specific'] 		= isset($params['specific'])?$params['specific']:'';

			// $data['limit'] 			= $limit;
			// $data['offset'] 		= $offset;
			//var_dump($data);die();
			// $params = array($data['Status'],(int)$data['offset'],(int)$data['limit']);
			$params = array($data['Status']);
			$sql = " SELECT wu.*,wul.userlevelname,wul.description,em.first_name,em.middle_name,em.last_name,po.name AS position_name,de.department_name,em.position_id FROM tblwebusers wu LEFT JOIN tblwebuserslevel wul ON wu.userlevelid = wul.userlevelid LEFT JOIN tblemployees em ON wu.employee_id = em.id LEFT JOIN tblfieldpositions po ON em.position_id = po.id LEFT JOIN tblfielddepartments de ON em.division_id = de.id WHERE wu.status = ?";

			$fname = "DECRYPTER(em.first_name,'sunev8clt1234567890',em.id)";
			$mname = "DECRYPTER(em.middle_name,'sunev8clt1234567890',em.id)";
			$lname = "DECRYPTER(em.last_name,'sunev8clt1234567890',em.id)";

			if($data['specific'] != null){
				$sql .= " AND CONCAT_WS('',$fname,$mname,$lname,po.name,de.department_name,wu.userid,wu.username,wu.userlevelid,wu.email,wu.status,wu.datecreated,wu.createdby,wul.userlevelname) LIKE ?";
					array_splice($params, 1, 0, array('%'.$data['specific'].'%'));
			}
			//var_dump($params);die();
			// $sql.= " ORDER BY wu.datecreated DESC LIMIT ?,?";
			$query = $this->db->query($sql,$params);
			$user_rows = $query->result_array();
			$data['details'] = $user_rows;
			//var_dump($user_rows);die();
			if(sizeof($user_rows) > 0){
				$code = "0";
				$message = "Successfully fetched users.";
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
		public function getUser($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT wu.*,wul.userlevelname,wul.description,em.first_name,em.middle_name,em.last_name,po.name AS position_name,de.department_name,em.position_id FROM tblwebusers wu LEFT JOIN tblwebuserslevel wul ON wu.userlevelid = wul.userlevelid LEFT JOIN tblemployees em ON wu.employee_id = em.id LEFT JOIN tblfieldpositions po ON em.position_id = po.id LEFT JOIN tblfielddepartments de ON em.division_id = de.id  WHERE wu.userid = ?";
			$params = array($id);
			$query = $this->db->query($sql,$params);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched appearance.";
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
		public function getUserLevels($status){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblwebuserslevel WHERE status = ?";
			$query = $this->db->query($sql,array($status));
			$userlevel_rows = $query->result_array();
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched user levels.";	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
						
			}	
			else {
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			//var_dump($userlevel_rows);die();
			return $userlevel_rows;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to activate.";
			$data['status'] = "ACTIVE";
			$sysmessages = new SystemMessages();
			/*if($helperDao->isUserExists($params['email'])){
				$sysmessages->init(MessageRels::EMAIL_ALREADY_TAKEN);
				$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
			}*/
			$employee = "";
			$email = "";
			$user = $helperDao->getUser($params['id']);
			if(sizeof($user) > 0){
				$email = Helper::decrypt($user[0]['email'],$user[0]['username']);
			}
			$this->db->where('userid', $params['id']);
			if ($this->db->update('tblwebusers',$data) === FALSE){
				$auditmessage = "User failed to activate (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
						
			}	
			else {
				$sysmessages->init(MessageRels::EMAIL_USER_ACTIVATED);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$message = str_replace("<system>","NWRB-CHRIS",$message);
				$message = str_replace("<webmaster>","NWRB-CHRIS",$message);
				$message = str_replace("<link>","https://www.tlcpay.ph/NWRBCHRIS",$message);
				//var_dump();die();
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$email,
					$message
				);
				$code = "0";
				$message = "Successfully activate User.";
				$auditmessage = "Successfully activate User (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		/*public function logoutUser($params){
			$data['SessionId'] 		= UserAccount::getSessionId();
			$data['RequestType'] 	= "logoutUser";
			$data['userid'] 	= isset($params['id'])?$params['id']:'';
			$data = json_encode($data);
			$ret = parent::serviceCall('workflow',$data);
			//var_dump($ret);die();
			if($ret != null) {
				if($ret->Code == 0) {
					
						$this->ModelResponse($ret->Code, $ret->Message);
						return true;				
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}			
			}
			return false;
		}*/
		public function inactiveRows($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to deactivate.";
			$data['status'] = "INACTIVE";
			$sysmessages = new SystemMessages();
			$employee = "";
			$email = "";
			$user = $helperDao->getUser($params['id']);
			if(sizeof($user) > 0){
				$email = Helper::decrypt($user[0]['email'],$user[0]['username']);
			}
			$this->db->where('userid', $params['id']);
			if ($this->db->update('tblwebusers',$data) === FALSE){
				$auditmessage = "User failed to deactivate (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
						
			}	
			else {
				if($params['userlevel'] == "ADMINISTRATOR"){
					$params['userlevel'] = "Admin Rights";
				}else{
					$params['userlevel'] = $params['userlevel'];
				}

				$sysmessages->init(MessageRels::EMAIL_USER_DEACTIVATED);
				$message = $sysmessages->getMessage();
				$get_subject = $sysmessages->getSubject();
				$subject = str_replace("<userlevel>",$params['userlevel'],$get_subject);
				// print_r($subject); die();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$message = str_replace("<system>","NWRB-CHRIS",$message);
				$message = str_replace("<userlevel>",$params['userlevel'],$message);
				$message = str_replace("<webmaster>","NWRB-CHRIS",$message);
				$message = str_replace("<link>","https://www.tlcpay.ph/NWRBCHRIS",$message);
				// var_dump($message);die();
				$helperDao->sendMail($subject,$email,$message);
				$code = "0";
				$message = "Successfully deactivated User.";
				$auditmessage = "Successfully deactivated User (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

		public function grantAccess($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to grant PDS access.";
			$sysmessages = new SystemMessages();
			$employee = "";
			$email = "";
			$user = $helperDao->getUser($params['id']);
			if(sizeof($user) > 0){
				$email = Helper::decrypt($user[0]['email'],$user[0]['username']);
			}
			$this->sql = "CALL sp_grant_employee_pds_access(?)";
			$this->db->query($this->sql,array($user[0]['employee_id']));
			// mysqli_next_result($this->db->conn_id);
			
			if($this->db->affected_rows() > 0){
				$sysmessages->init(MessageRels::EMAIL_USER_PDS_ACCESS);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$message = str_replace("<system>","NWRB-CHRIS",$message);
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$email,
					$message
				);
				$code = "0";
				$message = "Successfully Grant PDS Access User.";
				$auditmessage = "Successfully Grant PDS Access User (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$auditmessage = "User failed to grant PDS access (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		

		// public function givePDSAccess($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Allow employee to access PDS Failed.";
		// 	$data['Id'] = isset($params['id'])?$params['id']:'';
		// 	$data['Status'] = "0";
		// 	$this->sql = "CALL sp_grant_employee_pds_access(?)";
		// 	$updateparams = array($data['Id']);
		// 	$this->db->query($this->sql,$updateparams);
		// 	mysqli_next_result($this->db->conn_id);
		// 	if($this->db->affected_rows() > 0){
		// 		$code = "0";
		// 		$message = "Allow employee to access PDS successful.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	} else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to insert.";
			$length = 5;
			$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			//var_dump($randomletter);die();
			$params['userid'] = uniqid();
			$params['username'] = strtoupper($params['username']);
			// $password = Helper::encrypt($randomletter,$params['username']);
			$tmp_password = $params['password'];
			$password = Helper::encrypt($params['password'],$params['username']);
			$params['email'] = Helper::encrypt($params['email'],$params['username']);
			$params['password'] = $password;
			$params['registrationstatus'] = "APPROVED";
			$params['status'] = "ACTIVE";
			$params['createdby'] = Helper::get('username');
			$sysmessages = new SystemMessages();
			if($helperDao->isUserExists($params['email'])){
				$sysmessages->init(MessageRels::EMAIL_ALREADY_TAKEN);
				$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
			}
			if($helperDao->isUserExists($params['username'])){
				$sysmessages->init(MessageRels::ACCOUNT_ALREADY_TAKEN);
				$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
			}
			else{
				$this->db->insert('tblwebusers', $params);
				if($this->db->affected_rows() > 0)
				{
					
					if(trim($tmp_password) != ""){
						$sysmessages->init(MessageRels::EMAIL_USER_REGISTER);
						$message = $sysmessages->getMessage();
						$message = str_replace("<fname>","Sir/Ma'am",$message);
						$message = str_replace("<system>","NWRB-CHRIS",$message);
						$message = str_replace("<username>",$params['username'],$message);
						// $message = str_replace("<tempassw>",$randomletter,$message); // random password
						$message = str_replace("<tempassw>",$tmp_password,$message); //input password
						$message = str_replace("<templink>",base_url(),$message);
						$helperDao->sendMail(
							$sysmessages->getSubject(),
							Helper::decrypt($params['email'],$params['username']),
							$message
						);
					}
					$code = "0";
					$message = "Successfully inserted User.";
					$auditmessage = "Successfully inserted User (".$params['username'].").";
					$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
					$this->ModelResponse($code, $message);
					return true;		
				}	
				else {
					$auditmessage = "User failed to insert (".$params['username'].").";
					$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
					$this->ModelResponse($code, $message);
				}
			}
			
			return false;
		}
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to update.";
			//$length = 5;
			//$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			//var_dump($randomletter);die();
			$params['username'] = strtoupper($params['username']);
			//$password = Helper::encrypt($randomletter,$params['username']);
			//$params['password'] = $password;
			/*$params['registrationstatus'] = "APPROVED";*/
			$params['email'] = Helper::encrypt($params['email'],$params['username']);
			$params['password'] = Helper::encrypt($params['password'],$params['username']);
			// $params['status'] = "ACTIVE";
			// $params['isfirstlogin'] = "1";
			// unset($params["password"]);
			$sysmessages = new SystemMessages();
			/*if($helperDao->isUserExists($params['email'])){
				$sysmessages->init(MessageRels::EMAIL_ALREADY_TAKEN);
				$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
			}*/
			
			$this->db->where('userid', $params['userid']);
			if ($this->db->update('tblwebusers',$params) === FALSE){
				$auditmessage = "User failed to update (".$params['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
			}	
			else {
				// update mobile users
				$data = array(
					"password" => $params['password'],
					"isfirstlogin" => "0"
				);
				$this->db->where('username', Helper::decrypt($params['email'],$params['username']));
				if ($this->db->update('tblmobileusers',$data) === FALSE){
					$auditmessage = "User failed to update (".$params['username'].").";
					$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
					$this->ModelResponse($code, $message);
				}
				// send email
				$sysmessages->init(MessageRels::EMAIL_USER_UPDATE);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$changelogs = "\n USERNAME: ". Helper::decrypt($params['email'],$params['username']);
							// . "\n PASSWORD: ". Helper::decrypt($params['password'],$params['username']);
				$message = str_replace("<changelogs>",$changelogs,$message);
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					Helper::decrypt($params['email'],$params['username']),
					$message
				);
				$code = "0";
				$message = "Successfully updated User.";
				$auditmessage = "Successfully updated User (".$params['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		public function fpass($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User password failed to reset.";
			$sysmessages = new SystemMessages();
			/*if($helperDao->isUserExists($params['email'])){
				$sysmessages->init(MessageRels::EMAIL_ALREADY_TAKEN);
				$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
			}*/
			$employee = "";
			$email = "";
			$username="";
			$user = $helperDao->getUser($params['id']);
			if(sizeof($user) > 0){
				$email = Helper::decrypt($user[0]['email'],$user[0]['username']);

				$username = $user[0]['username'];
			}
			$length = 5;
			$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			//var_dump($randomletter);die();
			//$params['username'] = strtoupper($params['username']);
			$data['password'] = Helper::encrypt($randomletter,$username);
			$this->db->where('userid', $params['id']);
			if ($this->db->update('tblwebusers',$data) === FALSE){
				$auditmessage = "User password failed to reset (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
						
			}	
			else {
				$sysmessages->init(MessageRels::EMAIL_USER_UPDATE);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$changelogs = "\n USERNAME: ". $username
							. "\n TEMPORARY PASSWORD: ". $randomletter;
				$message = str_replace("<changelogs>",$changelogs,$message);
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$email,
					$message
				);
				$code = "0";
				$message = "User password successfully reset. Temporary password was sent to email.";
				$auditmessage = "User password successfully reset (".$user[0]['username'].").";
				$helperDao->auditTrails(Helper::get('userid'),$auditmessage);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		/*public function cpass($params){
			$data['SessionId'] 		= UserAccount::getSessionId();
			$data['RequestType'] 	= "changePassword";
			$data['userid'] 		= isset($params['UserId'])? $params['UserId'] : "";
			$data['username'] 		= isset($params['Username'])? $params['Username'] : "";
			$data['newpassword'] 		= isset($params['newpass'])? $params['newpass'] : "";
			//$params['DoctypeId'] = isset($params['id'])?$params['id']:'';
			//var_dump($data);die();
			$data = json_encode($data);
			$ret = parent::serviceCall('workflow',$data);
			//var_dump($ret);die();
			if($ret != null) {
				if($ret->Code == 0) {
						$this->ModelResponse($ret->Code, $ret->Message);
						return true;				
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}			
			}
			return false;
		}*/

	}
?>