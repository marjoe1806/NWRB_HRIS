<?php

class LocalRecord extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LocalRecordCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewLocalRecord";
		$listData['key'] = $page;
		$ret = new LocalRecordCollection();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/localrecordlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Local Records');
			Helper::setMenu('templates/menu_template');
			Helper::setView('localrecord',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addLocalRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addLocalRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/localrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateLocalRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateLocalRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/localrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function previewLocalRecord(){
		$formData = array();
		$result = array();
		$result['key'] = 'previewLocalRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/localrecordpreview.php', $helperData, TRUE);
		}
		echo json_encode($result);
	}
	public function addLocalRecord(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addLocalRecord';
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
				$ret =  new LocalRecordCollection();
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
	public function updateLocalRecord(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateLocalRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LocalRecordCollection();
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
	public function getParticipants(){
		//var_dump($_POST);die();
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
				$ret =  new LocalRecordCollection();
				if($ret->fetchParticipants($post_data['id'])) {
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
}

?>