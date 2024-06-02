<?php

class LoanApplications extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LoanApplicationsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LOAN_APPLICATIONS_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLoanApplications";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/loanapplicationslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Loan Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('loanapplications',$viewData,FALSE);
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
        $fetch_data = $this->LoanApplicationsCollection->make_datatables($pay_basis,$payroll_period_id);
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
            $sub_array[] = date('m/d/Y',strtotime($row->start_date));
            $sub_array[] = number_format($row->loan_amount,2);
            $sub_array[] = date('m/d/Y',strtotime($row->maturity_date));
            $sub_array[] = number_format($row->amortization_per_month,2);
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

            /*'<a  id="updateLoanApplicationsForm" 
                class="updateLoanApplicationsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateLoanApplicationsForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
                data-id="'. $value->id.'"
                foreach ($value as $k => $v) {
                    ' data-'.$k.'="'.$v.'" ';
                } "
            >'

                <i class="material-icons">mode_edit</i>
            </a>*/
            /*foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons .= ' <a id="viewLoanApplicationsForm" ' 
            		  . ' class="viewLoanApplicationsForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLoanApplicationsForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';*/
            /*if($row->is_active == "1"){
	            $buttons .= ' <a ' 
	            		  . ' class="deactivateLoanApplications btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateLoanApplications" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';	
	        }
	        else{
	        	$buttons .= ' <a ' 
	        			  . ' class="activateLoanApplications btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateLoanApplications" '
	        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
	        			  . ' data-id='.$row->id.' '
	        			  . ' > '
	        			  . ' <i class="material-icons">done</i> '
	        			  . ' </a> ';
	        } */
	        // $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LoanApplicationsCollection->get_all_data($pay_basis,$payroll_period_id),  
            "recordsFiltered"     	=>     $this->LoanApplicationsCollection->get_filtered_data($pay_basis,$payroll_period_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewLoanApplicationsSummary(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewLoanApplicationsSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new LoanApplicationsCollection();
			//Computations for Payroll
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$formData['key'] = $result['key'];
			
			//var_dump($formData['allowances']);die();
			$formData["list"] = $this->LoanApplicationsCollection->fetchLoanApplications($pay_basis,$payroll_period_id);
			foreach($formData["list"] as $k => $row)  
        	{ 
				$formData["list"][$k]['employee_number'] = $this->Helper->decrypt($row['employee_number'],$row['employee_id']);
	        	$formData["list"][$k]['employee_id_number'] = $this->Helper->decrypt($row['employee_id_number'],$row['employee_id']);
	        	$formData["list"][$k]['first_name'] = $this->Helper->decrypt($row['first_name'],$row['employee_id']);
	        	$formData["list"][$k]['middle_name'] = $this->Helper->decrypt($row['middle_name'],$row['employee_id']);
	        	$formData["list"][$k]['last_name'] = $this->Helper->decrypt($row['last_name'],$row['employee_id']);
	        }
			$result['form'] = $this->load->view('helpers/employeeloanapplications.php', $formData, TRUE);
			/*} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}*/
			//Forms
		}
		echo json_encode($result);
	}
}

?>