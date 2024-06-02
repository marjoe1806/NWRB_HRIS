
<?php

class PayrollRegister extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollRegisterCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_REGISTER_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayrollRegister";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/payrollregisterlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Register');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollregister',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function viewPayrollRegisterSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPayrollRegisterSummary';
		if (!$this->input->is_ajax_request())
		   show_404(); 
		else {
			$ret =  new PayrollRegisterCollection();
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$week_period = isset($_POST['week_period'])?$_POST['week_period']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$payroll_grouping_id = isset($_POST['payroll_grouping_id'])?$_POST['payroll_grouping_id']:"";
			$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$division_name = isset($_POST['division_name'])?$_POST['division_name']:"";
			$uses_atm = @$_POST['uses_atm'];
			$is_initial_salary = @$_POST['is_initial_salary'];
			$payroll_type = @$_POST['payroll_type'];
			$formData['division_name2'] = $division_name; 
			$formData['uses_atm'] = $uses_atm; 
			$formData['is_initial_salary'] = $is_initial_salary;
			$formData['payroll_type'] = $payroll_type; 
			$formData['key'] = $result['key'];
			$formData['pay_basis'] = $pay_basis;
			$formData['payroll_period_id'] = $payroll_period_id;
			$before_period = $payroll_period_id - 1;
			$formData['payroll'] = $ret->fetchPayrollRegister($pay_basis,$payroll_period_id,$division_id);
			//var_dump($before_period); 
			

			$formData['additional_header1'] = $ret->fetchHeaders1($pay_basis,$payroll_period_id,$division_id);

			$formData['additional_header2'] = $ret->fetchHeaders2($pay_basis,$payroll_period_id,$division_id);
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			//var_dump($formData['payroll_2']);
			$formData['signatories_a'] = $ret->getSignatoriesA('Payroll Register - Box A '.$division_name);
			//var_dump($formData['signatories_a']);
			$formData['signatories'] = $ret->getSignatories();
			foreach ($formData['payroll'] as $k => $v) {
				$formData['ut'][$v['employee_id']] = $ret->getUTV2($v['employee_id'],$payroll_period_id);
				$formData['allowances'][$v['employee_id']] = $ret->getAmortizedAllowances($v['employee_id'],$payroll_period_id);
				$formData['otherEarnings'][$v['employee_id']] = $ret->getAmortizedOtherEarnings($v['employee_id'],$payroll_period_id);
				$formData['otherDeductions'][$v['employee_id']] = $ret->getAmortizedOtherDeductions($v['employee_id'],$payroll_period_id);
				$formData['loanDeductions'][$v['employee_id']] = $ret->getAmortizedLoans($v['employee_id'],$payroll_period_id);
				$formData['tax'][$v['employee_id']] = $ret->getTax($v['employee_id'],$payroll_period_id);
				$formData['payrollinfo'][$v['employee_id']] = $ret->payrollinfo($v['employee_id']);
				//$formData['payroll_2'][$v['employee_id']] = $ret->getpayrolltransaction($before_period,$v['employee_id']);
				//print_r($formData['tax']);
			}	
			//var_dump($formData['ut']);die();
			 
			if(sizeof($formData['payroll']) > 0){ 
				if($formData['pay_basis'] == 'Contract of Service'){
					$result['form'] = $this->load->view('helpers/emp_payroll_register_period_1_cos.php', $formData, TRUE);
				}else{
					// if(substr(substr($formData['payroll_period'][0]['start_date'],8),0,2) == "01")
						$result['form'] = $this->load->view('helpers/emp_payroll_register_period_1.php', $formData, TRUE);
					// $result['form'] = $this->load->view('helpers/emp_payroll_register_period_2.php', $formData, TRUE);
				}
			}else{
				$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
			}
		}
		echo json_encode($result);
	}

	public function viewPayrollRegisterAllSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPayrollRegisterAllSummary';
		if (!$this->input->is_ajax_request())
		   show_404();
		else {
			$ret =  new PayrollRegisterCollection();
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$week_period = isset($_POST['week_period'])?$_POST['week_period']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$payroll_grouping_id = isset($_POST['payroll_grouping_id'])?$_POST['payroll_grouping_id']:"";
			$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$division_name = isset($_POST['division_name'])?$_POST['division_name']:"";
			$uses_atm = @$_POST['uses_atm'];
			$is_initial_salary = @$_POST['is_initial_salary'];
			$payroll_type = @$_POST['payroll_type'];
			$formData['division_name2'] = $division_name; 
			$formData['uses_atm'] = $uses_atm; 
			$formData['is_initial_salary'] = $is_initial_salary;
			$formData['payroll_type'] = $payroll_type; 
			$formData['key'] = $result['key'];
			$formData['pay_basis'] = $pay_basis;
			$formData['payroll_period_id'] = $payroll_period_id;
			$before_period = $payroll_period_id - 1; 
			$formData['payroll'] = $ret->fetchPayrollRegisterAll($pay_basis,$payroll_period_id);
			//var_dump($before_period);
			

			$formData['additional_header1'] = $ret->fetchAllHeaders1($pay_basis,$payroll_period_id);

			$formData['additional_header2'] = $ret->fetchAllHeaders2($pay_basis,$payroll_period_id);
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			//var_dump($formData['payroll_2']);
			$formData['signatories_a'] = $ret->getSignatoriesA('Payroll Register - Box A '.$division_name);
			//var_dump($formData['signatories_a']);
			$formData['signatories'] = $ret->getSignatories();
			foreach ($formData['payroll'] as $k => $v) {
				$formData['allowances'][$v['employee_id']] = $ret->getAmortizedAllowances($v['employee_id'],$payroll_period_id);
				$formData['otherEarnings'][$v['employee_id']] = $ret->getAmortizedOtherEarnings($v['employee_id'],$payroll_period_id);
				$formData['otherDeductions'][$v['employee_id']] = $ret->getAmortizedOtherDeductions($v['employee_id'],$payroll_period_id);
				$formData['loanDeductions'][$v['employee_id']] = $ret->getAmortizedLoans($v['employee_id'],$payroll_period_id);
				$formData['payrollinfo'][$v['employee_id']] = $ret->payrollinfo($v['employee_id']);
				//$formData['payroll_2'][$v['employee_id']] = $ret->getpayrolltransaction($before_period,$v['employee_id']);
				//var_dump($formData['ut']);die();
								
			}	

						 
			if(sizeof($formData['payroll']) > 0){ 

					$result['form'] = $this->load->view('helpers/emp_payroll_register_period_all.php', $formData, TRUE);
			
			}else{
				$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
			}
		}
		echo json_encode($result);
	}



	public function updatePayrollRegisterSummary(){
		// var_dump($_POST);die();
		$result = array();
		$page = 'updatePayrollRegisterSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
					if ($k === 'forUpdateDataHeader') {
						foreach ($post_data[$k]as $k1 => $v1) {
							$ret =  new PayrollRegisterCollection();
							if($ret->updateRowsHeader($v1)) {
								$res = new ModelResponse($ret->getCode(), $ret->getMessage());
							} else {
								$res = new ModelResponse($ret->getCode(), $ret->getMessage());
							}
							$result = json_decode($res,true); 
						}
					}else{
						foreach ($post_data[$k] as $k1 => $v1) {
							$ret =  new PayrollRegisterCollection();
							if(isset($v1['id'])){
								if($ret->updateRows($v1)) {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								} else {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								}
							}
							$result = json_decode($res,true);
						}
					}
				}
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function deletePayrollRegisterSummary(){
		//var_dump($this->input->post());
		$result = array();
		$page = 'deletePayrollRegisterSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				
						$ret =  new PayrollRegisterCollection();
						if($ret->deleteRowsHeader($_POST['pay_basis'], $_POST['period'], $_POST['division'], $_POST['type'])) {
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						} else {
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						}
						$result = json_decode($res,true); 
						if($_POST['type'] == "header_1"){ 
							foreach ($_POST['id']as $k1 => $v1) {
								if($ret->updatePayrollCol1( $v1)) {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								} else {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								}
							}
						}else{
							foreach ($_POST['id']as $k1 => $v1) {
								if($ret->updatePayrollCol2( $v1)) {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								} else {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								}
							}
						}
						
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}

?>