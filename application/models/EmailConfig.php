<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.	
*
*/
class EmailConfig extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$sql = " SELECT emailid,emailaddress,(SELECT DECRYPTER(password,'sunev8clt1234567890',emailaddress)) AS password,protocol,host,port,name,status FROM tblemailconfig";
		$query = $this->db->query($sql);
		$rows = $query->result_array();
		if(sizeof($rows) > 0){
			foreach ($rows[0] as $k => $v) {
				$str = '$this->set'.ucFirst($k).'("'.$v.'");';
				eval($str);
			}
			
		}
	}
	
	private $emailid;
	private $emailaddress;
	private $password;
	private $protocol;
	private $host;
	private $port;
	private $name;
	private $status;

	public function setEmailid($emailid){
		$this->emailid = $emailid;
	}
	public function getEmailid(){
		return $this->emailid;
	}

	public function setEmailaddress($emailaddress){
		$this->emailaddress = $emailaddress;
	}
	public function getEmailaddress(){
		return $this->emailaddress;
	}

	public function setPassword($password){
		$this->password = $password;
	}
	public function getPassword(){
		return $this->password;
	}

	public function setProtocol($protocol){
		$this->protocol = $protocol;
	}
	public function getProtocol(){
		return $this->protocol;
	}

	public function setHost($host){
		$this->host = $host;
	}
	public function getHost(){
		return $this->host;
	}
	
	public function setPort($port){
		$this->port = $port;
	}
	public function getPort(){
		return $this->port;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function getName(){
		return $this->name;
	}

	public function setStatus($status){
		$this->status = $status;
	}
	public function getStatus(){
		return $this->status;
	}
}

?>