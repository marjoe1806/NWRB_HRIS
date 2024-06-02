<?php

class CTOApproval extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CTOApprovalCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_CTO_APPROVER);
		$listData = array();
		$viewData = array();
		$page = "viewCTOApproval";
		$listData['key'] = $page;

		$ret = new CTOApprovalCollection();
		$viewData['table'] = $this->load->view("helpers/ctoapprovallist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/ctoprintpreview", $listData['key'], TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Compensatory Time Off Approval');
			Helper::setMenu('templates/menu_template');
			Helper::setView('ctoapproval',$viewData,FALSE);
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
        $fetch_data = $this->CTOApprovalCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row){ 
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
			
			// $ret_offset = $this->CTOApprovalCollection->get_service_record($row->employee_id);
			// $offset =  @$ret_offset['employee'][0]['totaloffset'] != ""? ($ret_offset['employee'][0]['totaloffset'] / 0.0020833333333333) / 60 : 0;
			// $offsetHrs = floor($offset);
			// $offsetMins = floor(($offset - $offsetHrs)*60);
			$offset =  explode(":",$row->total_offset);
			$offsetHrs = floor($offset[0]);
			$offsetMins = floor($offset[1]);

            $sub_array = array();    
            if($row->type == 'offset') $row->type = "CTO";
            if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";

			if($row->status_name == "REJECTED"){
				$status_name = "DISAPPROVED";
			}else{
				$status_name = $row->status_name;
			}

            foreach($row as $k1=>$v1){
				$buttons_data .= ' data-offset-hrs="'.$offsetHrs.'" ';
				$buttons_data .= ' data-offset-mins="'.$offsetMins.'" ';
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
            // if(Helper::role(ModuleRels::LEAVE_VIEW_DETAILS)): 
            	$buttons .= ' <a id="viewCTOApprovalDetails" ' 
            		  . ' class="viewCTOApprovalDetails" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewCTOApprovalDetails" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
			// endif;
            	if($row->status == 1){
					$buttons .= ' <a id="certifyCTOApproval" ' 
					   . ' class="certifyCTOApproval" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/certifyCTOApproval" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Certification">'
					   . ' <i class="material-icons">done</i> '
					   . ' </button> '
					   . ' </a> ';
					$buttons .= ' <a id="rejectCTOApproval" ' 
					   . ' class="rejectCTOApproval" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectCTOApproval" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
					   . ' <i class="material-icons">clear</i> '
					   . ' </button> '
					   . ' </a> ';
				}
			
			// if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) || $row->certify > 0): //CTO_RECOMMEND
				if($row->status == 2){
					$buttons .= ' <a id="recommendCTOApproval" ' 
					   . ' class="recommendCTOApproval" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendCTOApproval" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
					   . ' <i class="material-icons">done</i> '
					   . ' </button> '
					   . ' </a> ';
					$buttons .= ' <a id="rejectCTOApproval" ' 
					   . ' class="rejectCTOApproval" style="text-decoration: none;" '
					   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectCTOApproval" '
					   . $buttons_data
					   . ' > '
					   . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
					   . ' <i class="material-icons">clear</i> '
					   . ' </button> '
					   . ' </a> ';
				}
		 	// endif;
			
			// if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) || $row->recom > 0): //CTO_CERTIFY
				if($row->status == 3){
					$buttons .= ' <a id="recommendCTOApproval2" ' 
						. ' class="recommendCTOApproval2" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendCTOApproval2" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Recommendation">'
						. ' <i class="material-icons">done</i> '
						. ' </button> '
						. ' </a> ';
					$buttons .= ' <a id="rejectCTOApproval" ' 
						. ' class="rejectCTOApproval" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectCTOApproval" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
						. ' <i class="material-icons">clear</i> '
						. ' </button> '
						. ' </a> ';
				}
			// endif;
			   
           	// if(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS) || $row->approve > 0): //CTO_APPROVE
				if($row->status == 4){
					$buttons .= ' <a id="approveCTOApproval" ' 
	            		  . ' class="approveCTOApproval" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approveCTOApproval" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="For Approval">'
	            		  . ' <i class="material-icons">done</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
					$buttons .= ' <a id="rejectCTOApproval" ' 
	            		  . ' class="rejectCTOApproval" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectCTOApproval" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove">'
	            		  . ' <i class="material-icons">clear</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
	        	}
			// endif;

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
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;
            $sub_array[] = $row->filing_date;
            $sub_array[] = ucwords(str_replace("_"," ",$row->type));
			$sub_array[] = $row->offset_date_effectivity;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $sub_array[] = $row->remarks;
            $data[] = $sub_array;
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->CTOApprovalCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->CTOApprovalCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	//Regular Leave
	public function addCTOApprovalRegularForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addCTOApprovalRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleaveregularform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateCTOApprovalRegularForm(){
		$formData = $result = array();
		$result['key'] = 'updateCTOApprovalRegular';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleaveregularform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewCTOApprovalDetails(){
		$formData = $result = array();
		$result['key'] = 'viewCTOApprovalDetails';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/ctorequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addCTOApprovalRegular(){
		$result = array();
		$page = 'addCTOApprovalRegular';
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
				$ret =  new CTOApprovalCollection();
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

	public function updateCTOApprovalRegular(){
		$result = array();
		$page = 'updateCTOApprovalRegular';
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
				$ret =  new CTOApprovalCollection();
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
	public function addCTOApprovalSpecialForm(){
		$formData = $result = array();
		$result['key'] = 'addCTOApprovalSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleavespecialform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateCTOApprovalSpecialForm(){
		$formData = $result = array();
		$result['key'] = 'updateCTOApprovalSpecial';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleavespecialform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function addCTOApprovalSpecial(){
		$result = array();
		$page = 'addCTOApprovalSpecial';
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
				$ret =  new CTOApprovalCollection();
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
	public function updateCTOApprovalSpecial(){
		$result = array();
		$page = 'updateCTOApprovalSpecial';
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
				$ret =  new CTOApprovalCollection();
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

	public function generateCTOApprovalForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'generateCTOApproval';
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
	
	public function generateCTOApproval(){
		$result = array();
		$page = 'generateCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CTOApprovalCollection($post_data['employee_id']);
				if($ret->generateEmployeeCTOApproval($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				}
				$result = json_decode($res,true);
				$helperData['list'] = json_decode($res);
				$ret = new CTOApprovalCollection();
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

	public function activateCTOApproval(){
		$result = array();
		$page = 'activateCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CTOApprovalCollection();
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

	public function deactivateCTOApproval(){
		$result = array();
		$page = 'deactivateCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CTOApprovalCollection();
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
				$ret =  new CTOApprovalCollection();
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

	public function getCTOApprovals(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CTOApprovalCollection();
				if($ret->fetchCTOApprovals($post_data['id'])) {
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

	public function recommendCTOApproval(){
		$result = array();
		$page = 'recommendCTOApproval';
		// var_dump($this->input->post()); die();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				// if(isset($_FILES)) {
				// 	$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				// 	$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				// 	$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				// 	$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				// 	$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				// 	$uploadpath = './assets/uploads/ctoapproval/'.Helper::get("userid");
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
				$ret =  new CTOApprovalCollection();
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

	public function recommendCTOApproval2(){
		$result = array();
		$page = 'recommendCTOApproval2';
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
					$uploadpath = './assets/uploads/ctoapproval/'.Helper::get("userid");
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
				$ret =  new CTOApprovalCollection();
				if($ret->recommendRows2($post_data)) {
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
	
	public function certifyCTOApproval(){
		$result = array();
		$page = 'certifyCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/ctoapproval/'.Helper::get("userid");
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
				$ret =  new CTOApprovalCollection();
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
	
	
	public function approveCTOApproval(){
		$result = array();
		$page = 'approveCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/ctoapproval/'.Helper::get("userid");
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
				$ret =  new CTOApprovalCollection();
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

	public function rejectCTOApproval(){
		$result = array();
		$page = 'rejectCTOApproval';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/ctoapproval/'.Helper::get("userid");
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
				$ret =  new CTOApprovalCollection();
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
				$ret =  new CTOApprovalCollection();
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