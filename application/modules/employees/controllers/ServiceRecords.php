<?php

class ServiceRecords extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ServiceRecordsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->database();
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::EMPLOYEES_SERVICE_RECORDS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayslip";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/servicerecordlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Service Records');
			Helper::setMenu('templates/menu_template');
			Helper::setView('servicerecords',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} 
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function is_arr($arr){
		$odds = array();
		$evens = array();
		foreach($arr as $k => $v){
			if($v%2 === 0){
				array_push($odds,$v);
			}else{
				array_push($evens,$v);
			}
		}
		if(sizeof($odds) === 1){
			return "Should return: " . $odds[0] . "(the only odd number)";
		}else if(sizeof($evens) === 1){
			return "Should return: " . $evens[0] . "(the only even number)";
		}else{
			return "Array should expect for a one integer either odd / even number";
		}
	}

	public function is_isogram($string){
		if($string == "")
			return true;
		else{
			$charLength = strlen($string);
			$tmpArr = array();
			$tmpArr = str_split($string);
			$i = 0;
			foreach($tmpArr as $k => $v){
				if(ctype_upper($v)){
					return false;
				}else if(substr_count($string,$v) > 1){
					return false;
				}else if($charLength === $k && substr_count($string,$v) == 0){
					return true;
				}else{
					continue;
				}
			}
			return true;
		}
	}

	public function fetchRows() {
        if ($this->input->is_ajax_request()) {
            $division_id = $this->input->post('DivisionId');
            $employment_status = $this->input->post('EmploymentStatus');

            $fetch_data = $this->ServiceRecordsCollection->make_datatables($division_id, $employment_status);
			//var_dump($fetch_data);die();
            $data = array();

            foreach ($fetch_data as $row) {
                $buttons = "";
                $buttons_data = "";
                $emp_id = $row->salt;
				$department_name = (isset($row->department_name)) ? $row->department_name : "NA";
				$position_name =  (isset($row->position_name)) ? $row->position_name : "NA";
                $emp_id_num = (isset($row->emp_number)) ? $row->emp_number : "NA";
                $row->first_name = $this->Helper->decrypt((string)$row->first_name, $emp_id);
                $row->middle_name = $this->Helper->decrypt((string)$row->middle_name, $emp_id);
                $row->last_name = $this->Helper->decrypt((string)$row->last_name, $emp_id);
                $row->extension = $this->Helper->decrypt((string)$row->extension, $emp_id);

                $buttons .= ' <a class="viewServiceRecord" data-employee_id="' . $emp_id . '" style="text-decoration: none;" '
                        . ' href="' . base_url() . $this->uri->segment(1) . '/ServiceRecords/viewServiceRecord" > '
                        . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Service Record">'
                        . ' <i class="material-icons">remove_red_eye</i> '
                        . ' </button> '
                        . ' </a> ';

                $buttons .= ' <a class="addServiceRecord" data-employee_id="' . $emp_id . '" style="text-decoration: none;" '
                        . ' href="' . base_url() . $this->uri->segment(1) . '/ServiceRecords/addServiceRecordForm" > '
                        . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Add Service Record">'
                        . ' <i class="material-icons">add</i> '
                        . ' </button>'
                        . ' </a> ';

                $sub_array = array($buttons, $emp_id_num, $row->last_name . ', ' . $row->first_name . ' ' . $row->middle_name . ' ' . $row->extension, $position_name, $department_name, number_format($row->salary, 2), $row->employment_status);
                $data[] = $sub_array;
            }

            $output = array(
                "draw" => intval($this->input->post("draw")),
                "recordsTotal" => $this->ServiceRecordsCollection->get_all_data(),
                "recordsFiltered" => $this->ServiceRecordsCollection->get_filtered_data($division_id, $employment_status),
                "data" => $data
            );

            echo json_encode($output);
        } else {
            show_404();
        }
    }
	
	public function viewServiceRecord(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewServiceRecord';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$emp_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret = $this->ServiceRecordsCollection->get_service_record($emp_id);
			//var_dump($ret);die();
			
			$formData['key'] = $result['key']; 
			if(sizeof($ret['employee']) > 0){
				$formData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$emp_id);
				$formData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$emp_id);
				$formData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$emp_id);
				$formData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$emp_id);
				$formData['employee']['extension'] = $this->Helper->decrypt($ret['employee']['extension'],$emp_id);
				$formData['employee']['birthday'] = date("F j, Y", strtotime($ret['employee']['birthday']));
				$formData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$formData['employee']['gender'] = $ret['employee']['gender'];
				$formData['employee']['civil_status'] = $ret['employee']['civil_status'];
				$formData['employee']['maiden_last_name'] = $ret['employee']['mother_last_name'];//maiden_name
				$formData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$emp_id);
			} 
			$formData["Experience"] = $ret["experience"]; 
			$formData['signatories'] = $this->ServiceRecordsCollection->get_signatories();
			$data["Data"] = $formData;
			//var_dump($formData["Experience"]); 
			$result['form'] = $this->load->view('helpers/employeeservicerecord.php', $data, TRUE);
			$result['data'] = $formData['employee'];
		}
		echo json_encode($result); 
	}
	// Edit service Record
	public function editServiceRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'editServiceRecord';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$emp_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret = $this->ServiceRecordsCollection->get_edit_service_record($emp_id);
			if(sizeof($ret['employee']) > 0){
				$formData['employee']['employee_id'] = $emp_id;
				$formData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$emp_id);
				$formData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$emp_id);
				$formData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$emp_id);
				$formData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$emp_id);
				$formData['employee']['extension'] = $this->Helper->decrypt($ret['employee']['extension'],$emp_id);
				$formData['employee']['birthday'] = date("F j, Y", strtotime($ret['employee']['birthday']));
				$formData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$formData['employee']['gender'] = $ret['employee']['gender'];
				$formData['employee']['civil_status'] = $ret['employee']['civil_status'];
				$formData['employee']['maiden_last_name'] = $ret['employee']['mother_last_name'];//maiden_name
				$formData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$emp_id);
			}
			$formData["Experience"] = $ret["experience"];
			 //var_dump($formData["Experience"]);
			$formData['signatories'] = $this->ServiceRecordsCollection->get_signatories();
			$data["Data"] = $formData;
			//var_dump($data["Data"]);
			$data['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeeditservicerecord.php', $data, TRUE);
			$result['data'] = $formData['employee'];
		
			
		}
		echo json_encode($result);

	} 
	// End of Edit Service Record 

	// Update Service Recocord
	public function editServiceRecord(){
		$result = array();
		$page = 'editServiceRecord';
		if (!$this->input->is_ajax_request()) {
			show_404();
		 } else {
 
			 if($this->input->post() && $this->input->post() != null) {
				 $post_data = array();
				 
				 foreach ($this->input->post() as $k => $v) {
					 if($this->input->post($k,true) != "" || $this->input->post($k,true) != NULL){
						 //var_dump($this->input->post($k,true)).die();
						 // if($post_data[$k])
						 $post_data[$k] = $this->input->post($k,true);
					 }
				 }
				 //var_dump($post_data).die();
 
				 $ret =  new ServiceRecordsCollection();
				 if($ret->addServiceRecord($post_data)) {
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
		// if (!$this->input->is_ajax_request()) {
		//    show_404();
		// } else {

		// 	if($this->input->post() && $this->input->post() != null) {
		// 		$id = $this->input->post('id');
		// 		//var_dump($id).die();
		// 		$work_from =  $this->input->post('work_from');
		// 		$work_to =  $this->input->post('work_to');
		// 		$position =  $this->input->post('position');
		// 		$status_appointment =  $this->input->post('status_appointment');
		// 		$company =  $this->input->post('company');
		// 		$monthly_salary = str_replace(".00", "", $this->input->post('monthly_salary'));
		// 		$branch =  $this->input->post('branch');
		// 		$lv_abs_wo_pay =  $this->input->post('lv_abs_wo_pay');
		// 		$seperation_date=  $this->input->post('seperation_date');
		// 		$seperation_cause =  $this->input->post('seperation_cause');

				 
		// 		$ret =  new ServiceRecordsCollection();
		// 		// print_r($post_data); die(); 
		// 		if($ret->addServiceRecord($id, $work_from, $work_to, $position, $status_appointment,$monthly_salary, $company, $branch, $lv_abs_wo_pay, $seperation_date, $seperation_cause )) {
		// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 		} else {
		// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 		}
		// 		$result = json_decode($res,true);
		// 	} else {
		// 		$res = new ModelResponse();
		// 		$result = json_decode($res,true);
		// 	}
		// 	$result['key'] = $page;
		// }
		// echo json_encode($result);
	
	  }
	// end Update Service Recocord

	  public function addServiceRecordForm(){
		$formData = array();
		$result = array(); 
		$result['key'] = 'addServiceRecord';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$emp_id = isset($_POST['employee_id'])?$_POST['employee_id']:""; 
			$ret = $this->ServiceRecordsCollection->get_edit_service_record($emp_id);
			//var_dump($ret['employee']['salary']);
			
			if(sizeof($ret['employee']) > 0){
				
				$formData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$emp_id);
				$formData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$emp_id);
				$formData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$emp_id);
				$formData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$emp_id);
				$formData['employee']['extension'] = $this->Helper->decrypt($ret['employee']['extension'],$emp_id);
				$formData['employee']['birthday'] = date("F j, Y", strtotime($ret['employee']['birthday']));
				$formData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$formData['employee']['gender'] = $ret['employee']['gender'];
				$formData['employee']['civil_status'] = $ret['employee']['civil_status'];
				$formData['employee']['maiden_last_name'] = $ret['employee']['mother_last_name'];//maiden_name
				$formData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$emp_id);
				$formData['employee']['employee_id'] = $emp_id;
				$formData['employee']['salary'] = $ret['employee']['salary'];
			}
			
			$formData["Experience"] = $ret["experience"];
			$formData['signatories'] = $this->ServiceRecordsCollection->get_signatories();
			$data["Data"] = $formData;
			$data['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeaddservicerecord.php', $data, TRUE);
			$result['data'] = $formData['employee'];
		
			
		}
		echo json_encode($result);
	  }
 
	  public function addServiceRecord(){
		//var_dump($this->input->post('id')).die();
		$result = array();
		$page = 'addServiceRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {

			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				
				foreach ($this->input->post() as $k => $v) {
					if($this->input->post($k,true) != "" || $this->input->post($k,true) != NULL){
						//var_dump($this->input->post($k,true)).die();
						// if($post_data[$k])
						$post_data[$k] = $this->input->post($k,true);
					}
				}
				//var_dump($post_data).die();

				$ret =  new ServiceRecordsCollection();
				if($ret->addServiceRecord($post_data)) {
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

	  public function deleteServiceRecord(){
		$result = array();
		$page = 'deleteServiceRecord';
		$id = $this->input->post('id');
		if (!$this->input->is_ajax_request()) {
		   show_404();

		} else {
			if($this->input->post() && $this->input->post() != null) {

				$ret =  new ServiceRecordsCollection();
				if($ret->deleteServiceRecord($id)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}

				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	  }

	public function getReportSignatories(){
		$signatories = $this->ServiceRecordsCollection->get_signatories();
		$data["signatories"] = $signatories;
		echo json_encode($data);
	}	

 
}

?>
