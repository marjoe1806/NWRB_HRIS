<?php

class RATASlip extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('RATASlipCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::RATASLIP_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewRATASlip";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/ratasliplist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('RATA Summary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('rataslip',$viewData,FALSE);
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
        $fetch_data = $this->RATASlipCollection->make_datatables($employment_status,$employee_id,$pay_basis,$payroll_period_id);
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
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();    
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->first_name.' '.$row->last_name;  
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->rep_allowance,2);
            $sub_array[] = number_format($row->transpo_allowance,2);
            $sub_array[] = $row->contract;
           	//Is approved
          
            //Status
            /*$status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';*/

            /*'<a  id="updateRATASlipForm" 
                class="updateRATASlipForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateRATASlipForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
                data-id="'. $value->id.'"
                foreach ($value as $k => $v) {
                    ' data-'.$k.'="'.$v.'" ';
                } "
            >'

                <i class="material-icons">mode_edit</i>
            </a>*/
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons_data .= ' data-extension="'.$row->extension.'" ';
            $buttons .= ' <a id="viewRATASlipForm" ' 
            		  . ' class="viewRATASlipForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewRATASlipForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            /*if($row->is_active == "1"){
	            $buttons .= ' <a ' 
	            		  . ' class="deactivateRATASlip btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateRATASlip" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';	
	        }
	        else{
	        	$buttons .= ' <a ' 
	        			  . ' class="activateRATASlip btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateRATASlip" '
	        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
	        			  . ' data-id='.$row->id.' '
	        			  . ' > '
	        			  . ' <i class="material-icons">done</i> '
	        			  . ' </a> ';
	        } */
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->RATASlipCollection->get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id),  
            "recordsFiltered"     	=>     $this->RATASlipCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewRATASlipForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewRATASlip';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new RATASlipCollection();
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
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			/*$formData['cutoff'] =  $ret->getPayrollPeriodCutoffById($cutoff_id);*/

			$formData['allowances'] = $ret->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret->getAmortizedLoans($employee_id,$payroll_period_id);
			$result['form'] = $this->load->view('helpers/employeerataslip.php', $formData, TRUE);
			// var_dump($formData);die();
			//Forms
		}
		echo json_encode($result);
	}
	public function rataslipContainer(){
		$formData = array();
		$result = array();
		$result['key'] = 'rataslipContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			/*if($ret->computePayrollProcess($employee_id,$payroll_period_id)) {*/
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeerataslip.php', $formData, TRUE);
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
			$ret =  new RATASlipCollection();
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
	public function viewRATASlipSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewRATASlipSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new RATASlipCollection();
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
			$result['form'] = $this->load->view('helpers/employeerataslip.php', $formData, TRUE);
			/*} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}*/
			//Forms
		}
		echo json_encode($result);
	}

	public function viewRataSummary(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new RATASlipCollection();
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";

			$formData['payroll'] = $ret->getPayrollSummary($payroll_period_id,$pay_basis);
			$formData['payroll_period'] = $ret->getPayrollPeriodById($payroll_period_id);
			$formData['signatories'] = $ret->get_signatories();

			$result['length'] = sizeof($formData['payroll']);
 			$result['form'] = $this->load->view('helpers/employeeratasummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}

?>