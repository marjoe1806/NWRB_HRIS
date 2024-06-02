<?php
	class Userprofile_model extends Helper {
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
		public function cPass($oldpass,$newpass,$newpass2) {
			//Check Oldpassword
			$sql = " SELECT * FROM tblwebusers WHERE userid = ? AND password = ?";
			//var_dump($params);die();
			$oldpass2 = Helper::encrypt($oldpass,Helper::get('username'));
			$sqlparams = array(Helper::get('userid'),$oldpass2);
			$query = $this->db->query($sql,$sqlparams);
			$user_rows = $query->result_array();
			$code = "1";
			$message = "Invalid old password!";
			if(sizeof($user_rows) > 0){
				//Update
				$helperDao = new HelperDao();
				$code = "1";
				$message = "Failed to change password!";
				//$length = 5;
				//$randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
				//var_dump($randomletter);die();
				//$params['username'] = strtoupper($params['username']);
				//$password = Helper::encrypt($randomletter,$params['username']);
				//$params['password'] = $password;
				/*$params['registrationstatus'] = "APPROVED";*/
				$password = Helper::encrypt($newpass,Helper::get('username'));
				$params2 = array('password'=>$password);
				$sysmessages = new SystemMessages();
				$data = array();
				$this->db->where('userid', Helper::get('userid'));
				if ($this->db->update('tblwebusers',$params2) === FALSE){
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
							
				}	
				else {
					$code = "0";
					$message = "Password successfully changed!";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}		
			}
			else{

				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to update.";
			$params['status'] = "ACTIVE";
			$params['username'] = strtoupper($params['username']);
			$params['email'] = Helper::encrypt($params['email'],$params['username']);
			$sysmessages = new SystemMessages();
			$this->db->where('userid', $params['userid']);
			if ($this->db->update('tblwebusers',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$sysmessages->init(MessageRels::EMAIL_USER_UPDATE);
				$message = $sysmessages->getMessage();
				$employee_name = explode(" ",$params['employee_name']);
				$message = str_replace("<fname>",$employee_name[0],$message);
				$changelogs = "\n NAME: ". $params['employee_name']
							. "\n POSITION: ". $params['employee_position']
							. "\n DIVISION: ". $params['employee_division']
							. "\n EMAIL: ". $params['email'];
				$message = str_replace("<changelogs>",$changelogs,$message);
				//var_dump();die();
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$params['email'],
					$message
				);
				$code = "0";
				$message = "Successfully updated User.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		public function updateUserPhoto($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User failed to update.";
			$sysmessages = new SystemMessages();
			$data['userid'] 	= isset($params['userid'])? $params['userid'] : "";
			$data['photopath'] 	= isset($params['photopath'])? $params['photopath'] : "";
			$this->db->where('userid', $params['userid']);
			if ($this->db->update('tblwebusers',$data) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);		
			}	
			else {
				$code = "0";
				$message = "Successfully updated User.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$_SESSION['photopath'] = $data['photopath'];
				return true;
			}
			return false;
		}
	}
?>