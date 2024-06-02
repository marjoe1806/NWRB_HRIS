<?php

class ChangePass extends MX_Controller {
	
	public function __construct() {
		parent::__construct();		
		$this->load->model('Helper');
		$this->load->model('LoginCollection');
		Helper::sessionEndedHook('session');
	}
	
	public function index() {
		if(Helper::get('isfirstlogin') == 1) {
			$this->Helper->setTitle('Password Change');
			$this->Helper->setView('changepass.form','',FALSE);
			$this->Helper->setTemplate('templates/login_template');
		} else {
			redirect();
		}
	}	
	
	public function cpass() {
		$ret = new LoginCollection();
		$oldpass = isset($_REQUEST['oldpass']) ? $_REQUEST['oldpass'] : "";
		$newpass = isset($_REQUEST['newpass']) ? $_REQUEST['newpass'] : "";
		$newpass2 = isset($_REQUEST['newpass2']) ? $_REQUEST['newpass2'] : "";
		
		if($ret->cPass($oldpass,$newpass,$newpass2)) {
			$ret = new ModelResponse($ret->getCode(), $ret->getMessage());
		} else {
			$ret = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		
		echo $ret;
	}

}
?>