<?php

class LocatorSlips extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LocatorSlipsCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		// Helper::rolehook(ModuleRels::HRIS_DASHBOARD);
		$listData = array();
		$viewData = array();
		$page = "viewLocatorSlips";
		$listData['key'] = $page;
		
		$listData['list'] = "";
		$viewData['table'] = $this->load->view("helpers/locatorslipslist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/businesspermissionform",$listData['key'],TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Locator Slip Transaction');
			Helper::setMenu('templates/menu_template');
			Helper::setView('locatorslips',$viewData,FALSE);
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
		$fetch_data = $this->LocatorSlipsCollection->make_datatables();
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
			if(Helper::role(ModuleRels::LOCATOR_SLIPS_VIEW_DETAILS)):
			$buttons .= '<a id="viewLocatorSlipsForm" class="viewLocatorSlipsForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLocatorSlipsForm'.'"data-toggle="tooltip" data-placement="top" title="View Details" '.$buttons_data.'><i class="material-icons">remove_red_eye</i></a>';
			endif;
			if($row->status == "APPROVED" && $row->secondapprove > 0):
				$buttons .=	'<a class="AssignDriverVehicle btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Assign Driver and Vehicle" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/AssignDriverVehicle" '.$buttons_data.'><i class="material-icons">done</i></a>';

			endif;
			if($row->status == "FOR APPROVAL" && $row->approve > 0):
				$buttons .=	'<a class="approveLocatorSlips btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approveLocatorSlips" '.$buttons_data.'><i class="material-icons">done</i></a>';
			$buttons .=	'<a class="rejectLocatorSlips btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectLocatorSlips" '.$buttons_data.'><i class="material-icons">do_not_disturb</i></a>';
			endif;
			if($row->status == "PENDING" && $row->recom > 0):
			$buttons .=	'<a class="recommendation btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendation" '.$buttons_data.'><i class="material-icons">done</i></a>';
			$buttons .=	'<a class="rejectLocatorSlips btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectLocatorSlips" '.$buttons_data.'><i class="material-icons">do_not_disturb</i></a>';
			endif;

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
            "recordsTotal"          =>     $this->LocatorSlipsCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->LocatorSlipsCollection->get_filtered_data(),  
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
	public function addLocatorSlipsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/locatorslipsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateLocatorSlipsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/locatorslipsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	// public function addLocatorSlips(){
	// 	// $result = array();
	// 	// $page = 'addLocatorSlips';
	// 	// if (!$this->input->is_ajax_request()) {
	// 	//    show_404();
	// 	// }
	// 	// else
	// 	// {
			
	// 	// 	if($this->input->post() && $this->input->post() != null){
	// 	// 		foreach ($_POST['table1'] as $key => $value) {
	// 	// 			$_FILES['uploadFile']['name'] 		= $_FILES['table1']['name'][$key]['file'];
	// 	// 			$_FILES['uploadFile']['type'] 		= $_FILES['table1']['type'][$key]['file'];
	// 	// 			$_FILES['uploadFile']['size'] 		= $_FILES['table1']['size'][$key]['file'];
	// 	// 			$_FILES['uploadFile']['error'] 		= $_FILES['table1']['error'][$key]['file'];
	// 	// 			$_FILES['uploadFile']['tmp_name'] = $_FILES['table1']['tmp_name'][$key]['file'];
	// 	// 			if (!file_exists('./assets/uploads/locatorslips'))
	// 	// 				mkdir('./assets/uploads/locatorslips', 0777, true);
	// 	// 			// $config['upload_path'] = './assets/uploads/locatorslips/';
	// 	// 			// $config['allowed_types'] = '*';
	// 	// 			// $config['overwrite'] = TRUE;
	// 	// 			// $config['remove_spaces'] = FALSE;
	// 	// 			// $this->upload->initialize($config);
	// 	// 			// if ($this->upload->do_upload('uploadFile')):
	// 	// 			// 	$data = array('upload_data' => $this->upload->data());
	// 	// 			// else:
	// 	// 			// 	$error = array('error' => $this->upload->display_errors());
	// 	// 			// endif;
	// 	// 			// date("H:i", strtotime("04:25 PM"));
	// 	// 			// var_dump($_POST['table2'][$key]['locator_transaction_time']);
	// 	// 			// var_dump(date("H:i:s", strtotime($_POST['table2'][0]['locator_transaction_time']))); 
	// 	// 			// die();


	// 	// 			foreach ($_POST['table2'][$key]['locator_transaction_time'] as $key2 => $value2) {
	// 	// 				$_POST['table2'][$key]['locator_transaction_time'][$key2] = date("H:i:s", strtotime($value2));
	// 	// 			}

	// 	// 			$post_data = array();
	// 	// 			$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['table1']['name'][$key]['file']);
	// 	// 			$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['table1']['size'][$key]['file']);
	// 	// 			$post_data  = $this->array_push_assoc($post_data, 'locator_transaction_time', $_POST['table2'][$key]['locator_transaction_time']);
	// 	// 			$post_data  = $this->array_push_assoc($post_data, 'locator_transaction_type', $_POST['table2'][$key]['locator_transaction_type']);

	// 	// 			// $post_data  = $this->array_push_assoc($post_data, 'leave_grouping_id', $_POST['leave_grouping_id']);
	// 	// 			// $post_data  = $this->array_push_assoc($post_data, 'employee_id', $_POST['employee_id']);
	// 	// 			foreach ($_POST['table1'][$key] as $k => $v) {
	// 	// 				$post_data[$k] = $_POST['table1'][$key][$k];
	// 	// 			}

	// 	// 			// $post_data = array();
	// 	// 			// foreach ($this->input->post() as $k => $v) {
	// 	// 			// 	$post_data[$k] = $this->input->post($k,true);
	// 	// 			// }
	// 	// 			// var_export($post_data);die();
	// 	// 			$ret =  new LocatorSlipsCollection();
	// 	// 			if($ret->addRows($post_data)) {
	// 	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 	// 			} else {
	// 	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 	// 			}
	// 	// 			$result = json_decode($res,true);
	// 	// 		}
	// 	// 	} else {
	// 	// 		$res = new ModelResponse();
	// 	// 		$result = json_decode($res,true);
	// 	// 	}
	// 	// 	$result['key'] = $page;
	// 	// }
	// 	// echo json_encode($result);
	// }


	public function addLocatorSlips(){

		$result = array();
		$page = 'addLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{	
				// var_dump($this->input->post()); die();
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($_POST['table1'] as $key => $value) {
					
					$post_data[$key]  = $value;
					// var_dump($key);
				}
				die();
				$ret =  new LocatorSlipsCollection();
				if($ret->addRows($post_data)) {
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

	public function updateLocatorSlips(){
		// var_dump($_POST); die();
		$result = array();
		$page = 'updateLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/locatorslips'))
						mkdir('./assets/uploads/locatorslips', 0777, true);
					$config['upload_path'] = './assets/uploads/locatorslips/';
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
					$params = $this->input->post($k,true);
					if($k == 'locator_transaction_time'){
						$timetrasaction = array();
						foreach ($this->input->post($k) as $k2 => $v2) {
							$timetrasaction[] = date("H:i:s", strtotime($v2));	
						}
						$params = $timetrasaction;
					}
					$post_data[$k] = $params;
				}

				$ret =  new LocatorSlipsCollection();
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

	public function approveLocatorSlips(){
		$result = array();
		$page = 'approveLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				if(isset($_FILES)) {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					$uploadpath = './assets/uploads/locatorapproval/'.Helper::get("userid");
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
				$ret =  new LocatorSlipsCollection();
				if($ret->approveRows($post_data)) {
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

	public function AssignDriverVehicle(){
		$result = array();
		$page = 'AssignDriverVehicle';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LocatorSlipsCollection();
				if($ret->AssignDriverVehicle($post_data)) {
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

	public function recommendation(){
		$result = array();
		$page = 'recommendation';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LocatorSlipsCollection();
				if($ret->recommendation($post_data)) {
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

	public function rejectLocatorSlips(){
		$result = array();
		$page = 'rejectLocatorSlips';
		// var_dump($_POST); die();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LocatorSlipsCollection();
				if($ret->rejectRows($post_data)) {
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
	public function getActiveLocatorSlips(){
		$result = array();
		$page = 'getActiveLocatorSlips';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new LocatorSlipsCollection();
			if($ret->hasRowsActive()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getActualLogs() {
		$model =  new LocatorSlipsCollection();
		$number = $model->getScanningNumber($_POST['employee_id']);
		$result['number'] = @$number;
		$result['dtr'] = @$model->getDailyTimeRecord($number, $_POST['transaction_date']);
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	public function getOBApprovals(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LocatorSlipsCollection();
				if($ret->fetchOBApprovals($post_data['id'], $post_data['employee_id'])) {
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
}

?>