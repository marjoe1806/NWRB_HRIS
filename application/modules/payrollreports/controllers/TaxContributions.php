<?php

class TaxContributions extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('TaxContributionsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::TAX_CONTRIBUTIONS_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewTaxContributions";
		$listData['key'] = $page;
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('TaxContributions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('taxcontributions',$viewData,FALSE);
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
		$fetch_data = $this->TaxContributionsCollection->make_datatables_summary_all($_GET['PayBasis'],$_GET['PayrollPeriod'], $_GET['PayrollPeriodId']);
		$formData['list'] = $fetch_data;
		$formData['payroll_period'] = isset($_GET['PayrollPeriod']) ? $_GET['PayrollPeriod'] : null;
		$formData['key'] = "viewTaxContributionsAll";
		$result['table'] = $this->load->view('helpers/taxcontributions.php', $formData, TRUE);
		$result['key'] = "viewTaxContributionsAll";
		$result['success'] = sizeof($fetch_data) > 0 ? true : false;
		echo json_encode($result);
	}

}

?>