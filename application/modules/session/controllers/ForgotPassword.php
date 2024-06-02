<?php

class ForgotPassword extends MX_Controller {
	
	public function __construct() {
		parent::__construct();		
		$this->load->model('Helper');
		$this->load->model('LoginCollection');
		$this->load->model("helperDao");
	}
	
	public function index() {
		Helper::setTitle('Forgot Password | NWRB - CHRIS');
		Helper::setView('forgot.pass','',FALSE);
		Helper::setTemplate('templates/login_template');
	}

	public function sendemail() {
		$ret = new LoginCollection();
		$post = $this->input->post();
		if($this->input->server('REQUEST_METHOD') == 'POST' && $post != null) {			
			// if($ret->fPass($post['username'],$post['email'], base_url())) {
			if($ret->fPass($post['username'], base_url())) {
				if(!empty($post['usermgmt']) && $post['usermgmt'] == 1) {
					echo new ModelResponse($ret->getCode(), "Reset password link has been sent to email.");
				} else {
					$response['serverMessage'] = $ret->getMessage();
					$response['serverCode'] = $ret->getCode();				
					Helper::setTitle('Forgot Password | NWRB - CHRIS');
					Helper::setView('forgot.pass',$response,FALSE);
					Helper::setTemplate('templates/blank_template');
				}
			} else {
				$response['serverMessage'] = $ret->getMessage();
				$response['serverCode'] = $ret->getCode();				
				Helper::setTitle('Forgot Password | NWRB - CHRIS');
				Helper::setView('forgot.pass',$response,FALSE);
				Helper::setTemplate('templates/blank_template');
			}			
		} else {
			show_404();
		}
	}

	public function testsendmail(){
		$helperDao = new HelperDao();
		return $helperDao->sendMail('test','veil.cusi@gmail.com', "test mail");
	}
}
?>