<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.	
*
*/
class Model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('UserAccount');
		$this->load->model('ModuleRels');
	}
	
	/*
	*
	* Reflects the UserAccount class and its values
	*
	*/
	public function UserAccount() {
		return $this->UserAccount;
	}
	
	/*
	*
	* Reflects the ModuleRels class and its values
	*
	*/
	public function ModuleRels() {
		return $this->ModuleRels;
	}
}

?>