<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.	
*
*/
class SystemMessages extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	public function init($id){
		$sql = " SELECT * FROM tblmessages WHERE id = ?";
		$params = array('id' => $id);
		$query = $this->db->query($sql,$params);
		$rows = $query->result_array();
		if(sizeof($rows) > 0){
			foreach ($rows[0] as $k => $v) {
				$str = '$this->set'.ucFirst($k).'("'.$v.'");';
				eval($str);
			}
			
		}
	}
	private $id;
	private $message;
	private $code;
	private $description;
	private $subject;
	private $status;

	public function setId($id){
		$this->id = $id;
	}
	public function getId(){
		return $this->id;
	}

	public function setMessage($message){
		$this->message = $message;
	}
	public function getMessage(){
		return $this->message;
	}

	public function setCode($code){
		$this->code = $code;
	}
	public function getCode(){
		return $this->code;
	}

	public function setDescription($description){
		$this->description = $description;
	}
	public function getDescription(){
		return $this->description;
	}

	public function setSubject($subject){
		$this->subject = $subject;
	}
	public function getSubject(){
		return $this->subject;
	}
	
	public function setStatus($status){
		$this->status = $status;
	}
	public function getStatus(){
		return $this->Status;
	}

}

?>