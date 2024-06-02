<?php

class PayrollPostingUpdates extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollPostingUpdatesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewPayrollPostingUpdates";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/payrollpostinglist_updates",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Posting Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollposting',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
	}
	function fetchRows(){ 
        $fetch_data = $this->PayrollPostingUpdatesCollection->make_datatables();  
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
            $sub_array[] = $row->pay_basis;
            $sub_array[] = $row->division_id;
            $sub_array[] = $row->days_count;
            $sub_array[] = $row->lwp;
            $sub_array[] = $row->lates;
            $sub_array[] = $row->undertimes;
            $sub_array[] = number_format($row->pg_share,2);
            $sub_array[] = $row->period_id.' - '.date("F Y", strtotime($row->payroll_period));
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
            "recordsTotal"          =>      $this->PayrollPostingUpdatesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->PayrollPostingUpdatesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>