<?php

class PayrollGrouping extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollGroupingCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		// Helper::rolehook(ModuleRels::BUDGET_CLASSIFICATIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayrollGrouping";
		$listData['key'] = $page;
		$ret = new PayrollGroupingCollection();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/payrollgroupinglist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Grouping');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollgrouping',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addPayrollGroupingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPayrollGrouping';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollgroupingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePayrollGroupingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePayrollGrouping';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollgroupingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addPayrollGrouping(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addPayrollGrouping';
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
				$ret =  new PayrollGroupingCollection();
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
	public function updatePayrollGrouping(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updatePayrollGrouping';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PayrollGroupingCollection();
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
	
	public function activatePayrollGrouping(){
		$result = array();
		$page = 'activatePayrollGrouping';
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
				$ret =  new PayrollGroupingCollection();
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
	public function deactivatePayrollGrouping(){
		$result = array();
		$page = 'deactivatePayrollGrouping';
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
				$ret =  new PayrollGroupingCollection();
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
	public function getActivePayrollGrouping(){
		$result = array();
		$page = 'getActivePayrollGrouping';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new PayrollGroupingCollection();
			if($ret->hasRowsActive()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
	} 
	public function getPayrollGroupingById(){
		$result = array();
		$page = 'getPayrollGroupingById';
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
				$ret =  new PayrollGroupingCollection();
				if($ret->hasRowsById($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
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