
<?php

class PhilHealth extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('RemittanceCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::REMITTANCE_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPhilHealth";
		$listData['key'] = $page;
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('PhilHealth Contribution');
			Helper::setMenu('templates/menu_template');
			Helper::setView('philhealth',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function viewPhilHealthSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPhilHealthSummary';
		if (!$this->input->is_ajax_request())
		   show_404();
		else {
			$ret =  new RemittanceCollection();
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$week_period = isset($_POST['week_period'])?$_POST['week_period']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$payroll_grouping_id = isset($_POST['payroll_grouping_id'])?$_POST['payroll_grouping_id']:"";
			$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$uses_atm = @$_POST['uses_atm'];
			$is_initial_salary = @$_POST['is_initial_salary'];
			$payroll_type = @$_POST['payroll_type'];
			$formData['uses_atm'] = $uses_atm;
			$formData['is_initial_salary'] = $is_initial_salary;
			$formData['payroll_type'] = $payroll_type; 
			$formData['key'] = $result['key'];
			$formData['pay_basis'] = $pay_basis;
			$formData['payroll_period_id'] = $payroll_period_id;
			$formData['payroll'] = $ret->fetchPayrollRegister($pay_basis,$payroll_period_id,$division_id);
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			$formData['signatories'] = $ret->getSignatories();
			foreach ($formData['payroll'] as $k => $v) {
				$formData['payrollinfo'][$v['employee_id']] = $ret->payrollinfo($v['employee_id']);
			}
			if(sizeof($formData['payroll']) > 0){
                if($pay_basis == "Permanent"){
					$result['form'] = $this->load->view('helpers/philhealth.php', $formData, TRUE);
                }else{                    
					$result['form'] = $this->load->view('helpers/philhealth_cos.php', $formData, TRUE);
                }
			}else{
				$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
			}
		}
		echo json_encode($result);
	}
}

?>