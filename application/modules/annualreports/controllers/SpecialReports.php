<?php

class SpecialReports extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SpecialReportsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::SPECIAL_PAYROLL_REPORTS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSpecialReports";
		$listData['key'] = $page;
		$viewData['table'] = "";//$this->load->view("helpers/specialreportslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Special Payroll Reports');
			Helper::setMenu('templates/menu_template');
			Helper::setView('specialreports',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function viewSpecialReportsSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewSpecialReportsSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SpecialReportsCollection();
			//Computations for Payroll
			$year = isset($_POST['year'])?$_POST['year']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$payroll_grouping_id = isset($_POST['payroll_grouping_id'])?$_POST['payroll_grouping_id']:"";
			$bonus_type = isset($_POST['bonus_type'])?$_POST['bonus_type']:"";
			$select_type = isset($_POST['select_type'])?$_POST['select_type']:"";
			$month = isset($_POST['month'])?$_POST['month']:"";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$division_name = isset($_POST['division_name'])?$_POST['division_name']:"";
			$uses_atm = @$_POST['uses_atm'];
			//var_dump($bonus_type);
			$is_initial_salary = @$_POST['is_initial_salary'];
			$payroll_type = @$_POST['payroll_type'];
			$formData['key'] = $result['key'];
			$formData['pay_basis'] = $pay_basis;
			$formData['year'] = $year;
			$formData['select_type'] = $select_type;
			$formData['month'] = $month;
			$formData['division_name'] = $ret->divisionName($division_name);
			$formData['bonus_name'] = $ret->bonusName($bonus_type);
			$div_id = isset($_POST['division_id'])?$_POST['division_id']:"";

			$formData['signatories_a'] = $ret->getSignatoriesA('Special Payroll - Box A '.$division_name.'-'.$select_type);

			if($select_type == "Overtime"){
				$rett = $this->SpecialReportsCollection->getSign($div_id);
	
				$formData['sign'] = $rett;
				
				$rettt = $this->SpecialReportsCollection->getSign2($div_id);
				$formData['sign2'] = $rettt;
				//var_dump($formData['payroll']).die();
				$formData['division'] =  $div_id;
				$formData['signatories'] = $ret->getSignatories();
				$formData['Overtime'] = $this->SpecialReportsCollection->getOvertime($div_id, $month, $year, $pay_basis);
				//var_dump($formData['Overtime']);
				$formData['monthRange'] = $this->SpecialReportsCollection->getMonthNumber($month, $year);
				//var_dump($formData['monthRange']);
				foreach ($formData['Overtime'] as $k => $v) {
					$totalOvertime = 0;
					//var_dump($formData['Overtime'][$x]['employee_id']);
					$formData['OvertimePer'] = $this->SpecialReportsCollection->getOvertimePer([$v['employee_id']], $month, $year);
					
					for($y = 0 ; sizeof($formData['OvertimePer']) > $y ; $y++){
						$hourlyRate = $formData['OvertimePer'][$y]['hourlyRate'];
						$dtr = $this->SpecialReportsCollection->getDtr($formData['OvertimePer'][$y]['emp_id'], $formData['OvertimePer'][$y]['transaction_date']);
						
						for($t = 0 ; sizeof($dtr) > $t ; $t++){
							$ot_hrs = $dtr[$t]['ot_hrs'];
							$ot_mins = $dtr[$t]['ot_mins'] / 60;
	
							$totalHours = $ot_hrs + $ot_mins;
							if($pay_basis == "Contract of Service"){
								if($totalHours > 2){
									$totalOvertime += 200;
								}
							}else{
								$totalOvertime += $hourlyRate * $totalHours;
							}
							
							
						}
						
					}
					$formData['totalNumber'][$v['employee_id']] = $totalOvertime;
				}
				//var_dump($pay_basis);
					
					if(sizeof($formData['Overtime']) > 0){
							if($pay_basis == "Contract of Service"){
								$result['form'] = $this->load->view('helpers/employeespecialreportsovertimecos.php', $formData, TRUE);
							}else{
								$result['form'] = $this->load->view('helpers/employeespecialreportsovertime.php', $formData, TRUE);
							}
						
					}
					else{ 
						$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
					}
			}else{

				$formData['payroll'] = $ret->fetchSpecialReports($pay_basis,$year,$bonus_type,$division_id,$payroll_grouping_id,$uses_atm,$is_initial_salary,$payroll_type);
				$formData['payroll_period'] = $ret->getPayrollPeriodById($year);
				$formData['payroll_grouping'] = $ret->getPayrollGroupingById($payroll_grouping_id);
				
				
				$rett = $this->SpecialReportsCollection->getSign($div_id);
	
				$formData['sign'] = $rett;
				
				$rettt = $this->SpecialReportsCollection->getSign2($div_id);
				$formData['sign2'] = $rettt;
				//var_dump($formData['payroll']).die();
				$formData['division'] =  $div_id;
				$formData['signatories'] = $ret->getSignatories();
	
				foreach ($formData['payroll'] as $k => $v) {
					$formData['allowances'][$v['employee_id']] = $ret->getAmortizedAllowances($v['employee_id'],$year);
					$formData['otherEarnings'][$v['employee_id']] = $ret->getAmortizedOtherEarnings($v['employee_id'],$year);
					$formData['otherDeductions'][$v['employee_id']] = $ret->getAmortizedOtherDeductions($v['employee_id'],$year);
					$formData['loanDeductions'][$v['employee_id']] = $ret->getAmortizedLoans($v['employee_id'],$year);
				}
				if(sizeof($formData['payroll']) > 0){ 
					$result['form'] = $this->load->view('helpers/employeespecialreports.php', $formData, TRUE);
				}
				else{
					$result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
				}
				//Forms
			}

		}
		echo json_encode($result);
	}
}

?>