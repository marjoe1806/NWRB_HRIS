<?php

class Contributions extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ContributionsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::CONTRIBUTIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewContributionsGSIS";
		//GSIS
		$listData['key'] = $page;
		$ret = new ContributionsCollection();
		if($ret->hasRowsGSIS()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table_gsis'] = $this->load->view("helpers/contributionsgsislist",$listData,TRUE);
		//PhilHealth
		if($ret->hasRowsPhilHealth()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData2['key'] = "viewContributionsPhilHealth";
		$listData2['list'] = $respo; 
		$viewData['table_philhealth'] = $this->load->view("helpers/contributionsphilhealthlist",$listData2,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Contributions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('contributions',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table_gsis'] = $viewData['table_gsis'];
			$result['table_philhealth'] = $viewData['table_philhealth'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addContributionsGSISForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addContributionsGSIS';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/contributionsgsisform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateContributionsGSISForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateContributionsGSIS';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/contributionsgsisform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addContributionsGSIS(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addContributionsGSIS';
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
				$ret =  new ContributionsCollection();
				if($ret->addRowsGSIS($post_data)) {
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
	public function updateContributionsGSIS(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateContributionsGSIS';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ContributionsCollection();
				if($ret->updateRowsGSIS($post_data)) {
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
	
	public function activateContributionsGSIS(){
		$result = array();
		$page = 'activateContributionsGSIS';
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
				$ret =  new ContributionsCollection();
				if($ret->activeRowsGSIS($post_data)) {
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
	public function deactivateContributionsGSIS(){
		$result = array();
		$page = 'deactivateContributionsGSIS';
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
				$ret =  new ContributionsCollection();
				if($ret->inactiveRowsGSIS($post_data)) {
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
	//Phil Health
	public function addContributionsPhilHealthForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addContributionsPhilHealth';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/contributionsphilhealthform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateContributionsPhilHealthForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateContributionsPhilHealth';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/contributionsphilhealthform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addContributionsPhilHealth(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addContributionsPhilHealth';
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
				$ret =  new ContributionsCollection();
				if($ret->addRowsPhilHealth($post_data)) {
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
	public function updateContributionsPhilHealth(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateContributionsPhilHealth';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ContributionsCollection();
				if($ret->updateRowsPhilHealth($post_data)) {
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
	
	public function activateContributionsPhilHealth(){
		$result = array();
		$page = 'activateContributionsPhilHealth';
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
				$ret =  new ContributionsCollection();
				if($ret->activeRowsPhilHealth($post_data)) {
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
	public function deactivateContributionsPhilHealth(){
		$result = array();
		$page = 'deactivateContributionsPhilHealth';
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
				$ret =  new ContributionsCollection();
				if($ret->inactiveRowsPhilHealth($post_data)) {
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