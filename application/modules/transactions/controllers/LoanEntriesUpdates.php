<?php

class LoanEntriesUpdates extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LoanEntriesUpdatesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewLoanEntriesUpdates";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/loanentrieslist_updates",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Loan Entries Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('loanentries',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
	}
	function fetchRows(){ 
        $fetch_data = $this->LoanEntriesUpdatesCollection->make_datatables();  
        $data = array(); 
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();
            $sub_array[] = $row->rowNumber;
            $fullname = $row->last_name.", ".$row->first_name;
            if($row->middle_name != null)
            	$fullname .= " ".$row->middle_name[0].".";
            $sub_array[] = $fullname;
            $sub_array[] = $row->loan_description;
            $sub_array[] = $row->sub_loan_description;
            $sub_array[] = number_format($row->loan_amount,2);
            $sub_array[] = number_format($row->loan_balance,2);
            $sub_array[] = number_format($row->amortization_per_month,2);
            $sub_array[] = $row->rmtp;
            $status_color = "text-danger";
            if($row->is_active == "1"){
                $status_color = "text-success";
                $status = "ACTIVE";
            }
            else{
                $status_color = "text-danger";
                $status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $sub_array[] = $row->username;
            $sub_array[] = date("F d, Y h:ia", strtotime($row->date_created));
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LoanEntriesUpdatesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->LoanEntriesUpdatesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>