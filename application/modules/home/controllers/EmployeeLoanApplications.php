<?php

class EmployeeLoanApplications extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeeLoanApplicationsCollections');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewLoanApplications";
		$listData['key'] = $page;
		$ret = new EmployeeLoanApplicationsCollections();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/employeeloanapplicationslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('My Loan Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('employeeloanapplications',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
}

?>