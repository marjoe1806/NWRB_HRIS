<?php

class Employees extends MX_Controller {
	
	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeesCollection');
		$this->load->model('../../usermanagement/models/MobileUserConfigCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] == 1){
			Helper::rolehook(ModuleRels::EX_EMPLOYEES_SUB_MENU);
		}
		else{
			Helper::rolehook(ModuleRels::EMPLOYEES_SUB_MENU);
		}
		$listData = array();
		$viewData = array();
		$page = "viewEmployees";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/employeeslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] == 1) Helper::setTitle('Former Employees');
			else Helper::setTitle('Active Employees');
			Helper::setMenu('templates/menu_template');
			Helper::setView('employees',$viewData,FALSE);
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
		$ret = $this->EmployeesCollection->make_datatables() ;
		// var_dump($ret);die();
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
				// $school = '';
				// $isHave= $this->db->select("school")->from('tblemployeeseducbgcolleges')->where("employee_id", $row->id)->limit(1)->get()->row_array();
				// if($isHave){
				// 	$school1= array(
				// 		'school' => $isHave['school'],
				// 	);
				// }
				// $school = $school1['school'];	
				
				$sub_array = array();    
				foreach($row as $k1=>$v1) {
					if($k1 == "date_of_permanent" || $k1 == "end_date" || $k1 == "start_date"){
						if($v1 != "0000-00-00" && $v1 != "") $v1 = date("m-d-Y", strtotime($v1));
						else $v1 = "";
					}
					$buttons_data .= ' data-'.$k1.'="'.str_replace(" 00:00:00","",$v1).'" ';
				}
				if(Helper::role(ModuleRels::EMPLOYEE_VIEW_DETAILS)): 
				$buttons_action .= ' <a id="viewEmployeesForm" ' 
						. ' class="viewEmployeesForm" style="text-decoration: none;" '
						. ' href="'. base_url().'employees/Employees/viewEmployeesForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Print Preview">'
						. ' <i class="material-icons">print</i>'
						. ' </button> '
						. ' </a> ';
				endif;
				
				$buttons_payroll_info .= ' <a id="viewEmployeesPayrollInfoForm" ' 
						. ' class="viewEmployeesPayrollInfoForm" style="text-decoration: none;" '
						. ' href="'. base_url().'employees/Employees/viewEmployeesPayrollInfoForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
						. ' <i class="material-icons">payments</i> '
						. ' </button> '
						. ' </a> ';
				
				$buttons_action .= ' <a id="viewEmployeesAttachmentsForm" ' 
						. ' class="viewEmployeesAttachmentsForm" style="text-decoration: none;" '
						. ' href="'. base_url().'employees/Employees/viewEmployeesAttachmentsForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Attachments">'
						. ' <i class="material-icons">attach_file</i> '
						. ' </button> '
						. ' </a> ';

				if(Helper::role(ModuleRels::EMPLOYEE_UPDATE_DETAILS)): 
				// $buttons_payroll_info .= ' <a id="updateEmployeesForm" ' 
				// 		. ' class="updateEmployeesForm" style="text-decoration: none;" '
				// 		. ' href="'. base_url().'employees/Employees/updateEmployeesForm" '
				// 		. $buttons_data
				// 		. ' > '
				// 		. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="PDS Info">'
				// 		. ' <i class="material-icons">remove_red_eye</i> '
				// 		. ' </button> '
				// 		. ' </a> ';
				$buttons_action .= ' <a id="updateEmployeesForm" ' 
						. ' class="updateEmployeesForm" style="text-decoration: none;" '
						. ' href="'. base_url().'employees/Employees/updateEmployeesForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
						. ' <i class="material-icons">edit</i> '
						. ' </button> '
						. ' </a> ';
				endif;
				// if(isset($row->username)):
				// $buttons_action .= ' <a id="addEmployeeAccessForm" ' 
				// 			. ' class="addEmployeeAccessForm" style="text-decoration: none;" '
				// 			. ' href="javascript:void(0)"'
				// 			. $buttons_data
				// 			. ' > '
				// 			. ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" title="Give Employee PDS Access">'
				// 			. ' <i class="material-icons">remove_red_eye</i> '
				// 			. ' </button> '
				// 			. ' </a> ';
				// endif;

				
				$position_name = $row->position_name;
				if(!is_numeric($row->position_id)){
					$position_name = $row->position_id;
				}

				$sub_array[] = $buttons_payroll_info;
				$sub_array[] = $buttons_action;
				$sub_array[] = preg_replace("/[^0-9,.]/","",$row->employee_id_number); //empl no
				$sub_array[] = $row->employee_number; // scanning no
				$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;  //emp name
				$sub_array[] = $position_name; // position
				$sub_array[] = $row->department_name; // division
				// $sub_array[] = $row->grade; //salary grade
				$sub_array[] = ($row->grade != "") ? $row->grade : $row->salary_grade_id; //salary grade
				if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != "Active") $sub_array[] = (trim($row->end_date) != "") ? date("F d, Y", strtotime($row->end_date)):""; // date of last service
				else $sub_array[] = (trim($row->start_date) != "") ? date("F d, Y", strtotime($row->start_date)):""; // date of assumption
				$status_color = "text-danger";
				if($row->employment_status == "Active" || $row->employment_status == "ACTIVE") $status_color = "text-success";
				$sub_array[] = '<b><span class="'.$status_color.'">'.strtoupper($row->employment_status).'</span><b>'; // employment status
				$sub_array[] = $row->gender; // sex
				$sub_array[] = $row->contact_number; //mobile number
				$sub_array[] = $row->email; //email
				// $sub_array[] = $school;
				$sub_array[] = date("F d, Y", strtotime($row->date_created)); // date created
				$sub_array[] = (trim($row->date_modified) !== "0000-00-00 00:00:00") ? date("F d, Y", strtotime($row->date_modified)):""; // date modified
				$data[] = $sub_array;  
			}
			

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->EmployeesCollection->get_all_data(),
				"recordsFiltered" => $this->EmployeesCollection->get_filtered_data(),
				"data" => $data,
			);
        echo json_encode($output);  
    }
    public function getEmpTables(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = $this->EmployeesCollection->getEmpRows($_POST["id"]);
		echo json_encode($ret);
	}
    public function getItemPosition(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = $this->EmployeesCollection->getItemPosition($_POST["id"]);
		echo json_encode($ret);
	}
	public function getEmpAttachments(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = new EmployeesCollection();
		if($ret->getEmpAtthcmentRows($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
    }
	public function isNameExist(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = new EmployeesCollection();
		if($ret->getisNameExist($_POST)) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
    }
    public function getPayrollConfig(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = new EmployeesCollection();
		if($ret->getPayrollConfigRow($_POST["id"])) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	
    public function getWithHoldingTax(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = $this->EmployeesCollection->getWithHoldingTaxRow($_POST["id"]);
		echo json_encode($ret);
	}
	
	public function addEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewEmployeesPayrollInfoForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeesPayrollInfo';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['locations'] = $this->MobileUserConfigCollection->getActiveLocations();
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollinfoform.php', $formData, TRUE);
		}
		echo json_encode($result); 
	}

	public function viewEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeprintpreview.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
 
	public function viewEmployeesAttachmentsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeesAttachments';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeattachmentform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

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

	public function viewEmployeeAllowancesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewEmployeeAllowances';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$ret = new EmployeesCollection();
			if($ret->hasRowsAllowances($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			}else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/employeeallowancesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
 
	public function addEmployeeAllowances(){
		$result = array();
		$page = 'addEmployeeAllowances';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeesCollection();
				if($ret->addAllowancesRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function updateEmployeeAllowances(){
		$result = array();
		$page = 'updateEmployeeAllowances';
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
				$ret =  new EmployeesCollection();
				if($ret->updateAllowancesRows($post_data)) {
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
	public function addEmployees(){
		$result = array();
		$page = 'addEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$count = 0;
				$files = (isset($_FILES))?$_FILES:array();
				// for($i=0;$i<sizeof($files["uploaded_file"]["name"]);$i++){
				// 	if(in_array($files["uploaded_file"]["type"][$i],$this->allowedfiles)) $count++;
				// }
				// if($count > 0){
					$post_data = array();
					foreach ($this->input->post() as $k => $v) {
						if($k != "employee_id_photo") $post_data[$k] = $this->input->post($k,true);
					}
					foreach ($files as $k => $v) {
						if($k != "employee_id_photo") $post_data[$k] = $files[$k];
					}
					$ret =  new EmployeesCollection();
					if($ret->addRows($post_data)) {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						$foldername = $ret->getData();
						$structure = './uploads/employees/'.$foldername;
						if(!file_exists($structure)) mkdir($structure, 0777, true);
						chmod($structure, 0775);
						$arrfailedfile = array();
						$inc = 0;
						$destination = getcwd(). "/uploads/employees/" . $foldername;
						$filecount = sizeof($files["uploaded_file"]["name"]);
						$fileupload = 0;
						if($filecount > 0){
							for($i=0;$i<$filecount;$i++){
								if($files["uploaded_file"]["size"][$i]>0){
									if(move_uploaded_file($files["uploaded_file"]["tmp_name"][$i], $destination.'/'.$files["uploaded_file"]["name"][$i])){
										$fileupload++;
									}
								}
							}
							// if($fileupload !== $filecount) $res = new ModelResponse($ret->getCode(), "Successfully inserted employee but something<br>went wrong uploading files.");
						}else{
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						}
					}else{
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}
				// } else {
				// 	$res = new ModelResponse("1", "Something went wrong uploading files.");
				// }
			} else {
				$res = new ModelResponse();
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function updateEmployees(){
		$result = array();
		$page = 'updateEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					if($k != "employee_id_photo"){
						$post_data[$k] = $this->input->post($k,true);
					}
				}
				$ret =  new EmployeesCollection();
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

	public function updateEmployeesPayrollInfo(){
		$result = array();
		$page = 'updateEmployeesPayrollInfo';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeesCollection();
				// print_r($post_data); die();
				if($ret->updatePayrollInfoRows($post_data)) {
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
	
	public function updateEmployeesAttachments(){
		$result = array();
		$page = 'addEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$files = (isset($_FILES))?$_FILES:array();
					$post_data = array();
					foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
					foreach ($files as $k => $v) $post_data[$k] = $files[$k];
					$ret =  new EmployeesCollection();
					if($ret->addEmployeeAttachment($post_data)) {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						$countNewFile = 0;
						$foldername = $post_data["id"];
						//var_dump($foldername).die();
						$structure = './uploads/employees/'.$foldername;
						$folder_files = glob($structure.'/*.*');
						if(!file_exists($structure)){
							mkdir($structure, 0777, true);
							chmod($structure, 0775);
						}
						$destination = getcwd(). "/uploads/employees/" . $foldername;
						foreach($folder_files as $k => $v){
							$ex = explode("/",$v);
							if(!in_array($ex[4],$post_data["cur_file"])) unlink($structure."/".$ex[4]);
						}
						$errorDelete = $errorRename = $errorUpload = $errorFormat = 0;
						if(isset($post_data["file_title"]) && sizeof($post_data["file_title"]) > 0){
							foreach($post_data["file_title"] as $key => $value){
								if($files["new_file"]["size"][$key] > 0){
									if(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"][$key] && $post_data["new_file"]["size"][$key] <= 0) if(!rename($structure."/".$post_data["cur_file"][$key],$structure."/".$value.substr(($files["new_file"]["name"][$key]!=="")?$files["new_file"]["name"][$key]:$post_data["cur_file"][$key],($files["new_file"]["type"][$key]==="image/jpeg")?-5:-4))) $errorRename++; // rename new file
									if(
										(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"][$key] && $post_data["new_file"]["size"][$key] > 0) || 
										($post_data["new_file"]["size"][$key] > 0 && $value === $post_data["cur_file_name"][$key] && isset($post_data["cur_file"][$key])) || 
										($post_data["new_file"]["size"][$key] > 0 && !isset($post_data["cur_file"][$key]))){ // upload new file
											if(isset($post_data["cur_file"][$key]) && $post_data["cur_file"][$key] != ""){
												if(unlink($structure."/".$post_data["cur_file"][$key])){
													if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$files["new_file"]["name"][$key])) $errorUpload++;
												}else $errorDelete++;
											}else{
												if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$files["new_file"]["name"][$key])) $errorUpload++;
											}
									}
								}
							}
						}
						$erros = $errorDelete + $errorRename + $errorUpload + $errorFormat;
						if($erros > 0) $res = new ModelResponse($ret->getCode(), "Successfully update employee but something<br>went wrong uploading files.");
						else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}else{
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}
			} else {
				$res = new ModelResponse();
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function employeePDSAccess(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new EmployeesCollection();
				if($ret->givePDSAccess($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function activateEmployees(){
		$result = array();
		$page = 'activateEmployees';
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
				$ret =  new EmployeesCollection();
				if($ret->activeRows($post_data)) {
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

	public function deactivateEmployees(){
		$result = array();
		$page = 'deactivateEmployees';
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
				$ret =  new EmployeesCollection();
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

	public function activateEmployeeAllowances(){
		$result = array();
		$page = 'activateEmployeeAllowances';
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
				$ret =  new EmployeesCollection();
				if($ret->activeAllowancesRows($post_data)) {
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

	public function deactivateEmployeeAllowances(){
		$result = array();
		$page = 'deactivateEmployeeAllowances';
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
				$ret =  new EmployeesCollection();
				if($ret->inactiveAllowancesRows($post_data)) {
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

	public function getActiveEmployees(){
		$post_data = $this->input->post();
		// var_dump($post_data); die();
		$result = array();
		$page = 'getActiveEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$ret =  new EmployeesCollection();
			if($ret->hasRowsByDivision($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['employee_id_number'] = $this->Helper->decrypt($v1['employee_id_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    				$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    				$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function getActiveEmployeesByPayBasis(){
		$result = array();
		$page = 'getActiveEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new EmployeesCollection();
			if($ret->hasRows()) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['employee_id_number'] = $this->Helper->decrypt($v1['employee_id_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    				$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    				$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getActivePhotoByEmployeeId(){
		$result = array();
		$page = 'getActivePhotoByEmployeeId';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret =  new EmployeesCollection();
			if($ret->hasRowsPhotos($employee_id)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getEmployeesById(){
		$result = array();
		$page = 'getEmployeesById';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			if(isset($_GET['Id']) && $_GET['Id'] != null){
				$ret =  new EmployeesCollection();
				if($ret->hasRowsById()) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}else $res = new ModelResponse();
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_id_number'] = $this->Helper->decrypt($v1['employee_id_number'],$v1['id']);
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    				$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    				$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	function getEmployeeList(){
		$employee_sort = array();
		$employees = @$this->EmployeesCollection->getEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific'], $_POST['leave_grouping_id'], $_POST['payroll_grouping_id']);
		foreach ($employees as $k => $value) {
			$employees[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$employees[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$employees[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$employees[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$employees[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$employee_sort[$k] = $employees[$k]['last_name'];
		}
		array_multisort($employee_sort, SORT_ASC, $employees);
		$formData['list'] = $employees;
		$formData['key'] = "viewEmployees";
		$result['table'] = $this->load->view('helpers/employeechecklist.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}

	function getregularEmployeeList(){
		$employee_sort = array();
		$employees = @$this->EmployeesCollection->getRegularEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific'], $_POST['leave_grouping_id'], $_POST['payroll_grouping_id']);
		foreach ($employees as $k => $value) {
			$employees[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$employees[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$employees[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$employees[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$employees[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$employee_sort[$k] = $employees[$k]['last_name'];
		}
		array_multisort($employee_sort, SORT_ASC, $employees);
		$formData['list'] = $employees;
		$formData['key'] = "viewEmployees";
		$result['table'] = $this->load->view('helpers/employeechecklist.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}

	public function exportEmployeesCSVFile(){
		$formData = array();
		$this->load->library('simple_html_dom');
		$ret =  new EmployeesCollection();
		if($ret->hasRows()) $res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		$data = json_decode($res,true);
		if(isset($data['Data'])){
			foreach ($data['Data']['details'] as $k1 => $v1) {
				$data['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
				$data['Data']['details'][$k1]['employee_id_number'] = $this->Helper->decrypt($v1['employee_id_number'],$v1['id']);
				$data['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
				$data['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
				$data['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
			}
		}
		$formData['list'] = $data;
		$viewData['table'] = $this->load->view('helpers/employeescsv.php', $formData, TRUE);
		$html = str_get_html($viewData['table']);
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename=employees.csv');
		$fp = fopen("php://output", "w");
		foreach($html->find('tr') as $element){
			$element->getAllAttributes();
			$td = array();
			foreach( $element->find('td') as $row){
				$td [] = $row->innertext;
			}
			fputcsv($fp, $td);
		}
		fclose($fp);
	}

}

?>
