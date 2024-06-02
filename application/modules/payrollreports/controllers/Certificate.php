<?php

class Certificate extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CertificateCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		// Helper::rolehook(ModuleRels::CERTIFICATE_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewCertificate";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/certificatelist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Certificate');
			Helper::setMenu('templates/menu_template');
			Helper::setView('certificate',$viewData,FALSE);
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
        $loans_id = @$_GET['LoanId'];
        $sub_loans_id = @$_GET['SubLoanId'];
		$self = 0;
		// if(in_array(17001,$_SESSION["sessionModules"])) $self = 1;
        $fetch_data = $this->CertificateCollection->make_datatables($employee_id,$self,$location_id,$payroll_period_id,$division_id,$employment_status,$loans_id,$sub_loans_id,$pay_basis);
		$data = array();  
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();    
           	foreach($row as $k1=>$v1)
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons_data .= ' data-extension="'.$row->extension.'" ';
            $buttons .= ' <a id="viewCertificateForm" ' 
            		  . ' class="viewCertificateForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewCertificate" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect  waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="View Certificate">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> '; 
	        $sub_array[] = $buttons;
            $sub_array[] = $row->emp_number;
			$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name;  
			$sub_array[] = $row->position_name;            
			$sub_array[] = $row->department_name;
			$sub_array[] = number_format($row->salary,2);
            $sub_array[] = $row->employment_status;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->CertificateCollection->get_all_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$loans_id,$sub_loans_id, $self),  
            "recordsFiltered"     	=>     $this->CertificateCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$payroll_period_id,$location_id,$division_id,$loans_id,$sub_loans_id, $self),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
	public function certificateContainer(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$result['key'] = 'certificateContainer';
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeepayslipV2.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewCertificate(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$ret =  new CertificateCollection();
			//get certificates
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$loans_id = isset($_POST['loans_id'])?@$_POST['loans_id']:"";
			$sub_loans_id = isset($_POST['sub_loans_id'])?@$_POST['sub_loans_id']:"";
			$result['key'] = $_POST["key"];
			$formData['key'] = $result['key'];

			if($_POST['loans_id'] == 0) {//Philhealth_Certificate
				$formData['list'] = $ret->getCertificatePhil($loans_id,$sub_loans_id,$pay_basis,$employee_id);
			} else {
				if($_POST['loans_id'] == 2 && $_POST['sub_loans_id'] == 0) {//Pag-ibig MP2
					$formData['list'] = $ret->getCertificateMP2($loans_id,$sub_loans_id,$pay_basis,$employee_id);
				}else{
				$formData['list'] = $ret->getCertificate($loans_id,$sub_loans_id,$pay_basis,$employee_id);
				}
			}
			if(sizeof($formData['list']) > 0){
				$emp_id = $formData['list'][0]['emp_id'];
				$formData['list'][0]['employee_number'] = $this->Helper->decrypt($formData['list'][0]['employee_number'],$emp_id);
				$formData['list'][0]['first_name'] = $this->Helper->decrypt($formData['list'][0]['first_name'],$emp_id);
				$formData['list'][0]['last_name'] = $this->Helper->decrypt($formData['list'][0]['last_name'],$emp_id);
				$formData['list'][0]['middle_name'] = $this->Helper->decrypt($formData['list'][0]['middle_name'],$emp_id);
				$formData['list'][0]['employee_id_number'] = $this->Helper->decrypt($formData['list'][0]['employee_id_number'],$emp_id);
			}
			if($_POST['loans_id'] == 1){
				$result['form'] = $this->load->view('helpers/certificateprintgsis.php', $formData, TRUE);
			} else if($_POST['loans_id'] == 2){
				if($_POST['sub_loans_id'] == 0) {$result['form'] = $this->load->view('helpers/certificateprintpagibig_mp2.php', $formData, TRUE);}
				else{$result['form'] = $this->load->view('helpers/certificateprintpagibig.php', $formData, TRUE);}
			} else if($_POST['loans_id'] == 0){
				$result['form'] = $this->load->view('helpers/certificateprintphilhealth.php', $formData, TRUE);
			} else if($_POST['loans_id'] == 4){
				$result['form'] = $this->load->view('helpers/', $formData, TRUE);
			} else {

			}
		}
		echo json_encode($result);
	}
}

?>