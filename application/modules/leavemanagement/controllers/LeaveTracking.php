<?php

class LeaveTracking extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LeaveTrackingCollection');
		$this->load->model('PendingLeaveCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_STATUS_TRACKING_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLeaveTracking";
		$listData['key'] = $page;

		$ret = new LeaveTrackingCollection();
		$viewData['table'] = $this->load->view("helpers/pendingleavelist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/leaveapplicationform", $listData['key'], TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Leave Status Tracking');
			Helper::setMenu('templates/menu_template');
			Helper::setView('leavetracking',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
        $fetch_data = $this->LeaveTrackingCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row){ 
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();    
			
            if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";

			if($row->status_name == "REJECTED"){
				$status_name = "DISAPPROVED";
			}else{
				$status_name = $row->status_name;
			}

			$supervisor = false;
			$division_head = false;
			$deputy = false;

			// $ret =  new PendingLeaveCollection();
			// if($ret->fetchLeaveApprovals($row->employee_id)) {
			// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			// 	$approvers = json_decode($res,true);

			// 	if($approvers['Code'] == "0"){
			// 		$app = $approvers['Data']['approvers'];

			// 		foreach ($app as $k => $v) {
			// 			$id = $v['id'];
			// 			$approve_type = $v['approve_type'];

			// 			if($approve_type == "2"){
			// 				$supervisor = true;
			// 			}
			// 			if($approve_type == "3"){
			// 				$division_head = true;
			// 			}

			// 			if($approve_type == "8"){
			// 				$deputy = true;
			// 			}
			// 		}
			// 	}
			// }
			
			// if($division_head && !$deputy){
			// 	if($row->division_head > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Division Hea1d)";
			// 		}
			// 	endif;
			// }else if(!$division_head && $deputy){
			// 	if($row->deputy > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Deputy)";
			// 		}
			// 	endif;
			// }
			
			$row->inclusive_dates_original = $row->inclusive_dates;

            foreach($row as $k1=>$v1){
				if($k1 == "inclusive_dates"){
					if($k1 == "inclusive_dates"){
						if(strpos($v1, ', ') !== false ){
							$isRange= explode(", ",$v1);
							sort($isRange);
							$v1 = date("M. d, Y", strtotime($isRange[0])) . " - " . date("M. d, Y", strtotime(end($isRange)));
							$row->inclusive_dates = $v1;
						}else if(strpos($v1, ' - ') !== false ){	
							$isRange= explode(" - ",$v1);					
							if(sizeof($isRange) == 2){
								if(strtotime($isRange[0]) == strtotime($isRange[1])){
									$v1 = date("M. d, Y", strtotime($isRange[0]));
									$row->inclusive_dates = $v1;
								} else{							
									$v1 = date("M. d, Y", strtotime($isRange[0])) . " - " . date("M. d, Y", strtotime($isRange[1]));
									$row->inclusive_dates = $v1;
								}
							}
						}else{
							$v1 = date("M. d, Y", strtotime($v1));;
							$row->inclusive_dates = $v1;
						}
					}
				}
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}

            	$buttons .= ' <a id="viewLeaveTrackingDetails" ' 
            		  . ' class="viewLeaveTrackingDetails" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLeaveTrackingDetails" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';

	        $buttons .= ' <a  data-toggle="tooltip" data-placement="top" title="Print Preview"'
	            		  . ' style="text-decoration: none;" data-toggle = "modal" data-target = "#print_preview_modal"'
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float"' 
	            		  . $buttons_data
	            		  . 'data-toggle = "modal" data-target = "#print_preview_modal" >'
	            		  . ' <i class="material-icons">print</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
			
	        $sub_array[] = $buttons;
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;;
            $sub_array[] = $row->filing_date;
            $sub_array[] = ucwords(str_replace("_"," ",$row->type_name));
			$sub_array[] = $row->inclusive_dates;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $sub_array[] = $row->remarks;
            $data[] = $sub_array;
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LeaveTrackingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->LeaveTrackingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function viewLeaveTrackingDetails(){
		$formData = $result = array();
		$result['key'] = 'viewLeaveTrackingDetails';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/leaverequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}

?>