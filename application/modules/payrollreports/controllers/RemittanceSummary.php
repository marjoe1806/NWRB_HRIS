<?php

class RemittanceSummary extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('RemittanceSummaryCollection');
		$this->load->model('PayrollRegisterCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::REMITTANCE_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewRemittanceSummary";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/remittancesummarylist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Remittance Summary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('remittancesummary',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
    $fetch_data = $this->RemittanceSummaryCollection->make_datatables();
		$data = array();
		foreach($fetch_data as $k => $row)
		{
			$buttons = "";
			$buttons_data = "";
			$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
			$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
			$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
			$sub_array = array();
			$sub_array[] = $row->employee_id_number;
			$sub_array[] = $row->first_name.' '.$row->last_name;
			$sub_array[] = $row->division_id;
			$sub_array[] = $row->office_name;
			$sub_array[] = $row->position_name;
			$sub_array[] = $row->pay_basis;
			foreach($row as $k1=>$v1){
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons_data .= ' data-extension="'.$row->extension.'" ';
			$buttons .= ' <a id="viewRemittanceSummaryForm" '
								. ' class="viewRemittanceSummaryForm" style="text-decoration: none;" '
								. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewRemittanceSummaryForm" '
								. $buttons_data
								. ' > '
								. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Summary">'
								. ' <i class="material-icons">remove_red_eye</i> '
								. ' </button> '
								. ' </a> ';
			$sub_array[] = $buttons;
			$data[] = $sub_array;
		}
		$output = array(
				"draw"                  =>     intval($_POST["draw"]),
				"recordsTotal"          =>     $this->RemittanceSummaryCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->RemittanceSummaryCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewRemittanceSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewRemittanceSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/remittancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function attendanceSummaryContainer(){
		$formData = array();
		$result = array();
		$result['key'] = 'attendanceSummaryContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/remittancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewRemittanceSummaryAll(){
		die('hit');
		$formData = array();
		$result = array();
		$result['key'] = 'viewRemittanceSummaryAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			die('hit');
			$result['form'] = $this->load->view('helpers/remittancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function getEmployeeList(){
		$employee_sort = array();
		// var_export($_POST);die();
		$employees = @$this->RemittanceSummaryCollection->getEmployeeList($_POST["remittance_type"],$_POST['pay_basis'],$_POST['division_id'], $_POST['location'],$_POST["per"],date("Y-m-d",strtotime($_POST["period_covered_from"])),date("Y-m-d",strtotime($_POST["period_covered_to"])),$_POST['payroll_period_id'],$_POST['payroll_grouping_id']);
		// var_dump(json_encode($employees)); die();
		foreach ($employees as $k => $value) {
			if(isset($value['employee_number'])){
				$employees[$k]['employee_number'] = $this->Helper->decrypt(@$value['employee_number'], @$value['id']);
				$employees[$k]['employee_id_number'] = $this->Helper->decrypt(@$value['employee_id_number'], @$value['id']);
				$employees[$k]['last_name'] = $this->Helper->decrypt(@$value['last_name'], @$value['id']);
				$employees[$k]['first_name'] = $this->Helper->decrypt(@$value['first_name'], @$value['id']);
				$employees[$k]['middle_name'] = $this->Helper->decrypt(@$value['middle_name'], @$value['id']);
				$employee_sort[$k] = $employees[$k]['last_name'];
				
			}
		}
		if(sizeof($employee_sort) > 0)
			array_multisort($employee_sort, SORT_ASC, $employees);
		$formData['list'] = $employees;
		$formData['key'] = "viewEmployees";
		$formData['payroll_period'] = $this->RemittanceSummaryCollection->getPayrollPeriodById($_POST['payroll_period_id']);
		$formData['payroll_grouping'] = @$this->RemittanceSummaryCollection->getPayrollGroupingById($_POST['payroll_grouping_id']);
		// var_dump($formData['payroll_grouping']);die();
		$formData['signatories'] = $this->RemittanceSummaryCollection->getSignatories();
		$result['table'] = $this->load->view('helpers/employeesloan.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}

	function test(){
		echo date("Y-m-d",strtotime("May 21, 2019"));
	}

	function fetchLoanRowsSummaryAll(){
		// $fetch_data = $this->RemittanceSummaryCollection->make_datatables_summary_all($_POST['pay_basis'], $_POST['payroll_period'], $_POST['payroll_period_id']);
		$from = date("Y-m-d",strtotime($_POST["period_covered_from"]));
		$to = date("Y-m-d",strtotime($_POST["period_covered_to"]));
		$employee_sort = array();
		$fetch_data = @$this->RemittanceSummaryCollection->getEmployeeList($_POST["remittance_type"],$_POST['pay_basis'],$_POST['division_id'], $_POST['location'],$_POST["per"],$from,$to,$_POST['payroll_period_id']);
		$formData['remittance_type'] = @$_POST['remittance_type'];
		$formData['pay_basis'] = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
		$formData['payroll_period_id'] = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
		$formData['payroll_period'] = isset($_POST['payroll_period']) ? $_POST['payroll_period'] : null;

		foreach ($fetch_data as $k => $value) {
			$fetch_data[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$fetch_data[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$fetch_data[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$fetch_data[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$fetch_data[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$employee_sort[$k] = $fetch_data[$k]['last_name'];
		}
		array_multisort($employee_sort, SORT_ASC, $fetch_data);
		$formData['list'] = $fetch_data;

		// $formData['list'] = $fetch_data;
		$formData['data'] = $_POST;
		$formData['key'] = "viewRemittanceSummary";
		$result['table'] = $this->load->view('helpers/remittancesummaryloans.php', $formData, TRUE);
		// $result['key'] = "viewRemittanceSummary";
		// $result['success'] = sizeof($fetch_data) > 0 ? true : false;
		echo json_encode($result);
	}

	function getTimeDifference($start, $end) {
		$start  = strtotime($start);
		$end = strtotime($end);
		$diff = ($end - $start);
		$minutes = ($diff / 60) / 60;
		return number_format((float)abs($minutes), 2, '.', '');
	}

	function modal() {
		$result = $_POST;
		echo json_encode($result);
	}

	function fetchRowsSummaryAll(){
		$fetch_data = $this->RemittanceSummaryCollection->make_datatables_summary_all($_POST['pay_basis'], $_POST['payroll_period'], $_POST['payroll_period_id']);
		// $employee =
		// var_dump($fetch_data); die();
		$employees_raw = array();
		foreach($_POST['employee_id'] as $index => $value) {
			$employees_raw[$index] = $value['id'];
		}


// var_dump($employees_raw); die();
		$formData['remittance_type'] = @$_POST['remittance_type'];
		$ret = new PayrollRegisterCollection();
		$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
		$employees = sprintf("'%s'", implode("', '", @$employees_raw));
		// var_dump($employees);die();
		$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
		// $formData['key'] = $result['key'];
		$formData['pay_basis'] = $pay_basis;
		$formData['payroll_period_id'] = $payroll_period_id;
		$formData['payroll'] = $ret->fetchPayrollRegister($pay_basis,$payroll_period_id,$employees,$_POST["location"],$_POST["division_id"]);// add payroll_grouping_id and location
		$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
		$this->load->model('transactions/ProcessPayrollCollection');
		$ret2 =  new ProcessPayrollCollection();
		foreach ($employees_raw as $k => $employee_id) {
			$formData['allowances'][$employee_id] = $ret2->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'][$employee_id] = $ret2->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'][$employee_id] = $ret2->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['loanDeductions'][$employee_id] = $ret2->getAmortizedLoans($employee_id,$payroll_period_id);
		}


		// var_dump($formData); die();

		$formData['list'] = $fetch_data;
		$formData['data'] = $_POST;
		$formData['payroll_period'] = isset($_POST['payroll_period']) ? $_POST['payroll_period'] : null;
		$formData['key'] = "viewRemittanceSummary";
		$result['table'] = $this->load->view('helpers/remittancesummary.php', $formData, TRUE);
		// $result['key'] = "viewRemittanceSummary";
		// $result['success'] = sizeof($fetch_data) > 0 ? true : false;
		echo json_encode($result);
	}

	public function getpayroll_grouping_ids(){
		$ret = $this->RemittanceSummaryCollection->getpayroll_grouping_ids();
		echo json_encode($ret);
	}

	public function getLocations(){
		$ret = $this->RemittanceSummaryCollection->getLocations();
		echo json_encode($ret);
	}

	public function getPayrollGrouping(){
		$ret = $this->RemittanceSummaryCollection->getPayrollGrouping();
		echo json_encode($ret);
	}

	public function getLoans(){
		$ret = $this->RemittanceSummaryCollection->getLoans();
		echo json_encode($ret);
	}

	public function getDivisions(){
		$ret = $this->RemittanceSummaryCollection->getDivisions();
		echo json_encode($ret);
	}

}

?>
