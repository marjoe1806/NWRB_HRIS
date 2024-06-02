<?php

class Session extends MX_Controller {
	
	public function __construct() {
		parent::__construct();		
		$this->load->model('Helper');
		$this->load->model('HelperDao');
		$this->load->model('LoginCollection');
	}	
	
	public function index() {
		if(Helper::get('isfirstlogin') == 0) {
			Helper::sessionStartedHook('home');
		} else {
			Helper::sessionStartedHook('session/ChangePass');
		}
		Helper::setTitle('Sign In | NWRB - CHRIS');
		Helper::setView('login','',FALSE);
		Helper::setTemplate('templates/login_template');
	}	
	
	public function validate() {
		Helper::sessionStartedHook('home');
		$helperDao = new HelperDao();
		$ret = new LoginCollection();
		$user = isset($_POST['username']) ? strtoupper($_POST['username']) : "";
		$passw = isset($_POST['password']) ? $_POST['password'] : "";
		$token =  isset($_POST['ip']) ? base64_encode($user . ':' . $_POST['ip']) : "";
		$userid = $ret->getUserID($user);
		if($ret->hasRows($user,$passw,$token)) {		
			Helper::setSessionInstance(TRUE);
			$result['Code'] = "0";
			$result['Message'] = "Successfully logged in.";
			$helperDao->auditTrails(Helper::get('userid'),$result['Message']);
		} else {
			$result['Code'] = "1";
			$result['Message'] = "Incorrect password.";
			$helperDao->auditTrails($userid->userid,"Failed to login.");
		}
		echo json_encode($result);
	}
	public function validateUser(){
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new SessionDao();
				if($ret->checkUser($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function validateUsername(){
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new SessionDao();
				if($ret->checkUsername($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function logout() {
		$helperDao = new HelperDao();
		$message = "Successfully logged out.";
		$helperDao->auditTrails(Helper::get('userid'),$message);
		$ret = new LoginCollection();
		Helper::sessionTerminate();
		/*if($ret->sessionLogout()) {
			Helper::sessionTerminate();
		}*/
		self::checksession();
	}

	public static function checksession() {
		$ret = new LoginCollection();		
		
		if(!$ret->checkSessionState()) {
			Helper::sessionTerminate();
		}		
	}
	
}
?>