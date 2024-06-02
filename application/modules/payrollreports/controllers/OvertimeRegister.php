<?php

class OvertimeRegister extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeRegisterCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_REGISTER_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOvertimeRegister";
		$listData['key'] = $page;
		$viewData['table'] = ""; 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Register');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimeregister',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function viewOvertimeRegisterSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewOvertimeRegisterSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OvertimeRegisterCollection();
			//Computations for Payroll
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$payroll_grouping_id = isset($_POST['payroll_grouping_id'])?$_POST['payroll_grouping_id']:"";
			$formData['key'] = $result['key'];
			$formData['pay_basis'] = $pay_basis;
			$formData['payroll_period_id'] = $payroll_period_id;
			$formData['payroll'] = $ret->fetchOvertimeRegister(@$_POST);
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			$formData['payroll_grouping'] = $ret->getPayrollGroupById($payroll_grouping_id);
			// var_dump($formData['payroll_grouping']);die();
			$formData['signatories'] = $ret->getSignatories();
			$formData['signatories_head'] = $ret->getSignatoriesHead($payroll_grouping_id);
			if(sizeof($formData['payroll']) > 0)
				$result['form'] = $this->load->view('helpers/employeeovertimeregister.php', $formData, TRUE);
			else
				$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
			//Forms
		}
		echo json_encode($result);
	}
}

?>