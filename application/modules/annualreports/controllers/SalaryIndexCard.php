<?php

class SalaryIndexCard extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SalaryIndexCardCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYSLIP_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSalaryIndexCard";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/salaryindexcardlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('SalaryIndexCard');
			Helper::setMenu('templates/menu_template');
			Helper::setView('salaryindexcard',$viewData,FALSE);
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
		$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
		$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
		$pay_basis = isset($_GET['PayBasis'])?$_GET['PayBasis']:"";
        $payroll_period_id = isset($_GET['PayrollPeriod'])?$_GET['PayrollPeriod']:"";
        $location_id = @$_GET['Location'];
        $fetch_data = $this->SalaryIndexCardCollection->make_datatables($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id);
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();    
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->first_name.' '.$row->last_name;  
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->salary,2);
            $sub_array[] = $row->contract;
           	foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons .= ' <a id="viewSalaryIndexCardForm" ' 
            		  . ' class="viewSalaryIndexCardForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewSalaryIndexCardForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->SalaryIndexCardCollection->get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id),  
            "recordsFiltered"     	=>     $this->SalaryIndexCardCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewSalaryIndexCardForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewSalaryIndexCard';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryIndexCardCollection();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$formData['key'] = $result['key'];
			$id = @$_POST['id'];
			$formData['payroll'] = $ret->getPayrollById($id);

			// var_dump($id);die();
			if(sizeof($formData['payroll']) > 0){
				$emp_id = $formData['payroll'][0]['employee_id'];
				$formData['payroll'][0]['employee_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_number'],$emp_id);
				$formData['payroll'][0]['first_name'] = $this->Helper->decrypt($formData['payroll'][0]['first_name'],$emp_id);
				$formData['payroll'][0]['last_name'] = $this->Helper->decrypt($formData['payroll'][0]['last_name'],$emp_id);
				$formData['payroll'][0]['middle_name'] = $this->Helper->decrypt($formData['payroll'][0]['middle_name'],$emp_id);
				$formData['payroll'][0]['employee_id_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_id_number'],$emp_id);
			}
			//var_dump($formData['payroll'][0]);die();
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			/*$formData['cutoff'] =  $ret->getPayrollPeriodCutoffById($cutoff_id);*/
			$this->load->model('transactions/ProcessPayrollCollection');
			$ret2 =  new ProcessPayrollCollection();
			$formData['allowances'] = $ret2->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret2->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret2->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret2->getAmortizedLoans($employee_id,$payroll_period_id);
			$result['form'] = $this->load->view('helpers/employeesalaryindexcard.php', $formData, TRUE);
			// var_dump($formData);die();
			//Forms
		}
		echo json_encode($result);
	}
	public function salaryindexcardContainer(){
		$formData = array();
		$result = array();
		$result['key'] = 'salaryindexcardContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			/*if($ret->computePayrollProcess($employee_id,$payroll_period_id)) {*/
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeesalaryindexcard.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function getPayrollData(){
		$result = array();
		$result['key'] = 'getPayrollData';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryIndexCardCollection();
			$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
			$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
			$pay_basis = isset($_GET['PayBasis'])?$_GET['PayBasis']:"";
	        $payroll_period_id = isset($_GET['PayrollPeriod'])?$_GET['PayrollPeriod']:"";
			/*if($ret->computePayrollProcess($employee_id,$payroll_period_id)) {*/
			$result['data'] = $ret->fetchPayroll($employment_status,$employee_id,$pay_basis,$payroll_period_id);
			//var_dump($result['data']);die();
			foreach($result['data'] as $k => $row)  
        	{ 
				$result['data'][$k]['employee_number'] = $this->Helper->decrypt($row['employee_number'],$row['employee_id']);
	        	$result['data'][$k]['employee_id_number'] = $this->Helper->decrypt($row['employee_id_number'],$row['employee_id']);
	        	$result['data'][$k]['first_name'] = $this->Helper->decrypt($row['first_name'],$row['employee_id']);
	        	$result['data'][$k]['middle_name'] = $this->Helper->decrypt($row['middle_name'],$row['employee_id']);
	        	$result['data'][$k]['last_name'] = $this->Helper->decrypt($row['last_name'],$row['employee_id']);
	        }
		}
		//var_dump($result);die();
		echo json_encode($result);
	}
	public function viewSalaryIndexCardSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewSalaryIndexCardSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryIndexCardCollection();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			// var_dump($pay_basis);die();
			/*if($ret->computePayrollProcess($employee_id,$payroll_period_id)) {*/
			$formData['key'] = $result['key'];
			//var_dump($pay_basis);die();
			$formData['payroll'] = $ret->getPayrollFilter($employee_id,$payroll_period_id,$pay_basis);
			if(sizeof($formData['payroll']) > 0){
				$emp_id = $formData['payroll'][0]['employee_id'];
				$formData['payroll'][0]['employee_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_number'],$emp_id);
				$formData['payroll'][0]['first_name'] = $this->Helper->decrypt($formData['payroll'][0]['first_name'],$emp_id);
				$formData['payroll'][0]['last_name'] = $this->Helper->decrypt($formData['payroll'][0]['last_name'],$emp_id);
				$formData['payroll'][0]['middle_name'] = $this->Helper->decrypt($formData['payroll'][0]['middle_name'],$emp_id);
				$formData['payroll'][0]['employee_id_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_id_number'],$emp_id);
			}
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			// $formData['cutoff'] =  $ret->getPayrollPeriodCutoffById($cutoff_id);
			//var_dump($formData['allowances']);die();

			$this->load->model('transactions/ProcessPayrollCollection');
			$ret2 =  new ProcessPayrollCollection();
			$formData['allowances'] = $ret2->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret2->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret2->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret2->getAmortizedLoans($employee_id,$payroll_period_id);
			// var_dump($formData['allowances']);die();
			$result['form'] = $this->load->view('helpers/employeesalaryindexcard.php', $formData, TRUE);
			/*} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}*/
			//Forms
		}
		echo json_encode($result);
	}
}

?>