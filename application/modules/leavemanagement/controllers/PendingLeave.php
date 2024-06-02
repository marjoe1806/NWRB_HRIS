<?php

class PendingLeave extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PendingLeaveCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_TRANSACTIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPendingLeave";
		$listData['key'] = $page;

		$ret = new PendingLeaveCollection();
		$viewData['table'] = $this->load->view("helpers/pendingleavelist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/leaveapplicationform", $listData['key'], TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Leave Transactions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('pendingleave',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
		// var_dump(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS));
		// die();
        $fetch_data = $this->PendingLeaveCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row){ 
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();    
			// $sub_array[] = $row->number_of_days;
			// if($row->id == 20) var_dump($row->inclusive_dates);
			// if($row->inclusive_dates != ""){
			// 	$isRange= explode(" - ",$row->inclusive_dates);
			// 	if(sizeof($isRange) == 2) $row->inclusive_dates = date("F d, Y", strtotime($isRange[0])) . " - " . date("F d, Y", strtotime($isRange[1]));
			// 	else{
			// 		$dates = explode(", ",$row->inclusive_dates);
			// 		$stdates = array();
			// 		foreach($dates as $k2 => $v2) $stdates[$k2] = date("F d, Y", strtotime($v2));
			// 		$row->inclusive_dates = implode(", ", $stdates);
			// 	}
			// }
				// if($row->id == 20) var_dump($row->inclusive_dates);
            if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";

			if($row->status_name == "REJECTED"){
				$status_name = "DISAPPROVED";
			}else{
				$status_name = $row->status_name;
			}

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

			// $supervisor = false;
			// $division_head = false;
			// $deputy = false;

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

            if(Helper::role(ModuleRels::LEAVE_VIEW_DETAILS)): 
            	$buttons .= ' <a id="viewPendingLeaveDetails" ' 
            		  . ' class="viewPendingLeaveDetails" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewPendingLeaveDetails" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
			endif;

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
			
			if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->certify > 0): //LEAVE_CERTIFY
				if($row->status == 1){
					$buttons .= ' <a id="certifyPendingLeave" ' 
					   . ' class="certifyPendingLeave" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/certifyPendingLeave" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Certification">'
					   . ' <i class="material-icons">done</i> '
					   . ' </button> '
					   . ' </a> ';
					$buttons .= ' <a id="rejectPendingLeave" ' 
					   . ' class="rejectPendingLeave" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
					   . ' <i class="material-icons">clear</i> '
					   . ' </button> '
					   . ' </a> ';
				}
		 	endif;
			
			// if($supervisor && $division_head){
				if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->supervisor > 0): //LEAVE_RECOMMEND
					if($row->status == 2){
						$buttons .= ' <a id="recommendPendingLeave" ' 
							. ' class="recommendPendingLeave" style="text-decoration: none;" '
							. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendPendingLeave" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
							. ' <i class="material-icons">done</i> '
							. ' </button> '
							. ' </a> ';
						$buttons .= ' <a id="rejectPendingLeave" ' 
							. ' class="rejectPendingLeave" style="text-decoration: none;" '
							. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
							. ' <i class="material-icons">clear</i> '
							. ' </button> '
							. ' </a> ';
					}
				endif;

				if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->division_head > 0): //LEAVE_RECOMMEND
					if($row->status == 3){
						$buttons .= ' <a id="recommendPendingLeaveHead" ' 
							. ' class="recommendPendingLeaveHead" style="text-decoration: none;" '
							. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendPendingLeaveHead" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
							. ' <i class="material-icons">done</i> '
							. ' </button> '
							. ' </a> ';
						$buttons .= ' <a id="rejectPendingLeave" ' 
							. ' class="rejectPendingLeave" style="text-decoration: none;" '
							. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
							. ' <i class="material-icons">clear</i> '
							. ' </button> '
							. ' </a> ';
					}
				endif;
			// }else if($division_head && !$deputy){
			// 	if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->division_head > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Division Hea1d)";
			// 			$buttons .= ' <a id="recommendPendingLeaveHead" ' 
			// 				. ' class="recommendPendingLeaveHead" style="text-decoration: none;" '
			// 				. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendPendingLeaveHead" '
			// 				. $buttons_data
			// 				. ' > '
			// 				. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
			// 				. ' <i class="material-icons">done</i> '
			// 				. ' </button> '
			// 				. ' </a> ';
			// 			$buttons .= ' <a id="rejectPendingLeave" ' 
			// 				. ' class="rejectPendingLeave" style="text-decoration: none;" '
			// 				. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
			// 				. $buttons_data
			// 				. ' > '
			// 				. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
			// 				. ' <i class="material-icons">clear</i> '
			// 				. ' </button> '
			// 				. ' </a> ';
			// 		}
			// 	endif;
			// }else if(!$division_head && $deputy){
			// 	if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->deputy > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Deputy)";
			// 			$buttons .= ' <a id="recommendPendingLeaveHead" ' 
			// 				. ' class="recommendPendingLeaveHead" style="text-decoration: none;" '
			// 				. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendPendingLeaveHead" '
			// 				. $buttons_data
			// 				. ' > '
			// 				. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
			// 				. ' <i class="material-icons">done</i> '
			// 				. ' </button> '
			// 				. ' </a> ';
			// 			$buttons .= ' <a id="rejectPendingLeave" ' 
			// 				. ' class="rejectPendingLeave" style="text-decoration: none;" '
			// 				. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
			// 				. $buttons_data
			// 				. ' > '
			// 				. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
			// 				. ' <i class="material-icons">clear</i> '
			// 				. ' </button> '
			// 				. ' </a> ';
			// 		}
			// 	endif;
			// }
			
			   
           	// if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->approve > 0): //LEAVE_APPROVE
			// 650bf745e83a4 RICKY AGTARAP ARZADON
			if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->status == 4 && $row->vacation_loc == "abroad" && $_SESSION['id'] == "650bf745e83a4"): //LEAVE_APPROVE
					$buttons .= ' <a id="approvePendingLeave" ' 
	            		  . ' class="approvePendingLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approvePendingLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Approval">'
	            		  . ' <i class="material-icons">done</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
					$buttons .= ' <a id="rejectPendingLeave" ' 
	            		  . ' class="rejectPendingLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
	            		  . ' <i class="material-icons">clear</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
			elseif(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) && $row->approve > 0 && $row->vacation_loc != "abroad" && $_SESSION['id'] != "650bf745e83a4"): //LEAVE_APPROVE
				if($row->status == 4){
					$buttons .= ' <a id="approvePendingLeave" ' 
	            		  . ' class="approvePendingLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approvePendingLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Approval">'
	            		  . ' <i class="material-icons">done</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
					$buttons .= ' <a id="rejectPendingLeave" ' 
	            		  . ' class="rejectPendingLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
	            		  . ' <i class="material-icons">clear</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
	        	}
			endif;
			   
			if($_SESSION['position_id'] == 363):
				if(($row->status == 5 || $row->status == 6) && $row->dir_filename == null && $row->dir_filesize == null){
					$buttons .= ' <a id="approveDisapprovedLeave" ' 
	            		  . ' class="approveDisapprovedLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approveDisapprovedLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve">'
	            		  . ' <i class="material-icons">done</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
					$buttons .= ' <a id="rejectDisapprovedLeave" ' 
	            		  . ' class="rejectDisapprovedLeave" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectDisapprovedLeave" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
	            		  . ' <i class="material-icons">clear</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
				}

				$buttons .= $row->vacation_loc;
			endif;
	        $sub_array[] = $buttons;
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;;
            // $sub_array[] = $row->status.' '.$row->certify." ".$row->supervisor." ".$row->division_head." ".$row->deputy;
            $sub_array[] = $row->filing_date;
            $sub_array[] = ucwords(str_replace("_"," ",$row->type_name));
			$sub_array[] = $row->inclusive_dates;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $sub_array[] = $row->remarks;
            $data[] = $sub_array;
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->PendingLeaveCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->PendingLeaveCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	//Regular Leave
	public function addPendingLeaveRegularForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPendingLeaveRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleaveregularform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePendingLeaveRegularForm(){
		$formData = $result = array();
		$result['key'] = 'updatePendingLeaveRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleaveregularform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewPendingLeaveDetails(){
		$formData = $result = array();
		$result['key'] = 'viewPendingLeaveDetails';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/leaverequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addPendingLeaveRegular(){
		$result = array();
		$page = 'addPendingLeaveRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null){
				$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				if (!file_exists('./assets/uploads/leaveattachment'))
					mkdir('./assets/uploads/leaveattachment', 0777, true);
				$config['upload_path'] = './assets/uploads/leaveattachment/';
				$config['allowed_types'] = '*';
				$config['overwrite'] = TRUE;
				$config['remove_spaces'] = FALSE;
				$this->upload->initialize($config);
				if ($this->upload->do_upload('uploadFile')):
					$data = array('upload_data' => $this->upload->data());
				else:
					$error = array('error' => $this->upload->display_errors());
				endif;

				$post_data = array();
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->addRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage()); 
				$result = json_decode($res,true);
			}
			else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function updatePendingLeaveRegular(){
		$result = array();
		$page = 'updatePendingLeaveRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/leaveattachment'))
						mkdir('./assets/uploads/leaveattachment', 0777, true);
					$config['upload_path'] = './assets/uploads/leaveattachment/';
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
				if(isset($_FILES)) {
					$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
					$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				}
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->updateRows($post_data)) {
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

	//Special Leave
	public function addPendingLeaveSpecialForm(){
		$formData = $result = array();
		$result['key'] = 'addPendingLeaveSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleavespecialform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updatePendingLeaveSpecialForm(){
		$formData = $result = array();
		$result['key'] = 'updatePendingLeaveSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleavespecialform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function addPendingLeaveSpecial(){
		$result = array();
		$page = 'addPendingLeaveSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/leaveattachment'))
						mkdir('./assets/uploads/leaveattachment', 0777, true);
					$config['upload_path'] = './assets/uploads/leaveattachment/';
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
				if(isset($_FILES)) {
					$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
					$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				}

				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->addRowsSpecial($post_data)) {
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
	public function updatePendingLeaveSpecial(){
		$result = array();
		$page = 'updatePendingLeaveSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/leaveattachment'))
						mkdir('./assets/uploads/leaveattachment', 0777, true);
					$config['upload_path'] = './assets/uploads/leaveattachment/';
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
				if(isset($_FILES)) {
					$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
					$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				}
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->updateRowsSpecial($post_data)) {
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

	public function generatePendingLeaveForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'generatePendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$res = array(
					array("employee_id"=>"1","first_name"=>"Edrian","last_name"=>"Vidal"),
					array("employee_id"=>"2","first_name"=>"Dennis","last_name"=>"Baguyo")
			);
			$formData['employees'] = $res;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/servicesearchform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function generatePendingLeave(){
		$result = array();
		$page = 'generatePendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection($post_data['employee_id']);
				if($ret->generateEmployeePendingLeave($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				}
				$result = json_decode($res,true);
				$helperData['list'] = json_decode($res);
				$ret = new PendingLeaveCollection();
				if($ret->getSignatory()){
					$res2 = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
					$respo2 = json_decode($res2);
				}
				else{
					$res2 = new ModelResponse($ret->getCode(), $ret->getMessage());
					$respo2 = json_decode($res2);
				}
				$helperData['signatory'] = $respo2;
				$table = $this->load->view('helpers/pendingleaveemployee.php', $helperData, TRUE);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
			$result['table'] = $table;
		}
		echo json_encode($result);
	}

	public function activatePendingLeave(){
		$result = array();
		$page = 'activatePendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->activeRows($post_data)) {
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

	public function deactivatePendingLeave(){
		$result = array();
		$page = 'deactivatePendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->inactiveRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getLeaveDates(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->fetchLeaveDates($post_data['id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	// public function getLeaveApprovals(){
	// 	$result = array();
	// 	if (!$this->input->is_ajax_request()) show_404();
	// 	else {
	// 		if($this->input->post() && $this->input->post() != null) {
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PendingLeaveCollection();
	// 			if($ret->fetchLeaveApprovals($post_data['id'])) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		} else {
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 	}
	// 	echo json_encode($result);
	// }

	public function certifyPendingLeave(){
		$result = array();
		$page = 'certifyPendingLeave';
		// var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.Helper::get("userid");
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
				$ret =  new PendingLeaveCollection();
				if($ret->certifyRows($post_data)) {
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
	
	public function recommendPendingLeave(){
		$result = array();
		$page = 'recommendPendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				// if(isset($_FILES)) {
				// 	$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				// 	$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				// 	$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				// 	$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				// 	$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				// 	$uploadpath = './assets/uploads/leaveapproval/'.Helper::get("userid");
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
				$ret =  new PendingLeaveCollection();
				if($ret->recommendRows($post_data)) {
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
	
	public function recommendPendingLeaveHead(){
		$result = array();
		$page = 'recommendPendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.Helper::get("userid");
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
				$ret =  new PendingLeaveCollection();
				if($ret->recommendRowsHead($post_data)) {
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
	
	public function approvePendingLeave(){
		$result = array();
		$page = 'approvePendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.Helper::get("userid");
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
				$ret =  new PendingLeaveCollection();
				if($ret->approveRows($post_data)) {
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

	public function rejectPendingLeave(){
		$result = array();
		$page = 'rejectPendingLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.Helper::get("userid");
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
				$ret =  new PendingLeaveCollection();
				if($ret->rejectRows($post_data)) {
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
	public function approveDisapprovedLeave(){
		$result = array();
		$page = 'approveDisapprovedLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.$this->input->post('sess_employee_id');
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
				$post_data  = $this->array_push_assoc($post_data, 'dir_file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'dir_file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->approveDisapprovedLeave($post_data)) {
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
	public function rejectDisapprovedLeave(){
		$result = array();
		$page = 'rejectDisapprovedLeave';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/leaveapproval/'.$this->input->post('sess_employee_id');
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
				$post_data  = $this->array_push_assoc($post_data, 'dir_file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'dir_file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->rejectDisapprovedLeave($post_data)) {
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

	public function getDays(){
		$result = array();
		$page = 'getDays';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PendingLeaveCollection();
				if($ret->getDays($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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
}

?>