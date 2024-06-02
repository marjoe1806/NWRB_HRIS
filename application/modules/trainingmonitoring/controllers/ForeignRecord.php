<?php

class ForeignRecord extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ForeignRecordCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewForeignRecord";
		$listData['key'] = $page;
		$ret = new ForeignRecordCollection();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/foreignrecordlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Foreign Records');
			Helper::setMenu('templates/menu_template');
			Helper::setView('foreignrecord',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addForeignRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addForeignRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/foreignrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateForeignRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateForeignRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/foreignrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function previewForeignRecord(){
		$formData = array();
		$result = array();
		$result['key'] = 'previewForeignRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/foreignrecordpreview.php', $helperData, TRUE);
		}
		echo json_encode($result);
	}
	public function addForeignRecord(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addForeignRecord';
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
				$ret =  new ForeignRecordCollection();
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
	public function updateForeignRecord(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateForeignRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ForeignRecordCollection();
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
				$ret =  new ForeignRecordCollection();
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