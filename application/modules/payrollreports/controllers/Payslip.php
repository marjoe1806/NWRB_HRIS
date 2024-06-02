<?php

class Payslip extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayslipCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYSLIP_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayslip";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/paysliplist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payslip Summary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payslip',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} else{
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
        $division_id = @$_GET['DivisionId'];
		$self = 0;
		if(in_array(17001,$_SESSION["sessionModules"])) $self = 1;
        $fetch_data = $this->PayslipCollection->make_datatables($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self);
        $data = array();  
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();    
           	foreach($row as $k1=>$v1)
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons_data .= ' data-extension="'.$row->extension.'" ';
            $buttons .= ' <a id="viewPayslipForm" ' 
            		  . ' class="viewPayslipForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewPayslip" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
	        $sub_array[] = $buttons;
            $sub_array[] = $row->emp_number;
            $sub_array[] = $row->first_name.' '.$row->last_name;  
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->salary,2);
            $sub_array[] = $row->employment_status;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->PayslipCollection->get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id, $self),  
            "recordsFiltered"     	=>     $this->PayslipCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id, $self),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
	public function payslipContainer(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$result['key'] = 'payslipContainer';
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeepayslipV2.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function getPayrollData(){
		$result = array();
		$result['key'] = 'getPayrollData';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$ret =  new PayslipCollection();
			$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
			$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
			$pay_basis = isset($_GET['PayBasis'])?$_GET['PayBasis']:"";
			$payroll_period_id = isset($_GET['PayrollPeriod'])?$_GET['PayrollPeriod']:"";
			$result['data'] = $ret->fetchPayroll($employment_status,$employee_id,$pay_basis,$payroll_period_id);
			foreach($result['data'] as $k => $row){ 
				$result['data'][$k]['employee_number'] = $this->Helper->decrypt($row['employee_number'],$row['employee_id']);
	        	$result['data'][$k]['employee_id_number'] = $this->Helper->decrypt($row['employee_id_number'],$row['employee_id']);
	        	$result['data'][$k]['first_name'] = $this->Helper->decrypt($row['first_name'],$row['employee_id']);
	        	$result['data'][$k]['middle_name'] = $this->Helper->decrypt($row['middle_name'],$row['employee_id']);
	        	$result['data'][$k]['last_name'] = $this->Helper->decrypt($row['last_name'],$row['employee_id']);
	        }
		}
		echo json_encode($result);
	}

	public function viewPayslip(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$ret =  new PayslipCollection();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$id = isset($_POST['id'])?@$_POST['id']:"";
			$result['key'] = $_POST["key"];
			$formData['key'] = $result['key'];
			$formData['payroll'] = $ret->getPayroll($id,$employee_id,$payroll_period_id);

			if(sizeof($formData['payroll']) > 0){
				$emp_id = $formData['payroll'][0]['employee_id'];
				$formData['payroll'][0]['employee_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_number'],$emp_id);
				$formData['payroll'][0]['first_name'] = $this->Helper->decrypt($formData['payroll'][0]['first_name'],$emp_id);
				$formData['payroll'][0]['last_name'] = $this->Helper->decrypt($formData['payroll'][0]['last_name'],$emp_id);
				$formData['payroll'][0]['middle_name'] = $this->Helper->decrypt($formData['payroll'][0]['middle_name'],$emp_id);
				$formData['payroll'][0]['employee_id_number'] = $this->Helper->decrypt($formData['payroll'][0]['employee_id_number'],$emp_id);
			}
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			$formData['allowances'] = $ret->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret->getAmortizedLoans($employee_id,$payroll_period_id);
			$formData['emp_id'] = $employee_id;
			// print_r($formData); die();
			$result['form'] = $this->load->view('helpers/employeepayslipV2.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}

?>