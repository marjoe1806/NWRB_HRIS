<?php

class PAGIBIGContributions extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PAGIBIGContributionsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::TAX_CONTRIBUTIONS_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPAGIBIGContributions";
		$listData['key'] = $page;
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('PAGIBIGContributions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('pagibigcontributions',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRowsSummaryAll(){
		$fetch_data = $this->PAGIBIGContributionsCollection->make_datatables_summary_all($_GET['PayBasis'],$_GET['PayrollPeriod'], $_GET['PayrollPeriodId']);
		$formData['list'] = $fetch_data;
		$formData['payroll_period'] = isset($_GET['PayrollPeriod']) ? $_GET['PayrollPeriod'] : null;
		$formData['key'] = "viewPAGIBIGContributionsAll";
		$result['table'] = $this->load->view('helpers/pagibigcontributions.php', $formData, TRUE);
		$result['key'] = "viewPAGIBIGContributionsAll";
		$result['success'] = sizeof($fetch_data) > 0 ? true : false;
		echo json_encode($result);
	}

}

?>