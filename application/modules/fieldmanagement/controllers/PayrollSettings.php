<?php

class PayrollSettings extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollSettingsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_SETTINGS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "addPayrollSettingsPayrollSetup";
		$listData['key'] = $page;
		$ret = new PayrollSettingsCollection();
		if($ret->hasRowsPayrollSetup()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['form_payroll_setup'] = $this->load->view("forms/payrollsetupform",$listData,TRUE);
		//DeductionOptions
		if($ret->hasRowsDeductionOptions()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData2['key'] = "viewPayrollSettingsDeductionOptions";
		$listData2['list'] = $respo; 
		$viewData['table_deduction_options'] = $this->load->view("helpers/payrolldeductionoptionslist",$listData2,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Settings');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollsettings',$viewData,FALSE);
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
			$ret =  new PayrollSettingsCollection();
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
	public function addPayrollSettingsPayrollSetupForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPayrollSettingsPayrollSetup';
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
	public function updatePayrollSettingsPayrollSetupForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePayrollSettingsPayrollSetup';
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
	public function addPayrollSettingsPayrollSetup(){
		$result = array();
		$page = 'addPayrollSettingsPayrollSetup';
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
				$ret =  new PayrollSettingsCollection();
				if($ret->addRowsPayrollSetup($post_data)) {
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
	public function updatePayrollSettingsPayrollSetup(){
		$result = array();
		$page = 'updatePayrollSettingsPayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PayrollSettingsCollection();
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
	
	public function activatePayrollSettingsPayrollSetup(){
		$result = array();
		$page = 'activatePayrollSettingsPayrollSetup';
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
				$ret =  new PayrollSettingsCollection();
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
	public function deactivatePayrollSettingsPayrollSetup(){
		$result = array();
		$page = 'deactivatePayrollSettingsPayrollSetup';
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
				$ret =  new PayrollSettingsCollection();
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
	//Phil Health
	public function addPayrollSettingsDeductionOptionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPayrollSettingsDeductionOptions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/deductionoptionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePayrollSettingsDeductionOptionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePayrollSettingsDeductionOptions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/deductionoptionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addPayrollSettingsDeductionOptions(){
		$result = array();
		$page = 'addPayrollSettingsDeductionOptions';
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
				$ret =  new PayrollSettingsCollection();
				if($ret->addRowsDeductionOptions($post_data)) {
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
	public function updatePayrollSettingsDeductionOptions(){
		$result = array();
		$page = 'updatePayrollSettingsDeductionOptions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PayrollSettingsCollection();
				if($ret->updateRowsDeductionOptions($post_data)) {
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
	
	public function activatePayrollSettingsDeductionOptions(){
		$result = array();
		$page = 'activatePayrollSettingsDeductionOptions';
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
				$ret =  new PayrollSettingsCollection();
				if($ret->activeRowsDeductionOptions($post_data)) {
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
	public function deactivatePayrollSettingsDeductionOptions(){
		$result = array();
		$page = 'deactivatePayrollSettingsDeductionOptions';
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
				$ret =  new PayrollSettingsCollection();
				if($ret->inactiveRowsDeductionOptions($post_data)) {
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