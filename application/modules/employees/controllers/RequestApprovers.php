<?php

class RequestApprovers extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('RequestApproversCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::EMPLOYEES_APPROVERS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewEmployees";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/requestapproverslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Manage Employees Approver');
			Helper::setMenu('templates/menu_template');
			Helper::setView('requestapprovers',$viewData,FALSE);
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
		$data = array();
		
		$ret = 	$ret = $this->RequestApproversCollection->getRows($_POST);
			foreach($ret as $k => $row){  
				$buttons_payroll_info = "";
				$buttons_action = "";
				$buttons_data = "";
				$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->id):"";
				$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->id):"";
				$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->id):"";
				$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->id):"";
				$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->id):"";
				$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->id):"";
				if($row->employee_number != "") $row->employee_number = str_pad($row->employee_number, 4, '0', STR_PAD_LEFT);

				$sub_array = array();    
				foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.str_replace(" 00:00:00","",$v1).'" ';

				if(Helper::role(ModuleRels::EMPLOYEE_UPDATE_DETAILS)):
				
					$buttons_action .= ' <a id="updateEmployeesApproverForm" ' 
					. ' class="updateEmployeesApproverForm" style="text-decoration: none;" '
					. ' href="'. base_url().'employees/Employees/updateEmployeesApproverForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approvers">'
					. ' <i class="material-icons">playlist_add_check</i> '
					. ' </button> '
					. ' </a> ';
				endif;
				$sub_array[] = $buttons_action;
				$sub_array[] = $row->emp_number; //empl no
				$sub_array[] = $row->scanning_no; // scanning no
				$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;  //emp name
				$sub_array[] = $row->position_name; // position
				$sub_array[] = $row->department_name; // division
				$sub_array[] = $row->grade; //salary grade
				if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != "Active") $sub_array[] = (trim($row->end_date) != "") ? date("F d, Y", strtotime($row->end_date)):""; // date of last service
				else $sub_array[] = (trim($row->start_date) != "") ? date("F d, Y", strtotime($row->start_date)):""; // date of assumption
				$status_color = "text-danger";
				if($row->employment_status == "Active" || $row->employment_status == "ACTIVE") $status_color = "text-success";
				$sub_array[] = '<b><span class="'.$status_color.'">'.strtoupper($row->employment_status).'</span><b>'; // employment status
				$sub_array[] = $row->gender; // sex
				
				$sub_array[] = date("F d, Y", strtotime($row->date_created)); // date created
				$sub_array[] = (trim($row->date_modified) !== "0000-00-00 00:00:00") ? date("F d, Y", strtotime($row->date_modified)):""; // date modified
				$data[] = $sub_array;  
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->RequestApproversCollection->countAll(),
				"recordsFiltered" => $this->RequestApproversCollection->countFiltered($_POST),
				"data" => $data,
			);
        echo json_encode($output);  
	}
	//Load approvers
	public function getCTOApprovers(){
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->getCTOApprovers($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		
		$result = json_decode($res,true);
		if(isset($result['Data'])){
			foreach ($result['Data'] as $k => $v) {
					$result['Data'][$k]['employee_number'] = $this->Helper->decrypt($v['employee_number'],$v['emp_id']);
					$result['Data'][$k]['employee_id_number'] = $this->Helper->decrypt($v['employee_id_number'],$v['emp_id']);
					$result['Data'][$k]['first_name'] = $this->Helper->decrypt($v['first_name'],$v['emp_id']);
    				$result['Data'][$k]['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['emp_id']);
    				$result['Data'][$k]['last_name'] = $this->Helper->decrypt($v['last_name'],$v['emp_id']);
					$result['Data'][$k]['employee'] = $result['Data'][$k]['last_name'] . ", " . $result['Data'][$k]['first_name'] . " " . $result['Data'][$k]['middle_name'];
					$result['Data'][$k]['approver_status'] = $v['approver_status'] === "1" ? "ACTIVE" : "INACTIVE";
					$buttons = '<a href="javascript:void(0);" data-id="'.$v['id'].'" data-approver="'.$v['approver'].'" data-type="'.$v['approve_type'].'" data-status="'.$v['approver_status'].'" class="btn btn-warning btn-md btn-circle waves-effect waves-circle waves-float select_cto_approver"><i class="material-icons">edit</i></a> ';
					if($v['approver_status'] === "0")
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-info btn-md btn-circle waves-effect waves-circle waves-float activate_cto_approver"><i class="material-icons">check_circle</i></a> ';
					else 
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float deactivate_cto_approver"><i class="material-icons">remove_circle</i></a> ';
					$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float delete_cto_approver"><i class="material-icons">close</i></a> ';
					$result['Data'][$k]['action'] = $buttons;
			}
		}
		echo json_encode($result);
	}

	public function getLeaveApprovers(){
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->getLeaveApprovers($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		
		$result = json_decode($res,true);
		if(isset($result['Data'])){
			foreach ($result['Data'] as $k => $v) {
					$result['Data'][$k]['employee_number'] = $this->Helper->decrypt($v['employee_number'],$v['emp_id']);
					$result['Data'][$k]['employee_id_number'] = $this->Helper->decrypt($v['employee_id_number'],$v['emp_id']);
					$result['Data'][$k]['first_name'] = $this->Helper->decrypt($v['first_name'],$v['emp_id']);
    				$result['Data'][$k]['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['emp_id']);
    				$result['Data'][$k]['last_name'] = $this->Helper->decrypt($v['last_name'],$v['emp_id']);
					$result['Data'][$k]['employee'] = $result['Data'][$k]['last_name'] . ", " . $result['Data'][$k]['first_name'] . " " . $result['Data'][$k]['middle_name'];
					$result['Data'][$k]['approver_status'] = $v['approver_status'] === "1" ? "ACTIVE" : "INACTIVE";
					$buttons = '<a href="javascript:void(0);" data-id="'.$v['id'].'" data-approver="'.$v['approver'].'" data-type="'.$v['approve_type'].'" data-status="'.$v['approver_status'].'" class="btn btn-warning btn-md btn-circle waves-effect waves-circle waves-float select_leave_approver"><i class="material-icons">edit</i></a> ';
					if($v['approver_status'] === "0")
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-info btn-md btn-circle waves-effect waves-circle waves-float activate_leave_approver"><i class="material-icons">check_circle</i></a> ';
					else 
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float deactivate_leave_approver"><i class="material-icons">remove_circle</i></a> ';
					$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float delete_leave_approver"><i class="material-icons">close</i></a> ';
					$result['Data'][$k]['action'] = $buttons;
			}
		}
		echo json_encode($result);
	}
	
	public function getOBApprovers(){
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->getOBApprovers($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		
		$result = json_decode($res,true);
		if(isset($result['Data'])){
			foreach ($result['Data'] as $k => $v) {
					$result['Data'][$k]['employee_number'] = $this->Helper->decrypt($v['employee_number'],$v['emp_id']);
					$result['Data'][$k]['employee_id_number'] = $this->Helper->decrypt($v['employee_id_number'],$v['emp_id']);
					$result['Data'][$k]['first_name'] = $this->Helper->decrypt($v['first_name'],$v['emp_id']);
    				$result['Data'][$k]['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['emp_id']);
    				$result['Data'][$k]['last_name'] = $this->Helper->decrypt($v['last_name'],$v['emp_id']);
					$result['Data'][$k]['employee'] = $result['Data'][$k]['last_name'] . ", " . $result['Data'][$k]['first_name'] . " " . $result['Data'][$k]['middle_name'];
					$result['Data'][$k]['approver_status'] = $v['approver_status'] === "1" ? "ACTIVE" : "INACTIVE";
					$buttons = '<a href="javascript:void(0);" data-id="'.$v['id'].'" data-approver="'.$v['approver'].'" data-type="'.$v['approve_type'].'" data-status="'.$v['approver_status'].'" class="btn btn-warning btn-md btn-circle waves-effect waves-circle waves-float select_ob_approver"><i class="material-icons">edit</i></a> ';
					if($v['approver_status'] === "0")
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-info btn-md btn-circle waves-effect waves-circle waves-float activate_ob_approver"><i class="material-icons">check_circle</i></a> ';
					else 
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float deactivate_ob_approver"><i class="material-icons">remove_circle</i></a> ';
					$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float delete_ob_approver"><i class="material-icons">close</i></a> ';
					$result['Data'][$k]['action'] = $buttons;
			}
		}
		echo json_encode($result);
	}

	public function getTravelApprovers(){
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->getTravelApprovers($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		
		$result = json_decode($res,true);
		if(isset($result['Data'])){
			foreach ($result['Data'] as $k => $v) {
					$result['Data'][$k]['employee_number'] = $this->Helper->decrypt($v['employee_number'],$v['emp_id']);
					$result['Data'][$k]['employee_id_number'] = $this->Helper->decrypt($v['employee_id_number'],$v['emp_id']);
					$result['Data'][$k]['first_name'] = $this->Helper->decrypt($v['first_name'],$v['emp_id']);
    				$result['Data'][$k]['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['emp_id']);
    				$result['Data'][$k]['last_name'] = $this->Helper->decrypt($v['last_name'],$v['emp_id']);
					$result['Data'][$k]['employee'] = $result['Data'][$k]['last_name'] . ", " . $result['Data'][$k]['first_name'] . " " . $result['Data'][$k]['middle_name'];
					$result['Data'][$k]['approver_status'] = $v['approver_status'] === "1" ? "ACTIVE" : "INACTIVE";
					$buttons = '<a href="javascript:void(0);" data-id="'.$v['id'].'" data-approver="'.$v['approver'].'" data-type="'.$v['approve_type'].'" data-status="'.$v['approver_status'].'" class="btn btn-warning btn-md btn-circle waves-effect waves-circle waves-float select_travel_approver"><i class="material-icons">edit</i></a> ';
					if($v['approver_status'] === "0")
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-info btn-md btn-circle waves-effect waves-circle waves-float activate_travel_approver"><i class="material-icons">check_circle</i></a> ';
					else 
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float deactivate_travel_approver"><i class="material-icons">remove_circle</i></a> ';
					$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float delete_travel_approver"><i class="material-icons">close</i></a> ';
					$result['Data'][$k]['action'] = $buttons;
			}
		}
		echo json_encode($result);
	}


	//Add approvers
	public function addCTOApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->addCTOApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	
	public function addLeaveApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->addLeaveApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function addTravelApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->addTravelApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function addOBApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->addOBApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	//Update approvers
	public function updateCTOApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->updateCTOApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function updateLeaveApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->updateLeaveApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	public function updateOBApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->updateOBApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function updateTravelApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->updateTravelApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	//Activate approvers	
	public function activateCTOApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->activateCTOApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function activateLeaveApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->activateLeaveApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function activateOBApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->activateOBApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function activateTravelApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->activateTravelApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	//Deactivate approvers
	public function deactivateCTOApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deactivateCTOApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deactivateLeaveApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deactivateLeaveApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deactivateOBApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deactivateOBApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deactivateTravelApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deactivateTravelApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	//Delete approvers
	public function deleteCTOApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deleteCTOApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deleteLeaveApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deleteLeaveApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deleteOBApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deleteOBApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deleteTravelApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deleteTravelApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	//View and update approvers
	public function updateEmployeesApproverForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeesApprover';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesapproverform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	// Request For Overtime
		//OT Request
		public function getOvertimeApprovers(){
			if (!$this->input->is_ajax_request()) show_404();
			$ret =  new RequestApproversCollection();
			if($ret->getOvertimeApprovers($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data'] as $k => $v) {
						$result['Data'][$k]['employee_number'] = $this->Helper->decrypt($v['employee_number'],$v['emp_id']);
						$result['Data'][$k]['employee_id_number'] = $this->Helper->decrypt($v['employee_id_number'],$v['emp_id']);
						$result['Data'][$k]['first_name'] = $this->Helper->decrypt($v['first_name'],$v['emp_id']);
						$result['Data'][$k]['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['emp_id']);
						$result['Data'][$k]['last_name'] = $this->Helper->decrypt($v['last_name'],$v['emp_id']);
						$result['Data'][$k]['employee'] = $result['Data'][$k]['last_name'] . ", " . $result['Data'][$k]['first_name'] . " " . $result['Data'][$k]['middle_name'];
						$result['Data'][$k]['approver_status'] = $v['approver_status'] === "1" ? "ACTIVE" : "INACTIVE";
						$buttons = '<a href="javascript:void(0);" data-id="'.$v['id'].'" data-approver="'.$v['approver'].'" data-type="'.$v['approve_type'].'" data-status="'.$v['approver_status'].'" class="btn btn-warning btn-md btn-circle waves-effect waves-circle waves-float select_overtime_approver" data-toggle="tooltip" data-placement="top" title="Update"><i class="material-icons">edit</i></a> ';
						if($v['approver_status'] === "0")
							$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-info btn-md btn-circle waves-effect waves-circle waves-float activate_overtime_approver" data-toggle="tooltip" data-placement="top" title="Approved"><i class="material-icons">check_circle</i></a> ';
						else 
							$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float deactivate_overtime_approver" data-toggle="tooltip" data-placement="top" title="Disapproved"><i class="material-icons">remove_circle</i></a> ';
						$buttons .= '<a href="javascript:void(0);" data-id="'.$v['id'].'" class="btn btn-danger btn-md btn-circle waves-effect waves-circle waves-float delete_overtime_approver" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons">close</i></a> ';
						$result['Data'][$k]['action'] = $buttons;
				}
			}
			echo json_encode($result);
		}
		
	public function deleteOvertimeApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deleteOvertimeApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function deactivateOvertimeApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->deactivateOvertimeApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function addOvertimeApprovers(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->addOvertimeApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	public function updateOTApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->updateOvertimeApprover($_POST)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function activateOvertimeApprover(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		$ret =  new RequestApproversCollection();
		if($ret->activateOvertimeApprover($_POST['id'])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	// End of Request Overtime
}
?>