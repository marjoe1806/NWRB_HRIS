<?php

class LoanLedgers extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LoanLedgersCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LOAN_DEDUCTIONS_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLoanLedgers";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/loanledgerslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Loan Ledger');
			Helper::setMenu('templates/menu_template');
			Helper::setView('loanledgers',$viewData,FALSE);
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
        $fetch_data = $this->LoanLedgersCollection->make_datatables($pay_basis,$employee_id);
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
            $sub_array[] = $row->category_code;
            $sub_array[] = $row->type_code;
            $sub_array[] = number_format($row->amount,2);
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LoanLedgersCollection->get_all_data($pay_basis,$employee_id),  
            "recordsFiltered"     	=>     $this->LoanLedgersCollection->get_filtered_data($pay_basis,$employee_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewLoanLedgersSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewLoanLedgersSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new LoanLedgersCollection();
			//Computations for Payroll
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$formData['key'] = $result['key'];
			
			//var_dump($formData['allowances']);die();
			$formData["list"] = $this->LoanLedgersCollection->fetchLoanLedgers($pay_basis,$employee_id);
			foreach($formData["list"] as $k => $row)  
        	{ 
				$formData["list"][$k]['employee_number'] = $this->Helper->decrypt($row['employee_number'],$row['employee_id']);
	        	$formData["list"][$k]['employee_id_number'] = $this->Helper->decrypt($row['employee_id_number'],$row['employee_id']);
	        	$formData["list"][$k]['first_name'] = $this->Helper->decrypt($row['first_name'],$row['employee_id']);
	        	$formData["list"][$k]['middle_name'] = $this->Helper->decrypt($row['middle_name'],$row['employee_id']);
	        	$formData["list"][$k]['last_name'] = $this->Helper->decrypt($row['last_name'],$row['employee_id']);
	        }
			$result['form'] = $this->load->view('helpers/employeeloanledgers.php', $formData, TRUE);
			/*} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}*/
			//Forms
		}
		echo json_encode($result);
	}
}

?>