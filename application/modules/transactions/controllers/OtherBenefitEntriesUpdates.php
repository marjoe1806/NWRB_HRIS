<?php

class OtherBenefitEntriesUpdates extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OtherBenefitEntriesUpdatesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewOtherBenefitEntriesUpdates";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/otherbenefitentrieslist_updates",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Other Benefits Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('otherbenefitentries',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
	}
	function fetchRows(){ 
        $fetch_data = $this->OtherBenefitEntriesUpdatesCollection->make_datatables();  
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
            $sub_array[] = $row->period_id.' - '.date("F Y", strtotime($row->payroll_period));
            $sub_array[] = $row->benefit_description;
            $sub_array[] = $row->amount;
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
            "recordsTotal"          =>      $this->OtherBenefitEntriesUpdatesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OtherBenefitEntriesUpdatesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>