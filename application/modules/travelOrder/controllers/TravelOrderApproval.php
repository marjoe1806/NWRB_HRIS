<?php

class TravelOrderApproval extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('TravelOrderApprovalCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		Helper::rolehook(ModuleRels::HRIS_DASHBOARD);
		$listData = array();
		$viewData = array();
		$page = "viewTravelOrder";
		$listData['key'] = $page;
		// $ret = new TravelOrderCollection();
		$viewData['table'] = $this->load->view("helpers/tarvelorderlist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/travelorderPrint", $listData, TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Travel Order Approval');
			Helper::setMenu('templates/menu_template');
			Helper::setView('travelorderapproval',$viewData,FALSE);
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
        $fetch_data = $this->TravelOrderApprovalCollection->make_datatables();  
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

            if ($row->status == 0 && $row->section_head > 0){
            	$buttons .= ' <a id="firstRecommendation" ' 
					   . ' class="firstRecommendation" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/firstRecommendation" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Recommend">'
					   . ' <i class="material-icons">done</i> '
					   . ' </button> '
					   . ' </a> ';
					$buttons .= ' <a id="rejectTravelOrder" ' 
					   . ' class="rejectTravelOrder" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
					   . ' <i class="material-icons">clear</i> '
					   . ' </button> '
					   . ' </a> ';
            }

			// if($section_head && $division_head){
				if ($row->status == 1 && $row->division_head > 0){
					$buttons .= ' <a id="secondRecommendation" ' 
						. ' class="secondRecommendation" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/secondRecommendation" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Recommend">'
						. ' <i class="material-icons">done</i> '
						. ' </button> '
						. ' </a> ';
						$buttons .= ' <a id="rejectTravelOrder" ' 
						. ' class="rejectTravelOrder" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
						. ' <i class="material-icons">clear</i> '
						. ' </button> '
						. ' </a> ';
				}
				if ($row->status == 2 && $row->certify > 0){
					$buttons .= ' <a id="forCertification" ' 
						. ' class="forCertification" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/forCertification" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Certify">'
						. ' <i class="material-icons">done</i> '
						. ' </button> '
						. ' </a> ';
						$buttons .= ' <a id="rejectTravelOrder" ' 
						. ' class="rejectTravelOrder" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
						. ' <i class="material-icons">clear</i> '
						. ' </button> '
						. ' </a> ';
				}
			// }else if($division_head && !$deputy){
			// 	if ($row->status == 1 && $row->division_head > 0){
			// 		$status_name = "RECOMMENDATION <br><small>(Division Head)</small>";
			// 		$buttons .= ' <a id="secondRecommendation" ' 
			// 			. ' class="secondRecommendation" style="text-decoration: none;" '
			// 			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/secondRecommendation" '
			// 			. $buttons_data
			// 			. ' > '
			// 			. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Recommend">'
			// 			. ' <i class="material-icons">done</i> '
			// 			. ' </button> '
			// 			. ' </a> ';
			// 			$buttons .= ' <a id="rejectTravelOrder" ' 
			// 			. ' class="rejectTravelOrder" style="text-decoration: none;" '
			// 			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
			// 			. $buttons_data
			// 			. ' > '
			// 			. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
			// 			. ' <i class="material-icons">clear</i> '
			// 			. ' </button> '
			// 			. ' </a> ';
			// 	}
			// }else if(!$division_head && $deputy){
			// 	if ($row->status == 1 && $row->certify > 0){
			// 		$status_name = "CERTIFICATION <br><small>(Deputy)</small>";
			// 		$buttons .= ' <a id="forCertification" ' 
			// 			. ' class="forCertification" style="text-decoration: none;" '
			// 			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/forCertification" '
			// 			. $buttons_data
			// 			. ' > '
			// 			. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Certify">'
			// 			. ' <i class="material-icons">done</i> '
			// 			. ' </button> '
			// 			. ' </a> ';
			// 			$buttons .= ' <a id="rejectTravelOrder" ' 
			// 			. ' class="rejectTravelOrder" style="text-decoration: none;" '
			// 			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
			// 			. $buttons_data
			// 			. ' > '
			// 			. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
			// 			. ' <i class="material-icons">clear</i> '
			// 			. ' </button> '
			// 			. ' </a> ';
			// 	}
			// }
			
			if ($row->status == 3 && $row->approval > 0){
					$buttons .= ' <a id="forApproval" ' 
					. ' class="forApproval" style="text-decoration: none;" '
					. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/forApproval" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve">'
					. ' <i class="material-icons">done</i> '
					. ' </button> '
					. ' </a> ';
				 $buttons .= ' <a id="rejectTravelOrder" ' 
					. ' class="rejectTravelOrder" style="text-decoration: none;" '
					. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
					. ' <i class="material-icons">clear</i> '
					. ' </button> '
					. ' </a> ';
				
            
            }
			
			if ($row->status == 4 && $row->for_driver > 0 && $row->is_vehicle == 2){
				if($row->is_vehicle == 2)
				{
            	$buttons .= ' <a id="forDriverVehicle" ' 
					   . ' class="forDriverVehicle" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/forDriverVehicle" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Complete">'
					   . ' <i class="material-icons">done</i> '
					   . ' </button> '
					   . ' </a> ';
					$buttons .= ' <a id="rejectTravelOrder" ' 
					   . ' class="rejectTravelOrder" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectTravelOrder" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Reject">'
					   . ' <i class="material-icons">clear</i> '
					   . ' </button> '
					   . ' </a> ';
					 $buttons .= ' <a id="updateTravelOrderForm" ' 
					   . ' class="updateTravelOrderForm" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateTravelOrderForm" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-dark btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Assign driver and Vehicle">'
					   . ' <i class="material-icons">mode_edit</i> '
					   . ' </button> '
					   . ' </a> ';
				}

            }
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
			// if($row->status == 5){
			// 	$for_to_no = $row->travel_order_no; 
			// }
			// elseif($row->status == 4){
			// 	$for_to_no = $row->travel_order_no; 
			// }else{
			// 	$for_to_no = ""; 
			// }
	        $sub_array[] = $buttons;
            // $sub_array[] = $row->section_head.' '.$row->division_head.' '.$row->certify.' '.$row->approval;		
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
            "recordsTotal"          =>     $this->TravelOrderApprovalCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->TravelOrderApprovalCollection->get_filtered_data(),  
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

	public function updateTravelOrderForm(){
		$formData = $result = array();
		$result['key'] = 'updateTravelOrder';
			
			if (in_array("1313", $_SESSION['sessionModules'])){
				$canselectmultiple = "yes";
			}else{
				$canselectmultiple = "no";
			}
			$formData['canselectmultiple'] = $canselectmultiple;
			$formData['division_id'] = $_SESSION['division_id'];
			$formData['employee_id'] = $_SESSION['employee_id'];
			$formData['userlevel_id'] = $_SESSION['userlevelid'];
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/travelorderform.php', $formData, TRUE);
		
		echo json_encode($result);
	}


	public function firstRecommendation(){
		$result = array();
		$page = 'firstRecommendation';
		//var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->firstRecommendation($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}


	public function updateTravelOrder(){
		$result = array();
		$page = 'updateTravelOrder';
		// 
		// var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->updateTravelOrder($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}

		echo json_encode($result);
	}

	public function secondRecommendation(){
		$result = array();
		$page = 'secondRecommendation';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/travelorderapproval/'.$this->input->post('travel_id');
					if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
					chmod($uploadpath, 0775);
					$config['upload_path'] = $uploadpath;
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['remove_spaces'] = FALSE;
					$this->upload->initialize($config);
					if ($this->upload->do_upload('uploadFile')):
						$data = array('upload_data' => $this->upload->data());
					else:
						$error = array('error' => $this->upload->display_errors()); 
					endif;
				}
				$post_data = array();
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->secondRecommendation($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	

	public function forCertification(){
		$result = array();
		$page = 'forCertification';
		//var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				// if(isset($_FILES)) {
				// 	$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				// 	$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				// 	$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				// 	$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				// 	$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				// 	$uploadpath = './assets/uploads/travelorderapproval/'.$this->input->post('travel_id');
				// 	if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
				// 	chmod($uploadpath, 0775);
				// 	$config['upload_path'] = $uploadpath;
				// 	$config['allowed_types'] = '*';
				// 	$config['overwrite'] = TRUE;
				// 	$config['remove_spaces'] = FALSE;
				// 	$this->upload->initialize($config);
				// 	if ($this->upload->do_upload('uploadFile')):
				// 		$data = array('upload_data' => $this->upload->data());
				// 	else:
				// 		$error = array('error' => $this->upload->display_errors()); 
				// 	endif;
				// }
				$post_data = array();
				// $post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				// $post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->forCertification($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function forApproval(){
		$result = array();
		$page = 'forApproval';
		//var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/travelorderapproval/'.$this->input->post('travel_id');
					if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
					chmod($uploadpath, 0775);
					$config['upload_path'] = $uploadpath;
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['remove_spaces'] = FALSE;
					$this->upload->initialize($config);
					if ($this->upload->do_upload('uploadFile')):
						$data = array('upload_data' => $this->upload->data());
					else:
						$error = array('error' => $this->upload->display_errors());
					endif;
				}
				$post_data = array();
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->forApproval($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function forDriverVehicle(){
		$result = array();
		$page = 'forDriverVehicle';
		// var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->forDriverVehicle($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function rejectTravelOrder(){
		$result = array();
		$page = 'rejectTravelOrder';
		// var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new TravelOrderApprovalCollection();
				if($ret->rejectTravelOrder($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	// public function addTravelOrderForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'addTravelOrder';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/travelorderform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }

	// public function addTravelOrder(){
	// 	// print_r($this->input->post()); die();
	// 	$result = array();
	// 	$page = 'addTravelOrder';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else{
	// 		$post_data = array();
	// 		if($this->input->post() && $this->input->post() != null){
	// 			foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
	// 			$ret =  new TravelOrderApprovalCollection();
	// 			if($ret->addRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			$result = json_decode($res,true);
	// 		}else{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	

	public function getUsersWithTravel() {
        $this->load->model('TravelOrderApprovalCollection');
        $travel_id = $this->input->post('travel_id'); // Assuming you are sending the travel_id via POST

        $users = $this->TravelOrderApprovalCollection->getTravelId($travel_id);

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($users);
    }

	// public function fetchTravelID() {
    //     $this->load->model('TravelOrderApprovalCollection');

    //     $travel_id = $this->input->get('travel_id'); // Assuming 'travel_id' is passed as a parameter in the GET request
    //     $data = $this->TravelOrderApprovalCollection->fetchTravelID($travel_id);

    //     // You can return the data as JSON response
    //     echo json_encode($data);
    // }

	public function fetchUsersByTravelID() {
        // Get the travel_id from the AJAX request
        $travel_id = $this->input->post('travel_id');

        // Load the model
		$this->load->model('TravelOrderApprovalCollection');

        // Call the model function to get users with the provided travel_id
        $data['users'] = $this->TravelOrderApprovalCollection->getUsersByTravelID($travel_id);

        // Convert the data to JSON and send it back to the client
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

?>	