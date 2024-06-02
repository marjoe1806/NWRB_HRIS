<?php

class OvertimeApplicationsUpdates extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeApplicationsUpdatesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewOvertimeApplicationsUpdates";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/overtimeapplicationslist_updates",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Overtime Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimeapplications',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
	}
	function fetchRows(){ 
        $fetch_data = $this->OvertimeApplicationsUpdatesCollection->make_datatables();  
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
            $sub_array[] = $row->payroll_grouping_code;
            $sub_array[] = $row->position_name;
            $sub_array[] = number_format($row->salary,2);
            if($row->pay_basis == "Permanent" || $row->pay_basis == "Casual"){
                $sub_array[] = $row->hrs_15;
                $sub_array[] = $row->mins_15;
                $sub_array[] = $row->hrs_125;
                $sub_array[] = $row->mins_125;
            }
            else{
                $sub_array[] = $row->hrs_1;
                $sub_array[] = $row->mins_1;
                $sub_array[] = $row->hrs_1625;
                $sub_array[] = $row->mins_1625;
            }
            $sub_array[] = $row->ot_percent;
            $sub_array[] = $row->tax;
            $sub_array[] = $row->tax_amt;
            $sub_array[] = number_format($row->period_earned,2);
            $sub_array[] = number_format($row->net_pay,2);
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
            $sub_array[] = $row->remarks;
            $sub_array[] = $row->username;
            $sub_array[] = date("F d, Y h:ia", strtotime($row->date_created));
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OvertimeApplicationsUpdatesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OvertimeApplicationsUpdatesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>