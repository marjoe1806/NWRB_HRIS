<?php

class Employees extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeesCollection');
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
			Helper::setTitle('Employees');
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
        $fetch_data = $this->EmployeesCollection->make_datatables();  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
            $sub_array = array();    
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->employee_number;
            $sub_array[] = $row->last_name.', '.$row->first_name;  
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = $row->location_name;
            $status_color = "text-danger";
            if($row->employment_status == "Active")
            	$status_color = "text-success";
            $sub_array[] = '<b><span class="'.$status_color.'">'.strtoupper($row->employment_status).'</span><b>';
            $sub_array[] = date("F d, Y", strtotime($row->date_created));
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            if(Helper::role(ModuleRels::EMPLOYEE_VIEW_DETAILS)): 
            $buttons .= ' <a id="viewEmployeesForm" ' 
            		  . ' class="viewEmployeesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewEmployeesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::EMPLOYEE_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateEmployeesForm" ' 
            		  . ' class="updateEmployeesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->EmployeesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->EmployeesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function addEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewEmployeeAllowancesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewEmployeeAllowances';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			//var_dump($this->input->post('id'));die();
			$ret = new EmployeesCollection();
			if($ret->hasRowsAllowances($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/employeeallowancesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addEmployeeAllowances(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addEmployeeAllowances';
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
				if($ret->addAllowancesRows($post_data)) {
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
	public function updateEmployeeAllowances(){
		//var_dump($_POST);die();
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
		//var_dump($_POST);die();
		$result = array();
		$page = 'addEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					if($k != "employee_id_photo"){
						$post_data[$k] = $this->input->post($k,true);
					}
				}
				//var_dump($_FILES);die();
				//ini_get("allow_url_fopen");
				/*file_put_contents(FCPATH.'assets/employee-photos', $_FILES["blob"]);*/
				//var_dump(file_get_contents($temp_name));die();
				$ret =  new EmployeesCollection();
				
				//Add Employee
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				
			}
			else
			{
				$res = new ModelResponse();
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function updateEmployees(){

		//var_dump($_POST);die();
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
		$result = array();
		$page = 'getActiveEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new EmployeesCollection();
			if($ret->hasRows()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				//var_dump($res);die();
				
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
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
			if($ret->hasRows()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				//var_dump($res);die();
				
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
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
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			// var_dump($_POST);die();
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret =  new EmployeesCollection();
			if($ret->hasRowsPhotos($employee_id)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				//var_dump($res);die();
				
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
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new EmployeesCollection();
				if($ret->hasRowsById()) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				
			}
			else
			{
				$res = new ModelResponse();
			}
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
		// var_dump($_POST);die();
		$employees = @$this->EmployeesCollection->getEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific']);
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
		if($ret->hasRows()) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			// var_dump($res);die();
			
		} else {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
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
		// Helper::setTitle('Employees');
		// Helper::setMenu('templates/menu_template');
		// Helper::setView('employees',$viewData,FALSE);
		// Helper::setTemplate('templates/master_template');
		$html = str_get_html($viewData['table']);
		//var_dump($html);die();
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename=employees.csv');

		$fp = fopen("php://output", "w");
		foreach($html->find('tr') as $element)
		{
			$element->getAllAttributes();
			$td = array();
			foreach( $element->find('td') as $row)
			{
				$td [] = $row->innertext;

			}
			fputcsv($fp, $td);
		}

		fclose($fp);
	}

}

?>