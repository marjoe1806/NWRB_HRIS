<?php

class TravelOrderTracking extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('TravelOrderTrackingCollection');
		$this->load->model('TravelOrderApprovalCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		Helper::rolehook(ModuleRels::TRAVEL_ORDER_STATUS_TRACKING_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewTravelOrder";
		$listData['key'] = $page;
		// $ret = new TravelOrderCollection();
		$viewData['table'] = $this->load->view("helpers/tarvelorderlist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/travelorderPrint", $listData, TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Travel Order Tracking');
			Helper::setMenu('templates/menu_template');
			Helper::setView('travelordertracking',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
		// print_r($_GET["status"]); die();
        $fetch_data = $this->TravelOrderTrackingCollection->make_datatables();  
        $data = array();
		//var_dump($fetch_data).die();
        foreach($fetch_data as $k => $row){ 
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    

            if($row->status == 4 || $row->status == 5){
            	$status_color = "text-success";
            }else if($row->status == 1 || $row->status == 2 || $row->status == 3 || $row->status == 0 || $row->status == 4){
            	$status_color = "text-warning";
            } 
			else{
				$status_color = "text-danger";
			} 

			switch($row->status) {
				case 0 :	$status_name = "RECOMMENDATION <br><small>(Section Head)</small>";
									break;
				case 1 :	$status_name = "RECOMMENDATION <br><small>(Division Head)</small>";
									break;
				case 2 :	$status_name = "CERTIFICATION <br><small>(Deputy)</small>";
									break;
				case 3 :	$status_name = "FOR APPROVAL <br><small>(Director)</small>";
									break;
				case 4 :	$status_name = "FOR DRIVER AND VEHICLE ASSIGNING <br><small>(Gss)</small>";
									break;
				case 5 :	$status_name = "COMPLETED";
									break;
				case 6 :	$status_name = "REJECTED";
									break;
				default : 	$status_name = "";
									break;
			}

			// $section_head = false;
			// $division_head = false;
			// $deputy = false;

			// $ret =  new TravelOrderApprovalCollection();
			// if($ret->fetchTravelOrderApprovals($row->employee_id)) {
			// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			// 	$approvers = json_decode($res,true);

			// 	if($approvers['Code'] == "0"){
			// 		$app = $approvers['Data']['approvers'];

			// 		foreach ($app as $k => $v) {
			// 			$id = $v['id'];
			// 			$approve_type = $v['approve_type'];

			// 			if($approve_type == "0"){
			// 				$section_head = true;
			// 			}
			// 			if($approve_type == "1"){
			// 				$division_head = true;
			// 			}

			// 			if($approve_type == "2"){
			// 				$deputy = true;
			// 			}
			// 		}
			// 	}
			// }

			// if($division_head && !$deputy){
			// 	if ($row->status == 1){
			// 		$status_name = "RECOMMENDATION <br><small>(Division Head)</small>";
			// 	}
			// }else if(!$division_head && $deputy){
			// 	if ($row->status == 1){
			// 		$status_name = "CERTIFICATION <br><small>(Deputy)</small>";
			// 	}
			// }

            foreach($row as $k1=>$v1){
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons .= ' <a id="viewPendingTravelOrder" ' 
            		  . ' class="viewPendingTravelOrder" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewPendingTravelOrder" '
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
	        $sub_array[] = $row->travel_order_no;
            $sub_array[] = $row->duration;
            $sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;		
            $sub_array[] = $row->location;
            $sub_array[] = $row->officialpurpose;
			$sub_array[] = @$row->department_name;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $data[] = $sub_array;
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>     $this->TravelOrderTrackingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->TravelOrderTrackingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function viewPendingTravelOrder(){
		$formData = $result = array();
		$result['key'] = 'viewPendingTravelOrder';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/travelorderrequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}

?>	