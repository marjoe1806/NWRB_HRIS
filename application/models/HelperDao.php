<?php
class HelperDao extends Helper {
	
	public function __construct() {
		$this->load->database();
		//ModelResponse::busy();
		$this->load->model('EmailConfig');
	}
			
	public function auditTrails($userid,$log){
		$data = array();
		//$code = "1";
		//$message = "Audit trail failed to insert.";
		$sql = " INSERT INTO tblaudittrail(userid,log,ip)VALUES(?,?,?)";
		$params = array($userid,$log,$this->getRealIpAddr());
		// var_dump($params);
		$query = $this->db->query($sql,$params);
		if($this->db->affected_rows() > 0){
			//$code = "0";
			//$message = "Successfully inserted audit trail.";
			//$this->ModelResponse($code, $message);			
			//return true;		
		}	
		else {
			//$this->ModelResponse($code, $message);
		}
		//return false;
	}
	public function getUser($param){
		$data = array();
		//$code = "1";
		//$message = "Audit trail failed to insert.";
		$sql = " SELECT * FROM tblwebusers WHERE ? IN(userid,username,email)";
		$params = array($param);
		$query = $this->db->query($sql,$params);
		$user_rows = $query->result_array();
		return $user_rows;
	}
	public function getUserDetails($param){
		$data = array();
		//$code = "1";
		//$message = "Audit trail failed to insert.";
		// $sql = " SELECT * FROM tblwebusers WHERE email = ?";
		$sql = " SELECT * FROM tblwebusers WHERE username = ?";
		$params = array($param);
		$query = $this->db->query($sql,$params);
		$user_rows = $query->row_array();
		return $user_rows;
	}
	public function isUserExists($param){
		$data = array();
		//$code = "1";
		//$message = "Audit trail failed to insert.";
		$sql = " SELECT * FROM tblwebusers WHERE ? IN(username,email)";
		$params = array($param);
		$query = $this->db->query($sql,$params);
		$user_rows = $query->result_array();
		if(sizeof($user_rows) > 0){
			return true;		
		}	
		return false;
	}
	public function addUpdateHistory($table_name,$column_name,$reference_id,$new_value,$modified_by){
		$sql1 	= " SELECT ".$column_name." FROM ".$table_name." WHERE id = '".$reference_id."'";
		$query1 = $this->db->query($sql1);
		$rows1 	= $query1->result_array();
		$previous_value = $rows1[0][$column_name];
		//var_dump($previous_value." : ".$new_value);die();
		if($new_value != $previous_value){
			$sql2 = " INSERT INTO tblupdatehistory(table_name,column_name,reference_id,previous_value,new_value,modified_by)
					VALUES('".$table_name."','".$column_name."','".$reference_id."','".$previous_value."','".$new_value."','".$modified_by."') ";	
			$query2 = $this->db->query($sql2);
		}

	}

	//*********************NEW************************
	public function sendMail($subject,$to_email,$message)
	{
		$data = array();
		$emailconfig = new EmailConfig();
		$config = Array(
			'protocol' 	=> $emailconfig->getProtocol(),
			'smtp_host' => $emailconfig->getHost(),
			'smtp_port' => $emailconfig->getPort(),
			'smtp_user' => $emailconfig->getEmailaddress(), // change it to yours
			'smtp_pass' => $emailconfig->getPassword(), // change it to yours
			'mailtype' 	=> 'text',
			'charset' 	=> 'utf-8',
			'wordwrap' 	=> TRUE
		);
		$message = str_replace("<webmaster>",$emailconfig->getName(),$message);
		$this->load->library('email', $config);
		$this->email->initialize($config);
      	$this->email->set_newline("\r\n");
      	$this->email->from($emailconfig->getEmailaddress(),$emailconfig->getName()); // change it to yours
      	$this->email->to($to_email);// change it to yours
      	$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send()){
			$code 	 = "0";
			$message = "Mail successfully sent.";
		}else{
	    	$code	 = "1";
	    	$message = "Failed to send mail";
	    	$error = $this->email->print_debugger();
	    }
	    $data['code'] = $code;
	   	$data['message'] = $message;
	   	return $data; 

	}

	//*********************NEW************************
	public function sendMail2($subject,$to_email,$message)
	{
		$data = array();
		$emailconfig = new EmailConfig();
		$config = Array(
			'protocol' 	=> $emailconfig->getProtocol(),
			'smtp_host' => $emailconfig->getHost(),
			'smtp_port' => $emailconfig->getPort(),
			'smtp_user' => $emailconfig->getEmailaddress(), // change it to yours
			'smtp_pass' => $emailconfig->getPassword(), // change it to yours
			'mailtype' 	=> 'html',
			'charset' 	=> 'utf-8',
			'wordwrap' 	=> TRUE
		);
		$message = str_replace("<webmaster>",$emailconfig->getName(),$message);
		$this->load->library('email', $config);
		$this->email->initialize($config);
      	$this->email->set_newline("\r\n");
      	$this->email->from($emailconfig->getEmailaddress(),$emailconfig->getName()); // change it to yours
      	$this->email->to($to_email);// change it to yours
      	$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send()){
			$code 	 = "0";
			$message = "Mail successfully sent.";
		}else{
	    	$code	 = "1";
	    	$message = "Failed to send mail";
	    	$error = $this->email->print_debugger();
	    }
	    $data['code'] = $code;
	   	$data['message'] = $message;
	   	return $data; 

	}

	
	function getRealIpAddr()
	{
	    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
	    return $ip;
	}
}
?>