<?php

class UserLevelConfig extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('UserLevelConfigCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewUserLevelConfig";
		$listData['key'] = $page;
		$formData['key'] = "addUserLevelConfig";
		$ret = new UserLevelConfigCollection();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		if($ret->getModules()){
			$res2 = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo2 = json_decode($res2);
		}
		else{
			$res2 = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo2 = json_decode($res2);
		}
		$formData['modules'] = $respo2;
		//var_dump($listData['list']);die();
		//var_dump($formData['modules']);die();
		$viewData['form'] = $this->load->view("forms/userlevelconfigform",$formData,TRUE);
		$viewData['table'] = $this->load->view("helpers/userlevelconfiglist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('User Account Levels');
			Helper::setMenu('templates/menu_template');
			Helper::setView('userlevelconfig',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			$result['form'] = $viewData['form'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addUserLevelConfigForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addUserLevelConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/userlevelconfigform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function updateUserLevelConfigForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'updateUserLevelConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$ret = new UserLevelConfigCollection();
			$respo = array();
			if($ret->getUserLevel($this->input->post('id'))) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}			
			if($ret->getModules()) {
				$res2 = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo2 = json_decode($res2);
			} else {
				$res2 = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo2 = json_decode($res2);
			}
			$formData['data'] = $respo;
			//var_dump($formData['data']->Data->details->modules);die();
			$formData['modules'] = $respo2;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/userlevelconfigform.php', $formData, TRUE);
		}
		
		echo json_encode($result);
	}
	public function addUserLevelConfig(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addUserLevelConfig';
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
				$ret =  new UserLevelConfigCollection();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
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
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function updateUserLevelConfig(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateUserLevelConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new UserLevelConfigCollection();
				if($ret->updateRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function activateUserLevelConfig(){
		$result = array();
		$page = 'activateUserLevelConfig';
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
				$ret =  new UserLevelConfigCollection();
				if($ret->activeRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
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
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function deactivateUserLevelConfig(){
		$result = array();
		$page = 'deactivateUserLevelConfig';
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
				$ret =  new UserLevelConfigCollection();
				if($ret->inactiveRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
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
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}

?>