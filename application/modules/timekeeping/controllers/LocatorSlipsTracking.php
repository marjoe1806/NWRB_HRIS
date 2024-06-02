<?php

class LocatorSlipsTracking extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LocatorSlipsTrackingCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		Helper::rolehook(ModuleRels::LOCATOR_STATUS_TRACKING_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLocatorSlips";
		$listData['key'] = $page;
		
		$listData['list'] = "";
		$viewData['table'] = $this->load->view("helpers/locatorslipslist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/businesspermissionform",$listData['key'],TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Locator Slip Tracking');
			Helper::setMenu('templates/menu_template');
			Helper::setView('locatorslipstracking',$viewData,FALSE);
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
		$fetch_data = $this->LocatorSlipsTrackingCollection->make_datatables();
		$data = array();
		// print_r($fetch_data); die();
        foreach($fetch_data as $k => $row) { 
			$status = "";
			$purpose = "";
			switch($row->purpose) {
				case "personal" :	$purpose = "Personal";
									break;
				case "meeting" :	$purpose = "Meeting";
									break;
				case "training_program" :	$purpose = "Training Program";
									break;
				case "others" :		$purpose = "Others";
									break;
				case "seminar_conference" :	$purpose = "Seminar/Conference";
									break;
				case "gov_transaction" :	$purpose = "Government Transaction";
									break;
				default : 			$purpose = "Official";
									break;
			}

			switch($row->status) {
				case "PENDING" :	$class = "text-warning";$status = "FOR RECOMMENDATION (Supervisor)";
									break;
				case "FOR APPROVAL" :	$class = "text-warning";$status = "FOR APPROVAL";
									break;
				case "APPROVED" :	$class = "text-success";$status = "APPROVED<br><small> For assigning driver and vehicle </small>";
									break;
				case "REJECTED" :	$class = "text-danger";$status = "DISAPPROVED";
									break;
				case "COMPLETED" :	$class = "text-success";$status = "COMPLETED";
									break;
				case "CANCELLED" :	$class = "text-danger";$status = "CANCELLED";
									break;
				default : 	$class = "text-default";
									break;
			} 
        	$buttons = "";
        	$buttons_data = "";
			$emp_id_num = "";
			$fullname = "";
			$emp_id = $row->salt;
        	$emp_id_num = $this->Helper->decrypt((string)$row->employee_id_number,$emp_id);
        	$row->first_name = $this->Helper->decrypt((string)$row->first_name,$emp_id);
        	$row->middle_name = $this->Helper->decrypt((string)$row->middle_name,$emp_id);
        	$row->last_name = $this->Helper->decrypt((string)$row->last_name,$emp_id);
			$row->extension = ($row->extension == null || $row->extension == "")?"":$this->Helper->decrypt((string)$row->extension,$emp_id);
			$sub_array = array();    
			// $sub_array[] = date('M d, Y', strtotime(@$value->transaction_date));
            // $sub_array[] = $row->first_name. ' '. $row->middle_name .' '.$row->last_name .' '.$row->extension;
			// $sub_array[] = $row->location;
			// $sub_array[] = $purpose;
			// $sub_array[] = $row->activity_name;
			// $sub_array[] = '<label class="'.$class.'">'.$status.'</label>';
			// $sub_array[] = $row->reject_remarks;
			
			// var_dump($row); die();
           	foreach($row as $k1=>$v1){
				if($k1=="locator_time_in" || $k1=="locator_time_out" || $k1=="transaction_time" || $k1=="transaction_time_end") $v1 =  date("h:i A", strtotime($v1));
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons_data .= ' data-transaction_date_strto="'.date("F d, Y", strtotime($row->transaction_date)).'" ';
			$buttons_data .= ' data-transaction_date_end_strto="'.date("F d, Y", strtotime($row->transaction_date_end)).'" ';
			$strto = explode(" ",$row->date_created);
			$buttons_data .= ' data-date_created_strto="'.date("F d, Y", strtotime($strto[0])) .' - '.date("H:i A", strtotime($strto[1])).'" ';
				
            $buttons .= '<a id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float" '.$buttons_data.' data-toggle="modal" data-target="#print_preview_modal" "title="Print" ><i class="material-icons">print</i></a>';
			$buttons .= '<a id="viewLocatorSlipsForm" class="viewLocatorSlipsForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLocatorSlipsForm'.'"data-toggle="tooltip" data-placement="top" title="View Details" '.$buttons_data.'><i class="material-icons">remove_red_eye</i></a>';

	        $sub_array[] = $buttons;
	        $sub_array[] = str_replace("NWRB-", "", $row->control_no);
			$sub_array[] = $row->transaction_date_end != "0000-00-00" ? date('Y-m-d', strtotime($row->transaction_date)) .' - '.date('Y-m-d', strtotime($row->transaction_date_end)) : date('Y-m-d', strtotime($row->transaction_date));
            $sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
			$sub_array[] = $purpose;
			$sub_array[] = @$row->location;
			$sub_array[] = $row->activity_name;
			$sub_array[] = @$row->department_name;
			$sub_array[] = $row->reject_remarks;
			$sub_array[] = '<label class="'.$class.'">'.$status.'</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->LocatorSlipsTrackingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->LocatorSlipsTrackingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function viewLocatorSlipsForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/officialbusinessrequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}

?>