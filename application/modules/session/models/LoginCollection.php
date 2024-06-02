<?php
class LoginCollection extends Helper {
	
	public function __construct() {
		ModelResponse::busy();
		$this->load->model("UserAccount");
		$this->load->model("SessionDao");
	}
			
	public function hasRows($user, $passw, $token) {
		$data = json_encode(array("Username"=>$user,"Password"=>$passw,"Token"=>$token));
		$ret = new SessionDao();
		$ret = $ret->getUser($user,$passw,$token);
		if($ret != null) {
			if($ret->Code == 0) {
				if(isset($ret->Data)) {
					/*highlight_string("<?php\n\$data =\n" . var_export($ret, true) . ";\n?>");die();*/
					$ret->Data->details->email =  $this->Helper->decrypt($ret->Data->details->email,$ret->Data->details->username);
					$useracct = $ret->Data->details;
					$sessUser = new UserAccount();
					$sessUser->put($useracct);
					$sessUser->modules($ret->Data->roles);
					/*$sessUser->sessionId($ret->Data->sessionid);*/
					$this->ModelResponse($ret->Code, $ret->Message, $useracct);
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}				
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;
	}
	
	public function sessionLogout() {
		$data = json_encode(array("SessionId"=>UserAccount::getSessionId()));
		$ret = parent::serviceCall("LOGT",$data);		
		if($ret != null) {
			if($ret->Code == 0) {
				$this->ModelResponse($ret->Code, $ret->Message);
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;
	}
	
	public function checkSessionState() {
		/*$data = json_encode(array("SessionId"=>UserAccount::getSessionId()));
		$ret = parent::serviceCall("CHKSESS",$data);
		if($ret != null) {
			if($ret->Code == 0) {
				$this->ModelResponse($ret->Code, $ret->Message);
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;*/
		if(Helper::get('userid') != null){
			$this->ModelResponse("0", "Session is Enabled.");
			return true;
		} else {
			$this->ModelResponse("1", "Session is Disabled.");
		}
		return false;
	}
	
	public function cPass($oldpass,$newpass,$newpass2) {
		$ret = new SessionDao();
		$ret = $ret->changePassword($oldpass,$newpass,$newpass2);
		if($ret != null) {
			if($ret->Code == 0) {
				
				$this->ModelResponse($ret->Code, $ret->Message);			
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;
	}
	
	public function fPass($username, $baseurl) {
		//$data = json_encode(array("Email"=>$email, "BaseUrl"=>$baseurl));
		$username = strtoupper($username);
		$this->load->model('HelperDao');
		$this->load->model('SystemMessages');
		$this->load->model('MessageRels');
		$helperDao = new HelperDao();
		$code = "1";
		$message = "User password failed to reset.";
		$message2 = "Email does not exist.";
		$sysmessages = new SystemMessages();
		/*if($helperDao->isUserExists($params['email'])){
			$sysmessages->init(MessageRels::EMAIL_ALREADY_TAKEN);
			$this->ModelResponse($sysmessages->getCode(), $sysmessages->getMessage());
		}*/
		$employee = "";
		$email = "";
		// $user = $helperDao->getUserDetails(Helper::encrypt($username,$username));
		$user = $helperDao->getUserDetails($username);
		if($user){
			// $employee = $user[0]['employee_name'];
			$email = Helper::decrypt($user['email'],$user['username']);
		
			$length = 5;
			$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			$data['password'] = Helper::encrypt($randomletter,$username);
			$data['isfirstlogin'] = 1;
			if ($this->db->where('username', $username)->update('tblwebusers',$data)){
				// update mobile users
				$data1 = array(
					"password" => $data['password'],
					"isfirstlogin" => "1"
				);
				$this->db->where('username', $email);
				if ($this->db->update('tblmobileusers',$data1) === FALSE){
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
				//send email
				$sysmessages->init(MessageRels::EMAIL_FORGOT_PASS);
				$message = $sysmessages->getMessage();
				// $employee_name = explode(" ",$employee);
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
				$message = "User password successfully reset. Temporary password was sent to email. <br><br><span style='color:red;font-weight: bold;'>NOTE</span>: If you dont receive an mail. Please check your spam folder and mark the email as safe.";
				//$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
						
			}else {
			
			//$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
		  }

		}else {
			
			//$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message2);
		}
		return false;
	}
	
	public function fCPass($linkid, $newpass, $newpass2) {
		$data = json_encode(array("LinkId"=>$linkid,"NewPassword"=>$newpass,"NewPassword2"=>$newpass2));
		$ret = parent::serviceCall("FCPASS",$data);
		if($ret != null) {
			if($ret->Code == 0) {
				$this->ModelResponse($ret->Code, $ret->Message);
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;
	}

	public function getUserID($username){
		$sqlgetuserid = 'SELECT userid FROM tblwebusers WHERE username = ?';
		$queryuserid = $this->db->query($sqlgetuserid,array($username));
		return $queryuserid->row();
	}

}
?>