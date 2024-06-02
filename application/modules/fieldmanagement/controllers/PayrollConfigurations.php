<?php

class PayrollConfigurations extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollConfigurationsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_CONFIGURATION_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "addPayrollConfigurationsPayrollSetup";
		$listData['key'] = $page;
		$listData['data'] = $this->PayrollConfigurationsCollection->hasRowsPayrollSetupData();
		$viewData['form_payroll_setup'] = $this->load->view("forms/payrollconfigurationsform",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Configuration');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollconfigurations',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			
			$result['key'] = $listData['key'];
			$result['table_deduction_options'] = $viewData['table_deduction_options'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function loadPayrollSetup(){
		$result = array();
		$page = 'loadPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$post_data = array();
			foreach ($this->input->post() as $k => $v) {
				$post_data[$k] = $this->input->post($k,true);
			}
			$ret =  new PayrollConfigurationsCollection();
			if($ret->hasRowsPayrollSetup($post_data)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function addPayrollConfigurationsPayrollSetupForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPayrollConfigurationsPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollconfigurationsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePayrollConfigurationsPayrollSetupForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePayrollConfigurationsPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollconfigurationsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addPayrollConfigurationsPayrollSetup(){
		$result = array();
		$page = 'addPayrollConfigurationsPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				$ret =  new PayrollConfigurationsCollection();
				if($ret->addRowsPayrollSetup($_POST)) {
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
	public function updatePayrollConfigurationsPayrollSetup(){
		$result = array();
		$page = 'updatePayrollConfigurationsPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PayrollConfigurationsCollection();
				if($ret->updateRowsPayrollSetup($post_data)) {
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
	
	public function activatePayrollConfigurationsPayrollSetup(){
		$result = array();
		$page = 'activatePayrollConfigurationsPayrollSetup';
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
				$ret =  new PayrollConfigurationsCollection();
				if($ret->activeRowsPayrollSetup($post_data)) {
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
	public function deactivatePayrollConfigurationsPayrollSetup(){
		$result = array();
		$page = 'deactivatePayrollConfigurationsPayrollSetup';
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
				$ret =  new PayrollConfigurationsCollection();
				if($ret->inactiveRowsPayrollSetup($post_data)) {
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